@extends('dashboard')
@section('Content')
<div class="content">

    {{-- ================= FILTER ================= --}}
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <form method="GET" class="form-inline">
                        <label class="mr-2">Tahun</label>
                        <select name="tahun" class="form-control mr-3" onchange="this.form.submit()">
                            <option value="">Semua tahun</option>
                            @foreach ($tahunList as $t)
                                <option value="{{ $t }}" @selected($tahun === $t)>
                                    {{ $t }}{{ $t === $tahunSebagian ? ' (sebagian)' : '' }}
                                </option>
                            @endforeach
                        </select>

                        <label class="mr-2">Tanaman</label>
                        <select name="tanaman" class="form-control mr-3" onchange="this.form.submit()">
                            <option value="">Semua tanaman</option>
                            @foreach ($tanamanList as $j)
                                <option value="{{ $j }}" @selected($tanaman === $j)>{{ $j }}</option>
                            @endforeach
                        </select>

                        <a href="{{ url()->current() }}" class="btn btn-primary">Reset</a>
                    </form>
                    <small class="text-muted d-block mt-2">
                        Filter berlaku untuk kartu serta grafik per desa, varietas, hubungan pupuk-hasil, dan gagal panen.
                        Grafik tren tahunan dan pupuk selalu menampilkan semua tahun.
                        Hanya tanam berstatus <em>panen</em> yang dihitung sebagai hasil.
                    </small>
                </div>
            </div>
        </div>
    </div>

    {{-- ================= KARTU RINGKASAN ================= --}}
    <div class="row">
        <div class="col-lg-3 col-md-6 col-sm-6">
            <div class="card card-stats">
                <div class="card-body">
                    <div class="numbers text-left">
                        <p class="card-category">Total Hasil Panen</p>
                        <p class="card-title">{{ number_format($kartu['total_hasil'], 0, ',', '.') }} <small>kg</small></p>
                    </div>
                </div>
                <div class="card-footer"><hr><div class="stats">{{ $tahun ?? 'Semua tahun' }} · {{ $tanaman ?? 'Semua tanaman' }}</div></div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6">
            <div class="card card-stats">
                <div class="card-body">
                    <div class="numbers text-left">
                        <p class="card-category">Produktivitas Padi</p>
                        <p class="card-title">
                            {{ $kartu['prod_padi'] !== null ? number_format($kartu['prod_padi'], 0, ',', '.') : '-' }} <small>kg/ha</small>
                        </p>
                    </div>
                </div>
                <div class="card-footer"><hr><div class="stats">Hasil ÷ luas panen</div></div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6">
            <div class="card card-stats">
                <div class="card-body">
                    <div class="numbers text-left">
                        <p class="card-category">Produktivitas Jagung</p>
                        <p class="card-title">
                            {{ $kartu['prod_jagung'] !== null ? number_format($kartu['prod_jagung'], 0, ',', '.') : '-' }} <small>kg/ha</small>
                        </p>
                    </div>
                </div>
                <div class="card-footer"><hr><div class="stats">Hasil ÷ luas panen</div></div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6">
            <div class="card card-stats">
                <div class="card-body">
                    <div class="numbers text-left">
                        <p class="card-category">Lahan Sedang Ditanami</p>
                        <p class="card-title">{{ number_format($kartu['lahan_tanam_ha'], 2, ',', '.') }} <small>ha</small></p>
                    </div>
                </div>
                <div class="card-footer"><hr><div class="stats">Oleh {{ $kartu['petani_tanam'] }} petani (saat ini)</div></div>
            </div>
        </div>
    </div>

    {{-- ================= GRAFIK ================= --}}
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Produktivitas per Tahun</h5>
                    <p class="card-category">kg per hektar, padi dan jagung dipisah
                        @if ($tahunSebagian) · {{ $tahunSebagian }} baru sebagian musim @endif</p>
                </div>
                <div class="card-body"><div style="position:relative;height:300px"><canvas id="chartTrend"></canvas></div></div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Produktivitas per Desa</h5>
                    <p class="card-category">kg per hektar</p>
                </div>
                <div class="card-body"><div style="position:relative;height:300px"><canvas id="chartDesa"></canvas></div></div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Perbandingan Varietas Bibit</h5>
                    <p class="card-category">rata-rata kg per hektar</p>
                </div>
                <div class="card-body"><div style="position:relative;height:300px"><canvas id="chartVarietas"></canvas></div></div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Pembelian Pupuk vs Pemakaian</h5>
                    <p class="card-category">kg per tahun (sak dikonversi ke kg)</p>
                </div>
                <div class="card-body"><div style="position:relative;height:300px"><canvas id="chartPupuk"></canvas></div></div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Dosis Pupuk vs Hasil</h5>
                    <p class="card-category">tiap titik = satu musim tanam (kg per hektar)</p>
                </div>
                <div class="card-body"><div style="position:relative;height:300px"><canvas id="chartScatter"></canvas></div></div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Penyebab Gagal Panen</h5>
                    <p class="card-category">jumlah kejadian</p>
                </div>
                <div class="card-body"><div style="position:relative;height:300px"><canvas id="chartGagal"></canvas></div></div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
(function () {
    // Simpan referensi Chart.js v4 sekarang, kalau-kalau layout memuat versi lain sesudahnya
    var C = window.Chart;
    var D = @json($chart);

    var WARNA = { Padi: '#6bd098', Jagung: '#fbc658' };
    var CADANGAN = ['#51bcda', '#ef8157', '#9c7bd3', '#8d99ae', '#e76f8b', '#4a9d9c'];
    function warna(nama, i) { return WARNA[nama] || CADANGAN[i % CADANGAN.length]; }
    function angka(v) { return Number(v).toLocaleString('id-ID'); }

    function kosong(id) {
        var el = document.getElementById(id).parentNode;
        el.innerHTML = '<p class="text-muted text-center" style="padding-top:110px">Belum ada data untuk filter ini.</p>';
    }

    function sumbu(judul) {
        return {
            beginAtZero: true,
            title: { display: true, text: judul },
            ticks: { callback: function (v) { return angka(v); } }
        };
    }

    var dasar = {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { position: 'bottom' } }
    };

    function opsi(tambahan) { return Object.assign({}, dasar, tambahan || {}); }

    // 1. Produktivitas per tahun (garis)
    if (D.trend.labels.length) {
        new C(document.getElementById('chartTrend'), {
            type: 'line',
            data: {
                labels: D.trend.labels,
                datasets: D.trend.datasets.map(function (s, i) {
                    return { label: s.label, data: s.data, borderColor: warna(s.label, i),
                             backgroundColor: warna(s.label, i), tension: 0.3, spanGaps: true, pointRadius: 4 };
                })
            },
            options: opsi({ scales: { y: sumbu('kg/ha') } })
        });
    } else { kosong('chartTrend'); }

    // 2. Produktivitas per desa (batang berkelompok)
    if (D.perDesa.labels.length) {
        new C(document.getElementById('chartDesa'), {
            type: 'bar',
            data: {
                labels: D.perDesa.labels,
                datasets: D.perDesa.datasets.map(function (s, i) {
                    return { label: s.label, data: s.data, backgroundColor: warna(s.label, i) };
                })
            },
            options: opsi({ scales: { y: sumbu('kg/ha') } })
        });
    } else { kosong('chartDesa'); }

    // 3. Varietas bibit (batang horizontal)
    if (D.varietas.labels.length) {
        new C(document.getElementById('chartVarietas'), {
            type: 'bar',
            data: {
                labels: D.varietas.labels,
                datasets: [{
                    label: 'kg/ha',
                    data: D.varietas.data,
                    backgroundColor: D.varietas.jenis.map(function (j, i) { return warna(j, i); })
                }]
            },
            options: opsi({
                indexAxis: 'y',
                plugins: {
                    legend: { display: false },
                    tooltip: { callbacks: {
                        label: function (ctx) {
                            return angka(ctx.parsed.x) + ' kg/ha (' + D.varietas.jumlah[ctx.dataIndex] + ' musim tanam)';
                        }
                    } }
                },
                scales: { x: sumbu('kg/ha') }
            })
        });
    } else { kosong('chartVarietas'); }

    // 4. Pupuk dibeli vs dipakai
    if (D.pupuk.labels.length) {
        new C(document.getElementById('chartPupuk'), {
            type: 'bar',
            data: {
                labels: D.pupuk.labels,
                datasets: [
                    { label: 'Dibeli (kg)',  data: D.pupuk.dibeli,  backgroundColor: '#51bcda' },
                    { label: 'Dipakai (kg)', data: D.pupuk.dipakai, backgroundColor: '#ef8157' }
                ]
            },
            options: opsi({ scales: { y: sumbu('kg') } })
        });
    } else { kosong('chartPupuk'); }

    // 5. Scatter dosis pupuk vs hasil
    if (D.scatter.length) {
        new C(document.getElementById('chartScatter'), {
            type: 'scatter',
            data: {
                datasets: D.scatter.map(function (s, i) {
                    return { label: s.label, data: s.data, backgroundColor: warna(s.label, i), pointRadius: 4 };
                })
            },
            options: opsi({
                scales: {
                    x: { beginAtZero: true, title: { display: true, text: 'Pupuk (kg/ha)' } },
                    y: sumbu('Hasil (kg/ha)')
                },
                plugins: {
                    legend: { position: 'bottom' },
                    tooltip: { callbacks: {
                        label: function (ctx) {
                            return ctx.dataset.label + ': pupuk ' + angka(ctx.parsed.x) + ' kg/ha, hasil ' + angka(ctx.parsed.y) + ' kg/ha';
                        }
                    } }
                }
            })
        });
    } else { kosong('chartScatter'); }

    // 6. Gagal panen (donat)
    if (D.gagal.labels.length) {
        new C(document.getElementById('chartGagal'), {
            type: 'doughnut',
            data: {
                labels: D.gagal.labels,
                datasets: [{
                    data: D.gagal.data,
                    backgroundColor: D.gagal.labels.map(function (_, i) { return CADANGAN[i % CADANGAN.length]; })
                }]
            },
            options: opsi()
        });
    } else { kosong('chartGagal'); }
})();
</script>
@endsection