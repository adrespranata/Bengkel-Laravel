<hr>
<table id="listsparepart" class="table table-striped dt-responsive" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
    <thead>
        <tr>
            <th>#</th>
            <th>Barcode</th>
            <th>Nama Sparepart</th>
            <th>Harga Beli</th>
            <th>Qty</th>
            <th>Sub Total</th>
            <th>Aksi</th>
        </tr>
    </thead>


    <tbody> 
        @foreach ($datadetail as $row)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $row['kode'] }}</td>
                <td>{{ $row['nama_sparepart'] }}</td>
                <td>{{ number_format($row['hargabeli'], 2, ",", ".") }}</td>
                <td>{{ $row['qty'] }}</td>
                <td>{{ number_format($row['subtotal'], 0, ",", ".") }}</td>
                <td>
                    <button type="button" class="btn btn-danger btn-sm btn-del" onclick="hapusitem('{{ $row['id'] }} ','{{ $row['nama_sparepart'] }} ')">
                        <i class="fa fa-trash"></i>
                    </button>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
<script>
    $(document).ready(function() {
        $('#listsparepart').DataTable();
    });

    function hapusitem(id, nama_sparepart) {
        Swal.fire({
            title: 'Hapus data?',
            html: `Apakah anda yakin ingin menghapus data produk <strong>${nama_sparepart}</strong> ?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya!',
            cancelButtonText: 'Tidak'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    type: "post",
                    url: "{{ url('purchase/hapusItem') }}",
                    dataType: "json",
                    data: {
                        id: id
                    },
                    success: function(response) {
                        if (response.sukses) {
                            Swal.fire({
                                title: "Berhasil!",
                                icon: "success",
                                text: response.sukses,
                            });
                            dataPurchaseDetail();
                            kosong();
                        }
                    },
                    error: function(xhr, ajaxOptions, thrownError) {
                        alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
                    }
                });
            }
        })
    }
</script>