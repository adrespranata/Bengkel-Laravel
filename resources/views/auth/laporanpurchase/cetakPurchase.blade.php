<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Purchase</title>
</head>

<body onload="window.print()">
    <hr>
    <table style="border-collapse: collapse; border-spacing: 0; width: 100%; text-align: center;">
        <tr>
            <td>
                <table style="width: 100%; text-align: center; ">
                    <tr style="text-align: center;">
                        <td>
                            <h1>Camit Bengkel</h1>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td>
                <table style="width: 100%; text-align: center; ">
                    <tr style="text-align: center;">
                        <td>
                            <h3><u>Laporan Barang Masuk</u></h3>
                            <br>
                            Periode : {{ date('d-m-Y', strtotime($tglawal))  . " s/d " . date('d-m-Y', strtotime($tglakhir)) }}
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <br>
                            <table border="1" style="border-collapse: collapse; border-spacing: 0; width: 100%; text-align: center;">
                                <thead>
                                    <tr>
                                        <td>#</td>
                                        <td>No Faktur</td>
                                        <td>Tanggal</td>
                                        <td>Total Harga</td>
                                    </tr>
                                </thead>
                                <tbody>
                                    
                                    <input type="hidden" value="{{ $totalSeluruhHarga = 0; }}">
                                    @foreach ($datalaporan as $row)
                                    <input type="hidden" value="{{ $totalSeluruhHarga += $row->beli_total}}">
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $row->beli_faktur }}</td>
                                            <td>{{ date('d-m-Y', strtotime($row->beli_date)) }}</td>
                                            <td>{{ number_format($row->beli_total, 0, ',', '.') }} </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="3">Total Seluruh Harga</td>
                                        <td>{{ number_format($totalSeluruhHarga, 0, ',', '.')  }}</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>

    </table>
</body>

</html>