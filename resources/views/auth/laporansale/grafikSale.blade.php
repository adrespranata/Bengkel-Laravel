<canvas id="myChart" style="height: 50vh; width: 80vh;">
</canvas>

<?php
$tanggal = "";
$total = "";

foreach ($grafik as $row) :
    $tgl = date('d-m-Y', strtotime($row->tgl));
    $tanggal .= "'$tgl'" . ",";

    $totalharga = $row->jual_total;
    $total .= "'$totalharga'" . ",";
endforeach;
?>

<script>
    var ctx = document.getElementById('myChart').getContext('2d');
    var chart = new Chart(ctx, {
        type: 'bar',
        responsive: true,
        data: {
            labels: [<?= $tanggal ?>],
            datasets: [{
                label: 'Total Harga',
                backgroundColor: [
                    'rgba(255,0,0)',
                    'rgb(255,128,0)',
                    'rgb(255,255,0)',
                    'rgb(128,255,0)',
                    'rgb(0,255,0)',
                    'rgb(0,255,128)',
                    'rgb(0,255,255)',
                    'rgb(0,128,255)',
                    'rgb(0,0,255)',
                    'rgb(127,0,255)',
                    'rgb(255,0,127)',
                    'rgba(255,0,0)',
                    'rgb(255,128,0)',
                    'rgb(255,255,0)',
                    'rgb(128,255,0)',
                    'rgb(0,255,0)',
                    'rgb(0,255,128)',
                    'rgb(0,255,255)',
                    'rgb(0,128,255)',
                    'rgb(0,0,255)',
                    'rgb(127,0,255)',
                    'rgb(255,0,127)',
                    'rgba(255,0,0)',
                    'rgb(255,128,0)',
                    'rgb(255,255,0)',
                    'rgb(128,255,0)',
                    'rgb(0,255,0)',
                    'rgb(0,255,128)',
                    'rgb(0,255,255)',
                    'rgb(0,128,255)',
                    'rgb(0,0,255)',
                ],
                data: [
                    <?= $total ?>
                ]
            }]
        },
        duration: 1000
    })
</script>