<?php
 require_once __DIR__."/config/support.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $login = new LoginController;

    $username = $_POST["username"];
    $password = $_POST["password"];

    $cek = $login->login($username, $password);

    if($cek['status'] == true)
    {
        session_start();
        
        $_SESSION['username'] = $username;
        $_SESSION['level'] = $row["level"];

        if ($row["level"] == "0") {
            header("Location: ./user/sidebar.php");
            exit();
        } 
        else {
            header("Location: ./Admin/db_admin.php");
            exit();
        }
    } 
    else {
            $error[] = 'Password Salah';
    }
}

    

    // $sql = "SELECT * FROM tb_user WHERE username='$username'";
    // $result = mysqli_query($koneksi, $sql);
    // $row = mysqli_fetch_array($result);

    // if ($row) {
    //     if ($row["password"] === $password) {
    //         $_SESSION['username'] = $username;
    //         $_SESSION['level'] = $row["level"];

    //         if ($row["level"] == "0") {
    //             header("Location: ./user/sidebar.php");
    //             exit();
    //         } else {
    //             header("Location: ./Admin/db_admin.php");
    //             exit();
    //         }
    //     } else {
    //         $error[] = 'Password Salah';
    //     }
    // } else {
    //     $error[] = 'Username Tidak Ditemukan';
    // }
// }
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="login.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">

    <title>Login</title>
</head>
<body>
<form action="index.php" method="POST" class="login-email">
    <section class="login d-flex">
        <div class="login-left w-50 d-flex justify-content-center align-items-center position-relative">
            <div class="login-content text-center">
                <div class="header">
                    <h1>Hello, again</h1>
                    <p>Selamat Datang Kembali! Masuk untuk melanjutkan.</p>
                </div>
                <form action="" method="post">
                <div class="login-form">
                    <div class="mb-3 text-start">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" class="form-control" name="username" placeholder="Enter your username" required>
                    </div>
                    
                    <div class="mb-3 text-start">
                        <label for="password" class="form-label">Password</label>
                        <input type="password"class="form-control" name="password" placeholder="Enter your password" required>
                    </div>

                    
                    <div class="align-items-center">
                    <?php
                    if(isset($error)){
                        foreach($error as $error){
                        echo '<span class="error-msg">'.$error.'</span>';
                        };
                    };
                    ?>
                    <button name="submit" class="btn"><a href="/user/sidebar.php"></a> Login</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="login-right w-50">
            <div class="right">
                <img src="./user/img/logo.png" alt="Image">
            </div>
        </div>
    </section>
</form>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>
</html>
