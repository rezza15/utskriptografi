<?php
// Mulai sesi PHP untuk pengelolaan data sesi
session_start();

// Mengimpor fungsi Affine Cipher dan konfigurasi database
include("php/affineCipher.php");
include("php/config.php");

?>
<!DOCTYPE html>
<html lang="en">

<head>    
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style/style.css">
    <title>Password Baru</title>
</head>

<body>
    <div class="container">
        <div class="box form-box">
            <?php
            // Mengambil dan membersihkan data password baru dan konfirmasi password
            if (isset($_POST['submit'])) {
                $new_password = mysqli_real_escape_string($con, $_POST['new_password']);
                $confirm_password = mysqli_real_escape_string($con, $_POST['confirm_password']);

                // Validasi password baru dan konfirmasi password
                if ($new_password == $confirm_password) {
                    // Periksa apakah password memenuhi persyaratan panjang
                    if (strlen($new_password) >= 8 && strlen($new_password) <= 32) {
                        // Update password pengguna di database
                        $new_password = strtolower($new_password);
                        // Hasilkan nilai acak baru untuk a dan b
                        $a = findCoprime(26);
                        $b = mt_rand(1, 25);
                        // Perbarui kata sandi pengguna, a, dan b di database
                        $encrypted_password = affineEncrypt($new_password, $a, $b);
                        mysqli_query($con, "UPDATE users SET Password='$encrypted_password', Nilai_A=$a, Nilai_B=$b WHERE Username='{$_SESSION['username']}' AND Age='{$_SESSION['age']}'");

                        // Tampilkan pesan sukses setelah mengubah password
                        echo "<div class='message'>
                                <p>Password sudah diupdate.</p>
                                </div> <br>";
                        echo "<a href='login.php'><button class='btn'>Kembali Login</button>";
                    } else {
                        // Tampilkan pesan kesalahan jika password tidak memenuhi persyaratan panjang
                        echo "<div class='message'>
                                <p>Password harus terdiri dari 8 hingga 32 karakter</p>
                                </div> <br>";
                        echo "<a href='passwordBaru.php'><button class='btn'>Kembali </button>";
                    }
                } else {
                    // Tampilkan pesan kesalahan jika password baru dan konfirmasi password tidak cocok
                    echo "<div class='message'>
                            <p>Password baru dan Konfirmasi Password tidak cocok</p>
                            </div> <br>";
                    echo "<a href='passwordBaru.php'><button class='btn'>Kembali </button>";
                }
            } else {
            ?>
                <!-- Header untuk formulir pengaturan password baru -->
                <header>Ubah Password</header>
                
                <!-- Formulir HTML untuk pengaturan password baru -->
                <form action="" method="post">
                    <!-- Input untuk password baru -->
                    <div class="field input">
                        <label for="new_password">Password Baru</label>
                        <input type="password" name="new_password" id="new_password" autocomplete="off" placeholder="Masukkan Password Baru (8-32 karakter)" required>
                    </div>

                    <!-- Input untuk konfirmasi password -->
                    <div class="field input">
                        <label for="confirm_password">Konfirmasi Password</label>
                        <input type="password" name="confirm_password" id="confirm_password" autocomplete="off" placeholder="Masukkan Password yang sama" required>
                    </div>

                    <!-- Tombol submit formulir -->
                    <div class="field">
                        <input type="submit" class="btn" name="submit" value="Set Password" required>
                    </div>
                </form>
            <?php } ?>
        </div>
    </div>
</body>

</html>
