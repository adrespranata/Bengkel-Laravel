<!-- Modal -->
<div class="modal fade" id="modaledit" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{{ $title }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ url('pelanggan/update') }}" class="formpelanggan">
                @csrf
                <div class="modal-body">

                    <input type="hidden" class="form-control" id="pelanggan_id" value="{{ $pelanggan_id }}" name="pelanggan_id" readonly>

                    <div class="form-group row">
                        <label for="" class="col-sm-4 col-form-label">Nama</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="nama_pelanggan" value="{{ $nama_pelanggan }}" name="nama_pelanggan">
                            <div class="invalid-feedback errorNama_pelanggan">

                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="" class="col-sm-4 col-form-label">Telephone</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="telephone" value="{{ $telephone }}" name="telephone">
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
        $('.formpelanggan').submit(function(e) {
            e.preventDefault();
            $.ajax({
                type: "post",
                url: $(this).attr('action'),
                data: $(this).serialize(),
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

                        if (response.error.nama_pelanggan) {
                            $('#nama_pelanggan').addClass('is-invalid');
                            $('.errorNama_pelanggan').html(response.error.nama_pelanggan);
                        } else {
                            $('#nama_pelanggan').removeClass('is-invalid');
                            $('.errorNama_pelanggan').html('');
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
                        $('#modaledit').modal('hide');
                        listpelanggan();
                    }
                }
            });
        })
    });
</script>