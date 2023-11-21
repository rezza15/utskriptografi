<?php
// Mulai sesi PHP untuk pengelolaan data sesi
session_start();

// Mengimpor fungsi Affine Cipher dan konfigurasi database
include("php/config.php");
include("php/affineCipher.php");

// Redirect ke halaman login jika belum login
if (!isset($_SESSION['valid'])) {
    header("Location: login.php");
    exit();
}

// Mendapatkan ID pengguna dari sesi
$id = $_SESSION['id'];

// Mendapatkan nilai a dan b dari database
$result = mysqli_query($con, "SELECT Nilai_A, Nilai_B, Password FROM users WHERE Id=$id") or die("Terjadi kesalahan");
$row = mysqli_fetch_assoc($result);
$a = $row['Nilai_A'];
$b = $row['Nilai_B'];

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style/style.css">
    <title>Ubah Password</title>
</head>

<body>
    <div class="nav">
        <div class="logo">
            <p><a href="home.php"> Affine Cipher</a></p>
        </div>

        <div class="right-links">
            <a href="edit.php">Ubah Profil</a>
            <a href="php/logout.php"> <button class="btn">Keluar</button> </a>
        </div>
    </div>

    <div class="container">
        <div class="box form-box">
            <?php

            // Jika formulir disubmit, proses perubahan Password
            if (isset($_POST['submit'])) {
                // Mendapatkan data dari formulir dan mengonversi huruf besar menjadi huruf kecil
                $currentPassword = mysqli_real_escape_string($con, strtolower($_POST['current_password']));
                $newPassword = mysqli_real_escape_string($con, strtolower($_POST['new_password']));
                $confirmPassword = mysqli_real_escape_string($con, strtolower($_POST['confirm_password']));

                // Mengambil Password saat ini dari database untuk verifikasi
                $currentPasswordDB = $row['Password'];

                // Membandingkan Password saat ini dengan yang ada di database
                if (affineDecrypt($currentPasswordDB, $a, $b) == $currentPassword) {
                    // Memastikan Password baru dan konfirmasinya cocok
                    if ($newPassword == $confirmPassword) {
                        // Memastikan panjang Password baru antara 8 dan 32 karakter
                        if (strlen($newPassword) >= 8 && strlen($newPassword) <= 32) {
                            // Hasilkan nilai acak baru untuk a dan b
                            $a = findCoprime(26);
                            $b = mt_rand(1, 25);
                            $newPassword = strtolower($newPassword);
                            // Perbarui kata sandi pengguna, a, dan b di database
                            $new_encrypted_password = affineEncrypt($newPassword, $a, $b);
                            mysqli_query($con, "UPDATE users SET Password='$new_encrypted_password', Nilai_A=$a, Nilai_B=$b WHERE Username='{$_SESSION['username']}' AND Age='{$_SESSION['age']}'");

                            // Jika update berhasil, tampilkan pesan sukses
                            echo "<div class='message'>
                                    <p>Password berhasil diperbarui</p>
                                </div> <br>";
                            echo "<a href='login.php'><button class='btn'>Kembali Login</button>";
                        } else {
                            // Jika panjang Password baru tidak sesuai, tampilkan pesan kesalahan
                            echo "<div class='message'>
                                    <p>Panjang Password baru harus antara 8 dan 32 karakter</p>
                                </div> <br>";
                            echo "<a href='javascript:self.history.back()'><button class='btn'>Kembali</button>";
                        }
                    } else {
                        // Jika Password baru dan konfirmasi tidak cocok, tampilkan pesan kesalahan
                        echo "<div class='message'>
                                <p>Password baru dan Konfirmasi Password tidak cocok</p>
                            </div> <br>";
                        echo "<a href='javascript:self.history.back()'><button class='btn'>Kembali</button>";
                    }
                } else {
                    // Jika Password saat ini tidak benar, tampilkan pesan kesalahan
                    echo "<div class='message'>
                            <p>Password saat ini tidak benar</p>
                        </div> <br>";
                    echo "<a href='javascript:self.history.back()'><button class='btn'>Kembali</button>";
                }
            } else {
            ?>
                <!-- Formulir HTML untuk mengubah Password -->
                <header>Ubah Password</header>
                <form action="" method="post">
                    <!-- Input untuk Password saat ini, Password baru, dan konfirmasi Password -->
                    <div class="field input">
                        <label for="current_password">Password Saat Ini</label>
                        <input type="password" name="current_password" id="current_password" autocomplete="off" placeholder="Masukan Password " required>
                    </div>

                    <!-- Input untuk Password baru -->
                    <div class="field input">
                        <label for="new_password">Password Baru</label>
                        <input type="password" name="new_password" id="new_password" autocomplete="off" placeholder="Masukan Password Baru" required>
                    </div>

                    <!-- Input untuk konfirmasi Password -->
                    <div class="field input">
                        <label for="confirm_password">Konfirmasi Password</label>
                        <input type="password" name="confirm_password" id="confirm_password" autocomplete="off" placeholder="Masukan Password yang sama" required>
                    </div>

                    <!-- Tombol untuk submit formulir -->
                    <div class="field">
                        <input type="submit" class="btn" name="submit" value="Ubah Password" required>
                    </div>
                </form>
                <!-- Tag penutup -->
            <?php } ?>
        </div>
    </div>
</body>

</html>
