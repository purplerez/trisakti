<?php
include "support.php";

session_start();
require 'session_check.php';

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
    
    <link rel="stylesheet" href="cetak.css">
</head>
<body>
    <div class="wrapper">
        <aside id="sidebar">
            <div class="d-flex">
                <button class="toggle-btn" type="button" id="toggleButton">
                    <i class="lni lni-grid-alt"></i>
                </button>
                <div class="sidebar-logo">
                    <a href="#" class="sidebar-link">Trimas</a>
                </div>
            </div>
            <div class="sidebar">
            <div class="header">
                <div class="illustration">
                    <img src="../Admin/img/logo.png" alt="kapal">
                </div>
            <ul class="sidebar-nav">
                <li class="sidebar-item">
                    <a href="edit.php?username=<?php echo $_SESSION['username']; ?>" class="sidebar-link">
                        <i class="lni lni-user"></i>
                        <span>Profile</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="sidebar.php" class="sidebar-link">
                    <i class="lni lni-home"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="#" class="sidebar-link">
                        <i class="lni lni-agenda"></i>
                        <span>Transaksi</span>
                    </a>
                </li>
                
                <li class="sidebar-item">
                    <a href="cetak.php" class="sidebar-link">
                    <i class="lni lni-printer"></i>
                        <span>Cetak Laporan</span>
                    </a>
                </li>
                
            </ul>
            <div class="sidebar-footer position-fixed bottom-0">
                <a href="logout.php" class="sidebar-link">
                    <i class="lni lni-exit"></i>
                    <span>Logout</span>
                </a>
            </div>
        </aside>
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
                            <td><input type="date" name="tanggal"></td>
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
                                <td><input type="text" name="pelabuhan" id="">
                                </td>
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
                        while($rec = $stmt->fetch_assoc()) {
                            // Example condition to check for Golongan VII
                            if ($rec['nama_tiket'] === 'Golongan VII') {
                                ?>
                                <tr>
                                    <td colspan="4">Subtotal</td>
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
                    <tr>
                        <td><?= $no++ ?></td>
                        <td>Bea Cetak</td>
                        <td>90</td>
                        <td id="total-produksi-bea-cetak">0</td>
                        <td id="total-bea-cetak">Rp 0</td>
                    </tr>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td>Bea Sandar</td>
                        <td id="beasandar-value">345.690</td>
                        <td><input type="number" id="total-produksi-bea-sandar"></td>
                        <td id="total-bea-sandar">Rp 0</td>
                    </tr>
                    <tr>
                        <th></th>
                        <th colspan="3">Total Pendapatan</th>
                        <th id="total-keseluruhan">Rp 0</th>
                    </tr>
                </tfoot>
                    </table>
                </div>
                <div class="create">
                    <p>Yang membuat <?php echo $_SESSION ["username"] ?></p>
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

<script>
        document.querySelectorAll('.produksi-input').forEach(input => {
            input.addEventListener('input', () => {
                const tarif = parseInt(input.getAttribute('data-tarif'));
                const produksi = parseInt(input.value) || 0;
                const pendapatan = tarif * produksi;

                const pendapatanCell = input.closest('tr').querySelector('.pendapatan');
                pendapatanCell.textContent = 'Rp ' + pendapatan.toLocaleString();

                calculateTotal();
            });
        });

        function calculateTotal() {
            let totalPendapatan = 0;
            let totalProduksiBeaCetak = 0;
            let totalBeaCetak = 0;

            document.querySelectorAll('.pendapatan').forEach(cell => {
                const pendapatan = parseInt(cell.textContent.replace(/[^\d]/g, '')) || 0;
                totalPendapatan += pendapatan;
            });

            document.querySelectorAll('.produksi-input').forEach(input => {
                const produksi = parseInt(input.value) || 0;
                totalProduksiBeaCetak += produksi;
            });

            // Assuming Bea Cetak is a fixed value, replace this with your actual value
            const beaCetakTarif = 90; // Replace with your actual value from the database
            totalBeaCetak = totalProduksiBeaCetak * beaCetakTarif;

            document.getElementById('total-pendapatan').textContent = 'Rp ' + (totalPendapatan - totalBeaCetak).toLocaleString();
            document.getElementById('total-produksi-bea-cetak').textContent = totalProduksiBeaCetak;
            document.getElementById('total-bea-cetak').textContent = 'Rp ' + totalBeaCetak.toLocaleString();
        }
    </script>
</body>
</html>
