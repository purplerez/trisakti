<?php 

$conn = new DatabaseConnection;
$jenistiket = new JenistiketController;
$transaksi = new TransaksiController;
$uname = new UserController;

if(isset($_POST['addTiket'])){
    $nama_tiket = $_POST['nama_tiket'];
    $tarif = $_POST['tarif'];
    $status = $_POST['status'];

    $data = [
        'nama_tiket' => $nama_tiket,
        'tarif' => $tarif,
        'status' => $status
    ];

    $result = $jenistiket->create($data);

    if($result) header("location:master.php?status=1");
    else header("location:master.php?status=0");
}
else if (isset($_POST['delJenis'])){
    $id_del = $_POST['id'];

    $result = $jenistiket->delete($id_del);

    if($result) header("location:master.php?status=1");
    else header("location:master.php?status=0");
}
else if(isset($_POST['updateTiket'])){
    $id = $_POST['id'];
    $nama_tiket = $_POST['nama_tiket'];
    $tarif = $_POST['tarif'];

    $data = [
        'id' => $id,
        'nama_tiket' => $nama_tiket,
        'tarif' => $tarif
    ];

    $result = $jenistiket->update($data);

    if($result) header("location:master.php?status=1");
    else header("location:master.php?status=0");
}
if(isset($_POST['addUser'])) {
    $username = $_POST['username'];
    $password = md5('12345678');
    $nama = $_POST['nama'];
    $type = $_POST['type'];

    $data = [
        'username' => $username,
        'password' => $password,
        'nama' => $nama,
        'level' => $type
    ];

    $result = $uname->create($data);

    if($result) header("location:user.php?status=1");
    else header("location:user.php?status=0");
}
else if (isset($_POST['delUser'])){
    $id_delus = $_POST['id'];

    $result = $uname->delete($id_delus);

    if($result) header("location:user.php?status=1");
    else header("location:user.php?status=0");
}
else if(isset($_POST['updateUser'])){
    $username = $_POST['id'];
    // $password = md5($_POST['password']);
    $nama = $_POST['nama'];
    $type = $_POST['type'];

    $data = [
        'username' => $username,
        // 'password' => $password,
        'nama' => $nama,
        'type' => $type
    ];

    $result = $uname->update($data);

    if($result) header("location:user.php?status=1");
    else header("location:user.php?status=0");
}
else if(isset($_POST['setPass'])) {
    $username = $_POST['id'];
    
    $result = $uname->resetPasswordToDefault($username);

    if($result) header("location:user.php?status=1");
    else header("location:user.php?status=0");
}

else if(isset($_POST['inputTransaksi'])){

    $tanggal = $_POST['tgl'];
    $waktu = $_POST['waktu'];
    $pelabuhan = isset($_POST['pelabuhan']) ? $_POST['pelabuhan'] : ''; 
    $trip = $_POST['trip'];

    $idjenis = $_POST['idjenis'];
    $produksi = $_POST['produksi'];
    // var_dump($idjenis);
    $data = [
        'tanggal' => $tanggal,
        'waktu' => $waktu,
        'pelabuhan' => $pelabuhan,
        'trip' => $trip
    ];

    $result = $transaksi->create($data, $idjenis, $produksi);

    if($result) header("location:lap.php?status=1");
    else header("location:transaksi.php?status=0"); 

}
else if (isset($_POST['delLap'])){
    $id_lap = $_POST['id'];

    $result = $transaksi->delete($id_lap);

    if($result) header("location:lap.php?status=1");
    else header("location:lap.php?status=0");
}
else if(isset($_POST['updateLap'])){
    $id = $_POST['id'];
    $produksi = $_POST['produksi'];

    $data = [
        'id' => $id,
        'produksi' => $produksi
    ];

    $result = $transaksi->update($data);

    if($result) header("location:transaksi.php?status=1");
    else header("location:transaksi.php?status=0");
}
?>