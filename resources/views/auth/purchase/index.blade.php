@extends('layout.script')

@section('judul')
<div class="col-sm-6">
    <h4 class="page-title">{{ $title }}</h4>
</div>
<div class="col-sm-6">
    <ol class="breadcrumb float-right">
        <li class="breadcrumb-item"><a href="javascript:void(0);">Purchase</a></li>
        <li class="breadcrumb-item active">List Purchase</li>
    </ol>
</div>
@endSection

@section('isi')
<p class="sub-title">
    <button type="button" class="btn btn-primary btn-sm" onclick="window.location='{{ url('purchase/formtambah') }}'">
        <i class=" fa fa-plus-circle"></i> Tambah Purchase
    </button>
</p>

<div class="viewdata">
</div>

<script>
    $(document).ready(function() {
        listpurchase();
    });

    function listpurchase() {
        $.ajax({
            url: "{{ url('purchase/getdata') }}",
            dataType: "json",
            success: function(response) {
                $('.viewdata').html(response.data);
            }
        });
    }
</script>
@endSection