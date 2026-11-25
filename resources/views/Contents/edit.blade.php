@extends('dashboard')
@section('Content')
<div class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Tabel Data</h4>

                    @if (session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    @if (session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

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
                    @endphp
                    <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
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
                            <div style="overflow-y: auto; max-height:590px">
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
                                                <a href="/petani/delete/{{ $val->id_petani }}" class="text-danger" onclick="return confirm('Hapus data ini?')">Hapus</a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        {{-- ================= LAHAN ================= --}}
                        <div class="tab-pane fade" id="pills-lahan" role="tabpanel" aria-labelledby="pills-lahan-tab">
                            <div style="overflow-y: auto; max-height:590px">
                                <table class="table">
                                    <thead class="text-primary">
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Petani</th>
                                            <th>Lokasi</th>
                                            <th>Desa</th>
                                            <th>Luas (m<sup>2</sup>)</th>
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
                                            <td>
                                                <a href="/lahan/edit/{{ $val->id_lahan }}">Edit</a> |
                                                <a href="/lahan/delete/{{ $val->id_lahan }}" class="text-danger" onclick="return confirm('Hapus data ini?')">Hapus</a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        {{-- ================= LOKASI ================= --}}
                        <div class="tab-pane fade" id="pills-lokasi" role="tabpanel" aria-labelledby="pills-lokasi-tab">
                            <div style="overflow-y: auto; max-height:590px">
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
                                                <a href="/lokasi/delete/{{ $val->id_lokasi }}" class="text-danger" onclick="return confirm('Hapus data ini?')">Hapus</a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        {{-- ================= DESA ================= --}}
                        <div class="tab-pane fade" id="pills-desa" role="tabpanel" aria-labelledby="pills-desa-tab">
                            <div style="overflow-y: auto; max-height:590px">
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
                                                <a href="/desa/delete/{{ $val->id_desa }}" class="text-danger" onclick="return confirm('Hapus data ini?')">Hapus</a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        {{-- ================= BIBIT ================= --}}
                        <div class="tab-pane fade" id="pills-bibit" role="tabpanel" aria-labelledby="pills-bibit-tab">
                            <div style="overflow-y: auto; max-height:590px">
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
                                                <a href="/bibit/delete/{{ $val->id_bibit }}" class="text-danger" onclick="return confirm('Hapus data ini?')">Hapus</a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        {{-- ================= PUPUK ================= --}}
                        <div class="tab-pane fade" id="pills-pupuk" role="tabpanel" aria-labelledby="pills-pupuk-tab">
                            <div style="overflow-y: auto; max-height:590px">
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
                                                <a href="/pupuk/delete/{{ $val->id_pupuk }}" class="text-danger" onclick="return confirm('Hapus data ini?')">Hapus</a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        {{-- ================= RIWAYAT TANAM ================= --}}
                        <div class="tab-pane fade" id="pills-riwayattanam" role="tabpanel" aria-labelledby="pills-riwayattanam-tab">
                            <div style="overflow: auto; max-height:590px">
                                <table class="table">
                                    <thead class="text-primary">
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Petani</th>
                                            <th>Lokasi</th>
                                            <th>Desa</th>
                                            <th>Bibit</th>
                                            <th>Jumlah Bibit (KG)</th>
                                            <th>Pupuk Dipakai</th>
                                            <th>Musim</th>
                                            <th>Tanggal Tanam</th>
                                            <th>Tanggal Panen</th>
                                            <th>Total Hasil (KG)</th>
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
                                                <a href="/riwayat/edit/{{ $val->id_riwayat_tanam }}">Edit</a> |
                                                <a href="/riwayat/delete/{{ $val->id_riwayat_tanam }}" class="text-danger" onclick="return confirm('Hapus data ini?')">Hapus</a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        {{-- ================= RIWAYAT PEMBELIAN PUPUK ================= --}}
                        <div class="tab-pane fade" id="pills-riwayatpembelian" role="tabpanel" aria-labelledby="pills-riwayatpembelian-tab">
                            <div style="overflow-y: auto; max-height:590px">
                                <table class="table">
                                    <thead class="text-primary">
                                        <tr>
                                            <th>No</th>
                                            <th>Nama</th>
                                            <th>Tanggal Beli</th>
                                            <th>Nama Pupuk</th>
                                            <th>Jenis Pupuk</th>
                                            <th>Jumlah Pupuk (Sak)</th>
                                            <th>Setara (KG)</th>
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
                                                <a href="/transaksi/edit/{{ $val->id_transaksi }}">Edit</a> |
                                                <a href="/transaksi/delete/{{ $val->id_transaksi }}" class="text-danger" onclick="return confirm('Hapus data ini?')">Hapus</a>
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