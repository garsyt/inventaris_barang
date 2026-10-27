<?php
// config/database.php

// Pengaturan kredensial database
$host     = "localhost";
$username = "root";
$password = ""; // Sesuaikan jika MySQL kamu memakai password
$database = "db_inventaris";

// Mengaktifkan laporan error MySQLi secara eksplisit
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    // Membuat koneksi ke database menggunakan ekstensi MySQLi
    $conn = new mysqli($host, $username, $password, $database);
    
    // Set charset ke utf8mb4 agar mendukung karakter khusus secara konsisten
    $conn->set_charset("utf8mb4");
} catch (Exception $e) {
    // Jika koneksi gagal, hentikan eksekusi dan tampilkan pesan error yang aman
    die("Koneksi ke database gagal: " . $e->getMessage());
}
?>