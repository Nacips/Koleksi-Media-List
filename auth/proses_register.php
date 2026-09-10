<?php
session_start();
include '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email    = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    // 1. Cek apakah email sudah pernah terdaftar di database
    $check_email = mysqli_query($conn, "SELECT email FROM user WHERE email = '$email'");
    
    if (mysqli_num_rows($check_email) > 0) {
        // Jika email sudah ada
        header("Location: register.php?error=Email sudah terdaftar!");
        exit();
    }

    // 2. Hash password agar aman di database
    $password_hashed = password_hash($password, PASSWORD_DEFAULT);

    // 3. Simpan user baru ke database
    $query = "INSERT INTO user (email, password) VALUES ('$email', '$password_hashed')";

    if (mysqli_query($conn, $query)) {
        // Jika berhasil, langsung arahkan ke halaman login
        header("Location: login.php?success=Registrasi berhasil! Silakan login.");
        exit();
    } else {
        header("Location: register.php?error=Gagal mendaftar, coba lagi.");
        exit();
    }
}
?>