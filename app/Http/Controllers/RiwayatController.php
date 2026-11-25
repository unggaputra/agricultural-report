<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RiwayatController extends Controller
{
    /**
     * Aturan validasi yang dipakai bersama oleh store() dan update().
     */
    private function rules(): array
    {
        return [
            'id_lahan'        => 'required|exists:lahan,id_lahan',
            'id_bibit'        => 'required|exists:bibit,id_bibit',
            'jumlah_bibit_kg' => 'required|numeric|min:0',
            'musim'           => 'nullable|in:MT1,MT2,MT3',
            'status'          => 'required|in:tanam,panen,gagal',
            'tanggal_tanam'   => 'required|date',
            'tanggal_panen'   => 'nullable|date|after_or_equal:tanggal_tanam|required_if:status,panen',
            'hasil_kg'        => 'nullable|integer|min:0|required_if:status,panen',
            'alasan_gagal'    => 'nullable|string|max:100|required_if:status,gagal',
            'pupuk'                => 'required|array|min:1',
            'pupuk.*.id_pupuk'     => 'required|exists:pupuk,id_pupuk',
            'pupuk.*.jumlah_kg'    => 'required|numeric|min:0',
        ];
    }

    /**
     * Susun data untuk tabel riwayat_tanam sesuai status.
     */
    private function dataTanam(array $d): array
    {
        return [
            'id_lahan'        => $d['id_lahan'],
            'id_bibit'        => $d['id_bibit'],
            'jumlah_bibit_kg' => $d['jumlah_bibit_kg'],
            'musim'           => $d['musim'] ?? null,
            'status'          => $d['status'],
            'tanggal_tanam'   => $d['tanggal_tanam'],
            'tanggal_panen'   => $d['status'] === 'panen' ? $d['tanggal_panen'] : null,
            'hasil_kg'        => $d['status'] === 'tanam' ? null : ($d['hasil_kg'] ?? 0),
            'alasan_gagal'    => $d['status'] === 'gagal' ? $d['alasan_gagal'] : null,
        ];
    }

    private function simpanPupuk(int $idRiwayat, array $daftarPupuk): void
    {
        foreach ($daftarPupuk as $p) {
            DB::table('riwayat_tanam_pupuk')->insert([
                'id_riwayat_tanam' => $idRiwayat,
                'id_pupuk'         => $p['id_pupuk'],
                'jumlah_kg'        => $p['jumlah_kg'],
            ]);
        }
    }

    public function store(Request $request)
    {
        $data = $request->validate($this->rules());

        DB::transaction(function () use ($data) {
            $id = DB::table('riwayat_tanam')->insertGetId($this->dataTanam($data));
            $this->simpanPupuk($id, $data['pupuk']);
        });

        return redirect('/dashboard')->with('success', 'Riwayat tanam ditambahkan.');
    }

    public function edit($id)
    {
        $riwayat = DB::table('riwayat_tanam')->where('id_riwayat_tanam', $id)->first();
        abort_if(!$riwayat, 404);

        $pupukDipakai = DB::table('riwayat_tanam_pupuk')
            ->where('id_riwayat_tanam', $id)
            ->get();

        $lahan = DB::table('lahan')
            ->join('petani', 'lahan.id_petani', '=', 'petani.id_petani')
            ->join('lokasi', 'lahan.id_lokasi', '=', 'lokasi.id_lokasi')
            ->join('desa', 'lokasi.id_desa', '=', 'desa.id_desa')
            ->select('lahan.*', 'petani.nama_petani', 'lokasi.nama_lokasi', 'desa.nama_desa')
            ->orderBy('petani.nama_petani')
            ->get();

        $bibit = DB::table('bibit')->get();
        $pupuk = DB::table('pupuk')->get();

        return view('Edit.editriwayat', [
            'riwayat'      => $riwayat,
            'pupukDipakai' => $pupukDipakai,
            'lahan'        => $lahan,
            'bibit'        => $bibit,
            'pupuk'        => $pupuk,
        ]);
    }

    public function update(Request $request)
    {
        $request->validate(['id' => 'required|exists:riwayat_tanam,id_riwayat_tanam']);
        $data = $request->validate($this->rules());
        $id = (int) $request->id;

        DB::transaction(function () use ($data, $id) {
            DB::table('riwayat_tanam')
                ->where('id_riwayat_tanam', $id)
                ->update($this->dataTanam($data));

            // Ganti seluruh daftar pupuk dengan yang baru dikirim dari form
            DB::table('riwayat_tanam_pupuk')->where('id_riwayat_tanam', $id)->delete();
            $this->simpanPupuk($id, $data['pupuk']);
        });

        return redirect('/dashboard')->with('success', 'Riwayat tanam diperbarui.');
    }

    public function delete($id)
    {
        DB::transaction(function () use ($id) {
            DB::table('riwayat_tanam_pupuk')->where('id_riwayat_tanam', $id)->delete();
            DB::table('riwayat_tanam')->where('id_riwayat_tanam', $id)->delete();
        });

        return redirect('/dashboard')->with('success', 'Riwayat tanam dihapus.');
    }
}