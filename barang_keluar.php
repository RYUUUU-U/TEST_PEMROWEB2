<?php 
include 'header.php'; 
include 'koneksi.php'; 

// Cek Role (Admin & Owner boleh lihat)
if(!isset($_SESSION['status']) || ($_SESSION['role'] != 'admin' && $_SESSION['role'] != 'owner')){
    echo "<script>window.location='index.php';</script>";
    exit;
}

// --- LOGIKA FILTER & PAGINATION ---
$limit = isset($_GET['limit']) ? $_GET['limit'] : 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$halaman_awal = ($page > 1) ? ($page * $limit) - $limit : 0;
$tgl_mulai = isset($_GET['tgl_mulai']) ? $_GET['tgl_mulai'] : "";
$tgl_selesai = isset($_GET['tgl_selesai']) ? $_GET['tgl_selesai'] : "";

// Filter Query
$where_clause = "";
if(!empty($tgl_mulai) && !empty($tgl_selesai)){
    $where_clause = "WHERE DATE(barang_keluar.tanggal_keluar) BETWEEN '$tgl_mulai' AND '$tgl_selesai'";
}

// Hitung Total Data
$query_count = "SELECT count(*) as total FROM barang_keluar $where_clause";
$result_count = mysqli_query($koneksi, $query_count);
$row_count = mysqli_fetch_assoc($result_count);
$total_records = $row_count['total'];
$total_pages = ceil($total_records / $limit);

// Query Data Utama
$query_data = "SELECT barang_keluar.*, barang.nama_barang 
               FROM barang_keluar 
               LEFT JOIN barang ON barang_keluar.id_barang = barang.id_barang 
               $where_clause 
               ORDER BY barang_keluar.tanggal_keluar DESC 
               LIMIT $halaman_awal, $limit";

$data_transaksi = mysqli_query($koneksi, $query_data);
$nomor = $halaman_awal + 1;
?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2"><i class="fa-solid fa-file-invoice-dollar"></i> Laporan Barang Keluar (Penjualan)</h1>
</div>

<div class="card shadow mb-4">
    <div class="card-header bg-light py-2">
        <h6 class="m-0 font-weight-bold text-dark small"><i class="fa-solid fa-filter me-1"></i> Filter Laporan</h6>
    </div>
    <div class="card-body py-3">
        <form method="GET" action="">
            <input type="hidden" name="limit" value="<?php echo $limit; ?>">
            <div class="row g-2 align-items-end">
                <div class="col-md-3">
                    <label class="form-label fw-bold small mb-1">Dari Tanggal</label>
                    <input type="date" name="tgl_mulai" class="form-control form-control-sm" value="<?php echo $tgl_mulai; ?>">
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-bold small mb-1">Sampai Tanggal</label>
                    <input type="date" name="tgl_selesai" class="form-control form-control-sm" value="<?php echo $tgl_selesai; ?>">
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-primary btn-sm fw-bold"><i class="fa-solid fa-filter"></i> Terapkan</button>
                    <a href="barang_keluar.php" class="btn btn-secondary btn-sm"><i class="fa-solid fa-rotate"></i> Reset</a>
                    
                    <div class="btn-group ms-2">
                        <button type="button" class="btn btn-success btn-sm dropdown-toggle" data-bs-toggle="dropdown">
                            <i class="fa-solid fa-download"></i> Export
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" target="_blank" href="export_excel.php?tgl_mulai=<?php echo $tgl_mulai; ?>&tgl_selesai=<?php echo $tgl_selesai; ?>">Excel</a></li>
                            <li><a class="dropdown-item" target="_blank" href="cetak_laporan.php?tgl_mulai=<?php echo $tgl_mulai; ?>&tgl_selesai=<?php echo $tgl_selesai; ?>">PDF / Print</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="card shadow mb-4">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <h6 class="m-0 font-weight-bold text-primary">Data Transaksi</h6>
        <form method="GET" action="" class="d-flex align-items-center">
            <input type="hidden" name="tgl_mulai" value="<?php echo $tgl_mulai; ?>">
            <input type="hidden" name="tgl_selesai" value="<?php echo $tgl_selesai; ?>">
            <span class="small me-2 text-muted">Show:</span>
            <select name="limit" class="form-select form-select-sm" style="width: auto;" onchange="this.form.submit()">
                <option value="10" <?php if($limit == 10) echo 'selected'; ?>>10</option>
                <option value="25" <?php if($limit == 25) echo 'selected'; ?>>25</option>
                <option value="50" <?php if($limit == 50) echo 'selected'; ?>>50</option>
                <option value="100" <?php if($limit == 100) echo 'selected'; ?>>100</option>
            </select>
        </form>
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped mb-0" width="100%" cellspacing="0">
                <thead class="table-dark">
                    <tr>
                        <th width="5%">No</th>
                        <th>Waktu Transaksi</th>
                        <th>Nama Barang</th>
                        <th class="text-center">Qty</th>
                        <th class="text-end">Total Harga</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    if(mysqli_num_rows($data_transaksi) > 0){
                        while($d = mysqli_fetch_array($data_transaksi)){
                            $waktu = date('d/m/Y H:i', strtotime($d['tanggal_keluar']));
                    ?>
                        <tr>
                            <td><?php echo $nomor++; ?></td>
                            <td><b><?php echo $waktu; ?> WIB</b></td>
                            <td><?php echo $d['nama_barang']; ?></td>
                            <td class="text-center fw-bold"><?php echo $d['jumlah_keluar']; ?></td>
                            <td class="text-end text-success fw-bold">Rp <?php echo number_format($d['total_harga']); ?></td>
                        </tr>
                    <?php 
                        }
                    } else {
                        echo "<tr><td colspan='5' class='text-center py-5 text-muted'>Data tidak ditemukan.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-between align-items-center mt-3">
            <small class="text-muted">Halaman <?php echo $page; ?> dari <?php echo $total_pages; ?></small>
            <nav>
                <ul class="pagination pagination-sm mb-0">
                    <li class="page-item <?php if($page <= 1){ echo 'disabled'; } ?>">
                        <a class="page-link" href="<?php if($page > 1){ echo "?page=".($page-1)."&limit=$limit&tgl_mulai=$tgl_mulai&tgl_selesai=$tgl_selesai"; } ?>">Prev</a>
                    </li>
                    <li class="page-item <?php if($page >= $total_pages){ echo 'disabled'; } ?>">
                        <a class="page-link" href="<?php if($page < $total_pages){ echo "?page=".($page+1)."&limit=$limit&tgl_mulai=$tgl_mulai&tgl_selesai=$tgl_selesai"; } ?>">Next</a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>