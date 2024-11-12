<?php 
 session_start();
 require_once __DIR__."/../config/support.php";
 require '../user/session_check.php';
 include "./adm/data.php";

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sidebar</title>
    <link href="https://cdn.lineicons.com/4.0/lineicons.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <link rel="stylesheet" href="laporan.css">
</head>
<body>
<div class="wrapper d-flex">
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
                <li class="sidebar-item">
                    <a href="edit.php?username=<?php echo $_SESSION['username']; ?>" class="sidebar-link">
                        <i class="lni lni-user"></i>
                        <span>Profile</span>
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

    <div class="table-container">
    <h3>Data User</h3>
    <div class="row">
        <div class="col-sm-12"> <!-- Adjust to full width -->
            <form method="post">
            <?php 
                    if(isset($_POST['editUser'])){
                        $id_user = $_POST['id']; 

                        $result = $uname->edit($id_user);

                        foreach ($result as $edit){
                            $username = $edit['username'];
                            $nama = $edit['nama'];
                            $type = $edit['level'];
                        }
                    }
                ?>

            <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editModalLabel">Edit Data User</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form method="post" class="editForm">
                                <input type="hidden" name="id" id="modalId">
                                <div class="mb-3">
                                    <label for="username" class="form-label">Username</label>
                                    <input type="text" class="form-control" name="username" placeholder="Username">
                                </div>
                                <div class="mb-3">
                                    <label for="nama" class="form-label">Nama</label>
                                    <input type="text" class="form-control" name="nama" placeholder="Nama">
                                </div>
                                <div class="mb-3">
                                    <label for="type" class="form-label">User Type</label>
                                    <select name="type" class="form-select">
                                        <option value="0">user</option>
                                        <option value="1">admin</option>
                                    </select>
                                </div>
                                <input type="submit" value="Perbaharui Data" name="updateUser" class="btn btn-warning">
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addUserModal">Tambah User</button>
            <div class="table-responsive"> <!-- Add table-responsive class -->
                <table class="table table-bordered border-dark">
                    <thead>
                        <tr>
                            <th scope="col">No</th>
                            <th scope="col">Username</th>
                            <th scope="col">Nama</th>
                            <th colspan="3">Menu</th>
                            <th scope="col">Terakhir Login</th>
                            <th scope="col">Type</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                            $record = $uname->index();
                            if($record == true) {
                                $no = 1;
                                foreach($record as $rec) {
                        ?>
                        <tr>
                            <td align=center><?= $no ?></td>
                            <td><?= $rec['username'] ?></td>
                            <td><?= $rec['nama'] ?></td>
                            <form method="post" action="">
                                <input type="hidden" name="id" value="<?= $rec['username'] ?>" />
                                <td>
                                    <div class="d-flex justify-content-center">
                                        <button type="button" class="btn btn-primary edit-btn" data-id="<?= $rec['username'] ?>" data-username="<?= $rec['username'] ?>" data-nama="<?= $rec['nama'] ?>" data-type="<?= $rec['level'] ?>" data-bs-toggle="modal" data-bs-target="#editModal">Edit</button>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex justify-content-center">
                                        <input type="submit" value="Delete" name="delUser" class="btn btn-primary">
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex justify-content-center">
                                        <input type="submit" value="Default Password" name="setPass" class="btn btn-primary">
                                    </div>
                                </td>
                            </form>
                            <td><?= $rec['lastlogin'] ? date("d-m-Y H:i:s", strtotime($rec['lastlogin'])) : 'Belum Pernah Login'; ?></td>
                            <td><?php 
                                if ($rec['level'] == 1){
                                    echo 'admin';
                                } elseif ($rec['level'] == 0){
                                    echo 'user';
                                }
                            ?></td>
                        </tr>
                        <?php 
                                $no++; 
                                } 
                        ?>
                        <?php 
                            } else { 
                        ?>
                        <tr>
                            <td colspan="8">Nothing to Generate</td>
                        </tr>
                        <?php 
                            } 
                        ?>
                    </tbody>
                </table>
            </div> <!-- Close table-responsive -->
        </div>

        <div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addUserModalLabel">Tambah User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="post" action="">
                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" class="form-control" name="username" placeholder="Masukkan Username">
                    </div>
                    <div class="mb-3">
                        <label for="nama" class="form-label">Nama</label>
                        <input type="text" class="form-control" name="nama" placeholder="Masukkan Nama">
                    </div>
                    <div class="mb-3">
                        <label for="type" class="form-label">Type</label>
                        <select name="type" class="form-select">
                            <option value="0">user</option>
                            <option value="1">admin</option>
                        </select>
                    </div>
                    <input type="submit" value="Simpan" name="addUser" class="btn btn-primary" >
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe"
        crossorigin="anonymous"></script>

    <script>
    // Populate the modal with data when edit button is clicked
    document.querySelectorAll('.edit-btn').forEach(button => {
    button.addEventListener('click', function () {
        const id = this.getAttribute('data-id');
        const username = this.getAttribute('data-username');
        const nama = this.getAttribute('data-nama');
        const type = this.getAttribute('data-type');

        // Set modal form values
        document.getElementById('modalId').value = id;
        document.querySelector('input[name="username"]').value = username;
        document.querySelector('input[name="nama"]').value = nama;
        document.querySelector('select[name="type"]').value = type;
    });
});

</script>

<script src="side.js"></script>
</body>
</html>
