<?php include 'header.php'; include 'koneksi.php'; 

if($_SESSION['role'] != 'admin'){
    echo "<script>alert('Akses Ditolak!'); window.location='index.php';</script>";
    exit;
}
?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Data Supplier</h1>
    <a href="tambah_supplier.php" class="btn btn-primary"><i class="fa-solid fa-plus"></i> Tambah Supplier</a>
</div>

<div class="table-responsive">
    <table class="table table-striped table-bordered table-hover">
        <thead class="table-dark">
            <tr>
                <th width="5%">No</th>
                <th>Nama Supplier</th>
                <th>No. Telepon</th>
                <th>Alamat</th>
                <th width="15%">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $no = 1;
            $data = mysqli_query($koneksi, "SELECT * FROM supplier ORDER BY id_supplier DESC");
            while($d = mysqli_fetch_array($data)){
            ?>
            <tr>
                <td><?php echo $no++; ?></td>
                <td><?php echo $d['nama_supplier']; ?></td>
                
                <td><?php echo wordwrap($d['no_telp'], 4, '-', true); ?></td>
                
                <td><?php echo $d['alamat']; ?></td>
                <td>
                    <a href="edit_supplier.php?id=<?php echo $d['id_supplier']; ?>" class="btn btn-warning btn-sm">Edit</a>
                    <a href="hapus_supplier.php?id=<?php echo $d['id_supplier']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin hapus data ini?')">Hapus</a>
                </td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

<?php include 'footer.php'; ?>