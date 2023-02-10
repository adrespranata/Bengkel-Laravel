@extends('layout.script')

@section('judul')
<div class="col-sm-6">
    <h4 class="page-title">{{ $title }}</h4>
</div>
<div class="col-sm-6">
    <ol class="breadcrumb float-right">
        <li class="breadcrumb-item"><a href="javascript:void(0);">Sale</a></li>
        <li class="breadcrumb-item active">Tambah Sale</li>
    </ol>
</div>
@endSection

@section('isi')
<div class="card card-default color-palette-box">
    <div class="card-header">
        <h3 class="card-title">
            <button type="button" class="btn btn-warning" onclick="window.location='{{ url('auth/sale') }}'">
                <i class="fa fa-backward"></i> Kembali
            </button>
        </h3>
    </div>

    <div class="card-body">
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label>Faktur</label>
                    <input type="text" class="form-control" name="nofaktur" id="nofaktur" value="{{ $nofaktur }}" readonly>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="tanggal">Tanggal</label>
                    <input type="date" class="form-control" name="tanggal" id="tanggal" readonly value="{{ date('Y-m-d') }}">
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label>Cari Pelanggan</label>
                    <div class="input-group mb-3">
                        <input type="hidden" name="pelanggan_id" id="pelanggan_id">
                        <input type="text" class="form-control" name="nama_pelanggan" id="nama_pelanggan" placeholder="Nama Pelanggan" readonly>
                        <div class="input-group-append">
                            <button class="btn btn-sm btn-primary" type="button" id="tombolCariPelanggan" title="Cari Pelanggan">
                                <i class="fa fa-search"></i>
                            </button>
                            <button class="btn btn-sm btn-success" type="button" id="tombolTambahPelanggan" title="Tambah Pelanggan">
                                <i class="fa fa-plus-square"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header bg-primary text-white">
                Cari Data Barang
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="kodebarcode">Barcode</label>
                            <input type="text" class="form-control" name="kodebarcode" id="kodebarcode">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="nama_sparepart">Nama Sparepart</label>
                            <input type="text" style="font-weight: bold;" class="form-control" name="nama_sparepart" id="nama_sparepart" readonly>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="stok">Stok Tersedia</label>
                            <input type="text" style="font-weight: bold;" class="form-control" name="stok" id="stok" readonly>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="qty">Qty</label>
                            <input type="number" class="form-control" name="qty" id="qty" onkeypress="return isNumeric(event)" oninput="maxLengthCheck(this)" value="1" min="1" max="999" maxlength="3">
                        </div>
                    </div>
                    <div class="col-md-1">
                        <div class="form-group">
                            <label for="tanggal">Aksi</label>
                            <div class="input-group">
                                <button class="btn btn-secondary " type="button" id="btnReload" title="Reload Data">
                                    <i class="fa fa-sync-alt"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md">
                <div class="form-group">
                    <label for="totalbayar">Total Bayar</label>
                    <input type="text" class="form-control form-control-lg" name="totalbayar" id="totalbayar" style="text-align: right; color:blue; font-weight : bold; font-size:30pt;" value="0" readonly>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md dataSaleDetail"></div>
        </div>
        <hr>
        <div class="row justify-content-end">
            <div class="form-group">
                <div class="input-group">
                    <button class="btn btn-danger btn-sm" type="button" id="btnHapusSale">
                        <i class="fa fa-trash-alt"></i> Batal Transaksi
                    </button>&nbsp;
                    <button class="btn btn-success" type="button" id="btnSimpanSale">
                        <i class="fa fa-save"></i> Simpan Transaksi
                    </button>&nbsp;
                </div>
            </div>
        </div>
    </div>
</div>

<div class="viewmodal" style="display: none;"></div>

<div class="viewmodalpembayaran" style="display: none;"></div>

<div class="viewmodalpelanggan" style="display: none;"></div>

<script>
    //Membatasi inputan angka pada qty
    function maxLengthCheck(object) {
        if (object.value.length > object.maxLength)
            object.value = object.value.slice(0, object.maxLength)
    }

    function isNumeric(evt) {
        var theEvent = evt || window.event;
        var key = theEvent.keyCode || theEvent.which;
        key = String.fromCharCode(key);
        var regex = /[0-9]|\./;
        if (!regex.test(key)) {
            theEvent.returnValue = false;
            if (theEvent.preventDefault) theEvent.preventDefault();
        }
    }

    //customer
    $('#tombolCariPelanggan').click(function(e) {
        e.preventDefault();
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: "{{ url('/pelanggan/modalData') }}",
            type: "post",
            dataType: "json",
            success: function(response) {
                if (response.data) {
                    $('.viewmodalpelanggan').html(response.data).show();
                    $('#modalpelanggan').modal('show');
                }
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
            }
        });
    })

    $('#tombolTambahPelanggan').click(function(e) {
        e.preventDefault();
        $.ajax({
            url: "{{ url('pelanggan/formtambah') }}",
            dataType: "json",
            success: function(response) {
                if (response.data) {
                    $('.viewmodalpelanggan').html(response.data).show();
                    $('#modaltambah').modal('show');
                }
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
            }
        });
    });


    //produk
    $(document).ready(function() {

        dataSaleDetail();
        hitungTotalBayar();

        $('#harga_jual').autoNumeric('init', {
            aSep: ',',
            aDec: '.',
            mDec: '0'
        });



        $('#kodebarcode').keydown(function(e) {
            if (e.keyCode == 13) {
                e.preventDefault();
                cekKode();
            }
        });

        $('#btnHapusSale').click(function(e) {
            Swal.fire({
                title: 'Hapus data?',
                text: `Apakah anda ingin membatalkan transaksi ?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya!',
                cancelButtonText: 'Tidak'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ url('sale/batalSale') }}",
                        type: "post",
                        dataType: "json",
                        data: {
                            nofaktur: $('#nofaktur').val()
                        },
                        success: function(response) {
                            if (response.sukses == 'berhasil') {
                                window.location.reload();
                            }
                        },
                        error: function(xhr, ajaxOptions, thrownError) {
                            alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
                        }
                    });
                }
            })
        });

        $('#btnSimpanSale').click(function(e) {
            e.preventDefault();
            pembayaran();
        });
        $('#btnReload').click(function(e) {
            e.preventDefault();
            dataSaleDetail();
        });
    });

    function cekKode() {
        let kode = $('#kodebarcode').val();

        if (kode.length == 0) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: "post",
                url: "{{ url('sale/viewDataProduk') }}",
                dataType: "json",
                success: function(response) {
                    $('.viewmodal').html(response.data).show();

                    $('#modalproduk').modal('show');
                },
                error: function(xhr, thrownError) {
                    alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
                }
            });
        } else {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: "post",
                url: "{{ url('sale/tempSale') }}",
                data: {
                    kodebarcode: kode,
                    nama_sparepart: $('#nama_sparepart').val(),
                    qty: $('#qty').val(),
                    nofaktur: $('#nofaktur').val()
                },
                dataType: "json",
                success: function(response) {
                    if (response.totaldata == 'banyak') {
                        $.ajax({
                            url: "{{ url('sale/viewDataProduk') }}",
                            dataType: "json",
                            data: {
                                keyword: kode
                            },
                            type: "post",
                            success: function(response) {
                                $('.viewmodal').html(response.data).show();

                                $('#modalproduk').modal('show');
                            },
                            error: function(xhr, thrownError) {
                                alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
                            }
                        });
                    }

                    if (response.sukses == 'berhasil') {
                        dataSaleDetail();
                        kosong();
                    }

                    if (response.error) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error..!',
                            html: response.error
                        });
                        dataSaleDetail();
                        kosong();
                    }
                },
                error: function(xhr, thrownError) {
                    alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
                }
            });
        }
    }

    function dataSaleDetail() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type: "post",
            url: "{{ url('sale/dataDetail') }}",
            dataType: "json",
            data: {
                nofaktur: $('#nofaktur').val()
            },
            beforeSend: function() {
                $('.dataSaleDetail').attr('disable', 'disable');
                $('.dataSaleDetail').html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> <i>Loading...</i>');
            },
            success: function(response) {
                if (response.data) {
                    $('.dataSaleDetail').html(response.data);
                }
            },
            error: function(xhr, thrownError) {
                alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
            }
        });
    }

    function hitungTotalBayar() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: "{{ url('sale/hitungTotalBayar') }}",
            dataType: "json",
            type: "post",
            data: {
                nofaktur: $('#nofaktur').val()
            },
            success: function(response) {
                if (response.totalbayar) {
                    $('#totalbayar').val(response.totalbayar)
                }
            },
            error: function(xhr, thrownError) {
                alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
            }
        });
    }

    function kosong() {
        $('#kodebarcode').val('')
        $('#nama_sparepart').val('')
        $('#stok').val('')

        $('#kodebarcode').focus()

        hitungTotalBayar();
    }

    function pembayaran() {
        let nofaktur = $('#nofaktur').val()
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type: "post",
            url: "{{ url('sale/pembayaran') }}",
            dataType: "json",
            data: {
                nofaktur: nofaktur,
                tglfaktur: $('#tanggal').val(),
                pelanggan_id: $('#pelanggan_id').val(),
                nama_pelanggan: $('#nama_pelanggan').val()
            },
            success: function(response) {
                if (response.error) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Maaf!',
                        text: response.error
                    })
                }

                if (response.data) {
                    dataSaleDetail();
                    $('.viewmodalpembayaran').html(response.data).show();

                    $('#modalpembayaran').modal('show');
                }
            },
            error: function(xhr, thrownError) {
                alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
            }
        });
    }

</script>
@endSection