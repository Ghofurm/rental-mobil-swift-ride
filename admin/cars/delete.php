<?php
// admin/cars/delete.php — Handler Hapus Kendaraan (POST only)

session_start();

define('BASE_URL', '../../');
require_once '../../config/db.php';
require_once '../../includes/csrf.php';
require_once '../../includes/auth_check.php';

// Tolak semua metode selain POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    header('Allow: POST');
    exit('Method Not Allowed');
}

// Validasi CSRF
csrf_verify();

// Validasi ID
$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
if (!$id || $id < 1) {
    $_SESSION['flash_error'] = 'ID kendaraan tidak valid.';
    header('Location: ../index.php');
    exit;
}

// Pastikan kendaraan memang ada sebelum dihapus
$stmt = $pdo->prepare("SELECT brand, model FROM cars WHERE id = ? LIMIT 1");
$stmt->execute([$id]);
$car = $stmt->fetch();

if (!$car) {
    $_SESSION['flash_error'] = 'Kendaraan tidak ditemukan atau sudah dihapus.';
    header('Location: ../index.php');
    exit;
}

// Lakukan penghapusan
$delStmt = $pdo->prepare("DELETE FROM cars WHERE id = ?");
$delStmt->execute([$id]);

$_SESSION['flash_success'] = htmlspecialchars($car['brand'] . ' ' . $car['model'], ENT_QUOTES, 'UTF-8') . ' berhasil dihapus.';
header('Location: ../index.php');
exit;
