<!DOCTYPE html>
<html lang="id">
<head>
    <title>Login Inventory Motor</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-secondary d-flex align-items-center justify-content-center" style="height: 100vh;">

    <div class="card shadow p-4" style="width: 400px;">
        <div class="text-center mb-4">
            <h4>Sistem Inventory</h4>
            <span class="text-muted">Sparepart Motor</span>
        </div>
        
        <?php if(isset($_GET['pesan'])) { ?>
            <div class="alert alert-danger text-center py-2" style="font-size: 14px;">
                <?php echo $_GET['pesan']; ?>
            </div>
        <?php } ?>
        
        <form action="cek_login.php" method="POST">
            <div class="mb-3">
                <label class="form-label">Username</label>
                <input type="text" name="username" class="form-control" placeholder="admin / kasir" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" placeholder="Masukan password" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">LOGIN</button>
        </form>
        
        <div class="text-center mt-3 border-top pt-3">
            <small class="text-muted d-block">Akun Demo:</small>
            <small class="text-primary"><b>admin</b> (Pass: 123)</small> | 
            <small class="text-success"><b>kasir</b> (Pass: 123)</small>
        </div>
    </div>

</body>
</html>