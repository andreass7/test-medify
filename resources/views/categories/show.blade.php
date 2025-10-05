<h3>{{ $category->nama }} ({{ $category->kode }})</h3>

<h4>Items:</h4>
<ul>
    @foreach ($category->items as $item)
        <li>{{ $item->nama }} - {{ $item->kode }}</li>
    @endforeach
</ul>
