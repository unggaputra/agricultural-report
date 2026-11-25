@extends('dashboard')
@section('Content')
<div class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Edit Data</h4>

                    @if (session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    @if (session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    {{-- ================= NAV TAB ================= --}}
                    <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                        @php
                            $tabs = [
                                'petani'          => 'Petani',
                                'lahan'           => 'Lahan',
                                'lokasi'          => 'Lokasi',
                                'desa'            => 'Desa',
                                'bibit'           => 'Bibit',
                                'pupuk'           => 'Pupuk',
                                'riwayattanam'    => 'Riwayat Tanam',
                                'riwayatpembelian'=> 'Riwayat Pembelian Pupuk',
                            ];
                        @endphp
                        @foreach ($tabs as $key => $label)
                            <li class="nav-item">
                                <a class="nav-link {{ $loop->first ? 'active' : '' }}"
                                   id="pills-{{ $key }}-tab" data-toggle="pill" href="#pills-{{ $key }}"
                                   role="tab" aria-controls="pills-{{ $key }}"
                                   aria-selected="{{ $loop->first ? 'true' : 'false' }}">{{ $label }}</a>
                            </li>
                        @endforeach
                    </ul>

                    <div class="tab-content" id="pills-tabContent">

                        {{-- ================= PETANI ================= --}}
                        <div class="tab-pane fade show active" id="pills-petani" role="tabpanel" aria-labelledby="pills-petani-tab">
                            <div style="overflow-y: auto; max-height: 500px">
                                <table class="table">
                                    <thead class="text-primary">
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Petani</th>
                                            <th>No Telepon</th>
                                            <th>Opsi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($petani as $val)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $val->nama_petani }}</td>
                                            <td>{{ $val->no_telepon }}</td>
                                            <td>
                                                <a href="/petani/edit/{{ $val->id_petani }}">Edit</a> |
                                                <form action="/petani/hapus/{{ $val->id_petani }}" method="POST" class="d-inline"
                                                      onsubmit="return confirm('Hapus data ini?')">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="btn btn-link btn-sm p-0 text-danger">Hapus</button>
                                                </form>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        {{-- ================= LAHAN ================= --}}
                        <div class="tab-pane fade" id="pills-lahan" role="tabpanel" aria-labelledby="pills-lahan-tab">
                            <div style="overflow-y: auto; max-height: 500px">
                                <table class="table">
                                    <thead class="text-primary">
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Petani</th>
                                            <th>Lokasi</th>
                                            <th>Desa</th>
                                            <th>Luas (m²)</th>
                                            <th>Luas (ha)</th>
                                            <th>Opsi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($lahan as $val)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $val->nama_petani }}</td>
                                            <td>{{ $val->nama_lokasi }}</td>
                                            <td>{{ $val->nama_desa }}</td>
                                            <td>{{ number_format($val->luas, 0, ',', '.') }}</td>
                                            <td>{{ number_format($val->luas / 10000, 2, ',', '.') }}</td>
                                            <td>
                                                <a href="/lahan/edit/{{ $val->id_lahan }}">Edit</a> |
                                                <form action="/lahan/hapus/{{ $val->id_lahan }}" method="POST" class="d-inline"
                                                      onsubmit="return confirm('Hapus data ini?')">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="btn btn-link btn-sm p-0 text-danger">Hapus</button>
                                                </form>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        {{-- ================= LOKASI ================= --}}
                        <div class="tab-pane fade" id="pills-lokasi" role="tabpanel" aria-labelledby="pills-lokasi-tab">
                            <div style="overflow-y: auto; max-height: 500px">
                                <table class="table">
                                    <thead class="text-primary">
                                        <tr>
                                            <th>No</th>
                                            <th>Lokasi</th>
                                            <th>Desa</th>
                                            <th>Opsi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($lokasi as $val)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $val->nama_lokasi }}</td>
                                            <td>{{ $val->nama_desa }}</td>
                                            <td>
                                                <a href="/lokasi/edit/{{ $val->id_lokasi }}">Edit</a> |
                                                <form action="/lokasi/hapus/{{ $val->id_lokasi }}" method="POST" class="d-inline"
                                                      onsubmit="return confirm('Hapus data ini?')">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="btn btn-link btn-sm p-0 text-danger">Hapus</button>
                                                </form>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        {{-- ================= DESA ================= --}}
                        <div class="tab-pane fade" id="pills-desa" role="tabpanel" aria-labelledby="pills-desa-tab">
                            <div style="overflow-y: auto; max-height: 500px">
                                <table class="table">
                                    <thead class="text-primary">
                                        <tr>
                                            <th>No</th>
                                            <th>Desa</th>
                                            <th>Opsi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($desa as $val)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $val->nama_desa }}</td>
                                            <td>
                                                <a href="/desa/edit/{{ $val->id_desa }}">Edit</a> |
                                                <form action="/desa/hapus/{{ $val->id_desa }}" method="POST" class="d-inline"
                                                      onsubmit="return confirm('Hapus data ini?')">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="btn btn-link btn-sm p-0 text-danger">Hapus</button>
                                                </form>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        {{-- ================= BIBIT ================= --}}
                        <div class="tab-pane fade" id="pills-bibit" role="tabpanel" aria-labelledby="pills-bibit-tab">
                            <div style="overflow-y: auto; max-height: 500px">
                                <table class="table">
                                    <thead class="text-primary">
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Bibit</th>
                                            <th>Jenis Tanaman</th>
                                            <th>Opsi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($bibit as $val)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $val->nama_bibit }}</td>
                                            <td>{{ $val->jenis_tanaman }}</td>
                                            <td>
                                                <a href="/bibit/edit/{{ $val->id_bibit }}">Edit</a> |
                                                <form action="/bibit/hapus/{{ $val->id_bibit }}" method="POST" class="d-inline"
                                                      onsubmit="return confirm('Hapus data ini?')">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="btn btn-link btn-sm p-0 text-danger">Hapus</button>
                                                </form>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        {{-- ================= PUPUK ================= --}}
                        <div class="tab-pane fade" id="pills-pupuk" role="tabpanel" aria-labelledby="pills-pupuk-tab">
                            <div style="overflow-y: auto; max-height: 500px">
                                <table class="table">
                                    <thead class="text-primary">
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Pupuk</th>
                                            <th>Jenis Pupuk</th>
                                            <th>Kg per Sak</th>
                                            <th>Opsi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($pupuk as $val)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $val->nama_pupuk }}</td>
                                            <td>{{ $val->jenis_pupuk }}</td>
                                            <td>{{ rtrim(rtrim(number_format($val->kg_per_sak, 2, ',', '.'), '0'), ',') }}</td>
                                            <td>
                                                <a href="/pupuk/edit/{{ $val->id_pupuk }}">Edit</a> |
                                                <form action="/pupuk/hapus/{{ $val->id_pupuk }}" method="POST" class="d-inline"
                                                      onsubmit="return confirm('Hapus data ini?')">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="btn btn-link btn-sm p-0 text-danger">Hapus</button>
                                                </form>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        {{-- ================= RIWAYAT TANAM ================= --}}
                        <div class="tab-pane fade" id="pills-riwayattanam" role="tabpanel" aria-labelledby="pills-riwayattanam-tab">
                            <div style="overflow: auto; max-height: 500px">
                                <table class="table">
                                    <thead class="text-primary">
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Petani</th>
                                            <th>Lokasi</th>
                                            <th>Desa</th>
                                            <th>Bibit</th>
                                            <th>Bibit (kg)</th>
                                            <th>Pupuk Dipakai</th>
                                            <th>Musim</th>
                                            <th>Tanggal Tanam</th>
                                            <th>Tanggal Panen</th>
                                            <th>Hasil (kg)</th>
                                            <th>Status</th>
                                            <th>Opsi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($riwayat_tanam as $val)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $val->nama_petani }}</td>
                                            <td>{{ $val->nama_lokasi }}</td>
                                            <td>{{ $val->nama_desa }}</td>
                                            <td>{{ $val->nama_bibit }}</td>
                                            <td>{{ rtrim(rtrim(number_format($val->jumlah_bibit_kg, 2, ',', '.'), '0'), ',') }}</td>
                                            <td>{{ $val->pupuk_dipakai ?? '-' }}</td>
                                            <td>{{ $val->musim ?? '-' }}</td>
                                            <td>{{ \Carbon\Carbon::parse($val->tanggal_tanam)->format('d-m-Y') }}</td>
                                            <td>{{ $val->tanggal_panen ? \Carbon\Carbon::parse($val->tanggal_panen)->format('d-m-Y') : '-' }}</td>
                                            <td>{{ $val->hasil_kg !== null ? number_format($val->hasil_kg, 0, ',', '.') : '-' }}</td>
                                            <td>
                                                @if ($val->status === 'panen')
                                                    <span class="badge badge-success">Panen</span>
                                                @elseif ($val->status === 'gagal')
                                                    <span class="badge badge-danger" title="{{ $val->alasan_gagal }}">Gagal</span>
                                                @else
                                                    <span class="badge badge-warning">Tanam</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="/riwayat-tanam/edit/{{ $val->id_riwayat_tanam }}">Edit</a> |
                                                <form action="/riwayat-tanam/hapus/{{ $val->id_riwayat_tanam }}" method="POST" class="d-inline"
                                                      onsubmit="return confirm('Hapus data ini?')">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="btn btn-link btn-sm p-0 text-danger">Hapus</button>
                                                </form>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        {{-- ================= RIWAYAT PEMBELIAN PUPUK ================= --}}
                        <div class="tab-pane fade" id="pills-riwayatpembelian" role="tabpanel" aria-labelledby="pills-riwayatpembelian-tab">
                            <div style="overflow-y: auto; max-height: 500px">
                                <table class="table">
                                    <thead class="text-primary">
                                        <tr>
                                            <th>No</th>
                                            <th>Nama</th>
                                            <th>Tanggal Beli</th>
                                            <th>Nama Pupuk</th>
                                            <th>Jenis Pupuk</th>
                                            <th>Jumlah (sak)</th>
                                            <th>Setara (kg)</th>
                                            <th>Opsi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($riwayat_pembelian_pupuk as $val)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $val->nama_petani }}</td>
                                            <td>{{ \Carbon\Carbon::parse($val->tanggal_beli)->format('d-m-Y') }}</td>
                                            <td>{{ $val->nama_pupuk }}</td>
                                            <td>{{ $val->jenis_pupuk }}</td>
                                            <td>{{ $val->jumlah_karung }}</td>
                                            <td>{{ number_format($val->jumlah_karung * $val->kg_per_sak, 0, ',', '.') }}</td>
                                            <td>
                                                <a href="/riwayat-pembelian/edit/{{ $val->id_transaksi }}">Edit</a> |
                                                <form action="/riwayat-pembelian/hapus/{{ $val->id_transaksi }}" method="POST" class="d-inline"
                                                      onsubmit="return confirm('Hapus data ini?')">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="btn btn-link btn-sm p-0 text-danger">Hapus</button>
                                                </form>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection