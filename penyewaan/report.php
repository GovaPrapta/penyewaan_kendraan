<?php
session_start();

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
include 'koneksi.php';

// Ambil bulan dan tahun yang dipilih
$bulan = isset($_GET['bulan']) ? $_GET['bulan'] : date('m');
$tahun = isset($_GET['tahun']) ? $_GET['tahun'] : date('Y');

// Query untuk mendapatkan data laporan bulanan hanya untuk penyewaan yang sudah selesai
$query = "
    SELECT 
        kendaraan.jenis_kendaraan,
        kendaraan.type_kendaraan,
        kendaraan.n_kendaraan,
        COUNT(penyewaan.id_sewa) AS total_disewa,
        SUM(total_biaya) AS total_biaya_keseluruhan,
        SUM(denda) AS total_denda_keseluruhan,
        SUM(penyewaan.total_biaya + penyewaan.denda) AS total_pendapatan
    FROM penyewaan
    JOIN kendaraan ON penyewaan.id_kendaraan = kendaraan.id_kendaraan
    WHERE MONTH(penyewaan.tanggal_sewa) = '$bulan' 
    AND YEAR(penyewaan.tanggal_sewa) = '$tahun'
    AND penyewaan.keterangan_sewa = 'selesai'  -- Hanya yang statusnya 'selesai'
    GROUP BY kendaraan.id_kendaraan
";

$result = $conn->query($query);

// Ambil data untuk dropdown bulan dan tahun
$months = [
    '01' => 'Januari',
    '02' => 'Februari',
    '03' => 'Maret',
    '04' => 'April',
    '05' => 'Mei',
    '06' => 'Juni',
    '07' => 'Juli',
    '08' => 'Agustus',
    '09' => 'September',
    '10' => 'Oktober',
    '11' => 'November',
    '12' => 'Desember'
];
$currentYear = date('Y');
$years = range($currentYear - 5, $currentYear + 5); // Dropdown tahun dari 5 tahun yang lalu sampai 5 tahun ke depan
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <nav class="navbar navbar-light bg-light">
        <div class="container position-relative">
            <!-- Logo di Kiri -->
            <a class="navbar-brand" href="home.php">
                <img src="asset/img/WARCIDE.svg" alt="" width="50" height="50" class="d-inline-block align-text-top">
            </a>

            <!-- Teks di Tengah -->
            <h2 class="position-absolute top-50 start-50 translate-middle text-center m-0">Laporan Bulanan Penyewaan Kendaraan</h2>
        </div>
    </nav>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

    <div class="container my-5">
        <!-- Header -->
        <div class="nav">
            <!-- Tombol Kembali ke Halaman Utama -->
            <a type="button" class="btn-close" aria-label="Close" href="home.php"></a>
        </div><br>

        <!-- Form Filter Bulan dan Tahun -->
        <form method="GET" action="report.php" class="mb-4">
            <div class="row g-3">
                <div class="col-md-4">
                    <label for="bulan" class="form-label">Pilih Bulan:</label>
                    <select name="bulan" id="bulan" class="form-select">
                        <?php foreach ($months as $key => $value): ?>
                            <option value="<?= $key ?>" <?= ($bulan == $key) ? 'selected' : ''; ?>><?= $value ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="tahun" class="form-label">Pilih Tahun:</label>
                    <select name="tahun" id="tahun" class="form-select">
                        <?php foreach ($years as $year): ?>
                            <option value="<?= $year ?>" <?= ($tahun == $year) ? 'selected' : ''; ?>><?= $year ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <button type="submit" class="btn btn-info w-100"><b>Tampilkan Laporan</b></button>
                </div>
            </div>
        </form>

        <!-- Tabel Laporan -->
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Data Laporan Bulanan</h5>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover mb-0">
                    <thead class="table-info">
                        <tr>
                            <th>No</th>
                            <th>Jenis Kendaraan</th>
                            <th>Type Kendaraan</th>
                            <th>Nomer Kendaraan</th>
                            <th>Total Disewa</th>
                            <th>Total Biaya Keseluruhan</th>
                            <th>Total Denda Keseluruhan</th>
                            <th>Total Pendapatan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($result->num_rows > 0): ?>
                            <?php $no = 1;
                            while ($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td><?= $no++; ?></td>
                                    <td><?= $row['jenis_kendaraan']; ?></td>
                                    <td><?= $row['type_kendaraan']; ?></td>
                                    <td><?= $row['n_kendaraan']; ?></td>
                                    <td><?= $row['total_disewa']; ?></td>
                                    <td>Rp <?= number_format($row['total_biaya_keseluruhan'], 0, ',', '.'); ?></td>
                                    <td>Rp <?= number_format($row['total_denda_keseluruhan'], 0, ',', '.'); ?></td>
                                    <td>Rp <?= number_format($row['total_pendapatan'], 0, ',', '.'); ?></td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="text-center">Tidak ada data untuk bulan dan tahun yang dipilih dengan status selesai.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>