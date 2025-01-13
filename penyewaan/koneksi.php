<?php
$host = 'localhost';
$user = 'root';
$password = '';
$db = '2_uas';

// Membuat koneksi
$conn = new mysqli($host, $user, $password, $db);

// Mengecek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
?>