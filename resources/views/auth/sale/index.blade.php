@extends('layout.script')

@section('judul')
<div class="col-sm-6">
    <h4 class="page-title">{{ $title }}</h4>
</div>
<div class="col-sm-6">
    <ol class="breadcrumb float-right">
        <li class="breadcrumb-item"><a href="javascript:void(0);">Sale</a></li>
        <li class="breadcrumb-item active">List Sale</li>
    </ol>
</div>
@endSection

@section('isi')
<p class="sub-title">
    <button type="button" class="btn btn-primary btn-sm" onclick="window.location='{{ url('sale/formtambah') }}'">
        <i class=" fa fa-plus-circle"></i> Tambah transaksi
    </button>
</p>

<div class="viewdata">
</div>

<script>
    $(document).ready(function() {
        listsale();
    });

    function listsale() {
        $.ajax({
            url: "{{ url('sale/getdata') }}",
            dataType: "json",
            success: function(response) {
                $('.viewdata').html(response.data);
            }
        });
    }
</script>
@endSection