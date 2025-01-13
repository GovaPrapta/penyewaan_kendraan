<?php
session_start();

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
include 'koneksi.php';

// Menangani pencarian
$search = '';
if (isset($_POST['search'])) {
    $search = $_POST['search'];
}

// Ambil data kendaraan dengan kondisi pencarian
$sql = "SELECT * FROM kendaraan WHERE jenis_kendaraan LIKE ? OR type_kendaraan LIKE ? OR n_kendaraan LIKE ?";
$stmt = $conn->prepare($sql);
$searchTerm = "%" . $search . "%";
$stmt->bind_param("sss", $searchTerm, $searchTerm, $searchTerm);
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <nav class="navbar navbar-light bg-light">
        <div class="container position-relative">
            <!-- Logo di Kiri -->
            <a class="navbar-brand" href="home.php">
                <img src="asset/img/WARCIDE.svg" alt="" width="50" height="50" class="d-inline-block align-text-top">
            </a>

            <!-- Teks di Tengah -->
            <h2 class="position-absolute top-50 start-50 translate-middle text-center m-0">Data Kendaraan</h2>
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

        <!-- Tombol Tambah Data -->
        <div class="container mt-4">
            <div class="d-flex justify-content-end">
                <a type="button" class="btn btn-outline-primary" href="tambah_kendaraan.php">Tambah Data</a>
            </div>
        </div><br>

        <!-- Tabel Data Kendaraan -->
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-info">
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Jenis Kendaraan</th>
                        <th scope="col">Type</th>
                        <th scope="col">Plat Kendaraan</th>
                        <th scope="col">Harga Kendaraan</th>
                        <th scope="col">Denda Kendaraan</th>
                        <th scope="col">Status</th>
                        <th scope="col">Aksi</th>
                    </tr>
                </thead>
                <?php $no = 1;
                while ($row = $result->fetch_assoc()) { ?>
                    <tr>
                        <td><?= $no++; ?></td>
                        <td><?= $row['jenis_kendaraan']; ?></td>
                        <td><?= $row['type_kendaraan']; ?></td>
                        <td><?= $row['n_kendaraan']; ?></td>
                        <td>Rp. <?= number_format($row['harga_kendaraan'], 0, ',', '.'); ?></td>
                        <td>Rp. <?= number_format($row['denda_kendaraan'], 0, ',', '.'); ?></td>
                        <td><?= $row['status']; ?></td>
                        <td>
                            <!-- Tombol Edit -->
                            <a type="button" class="btn btn-info" href="edit_kendaraan.php?id=<?= $row['id_kendaraan']; ?>">Edit</a>
                            <!-- Hapus Data -->
                            <!-- Tombol Hapus dengan SweetAlert -->
                            <a type="button" class="btn btn-danger delete-btn" data-id="<?= $row['id_kendaraan']; ?>" href="kendaraan_hapus.php">Hapus</a>
                        </td>
                    </tr>
                <?php } ?>
            </table>
        </div>
    </div>
    <script>
        // Menangani klik tombol hapus dengan SweetAlert
        document.querySelectorAll('.delete-btn').forEach(button => {
            button.addEventListener('click', function(event) {
                event.preventDefault(); // Mencegah link melakukan navigasi langsung
                const id = this.getAttribute('data-id'); // Ambil ID kendaraan dari data-id

                // Menampilkan konfirmasi SweetAlert
                Swal.fire({
                    title: 'Yang bener?',
                    text: "beneran nih mau dihapus?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Jika pengguna mengonfirmasi, arahkan ke halaman hapus
                        window.location.href = 'hapus_kendaraan.php?id=' + id;
                    }
                });
            });
        });
    </script>
</body>

</html>