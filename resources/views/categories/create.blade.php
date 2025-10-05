@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Tambah Kategori Baru</h2>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('categories.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="kode" class="form-label">Kode Kategori</label>
                <input type="text" name="kode" id="kode" class="form-control" value="{{ old('kode') }}" required>
            </div>

            <div class="mb-3">
                <label for="nama" class="form-label">Nama Kategori</label>
                <input type="text" name="nama" id="nama" class="form-control" value="{{ old('nama') }}"
                    required>
            </div>

            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="{{ route('categories.index') }}" class="btn btn-secondary">Kembali</a>
        </form>
    </div>
@endsection
