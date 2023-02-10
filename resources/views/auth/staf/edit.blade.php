<!-- Modal -->
<div class="modal fade" id="modaledit" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"><?= $title ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ url('staf/update') }}" class="formstaf">
                @csrf
                <div class="modal-body">

                    <input type="hidden" class="form-control" id="staf_id" value="{{ $staf_id }}" name="staf_id" readonly>

                    <div class="form-group row">
                        <label for="" class="col-sm-4 col-form-label">Nama</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="nama_staf" value="{{ $nama_staf }}" name="nama_staf">
                            <div class="invalid-feedback errorNamastaf">

                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-sm-4 col-form-label">Tempat Lahir</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="tmp_lahir" value="{{ $tmp_lahir }}" name="tmp_lahir">
                            <div class="invalid-feedback errorTmp_lahir">

                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-sm-4 col-form-label">Tanggal Lahir</label>
                        <div class="col-sm-8">
                            <input type="date" class="form-control" id="tgl_lahir" value="{{ $tgl_lahir }}" name="tgl_lahir">
                            <div class="invalid-feedback errorTgl_lahir">

                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="" class="col-sm-4 col-form-label">Alamat</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="alamat" value="{{ $alamat }}" name="alamat">
                            <div class="invalid-feedback errorAlamat">

                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-sm-4 col-form-label">Pendidikan</label>
                        <div class="col-sm-8">
                            <select name="pendidikan" id="pendidikan" class="form-control">
                                <option value="SMA/SMK" <?php if ($pendidikan == 'SMA/SMK') echo "selected"; ?>>SMA/SMK</option>
                                <option value="DIII" <?php if ($pendidikan == 'DIII') echo "selected"; ?>>DIII</option>
                                <option value="S1" <?php if ($pendidikan == 'S1') echo "selected"; ?>>S1</option>
                                <option value="S2" <?php if ($pendidikan == 'S2') echo "selected"; ?>>S2</option>
                                <option value="S3" <?php if ($pendidikan == 'S3') echo "selected"; ?>>S3</option>

                            </select>
                            <div class="invalid-feedback errorPendidikan">

                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="" class="col-sm-4 col-form-label">Jabatan</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="jabatan" value="{{ $jabatan }}" name="jabatan">
                            <div class="invalid-feedback errorJabatan">

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
        $('.formstaf').submit(function(e) {
            e.preventDefault();
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

                        if (response.error.nama_staf) {
                            $('#nama_staf').addClass('is-invalid');
                            $('.errorNamastaf').html(response.error.nama_staf);
                        } else {
                            $('#nama_staf').removeClass('is-invalid');
                            $('.errorNamastaf').html('');
                        }

                        if (response.error.tmp_lahir) {
                            $('#tmp_lahir').addClass('is-invalid');
                            $('.errorTmp_lahir').html(response.error.tmp_lahir);
                        } else {
                            $('#tmp_lahir').removeClass('is-invalid');
                            $('.errorTmp_lahir').html('');
                        }

                        if (response.error.tgl_lahir) {
                            $('#tgl_lahir').addClass('is-invalid');
                            $('.errorTgl_lahir').html(response.error.tgl_lahir);
                        } else {
                            $('#tgl_lahir').removeClass('is-invalid');
                            $('.errorTgl_lahir').html('');
                        }

                        if (response.error.alamat) {
                            $('#alamat').addClass('is-invalid');
                            $('.errorAlamat').html(response.error.alamat);
                        } else {
                            $('#alamat').removeClass('is-invalid');
                            $('.errorAlamat').html('');
                        }

                        if (response.error.pendidikan) {
                            $('#pendidikan').addClass('is-invalid');
                            $('.errorPendidikan').html(response.error.pendidikan);
                        } else {
                            $('#pendidikan').removeClass('is-invalid');
                            $('.errorPendidikan').html('');
                        }

                        if (response.error.jabatan) {
                            $('#jabatan').addClass('is-invalid');
                            $('.errorJabatan').html(response.error.jabatan);
                        } else {
                            $('#jabatan').removeClass('is-invalid');
                            $('.errorJabatan').html('');
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
                        liststaf();
                    }
                }
            });
        })
    });
</script>