<?php
session_start();

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
include 'koneksi.php';

// Ambil ID kendaraan yang akan diedit
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Ambil data kendaraan dari database
    $result = $conn->query("SELECT * FROM kendaraan WHERE id_kendaraan = '$id'");
    if ($result->num_rows > 0) {
        $data = $result->fetch_assoc();
    } else {
        die("Data kendaraan tidak ditemukan.");
    }
} else {
    header("Location: kendaraan.php");
    exit;
}

// Proses penyimpanan data yang sudah diedit
if (isset($_POST['simpan'])) {
    $jenis = $_POST['jenis_kendaraan'];
    $type = $_POST['type_kendaraan'];
    $no = $_POST['n_kendaraan'];
    $harga = $_POST['harga_kendaraan'];
    $denda = $_POST['denda_kendaraan'];

    // Perbarui data kendaraan di database
    $updateQuery = "UPDATE kendaraan SET jenis_kendaraan = '$jenis', type_kendaraan = '$type', n_kendaraan = '$no', harga_kendaraan = '$harga', denda_kendaraan = '$denda' WHERE id_kendaraan = '$id'";
    $conn->query($updateQuery);

    // Redirect ke halaman utama setelah berhasil diperbarui
    header("Location: kendaraan.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Data Kendaraan</title>
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

    <!-- Centered Edit Form Container -->
    <div class="container d-flex align-items-center justify-content-center min-vh-100">

        <!-- Card Form -->
        <div class="card shadow-sm p-4 w-50">
            <h2 class="text-center mb-4">Edit Data Kendaraan</h2>
            <form method="POST" action="edit_kendaraan.php?id=<?php echo $data['id_kendaraan']; ?>">

                <!-- Jenis Kendaraan -->
                <div class="mb-3">
                    <label for="jenis_kendaraan" class="form-label">Jenis Kendaraan</label>
                    <input type="text" class="form-control" id="jenis_kendaraan" name="jenis_kendaraan" value="<?php echo htmlspecialchars($data['jenis_kendaraan']); ?>" required>
                </div>

                <!-- Type Kendaraan -->
                <div class="mb-3">
                    <label for="type_kendaraan" class="form-label">Type Kendaraan</label>
                    <input type="text" class="form-control" id="type_kendaraan" name="type_kendaraan" value="<?php echo htmlspecialchars($data['type_kendaraan']); ?>" required>
                </div>

                <!-- No Kendaraan -->
                <div class="mb-3">
                    <label for="n_kendaraan" class="form-label">No Kendaraan</label>
                    <input type="text" class="form-control" id="n_kendaraan" name="n_kendaraan" value="<?php echo htmlspecialchars($data['n_kendaraan']); ?>" required>
                </div>

                <!-- Harga Kendaraan -->
                <div class="mb-3">
                    <label for="harga_kendaraan" class="form-label">Harga Kendaraan</label>
                    <input type="number" class="form-control" id="harga_kendaraan" name="harga_kendaraan" value="<?php echo htmlspecialchars($data['harga_kendaraan']); ?>" required>
                </div>

                <!-- Denda Kendaraan -->
                <div class="mb-3">
                    <label for="denda_kendaraan" class="form-label">Denda Kendaraan</label>
                    <input type="number" class="form-control" id="denda_kendaraan" name="denda_kendaraan" value="<?php echo htmlspecialchars($data['denda_kendaraan']); ?>" required>
                </div>

                <!-- Buttons -->
                <div class="d-flex justify-content-between">
                    <button type="submit" name="simpan" class="btn btn-success">Simpan Perubahan</button>
                    <a href="kendaraan.php" class="btn btn-danger">Batal</a>
                </div>

            </form>
        </div>
    </div>

    <!-- Optional JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"></script>
</body>

</html>