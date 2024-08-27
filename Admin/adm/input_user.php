<!-- <?php 
include "../config/koneksi.php";
include "../Controller/UserController.php";
include "data.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management</title>
</head>
<body>

        <table>
            <tr>
                <th>Username</th>
                <th>Nama</th>
                <th>Type</th>
                <th colspan=2>Menu</th>
            </tr>
            <?php 
            $record = $uname->index();

            if($record == false) {
                echo "<tr><td colspan='5'>Nothing to generate</td></tr>";
            } else {
                $no = 1;
                foreach($record as $rec) {
            ?>
            <tr>
                <td><?= $rec['username'] ?></td>
                <td><?= $rec['nama'] ?></td>

                <form method="post" action="">
                    <input type="hidden" name="username" value="<?= $rec['username'] ?>" />
                    <td><input type="submit" value="Edit" name="editUser"></td>
                    <td><input type="submit" value="Delete" name="delUser"></td>
                </form>
                <td></td>
                <td><?= $rec['level'] ?></td>
            </tr>
            <?php 
                $no++; 
                } 
            }
            ?>
        </table>

    <?php 
    if(isset($_POST['editUser'])){
        $id_user = $_POST['username'];

        $result = $uname->edit($id_user);

        if($result) {
            foreach ($result as $edit){
                $username = $edit['username'];
                $password = $edit['password'];
                $nama = $edit['nama'];
                $type = $edit['level'];
            }
        }
    }
    ?>

<form method="POST" action="">
    <label>Username</label>
    <input type="text" name="username" placeholder="Username" <?php if(isset($_POST['editUser'])) { ?> value="<?= htmlspecialchars($username) ?>" <?php } ?> > <br/> -->
<!--     
    <label>Password</label>
    <input type="password" name="password" placeholder="Password" <?php if(isset($_POST['editUser'])) { ?> value="<?= htmlspecialchars($password) ?>" <?php } ?> > <br/>
     -->
    <!-- <label>Nama</label>
    <input type="text" name="nama" placeholder="Nama" <?php if(isset($_POST['editUser'])) { ?> value="<?= htmlspecialchars($nama) ?>" <?php } ?> > <br/>
    
    <label>Type</label>
    <input type="text" name="type" placeholder="Type" <?php if(isset($_POST['editUser'])) { ?> value="<?= htmlspecialchars($type) ?>" <?php } ?> > <br/>

    <?php 
    if(isset($_POST['editUser'])){
    ?>
    <input type="hidden" name="id" value="<?= htmlspecialchars($username) ?>">
    <input type="submit" value="Perbaharui Data" name="updateUser">
    <?php } else { ?> 
    <input type="submit" value="Simpan" name="addUser">
    <?php } ?>
</form>

</body>
</html> -->