<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Faktur Penjualan</title>
</head>

<body onload="window.print();">
    <table border="0" style="text-align: center; width: 100%;">
        <tr>
            <td colspan="2">
                <h2 style="height: 2px;">
                    Anjli Motor
                </h2>
                <h5 style="height: 2px;">
                    Jl. Lintas Sumatera Barat, Telp: 085268251608
                </h5>
                <hr style="border: none; border-top: 1px solid #000;">
            </td>
        </tr>
        <tr style="text-align: left;">
            <td>Faktur :</td>
            <td><?= $faktur ?></td>
        </tr>
        <tr style="text-align: left;">
            <td>Tanggal :</td>
            <td><?= date('d F Y', strtotime($tanggal)) ?></td>
        </tr>
        <tr style="text-align: left;">
            <td>Pelanggan :</td>
            <td><?= $nama_pelanggan ?></td>
        </tr>
        <tr>
            <td colspan="2">
                <hr style="border: none; border-top: 1px dashed #000;">
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <table style="width: 100%; text-align: left; font-size: 10pt;">
                    <?php
                    $totalItem = 0;
                    $jmlItem = 0;
                    $totalHarga = 0;
                    foreach ($detailbarang->get() as $row) :
                        $totalItem += $row['qty'];
                        $jmlItem++;
                        $totalHarga += $row['subtotal'];
                    ?>
                        <tr>
                            <td><?= $row['nama_sparepart'] ?></td>
                        </tr>
                        <tr>
                            <td>
                                <?= number_format($row['qty'], 0, ",", ".") . ' x' ?>
                            </td>
                            <td>
                                <?= number_format($row['hargajual'], 0, ",", ".") ?>
                            </td>
                            <td>
                                <?= number_format($row['subtotal'], 0, ",", ".") ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <tr>
                        <td colspan="3">
                            <hr style="border: none; border-top: 1px dashed #000;">
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3">
                            Jml.Item : <?= number_format($jmlItem, 0, ",", ".") . '(' . number_format($totalItem, 0, ",", ".") . ')' ?>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3">
                            <hr style="border: none; border-top: 1px dashed #000;">
                        </td>
                    </tr>
                    <tr style="text-align: right;">
                        <td></td>
                        <td>Total :</td>
                        <td><?= number_format($totalHarga, 0, ",", ".") ?></td>
                    </tr>
                    <tr style="text-align: right;">
                        <td></td>
                        <td>Jml.Uang :</td>
                        <td><?= number_format($jumlahuang, 0, ",", ".") ?></td>
                    </tr>
                    <tr style="text-align: right;">
                        <td></td>
                        <td>Sisa :</td>
                        <td><?= number_format($sisauang, 0, ",", ".") ?></td>
                    </tr>
                    <tr>
                        <td colspan="3">
                            <hr style="border: none; border-top: 1px dashed #000;">
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3">
                            Terima Kasih Atas Kunjungan Anda
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>

</html>