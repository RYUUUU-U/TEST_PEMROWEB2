<?php 
include 'header.php'; // Pastikan header.php tidak memuat menu edit/hapus jika role owner
include 'koneksi.php'; 

// Cek Role Owner
if($_SESSION['role'] != 'owner'){
    echo "<script>window.location='index.php';</script>";
    exit;
}

// DATA RINGKASAN (FOKUS KE KEUANGAN/STATISTIK)
$barang = mysqli_query($koneksi, "SELECT count(*) as jumlah FROM barang");
$jml_barang = mysqli_fetch_assoc($barang);

$transaksi = mysqli_query($koneksi, "SELECT count(*) as jumlah FROM barang_keluar");
$jml_transaksi = mysqli_fetch_assoc($transaksi);

// Data Grafik Penjualan (Sama seperti Admin)
$label_terlaris = [];
$data_terlaris = [];
$query_laris = mysqli_query($koneksi, "SELECT barang.nama_barang, SUM(barang_keluar.jumlah_keluar) as total FROM barang_keluar JOIN barang ON barang_keluar.id_barang = barang.id_barang GROUP BY barang_keluar.id_barang ORDER BY total DESC LIMIT 10");
while($row = mysqli_fetch_array($query_laris)){
    $label_terlaris[] = $row['nama_barang'];
    $data_terlaris[] = $row['total'];
}
?>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Dashboard Owner</h1>
</div>

<div class="alert alert-success">
    Selamat Datang, <b>Owner</b>. Berikut adalah ringkasan performa toko Anda.
</div>

<div class="row">
    <div class="col-md-6 mb-4">
        <div class="card text-white bg-primary shadow h-100">
            <div class="card-body">
                <h6 class="card-title">Total Produk Terdaftar</h6>
                <h2 class="my-2"><?php echo $jml_barang['jumlah']; ?> Item</h2>
            </div>
        </div>
    </div>
    <div class="col-md-6 mb-4">
        <div class="card text-white bg-success shadow h-100">
            <div class="card-body">
                <h6 class="card-title">Total Transaksi Penjualan</h6>
                <h2 class="my-2"><?php echo $jml_transaksi['jumlah']; ?> Transaksi</h2>
            </div>
        </div>
    </div>
</div>

<div class="card shadow mb-4">
    <div class="card-header font-weight-bold">
        Laporan Barang Terlaris
    </div>
    <div class="card-body">
        <div style="height: 300px;">
            <canvas id="chartOwner"></canvas>
        </div>
    </div>
</div>

<script>
    const ctx = document.getElementById('chartOwner').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: <?php echo json_encode($label_terlaris); ?>,
            datasets: [{
                label: 'Jumlah Terjual',
                data: <?php echo json_encode($data_terlaris); ?>,
                backgroundColor: '#FF4500'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false
        }
    });
</script>

<?php include 'footer.php'; ?>