<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style/style.css">
    <title>Register</title>
</head>

<body>
    <div class="container">
        <div class="box form-box">

            <?php
            // Include file-file PHP yang diperlukan
            include("php/affineCipher.php");
            include("php/config.php");
            // Cek apakah formulir registrasi telah disubmit
            if (isset($_POST['submit'])) {
                $username = $_POST['username'];
                $email = $_POST['email'];
                $age = $_POST['age'];
                $password = $_POST['password'];

                // Verifikasi apakah alamat email sudah terdaftar
            
                $verify_query = mysqli_query($con, "SELECT Email FROM users WHERE Email='$email'");

                if (mysqli_num_rows($verify_query) != 0) {
                    // jika Email Sama yang ada di database maka berikan notifikasi
                    echo "<div class='message'>
                      <p>Email ini sudah di gunakan, Silahkan coba lagi dengan email yang berbeda</p>
                  </div> <br>";
                    echo "<a href='javascript:self.history.back()'><button class='btn'>Kembali</button>";
                } else {
                    // Check apakah password memenuhi persyaratan panjang
                    if (strlen($password) >= 8 && strlen($password) <= 32) {
                        // Gunakan fungsi affineEncrypt untuk mengenkripsi password sebelum disimpan
                        $password = strtolower($password);
                        // Hasilkan nilai acak baru untuk a dan b
                        $a = findCoprime(26);
                        $b = mt_rand(1, 25);
                        // enkripsi password dengan affine cipher
                        $encrypted_password = affineEncrypt($password, $a, $b);
                        // Simpan data pengguna ke dalam database
                        mysqli_query($con, "INSERT INTO users(Username, Email, Age, Password, Nilai_A, Nilai_B) VALUES('$username','$email','$age','$encrypted_password', '$a', '$b')") or die("Error Occurred");
                        // jika registrasi berhasil maka munculkan notifikasi registrasi berhasil
                        echo "<div class='message'>
                      <p>Registrasi telah berhasil!</p>
                  </div> <br>";
                        echo "<a href='login.php'><button class='btn'>Login Sekarang</button>";

                    } else {
                        // jika password kurang dari 8 karakter dan melebihi 32 karakter berikan notifikasi 
                        echo "<div class='message'>
                      <p>Password harus berjumlah 8 karakter dan maksimal 32</p>
                  </div> <br>";
                        echo "<a href='javascript:self.history.back()'><button class='btn'>Kembali</button>";
                    }
                }
            } else {

                ?>

                <header>Registrasi</header>
                <!-- Formulir HTML untuk registrasi -->
                <form action="" method="post">
                    <!-- Input untuk username, email, umur, dan password -->
                    <div class="field input">
                        <label for="username">Username</label>
                        <input type="text" name="username" id="username" autocomplete="off" placeholder="Masukan Username" required>
                    </div>

                    <div class="field input">
                        <label for="email">Email</label>
                        <input type="text" name="email" id="email" autocomplete="off" placeholder="Masukan Email" required>
                    </div>

                    <div class="field input">
                        <label for="age">Umur</label>
                        <input type="number" name="age" id="age" autocomplete="off" placeholder="Masukan Umur" required>
                    </div>
                    <div class="field input">
                        <label for="password">Password</label>
                        <input type="password" name="password" id="password" autocomplete="off" placeholder="Masukan Password" required>
                    </div>

                    <div class="field">
                        <!-- Tombol submit formulir -->
                        <input type="submit" class="btn" name="submit" value="Register" required>
                    </div>
                    <div class="links">
                        Jika sudah punya akun? <a href="login.php">Login</a>
                    </div>
                </form>
            </div>
        <?php } ?>
    </div>
</body>

</html>