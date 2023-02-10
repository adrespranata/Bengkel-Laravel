<form action="{{ url('sparepart/hapusall') }}" class="formhapus">
    @csrf
    <button type="submit" class="btn btn-danger btn-sm">
        <i class="fa fa-trash"></i> Hapus yang diceklist
    </button>
    
    <hr>
    <table id="listsparepart" class="table table-striped dt-responsive" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
        <thead>
            <tr>
                <th>
                    <input type="checkbox" id="centangSemua">
                </th>
                <th>#</th>
                <th>Barcode</th>
                <th>Nama</th>
                <th>Harga Beli</th>
                <th>Harga Jual</th>
                <th>Stok</th>
                <th>Aksi</th>
            </tr>
        </thead>
            @foreach ($list as $item)
            <tr>
                <td>
                    <input type="checkbox" name="kodebarcode[]" class="centangSparepartid" value="{{ $item->kodebarcode }} ">
                </td>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $item->kodebarcode }} </td>
                <td>{{ $item->nama_sparepart }} </td>
                <td>{{ number_format($item->harga_beli, 2, ',', '.') }} </td>
                <td>{{  number_format($item->harga_jual, 2, ',', '.') }} </td>
                <td>{{ $item->stok }}</td>
               
                <td>
                    <button type="button" class="btn btn-primary btn-sm" onclick="edit('{{ $item->kodebarcode }}')">
                        <i class="fa fa-edit"></i>
                    </button>
                    <button type="button" class="btn btn-danger btn-sm" onclick="hapus('{{ $item->kodebarcode }}')">
                        <i class="fa fa-trash"></i>
                    </button>
                </td>
            </tr>    
            
            @endforeach
        <tbody>
            
        </tbody>
    </table>
</form>

<script>
    $(document).ready(function() {
        $('#listsparepart').DataTable();

        $('#centangSemua').click(function(e) {
            if ($(this).is(':checked')) {
                $('.centangSparepartid').prop('checked', true);
            } else {
                $('.centangSparepartid').prop('checked', false);
            }
        });

        $('.formhapus').submit(function(e) {
            e.preventDefault();
            let jmldata = $('.centangSparepartid:checked');
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
                                listsparepart();
                            }
                        });
                    }
                })
            }
        });
    });

    function edit(kodebarcode) {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type: "post",
            url: "{{ url('sparepart/formedit') }}",
            data: {
                kodebarcode: kodebarcode
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

    function hapus(kodebarcode) {
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
                    url: "{{ url('sparepart/hapus') }}",
                    type: "post",
                    dataType: "json",
                    data: {
                        kodebarcode: kodebarcode
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
                            listsparepart();
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