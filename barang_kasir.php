<?php 
include 'header.php'; 
include 'koneksi.php'; 

// KEAMANAN: Cek apakah yang akses benar-benar Kasir
if(!isset($_SESSION['role']) || $_SESSION['role'] != 'kasir'){
    echo "<script>alert('Akses Ditolak!'); window.location='index.php';</script>";
    exit;
}

// LOGIKA PENCARIAN
$keyword = "";
if(isset($_GET['cari'])){
    $keyword = $_GET['cari'];
    // Cari berdasarkan Nama Barang ATAU Kode Barang
    $query_barang = "SELECT * FROM barang WHERE nama_barang LIKE '%$keyword%' OR kode_barang LIKE '%$keyword%' ORDER BY stok ASC";
} else {
    // Default: Tampilkan semua, urutkan dari stok TERKECIL agar stok habis terlihat duluan
    $query_barang = "SELECT * FROM barang ORDER BY stok ASC";
}
$result = mysqli_query($koneksi, $query_barang);
?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2"><i class="fa-solid fa-boxes-stacked"></i> Data Gudang (View Only)</h1>
</div>

<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between bg-white">
        <h6 class="m-0 font-weight-bold text-primary">Daftar Stok Barang</h6>
        
        <form method="GET" action="" class="d-flex">
            <input type="text" name="cari" class="form-control form-control-sm me-2" placeholder="Cari nama/kode..." value="<?php echo $keyword; ?>">
            <button type="submit" class="btn btn-primary btn-sm"><i class="fa-solid fa-search"></i> Cari</button>
            <?php if(isset($_GET['cari'])): ?>
                <a href="barang_kasir.php" class="btn btn-secondary btn-sm ms-1">Reset</a>
            <?php endif; ?>
        </form>
    </div>
    
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th width="5%">No</th>
                        <th>Kode Barang</th>
                        <th>Nama Barang</th>
                        <th>Kategori</th>
                        <th>Satuan</th>
                        <th class="text-end">Harga Jual</th>
                        <th class="text-center">Stok</th>
                        <th class="text-center">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    if(mysqli_num_rows($result) > 0){
                        $no = 1;
                        while($d = mysqli_fetch_array($result)){
                            // Logika Warna Stok
                            $bg_stok = "bg-success";
                            $ket_stok = "Aman";
                            if($d['stok'] <= 5){
                                $bg_stok = "bg-danger"; // Merah jika stok <= 5
                                $ket_stok = "Kritis";
                            } elseif($d['stok'] <= 20){
                                $bg_stok = "bg-warning text-dark"; // Kuning jika stok menipis
                                $ket_stok = "Menipis";
                            }
                    ?>
                    <tr>
                        <td><?php echo $no++; ?></td>
                        <td><span class="badge bg-secondary"><?php echo $d['kode_barang']; ?></span></td>
                        <td class="fw-bold"><?php echo $d['nama_barang']; ?></td>
                        <td><?php echo $d['kategori']; ?></td>
                        <td><?php echo $d['satuan']; ?></td>
                        <td class="text-end">Rp <?php echo number_format($d['harga_jual']); ?></td>
                        <td class="text-center fw-bold fs-5"><?php echo $d['stok']; ?></td>
                        <td class="text-center">
                            <span class="badge <?php echo $bg_stok; ?>"><?php echo $ket_stok; ?></span>
                        </td>
                    </tr>
                    <?php 
                        }
                    } else {
                        echo "<tr><td colspan='8' class='text-center py-5'>Data tidak ditemukan.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>