<?php
session_start();

include("php/config.php");

// Redirect ke halaman login jika belum login
if (!isset($_SESSION['valid'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style/style.css">
    <title>Ubah Profil</title>
</head>

<body>
    <div class="nav">
        <div class="logo">
            <p><a href="home.php">Affine Cipher</a></p>
        </div>

        <div class="right-links">
            <a href="ubahKatasandi.php">Ubah Kata Sandi</a>
            <a href="php/logout.php"> <button class="btn">Keluar</button> </a>
        </div>
    </div>

    <div class="container">
        <div class="box form-box">
            <?php
            if (isset($_POST['submit'])) {
                // Update profil saat formulir dikirim
                $username = $_POST['username'];
                $email = $_POST['email'];
                $age = $_POST['age'];

                $id = $_SESSION['id'];

                // Lakukan queri pembaruan
                $edit_query = mysqli_query($con, "UPDATE users SET Username='$username', Email='$email', Age='$age' WHERE Id=$id ") or die("Terjadi kesalahan");

                if ($edit_query) {
                    echo "<div class='message'>
                        <p>Profil berhasil diperbarui</p>
                    </div> <br>";
                    echo "<a href='home.php'><button class='btn'>Kembali</button>";
                }
            } else {
                // Ambil data pengguna yang sudah ada untuk diisi sebelumnya dalam formulir
                $id = $_SESSION['id'];
                $query = mysqli_query($con, "SELECT * FROM users WHERE Id=$id ");

                while ($result = mysqli_fetch_assoc($query)) {
                    $res_Uname = $result['Username'];
                    $res_Email = $result['Email'];
                    $res_Age = $result['Age'];
                }

                ?>
                <header>Ubah Profil</header>
                <form action="" method="post">
                    <div class="field input">
                        <label for="username">Username</label>
                        <input type="text" name="username" id="username" value="<?php echo $res_Uname; ?>" autocomplete="off" required>
                    </div>

                    <div class="field input">
                        <label for="email">Email</label>
                        <input type="text" name="email" id="email" value="<?php echo $res_Email; ?>" autocomplete="off" required>
                    </div>

                    <div class="field input">
                        <label for="age">Umur</label>
                        <input type="number" name="age" id="age" value="<?php echo $res_Age; ?>" autocomplete="off" required>
                    </div>

                    <div class="field">
                        <input type="submit" class="btn" name="submit" value="Update" required>
                    </div>
                </form>
            </div>
        <?php } ?>
    </div>
</body>

</html>
