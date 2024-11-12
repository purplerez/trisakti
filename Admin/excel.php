<?php
header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=Transaksi_no_".$_GET['id'].".xls");

require_once __DIR__."/../config/support.php";
require '../user/session_check.php';
include "./adm/data.php";

$transaksi = new TransaksiController;

$database = new DatabaseConnection();
$conn = $database->conn;

$stmt = $conn->query('SELECT id, nama_tiket, tarif FROM tb_jenistiket');
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <link href="https://cdn.lineicons.com/4.0/lineicons.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    
    <link rel="stylesheet" href="laporan.css">
</head>
<body>
    
        <div class="main p-3">
            <div class="headerlagi">
                <img src="img/logo.png" alt="">
                <div class="judul">
                    <h4>LAPORAN PRODUKSI KAPAL</h4>
                    <h4>KMP. TRIMAS LAILA</h4>
                </div>
            </div>

            <form action="" method="post">
                <div class="atas">
                    <div>
                        <table>
                            <tr>
                                <td><label>Tanggal</label></td>
                                <td><input type="date" name="tgl" id=""></td>
                            </tr>
                            <tr>
                                <td><label class="col-sm-1 col-form-label">Trip</label></td>
                                <td><input type="text" name="trip" id=""></td>
                            </tr>
                        </table>
                    </div>

                    <div>
                        <table>
                            <tr>
                                <td><label>Pelabuhan</label></td>
                                <td><input type="text" name="pelabuhan" id=""></td>
                            </tr>
                            <tr>
                                <td><label class="col-sm-1">Jam</label></td>
                                <td><input type="time" name="waktu" id=""></td>
                            </tr>
                        </table>
                    </div>
                </div>

                <div class="box">
                    <table>
                        <thead>
                            <tr>
                                <td>No</td>
                                <td style="width: 30%">Jenis Tiket</td>
                                <td style="width: 20%">Tarif</td>
                                <td style="width: 20%">Produksi</td>
                                <td style="width: 20%">Total</td>
                            </tr>
                        </thead>
                        <tbody>
                        <?php 
                        $no = 1;
                        foreach($transaksi->index() as $rec) {
                            // Example condition to check for Golongan VII
                            if ($rec['nama_tiket'] === 'Golongan VII') {
                                ?>
                                <tr>
                                    <td colspan="4">Subtotal </td>
                                    <td id="subtotal">Rp 0</td>
                                </tr>
                                <?php
                            }
                            ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= htmlspecialchars($rec['nama_tiket']) ?></td>
                                <td>Rp <?= number_format($rec['tarif'], 0, ',', '.') ?></td>
                                <td>
                                    <input type="number" name="produksi[]" class="produksi-input" data-tarif="<?= $rec['tarif'] ?>" required>
                                    <input type="hidden" name="idjenis[]" value="<?= $rec['id'] ?>">
                                </td>
                                <td class="pendapatan">Rp 0</td>
                            </tr>
                            <?php 
                        }
                        ?>
                        </tbody>
                    <tfoot>
                    <tr>
                        <th></th>
                        <th colspan="3">Subtotal</th>
                        <th id="total-pendapatan">Rp 0</th>
                    </tr>
                    <?php 
                        foreach($transaksi->index(0) as $data) {
                    ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= $data['nama_tiket'] ?></td>
                        
                        <?php 
                            if($data['nama_tiket'] == 'Bea Cetak'){
                        ?>
                        <td id="tarif-bea-cetak"> Rp  <?= number_format($data['tarif'], 0, ',', '.') ?></td>
                            <td id="total-produksi-bea-cetak">0</td>
                            <td id="total-bea-cetak">Rp 0</td>
                        <?php } 
                        else { ?>
                        <td id="tarif-bea-sandar"> Rp  <?= number_format($data['tarif'], 0, ',', '.') ?></td>
                            <td><input type="number" id="total-produksi-bea-sandar"></td>
                            <td id="total-bea-sandar">Rp 0</td>
                        <?php 
                        } 
                        ?>
                        
                    </tr>
                    <?php } ?>
                    <!-- <tr>
                        <td><?= $no++ ?></td>
                        <td>Bea Sandar</td>
                        <td id="beasandar-value">345.690</td>
                        <td><input type="number" id="total-produksi-bea-sandar"></td>
                        
                    </tr> -->
                    <tr>
                        <th></th>
                        <th colspan="3">Total Pendapatan</th>
                        <th id="total-keseluruhan">Rp 0</th>
                    </tr>
                </tfoot>

                    </table>
                </div>
                <div class="create">
                    <p>Yang membuat <?php echo $_SESSION["username"] ?></p>
                </div>

                <div class="trans">
                   <center> <button type="submit" name="inputTransaksi">Simpan</button></center>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe"
        crossorigin="anonymous"></script>

    <script src="side.js"></script>
    <!-- <script>
    document.addEventListener('DOMContentLoaded', function () {
    // Event listener untuk input produksi
    document.querySelectorAll('.produksi-input').forEach(function(input) {
        input.addEventListener('input', hitungTotal);
    });

    // Event listener untuk input Bea Sandar
    document.getElementById('total-produksi-bea-sandar').addEventListener('input', hitungTotal);

    function hitungTotal() {
        let totalPendapatan = 0;
        let totalProduksi = 0;

        // Hitung total pendapatan dan total produksi
        document.querySelectorAll('.produksi-input').forEach(function(input) {
            let tarif = parseFloat(input.getAttribute('data-tarif'));
            let produksi = parseFloat(input.value) || 0;
            let total = tarif * produksi;
            input.closest('tr').querySelector('.pendapatan').innerText = 'Rp ' + total.toLocaleString();
            totalPendapatan += total;
            totalProduksi += produksi;
        });

        // Bea Cetak
        let beaCetakPerProduksi = 90; // Nilai tetap per produksi untuk Bea Cetak
        let beaCetak = beaCetakPerProduksi * totalProduksi;

        // Bea Sandar
        let beaSandarRate = parseFloat(document.getElementById('beasandar-value').innerText.replace(/[^0-9]/g, '')) || 0;
        let beaSandarInput = parseFloat(document.getElementById('total-produksi-bea-sandar').value) || 0;
        let beaSandar = beaSandarRate * beaSandarInput;

        // Hitung total biaya
        let totalBiaya = beaCetak + beaSandar;

        // Hitung total keseluruhan
        let totalKeseluruhan = totalPendapatan - totalBiaya;

        // Update tampilan
        document.getElementById('total-pendapatan').innerText = 'Rp ' + totalPendapatan.toLocaleString();
        document.getElementById('total-produksi-bea-cetak').innerText = totalProduksi;
        document.getElementById('total-bea-cetak').innerText = 'Rp ' + beaCetak.toLocaleString();
        document.getElementById('total-bea-sandar').innerText = 'Rp ' + beaSandar.toLocaleString();
        document.getElementById('total-keseluruhan').innerText = 'Rp ' + totalKeseluruhan.toLocaleString();
    }

    // Inisialisasi kalkulasi saat halaman dimuat
    hitungTotal();
});
</script> -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Event listener untuk input produksi
    document.querySelectorAll('.produksi-input').forEach(function(input) {
        input.addEventListener('input', calculateTotals);
    });
    let produksiTotal = 0; // Inisialisasi produksiTotal dengan 0
    let beacetak = 0;
    let beasandar = 0;

    // Listener untuk input Bea Sandar
    document.getElementById('total-produksi-bea-sandar').addEventListener('input', calculateBeaSandar);

    // Tarif Bea Cetak dan Bea Sandar (konversi ke angka)
    const tarifBeaCetak = parseFloat(document.getElementById('tarif-bea-cetak').innerText.replace(/[Rp. ]/g, '')) || 0;
    const tarifBeaSandar = parseFloat(document.getElementById('tarif-bea-sandar').innerText.replace(/[Rp. ]/g, '')) || 0;

    function calculateTotals() {
        let subtotal = 0;
        produksiTotal = 0; // Reset produksiTotal setiap kali fungsi dipanggil
        

        // Perhitungan subtotal tiket
        document.querySelectorAll('.produksi-input').forEach(function(input) {
            let tarif = parseFloat(input.getAttribute('data-tarif'));
            let production = parseFloat(input.value) || 0;
            let total = tarif * production;

            // Set pendapatan ke dalam elemen
            input.closest('tr').querySelector('.pendapatan').innerText = 'Rp ' + total.toLocaleString();

            // Hitung subtotal
            subtotal += total;

            // Tambahkan nilai produksi ke produksiTotal
            produksiTotal += production;
        });

        // Set subtotal tiket
        document.getElementById('total-pendapatan').innerText = 'Rp ' + subtotal.toLocaleString();

        // Tampilkan produksi total di dalam Bea Cetak
        document.getElementById('total-produksi-bea-cetak').innerText = produksiTotal;

        // Kalkulasi subtotal Bea Cetak
        calculateBeaCetak();

        // Kalkulasi total keseluruhan
        calculateGrandTotal(subtotal);
    }

    function calculateBeaCetak() {
        // Gunakan produksiTotal dari calculateTotals
        let totalBeaCetak = produksiTotal * tarifBeaCetak;
        beacetak = totalBeaCetak;

        // Set nilai total Bea Cetak
        document.getElementById('total-bea-cetak').innerText = 'Rp ' + totalBeaCetak.toLocaleString();
    }

    function calculateBeaSandar() {
        let produksiBeaSandar = parseFloat(document.getElementById('total-produksi-bea-sandar').value) || 0;
        let totalBeaSandar = produksiBeaSandar * tarifBeaSandar;
        beasandar = totalBeaSandar;

        // Set nilai total Bea Sandar
        document.getElementById('total-bea-sandar').innerText = 'Rp ' + totalBeaSandar.toLocaleString();
    }

    function calculateGrandTotal(subtotal = 0) {
        let totalBeaCetak = parseFloat(document.getElementById('total-bea-cetak').innerText.replace(/[Rp. ]/g, '')) || 0;
        let totalBeaSandar = parseFloat(document.getElementById('total-bea-sandar').innerText.replace(/[Rp. ]/g, '')) || 0;

        let totalKeseluruhan = subtotal - (beacetak + beasandar);

        // Set nilai total keseluruhan
        document.getElementById('total-keseluruhan').innerText = 'Rp ' + totalKeseluruhan.toLocaleString();
    }

    // Mulai kalkulasi saat DOM siap
    calculateTotals();
});

</script>

</body>
</html>
