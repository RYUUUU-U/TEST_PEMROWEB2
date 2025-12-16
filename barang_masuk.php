<?php 
include 'header.php'; 
include 'koneksi.php'; 

// Cek Role (Hanya Admin yang boleh edit/hapus)
if(!isset($_SESSION['status'])){
    echo "<script>window.location='index.php';</script>";
    exit;
}

// Logika Filter & Pagination (Sama seperti sebelumnya)
$limit = isset($_GET['limit']) ? $_GET['limit'] : 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$halaman_awal = ($page > 1) ? ($page * $limit) - $limit : 0;
$tgl_mulai = isset($_GET['tgl_mulai']) ? $_GET['tgl_mulai'] : "";
$tgl_selesai = isset($_GET['tgl_selesai']) ? $_GET['tgl_selesai'] : "";

$where_clause = "";
if(!empty($tgl_mulai) && !empty($tgl_selesai)){
    $where_clause = "WHERE DATE(barang_masuk.tanggal_masuk) BETWEEN '$tgl_mulai' AND '$tgl_selesai'";
}

$query_count = "SELECT count(*) as total FROM barang_masuk $where_clause";
$result_count = mysqli_query($koneksi, $query_count);
$row_count = mysqli_fetch_assoc($result_count);
$total_records = $row_count['total'];
$total_pages = ceil($total_records / $limit);

$query_data = "SELECT barang_masuk.*, barang.nama_barang, supplier.nama_supplier 
               FROM barang_masuk 
               LEFT JOIN barang ON barang_masuk.id_barang = barang.id_barang 
               LEFT JOIN supplier ON barang_masuk.id_supplier = supplier.id_supplier
               $where_clause 
               ORDER BY barang_masuk.tanggal_masuk DESC 
               LIMIT $halaman_awal, $limit";

$data_masuk = mysqli_query($koneksi, $query_data);
$nomor = $halaman_awal + 1;
?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2"><i class="fa-solid fa-dolly"></i> Laporan Barang Masuk (Restock)</h1>
    <?php if($_SESSION['role'] == 'admin' || $_SESSION['role'] == 'kasir') { ?>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="tambah_barang_masuk.php" class="btn btn-success fw-bold shadow-sm">
            <i class="fa-solid fa-plus me-2"></i> Input Barang Masuk
        </a>
    </div>
    <?php } ?>
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
                    <a href="barang_masuk.php" class="btn btn-secondary btn-sm"><i class="fa-solid fa-rotate"></i> Reset</a>
                    <div class="btn-group ms-2">
                        <button type="button" class="btn btn-success btn-sm dropdown-toggle" data-bs-toggle="dropdown">
                            <i class="fa-solid fa-download"></i> Export
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" target="_blank" href="export_excel_masuk.php?tgl_mulai=<?php echo $tgl_mulai; ?>&tgl_selesai=<?php echo $tgl_selesai; ?>">Excel</a></li>
                            <li><a class="dropdown-item" target="_blank" href="cetak_masuk.php?tgl_mulai=<?php echo $tgl_mulai; ?>&tgl_selesai=<?php echo $tgl_selesai; ?>">PDF / Print</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="card shadow mb-4">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <h6 class="m-0 font-weight-bold text-success">Data Restock</h6>
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
                        <th>Waktu Masuk</th>
                        <th>Nama Barang</th>
                        <th>Supplier</th>
                        <th class="text-center">Jumlah</th>
                        <?php if($_SESSION['role'] == 'admin') { ?>
                        <th class="text-center" width="10%">Aksi</th>
                        <?php } ?>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    if(mysqli_num_rows($data_masuk) > 0){
                        while($d = mysqli_fetch_array($data_masuk)){
                            $waktu = date('d/m/Y H:i', strtotime($d['tanggal_masuk']));
                    ?>
                        <tr>
                            <td><?php echo $nomor++; ?></td>
                            <td><b><?php echo $waktu; ?> WIB</b></td>
                            <td><?php echo $d['nama_barang']; ?></td>
                            <td><?php echo $d['nama_supplier']; ?></td>
                            <td class="text-center fw-bold text-success">+ <?php echo $d['jumlah_masuk']; ?></td>
                            
                            <?php if($_SESSION['role'] == 'admin') { ?>
                            <td class="text-center">
                                <a href="edit_barang_masuk.php?id=<?php echo $d['id_masuk']; ?>" class="btn btn-warning btn-sm text-white">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>
                                <a href="hapus_barang_masuk.php?id=<?php echo $d['id_masuk']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin hapus? Stok barang akan dikurangi otomatis.')">
                                    <i class="fa-solid fa-trash"></i>
                                </a>
                            </td>
                            <?php } ?>
                        </tr>
                    <?php 
                        }
                    } else {
                        echo "<tr><td colspan='6' class='text-center py-5 text-muted'>Data tidak ditemukan.</td></tr>";
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