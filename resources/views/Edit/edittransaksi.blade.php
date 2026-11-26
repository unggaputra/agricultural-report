@extends('dashboard')
@section('Content')
<div class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Edit Riwayat Pembelian Pupuk</h4>

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
                    <form action="/transaksi/update" method="post">
                        @csrf
                        <input type="hidden" name="id" value="{{ $transaksi->id_transaksi }}">
                        <div class="row"><div class="col-md-4">
                            <div class="form-group">
                                <label>Nama Petani</label>
                                <select name="id_petani" class="form-control" required>
                                    @foreach ($petani as $val)
                                        <option value="{{ $val->id_petani }}" @selected(old('id_petani', $transaksi->id_petani) == $val->id_petani)>
                                            {{ $val->id_petani }} - {{ $val->nama_petani }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Nama Pupuk</label>
                                <select name="id_pupuk" class="form-control" required>
                                    @foreach ($pupuk as $val)
                                        <option value="{{ $val->id_pupuk }}" @selected(old('id_pupuk', $transaksi->id_pupuk) == $val->id_pupuk)>
                                            {{ $val->nama_pupuk }} - {{ $val->jenis_pupuk }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Tanggal Beli</label>
                                <input type="date" class="form-control" name="tanggal_beli"
                                       value="{{ old('tanggal_beli', $transaksi->tanggal_beli) }}" required>
                            </div>
                            <div class="form-group">
                                <label>Jumlah (sak)</label>
                                <input type="number" min="1" class="form-control" name="jumlah"
                                       value="{{ old('jumlah', $transaksi->jumlah_karung) }}" required>
                            </div>
                            <button type="submit" class="btn btn-primary btn-round">Simpan</button>
                            <a href="/dashboard" class="btn btn-round btn-outline-secondary">Batal</a>
                        </div></div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection