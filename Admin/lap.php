<?php 
session_start();
require_once __DIR__."/../config/support.php";
require '../user/session_check.php';
include "./adm/data.php";

$transaksi = new TransaksiController;
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
<style>
    @media print {
        /* Sembunyikan seluruh sidebar dan elemen terkait saat print */
        #sidebar,
        .toggle-btn,
        .sidebar-footer {
            display: none !important;
        }

        /* Sesuaikan lebar konten utama agar memenuhi halaman saat print */
        .main {
            width: 100% !important;
            margin: 0 !important;
            padding: 0 !important;
        }

        /* Atur ulang layout agar sesuai dengan format print */
        .wrapper {
            display: block !important;
            margin: 0 !important;
        }
    }
</style>

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
                        <img src="img/logo.png" alt="kapal" class="sidebar-img">
                    </div>
                </div>
                <ul class="sidebar-nav">
                    <li class="sidebar-item">
                        <a href="edit.php?username=<?php echo $_SESSION['username']; ?>" class="sidebar-link">
                            <i class="lni lni-user"></i>
                            <span>Profile</span>
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a href="db_admin.php" class="sidebar-link">
                        <i class="lni lni-home"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a href="master.php" class="sidebar-link">
                        <i class="lni lni-database"></i>
                            <span>Master Data</span>
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a href="transaksi.php" class="sidebar-link">
                            <i class="lni lni-agenda"></i>
                            <span>Transaksi</span>
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a href="lap.php" class="sidebar-link">
                        <i class="lni lni-printer"></i>
                            <span>Data Laporan</span>
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a href="user.php" class="sidebar-link">
                        <i class="lni lni-archive"></i>
                            <span>Data User</span>
                        </a>
                    </li>
                </ul>
                <div class="sidebar-footer position-fixed bottom-0">
                    <a href="../logout.php" class="sidebar-link">
                        <i class="lni lni-exit"></i>
                        <span>Logout</span>
                    </a>
                </div>
            </div>
            <!-- Sidebar content -->
        </aside>
        <div class="main p-3">
            <div class="title-container">
                <h2>Cetak Laporan</h2>
                <hr>
            </div>
            <div class="tanggal-container">
                <form action="">
                    <div class="tanggal">
                        <input type="date" class="form-control">
                        <button class="btn btn-primary">Cari</button>
                    </div>
                </form>
            </div>
            <div class="table-lap mt-4">
                <table class="custom-table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>Jam</th>
                            <th>Pelabuhan</th>
                            <th>Trip</th>
                            <th>Total Transaksi</th>
                            <th colspan="3">Menu</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                            $data = $transaksi->view_transaksi(1);
                            $no = 1;
                            foreach($data as $rec) {
                        ?> 
                         <tr>
                            <td><?= $no ?></td>
                            <td><?= $rec['tanggal'] ?></td>
                            <td><?= $rec['jam'] ?></td>
                            <td><?= $rec['pelabuhan'] ?></td>
                            <td><?= $rec['trip'] ?></td>
                            <td>Rp <?= number_format($rec['total'], 0, ',', '.') ?></td>
                                <form action="excel.php" method="post">
                                    <input type="hidden" name="id" value="<?= $rec['id'] ?>" />
                                <td><input type="submit" value="Excel" name="download" class="btn btn-primary"></td>
                                </form>

                                <form action="pdf.php?id=<?= $rec['id'] ?>" method="post">
                                    <input type="hidden" name="id" value="<?= $rec['id'] ?>" />
                                <td><input type="submit" value="Pdf" name="download" class="btn btn-primary"></td>
                                </form>

                        </tr>
                        <?php $no++; } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe"
        crossorigin="anonymous"></script>
    <script src="side.js"></script>
</body>
</html>
