<!-- Modal -->
<div class="modal fade" id="modaltambah" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"><?= $title ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ url('supplier/simpan') }}" class="formtambah">
                @csrf
            <div class="modal-body">
                <div class="form-group row">
                    <label for="" class="col-sm-4 col-form-label">Nama</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" id="nama_supplier" name="nama_supplier">
                        <div class="invalid-feedback errorNama_supplier">

                        </div>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="" class="col-sm-4 col-form-label">Alamat</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" id="alamat" name="alamat">
                        <div class="invalid-feedback errorAlamat">

                        </div>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="" class="col-sm-4 col-form-label">Telephone</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" id="telephone" name="telephone">
                        <div class="invalid-feedback errorTelephone">

                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary btnsimpan"><i class="fa fa-share-square"></i> Simpan</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>

            </form>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        $('.formtambah').submit(function(e) {
            e.preventDefault();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: "post",
                url: $(this).attr('action'),
                data: {
                    nama_supplier: $('input#nama_supplier').val(),
                    alamat: $('input#alamat').val(),
                    telephone: $('input#telephone').val(),
                    foto: 'default.png'
                },
                dataType: "json",
                beforeSend: function() {
                    $('.btnsimpan').attr('disable', 'disable');
                    $('.btnsimpan').html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> <i>Loading...</i>');
                },
                complete: function() {
                    $('.btnsimpan').removeAttr('disable', 'disable');
                    $('.btnsimpan').html('<i class="fa fa-share-square"></i>  Simpan');
                },
                success: function(response) {
                    if (response.error) {

                        if (response.error.nama_supplier) {
                            $('#nama_supplier').addClass('is-invalid');
                            $('.errorNama_supplier').html(response.error.nama_supplier);
                        } else {
                            $('#nama_supplier').removeClass('is-invalid');
                            $('.errorNama_supplier').html('');
                        }

                        if (response.error.alamat) {
                            $('#alamat').addClass('is-invalid');
                            $('.errorAlamat').html(response.error.alamat);
                        } else {
                            $('#alamat').removeClass('is-invalid');
                            $('.errorAlamat').html('');
                        }

                        if (response.error.telephone) {
                            $('#telephone').addClass('is-invalid');
                            $('.errorTelephone').html(response.error.telephone);
                        } else {
                            $('#telephone').removeClass('is-invalid');
                            $('.errorTelephone').html('');
                        }

                    } else {
                        Swal.fire({
                            title: "Berhasil!",
                            text: response.sukses,
                            icon: "success",
                            showConfirmButton: false,
                            timer: 1500
                        });
                        $('#modaltambah').modal('hide');
                        listsupplier();
                    }
                }
            });
        })
    });
</script>