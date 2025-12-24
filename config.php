<?php
// File Koneksi Database
// Sesuaikan dengan pengaturan database Anda

$host = "localhost";
$username = "root";
$password = "";
$database = "pt_wayan_jaya_abadi";

// Membuat koneksi
$conn = mysqli_connect($host, $username, $password, $database);

// Cek koneksi
if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// Set charset ke utf8mb4 untuk mendukung karakter Indonesia
mysqli_set_charset($conn, "utf8mb4");
?>

