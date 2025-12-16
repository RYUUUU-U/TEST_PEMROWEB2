<?php 
include 'header.php'; 
include 'koneksi.php'; 

if($_SESSION['role'] != 'admin'){
    echo "<script>alert('Akses Ditolak!'); window.location='index.php';</script>";
    exit;
}

$id = $_GET['id'];
$data = mysqli_query($koneksi, "SELECT * FROM barang WHERE id_barang='$id'");
$d = mysqli_fetch_array($data);
?>

<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card shadow">
            <div class="card-header bg-warning">
                <h5 class="mb-0">Edit Data Barang</h5>
            </div>
            <div class="card-body">
                <form method="POST">
                    <input type="hidden" name="id" value="<?php echo $d['id_barang']; ?>">
                    
                    <div class="mb-3">
                        <label>Kode Barang</label>
                        <input type="text" name="kode" class="form-control bg-light" value="<?php echo $d['kode_barang']; ?>" readonly>
                    </div>
                    <div class="mb-3">
                        <label>Nama Barang</label>
                        <input type="text" name="nama" class="form-control" value="<?php echo $d['nama_barang']; ?>" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Harga Beli (Rp)</label>
                            <input type="text" name="beli" class="form-control" id="rupiah1" value="<?php echo number_format($d['harga_beli'], 0, ',', '.'); ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Harga Jual (Rp)</label>
                            <input type="text" name="jual" class="form-control" id="rupiah2" value="<?php echo number_format($d['harga_jual'], 0, ',', '.'); ?>" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Stok</label>
                            <input type="number" name="stok" class="form-control" value="<?php echo (int)$d['stok']; ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Satuan</label>
                            <input type="text" name="satuan" class="form-control" value="<?php echo $d['satuan']; ?>" required>
                        </div>
                    </div>
                    
                    <button type="submit" name="update" class="btn btn-primary">Update Data</button>
                    <a href="barang.php" class="btn btn-secondary">Batal</a>
                </form>

                <?php
                if(isset($_POST['update'])){
                    $id_brg = $_POST['id'];
                    $nama   = $_POST['nama'];
                    
                    // --- PEMBERSIHAN FORMAT RUPIAH ---
                    $beli = str_replace(".", "", $_POST['beli']);
                    $jual = str_replace(".", "", $_POST['jual']);
                    // ---------------------------------

                    $stok   = $_POST['stok'];
                    $satuan = $_POST['satuan'];

                    $update = mysqli_query($koneksi, "UPDATE barang SET nama_barang='$nama', harga_beli='$beli', harga_jual='$jual', stok='$stok', satuan='$satuan' WHERE id_barang='$id_brg'");

                    if($update){
                        echo "<script>alert('Data Berhasil Diupdate'); window.location='barang.php';</script>";
                    } else {
                        echo "<div class='alert alert-danger mt-3'>Gagal: ".mysqli_error($koneksi)."</div>";
                    }
                }
                ?>
            </div>
        </div>
    </div>
</div>

<script>
    function formatRupiah(angka){
        var number_string = angka.replace(/[^,\d]/g, '').toString(),
        split   		= number_string.split(','),
        sisa     		= split[0].length % 3,
        rupiah     		= split[0].substr(0, sisa),
        ribuan     		= split[0].substr(sisa).match(/\d{3}/gi);
    
        if(ribuan){
            separator = sisa ? '.' : '';
            rupiah += separator + ribuan.join('.');
        }
        return rupiah;
    }

    document.getElementById('rupiah1').addEventListener('keyup', function(){ this.value = formatRupiah(this.value); });
    document.getElementById('rupiah2').addEventListener('keyup', function(){ this.value = formatRupiah(this.value); });
</script>

<?php include 'footer.php'; ?>