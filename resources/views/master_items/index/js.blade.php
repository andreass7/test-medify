<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap5.min.js"></script>

<script>
    $(document).ready(function() {
        $('#table').DataTable({
            searching: false,
            order: [
                [0, 'desc']
            ],
        });

        getData();

        $('.btn-get-data').click(function() {
            getData();
        });
    });

    function getData() {
        $('#loading-filter').show();
        var dataTableObj = $('#table').DataTable();

        var filter_kode = $('#filter-kode').val();
        var filter_nama = $('#filter-nama').val();

        dataTableObj.clear().draw();

        $.ajax({
            url: '{{ url('master-items/search') }}',
            dataType: 'json',
            data: {
                kode: filter_kode,
                nama: filter_nama
            },
            success: function(results) {
                var data = results.data;

                $.each(data, function(index, item) {
                    var array_temp = [];

                    // harga jual
                    var harga_jual = item.harga_beli + item.harga_beli * item.laba / 100;
                    harga_jual = Math.round(harga_jual);

                    // tombol View
                    var html = `<a href="{{ url('master-items/view/') }}/` + item.kode +
                        `" class="btn btn-primary btn-sm">View</a>`;
                    array_temp.push(index + 1);
                    array_temp.push(item.kode);
                    array_temp.push(item.nama);
                    array_temp.push(item.harga_beli);
                    array_temp.push(harga_jual);
                    array_temp.push(item.supplier);

                    var avatar_html = item.avatar ?
                        `<img src="{{ asset('storage/') }}/` + item.avatar +
                        `" width="50">` :
                        `<img src="{{ asset('storage/default.png') }}" width="50">`;
                    array_temp.push(avatar_html);

                    array_temp.push(html);

                    dataTableObj.row.add(array_temp).draw(false);
                });

                $('#loading-filter').hide();
            },
            error: function(xhr, textStatus, errorThrown) {
                alert('Terjadi kesalahan server, tidak dapat mengambil data');
                $('#loading-filter').hide();
            }
        });
    }
</script>
