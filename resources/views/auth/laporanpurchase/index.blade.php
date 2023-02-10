@extends('layout.script')

@section('judul')
<div class="col-sm-6">
    <h4 class="page-title">{{ $title }} </h4>
</div>
<div class="col-sm-6">
    <ol class="breadcrumb float-right">
        <li class="breadcrumb-item"><a href="javascript:void(0);">Laporan Purchase</a></li>
        <li class="breadcrumb-item active">Cetak Laporan</li>
    </ol>
</div>
@endSection

@section('isi')
<div class="card card-default color-palette-box">
    <div class="card-body">
        <div class="row">
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        Pilih Periode
                    </div>
                    <div class="card-body">
                        <form action="{{ url('laporan/cetakpurchase') }}" target="_blank">
                            <div class="form-group">
                                <label for="">Tanggal Awal</label>
                                <input type="date" name="tglawal" id="tglawal" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="">Tanggal Akhir</label>
                                <input type="date" name="tglakhir" id="tglakhir" class="form-control" required>
                            </div>
                        
                            <div class="form-group">
                                <button type="submit" name="btnCetak" class="btn btn-block btn-danger">
                                    <i class="fa fa-file-pdf"></i> Export PDF
                                </button>
                            </div>
                        </form>
                        
                    </div>
                </div>
            </div>
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        Laporan Grafik
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="">Pilih Bulan</label>
                            <input type="month" class="form-control" id="bulan" value="{{ date('Y-m') }}">
                            <br>
                            <button type="button" class="btn btn-block btn-primary" id="btnTampil">
                                <i class="fa fa-eye"></i> Tampil Grafik
                            </button>
                        </div>
                        <div class="viewTampilGrafik">

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $('#btnCetak').click(function (e) { 
        e.preventDefault();
        var tglawal = $('#tglawal').val();
        var tglakhir = $('#tglakhir').val();
        window.location.href = ("{{ url('laporan/cetakpurchase/') }}") + '/' + tglawal + '/' + tglakhir;
    });

    
    $(document).ready(function() {
        tampilGrafik();
        $('#btnTampil').click(function(e) {
            e.preventDefault();
            tampilGrafik();
        });
    });

    function tampilGrafik() {
        $.ajax({
            type: "get",
            url: "{{ url('laporan/tampilGrafikPurchase') }}",
            dataType: "json",
            data: {
                bulan: $('#bulan').val()
            },
            beforeSend: function() {
                $('.viewTampilGrafik').attr('disable', 'disable');
                $('.viewTampilGrafik').html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> <i>Loading...</i>');
            },
            success: function(response) {
                if (response.data) {
                    $('.viewTampilGrafik').html(response.data);
                }
            },
            error: function(xhr, thrownError) {
                alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
            }
        });
    }
</script>
@endSection