<?php
session_start();

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

include 'koneksi.php';

// Proses tambah penyewa
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = $_POST['nama_penyewa'];
    $alamat = $_POST['alamat_penyewa'];
    $telepon = $_POST['no_telepon_penyewa'];

    $conn->query("INSERT INTO penyewa (nama_penyewa, alamat_penyewa, no_telepon_penyewa) VALUES ('$nama', '$alamat', '$telepon')");

    header("Location: penyewa.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Data Penyewa</title>
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
            <h2 class="text-center mb-4">Tambah Data Penyewa</h2>
            <form method="POST" action="tambah_penyewa.php">

                <!-- Nama Penyewa -->
                <div class="mb-3">
                    <label for="nama_penyewa" class="form-label">Nama Penyewa</label>
                    <input type="text" class="form-control" id="nama_penyewa" name="nama_penyewa" required placeholder="Nama Penyewa">
                </div>

                <!-- Alamat Penyewa -->
                <div class="mb-3">
                    <label for="alamat_penyewa" class="form-label">Alamat Penyewa</label>
                    <input type="text" class="form-control" id="alamat_penyewa" name="alamat_penyewa" required placeholder="Alamat Penyewa">
                </div>

                <!-- No Telepon Penyewa -->
                <div class="mb-3">
                    <label for="no_telepon_penyewa" class="form-label">No Telepon Penyewa</label>
                    <input type="text" class="form-control" id="no_telepon_penyewa" name="no_telepon_penyewa" required placeholder="No Telepon Penyewa">
                </div>

                <!-- Buttons -->
                <div class="d-flex justify-content-between">
                    <button type="submit" class="btn btn-outline-success">Tambah</button>
                    <a href="penyewa.php" class="btn btn-outline-danger">Batal</a>
                </div>

            </form>
        </div>
    </div>

    <!-- Optional JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"></script>
</body>

</html>