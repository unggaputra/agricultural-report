<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function view()
    {
        $petani = DB::table('petani')->get();
        $pupuk  = DB::table('pupuk')->get();
        $bibit  = DB::table('bibit')->get();
        $desa   = DB::table('desa')->get();

        $lokasi = DB::table('lokasi')
            ->join('desa', 'lokasi.id_desa', '=', 'desa.id_desa')
            ->select('lokasi.*', 'desa.nama_desa')
            ->get();

        $lahan = DB::table('lahan')
            ->join('petani', 'lahan.id_petani', '=', 'petani.id_petani')
            ->join('lokasi', 'lahan.id_lokasi', '=', 'lokasi.id_lokasi')
            ->join('desa', 'lokasi.id_desa', '=', 'desa.id_desa')
            ->select('lahan.*', 'petani.nama_petani', 'lokasi.nama_lokasi', 'desa.nama_desa')
            ->get();

        $riwayat_pembelian_pupuk = DB::table('riwayat_pembelian_pupuk')
            ->join('petani', 'riwayat_pembelian_pupuk.id_petani', '=', 'petani.id_petani')
            ->join('pupuk', 'riwayat_pembelian_pupuk.id_pupuk', '=', 'pupuk.id_pupuk')
            ->select(
                'riwayat_pembelian_pupuk.*',
                'petani.nama_petani',
                'pupuk.nama_pupuk',
                'pupuk.jenis_pupuk',
                'pupuk.kg_per_sak'
            )
            ->orderByDesc('riwayat_pembelian_pupuk.tanggal_beli')
            ->get();

        $riwayat_tanam = DB::table('riwayat_tanam')
            ->join('lahan', 'riwayat_tanam.id_lahan', '=', 'lahan.id_lahan')
            ->join('petani', 'petani.id_petani', '=', 'lahan.id_petani')
            ->join('bibit', 'riwayat_tanam.id_bibit', '=', 'bibit.id_bibit')
            ->join('lokasi', 'lahan.id_lokasi', '=', 'lokasi.id_lokasi')
            ->join('desa', 'lokasi.id_desa', '=', 'desa.id_desa')
            ->select(
                'riwayat_tanam.*',
                'petani.nama_petani',
                'lokasi.nama_lokasi',
                'desa.nama_desa',
                'bibit.nama_bibit',
                DB::raw("(SELECT GROUP_CONCAT(CONCAT(pk.nama_pupuk, ' ', rtp.jumlah_kg, ' kg') SEPARATOR ', ')
                          FROM riwayat_tanam_pupuk rtp
                          JOIN pupuk pk ON pk.id_pupuk = rtp.id_pupuk
                          WHERE rtp.id_riwayat_tanam = riwayat_tanam.id_riwayat_tanam) AS pupuk_dipakai")
            )
            ->orderByDesc('riwayat_tanam.tanggal_tanam')
            ->get();

        return view('Contents.edit', [
            'petani' => $petani,
            'pupuk' => $pupuk,
            'bibit' => $bibit,
            'desa' => $desa,
            'lokasi' => $lokasi,
            'lahan' => $lahan,
            'riwayat_pembelian_pupuk' => $riwayat_pembelian_pupuk,
            'riwayat_tanam' => $riwayat_tanam,
        ]);
    }

    public function getData()
    {
        $data = DB::table('riwayat_tanam')->limit(6)->get();
        return response()->json($data);
    }

    /**
     * Halaman report / dashboard grafik.
     * Filter lewat query string: ?tahun=2024&tanaman=Padi
     */
    public function chart(Request $request)
    {
        // ---------- Pilihan filter ----------
        $tahunList = DB::table('riwayat_tanam')
            ->selectRaw('YEAR(tanggal_tanam) as tahun')
            ->distinct()
            ->orderByDesc('tahun')
            ->pluck('tahun')
            ->map(fn ($t) => (int) $t)
            ->all();

        $tanamanList = DB::table('bibit')
            ->distinct()
            ->orderBy('jenis_tanaman')
            ->pluck('jenis_tanaman')
            ->all();

        $tahun = (int) $request->query('tahun');
        $tahun = in_array($tahun, $tahunList, true) ? $tahun : null;

        $tanaman = $request->query('tanaman');
        $tanaman = in_array($tanaman, $tanamanList, true) ? $tanaman : null;

        // Tahun yang masih punya tanaman berstatus "tanam" -> datanya belum lengkap
        $tahunSebagian = DB::table('riwayat_tanam')
            ->where('status', 'tanam')
            ->selectRaw('MAX(YEAR(tanggal_tanam)) as t')
            ->value('t');
        $tahunSebagian = $tahunSebagian ? (int) $tahunSebagian : null;

        // ---------- Kartu ringkasan ----------
        $ringkas = $this->panenBase($tahun, $tanaman)
            ->selectRaw('b.jenis_tanaman, SUM(rt.hasil_kg) as hasil, SUM(l.luas) as luas')
            ->groupBy('b.jenis_tanaman')
            ->get()
            ->keyBy('jenis_tanaman');

        $produktivitas = function (string $jenis) use ($ringkas) {
            if (!isset($ringkas[$jenis]) || $ringkas[$jenis]->luas <= 0) {
                return null;
            }
            return (int) round($ringkas[$jenis]->hasil / ($ringkas[$jenis]->luas / 10000));
        };

        $sedangTanam = DB::table('riwayat_tanam as rt')
            ->join('lahan as l', 'l.id_lahan', '=', 'rt.id_lahan')
            ->where('rt.status', 'tanam')
            ->selectRaw('COALESCE(SUM(l.luas), 0) as luas, COUNT(DISTINCT l.id_petani) as petani')
            ->first();

        $kartu = [
            'total_hasil'     => (int) $ringkas->sum('hasil'),
            'prod_padi'       => $produktivitas('Padi'),
            'prod_jagung'     => $produktivitas('Jagung'),
            'lahan_tanam_ha'  => round($sedangTanam->luas / 10000, 2),
            'petani_tanam'    => (int) $sedangTanam->petani,
        ];

        // ---------- 1. Produktivitas per tahun (abaikan filter tahun) ----------
        $trendRows = $this->panenBase(null, $tanaman)
            ->selectRaw('YEAR(rt.tanggal_tanam) as tahun, b.jenis_tanaman,
                         SUM(rt.hasil_kg) / (SUM(l.luas) / 10000) as kg_per_ha')
            ->groupBy('tahun', 'b.jenis_tanaman')
            ->orderBy('tahun')
            ->get();

        $tahunTrend = $trendRows->pluck('tahun')->map(fn ($t) => (int) $t)->unique()->sort()->values()->all();
        $trend = [
            'labels' => array_map(
                fn ($t) => $t === $tahunSebagian ? "$t (sebagian)" : (string) $t,
                $tahunTrend
            ),
            'datasets' => $this->seriesByTanaman($trendRows, 'tahun', $tahunTrend),
        ];

        // ---------- 2. Produktivitas per desa ----------
        $desaRows = $this->panenBase($tahun, $tanaman)
            ->selectRaw('d.nama_desa, b.jenis_tanaman,
                         SUM(rt.hasil_kg) / (SUM(l.luas) / 10000) as kg_per_ha')
            ->groupBy('d.nama_desa', 'b.jenis_tanaman')
            ->get();

        $desaLabels = $desaRows->pluck('nama_desa')->unique()->sort()->values()->all();
        $perDesa = [
            'labels'   => $desaLabels,
            'datasets' => $this->seriesByTanaman($desaRows, 'nama_desa', $desaLabels),
        ];

        // ---------- 3. Perbandingan varietas bibit ----------
        $varietasRows = $this->panenBase($tahun, $tanaman)
            ->selectRaw('b.nama_bibit, b.jenis_tanaman, COUNT(*) as jumlah,
                         SUM(rt.hasil_kg) / (SUM(l.luas) / 10000) as kg_per_ha')
            ->groupBy('b.id_bibit', 'b.nama_bibit', 'b.jenis_tanaman')
            ->orderBy('b.jenis_tanaman')
            ->orderByDesc('kg_per_ha')
            ->get();

        $varietas = [
            'labels' => $varietasRows->map(fn ($r) => "{$r->nama_bibit} ({$r->jenis_tanaman})")->all(),
            'data'   => $varietasRows->map(fn ($r) => (int) round($r->kg_per_ha))->all(),
            'jenis'  => $varietasRows->pluck('jenis_tanaman')->all(),
            'jumlah' => $varietasRows->pluck('jumlah')->all(),
        ];

        // ---------- 4. Pupuk dibeli vs dipakai per tahun (abaikan filter) ----------
        $pupukRows = DB::table('v_pupuk_tahunan')
            ->selectRaw('tahun, SUM(dibeli_kg) as dibeli_kg, SUM(dipakai_kg) as dipakai_kg')
            ->groupBy('tahun')
            ->orderBy('tahun')
            ->get();

        $pupukChart = [
            'labels' => $pupukRows->map(fn ($r) => (string) $r->tahun)->all(),
            'dibeli' => $pupukRows->map(fn ($r) => (int) round($r->dibeli_kg))->all(),
            'dipakai' => $pupukRows->map(fn ($r) => (int) round($r->dipakai_kg))->all(),
        ];

        // ---------- 5. Gagal panen per alasan ----------
        $gagalQuery = DB::table('riwayat_tanam as rt')
            ->join('bibit as b', 'b.id_bibit', '=', 'rt.id_bibit')
            ->where('rt.status', 'gagal');
        if ($tahun)   $gagalQuery->whereYear('rt.tanggal_tanam', $tahun);
        if ($tanaman) $gagalQuery->where('b.jenis_tanaman', $tanaman);

        $gagalRows = $gagalQuery
            ->selectRaw("COALESCE(rt.alasan_gagal, 'Tidak diketahui') as alasan, COUNT(*) as jumlah")
            ->groupBy('alasan')
            ->orderByDesc('jumlah')
            ->get();

        $gagal = [
            'labels' => $gagalRows->pluck('alasan')->all(),
            'data'   => $gagalRows->map(fn ($r) => (int) $r->jumlah)->all(),
        ];

        // ---------- 6. Scatter: dosis pupuk per ha vs hasil per ha ----------
        $scatterRows = $this->panenBase($tahun, $tanaman)
            ->selectRaw("b.jenis_tanaman,
                rt.hasil_kg / (l.luas / 10000) as hasil_ha,
                (SELECT COALESCE(SUM(rtp.jumlah_kg), 0)
                   FROM riwayat_tanam_pupuk rtp
                  WHERE rtp.id_riwayat_tanam = rt.id_riwayat_tanam) / (l.luas / 10000) as pupuk_ha")
            ->get();

        $scatter = [];
        foreach ($scatterRows->groupBy('jenis_tanaman') as $jenis => $rows) {
            $scatter[] = [
                'label' => $jenis,
                'data'  => $rows->map(fn ($r) => [
                    'x' => (int) round($r->pupuk_ha),
                    'y' => (int) round($r->hasil_ha),
                ])->values()->all(),
            ];
        }

        return view('Contents.report', [
            'tahunList'     => $tahunList,
            'tanamanList'   => $tanamanList,
            'tahun'         => $tahun,
            'tanaman'       => $tanaman,
            'tahunSebagian' => $tahunSebagian,
            'kartu'         => $kartu,
            'chart' => [
                'trend'    => $trend,
                'perDesa'  => $perDesa,
                'varietas' => $varietas,
                'pupuk'    => $pupukChart,
                'gagal'    => $gagal,
                'scatter'  => $scatter,
            ],
        ]);
    }

    /**
     * Query dasar: semua tanam berstatus "panen" lengkap dengan lahan, desa, dan bibit.
     */
    private function panenBase(?int $tahun, ?string $tanaman)
    {
        $q = DB::table('riwayat_tanam as rt')
            ->join('lahan as l', 'l.id_lahan', '=', 'rt.id_lahan')
            ->join('lokasi as lo', 'lo.id_lokasi', '=', 'l.id_lokasi')
            ->join('desa as d', 'd.id_desa', '=', 'lo.id_desa')
            ->join('bibit as b', 'b.id_bibit', '=', 'rt.id_bibit')
            ->where('rt.status', 'panen');

        if ($tahun)   $q->whereYear('rt.tanggal_tanam', $tahun);
        if ($tanaman) $q->where('b.jenis_tanaman', $tanaman);

        return $q;
    }

    /**
     * Ubah baris (jenis_tanaman, <labelKey>, kg_per_ha) menjadi dataset per tanaman
     * yang sejajar dengan $labels (nilai kosong = null).
     */
    private function seriesByTanaman($rows, string $labelKey, array $labels): array
    {
        $datasets = [];
        foreach ($rows->groupBy('jenis_tanaman') as $jenis => $group) {
            $map = $group->pluck('kg_per_ha', $labelKey);
            $datasets[] = [
                'label' => $jenis,
                'data'  => collect($labels)
                    ->map(fn ($l) => $map->has($l) ? (int) round($map[$l]) : null)
                    ->all(),
            ];
        }
        return $datasets;
    }
}