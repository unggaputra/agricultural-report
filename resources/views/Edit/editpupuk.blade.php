@extends('dashboard')
@section('Content')
<div class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Edit Pupuk</h4>

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
                    <form action="/pupuk/update" method="post">
                        @csrf
                        <input type="hidden" name="id" value="{{ $pupuk->id_pupuk }}">
                        <div class="row"><div class="col-md-4">
                            <div class="form-group">
                                <label>Nama Pupuk</label>
                                <input type="text" class="form-control" name="nama" maxlength="35"
                                       value="{{ old('nama', $pupuk->nama_pupuk) }}" required>
                            </div>
                            <div class="form-group">
                                <label>Jenis Pupuk</label>
                                <select name="jenis" class="form-control" required>
                                    @foreach (['Kimia', 'Organik'] as $j)
                                        <option value="{{ $j }}" @selected(old('jenis', $pupuk->jenis_pupuk) === $j)>{{ $j }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Berat per Sak (kg)</label>
                                <input type="number" min="0.1" step="0.1" class="form-control" name="kg_per_sak"
                                       value="{{ old('kg_per_sak', $pupuk->kg_per_sak + 0) }}" required>
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