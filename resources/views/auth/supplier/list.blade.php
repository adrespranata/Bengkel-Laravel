<form action="{{ url('supplier/hapusall') }}" class="formhapus">
    @csrf
<button type="submit" class="btn btn-danger btn-sm">
    <i class="fa fa-trash"></i> Hapus yang diceklist
</button>

<hr>
<table id="listsupplier" class="table table-striped dt-responsive" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
    <thead>
        <tr>
            <th>
                <input type="checkbox" id="centangSemua">
            </th>
            <th>#</th>
            <th>Nama</th>
            <th>Alamat</th>
            <th>Telephone</th>
            <th>Foto</th>
            <th>Aksi</th>
        </tr>
    </thead>


    <tbody>
        @foreach ($list as $item)
            <tr>
                <td>
                    <input type="checkbox" name="supplier_id[]" class="centangSupplierid" value="{{ $item->supplier_id }} ">
                </td>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $item->nama_supplier }} </td>
                <td>{{ $item->alamat }} </td>
                <td>{{ $item->telephone }}</td>
               
                <td class="text-center"><img onclick="gambar('{{ $item->supplier_id }}')" src="{{ url('img/supplier/thumb/' . 'thumb_' . $item->foto) }}" width="120px" class="img-thumbnail"></td>
                <td>
                    <button type="button" class="btn btn-primary btn-sm" onclick="edit('{{ $item->supplier_id }}')">
                        <i class="fa fa-edit"></i>
                    </button>
                    <button type="button" class="btn btn-danger btn-sm" onclick="hapus('{{ $item->supplier_id }}')">
                        <i class="fa fa-trash"></i>
                    </button>
                </td>
            </tr>    
            
            @endforeach
    </tbody>
</table>
</form>
<script>
    $(document).ready(function() {
        $('#listsupplier').DataTable();

        $('#centangSemua').click(function(e) {
            if ($(this).is(':checked')) {
                $('.centangSupplierid').prop('checked', true);
            } else {
                $('.centangSupplierid').prop('checked', false);
            }
        });

        $('.formhapus').submit(function(e) {
            e.preventDefault();
            let jmldata = $('.centangSupplierid:checked');
            if (jmldata.length === 0) {
                Swal.fire({
                    icon: 'error',
                    title: 'Ooops!',
                    text: 'Silahkan pilih data!',
                    showConfirmButton: false,
                    timer: 1500
                })
            } else {
                Swal.fire({
                    title: 'Hapus data',
                    text: `Apakah anda yakin ingin menghapus sebanyak ${jmldata.length} data?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajaxSetup({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        });
                        $.ajax({
                            type: "post",
                            url: $(this).attr('action'),
                            data: $(this).serialize(),
                            dataType: "json",
                            success: function(response) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil',
                                    text: 'Data berhasil dihapus!',
                                    showConfirmButton: false,
                                    timer: 1500
                                });
                                listsupplier();
                            }
                        });
                    }
                })
            }
        });
    });

    function edit(supplier_id) {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type: "post",
            url: "{{ url('supplier/formedit') }}",
            data: {
                supplier_id: supplier_id
            },
            dataType: "json",
            success: function(response) {
                if (response.sukses) {
                    $('.viewmodal').html(response.sukses).show();
                    $('#modaledit').modal('show');
                }
            }
        });
    }

    function hapus(supplier_id) {
        Swal.fire({
            title: 'Hapus data?',
            text: `Apakah anda yakin menghapus data?`,
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
                    url: "{{ url('supplier/hapus') }}",
                    type: "post",
                    dataType: "json",
                    data: {
                        supplier_id: supplier_id
                    },
                    success: function(response) {
                        if (response.sukses) {
                            Swal.fire({
                                title: "Berhasil!",
                                text: response.sukses,
                                icon: "success",
                                showConfirmButton: false,
                                timer: 1500
                            });
                            listsupplier();
                        }
                    },
                    error: function(xhr, ajaxOptions, thrownError) {
                        alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
                    }
                });
            }
        })
    }

    function gambar(supplier_id) {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type: "post",
            url: "{{ url('supplier/formupload') }}",
            data: {
                supplier_id: supplier_id
            },
            dataType: "json",
            success: function(response) {
                if (response.sukses) {
                    $('.viewmodal').html(response.sukses).show();
                    $('#modalupload').modal('show');
                }
            }
        });
    }
</script>