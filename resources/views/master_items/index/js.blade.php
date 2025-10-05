<!-- Base URL untuk storage -->
<script>
    var baseStorage = "{{ url('storage') }}/";
</script>

<!-- jQuery & DataTables -->
<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap5.min.js"></script>

<script>
    $(document).ready(function() {
        // Inisialisasi DataTable
        var dataTableObj = $('#table').DataTable({
            searching: false,
            order: [
                [0, 'desc']
            ],
        });

        // Load data awal
        getData();

        // Tombol filter
        $('.btn-get-data').click(function() {
            getData();
        });

        function getData() {
            $('#loading-filter').show();

            // Ambil filter
            var filter_kode = $('#filter-kode').val();
            var filter_nama = $('#filter-nama').val();

            // Clear table
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
                        var row = [];

                        // Hitung harga jual
                        var harga_jual = item.harga_beli + item.harga_beli * item.laba /
                        100;
                        harga_jual = Math.round(harga_jual);

                        // Tombol View
                        var viewBtn = `<a href="{{ url('master-items/view/') }}/` + item
                            .kode + `" class="btn btn-primary btn-sm">View</a>`;

                        // Avatar (gunakan baseStorage dari Blade)
                        var avatar_url = item.avatar ? baseStorage + item.avatar :
                            baseStorage + "default.png";
                        var avatar_html = `<img src="` + avatar_url + `" width="50">`;

                        // Push data ke row
                        row.push(index + 1);
                        row.push(item.kode);
                        row.push(item.nama);
                        row.push(item.harga_beli);
                        row.push(harga_jual);
                        row.push(item.supplier);
                        row.push(avatar_html);
                        row.push(viewBtn);

                        // Tambahkan row ke DataTable
                        dataTableObj.row.add(row).draw(false);
                    });

                    $('#loading-filter').hide();
                },
                error: function(xhr, textStatus, errorThrown) {
                    alert('Terjadi kesalahan server, tidak dapat mengambil data');
                    $('#loading-filter').hide();
                }
            });
        }
    });
</script>
