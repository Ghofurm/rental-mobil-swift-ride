<?php
// logout.php — Hancurkan sesi admin dan redirect ke login

session_start();

require_once 'includes/csrf.php';

// Validasi CSRF untuk permintaan logout (mencegah logout paksa oleh pihak lain)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_verify();
}

// Hapus semua data sesi
$_SESSION = [];

// Hapus cookie sesi dari browser pengguna
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(), '',
        time() - 42000,
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]
    );
}

// Hancurkan sesi server-side
session_destroy();

// Redirect ke halaman login setelah logout
header('Location: login.php');
exit;
