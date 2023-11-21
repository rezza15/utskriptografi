<?php

// Fungsi untuk menghitung FPB (Faktor Persekutuan Terbesar)
function gcd($a, $b) {
    return gmp_intval(gmp_gcd($a, $b));
}

// Fungsi untuk mencari bilangan coprime dengan suatu bilangan tertentu ($m)
function findCoprime($m) {
    do {
        // Menghasilkan bilangan acak ($a) antara 2 hingga $m - 1
        $a = mt_rand(2, $m - 1);
    } while (gcd($a, $m) != 1); // Melakukan loop hingga $a dan $m memiliki FPB = 1

    return $a;
}

// Fungsi untuk menghitung invers modular dari $a mod $m
function modular_inverse($a, $m) {
    $m0 = $m;
    $x0 = 0;
    $x1 = 1;

    // Algoritma Extended Euclidean untuk mencari invers modular
    while ($a > 1) {
        $q = (int)($a / $m);
        $t = $m;
        $m = $a % $m;
        $a = $t;
        $t = $x0;
        $x0 = $x1 - $q * $x0;
        $x1 = $t;
    }

    // Mengembalikan invers modular, memastikan nilainya positif
    return ($x1 < 0) ? $x1 + $m0 : $x1;
}

// Fungsi untuk melakukan enkripsi dengan metode Affine Cipher
function affineEncrypt($text, $a, $b) {
    $encrypted_text = "";
    $m = 26; // Jumlah huruf abjad

    // Loop melalui setiap karakter dalam teks
    for ($i = 0; $i < strlen($text); $i++) {
        $char = $text[$i];
        if (ctype_lower($char)) { // Memeriksa apakah karakter adalah huruf kecil
            $plaintext_num = ord($char) - ord('a'); // Konversi karakter ke nomor indeks huruf
            $ciphertext_num = ($a * $plaintext_num + $b) % $m; // Proses enkripsi
            $encrypted_text .= chr($ciphertext_num + ord('a')); // Konversi nomor indeks ke karakter
        } else {
            // Jika karakter bukan huruf kecil, tambahkan ke teks terenkripsi tanpa melakukan enkripsi
            $encrypted_text .= $char;
        }
    }

    return $encrypted_text; // Mengembalikan teks terenkripsi
}

// Fungsi untuk melakukan dekripsi dengan metode Affine Cipher
function affineDecrypt($text, $a, $b) {
    $decrypted_text = "";
    $m = 26; // Jumlah huruf abjad

    $a_inverse = modular_inverse($a, $m); // Menghitung invers modular dari $a mod $m

    // Loop melalui setiap karakter dalam teks terenkripsi
    for ($i = 0; $i < strlen($text); $i++) {
        $char = $text[$i];
        if (ctype_lower($char)) { // Memeriksa apakah karakter adalah huruf kecil
            $ciphertext_num = ord($char) - ord('a'); // Konversi karakter ke nomor indeks huruf
            $decrypted_num = ($a_inverse * ($ciphertext_num - $b)) % $m; // Proses dekripsi
            $decrypted_num = ($decrypted_num + $m) % $m; // Memastikan hasilnya positif
            $decrypted_text .= chr($decrypted_num + ord('a')); // Konversi nomor indeks ke karakter
        } else {
            // Jika karakter bukan huruf kecil, tambahkan ke teks terdekripsi tanpa melakukan dekripsi
            $decrypted_text .= $char;
        }
    }

    return $decrypted_text; // Mengembalikan teks terdekripsi
}
?>
