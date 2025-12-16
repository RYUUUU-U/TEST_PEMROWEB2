<?php
if (session_status() == PHP_SESSION_NONE) { session_start(); }

// Cek status login
if(!isset($_SESSION['status']) || $_SESSION['status'] != "login"){
    header("location:index.php?pesan=Belum Login");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <title>Sistem Inventory</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4 shadow">
  <div class="container">
    <a class="navbar-brand fw-bold" href="#">
        <i class="fa-solid fa-motorcycle me-2"></i>Bengkel Motor
    </a>
    
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        
        <li class="nav-item">
            <?php 
                $dash = "dashboard_admin.php";
                if($_SESSION['role']=='kasir'){ $dash="dashboard_kasir.php"; }
                if($_SESSION['role']=='owner'){ $dash="dashboard_owner.php"; }
            ?>
            <a class="nav-link" href="<?php echo $dash; ?>">Dashboard</a>
        </li>

        <?php if($_SESSION['role'] == 'admin') { ?>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                    Data Master
                </a>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="barang.php">Data Barang (Edit)</a></li>
                    <li><a class="dropdown-item" href="supplier.php">Data Supplier</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="user.php">Data User</a></li>
                </ul>
            </li>
            <li class="nav-item"><a class="nav-link" href="barang_masuk.php">Barang Masuk</a></li>
            <li class="nav-item"><a class="nav-link" href="barang_keluar.php">Barang Keluar</a></li>
        <?php } ?>

        <?php if($_SESSION['role'] == 'kasir') { ?>
            <li class="nav-item">
                <a class="nav-link" href="tambah_barang_keluar.php">Transaksi Penjualan</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="barang_kasir.php">Cek Gudang</a>
            </li>
        <?php } ?>
        
        <?php if($_SESSION['role'] == 'owner') { ?>
             <li class="nav-item"><a class="nav-link" href="barang_keluar.php">Laporan Keuangan</a></li>
        <?php } ?>

      </ul>
      
      <span class="navbar-text text-white me-3">
        Halo, <b><?php echo $_SESSION['nama_lengkap']; ?></b> (<?php echo ucfirst($_SESSION['role']); ?>)
      </span>
      <a href="logout.php" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin logout?')">Logout</a>
    </div>
  </div>
</nav>

<div class="container p-4 bg-white rounded shadow-sm" style="min-height: 400px;">