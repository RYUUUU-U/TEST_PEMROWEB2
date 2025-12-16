<?php 
include 'header.php'; 
include 'koneksi.php'; 

// Proteksi: Cek apakah user adalah admin
if($_SESSION['role'] != 'admin'){
    echo "<script>alert('Akses Ditolak!'); window.location='index.php';</script>";
    exit;
}
?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Data Barang</h1>
    <a href="tambah_barang.php" class="btn btn-primary"><i class="fa-solid fa-plus"></i> Tambah Barang</a>
</div>

<div class="table-responsive">
    <table class="table table-striped table-bordered table-hover">
        <thead class="table-dark">
            <tr>
                <th>No</th>
                <th>Kode</th>
                <th>Nama Barang</th>
                <th>Harga Beli</th>
                <th>Harga Jual</th>
                <th>Stok</th>
                <th>Satuan</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $no = 1;
            $data = mysqli_query($koneksi, "SELECT * FROM barang ORDER BY id_barang DESC");
            while($d = mysqli_fetch_array($data)){
            ?>
            <tr>
                <td><?php echo $no++; ?></td>
                <td><span class="badge bg-secondary"><?php echo $d['kode_barang']; ?></span></td>
                <td><?php echo $d['nama_barang']; ?></td>
                <td>Rp <?php echo number_format($d['harga_beli']); ?></td>
                <td>Rp <?php echo number_format($d['harga_jual']); ?></td>
                <td class="<?php echo ($d['stok'] <= 5) ? 'text-danger fw-bold' : ''; ?>">
                    <?php echo $d['stok']; ?>
                </td>
                <td><?php echo $d['satuan']; ?></td>
                <td>
                    <a href="edit_barang.php?id=<?php echo $d['id_barang']; ?>" class="btn btn-warning btn-sm">Edit</a>
                    <a href="hapus_barang.php?id=<?php echo $d['id_barang']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus data ini?')">Hapus</a>
                </td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

<?php include 'footer.php'; ?>