<?php 
// include "../config/koneksi.php";
// include "./Controller/JenistiketController.php";
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
                    <a href="logout.php" class="sidebar-link">
                        <i class="lni lni-exit"></i>
                        <span>Logout</span>
                    </a>
                </div>
            </div>
        </aside>
        <div class="main p-3">

        

        <div class="table-container">
                <h3>Jenis Tiket</h3>

                <div class="row">
                    <div class="col-sm-7">
                        <form action="" method="post">
                            <?php 
                                if(isset($_POST['editJenis'])){
                                    $id_edit = $_POST['id'];

                                    $result = $jenistiket->edit($id_edit);

                                    foreach ($result as $edit){
                                        $id = $edit['id'];
                                        $namatiket = $edit['nama_tiket'];
                                        $tarif = $edit['tarif'];
                                    }
                                }
                            ?>
                            <div class="mb-3">
                                <label for="jenistiket" class="form-label">Nama Tiket</label>
                                <input type="text" class="form-control" name="nama_tiket" id="" placeholder="Nama Tiket" <?php if(isset($_POST['editJenis'])) { ?> value="<?= htmlspecialchars($namatiket) ?>" <?php } ?> >
                                
                            </div>
                            <div class="mb-3">
                                <label for="tarif" class="form-label">Tarif</label>
                                <input type="number" name="tarif" id="" placeholder="0000000" value="<?= htmlspecialchars($tarif) ?>" class="form-control"> 
                            </div>
                            <div class="mb-3">
                                <label for="jenistiket" class="form-label">Jenis Tiket</label>
                                <select name="status" id="" class="form-control">
                                    <option value="1">Pemasukan</option>
                                    <option value="0">Pengeluaran</option>
                                </select>
                                
                                
                            </div>
                            <!-- <div class="mb-3 form-check">
                                <input type="checkbox" class="form-check-input" id="exampleCheck1">
                                <label class="form-check-label" for="exampleCheck1">Check me out</label>
                            </div> -->
                            <!-- <button type="submit" class="btn btn-primary">Submit</button> -->
                            <?php 
                            if(isset($_POST['editJenis'])){
                            ?>
                            <input type="hidden" name="id" value="<?= $id ?>">
                            <input type="submit" value="Perbaharui Data" name="updateTiket" class="btn btn-warning">
                            <?php } else { ?> 
                                    <input type="submit" value="Simpan" name="addTiket" class="btn btn-primary" >
                            <?php } ?>
                        </form>
                    </div>
               
                </div>
                
                <table class="table table-bordered border-dark">
                    <thead>
                        <tr>
                        <th scope="col" style="width:5%">No</th>
                                <th scope="col">Jenis Tiket</th>
                                <th scope="col">Tarip</th>
                                <th colspan="2"  style="width:15%">Menu</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php 
                        $record = $jenistiket->index();

                    
                        if($record == true) {
                            $no = 1;
                        foreach($record as $rec) {
                    ?>
                        <tr>
                            <td align=center><?= $no ?></td>
                            <td><?= $rec['nama_tiket'] ?></td>
                            <td>Rp. <?= number_format($rec['tarif']) ?></td>
                            <form method="post" action="">
            <input type="hidden" name="id" value="<?= $rec['id'] ?>" />
        <td><input type="submit" value="Edit" name="editJenis" class="btn btn-primary"></td>
        <td><input type="submit" value="Delete" name="delJenis" class="btn btn-primary"></td>
        </form>
                        </tr>
                    <?php 
                        $no++; 
                        } 
                    ?>
                        <!-- <tr>
                            <td>2</td>
                            <td>Dewasa</td>
                            <td>Rp 113.000</td>
                            <td><a href="" class="edit-btn">Edit</a></td>
                        </tr>

                        <tr>
                            <td>3</td>
                            <td>Golongan i (SPD)</td>
                            <td></td>
                            <td><a href="" class="edit-btn">Edit</a></td>
                        </tr>

                        <tr>
                            <td>4</td>
                            <td>Golongan II (SPM)</td>
                            <td>Rp 227.000</td>
                            <td><a href="#" class="edit-btn">Edit</a></td>
                        </tr>
                        
                        <tr>
                            <td>5</td>
                            <td>Golongan III (BEMO)</td>
                            <td>-</td>
                            <td><a href="#" class="edit-btn">Edit</a></td>
                        </tr>
                        
                        <tr>
                            <td>6</td>
                            <td>Golongan IVA (KK)</td>
                            <td>Rp 1.370.410</td>
                            <td><a href="#" class="edit-btn">Edit</a></td>
                        </tr>
                        
                        <tr>
                            <td>7</td>
                            <td>Golongan IVB (TM)</td>
                            <td>Rp 1.358.290</td>
                            <td><a href="#" class="edit-btn">Edit</a></td>
                        </tr>
                        
                        <tr>
                            <td>8</td>
                            <td>Golongan VA (BUS SEDANG)</td>
                            <td>Rp 2.443.065</td>
                            <td><a href="#" class="edit-btn">Edit</a></td>
                        </tr>
                        
                        <tr>
                            <td>9</td>
                            <td>Golongan VB (TS)</td>
                            <td>Rp 2.493.885</td>
                            <td><a href="#" class="edit-btn">Edit</a></td>
                        </tr>
                        
                        <tr>
                            <td>10</td>
                            <td>Golongan VIA (BUS BESAR)</td>
                            <td>Rp 3.916.790</td>
                            <td><a href="#" class="edit-btn">Edit</a></td>
                        </tr> -->

                    
                    <?php 
                }
                else { ?>
                    <tr>
                        <td colspan="4" >Nothing to Generate </td>
                    </tr>
                    
                <?php }
                ?>
                </tbody>
                </table>
             
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe"
        crossorigin="anonymous"></script>
   
    <script src="side.js"></script>
</body>
</html>