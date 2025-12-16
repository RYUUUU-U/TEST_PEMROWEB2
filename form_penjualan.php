<?php 
include 'header.php'; 
include 'koneksi.php'; 
date_default_timezone_set('Asia/Jakarta');

// --- LOGIKA SIMPAN TRANSAKSI ---
if(isset($_POST['simpan'])){
    $id_barang = $_POST['id_barang'];
    $jumlah = $_POST['jumlah'];
    $tanggal_input = $_POST['tanggal']; 
    $jam_sekarang = date('H:i:s');      
    $waktu_lengkap = $tanggal_input . ' ' . $jam_sekarang; 

    $cek_barang = mysqli_query($koneksi, "SELECT * FROM barang WHERE id_barang='$id_barang'");
    $data_barang = mysqli_fetch_array($cek_barang);
    
    // Validasi Stok
    if($data_barang['stok'] < $jumlah){
        echo "<script>alert('Gagal! Stok barang tidak mencukupi (Sisa: ".$data_barang['stok'].").');</script>";
    } else {
        $total_bayar = $jumlah * $data_barang['harga_jual'];
        
        // Simpan Data
        $input = mysqli_query($koneksi, "INSERT INTO barang_keluar VALUES (NULL, '$id_barang', '$waktu_lengkap', '$jumlah', '$total_bayar')");
        
        if($input){
            // Kurangi Stok
            mysqli_query($koneksi, "UPDATE barang SET stok = stok - $jumlah WHERE id_barang='$id_barang'");
            
            // Redirect KEMBALI KE TABEL (tambah_barang_keluar.php)
            echo "<script>
                    alert('Transaksi Berhasil Disimpan!'); 
                    window.location='tambah_barang_keluar.php'; 
                  </script>";
        }
    }
}
?>

<div class="row mt-4">
    <div class="col-md-6 mx-auto">
        <div class="card shadow">
            <div class="card-header bg-danger text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold"><i class="fa-solid fa-cart-shopping me-2"></i> Input Barang Keluar</h5>
            </div>
            <div class="card-body">
                <form method="POST">
                    
                    <div class="mb-3">
                        <label class="fw-bold">Tanggal Transaksi</label>
                        <input type="date" name="tanggal" class="form-control" value="<?php echo date('Y-m-d'); ?>" readonly>
                        <small class="text-muted">* Jam otomatis tersimpan saat tombol Simpan ditekan.</small>
                    </div>

                    <div class="mb-3">
                        <label class="fw-bold">Pilih Barang</label>
                        <select name="id_barang" class="form-control" required size="5">
                            <option value="" disabled selected>-- Pilih Barang --</option>
                            <?php 
                            $brg = mysqli_query($koneksi, "SELECT * FROM barang WHERE stok > 0 ORDER BY nama_barang ASC");
                            while($b = mysqli_fetch_array($brg)){
                            ?>
                            <option value="<?php echo $b['id_barang']; ?>">
                                <?php echo $b['nama_barang']; ?> (Sisa: <?php echo $b['stok']; ?>) - Rp <?php echo number_format($b['harga_jual']); ?>
                            </option>
                            <?php } ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="fw-bold">Jumlah Keluar (Qty)</label>
                        <input type="number" name="jumlah" class="form-control form-control-lg fw-bold" min="1" placeholder="0" required>
                    </div>
                    
                    <div class="d-flex gap-2">
                        <a href="tambah_barang_keluar.php" class="btn btn-secondary w-50">
                            <i class="fa-solid fa-arrow-left me-2"></i> Kembali
                        </a>
                        <button type="submit" name="simpan" class="btn btn-danger w-50 fw-bold">
                            <i class="fa-solid fa-save me-2"></i> Simpan Transaksi
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>