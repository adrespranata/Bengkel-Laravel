<!-- Modal -->
<div class="modal fade" id="modalpembayaran" tabindex="-1" aria-labelledby="modalpembayaranLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalpembayaranLabel"><?= $title ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ url('sale/simpanSale') }}" class="formpembayaran">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="">No Faktur</label>
                        <input type="text" name="nofaktur" id="nofaktur" class="form-control" value="{{ $nofaktur }}" readonly>
                    </div>
                    <div class="form-group">
                        <label for="">Pelanggan</label>
                        <input type="hidden" name="pelanggan_id" value="{{ $pelanggan_id }}">
                        <input type="text" name="nama_pelanggan" id="nama_pelanggan" class="form-control" value="{{ $nama_pelanggan }}" readonly>
                    </div>
                    <div class="form-group">
    
                        <input type="hidden" name="totalkotor" id="totalkotor" value="{{ $totalbayar }}">
                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label for="">Disc(%)</label>
                                <input type="text" name="dispersen" id="dispersen" class="form-control" value="0" autocomplete="off">
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <label for="">Disc(Rp)</label>
                                <input type="text" name="disuang" id="disuang" class="form-control" value="0" autocomplete="off">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="">Total Pembayaran</label>
                        <input type="text" name="totalbersih" id="totalbersih" class="form-control form-control-lg" value="{{ $totalbayar }} " style="font-weight: bold; text-align: right; color: blue; font-size: 30pt;" readonly>
                    </div>
    
                    <div class="form-group">
                        <label for="">Jumlah Uang</label>
                        <input type="text" name="jumlahuang" id="jumlahuang" class="form-control" style="font-weight: bold; text-align: right; color: red; font-size: 30pt;" autocomplete="off">
                    </div>
                    <div class="form-group">
                        <label for="">Sisa Uang</label>
                        <input type="text" name="sisauang" id="sisauang" class="form-control form-control-lg" style="font-weight: bold; text-align: right; color: blue; font-size: 30pt;" readonly>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary tombolSimpan">Simpan</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        })

        $('#dispersen').autoNumeric('init', {
            aSep: ',',
            aDec: '.',
            mDec: '2'
        });
        $('#disuang').autoNumeric('init', {
            aSep: ',',
            aDec: '.',
            mDec: '2'
        });
        $('#totalbersih').autoNumeric('init', {
            aSep: ',',
            aDec: '.',
            mDec: '0'
        });
        $('#jumlahuang').autoNumeric('init', {
            aSep: ',',
            aDec: '.',
            mDec: '0'
        });
        $('#sisauang').autoNumeric('init', {
            aSep: ',',
            aDec: '.',
            mDec: '0'
        });

        $('#dispersen').keyup(function(e) {
            hitungDiskon();
        });

        $('#disuang').keyup(function(e) {
            hitungDiskon();
        });
        
        $('#jumlahuang').keyup(function(e) {
            hitungSisaUang();
        });
        
        $('.formpembayaran').submit(function(e) {
            e.preventDefault();

            let jumlahuang = ($('#jumlahuang').val() == "") ? 0 : $('#jumlahuang').autoNumeric('get');
            let sisauang = ($('#sisauang').val() == "") ? 0 : $('#sisauang').autoNumeric('get');

            if (parseFloat(jumlahuang) == 0 || parseFloat(jumlahuang) == "") {
                Toast.fire({
                    icon: 'warning',
                    title: 'Maaf Jumlah uang belum diinput ...'
                })
            } else if (parseFloat(sisauang) < 0) {
                Toast.fire({
                    icon: 'error',
                    title: 'Jumlah uang belum mencukupi'
                })
            } else {
                $.ajax({
                    type: "post",
                    url: $(this).attr('action'),
                    data: $(this).serialize(),
                    dataType: "json",
                    beforeSend: function() {
                        $('.tombolSimpan').prop('disable', true);
                        $('.tombolSimpan').html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> <i>Loading...</i>');
                    },
                    complete: function() {
                        $('.tombolSimpan').prop('disable', false);
                        $('.tombolSimpan').html('Simpan');
                    },
                    success: function(response) {
                        if (response.sukses) {
                            Swal.fire({
                                title: 'Cetak Struk?',
                                text: response.sukses + ", Cetak faktur?",
                                icon: 'warning',
                                showCancelButton: true,
                                confirmButtonColor: '#3085d6',
                                cancelButtonColor: '#d33',
                                confirmButtonText: 'Ya, Cetak!',
                                cancelButtonText: 'Tidak!'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    let windowCetak = window.open(response.cetak,
                                        "Cetak Faktur Sale",
                                        "width=500, height=600");
                                    windowCetak.focus();
                                    window.location.reload();
                                } else {
                                    window.location.reload();
                                }
                            })
                        }
                    },
                    error: function(xhr, thrownError) {
                        alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
                    }
                });
            }

            return false;
        });
    });

    function hitungDiskon() {
        let totalkotor = $('#totalkotor').val();
        let dispersen = ($('#dispersen').val() == "") ? 0 : $('#dispersen').autoNumeric('get');
        let disuang = ($('#disuang').val() == "") ? 0 : $('#disuang').autoNumeric('get');

        let hasil;
        hasil = parseFloat(totalkotor) - (parseFloat(totalkotor) * parseFloat(dispersen) / 100) - parseFloat(disuang);

        $('#totalbersih').val(hasil);
        let totalbersih = $('#totalbersih').val();
        $('#totalbersih').autoNumeric('set', totalbersih);

    }

    function hitungSisaUang() {
        let totalpembayaran = $('#totalbersih').autoNumeric('get');
        let jumlahuang = ($('#jumlahuang').val() == "") ? 0 : $('#jumlahuang').autoNumeric('get');

        sisauang = parseFloat(jumlahuang) - parseFloat(totalpembayaran);

        $('#sisauang').val(sisauang);
        let sisauangx = $('#sisauang').val();
        $('#sisauang').autoNumeric('set', sisauangx);
    }
</script>