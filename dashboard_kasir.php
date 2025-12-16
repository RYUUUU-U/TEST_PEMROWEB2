<?php 
// Matikan error reporting kasar
error_reporting(0);
include 'header.php'; 
include 'koneksi.php'; 

// Cek Role Kasir
if(!isset($_SESSION['status']) || $_SESSION['role'] != 'kasir'){
    echo "<script>window.location='index.php';</script>";
    exit;
}

// Set Timezone
date_default_timezone_set('Asia/Jakarta');
$tanggal_hari_ini = date('Y-m-d'); 

// COUNTER DATA
$barang = mysqli_query($koneksi, "SELECT count(*) as jumlah FROM barang");
$jml_barang = mysqli_fetch_assoc($barang);

$transaksi_masuk = mysqli_query($koneksi, "SELECT count(*) as jumlah FROM barang_masuk WHERE DATE(tanggal_masuk) = '$tanggal_hari_ini'");
$jml_transaksi_masuk = mysqli_fetch_assoc($transaksi_masuk);

$transaksi_keluar = mysqli_query($koneksi, "SELECT count(*) as jumlah FROM barang_keluar WHERE DATE(tanggal_keluar) = '$tanggal_hari_ini'");
$jml_transaksi_keluar = mysqli_fetch_assoc($transaksi_keluar);
?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Halo, <?php echo isset($_SESSION['nama_lengkap']) ? $_SESSION['nama_lengkap'] : ''; ?></h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <button type="button" class="btn btn-sm btn-outline-secondary">
            <i class="fa-regular fa-calendar me-1"></i> <?php echo date('d F Y'); ?>
        </button>
    </div>
</div>

<div class="row">
    <div class="col-md-4 mb-3">
    <div class="card text-white bg-info shadow h-100">
        <div class="card-body d-flex justify-content-between align-items-center">
            <div>
                <h6 class="card-title mb-0">Total Jenis Barang</h6>
                <h2 class="my-2 fw-bold"><?php echo $jml_barang['jumlah']; ?> Item</h2>
            </div>
            <i class="fa-solid fa-boxes-stacked fa-4x opacity-25"></i>
        </div>
        <div class="card-footer bg-transparent border-0 small">
            <a class="text-white text-decoration-none" href="barang_kasir.php">
                Lihat Detail Gudang <i class="fa-solid fa-arrow-right"></i>
            </a>
        </div>
    </div>
</div>

<div class="col-md-4 mb-3">
    <div class="card text-white bg-success shadow h-100">
        <div class="card-body d-flex justify-content-between align-items-center">
            <div>
                <h6 class="card-title mb-0">Barang Masuk (Hari Ini)</h6>
                <h2 class="my-2 fw-bold"><?php echo $jml_transaksi_masuk['jumlah']; ?> Transaksi</h2>
            </div>
            <i class="fa-solid fa-dolly fa-4x opacity-25"></i>
        </div>
        <div class="card-footer bg-transparent border-0 small">
            <a class="text-white text-decoration-none" href="tambah_barang_masuk.php">
                Input Masuk <i class="fa-solid fa-arrow-right"></i>
            </a>
        </div>
    </div>
</div>
    <div class="col-md-4 mb-3">
        <div class="card text-white bg-warning shadow h-100">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="card-title mb-0">Barang Keluar (Hari Ini)</h6>
                    <h2 class="my-2 fw-bold"><?php echo $jml_transaksi_keluar['jumlah']; ?> Transaksi</h2>
                </div>
                <i class="fa-solid fa-truck-ramp-box fa-4x opacity-25"></i>
            </div>
            <div class="card-footer bg-transparent border-0 small">
                <a class="text-white text-decoration-none fw-bold" href="tambah_barang_keluar.php">MULAI TRANSAKSI KASIR <i class="fa-solid fa-arrow-right"></i></a>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-12">
        <div class="card shadow border-danger">
            <div class="card-header bg-danger text-white d-flex justify-content-between align-items-center">
                <span class="fw-bold"><i class="fa-solid fa-triangle-exclamation me-2"></i> Peringatan Stok Menipis (<= 5)</span>
                <span class="badge bg-white text-danger">Segera Lapor!</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">Nama Barang</th>
                                <th>Sisa Stok</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $stok_minim = mysqli_query($koneksi, "SELECT * FROM barang WHERE stok <= 5");
                            if(mysqli_num_rows($stok_minim) > 0){
                                while($d = mysqli_fetch_array($stok_minim)){
                            ?>
                                <tr>
                                    <td class="ps-4 fw-bold"><?php echo $d['nama_barang']; ?></td>
                                    <td><span class="badge bg-danger rounded-pill"><?php echo $d['stok']; ?> Unit</span></td>
                                </tr>
                            <?php 
                                }
                            } else {
                                echo "<tr><td colspan='2' class='text-center py-2 text-success fw-bold'>Aman! Tidak ada stok kritis.</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>