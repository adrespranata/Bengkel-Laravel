@extends('layout.script')

@section('judul')
<div class="col-sm-6">
    <h4 class="page-title">{{ $title }}</h4>
</div>
<div class="col-sm-6">
    <ol class="breadcrumb float-right">
        <li class="breadcrumb-item"><a href="javascript:void(0);">Sparepart</a></li>
        <li class="breadcrumb-item active">List Sparepart</li>
    </ol>
</div>
@endSection

@section('isi')
<p class="sub-title"> <button type="button" class="btn btn-primary btn-sm tambahsparepart"><i class=" fa fa-plus-circle"></i> Tambah Sparepart</button>
    <br>
    <small> <i class="fa fa-info-circle"></i> Klik foto untuk memperbarui foto!</small>
</p>
<div class="viewdata">
</div>

<div class="viewmodal">
</div>


<script>
    function listsparepart() {
        $.ajax({
            url: "{{ url('sparepart/getdata') }}",
            dataType: "json",
            success: function(response) {
                $('.viewdata').html(response.data);
            }
        });
    }

    $(document).ready(function() {
        listsparepart();
        $('.tambahsparepart').click(function(e) {
            e.preventDefault();
            $.ajax({
                url: "{{ url('sparepart/formtambah') }}",
                dataType: "json",
                success: function(response) {
                    $('.viewmodal').html(response.data).show();

                    $('#modaltambah').modal('show');
                }
            });
        });
    });
</script>
@endSection