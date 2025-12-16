<?php include 'header.php'; include 'koneksi.php'; $id = $_GET['id'];$data = mysqli_query($koneksi, "SELECT * FROM supplier WHERE id_supplier='$id'");$d = mysqli_fetch_array($data);?>

<div class="row">
    <div class="col-md-6 mx-auto">
        <div class="card shadow">
            <div class="card-header bg-warning">
                <h5 class="mb-0">Edit Supplier</h5>
            </div>
            <div class="card-body">
                <form method="POST">
                    <input type="hidden" name="id" value="<?php echo $d['id_supplier']; ?>">
                    
                    <div class="mb-3">
                        <label>Nama Supplier</label>
                        <input type="text" name="nama" class="form-control" value="<?php echo $d['nama_supplier']; ?>" required>
                    </div>
                    
                    <div class="mb-3">
                        <label>No. Telepon</label>
                        <input type="text" name="telp" id="no_telepon" class="form-control" value="<?php echo $d['no_telp']; ?>" maxlength="16" required>
                    </div>

                    <div class="mb-3">
                        <label>Alamat</label>
                        <textarea name="alamat" class="form-control" rows="3" required><?php echo $d['alamat']; ?></textarea>
                    </div>
                    
                    <button type="submit" name="update" class="btn btn-primary">Update Data</button>
                    <a href="supplier.php" class="btn btn-secondary">Batal</a>
                </form>

                <?php
                if(isset($_POST['update'])){
                    $id_sup = $_POST['id'];
                    $nama = $_POST['nama'];
                    // Bersihkan strip sebelum update ke database
                    $telp = str_replace('-', '', $_POST['telp']);
                    $alamat = $_POST['alamat'];

                    $update = mysqli_query($koneksi, "UPDATE supplier SET nama_supplier='$nama', no_telp='$telp', alamat='$alamat' WHERE id_supplier='$id_sup'");

                    if($update){
                        echo "<script>alert('Data Berhasil Diupdate'); window.location='supplier.php';</script>";
                    } else {
                        echo "<div class='alert alert-danger mt-3'>Gagal Update: ".mysqli_error($koneksi)."</div>";
                    }
                }
                ?>
            </div>
        </div>
    </div>
</div>

<script>
    const inputTelEdit = document.getElementById('no_telepon');
    
    // Fungsi format (dipisah biar bisa dipanggil saat load juga)
    function formatTelepon(input) {
        let value = input.value.replace(/\D/g, '');
        value = value.replace(/(\d{4})(?=\d)/g, '$1-');
        input.value = value;
    }

    // Jalankan saat mengetik
    inputTelEdit.addEventListener('input', function (e) {
        formatTelepon(e.target);
    });

    // Jalankan sekali saat halaman terbuka (agar data lama dari DB langsung terformat)
    formatTelepon(inputTelEdit);
</script>

<?php include 'footer.php'; ?>