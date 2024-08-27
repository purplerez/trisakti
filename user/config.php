<?php
$server = 'localhost';
$user = 'root';
$pass = '';
$db = 'trimas_db';
$koneksi = mysqli_connect($server, $user, $pass, $db);

if(!$koneksi){
    echo 'koneksi gagal';
}
?>