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
        @foreach ($datadet as $r)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $r['kode'] }}</td>
                <td>{{ $r['nama_sparepart'] }}</td>
                <td>{{ number_format($r['hargabeli'], 2, ",", ".") }}</td>
                <td>{{ $r['qty'] }}</td>
                <td>{{ number_format($r['subtotal'], 0, ",", ".") }}</td>

                <td>
                    <button type="button" class="btn btn-primary btn-sm btn-edit" title="Edit Data" onclick="editItem('{{ $r['id'] }}','{{ $r['nama_sparepart'] }}')">
                        <i class="fa fa-edit"></i>
                    </button>&nbsp;
                    <button type="button" class="btn btn-danger btn-sm btn-del" title="Hapus Data" onclick="hapusItemDetail('{{ $r['id'] }}','{{ $r['nama_sparepart'] }}')">
                        <i class="fa fa-trash"></i>
                    </button>&nbsp;
                </td>
            </tr>

        @endforeach
    </tbody>
</table>
<script>
    $(document).ready(function() {
        $('#listsparepart').DataTable();
    });

    function editItem(id, nama_sparepart) {
        $('#iddetail').val(id);
        $.ajax({
            type: "post",
            url: "{{ url('purchase/editItem') }}",
            dataType: "json",
            data: {
                iddetail: $('#iddetail').val()
            },
            success: function(response) {
                if (response.sukses) {
                    let data = response.sukses;
                    //call data
                    $('#kodebarcode').val(data.kodebarang);
                    $('#nama_sparepart').val(data.nama_sparepart);
                    $('#stok').val(data.stok);
                    $('#qty').val(data.qty);
                    //show button
                    $('#btnEdit').fadeIn();
                }
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
            }
        });
    }

    function hapusItemDetail(id, nama_sparepart) {
        let nofaktur = $('#nofaktur').val();

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
                $.ajax({
                    type: "post",
                    url: "{{ url('purchase/hapusItemDetail') }}",
                    dataType: "json",
                    data: {
                        id: id,
                        nofaktur: nofaktur
                    },
                    success: function(response) {
                        if (response.sukses) {
                            Swal.fire({
                                title: "Berhasil!",
                                icon: "success",
                                text: response.sukses,
                            });
                            dataDetailPurchase();
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