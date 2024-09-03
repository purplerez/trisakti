<?php
 require_once __DIR__."/../config/support.php";

session_start();
require '../user/session_check.php';

$error = [];
$success = "";
$db= new DatabaseConnection;
$koneksi = $db->conn;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
 

    $username = $_POST['username'];
    $pass_lama = md5($_POST['pass_lama']);
    $pass_baru = md5($_POST['pass_baru']);
    $konfirmasi = md5($_POST['konfirmasi_pass']);

    $stmt = $koneksi->prepare("SELECT * FROM tb_user WHERE username = ? AND password = ?");
    $stmt->bind_param("ss", $username, $pass_lama);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result) {
        $data = $result->fetch_assoc();

        if ($data) {
            if ($pass_baru === $konfirmasi) {
                $ubah_stmt = $koneksi->prepare("UPDATE tb_user SET password = ? WHERE username = ?");
                $ubah_stmt->bind_param("ss", $pass_baru, $username);
                $ubah = $ubah_stmt->execute();

                if ($ubah) {
                    $success = "Password berhasil diubah";
                } else {
                    $error[] = "Gagal mengubah password";
                }
            } else {
                $error[] = "Password baru dan konfirmasi password tidak sesuai";
            }
        } else {
            $error[] = "Password lama tidak sesuai";
        }
    } else {
        $error[] = "Query error: " . $koneksi->error;
    }
}

if (isset($_GET['username'])) {
    $username = $_GET['username'];
    $query = "SELECT * FROM tb_user WHERE username = '$username'";
    $result = $koneksi->query($query);
    // mysqli_query($koneksi, );
    if (!$result) {
        die("Query error: " . mysqli_errno($koneksi) . " - " . mysqli_error($koneksi));
    }
    $data = mysqli_fetch_assoc($result);

    if (!$data) {
        echo "<script>alert('Data tidak ditemukan');window.location='db_admin.php';</script>";
        exit();
    }
} else {
    echo "<script>alert('Pilih username yang akan diedit');window.location='db_admin.php';</script>";
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <link href="https://cdn.lineicons.com/4.0/lineicons.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <link rel="stylesheet" href="laporan.css">
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
        </aside>
        <div class="main p-3">
            <div class="profile">
                <h2>Ganti Password</h2>
            </div>
            
            <form class="edit" action="edit.php?username=<?php echo $data['username']; ?>" method="POST">
                <div class="jarak">
                <?php
                if (!empty($success)) {
                    echo '<div class="alert alert-success custom-alert" role="alert">' . $success . '</div>';
                }
                if (!empty($error)) {
                    foreach ($error as $err) {
                        echo '<div class="alert alert-danger custom-alert" role="alert">' . $err . '</div>';
                    }
                }
                ?>
                    <div class="edit">
                        <label for="">Username</label>
                        <input type="text" name="username" value="<?php echo htmlspecialchars($data['username']); ?>" disabled>
                        <input type="hidden" name="username" value="<?php echo htmlspecialchars($data['username']); ?>">
                    </div>
                    <div class="edit">
                        <label for="">Nama</label>
                        <input type="text" name="nama" value="<?php echo htmlspecialchars($data['nama']); ?>" disabled>
                    </div>
                    <div class="edit">
                        <label for="">Password Lama</label>
                        <input type="password" name="pass_lama" required>
                    </div>
                    <div class="edit">
                        <label for="">Password Baru</label>
                        <input type="password" name="pass_baru" required>
                    </div>
                    <div class="edit">
                        <label for="">Konfirmasi Password Baru</label>
                        <input type="password" name="konfirmasi_pass" required>
                    </div>
                    <div class="button_edit">
                        <button class="batal" type="reset">Batal</button>
                        <button class="simpan" type="submit">Simpan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe"
        crossorigin="anonymous"></script>
    <script src="side.js"></script>
</body>
</html>
