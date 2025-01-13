<?php
session_start();

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
include 'koneksi.php';

// Tambah data
if (isset($_POST['tambah'])) {
    $jenis = $_POST['jenis_kendaraan'];
    $type = $_POST['type_kendaraan'];
    $no = $_POST['n_kendaraan'];
    $harga = $_POST['harga_kendaraan'];
    $denda = $_POST['denda_kendaraan'];
    $status = 'tersedia'; // Default status kendaraan
    $conn->query("INSERT INTO kendaraan (jenis_kendaraan, type_kendaraan, n_kendaraan, harga_kendaraan, denda_kendaraan, status) 
                  VALUES ('$jenis', '$type', '$no', '$harga', '$denda', '$status')");

    // Redirect ke halaman utama setelah data ditambahkan
    header("Location: kendaraan.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Data Kendaraan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="home.php">
                <img src="asset/img/WARCIDE.svg" alt="Logo" width="50" height="50" class="d-inline-block align-text-top">
                Penyewaan Kendaraan
            </a>
        </div>
    </nav>

    <!-- Centered Form -->
    <div class="container d-flex align-items-center justify-content-center min-vh-100">

        <!-- Card Form -->
        <div class="card shadow-sm p-4 w-50">
            <h2 class="text-center mb-4">Tambah Data Kendaraan</h2>
            <form method="POST" action="tambah_kendaraan.php">

                <!-- Jenis Kendaraan -->
                <div class="mb-3">
                    <label for="jenis_kendaraan" class="form-label">Jenis Kendaraan</label>
                    <input type="text" class="form-control" id="jenis_kendaraan" name="jenis_kendaraan" required placeholder="Jenis Kendaraan">
                </div>

                <!-- Type Kendaraan -->
                <div class="mb-3">
                    <label for="type_kendaraan" class="form-label">Type Kendaraan</label>
                    <input type="text" class="form-control" id="type_kendaraan" name="type_kendaraan" required placeholder="Type Kendaraan">
                </div>

                <!-- No Kendaraan -->
                <div class="mb-3">
                    <label for="n_kendaraan" class="form-label">No Kendaraan</label>
                    <input type="text" class="form-control" id="n_kendaraan" name="n_kendaraan" required placeholder="Nomor Kendaraan">
                </div>

                <!-- Harga Kendaraan -->
                <div class="mb-3">
                    <label for="harga_kendaraan" class="form-label">Harga Kendaraan</label>
                    <input type="number" class="form-control" id="harga_kendaraan" name="harga_kendaraan" required placeholder="Harga Kendaraan">
                </div>

                <!-- Denda Kendaraan -->
                <div class="mb-3">
                    <label for="denda_kendaraan" class="form-label">Denda Kendaraan</label>
                    <input type="number" class="form-control" id="denda_kendaraan" name="denda_kendaraan" required placeholder="Denda Kendaraan">
                </div>

                <!-- Buttons -->
                <div class="d-flex justify-content-between">
                    <button type="submit" name="tambah" class="btn btn-outline-success">Tambah</button>
                    <a href="kendaraan.php" class="btn btn-outline-danger">Batal</a>
                </div>

            </form>
        </div>
    </div>

    <!-- Optional JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"></script>
</body>

</html>