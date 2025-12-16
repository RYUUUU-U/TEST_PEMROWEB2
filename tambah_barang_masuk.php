<?php 
include 'header.php'; 
include 'koneksi.php'; 

// IZINKAN ADMIN DAN KASIR
if(!isset($_SESSION['status']) || ($_SESSION['role'] != 'admin' && $_SESSION['role'] != 'kasir')){
    echo "<script>window.location='index.php';</script>";
    exit;
}

if(isset($_POST['simpan'])){
    $tanggal = $_POST['tanggal'];
    $id_barang = $_POST['id_barang'];
    $id_supplier = $_POST['id_supplier']; // Pastikan tabel barang_masuk punya kolom id_supplier, jika tidak hapus bagian ini
    $jumlah = $_POST['jumlah'];

    // Simpan ke barang_masuk
    // Sesuaikan query ini dengan struktur tabel Anda
    // Asumsi tabel: id_masuk, id_barang, id_supplier, tanggal_masuk, jumlah_masuk
    $query = "INSERT INTO barang_masuk VALUES (NULL, '$id_barang', '$id_supplier', '$tanggal', '$jumlah')";
    $simpan = mysqli_query($koneksi, $query);

    if($simpan){
        // Tambah Stok Barang
        mysqli_query($koneksi, "UPDATE barang SET stok = stok + $jumlah WHERE id_barang='$id_barang'");
        
        // Redirect sesuai role
        if($_SESSION['role'] == 'kasir'){
            echo "<script>alert('Stok Berhasil Ditambah!'); window.location='dashboard_kasir.php';</script>";
        } else {
            echo "<script>alert('Stok Berhasil Ditambah!'); window.location='barang_masuk.php';</script>";
        }
    }
}
?>

<div class="row mt-4">
    <div class="col-md-6 mx-auto">
        <div class="card shadow">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0 fw-bold"><i class="fa-solid fa-dolly me-2"></i> Input Barang Masuk (Restock)</h5>
            </div>
            <div class="card-body">
                <form method="POST">
                    <div class="mb-3">
                        <label>Tanggal Masuk</label>
                        <input type="date" name="tanggal" class="form-control" value="<?php echo date('Y-m-d'); ?>" required>
                    </div>

                    <div class="mb-3">
                        <label>Pilih Barang</label>
                        <select name="id_barang" class="form-control" required>
                            <option value="">-- Pilih Barang --</option>
                            <?php 
                            $brg = mysqli_query($koneksi, "SELECT * FROM barang ORDER BY nama_barang ASC");
                            while($b = mysqli_fetch_array($brg)){
                            ?>
                            <option value="<?php echo $b['id_barang']; ?>">
                                <?php echo $b['nama_barang']; ?> (Stok: <?php echo $b['stok']; ?>)
                            </option>
                            <?php } ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label>Supplier</label>
                        <select name="id_supplier" class="form-control" required>
                            <option value="">-- Pilih Supplier --</option>
                            <?php 
                            $sup = mysqli_query($koneksi, "SELECT * FROM supplier ORDER BY nama_supplier ASC");
                            while($s = mysqli_fetch_array($sup)){
                            ?>
                            <option value="<?php echo $s['id_supplier']; ?>">
                                <?php echo $s['nama_supplier']; ?>
                            </option>
                            <?php } ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label>Jumlah Masuk</label>
                        <input type="number" name="jumlah" class="form-control" min="1" required>
                    </div>

                    <div class="d-flex gap-2">
                        <?php 
                        // Tombol kembali sesuai role
                        $kembali = ($_SESSION['role'] == 'kasir') ? 'dashboard_kasir.php' : 'barang_masuk.php';
                        ?>
                        <a href="<?php echo $kembali; ?>" class="btn btn-secondary w-50">Kembali</a>
                        <button type="submit" name="simpan" class="btn btn-success w-50 fw-bold">Simpan Stok</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>