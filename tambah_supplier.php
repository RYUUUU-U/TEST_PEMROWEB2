<?php include 'header.php'; include 'koneksi.php'; ?>

<div class="row">
    <div class="col-md-6 mx-auto">
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Tambah Supplier</h5>
            </div>
            <div class="card-body">
                <form method="POST">
                    <div class="mb-3">
                        <label>Nama Supplier</label>
                        <input type="text" name="nama" class="form-control" required>
                    </div>
                    
                    <div class="mb-3">
                        <label>No. Telepon</label>
                        <input type="text" name="telp" id="no_telepon" class="form-control" placeholder="Contoh: 0812-xxxx-xxxx" maxlength="16" required>
                        <small class="text-muted" style="font-size: 11px;">*Format otomatis (strip akan muncul sendiri)</small>
                    </div>

                    <div class="mb-3">
                        <label>Alamat</label>
                        <textarea name="alamat" class="form-control" rows="3" required></textarea>
                    </div>
                    
                    <button type="submit" name="simpan" class="btn btn-success">Simpan Data</button>
                    <a href="supplier.php" class="btn btn-secondary">Kembali</a>
                </form>

                <?php
                if(isset($_POST['simpan'])){
                    $nama = $_POST['nama'];
                    // Kita hapus strip (-) sebelum simpan ke database agar data bersih (opsional, tapi disarankan)
                    $telp = str_replace('-', '', $_POST['telp']); 
                    $alamat = $_POST['alamat'];

                    // Simpan data (pastikan variabel $telp yang dipakai adalah yang sudah dibersihkan)
                    $simpan = mysqli_query($koneksi, "INSERT INTO supplier VALUES (NULL, '$nama', '$telp', '$alamat')");

                    if($simpan){
                        echo "<script>alert('Data Berhasil Disimpan'); window.location='supplier.php';</script>";
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
    const inputTel = document.getElementById('no_telepon');
    inputTel.addEventListener('input', function (e) {
        let value = e.target.value.replace(/\D/g, ''); // Hapus selain angka
        value = value.replace(/(\d{4})(?=\d)/g, '$1-'); // Tambah strip per 4 angka
        e.target.value = value;
    });
</script>

<?php include 'footer.php'; ?>