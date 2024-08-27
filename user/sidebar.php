<?php
session_start();
require 'session_check.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://cdn.lineicons.com/4.0/lineicons.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
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
                        <a href="" class="sidebar-link">
                            <i class="lni lni-home"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a href="transaksi.php" class="sidebar-link">
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
            </div>
        </aside>
        <div class="main p-3">
            <div class="top-bar">
                <h1>Dashboard</h1>
               
            </div>
            <div class="content-area">
                <div class="rectangle gray">
                    <div class="hello-text">
                        <h1>Hallo, <?php echo $_SESSION["username"]; ?></h1>
                        <p>Senang bertemu denganmu kembali</p>
                    </div>
                    <div class="hello-image">
                        <img src="img/kntr1.png" alt="Image" class="hello-image">
                    </div>
                </div>
                <div class="calendar">
                    <div class="calendar-header">
                        <span class="month-picker" id="month-picker">February</span>
                        <div class="year-picker">
                            <span class="year-change" id="prev-year">
                                <pre><</pre>
                            </span>
                            <span id="year">2021</span>
                            <span class="year-change" id="next-year">
                                <pre>></pre>
                            </span>
                        </div>
                    </div>
                    <div class="calendar-body">
                        <div class="calendar-week-day">
                            <div>Sun</div>
                            <div>Mon</div>
                            <div>Tue</div>
                            <div>Wed</div>
                            <div>Thu</div>
                            <div>Fri</div>
                            <div>Sat</div>
                        </div>
                        <div class="calendar-days"></div>
                    </div>
                    <div class="month-list"></div>
                </div>
            </div>
             <div class="bawah">
                <!-- <div class="statistics-cards">
                    <div class="card">
                        <h3>Total Transaksi</h3>
                        <p>150</p>
                    </div>
                    <div class="card">
                        <h3>Total Pendapatan</h3>
                        <p>$10,000</p>
                    </div>
                    <div class="card">
                        <h3>Jumlah Pengguna</h3>
                        <p>75</p>
                    </div>
                </div>  -->
                <div class="vision-mission col-md-6">
                    <h2>Visi dan Misi Perusahaan</h2>
                    <p>VISI : Menjadi operator terkemuka dalam industri pelayaran penyeberangan di Indonesia.</p>
                    <p>MISI : Menyediakan layanan yang berkualitas melalui inovasi, perbaikan berkelanjutan serta komitmen yang kuat terhadap keselamatan.</p>
                </div>
            </div>
           
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
    <script src="app.js"></script>
    <script src="side.js"></script>
</body>
</html>
