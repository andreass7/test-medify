<form method="POST" enctype="multipart/form-data">
    @csrf
    @if ($method == 'edit')
        <div class="form-group">
            <label>Kode Barang</label>
            <input type="text" class="form-control" name="kode_barang" required readonly value="{{ $item->kode ?? '' }}">
        </div>
    @endif

    <div class="form-group">
        <label>Nama</label>
        <input type="text" class="form-control" name="nama" required value="{{ $item->nama ?? '' }}">
    </div>

    <div class="form-group">
        <label>Harga Beli</label>
        <input type="number" class="form-control" name="harga_beli" required value="{{ $item->harga_beli ?? '' }}">
    </div>

    <div class="form-group">
        <label>Laba (dalam persen)</label>
        <input type="number" class="form-control" name="laba" required value="{{ $item->laba ?? '' }}">
    </div>

    @php $selected = $item->supplier ?? ''; @endphp
    <div class="form-group">
        <label>Supplier</label>
        <select class="form-control" required name="supplier">
            <option @if ($selected == '') selected @endif value="">--Pilih--</option>
            <option @if ($selected == 'Tokopaedi') selected @endif>Tokopaedi</option>
            <option @if ($selected == 'Bukulapuk') selected @endif>Bukulapuk</option>
            <option @if ($selected == 'TokoBagas') selected @endif>TokoBagas</option>
            <option @if ($selected == 'E Commurz') selected @endif>E Commurz</option>
            <optio @if ($selected == 'Blublu') selected @endif>Blublu</option>
        </select>
    </div>

    <div class="form-group">
        <label>Kategori (Jenis otomatis)</label>
        @php $selectedCategories = $item->categories->pluck('id')->toArray() ?? []; @endphp
        <select name="kategori[]" class="form-control" multiple required>
            @foreach (App\Models\Category::all() as $cat)
                <option value="{{ $cat->id }}" @if (in_array($cat->id, $selectedCategories)) selected @endif>
                    {{ $cat->nama }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="form-group">
        <label for="avatar">Foto</label>
        <input type="file" name="avatar" class="form-control" accept="image/*">
    </div>

    <button class="btn btn-primary mt-3">Submit</button>

</form>
