<?php
session_start();

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['username'])) {
  header("Location: login.php");
  exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <!-- Link ke file CSS terpisah -->
  <link rel="stylesheet" href="asset/css/home.css">

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
  <title>Penyewaan Kendaraan</title>
</head>

<body>
  <!-- Navbar and Header Section -->
  <nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm">
    <div class="container">
      <a class="navbar-brand" href="home.php">
        <img src="asset/img/WARCIDE.svg" alt="Logo" width="60" height="60" class="d-inline-block align-text-top">
        Penyewaan Kendaraan
      </a>
    </div>
  </nav>

  <!-- Main Content -->
  <div class="container my-5">
    <div class="header text-center mb-5">
      <h1 class="display-4 text-primary"><b>Penyewaan Kendaraan</b></h1>
    </div>

    <!-- Cards for sections -->
    <div class="row row-cols-1 row-cols-md-3 g-4">
      <div class="col">
        <div class="card shadow-lg text-center" onclick="window.location.href='kendaraan.php'">
          <img src="asset/img/kendaraan.svg" class="card-img-top mx-auto mt-3" style="width: 80px;">
          <div class="card-body">
            <h4 class="card-title">Kendaraan</h4>
          </div>
        </div>
      </div>

      <div class="col">
        <div class="card shadow-lg text-center" onclick="window.location.href='penyewa.php'">
          <img src="asset/img/penyewaan.svg" class="card-img-top mx-auto mt-3" style="width: 80px;">
          <div class="card-body">
            <h4 class="card-title">Penyewa</h4>
          </div>
        </div>
      </div>

      <div class="col">
        <div class="card shadow-lg text-center" onclick="window.location.href='penyewaan.php'">
          <img src="asset/img/penyewa.svg" class="card-img-top mx-auto mt-3" style="width: 80px;">
          <div class="card-body">
            <h4 class="card-title">Penyewaan</h4>
          </div>
        </div>
      </div>

      <div class="col mt-4">
        <div class="card shadow-lg text-center" onclick="window.location.href='report.php'">
          <img src="asset/img/report.svg" class="card-img-top mx-auto mt-3" style="width: 80px;">
          <div class="card-body">
            <h4 class="card-title">Report</h4>
          </div>
        </div>
      </div>
    </div>

    <!-- Logout Button -->
    <div class="d-flex justify-content-end mt-5">
      <a href="logout.php" class="btn btn-danger btn-logout">LOG OUT</a>
    </div>

    <!-- Bootstrap Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>