<?php
session_start();

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
include 'koneksi.php';

// Update status kendaraan saat keterangan sewa diubah
if (isset($_POST['edit_keterangan'])) {
    $id = $_POST['id_sewa'];
    $diserahkan = $_POST['diserahkan'] ? $_POST['diserahkan'] : NULL;

    // Tentukan keterangan sewa berdasarkan tanggal diserahkan
    $keterangan_sewa = $diserahkan ? 'selesai' : 'belum selesai';

    // Ambil data kendaraan untuk mendapatkan denda per hari
    $penyewaan = $conn->query("SELECT id_kendaraan, tanggal_kembali FROM penyewaan WHERE id_sewa = $id")->fetch_assoc();
    $id_kendaraan = $penyewaan['id_kendaraan'];
    $tanggal_kembali = new DateTime($penyewaan['tanggal_kembali']);
    $tanggal_disarahkan = $diserahkan ? new DateTime($diserahkan) : NULL;

    // Jika tanggal diserahkan lebih lambat dari tanggal kembali, hitung denda
    $denda = 0;
    if ($tanggal_disarahkan && $tanggal_disarahkan > $tanggal_kembali) {
        $durasi_terlambat = $tanggal_kembali->diff($tanggal_disarahkan)->days;

        // Ambil denda per hari kendaraan
        $kendaraan = $conn->query("SELECT denda_kendaraan FROM kendaraan WHERE id_kendaraan = '$id_kendaraan'")->fetch_assoc();
        $denda_per_hari = $kendaraan['denda_kendaraan'];

        // Hitung total denda
        $denda = $durasi_terlambat * $denda_per_hari;
    }

    // Update data penyewaan dengan denda
    $conn->query("UPDATE penyewaan SET keterangan_sewa = '$keterangan_sewa', diserahkan = '$diserahkan', denda = '$denda' WHERE id_sewa = $id");

    // Update status kendaraan
    $newStatus = ($keterangan_sewa == 'selesai') ? 'tersedia' : 'disewa';
    $conn->query("UPDATE kendaraan SET status = '$newStatus' WHERE id_kendaraan = '$id_kendaraan'");

    // Redirect setelah data diperbarui
    header("Location: penyewaan.php");
    exit;
}

// Proses update keterangan_sewa
if (isset($_POST['edit_keterangan'])) {
    $id_sewa = $_POST['id_sewa'];
    $id_kendaraan = $_POST['id_kendaraan'];
    $keterangan_sewa = $_POST['keterangan_sewa']; // Status 'selesai' or 'belum selesai'

    // Update penyewaan
    $conn->query("UPDATE penyewaan SET keterangan_sewa = '$keterangan_sewa' WHERE id_sewa = $id_sewa");

    // Update status kendaraan
    $newStatus = ($keterangan_sewa == 'selesai') ? 'tersedia' : 'disewa';
    $conn->query("UPDATE kendaraan SET status = '$newStatus' WHERE id_kendaraan = $id_kendaraan");

    // Redirect atau beri feedback
    header("Location: penyewaan.php");
    exit;
}

// Ambil data penyewaan dengan join penyewa dan kendaraan
$query = "
    SELECT 
        penyewaan.id_sewa,
        penyewa.nama_penyewa,
        kendaraan.jenis_kendaraan,
        kendaraan.type_kendaraan,
        kendaraan.n_kendaraan,
        penyewaan.tanggal_sewa,
        penyewaan.tanggal_kembali,
        penyewaan.durasi,
        penyewaan.total_biaya,
        penyewaan.denda,
        penyewaan.total_biaya + penyewaan.denda AS total_keseluruhan_biaya,
        penyewaan.jaminan,
        penyewaan.keterangan_sewa,
        penyewaan.diserahkan
    FROM penyewaan
    JOIN penyewa ON penyewaan.id_penyewa = penyewa.id_penyewa
    JOIN kendaraan ON penyewaan.id_kendaraan = kendaraan.id_kendaraan
";

$result = $conn->query($query);

$tambahMode = isset($_GET['tambah']); // Mengecek apakah mode tambah aktif
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
    <nav class="navbar navbar-light bg-light">
        <div class="container position-relative">
            <!-- Logo di Kiri -->
            <a class="navbar-brand" href="home.php">
                <img src="asset/img/WARCIDE.svg" alt="" width="50" height="50" class="d-inline-block align-text-top">
            </a>

            <!-- Teks di Tengah -->
            <h2 class="position-absolute top-50 start-50 translate-middle text-center m-0">Data Penyewaan</h2>
        </div>
    </nav>

</head>

<body>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <div class="container my-5">
        <div class="nav">
            <!-- Tombol Kembali ke Halaman Utama -->
            <a type="button" class="btn-close" aria-label="Close" href="home.php"></a>
        </div>


        <!-- Tombol Tambah Data -->
        <div class="container mt-4">
            <div class="d-flex justify-content-end">
                <a type="button" class="btn btn-outline-primary" href="tambah_penyewaan.php">Tambah Data</a>
            </div>
        </div>
        <hr>
        <!-- Tabel Data Penyewaan -->
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-info">
                    <tr>
                        <th scope="col">No</th>
                        <th scope="col">Nama Penyewa</th>
                        <th scope="col">Jenis Kendaraan</th>
                        <th scope="col">type Kendaraan </th>
                        <th scope="col">No Kendaraan </th>
                        <th scope="col">Tanggal Sewa</th>
                        <th scope="col">Tanggal Kembali</th>
                        <th scope="col">Durasi (Hari)</th>
                        <th scope="col">Total Biaya</th>
                        <th scope="col">Denda</th>
                        <th scope="col">TOtal Keseluruhan Biaya</th>
                        <th scope="col">Jaminan</th>
                        <th scope="col">Keterangan Sewa</th>
                        <th scope="col">Tanggal DIserahkan</th>
                        <th scope="col">Aksi</th>

                    </tr>
                </thead>
                <?php $no = 1;
                while ($row = $result->fetch_assoc()) { ?>
                    <tr>
                        <td><?= $no++; ?></td>
                        <td><?= $row['nama_penyewa']; ?></td>
                        <td><?= $row['jenis_kendaraan']; ?></td>
                        <td><?= $row['type_kendaraan']; ?></td>
                        <td><?= $row['n_kendaraan']; ?></td>
                        <td><?= $row['tanggal_sewa']; ?></td>
                        <td><?= $row['tanggal_kembali']; ?></td>
                        <td><?= $row['durasi']; ?></td>
                        <td>Rp. <?= $row['total_biaya']; ?></td>
                        <td>Rp. <?= $row['denda']; ?></td>
                        <td>Rp. <?= $row['total_keseluruhan_biaya']; ?></td>
                        <td><?= $row['jaminan']; ?></td>

                        <td>
                            <form method="POST" action="penyewaan.php">
                                <select name="keterangan_sewa" class="form-select form-select-sm" onchange="this.form.submit()">
                                    <option value="belum selesai" <?= $row['keterangan_sewa'] == 'belum selesai' ? 'selected' : ''; ?>>Belum Selesai</option>
                                    <option value="selesai" <?= $row['keterangan_sewa'] == 'selesai' ? 'selected' : ''; ?>>Selesai</option>
                                </select>
                                <input type="hidden" name="edit_keterangan" value="true">
                                <input type="hidden" name="id_sewa" value="<?= $row['id_sewa']; ?>">
                            </form>
                        </td>
                        <td>
                            <form method="POST" action="penyewaan.php">
                                <input type="date" name="diserahkan" class="form-control form-control-sm" value="<?= $row['diserahkan'] ?? ''; ?>" onchange="this.form.submit()">
                                <input type="hidden" name="edit_keterangan" value="true">
                                <input type="hidden" name="id_sewa" value="<?= $row['id_sewa']; ?>">
                            </form>
                        </td>
                        <td>
                            <a type="button" class="btn btn-info" href="edit_penyewaan.php?id=<?= $row['id_sewa']; ?>">Edit</a> |
                            <a type="button" class="btn btn-danger" href="hapus_penyewaan.php?id=<?= $row['id_sewa']; ?>" onclick="return confirm('Apakah Anda yakin ingin menghapus penyewaan ini?')">Hapus</a>
                        </td>
                    </tr>
                <?php } ?>
            </table>
        </div>
    </div>
</body>

</html>