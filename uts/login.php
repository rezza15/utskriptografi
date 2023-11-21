<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style/style.css">
    <title>Login</title>
</head>

<body>
    <div class="container">
        <div class="box form-box">
            <?php
            include("php/affineCipher.php");
            include("php/config.php");
            if (isset($_POST['submit'])) {
                $email = mysqli_real_escape_string($con, $_POST['email']);
                $password = mysqli_real_escape_string($con, $_POST['password']);

                $result = mysqli_query($con, "SELECT * FROM users WHERE Email='$email' ") or die("Select Error");
                $row = mysqli_fetch_assoc($result);

                if (is_array($row) && !empty($row)) {

                    $a = $row['Nilai_A'];
                    $b = $row['Nilai_B'];
                    // MenDecrypted password yang tersimpan dalam database
                    $decrypted_password = affineDecrypt($row['Password'], $a, $b);
                    // lalu yang huruf besar akan diubah menjadi huruf kecil
                    $password = strtolower($password);

                    // Bandingkan password yang dimasukkan dengan password yang ada di database setelah didekripsi
                    if ($password == $decrypted_password) {
                        // Jika cocok, set session dan arahkan ke halaman home
                        $_SESSION['valid'] = $row['Email'];
                        $_SESSION['username'] = $row['Username'];
                        $_SESSION['age'] = $row['Age'];
                        $_SESSION['id'] = $row['Id'];
                        header("Location: home.php");
                    } else {
                        // Jika password tidak cocok
                        echo "<div class='message'>
                            <p>Password Salah</p>
                            </div> <br>";
                        echo "<a href='login.php'><button class='btn'>Go Back</button>";
                    }
                } else {
                    // Jika alamat email tidak ditemukan
                    echo "<div class='message'>
                            <p>Email Salah Atau Email tidak terdaftar</p>
                            </div> <br>";
                    echo "<a href='login.php'><button class='btn'>Go Back</button>";
                }
            } else {


                ?>
                <header>Login</header>
                <form action="" method="post">
                    <div class="field input">
                        <label for="email">Email</label>
                        <input type="text" name="email" id="email" autocomplete="off" placeholder="Masukan Email" required>
                    </div>

                    <div class="field input">
                        <label for="password">Password</label>
                        <input type="password" name="password" id="password" autocomplete="off" placeholder="Masukan Password"required>
                    </div>

                    <div class="field">

                        <input type="submit" class="btn" name="submit" value="Login" required>
                    </div>
                    <div class="links">
                        Belum punya akun? <a href="register.php">Registrasi </a>Atau
                        <a href="lupaPassword.php">Lupa Password</a>
                    </div>
                </form>
            </div>
        <?php } ?>
    </div>
</body>

</html>