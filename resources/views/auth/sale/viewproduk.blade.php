<!-- DataTables -->
<link href="{{ URL::to('/assets/plugins/datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{ URL::to('/assets/plugins/datatables/responsive.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ URL::to('/assets/plugins/datatables/buttons.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />

<!-- Required datatable js -->
<script src="{{ URL::to('/assets/plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ URL::to('/assets/plugins/datatables/dataTables.bootstrap4.min.js') }}"></script>
<!-- Responsive examples -->
<script src="{{ URL::to('/assets/plugins/datatables/dataTables.responsive.min.js') }}"></script>
<script src="{{ URL::to('/assets/plugins/datatables/responsive.bootstrap4.min.js') }}"></script>
<!-- Modal -->
<div class="modal fade" id="modalproduk" tabindex="-1" aria-labelledby="modalprodukLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalprodukLabel"><?= $title ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="keyData" id="keyData" value="<?= $keyword; ?>">

                <table id="dataproduk" class="table table-striped dt-responsive" style="border-collapse: collapse; border-spacing: 0; width: 100%;" role="grid">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Barcode</th>
                            <th>Nama Sparepart</th>
                            <th>Harga Jual</th>
                            <th>Stok</th>
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
<script type="text/javascript">
    $('#harga_jual').autoNumeric('init', {
        aSep: ',',
        aDec: '.',
        mDec: '0'
    });

    $(document).ready(function() {
        var table = $('#dataproduk').DataTable({
            processing: true,
            serverside: true,
            order: [],
            ajax: {
                url: "{{ url('sale/listDataProduk') }}",
                type: "POST",
                data: {
                    keyData: $('#keyData').val()
                }
            },
            columnDefs: [{
                    targets: 0,
                    orderable: false,
                },
                {
                    targets: -1,
                    orderable: false,
                },
            ],
        });
    });

    function pilihsparepart(kodebarcode, nama_sparepart, stok) {
        $('#kodebarcode').val(kodebarcode);
        $('#nama_sparepart').val(nama_sparepart);
        $('#stok').val(stok);


        $('#modalproduk').on('hidden.bs.modal', function(event) {
            $('#kodebarcode').focus();
        });

        $('#modalproduk').modal('hide');
    }
</script>