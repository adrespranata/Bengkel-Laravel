<div class="modal fade" id="modalsupplier" tabindex="-1" aria-labelledby="modalsupplierLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalsupplierLabel"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table id="datasupplier" class="table table-striped dt-responsive" style="border-collapse: collapse; border-spacing: 0; width: 100%;" role="grid">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nama Supplier</th>
                            <th>Alamat</th>
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
        listDataSupplier();
    });

    function listDataSupplier() {
        var table = $('#datasupplier').DataTable({
            processing: true,
            serverside: true,
            order: [],
            ajax: {
                url:"{{ url('supplier/cariDataSupplier') }}",
                type: "POST"
            },
            columnDefs: [{
                targets: [0],
                orderable: false,
            }, ],
        });
    }

    function pilihsupplier(supplier_id, nama_supplier) {
        $('#nama_supplier').val(nama_supplier);
        $('#supplier_id').val(supplier_id);


        $('#modalsupplier').on('hidden.bs.modal', function(event) {
            $('#kodebarcode').focus();
        });

        $('#modalsupplier').modal('hide');
    }
</script>