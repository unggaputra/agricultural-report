@extends('dashboard')
@section('Content')
<div class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Edit Riwayat Tanam</h4>

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>
                <div class="card-body">
                    <form action="/riwayat/update" method="post">
                        @csrf
                        <input type="hidden" name="id" value="{{ $riwayat->id_riwayat_tanam }}">

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Pemilik Lahan</label>
                                    <select name="id_lahan" class="form-control" required>
                                        <option value="">Pilih Lahan</option>
                                        @foreach ($lahan as $val)
                                            <option value="{{ $val->id_lahan }}" @selected(old('id_lahan', $riwayat->id_lahan) == $val->id_lahan)>
                                                {{ $val->id_lahan }}. {{ $val->nama_petani }} - {{ $val->nama_lokasi }} - {{ number_format($val->luas, 0, ',', '.') }} m²
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Bibit</label>
                                    <select name="id_bibit" class="form-control" required>
                                        <option value="">Pilih Bibit</option>
                                        @foreach ($bibit as $val)
                                            <option value="{{ $val->id_bibit }}" @selected(old('id_bibit', $riwayat->id_bibit) == $val->id_bibit)>
                                                {{ $val->nama_bibit }} - {{ $val->jenis_tanaman }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Jumlah Bibit (kg)</label>
                                    <input type="number" min="0" step="0.01" name="jumlah_bibit_kg" class="form-control"
                                           value="{{ old('jumlah_bibit_kg', $riwayat->jumlah_bibit_kg) }}" required>
                                </div>
                                <div class="form-group">
                                    <label>Musim Tanam</label>
                                    <select name="musim" class="form-control">
                                        <option value="">- Tidak diisi -</option>
                                        @foreach (['MT1', 'MT2', 'MT3'] as $m)
                                            <option value="{{ $m }}" @selected(old('musim', $riwayat->musim) === $m)>{{ $m }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Status</label>
                                    <select name="status" id="status-tanam" class="form-control" required>
                                        <option value="tanam" @selected(old('status', $riwayat->status) === 'tanam')>Sedang tanam</option>
                                        <option value="panen" @selected(old('status', $riwayat->status) === 'panen')>Sudah panen</option>
                                        <option value="gagal" @selected(old('status', $riwayat->status) === 'gagal')>Gagal panen</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Tanggal Tanam</label>
                                    <input type="date" name="tanggal_tanam" class="form-control"
                                           value="{{ old('tanggal_tanam', $riwayat->tanggal_tanam) }}" required>
                                </div>
                                <div class="form-group" id="grup-panen">
                                    <label>Tanggal Panen</label>
                                    <input type="date" name="tanggal_panen" class="form-control"
                                           value="{{ old('tanggal_panen', $riwayat->tanggal_panen) }}">
                                </div>
                                <div class="form-group" id="grup-hasil">
                                    <label>Hasil Panen (kg)</label>
                                    <input type="number" min="0" name="hasil_kg" class="form-control"
                                           value="{{ old('hasil_kg', $riwayat->hasil_kg) }}">
                                </div>
                                <div class="form-group" id="grup-gagal">
                                    <label>Alasan Gagal</label>
                                    <input type="text" name="alasan_gagal" maxlength="100" class="form-control"
                                           placeholder="Hama, kekeringan, banjir..."
                                           value="{{ old('alasan_gagal', $riwayat->alasan_gagal) }}">
                                </div>
                            </div>

                            <div class="col-md-4">
                                <label>Pupuk yang Dipakai</label>
                                <div id="daftar-pupuk"></div>
                                <button type="button" id="tambah-pupuk" class="btn btn-sm btn-outline-primary mb-3">+ Tambah Pupuk</button>
                                <div>
                                    <button type="submit" class="btn btn-primary btn-round">Simpan Perubahan</button>
                                    <a href="/dashboard" class="btn btn-round btn-outline-secondary">Batal</a>
                                </div>
                            </div>
                        </div>
                    </form>

                    <template id="template-pupuk">
                        <div class="form-row align-items-end mb-2 baris-pupuk">
                            <div class="col-6">
                                <select name="pupuk[__I__][id_pupuk]" class="form-control" required>
                                    <option value="">Pilih Pupuk</option>
                                    @foreach ($pupuk as $val)
                                        <option value="{{ $val->id_pupuk }}">{{ $val->nama_pupuk }} - {{ $val->jenis_pupuk }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-4">
                                <input type="number" min="0" step="0.01" name="pupuk[__I__][jumlah_kg]" class="form-control" placeholder="kg" required>
                            </div>
                            <div class="col-2">
                                <button type="button" class="btn btn-sm btn-link text-danger hapus-pupuk">Hapus</button>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </div>
</div>

@php
    $barisPupuk = old('pupuk', $pupukDipakai->map(fn ($p) => [
        'id_pupuk'  => $p->id_pupuk,
        'jumlah_kg' => $p->jumlah_kg + 0,
    ])->values()->all());
@endphp

<script>
document.addEventListener('DOMContentLoaded', function () {
    var daftar   = document.getElementById('daftar-pupuk');
    var template = document.getElementById('template-pupuk').innerHTML;
    var indeks   = 0;

    function tambahBaris(idPupuk, jumlah) {
        var wrap = document.createElement('div');
        wrap.innerHTML = template.replace(/__I__/g, indeks++);
        var baris = wrap.firstElementChild;
        if (idPupuk) baris.querySelector('select').value = idPupuk;
        if (jumlah !== undefined && jumlah !== null) baris.querySelector('input').value = jumlah;
        daftar.appendChild(baris);
    }

    document.getElementById('tambah-pupuk').addEventListener('click', function () { tambahBaris(); });
    daftar.addEventListener('click', function (e) {
        if (e.target.classList.contains('hapus-pupuk')) {
            e.target.closest('.baris-pupuk').remove();
        }
    });

    var awal = @json($barisPupuk);
    if (awal.length) {
        awal.forEach(function (p) { tambahBaris(p.id_pupuk, p.jumlah_kg); });
    } else {
        tambahBaris();
    }

    var status = document.getElementById('status-tanam');
    function aturKolom() {
        var s = status.value;
        document.getElementById('grup-panen').style.display = (s === 'panen') ? '' : 'none';
        document.getElementById('grup-hasil').style.display = (s === 'tanam') ? 'none' : '';
        document.getElementById('grup-gagal').style.display = (s === 'gagal') ? '' : 'none';
    }
    status.addEventListener('change', aturKolom);
    aturKolom();
});
</script>
@endsection