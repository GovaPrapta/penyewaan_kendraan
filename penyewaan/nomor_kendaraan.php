<?php
include 'koneksi.php';

if (isset($_GET['type_kendaraan'])) {
    $type_kendaraan = $_GET['type_kendaraan'];

    $query = "SELECT id_kendaraan, n_kendaraan, jenis_kendaraan FROM kendaraan WHERE type_kendaraan = ? AND status = 'tersedia'";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('s', $type_kendaraan);
    $stmt->execute();
    $result = $stmt->get_result();

    $kendaraan = [];
    while ($row = $result->fetch_assoc()) {
        $kendaraan[] = $row;
    }

    echo json_encode($kendaraan);
    $stmt->close();
}
