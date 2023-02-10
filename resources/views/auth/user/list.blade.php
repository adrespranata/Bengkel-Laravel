<form action="{{ url('konfigurasi/hapusalluser') }}" class="formhapus">
    @csrf
<button type="submit" class="btn btn-danger btn-sm">
    <i class="fa fa-trash"></i> Hapus yang diceklist
</button>

<hr>
<table id="listuser" class="table table-striped dt-responsive " style="border-collapse: collapse; border-spacing: 0; width: 100%;">
    <thead>
        <tr>
            <th>
                <input type="checkbox" id="centangSemua">
            </th>
            <th>#</th>
            <th>Username</th>
            <th>Nama</th>
            <th>Email</th>
            <th>Level</th>
            <th>Status</th>
            <th>Foto</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($list as $item)
        <tr>
            <td>
                <input type="checkbox" name="user_id[]" class="centangUserid" value="{{ $item->user_id }} ">
            </td>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $item->username }} </td>
            <td>{{ $item->nama }}</td>
            <td>{{ $item->email }} </td>
            <td>
                @if ($item->level == '1')
                    <h6>
                        <span class="badge badge-primary">Admin</span>
                    </h6>
                @else
                    <h6>
                        <span class="badge badge-info">Kasir</span>
                    </h6>
                @endif
            </td>
            <td>
                @if ($item->active == '1')
                    <h6>
                        <span class="badge badge-success">Aktif</span>
                    </h6>
                @else
                    <h6>
                        <span class="badge badge-danger">Tidak Aktif</span>
                    </h6>
                @endif
            </td>
        
            <td class="text-center"><img onclick="gambar('{{ $item->user_id }}')" src="{{ url('img/user/thumb/' . 'thumb_' . $item->foto) }}" width="120px" class="img-thumbnail"></td>
            <td>
                <button type="button" class="btn btn-primary btn-sm" onclick="edit('{{ $item->user_id }}')">
                    <i class="fa fa-edit"></i>
                </button>
                <button type="button" class="btn btn-danger btn-sm" onclick="hapus('{{ $item->user_id }}')">
                    <i class="fa fa-trash"></i>
                </button>
                @if ($item->username != 'admin')
                    <button type="button" onclick="toggle('{{ $item->user_id }}')" class="btn btn-circle btn-sm {{ $item->active ? 'btn-secondary' : 'btn-success' }}" title="{{ $item->active ? 'Nonaktifkan' : 'Aktifkan' }}"><i class="fa fa-fw fa-power-off"></i>
                    </button>
                @endif 

            </td>
        </tr>    
        
        @endforeach
    </tbody>
</table>
</form>
<script>
    $(document).ready(function() {
        $('#listuser').DataTable();

        $('#centangSemua').click(function(e) {
            if ($(this).is(':checked')) {
                $('.centangUserid').prop('checked', true);
            } else {
                $('.centangUserid').prop('checked', false);
            }
        });

        $('.formhapus').submit(function(e) {
            e.preventDefault();
            let jmldata = $('.centangUserid:checked');
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
                    title: 'Hapus user',
                    text: `Apakah anda yakin ingin menghapus sebanyak ${jmldata.length} user?`,
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
                                listuser();
                            }
                        });
                    }
                })
            }
        });
    });

    function toggle(user_id) {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type: "post",
            url: "{{ url('konfigurasi/toggle') }}",
            data: {
                user_id: user_id
            },
            dataType: "json",
            success: function(response) {
                if (response.sukses) {
                    Swal.fire({
                        icon: 'success',
                        title: response.sukses,
                        showConfirmButton: false,
                        timer: 1500
                    })
                    listuser();
                }
            }
        });
    }

    function edit(user_id) {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type: "post",
            url: "{{ url('konfigurasi/formedit') }}",
            data: {
                user_id: user_id
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

    function hapus(user_id) {
        Swal.fire({
            title: 'Hapus user?',
            text: `Apakah anda yakin menghapus user?`,
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
                    url: "{{ url('konfigurasi/hapususer') }}",
                    type: "post",
                    dataType: "json",
                    data: {
                        user_id: user_id
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
                            listuser();
                        }
                    }
                });
            }
        })
    }

    function gambar(user_id) {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type: "post",
            url: "{{ url('konfigurasi/formuploaduser') }}",
            data: {
                user_id: user_id
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