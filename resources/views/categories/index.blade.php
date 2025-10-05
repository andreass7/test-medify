@extends('layouts.app')
@section('content')
    <div class="form-group mb-2">
        <a href="{{ route('category.create') }}" class="btn btn-secondary">+ Kategori Baru</a>
    </div>
    <label for="kategori">Kategori</label>
    <select name="kategori[]" multiple class="form-control">
        @foreach (App\Models\Category::all() as $cat)
            <option value="{{ $cat->id }}"
                {{ isset($data_item) && $data_item->categories->contains($cat->id) ? 'selected' : '' }}>
                {{ $cat->nama }}
            </option>
        @endforeach
    </select>
@endsection
