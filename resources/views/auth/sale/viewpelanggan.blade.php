<div class="modal fade" id="modalpelanggan" tabindex="-1" aria-labelledby="modalpelangganLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalpelangganLabel"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table id="datapelanggan" class="table table-striped dt-responsive" style="border-collapse: collapse; border-spacing: 0; width: 100%;" role="grid">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nama Pelanggan</th>
                            <th>Telephone</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        listDataPelanggan();
    });

    function listDataPelanggan() {
        var table = $('#datapelanggan').DataTable({
            processing: true,
            serverside: true,
            order: [],
            ajax: {
                url: "{{ url('/pelanggan/cariDataPelanggan') }}",
                type: "POST"
            },
            columnDefs: [{
                targets: [0],
                orderable: false,
            }, ],
        });
    }

    function pilihpelanggan(pelanggan_id, nama_pelanggan) {
        $('#nama_pelanggan').val(nama_pelanggan);
        $('#pelanggan_id').val(pelanggan_id);


        $('#modalpelanggan').on('hidden.bs.modal', function(event) {
            $('#kodebarcode').focus();
        });

        $('#modalpelanggan').modal('hide');
    }
</script>