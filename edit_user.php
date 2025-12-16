<?php include 'header.php'; include 'koneksi.php'; 
$id = $_GET['id'];
// PERBAIKAN: Select dari 'users'
$data = mysqli_query($koneksi, "SELECT * FROM users WHERE id_user='$id'");
$d = mysqli_fetch_array($data);
?>

<div class="row">
    <div class="col-md-6 mx-auto">
        <div class="card shadow">
            <div class="card-header bg-warning">
                <h5 class="mb-0">Edit User</h5>
            </div>
            <div class="card-body">
                <form method="POST">
                    <input type="hidden" name="id" value="<?php echo $d['id_user']; ?>">
                    
                    <div class="mb-3">
                        <label>Nama Lengkap</label>
                        <input type="text" name="nama" class="form-control" value="<?php echo $d['nama_lengkap']; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label>Username</label>
                        <input type="text" name="username" class="form-control" value="<?php echo $d['username']; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label>Password Baru <small class="text-muted">(Kosongkan jika tidak ingin mengganti)</small></label>
                        <input type="password" name="password" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label>Role / Akses</label>
                        <select name="role" class="form-control" required>
                            <option value="kasir" <?php if($d['role']=='kasir') echo 'selected'; ?>>Kasir</option>
                            <option value="admin" <?php if($d['role']=='admin') echo 'selected'; ?>>Admin</option>
                            <option value="owner" <?php if($d['role']=='owner') echo 'selected'; ?>>Owner</option>
                        </select>
                    </div>
                    
                    <button type="submit" name="update" class="btn btn-primary">Update User</button>
                    <a href="user.php" class="btn btn-secondary">Batal</a>
                </form>

                <?php
                if(isset($_POST['update'])){
                    $id_user = $_POST['id'];
                    $nama = $_POST['nama'];
                    $user = $_POST['username'];
                    $role = $_POST['role'];
                    $password_baru = $_POST['password'];

                    // PERBAIKAN: Update ke tabel 'users' set 'nama_lengkap'
                    if($password_baru == "") {
                        $query = "UPDATE users SET nama_lengkap='$nama', username='$user', role='$role' WHERE id_user='$id_user'";
                    } else {
                        $pass_enc = md5($password_baru);
                        $query = "UPDATE users SET nama_lengkap='$nama', username='$user', password='$pass_enc', role='$role' WHERE id_user='$id_user'";
                    }

                    $update = mysqli_query($koneksi, $query);

                    if($update){
                        echo "<script>alert('Data User Berhasil Diupdate'); window.location='user.php';</script>";
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