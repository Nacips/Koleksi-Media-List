<?php
session_start();

// Jika sudah login, lempar ke dashboard
if (isset($_SESSION['login'])) {
    header("Location: ../dashboard/index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun Baru</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body class="login-body">

<div class="login-box">
    <h4 class="login-title">Daftar Akun Baru</h4>

    <?php if (isset($_GET['error'])) : ?>
        <div class="alert alert-danger py-2 small text-center">
            <?= htmlspecialchars($_GET['error']); ?>
        </div>
    <?php endif; ?>

    <form action="proses_register.php" method="POST">
        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" placeholder="nama@email.com" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Password</label>
            <input type="password" name="password" class="form-control" placeholder="••••••••" required>
        </div>

        <button type="submit" class="btn-login mt-2">DAFTAR AKUN</button>
    </form>

    <div class="text-center mt-3">
        <small class="text-muted">Sudah punya akun? <a href="login.php" class="fw-bold text-danger">Login di sini</a></small>
    </div>
</div>

</body>
</html>