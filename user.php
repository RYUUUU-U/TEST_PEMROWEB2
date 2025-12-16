<?php include 'header.php'; include 'koneksi.php'; 

if($_SESSION['role'] != 'admin'){
    echo "<script>alert('Akses Ditolak! Hanya Admin yang boleh masuk.'); window.location='index.php';</script>";
    exit;
}
?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Data User / Pengguna</h1>
    <a href="tambah_user.php" class="btn btn-primary"><i class="fa-solid fa-plus"></i> Tambah User</a>
</div>

<div class="table-responsive">
    <table class="table table-striped table-bordered table-hover">
        <thead class="table-dark">
            <tr>
                <th width="5%">No</th>
                <th>Nama Lengkap</th>
                <th>Username</th>
                <th>Role (Hak Akses)</th>
                <th width="15%">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $no = 1;
            // PERBAIKAN: Ganti 'user' menjadi 'users'
            $data = mysqli_query($koneksi, "SELECT * FROM users ORDER BY id_user DESC");
            while($d = mysqli_fetch_array($data)){
            ?>
            <tr>
                <td><?php echo $no++; ?></td>
                <td><?php echo $d['nama_lengkap']; ?></td>
                <td><?php echo $d['username']; ?></td>
                <td>
                    <?php 
                    if($d['role'] == 'admin') {
                        echo '<span class="badge bg-success">Admin</span>';
                    } else {
                        echo '<span class="badge bg-secondary">Petugas/Kasir</span>';
                    }
                    ?>
                </td>
                <td>
                    <a href="edit_user.php?id=<?php echo $d['id_user']; ?>" class="btn btn-warning btn-sm">Edit</a>
                    <?php if($d['username'] != $_SESSION['username']) { ?>
                        <a href="hapus_user.php?id=<?php echo $d['id_user']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin hapus user ini?')">Hapus</a>
                    <?php } ?>
                </td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

<?php include 'footer.php'; ?>