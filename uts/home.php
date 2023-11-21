<?php 
   // Memulai sesi untuk pengelolaan data sesi
   session_start();

   // Memasukkan file konfigurasi untuk koneksi ke database
   include("php/config.php");

   // Memeriksa apakah pengguna sudah login, jika tidak, redirect ke halaman login
   if(!isset($_SESSION['valid'])){
      header("Location: login.php");
   }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style/style.css">
    <title>Home</title>
</head>
<body>
    <!-- Navigasi -->
    <div class="nav">
        <div class="logo">
            <!-- Link untuk kembali ke halaman utama -->
            <p><a href="home.php">Affine Cipher</a> </p>
        </div>

        <div class="right-links">
            <?php 
               // Mengambil data pengguna dari database berdasarkan Id sesi
               $id = $_SESSION['id'];
               $query = mysqli_query($con, "SELECT * FROM users WHERE Id=$id");

               // Menampilkan link "Ubah Profil" dengan mengambil Id pengguna
               while ($result = mysqli_fetch_assoc($query)){
                  $res_Uname = $result['Username'];
                  $res_Email = $result['Email'];
                  $res_Age = $result['Age'];
                  $res_id = $result['Id'];
               }
            
               echo "<a href='edit.php?Id=$res_id'>Ubah Profil</a>";
            ?>

            <!-- Link untuk logout -->
            <a href="php/logout.php"> <button class="btn">Keluar</button> </a>
        </div>
    </div>

    <!-- Isi utama halaman -->
    <main>
       <!-- Kotak informasi pengguna -->
       <div class="main-box top">
          <div class="top">
            <div class="box">
                <!-- Menampilkan umur pengguna -->
                <p>Umur Kamu <b><?php echo $res_Age ?> tahun</b>.</p>
            </div>
            <div class="box">
                <!-- Menampilkan email pengguna -->
                <p>Email Kamu <b><?php echo $res_Email ?></b></p>
            </div>
          </div>
          <div class="bottom">
            <div class="box">
                <!-- Menampilkan pesan selamat datang dengan nama pengguna -->
                <p>Halo <b><?php echo $res_Uname ?></b>, Selamat Datang!, Semoga Harimu Menyenangkan.</p>
            </div>
          </div>
       </div>
    </main>
</body>
</html>
