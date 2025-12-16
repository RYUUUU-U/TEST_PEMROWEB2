<?php include 'header.php'; include 'koneksi.php'; ?>

<div class="row">
    <div class="col-md-6 mx-auto">
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Tambah User Baru</h5>
            </div>
            <div class="card-body">
                <form method="POST">
                    <div class="mb-3">
                        <label>Nama Lengkap</label>
                        <input type="text" name="nama" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Username</label>
                        <input type="text" name="username" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Role / Akses</label>
                        <select name="role" class="form-control" required>
                            <option value="kasir">Kasir</option>
                            <option value="admin">Admin</option>
                            <option value="owner">Owner</option>
                        </select>
                    </div>
                    
                    <button type="submit" name="simpan" class="btn btn-success">Simpan User</button>
                    <a href="user.php" class="btn btn-secondary">Kembali</a>
                </form>

                <?php
                if(isset($_POST['simpan'])){
                    $nama = $_POST['nama'];
                    $user = $_POST['username'];
                    $pass = md5($_POST['password']); 
                    $role = $_POST['role'];

                    // PERBAIKAN: Insert ke tabel 'users' kolom 'nama_lengkap'
                    $simpan = mysqli_query($koneksi, "INSERT INTO users (nama_lengkap, username, password, role) VALUES ('$nama', '$user', '$pass', '$role')");

                    if($simpan){
                        echo "<script>alert('User Berhasil Ditambahkan'); window.location='user.php';</script>";
                    } else {
                        echo "<div class='alert alert-danger mt-3'>Gagal: ".mysqli_error($koneksi)."</div>";
                    }
                }
                ?>
            </div>
        </div>
    </div>
</div>
<?php include 'footer.php'; ?>