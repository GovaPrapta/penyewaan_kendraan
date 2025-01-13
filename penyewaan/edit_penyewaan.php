<?php
session_start();

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
include 'koneksi.php';

// Ambil data penyewa untuk dropdown
$penyewaResult = $conn->query("SELECT id_penyewa, nama_penyewa FROM penyewa");

// Ambil data kendaraan yang statusnya 'tersedia' untuk dropdown
$kendaraanResult = $conn->query("SELECT id_kendaraan, jenis_kendaraan, type_kendaraan, harga_kendaraan 
                                FROM kendaraan WHERE status = 'tersedia'");

// Periksa jika formulir telah dikirim
if (isset($_POST['update'])) {
    $id_sewa = $_POST['id_sewa'];
    $id_penyewa = $_POST['id_penyewa'];
    $id_kendaraan = $_POST['id_kendaraan'];
    $tanggal_sewa = $_POST['tanggal_sewa'];
    $tanggal_kembali = $_POST['tanggal_kembali'];
    $jaminan = $_POST['jaminan'];

    $kendaraan = $conn->query("SELECT harga_kendaraan FROM kendaraan WHERE id_kendaraan = '$id_kendaraan'")->fetch_assoc();
    $harga_per_hari = $kendaraan['harga_kendaraan'];

    $tanggal_sewa_obj = new DateTime($tanggal_sewa);
    $tanggal_kembali_obj = new DateTime($tanggal_kembali);
    $durasi = $tanggal_sewa_obj->diff($tanggal_kembali_obj)->days + 1;

    $total_biaya = $durasi * $harga_per_hari;

    $conn->query("UPDATE penyewaan SET 
        id_penyewa = '$id_penyewa',
        id_kendaraan = '$id_kendaraan',
        tanggal_sewa = '$tanggal_sewa',
        tanggal_kembali = '$tanggal_kembali',
        total_biaya = '$total_biaya',
        durasi = '$durasi',
        jaminan = '$jaminan'
        WHERE id_sewa = '$id_sewa'");

    header("Location: penyewaan.php");
    exit;
}

$id_sewa = $_GET['id'];
$penyewaan = $conn->query("SELECT * FROM penyewaan WHERE id_sewa = '$id_sewa'")->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Penyewaan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js"></script>
</head>

<body>
    <div class="container mt-5">
        <h2 class="text-center">Edit Data Penyewaan</h2>
        <div class="alert alert-light" role="alert">
            <form method="POST" action="">
                <input type="hidden" name="id_sewa" value="<?= $penyewaan['id_sewa']; ?>">

                <!-- Dropdown Penyewa -->
                <div class="mb-3">
                    <label for="id_penyewa" class="form-label">Penyewa:</label>
                    <select class="form-select" name="id_penyewa" required>
                        <option value="">Pilih Penyewa</option>
                        <?php while ($penyewa = $penyewaResult->fetch_assoc()) { ?>
                            <option value="<?= $penyewa['id_penyewa']; ?>"
                                <?= $penyewaan['id_penyewa'] == $penyewa['id_penyewa'] ? 'selected' : ''; ?>>
                                <?= $penyewa['nama_penyewa']; ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>

                <!-- Dropdown Kendaraan -->
                <div class="mb-3">
                    <label for="id_kendaraan" class="form-label">Kendaraan:</label>
                    <select class="form-select" name="id_kendaraan" required>
                        <option value="">Pilih Kendaraan</option>
                        <?php while ($kendaraan = $kendaraanResult->fetch_assoc()) { ?>
                            <option value="<?= $kendaraan['id_kendaraan']; ?>"
                                <?= $penyewaan['id_kendaraan'] == $kendaraan['id_kendaraan'] ? 'selected' : ''; ?>>
                                <?= $kendaraan['jenis_kendaraan'] . ' - ' . $kendaraan['type_kendaraan']; ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>

                <!-- Tanggal Sewa -->
                <div class="mb-3">
                    <label for="tanggal_sewa" class="form-label">Tanggal Sewa:</label>
                    <input type="date" class="form-control" name="tanggal_sewa" value="<?= $penyewaan['tanggal_sewa']; ?>" required>
                </div>

                <!-- Tanggal Kembali -->
                <div class="mb-3">
                    <label for="tanggal_kembali" class="form-label">Tanggal Kembali:</label>
                    <input type="date" class="form-control" name="tanggal_kembali" value="<?= $penyewaan['tanggal_kembali']; ?>" required>
                </div>

                <!-- Jaminan -->
                <div class="mb-3">
                    <label for="jaminan" class="form-label">Jaminan:</label>
                    <input type="text" class="form-control" name="jaminan" value="<?= $penyewaan['jaminan']; ?>" required>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn btn-outline-success" name="update">Simpan Perubahan</button>
                <a href="penyewaan.php" class="btn btn-outline-danger">Batal</a>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>