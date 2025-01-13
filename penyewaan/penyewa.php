<?php
session_start();

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
include 'koneksi.php';

// Ambil data penyewa
$result = $conn->query("SELECT * FROM penyewa");
// Menangani pencarian
$search = '';
if (isset($_POST['search'])) {
    $search = $_POST['search'];
}

// Ambil data kendaraan dengan kondisi pencarian
$sql = "SELECT * FROM penyewa WHERE nama_penyewa LIKE ? OR alamat_penyewa LIKE ?";
$stmt = $conn->prepare($sql);
$searchTerm = "%" . $search . "%";
$stmt->bind_param("ss", $searchTerm, $searchTerm);
$stmt->execute();
$result = $stmt->get_result();
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
            <h2 class="position-absolute top-50 start-50 translate-middle text-center m-0">Data Penyewa </h2>
        </div>
    </nav>


</head>

<body>
    <div class="container my-5">
        <div class="nav">
            <!-- Tombol Kembali ke Halaman Utama -->
            <a type="button" class="btn-close" aria-label="Close" href="home.php"></a>
        </div>
        <!-- Form Pencarian -->
        <div class="container mt-4">
            <form method="POST" action="">
                <div class="row">
                    <div class="col-md-6">
                        <input type="text" class="form-control" name="search" value="<?= $search; ?>" placeholder="Cari Data">
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-outline-primary w-100">Cari</button>
                    </div>
                </div>
            </form>
        </div><br>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
        <!-- Tombol Tambah Data -->
        <div class="container mt-4">
            <div class="d-flex justify-content-end">
                <a type="button" class="btn btn-outline-primary" href="tambah_penyewa.php">Tambah Data</a>
            </div>
        </div>

        <hr>
        <!-- Tabel Data Penyewa -->
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-info">
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Nama </th>
                        <th scope="col">Alamat</th>
                        <th scope="col">No telepon</th>
                        <th scope="col">Aksi</th>
                    </tr>
                </thead>
                <?php $no = 1;
                while ($row = $result->fetch_assoc()) { ?>
                    <tr>
                        <td><?= $no++; ?></td>
                        <td><?= $row['nama_penyewa']; ?></td>
                        <td><?= $row['alamat_penyewa']; ?></td>
                        <td><?= $row['no_telepon_penyewa']; ?></td>
                        <td>
                            <a type="button" class="btn btn-info" href="edit_penyewa.php?id=<?= $row['id_penyewa']; ?>">Edit</a>
                            <a type="button" class="btn btn-danger" href="hapus_penyewa.php?id=<?= $row['id_penyewa']; ?>" onclick="return confirm('Yakin ingin menghapus?')">Hapus</a>
                        </td>
                    </tr>
                <?php } ?>
            </table>
        </div>
    </div>
</body>

</html>