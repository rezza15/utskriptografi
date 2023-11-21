<?php
// Mulai sesi PHP untuk manajemen data sesi
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Set karakter set, kompatibilitas, dan viewport -->
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Tautan ke file stylesheet -->
    <link rel="stylesheet" href="style/style.css">

    <!-- Judul halaman -->
    <title>Lupa Password</title>
</head>

<body>
    <!-- Kotak utama sebagai wadah halaman -->
    <div class="container">
        <!-- Kotak untuk formulir lupa password -->
        <div class="box form-box">
            <?php
            // Sertakan fungsi Affine Cipher dan konfigurasi database
            include("php/affineCipher.php");
            include("php/config.php");

            // Periksa apakah formulir sudah dikirimkan
            if (isset($_POST['submit'])) {
                // Ambil dan bersihkan data username dan age
                $username = mysqli_real_escape_string($con, $_POST['username']);
                $age = mysqli_real_escape_string($con, $_POST['age']);

                // Query untuk mencari pengguna berdasarkan username dan age
                $result = mysqli_query($con, "SELECT * FROM users WHERE Username='$username' AND Age='$age'") or die("Select Error");
                $row = mysqli_fetch_assoc($result);

                // Jika data pengguna ditemukan, simpan detail pengguna di sesi untuk halaman password baru
                if (is_array($row) && !empty($row)) {
                    $_SESSION['username'] = $row['Username'];
                    $_SESSION['age'] = $row['Age'];

                    // Alihkan ke halaman atur password baru
                    header("Location: passwordBaru.php");
                    exit();
                } else {
                    // Tampilkan pesan kesalahan jika username dan age tidak cocok
                    echo "<div class='message'>
                            <p>Username atau Umur salah</p>
                            </div> <br>";
                    echo "<a href='lupaPassword.php'><button class='btn'>Kembali</button>";
                }
            } else {
            ?>
                <header>Lupa Password</header>
                <!-- Formulir HTML untuk lupa password -->
                <form action="" method="post">
                    <!-- Input untuk username -->
                    <div class="field input">
                        <label for="username">Username</label>
                        <input type="text" name="username" id="username" autocomplete="off" placeholder="Masukkan Username" required>
                    </div>

                    <!-- Input untuk age -->
                    <div class="field input">
                        <label for="age">Umur</label>
                        <input type="text" name="age" id="age" autocomplete="off" placeholder="Masukkan Umur" required>
                    </div>

                    <!-- Tombol submit formulir -->
                    <div class="field">
                        <input type="submit" class="btn" name="submit" value="Atur Ulang Password" required>
                    </div>

                    <!-- Tautan ke halaman login jika pengguna mengingat kata sandi mereka -->
                    <div class="links">
                        Ingat kata sandi Anda? <a href="login.php">Login</a>
                    </div>
                </form>
            <?php } ?>
        </div>
    </div>
</body>

</html>
