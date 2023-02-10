<div class="modal fade" id="modalitem" tabindex="-1" aria-labelledby="modalitemLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalitemLabel">{{ $title }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-striped dt-responsive" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Barcode</th>
                            <th>Nama Sparepart</th>
                            <th>Harga Jual</th>
                            <th>Qty</th>
                            <th>Sub Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($tampildetitem as $row)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $row['kode'] }}</td>
                                <td>{{ $row['nama_sparepart'] }}</td>
                                <td>{{ number_format($row['hargajual'], 2, ",", ".") }}</td>
                                <td>{{ $row['qty'] }}</td>
                                <td>{{ number_format($row['total'], 0, ",", ".") }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>