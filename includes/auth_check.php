<?php
// includes/auth_check.php — Middleware autentikasi admin
// Sertakan file ini di setiap halaman admin yang memerlukan login

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Jika tidak ada sesi admin yang valid, redirect ke halaman login
if (empty($_SESSION['admin_id'])) {
    header('Location: ' . BASE_URL . 'login.php');
    exit;
}

// Perbarui waktu aktivitas terakhir dan cek timeout sesi (30 menit)
$timeout = 1800; // 30 menit
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > $timeout) {
    // Hancurkan sesi yang sudah expired
    session_unset();
    session_destroy();
    header('Location: ' . BASE_URL . 'login.php?reason=timeout');
    exit;
}
$_SESSION['last_activity'] = time();
