<?php include 'header.php'; include 'koneksi.php'; ?>

<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Tambah Barang (Kode Otomatis)</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="">
                    <div class="alert alert-info py-2">
                        <small><i class="fa fa-info-circle"></i> Kode Barang akan dibuat otomatis dari Nama Barang.</small>
                    </div>

                    <div class="mb-3">
                        <label>Nama Barang</label>
                        <input type="text" name="nama" class="form-control" placeholder="Contoh: Oli Mesin, Kampas Rem" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Harga Beli (Rp)</label>
                            <input type="text" name="beli" class="form-control" id="rupiah1" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Harga Jual (Rp)</label>
                            <input type="text" name="jual" class="form-control" id="rupiah2" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Stok Awal</label>
                            <input type="number" name="stok" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Satuan</label>
                            <input type="text" name="satuan" class="form-control" placeholder="Pcs/Box/Unit" required>
                        </div>
                    </div>
                    
                    <button type="submit" name="simpan" class="btn btn-success">Simpan Data</button>
                    <a href="barang.php" class="btn btn-secondary">Kembali</a>
                </form>

                <?php
                if(isset($_POST['simpan'])){
                    $nama = $_POST['nama'];
                    
                    // --- PEMBERSIHAN FORMAT RUPIAH ---
                    // Hapus titik sebelum simpan ke database
                    $beli = str_replace(".", "", $_POST['beli']);
                    $jual = str_replace(".", "", $_POST['jual']);
                    // ---------------------------------

                    $stok = $_POST['stok'];
                    $satuan = $_POST['satuan'];

                    // --- LOGIKA KODE OTOMATIS ---
                    $prefix = strtoupper(substr($nama, 0, 3)); 
                    $query_cek = mysqli_query($koneksi, "SELECT kode_barang FROM barang WHERE kode_barang LIKE '$prefix-%' ORDER BY id_barang DESC LIMIT 1");
                    
                    if(mysqli_num_rows($query_cek) > 0){
                        $data_terakhir = mysqli_fetch_assoc($query_cek);
                        $pecah = explode("-", $data_terakhir['kode_barang']);
                        $angka = intval($pecah[1]) + 1;
                        $kode_final = $prefix . "-" . str_pad($angka, 3, "0", STR_PAD_LEFT); 
                    } else {
                        $kode_final = $prefix . "-001";
                    }
                    // --- AKHIR LOGIKA ---

                    $simpan = mysqli_query($koneksi, "INSERT INTO barang VALUES (NULL, '$kode_final', '$nama', 'Umum', '$beli', '$jual', '$stok', '$satuan')");

                    if($simpan){
                        echo "<script>alert('Berhasil! Kode: $kode_final'); window.location='barang.php';</script>";
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