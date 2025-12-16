<?php 
include 'header.php'; 
include 'koneksi.php'; 

// Cek apakah user sudah login & role-nya admin
if(!isset($_SESSION['status']) || $_SESSION['role'] != 'admin'){
    echo "<script>window.location='index.php';</script>";
    exit;
}

// --- DATA RINGKASAN ATAS ---
$barang = mysqli_query($koneksi, "SELECT count(*) as jumlah FROM barang");
$jml_barang = mysqli_fetch_assoc($barang);

$supplier = mysqli_query($koneksi, "SELECT count(*) as jumlah FROM supplier");
$jml_supplier = mysqli_fetch_assoc($supplier);

$user = mysqli_query($koneksi, "SELECT count(*) as jumlah FROM users");
$jml_user = mysqli_fetch_assoc($user);

$tanggal_hari_ini = date('Y-m-d');
$transaksi = mysqli_query($koneksi, "SELECT count(*) as jumlah FROM barang_keluar WHERE tanggal_keluar = '$tanggal_hari_ini'");
$jml_transaksi = mysqli_fetch_assoc($transaksi);

// --- PERSIAPAN DATA UNTUK GRAFIK (CHART) ---

// 1. Data untuk Grafik Barang Terlaris (Top 10)
$label_terlaris = [];
$data_terlaris = [];
$query_laris = mysqli_query($koneksi, "SELECT barang.nama_barang, SUM(barang_keluar.jumlah_keluar) as total 
                                       FROM barang_keluar 
                                       JOIN barang ON barang_keluar.id_barang = barang.id_barang 
                                       GROUP BY barang_keluar.id_barang 
                                       ORDER BY total DESC LIMIT 10");
while($row = mysqli_fetch_array($query_laris)){
    $label_terlaris[] = $row['nama_barang'];
    $data_terlaris[] = $row['total'];
}

// 2. Data untuk Grafik Stok Barang (Top 10 Stok Terbanyak)
$label_stok = [];
$data_stok = [];
$query_stok = mysqli_query($koneksi, "SELECT nama_barang, stok FROM barang ORDER BY stok DESC LIMIT 10");
while($row = mysqli_fetch_array($query_stok)){
    $label_stok[] = $row['nama_barang'];
    $data_stok[] = $row['stok'];
}
?>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Dashboard Admin</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <button type="button" class="btn btn-sm btn-outline-secondary">
            Tanggal: <?php echo date('d-m-Y'); ?>
        </button>
    </div>
</div>

<div class="row">
    <div class="col-md-3 mb-4">
        <div class="card text-white bg-primary shadow h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title mb-0">Total Barang</h6>
                        <h2 class="my-2"><?php echo $jml_barang['jumlah']; ?></h2>
                    </div>
                    <i class="fa-solid fa-box fa-3x opacity-50"></i>
                </div>
            </div>
            <div class="card-footer d-flex align-items-center justify-content-between small">
                <a class="text-white stretched-link text-decoration-none" href="barang.php">Lihat Detail</a>
                <i class="fa-solid fa-angle-right"></i>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-4">
        <div class="card text-white bg-success shadow h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title mb-0">Total Supplier</h6>
                        <h2 class="my-2"><?php echo $jml_supplier['jumlah']; ?></h2>
                    </div>
                    <i class="fa-solid fa-truck fa-3x opacity-50"></i>
                </div>
            </div>
            <div class="card-footer d-flex align-items-center justify-content-between small">
                <a class="text-white stretched-link text-decoration-none" href="supplier.php">Lihat Detail</a>
                <i class="fa-solid fa-angle-right"></i>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-4">
        <div class="card text-white bg-warning shadow h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title mb-0">Transaksi Hari Ini</h6>
                        <h2 class="my-2"><?php echo $jml_transaksi['jumlah']; ?></h2>
                    </div>
                    <i class="fa-solid fa-cart-shopping fa-3x opacity-50"></i>
                </div>
            </div>
            <div class="card-footer d-flex align-items-center justify-content-between small">
                <a class="text-white stretched-link text-decoration-none" href="barang_keluar.php">Lihat Transaksi</a>
                <i class="fa-solid fa-angle-right"></i>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-4">
        <div class="card text-white bg-danger shadow h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title mb-0">Total User</h6>
                        <h2 class="my-2"><?php echo $jml_user['jumlah']; ?></h2>
                    </div>
                    <i class="fa-solid fa-users fa-3x opacity-50"></i>
                </div>
            </div>
            <div class="card-footer d-flex align-items-center justify-content-between small">
                <a class="text-white stretched-link text-decoration-none" href="user.php">Lihat User</a>
                <i class="fa-solid fa-angle-right"></i>
            </div>
        </div>
    </div>
</div>

<div class="row mt-2" style="align-items: stretch;">
    
    <div class="col-md-6 mb-4 d-flex">
        <div class="card shadow w-100">
            <div class="card-header bg-white fw-bold">
                <i class="fa-solid fa-chart-simple text-primary"></i> 10 Barang Terlaris (Penjualan)
            </div>
            <div class="card-body">
                <div style="position: relative; height: 300px; width: 100%;">
                    <canvas id="chartTerlaris"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6 mb-4 d-flex">
        <div class="card shadow w-100">
            <div class="card-header bg-white fw-bold">
                <i class="fa-solid fa-chart-bar text-success"></i> Stok Barang Terbanyak
            </div>
            <div class="card-body">
                <div style="position: relative; height: 300px; width: 100%;">
                    <canvas id="chartStok"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mt-2">
    <div class="col-md-12">
        <div class="card shadow">
            <div class="card-header bg-danger text-white">
                <i class="fa-solid fa-triangle-exclamation me-2"></i> Peringatan: Stok Barang Menipis (Kurang dari 5)
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Barang</th>
                                <th>Sisa Stok</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $stok_minim = mysqli_query($koneksi, "SELECT * FROM barang WHERE stok <= 5");
                            if(mysqli_num_rows($stok_minim) > 0){
                                $no = 1;
                                while($d = mysqli_fetch_array($stok_minim)){
                            ?>
                                <tr>
                                    <td><?php echo $no++; ?></td>
                                    <td><?php echo $d['nama_barang']; ?></td>
                                    <td class="text-danger fw-bold"><?php echo $d['stok']; ?> Unit</td>
                                    <td>
                                        <a href="tambah_barang_masuk.php" class="btn btn-primary btn-sm">Restock Sekarang</a>
                                    </td>
                                </tr>
                            <?php 
                                }
                            } else {
                                echo "<tr><td colspan='4' class='text-center text-success fw-bold'>Aman! Tidak ada barang yang stoknya menipis.</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // --- 1. Konfigurasi Chart Barang Terlaris (Ubah jadi BAR CHART ORANYE) ---
    const ctx1 = document.getElementById('chartTerlaris').getContext('2d');
    new Chart(ctx1, {
        type: 'bar', // Diubah dari 'doughnut' menjadi 'bar'
        data: {
            labels: <?php echo json_encode($label_terlaris); ?>,
            datasets: [{
                label: 'Jumlah Terjual',
                data: <?php echo json_encode($data_terlaris); ?>,
                backgroundColor: '#FF4500', // Warna Oranye sesuai request
                hoverBackgroundColor: '#E03E00',
                borderWidth: 0,
                barPercentage: 0.7
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false, // Agar mengikuti tinggi container (300px)
            plugins: {
                legend: { display: false }, // Sembunyikan legenda agar mirip gambar
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return context.raw + ' Unit';
                        }
                    }
                },
                // Plugin Kustom: Menggambar Panah Biru (Opsional)
                // Panah ini akan menggambar garis dari kiri bawah ke kanan atas secara visual
                afterDraw: chart => {
                    const ctx = chart.ctx;
                    ctx.save();
                    
                    const xAxis = chart.scales.x;
                    const yAxis = chart.scales.y;
                    
                    // Koordinat Awal (Data Pertama) dan Akhir (Data Terakhir)
                    // Note: Karena datanya mungkin tidak urut "naik" seperti gambar bulan JAN-DES,
                    // Panah ini hanya pemanis visual.
                    const startX = xAxis.getPixelForValue(0); 
                    const endX = xAxis.getPixelForValue(<?php echo count($label_terlaris) - 1; ?>);
                    
                    // Kita set posisi Y secara manual agar terlihat "naik" melintang grafik
                    // (Mengambil 80% tinggi chart dari bawah ke 20% dari atas)
                    const startY = yAxis.bottom - 40; 
                    const endY = yAxis.top + 20;

                    ctx.beginPath();
                    ctx.moveTo(startX, startY);
                    ctx.lineTo(endX, endY);
                    ctx.lineWidth = 3;
                    ctx.strokeStyle = '#007bff'; // Warna panah biru
                    ctx.stroke();

                    // Kepala Panah
                    const headLen = 10;
                    const angle = Math.atan2(endY - startY, endX - startX);
                    ctx.beginPath();
                    ctx.moveTo(endX, endY);
                    ctx.lineTo(endX - headLen * Math.cos(angle - Math.PI / 6), endY - headLen * Math.sin(angle - Math.PI / 6));
                    ctx.lineTo(endX - headLen * Math.cos(angle + Math.PI / 6), endY - headLen * Math.sin(angle + Math.PI / 6));
                    ctx.fillStyle = '#007bff';
                    ctx.fill();
                    ctx.restore();
                }
            },
            scales: {
                y: { 
                    beginAtZero: true,
                    ticks: { stepSize: 1 } 
                },
                x: {
                    grid: { display: false } // Hilangkan garis grid vertikal agar bersih
                }
            }
        }
    });

    // --- 2. Konfigurasi Chart Stok Barang (Warna BIRU MUDA) ---
    const ctx2 = document.getElementById('chartStok').getContext('2d');
    new Chart(ctx2, {
        type: 'bar',
        data: {
            labels: <?php echo json_encode($label_stok); ?>,
            datasets: [{
                label: 'Jumlah Stok Saat Ini',
                data: <?php echo json_encode($data_stok); ?>,
                backgroundColor: '#87CEFA', // Biru muda (LightSkyBlue)
                borderColor: '#007bff',
                borderWidth: 1,
                barPercentage: 0.6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false, // Agar mengikuti tinggi container (300px)
            plugins: {
                legend: { 
                    position: 'top', 
                    align: 'end',
                    labels: { boxWidth: 12 } 
                }
            },
            scales: {
                y: { beginAtZero: true },
                x: { grid: { display: false } }
            }
        }
    });
</script>

<?php include 'footer.php'; ?>