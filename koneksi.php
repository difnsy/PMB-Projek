<?php

$host     = "localhost"; // Nama server
$username = "root";      // Username database
$password = "";          // Password database (kosong jika default)
$database = "pmb";       // Nama database

// Membuat koneksi ke MySQL
$conn = mysqli_connect($host, $username, $password, $database);

// cek  apakah koneksi berhasil
if ($conn) {
    echo "Koneksi berhasil.<br />";
} else {
    echo "Koneksi Gagal: " . mysqli_connect_error();
    die();
}

?>
