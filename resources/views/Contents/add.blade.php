@extends('dashboard')
@section('Content')
<div class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Tambah Data</h4>

                    @if (session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    @if (session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- ================= NAV TAB ================= --}}
                    @php
                        $tabs = [
                            'petani'           => 'Petani',
                            'lahan'            => 'Lahan',
                            'lokasi'           => 'Lokasi',
                            'desa'             => 'Desa',
                            'bibit'            => 'Bibit',
                            'pupuk'            => 'Pupuk',
                            'riwayattanam'     => 'Riwayat Tanam',
                            'riwayatpembelian' => 'Riwayat Pembelian Pupuk',
                        ];
                        $tabAktif = old('_tab', 'petani');
                    @endphp
                    <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                        @foreach ($tabs as $key => $label)
                            <li class="nav-item">
                                <a class="nav-link {{ $key === $tabAktif ? 'active' : '' }}"
                                   id="pills-{{ $key }}-tab" data-toggle="pill" href="#pills-{{ $key }}"
                                   role="tab" aria-controls="pills-{{ $key }}"
                                   aria-selected="{{ $key === $tabAktif ? 'true' : 'false' }}">{{ $label }}</a>
                            </li>
                        @endforeach
                    </ul>

                    <div class="tab-content" id="pills-tabContent" style="min-height: 590px">

                        {{-- ================= PETANI ================= --}}
                        <div class="tab-pane fade {{ $tabAktif === 'petani' ? 'show active' : '' }}" id="pills-petani" role="tabpanel" aria-labelledby="pills-petani-tab">
                            <form action="/petani/store" method="post">
                                @csrf
                                <input type="hidden" name="_tab" value="petani">
                                <div class="row"><div class="col-md-4">
                                    <div class="form-group">
                                        <label>Nama</label>
                                        <input type="text" class="form-control" name="nama" placeholder="Nama" value="{{ old('nama') }}" required>
                                    </div>
                                    <div class="form-group">
                                        <label>Nomor Telepon</label>
                                        <input type="text" class="form-control" name="telepon" placeholder="No Telp" value="{{ old('telepon') }}" required>
                                    </div>
                                    <button type="submit" class="btn btn-primary btn-round">Tambah</button>
                                </div></div>
                            </form>
                        </div>

                        {{-- ================= LAHAN ================= --}}
                        <div class="tab-pane fade {{ $tabAktif === 'lahan' ? 'show active' : '' }}" id="pills-lahan" role="tabpanel" aria-labelledby="pills-lahan-tab">
                            <form action="/lahan/store" method="post">
                                @csrf
                                <input type="hidden" name="_tab" value="lahan">
                                <div class="row"><div class="col-md-4">
                                    <div class="form-group">
                                        <label>Petani</label>
                                        <select name="id_petani" class="form-control" required>
                                            <option value="">Pilih Petani</option>
                                            @foreach ($petani as $val)
                                                <option value="{{ $val->id_petani }}" @selected(old('id_petani') == $val->id_petani)>
                                                    {{ $val->id_petani }} - {{ $val->nama_petani }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>Lokasi</label>
                                        <select name="id_lokasi" class="form-control" required>
                                            <option value="">Pilih Lokasi</option>
                                            @foreach ($lokasi as $val)
                                                <option value="{{ $val->id_lokasi }}" @selected(old('id_lokasi') == $val->id_lokasi)>
                                                    {{ $val->nama_lokasi }} - {{ $val->nama_desa }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>Luas Lahan (m²)</label>
                                        <input type="number" min="1" class="form-control" name="luas" placeholder="Contoh: 2500" value="{{ old('luas') }}" required>
                                    </div>
                                    <button type="submit" class="btn btn-primary btn-round">Tambah</button>
                                </div></div>
                            </form>
                        </div>

                        {{-- ================= LOKASI ================= --}}
                        <div class="tab-pane fade {{ $tabAktif === 'lokasi' ? 'show active' : '' }}" id="pills-lokasi" role="tabpanel" aria-labelledby="pills-lokasi-tab">
                            <form action="/lokasi/store" method="post">
                                @csrf
                                <input type="hidden" name="_tab" value="lokasi">
                                <div class="row"><div class="col-md-4">
                                    <div class="form-group">
                                        <label>Lokasi (Dusun)</label>
                                        <input type="text" class="form-control" name="nama" placeholder="Lokasi" value="{{ old('nama') }}" required>
                                    </div>
                                    <div class="form-group">
                                        <label>Desa</label>
                                        <select name="id_desa" class="form-control" required>
                                            <option value="">Pilih Desa</option>
                                            @foreach ($desa as $val)
                                                <option value="{{ $val->id_desa }}" @selected(old('id_desa') == $val->id_desa)>{{ $val->nama_desa }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <button type="submit" class="btn btn-primary btn-round">Tambah</button>
                                </div></div>
                            </form>
                        </div>

                        {{-- ================= DESA ================= --}}
                        <div class="tab-pane fade {{ $tabAktif === 'desa' ? 'show active' : '' }}" id="pills-desa" role="tabpanel" aria-labelledby="pills-desa-tab">
                            <form action="/desa/store" method="post">
                                @csrf
                                <input type="hidden" name="_tab" value="desa">
                                <div class="row"><div class="col-md-4">
                                    <div class="form-group">
                                        <label>Desa</label>
                                        <input type="text" class="form-control" name="nama" placeholder="Desa" value="{{ old('nama') }}" required>
                                    </div>
                                    <button type="submit" class="btn btn-primary btn-round">Tambah</button>
                                </div></div>
                            </form>
                        </div>

                        {{-- ================= BIBIT ================= --}}
                        <div class="tab-pane fade {{ $tabAktif === 'bibit' ? 'show active' : '' }}" id="pills-bibit" role="tabpanel" aria-labelledby="pills-bibit-tab">
                            <form action="/bibit/store" method="post">
                                @csrf
                                <input type="hidden" name="_tab" value="bibit">
                                <div class="row"><div class="col-md-4">
                                    <div class="form-group">
                                        <label>Nama Bibit</label>
                                        <input type="text" class="form-control" name="nama" placeholder="Bibit" value="{{ old('nama') }}" required>
                                    </div>
                                    <div class="form-group">
                                        <label>Jenis Tanaman</label>
                                        <input type="text" class="form-control" name="tanaman" placeholder="Padi / Jagung" value="{{ old('tanaman') }}" required>
                                    </div>
                                    <button type="submit" class="btn btn-primary btn-round">Tambah</button>
                                </div></div>
                            </form>
                        </div>

                        {{-- ================= PUPUK ================= --}}
                        <div class="tab-pane fade {{ $tabAktif === 'pupuk' ? 'show active' : '' }}" id="pills-pupuk" role="tabpanel" aria-labelledby="pills-pupuk-tab">
                            <form action="/pupuk/store" method="post">
                                @csrf
                                <input type="hidden" name="_tab" value="pupuk">
                                <div class="row"><div class="col-md-4">
                                    <div class="form-group">
                                        <label>Nama Pupuk</label>
                                        <input type="text" class="form-control" name="nama" placeholder="Pupuk" value="{{ old('nama') }}" required>
                                    </div>
                                    <div class="form-group">
                                        <label>Jenis Pupuk</label>
                                        <select name="jenis" class="form-control" required>
                                            <option value="">Pilih Jenis</option>
                                            <option value="Kimia" @selected(old('jenis') === 'Kimia')>Kimia</option>
                                            <option value="Organik" @selected(old('jenis') === 'Organik')>Organik</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>Berat per Sak (kg)</label>
                                        <input type="number" min="0.1" step="0.1" class="form-control" name="kg_per_sak" placeholder="Contoh: 50" value="{{ old('kg_per_sak', 50) }}" required>
                                    </div>
                                    <button type="submit" class="btn btn-primary btn-round">Tambah</button>
                                </div></div>
                            </form>
                        </div>

                        {{-- ================= RIWAYAT TANAM ================= --}}
                        <div class="tab-pane fade {{ $tabAktif === 'riwayattanam' ? 'show active' : '' }}" id="pills-riwayattanam" role="tabpanel" aria-labelledby="pills-riwayattanam-tab">
                            <form action="/riwayat/store" method="post">
                                @csrf
                                <input type="hidden" name="_tab" value="riwayattanam">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Pemilik Lahan</label>
                                            <select name="id_lahan" class="form-control" required>
                                                <option value="">Pilih Lahan</option>
                                                @foreach ($lahan as $val)
                                                    <option value="{{ $val->id_lahan }}" @selected(old('id_lahan') == $val->id_lahan)>
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
                                                    <option value="{{ $val->id_bibit }}" @selected(old('id_bibit') == $val->id_bibit)>
                                                        {{ $val->nama_bibit }} - {{ $val->jenis_tanaman }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>Jumlah Bibit (kg)</label>
                                            <input type="number" min="0" step="0.01" class="form-control" name="jumlah_bibit_kg" placeholder="Contoh: 12.5" value="{{ old('jumlah_bibit_kg') }}" required>
                                        </div>
                                        <div class="form-group">
                                            <label>Musim Tanam</label>
                                            <select name="musim" class="form-control">
                                                <option value="">- Tidak diisi -</option>
                                                @foreach (['MT1', 'MT2', 'MT3'] as $m)
                                                    <option value="{{ $m }}" @selected(old('musim') === $m)>{{ $m }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Status</label>
                                            <select name="status" id="status-tanam" class="form-control" required>
                                                <option value="tanam"  @selected(old('status', 'tanam') === 'tanam')>Sedang tanam</option>
                                                <option value="panen"  @selected(old('status') === 'panen')>Sudah panen</option>
                                                <option value="gagal"  @selected(old('status') === 'gagal')>Gagal panen</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>Tanggal Tanam</label>
                                            <input type="date" name="tanggal_tanam" class="form-control" value="{{ old('tanggal_tanam') }}" required>
                                        </div>
                                        <div class="form-group" id="grup-panen">
                                            <label>Tanggal Panen</label>
                                            <input type="date" name="tanggal_panen" class="form-control" value="{{ old('tanggal_panen') }}">
                                        </div>
                                        <div class="form-group" id="grup-hasil">
                                            <label>Hasil Panen (kg)</label>
                                            <input type="number" min="0" name="hasil_kg" class="form-control" placeholder="Hasil" value="{{ old('hasil_kg') }}">
                                        </div>
                                        <div class="form-group" id="grup-gagal">
                                            <label>Alasan Gagal</label>
                                            <input type="text" name="alasan_gagal" maxlength="100" class="form-control" placeholder="Hama, kekeringan, banjir..." value="{{ old('alasan_gagal') }}">
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <label>Pupuk yang Dipakai</label>
                                        <div id="daftar-pupuk"></div>
                                        <button type="button" id="tambah-pupuk" class="btn btn-sm btn-outline-primary mb-3">+ Tambah Pupuk</button>
                                        <div>
                                            <button type="submit" class="btn btn-primary btn-round">Tambah</button>
                                        </div>
                                    </div>
                                </div>
                            </form>

                            {{-- Template baris pupuk (disalin oleh JavaScript) --}}
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

                        {{-- ================= RIWAYAT PEMBELIAN PUPUK ================= --}}
                        <div class="tab-pane fade {{ $tabAktif === 'riwayatpembelian' ? 'show active' : '' }}" id="pills-riwayatpembelian" role="tabpanel" aria-labelledby="pills-riwayatpembelian-tab">
                            <form action="/transaksi/store" method="post">
                                @csrf
                                <input type="hidden" name="_tab" value="riwayatpembelian">
                                <div class="row"><div class="col-md-4">
                                    <div class="form-group">
                                        <label>Pembeli</label>
                                        <select name="id_petani" class="form-control" required>
                                            <option value="">Pilih Petani</option>
                                            @foreach ($petani as $val)
                                                <option value="{{ $val->id_petani }}" @selected(old('id_petani') == $val->id_petani)>
                                                    {{ $val->id_petani }} - {{ $val->nama_petani }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>Pupuk</label>
                                        <select name="id_pupuk" class="form-control" required>
                                            <option value="">Pilih Pupuk</option>
                                            @foreach ($pupuk as $val)
                                                <option value="{{ $val->id_pupuk }}" @selected(old('id_pupuk') == $val->id_pupuk)>
                                                    {{ $val->nama_pupuk }} - {{ $val->jenis_pupuk }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>Tanggal Beli</label>
                                        <input type="date" name="tanggal_beli" class="form-control" value="{{ old('tanggal_beli', date('Y-m-d')) }}" required>
                                    </div>
                                    <div class="form-group">
                                        <label>Jumlah (sak)</label>
                                        <input type="number" min="1" name="jumlah" class="form-control" placeholder="Jumlah" value="{{ old('jumlah') }}" required>
                                    </div>
                                    <button type="submit" class="btn btn-primary btn-round">Tambah</button>
                                </div></div>
                            </form>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // ---- Baris pupuk dinamis ----
    var daftar   = document.getElementById('daftar-pupuk');
    var template = document.getElementById('template-pupuk').innerHTML;
    var indeks   = 0;

    function tambahBaris(idPupuk, jumlah) {
        var wrap = document.createElement('div');
        wrap.innerHTML = template.replace(/__I__/g, indeks++);
        var baris = wrap.firstElementChild;
        if (idPupuk) baris.querySelector('select').value = idPupuk;
        if (jumlah)  baris.querySelector('input').value  = jumlah;
        daftar.appendChild(baris);
    }

    document.getElementById('tambah-pupuk').addEventListener('click', function () { tambahBaris(); });
    daftar.addEventListener('click', function (e) {
        if (e.target.classList.contains('hapus-pupuk')) {
            e.target.closest('.baris-pupuk').remove();
        }
    });

    // Pulihkan isian pupuk setelah validasi gagal, atau mulai dengan satu baris kosong
    var lama = @json(old('pupuk', []));
    if (lama.length) {
        lama.forEach(function (p) { tambahBaris(p.id_pupuk, p.jumlah_kg); });
    } else {
        tambahBaris();
    }

    // ---- Tampilkan/sembunyikan kolom sesuai status ----
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