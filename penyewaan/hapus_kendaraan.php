<?php
session_start();

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
include 'koneksi.php';

// Periksa apakah ID kendaraan ada di parameter GET
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Validasi ID untuk memastikan itu hanya angka
    if (is_numeric($id)) {
        $query = "DELETE FROM kendaraan WHERE id_kendaraan = $id";

        if ($conn->query($query)) {
            // Mengalihkan ke halaman kendaraan.php dengan pesan sukses
            header("Location: kendaraan.php?message=success");
        } else {
            // Jika gagal, mengalihkan ke halaman kendaraan.php dengan pesan error
            header("Location: kendaraan.php?message=error");
        }
    } else {
        // Jika ID tidak valid, mengalihkan dengan pesan invalid
        header("Location: kendaraan.php?message=invalid");
    }
    exit;
} else {
    // Jika tidak ada ID, mengalihkan dengan pesan invalid
    header("Location: kendaraan.php?message=invalid");
    exit;
}
