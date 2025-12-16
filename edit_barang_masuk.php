<?php 
include 'header.php'; 
include 'koneksi.php'; 

// Cek Admin
if($_SESSION['role'] != 'admin'){
    echo "<script>window.location='index.php';</script>";
    exit;
}

$id = $_GET['id'];
$data = mysqli_query($koneksi, "SELECT * FROM barang_masuk WHERE id_masuk='$id'");
$d = mysqli_fetch_assoc($data);

if(isset($_POST['update'])){
    $id_masuk = $_POST['id_masuk'];
    $id_barang = $_POST['id_barang']; // ID Barang tidak diubah (readonly) agar logika stok tidak rumit
    $jumlah_baru = $_POST['jumlah'];
    $tanggal = $_POST['tanggal'];
    $id_supplier = $_POST['id_supplier'];

    // 1. Ambil jumlah lama untuk hitung selisih
    $cek_lama = mysqli_query($koneksi, "SELECT jumlah_masuk FROM barang_masuk WHERE id_masuk='$id_masuk'");
    $row_lama = mysqli_fetch_assoc($cek_lama);
    $jumlah_lama = $row_lama['jumlah_masuk'];

    // 2. Hitung selisih
    // Jika Baru 15, Lama 10 -> Selisih 5 (Stok nambah 5)
    // Jika Baru 5, Lama 10 -> Selisih -5 (Stok kurang 5)
    $selisih = $jumlah_baru - $jumlah_lama;

    // 3. Update Transaksi
    $update = mysqli_query($koneksi, "UPDATE barang_masuk SET 
                                      id_supplier='$id_supplier', 
                                      tanggal_masuk='$tanggal', 
                                      jumlah_masuk='$jumlah_baru' 
                                      WHERE id_masuk='$id_masuk'");

    if($update){
        // 4. Update Stok Barang sesuai selisih
        // Jika selisih positif, stok nambah. Jika negatif, stok kurang.
        mysqli_query($koneksi, "UPDATE barang SET stok = stok + $selisih WHERE id_barang='$id_barang'");
        
        echo "<script>alert('Data Berhasil Diupdate! Stok otomatis disesuaikan.'); window.location='barang_masuk.php';</script>";
    } else {
        echo "<script>alert('Gagal Update');</script>";
    }
}
?>

<div class="row mt-4">
    <div class="col-md-6 mx-auto">
        <div class="card shadow">
            <div class="card-header bg-warning text-white">
                <h5 class="mb-0 fw-bold"><i class="fa-solid fa-pen-to-square me-2"></i> Edit Data Barang Masuk</h5>
            </div>
            <div class="card-body">
                <form method="POST">
                    <input type="hidden" name="id_masuk" value="<?php echo $d['id_masuk']; ?>">
                    
                    <div class="mb-3">
                        <label>Nama Barang (Tidak Bisa Diubah)</label>
                        <input type="hidden" name="id_barang" value="<?php echo $d['id_barang']; ?>">
                        <?php 
                            $brg = mysqli_query($koneksi, "SELECT nama_barang FROM barang WHERE id_barang='".$d['id_barang']."'");
                            $b = mysqli_fetch_assoc($brg);
                        ?>
                        <input type="text" class="form-control" value="<?php echo $b['nama_barang']; ?>" readonly style="background-color: #e9ecef;">
                        <small class="text-danger">* Jika salah barang, silakan Hapus data ini dan input baru.</small>
                    </div>

                    <div class="mb-3">
                        <label>Tanggal Masuk</label>
                        <input type="date" name="tanggal" class="form-control" value="<?php echo $d['tanggal_masuk']; ?>" required>
                    </div>

                    <div class="mb-3">
                        <label>Supplier</label>
                        <select name="id_supplier" class="form-control" required>
                            <?php 
                            $sup = mysqli_query($koneksi, "SELECT * FROM supplier ORDER BY nama_supplier ASC");
                            while($s = mysqli_fetch_array($sup)){
                            ?>
                            <option value="<?php echo $s['id_supplier']; ?>" <?php if($d['id_supplier'] == $s['id_supplier']) echo 'selected'; ?>>
                                <?php echo $s['nama_supplier']; ?>
                            </option>
                            <?php } ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label>Jumlah Masuk (Edit Angka)</label>
                        <input type="number" name="jumlah" class="form-control fw-bold border-warning" value="<?php echo $d['jumlah_masuk']; ?>" min="1" required>
                        <small class="text-muted">* Stok gudang akan otomatis bertambah/berkurang sesuai perubahan angka ini.</small>
                    </div>

                    <div class="d-flex gap-2">
                        <a href="barang_masuk.php" class="btn btn-secondary w-50">Batal</a>
                        <button type="submit" name="update" class="btn btn-warning w-50 fw-bold text-white">Update Data</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>