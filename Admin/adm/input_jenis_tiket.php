<?php 
include "../config/koneksi.php";
include "../Controller/JenistiketController.php";
include "data.php";

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>

<table>
    <tr>
        <td>#</td>
        <td>Nama Jenis Tiket</td>
        <td>Tarif</td>
        <td colspan=2>Menu</td>
    </tr>
    <?php 
    $record = $jenistiket->index();

    if($record == false) echo "nothing to generate";
    else {
        $no = 1;
    foreach($record as $rec) {
    ?>
    <tr>
        <td><?= $no ?></td>
        <td><?= $rec['nama_tiket'] ?></td>
        <td>Rp. <?= number_format($rec['tarif']) ?></td>
        <form method="post" action="">
            <input type="hidden" name="id" value="<?= $rec['id'] ?>" />
        <td><input type="submit" value="Edit" class="" name="editJenis"></td>
        <td><input type="submit" value="Delete" class="" name="delJenis"></td>
        </form>
    </tr>
    <?php $no++; 
} }?>
</table>
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
    <form method="POST" action="">
        Nama Tiket <input type="text" name="nama_tiket" id="" placeholder="Nama Tiket" <?php if(isset($_POST['editJenis'])) { ?> value="<?= htmlspecialchars($namatiket) ?>" <?php } ?> > <br/>
        Tarif <input type="number" name="tarif" id="" placeholder="0000000" value="<?= htmlspecialchars($tarif) ?>"> <br/>
<?php 
if(isset($_POST['editJenis'])){
?>
<input type="hidden" name="id" value="<?= $id ?>">
<input type="submit" value="Perbaharui Data" name="updateTiket" >
<?php } else { ?> 
        <input type="submit" value="Simpan" name="addTiket" >
<?php } ?>
    </form>
</body>
</html>