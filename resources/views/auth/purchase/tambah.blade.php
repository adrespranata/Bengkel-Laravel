@extends('layout.script')

@section('judul')
<div class="col-sm-6">
    <h4 class="page-title">{{ $title }}</h4>
</div>
<div class="col-sm-6">
    <ol class="breadcrumb float-right">
        <li class="breadcrumb-item"><a href="javascript:void(0);">Purchase</a></li>
        <li class="breadcrumb-item active">Tambah Purchase</li>
    </ol>
</div>
@endSection

@section('isi')
<div class="card card-default color-palette-box">
    <div class="card-header">
        <button type="button" class="btn btn-warning" onclick="window.location='{{ url('auth/purchase') }}'">
            <i class="fa fa-backward"></i> Kembali
        </button>
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
                    <label>Cari Supplier</label>
                    <div class="input-group mb-3">
                        <input type="hidden" name="supplier_id" id="supplier_id">
                        <input type="text" class="form-control" name="nama_supplier" id="nama_supplier" placeholder="Nama Supplier" readonly>
                        <div class="input-group-append">
                            <button class="btn btn-sm btn-primary" type="button" id="tombolCariSupplier">
                                <i class="fa fa-search"></i>
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
            <div class="col-md dataPurchaseDetail"></div>
        </div>
        <hr>
        <div class="row justify-content-end">
            <div class="form-group">
                <div class="input-group">
                    <button class="btn btn-danger" type="button" id="btnHapusPurchase" title="Hapus Transaksi">
                        <i class="fa fa-trash-alt"></i> Batal Transaksi
                    </button>&nbsp;
                    <button class="btn btn-success" type="button" id="btnSimpanPurchase" title="Simpan Transaksi">
                        <i class="fa fa-save"></i> Simpan Transaksi
                    </button>&nbsp;
                </div>
            </div>
        </div>
    </div>
</div>

<div class="viewmodal" style="display: none;"></div>

<div class="viewmodalpembayaran" style="display: none;"></div>

<div class="viewmodalsupplier" style="display: none;"></div>

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

    //supplier
    $('#tombolCariSupplier').click(function(e) {
        e.preventDefault();
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type: "post",
            url: "{{ url('supplier/modalData') }}",
            dataType: "json",
            success: function(response) {
                if (response.data) {
                    $('.viewmodalsupplier').html(response.data).show();
                    $('#modalsupplier').modal('show');
                }
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
            }
        });
    })

    //produk
    $(document).ready(function() {

        dataPurchaseDetail();
        hitungTotalBayar();

        $('#harga_beli').autoNumeric('init', {
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

        $('#btnHapusPurchase').click(function(e) {
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
                        url: "{{ url('purchase/batalPurchase') }}",
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

        $('#btnReload').click(function(e) {
            e.preventDefault();
            dataPurchaseDetail();
        });

        $('#btnSimpanPurchase').click(function(e) {
            e.preventDefault();
            pembayaran();
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
                url: "{{ url('purchase/viewDataProduk') }}",
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
                url: "{{ url('purchase/tempPurchase') }}",
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
                            url: "{{ url('purchase/viewDataProduk') }}",
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
                        dataPurchaseDetail();
                        kosong();
                    }
                },
                error: function(xhr, thrownError) {
                    alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
                }
            });
        }
    }

    function dataPurchaseDetail() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type: "post",
            url: "{{ url('purchase/dataDetail') }}",
            dataType: "json",
            data: {
                nofaktur: $('#nofaktur').val()
            },
            beforeSend: function() {
                $('.dataPurchaseDetail').attr('disable', 'disable');
                $('.dataPurchaseDetail').html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> <i>Loading...</i>');
            },
            success: function(response) {
                if (response.data) {
                    $('.dataPurchaseDetail').html(response.data);
                }
            },
            error: function(xhr, thrownError) {
                alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
            }
        });
    }

    function hitungTotalBayar() {
        $.ajax({
            url: "{{ url('purchase/hitungTotalBayar') }}",
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
        $('#kodebarcode').val('');
        $('#nama_sparepart').val('');
        $('#stok').val('');

        $('#kodebarcode').focus();

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
            url: "{{ url('purchase/pembayaran') }}",
            dataType: "json",
            data: {
                nofaktur: nofaktur,
                supplier_id: $('#supplier_id').val(),
                nama_supplier: $('#nama_supplier').val()
            },
            success: function(response) {
                if (response.error) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Maaf!',
                        text: response.error
                    })
                }

                if (response.data) {
                    dataPurchaseDetail();
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