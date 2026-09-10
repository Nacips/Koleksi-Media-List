<?php
session_start();

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
    <title>Login - Hobby List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body class="login-body">

<div class="login-box">
    <h4 class="login-title">
        🌸 Anime, Manhwa, Manhua, Manga List
    </h4>

    <?php if (isset($_GET['success'])) : ?>
        <div class="alert alert-success py-2 small text-center rounded-3">
            <?= htmlspecialchars($_GET['success']); ?>
        </div>
    <?php endif; ?>

    <?php if (isset($_GET['error'])) : ?>
        <div class="alert alert-danger py-2 small text-center rounded-3">
            Email atau password salah!
        </div>
    <?php endif; ?>

    <form action="proses_login.php" method="POST">
        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" placeholder="nama@email.com" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Password</label>
            <input type="password" name="password" class="form-control" placeholder="••••••••" required>
        </div>

        <button type="submit" class="btn-login mt-2">
            LOGIN
        </button>
    </form>

    <div class="text-center mt-3">
        <small class="text-muted">Belum punya akun? <a href="register.php" class="fw-bold text-danger text-decoration-none">Daftar di sini</a></small>
    </div>
</div>

</body>
</html>