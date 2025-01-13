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
$kendaraanResult = $conn->query("SELECT DISTINCT type_kendaraan FROM kendaraan WHERE status = 'tersedia'");

// Proses jika tombol tambah ditekan
if (isset($_POST['tambah'])) {
    $id_penyewa = $_POST['id_penyewa'];
    $id_kendaraan = $_POST['id_kendaraan'];
    $tanggal_sewa = $_POST['tanggal_sewa'];
    $tanggal_kembali = $_POST['tanggal_kembali'];
    $jaminan = $_POST['jaminan'];

    // Ambil harga kendaraan dari database untuk hitung total biaya
    $kendaraan = $conn->query("SELECT harga_kendaraan FROM kendaraan WHERE id_kendaraan = '$id_kendaraan'");
    if ($kendaraan && $kendaraan->num_rows > 0) {
        $kendaraanData = $kendaraan->fetch_assoc();
        $harga_per_hari = $kendaraanData['harga_kendaraan'];

        // Hitung durasi sewa
        $tanggal_sewa_obj = new DateTime($tanggal_sewa);
        $tanggal_kembali_obj = new DateTime($tanggal_kembali);
        $durasi = $tanggal_sewa_obj->diff($tanggal_kembali_obj)->days + 1;

        // Total biaya sewa
        $total_biaya = $durasi * $harga_per_hari;

        // Masukkan data penyewaan ke database
        $insertQuery = "INSERT INTO penyewaan (id_penyewa, id_kendaraan, tanggal_sewa, tanggal_kembali, total_biaya, durasi, jaminan) 
                          VALUES ('$id_penyewa', '$id_kendaraan', '$tanggal_sewa', '$tanggal_kembali', '$total_biaya', '$durasi', '$jaminan')";

        if ($conn->query($insertQuery)) {
            // Update status kendaraan menjadi 'disewa'
            $updateQuery = "UPDATE kendaraan SET status = 'disewa' WHERE id_kendaraan = '$id_kendaraan'";
            $conn->query($updateQuery);

            header("Location: penyewaan.php");
            exit;
        } else {
            echo "Gagal menambahkan penyewaan: " . $conn->error;
        }
    } else {
        echo "Kendaraan tidak ditemukan!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Tambah Penyewaan</title>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" crossorigin="anonymous"></script>
</head>

<body>
    <div class="container mt-4">
        <h2 class="text-center">Tambah Penyewaan</h2>
        <form method="POST" action="tambah_penyewaan.php">
            <!-- Dropdown Penyewa -->
            <div class="mb-3">
                <label for="id_penyewa" class="form-label">Penyewa</label>
                <select class="form-select" id="id_penyewa" name="id_penyewa" required>
                    <option value="">Pilih Penyewa</option>
                    <?php while ($penyewa = $penyewaResult->fetch_assoc()) { ?>
                        <option value="<?= $penyewa['id_penyewa']; ?>"><?= $penyewa['nama_penyewa']; ?></option>
                    <?php } ?>
                </select>
            </div>

            <!-- Dropdown Type Kendaraan -->
            <div class="mb-3">
                <label for="type_kendaraan" class="form-label">Type Kendaraan</label>
                <select class="form-select" id="typeKendaraanSelect" name="type_kendaraan" required>
                    <option value="">Pilih Type Kendaraan</option>
                    <?php while ($kendaraan = $kendaraanResult->fetch_assoc()) { ?>
                        <option value="<?= $kendaraan['type_kendaraan']; ?>"><?= $kendaraan['type_kendaraan']; ?></option>
                    <?php } ?>
                </select>
            </div>

            <!-- Dropdown Nomor Kendaraan -->
            <div class="mb-3" id="nomorKendaraanDiv" style="display:none;">
                <label for="id_kendaraan" class="form-label">Nomor Kendaraan</label>
                <select class="form-select" id="nomorKendaraanSelect" name="id_kendaraan" required>
                    <option value="">Pilih Nomor Kendaraan</option>
                </select>
            </div>

            <!-- Tanggal Sewa -->
            <div class="mb-3">
                <label for="tanggal_sewa" class="form-label">Tanggal Sewa</label>
                <input type="date" class="form-control" name="tanggal_sewa" required>
            </div>

            <!-- Tanggal Kembali -->
            <div class="mb-3">
                <label for="tanggal_kembali" class="form-label">Tanggal Kembali</label>
                <input type="date" class="form-control" name="tanggal_kembali" required>
            </div>

            <!-- Jaminan -->
            <div class="mb-3">
                <label for="jaminan" class="form-label">Jaminan</label>
                <input type="text" class="form-control" name="jaminan" required placeholder="Masukkan Jaminan">
            </div>

            <!-- Submit -->
            <button type="submit" class="btn btn-outline-success" name="tambah">Tambah</button>
            <a href="penyewaan.php" class="btn btn-outline-danger">Batal</a>
        </form>
    </div>

    <script>
        // Handle dynamic nomor kendaraan dropdown
        document.getElementById('typeKendaraanSelect').addEventListener('change', function() {
            const typeKendaraan = this.value;
            const nomorKendaraanDiv = document.getElementById('nomorKendaraanDiv');
            const nomorKendaraanSelect = document.getElementById('nomorKendaraanSelect');

            if (typeKendaraan) {
                fetch('nomor_kendaraan.php?type_kendaraan=' + typeKendaraan)
                    .then(response => response.json())
                    .then(data => {
                        nomorKendaraanSelect.innerHTML = '<option value="">Pilih Nomor Kendaraan</option>';
                        if (data.length > 0) {
                            data.forEach(item => {
                                const option = document.createElement('option');
                                option.value = item.id_kendaraan;
                                option.textContent = `${item.n_kendaraan} (${item.jenis_kendaraan})`;
                                nomorKendaraanSelect.appendChild(option);
                            });
                            nomorKendaraanDiv.style.display = 'block';
                        } else {
                            nomorKendaraanDiv.style.display = 'none';
                        }
                    });
            } else {
                nomorKendaraanDiv.style.display = 'none';
            }
        });
    </script>
</body>

</html>