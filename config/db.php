<?php
// config/db.php

// Keamanan: Mencegah akses langsung ke file konfigurasi
if (basename($_SERVER['PHP_SELF']) === basename(__FILE__)) {
    header("HTTP/1.1 403 Forbidden");
    exit("Akses ditolak.");
}

// 🛡️ Loader .env Sederhana & Aman (Tanpa dependency luar)
$envPath = dirname(__DIR__) . '/.env';
if (file_exists($envPath)) {
    $lines = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        // Abaikan baris kosong atau komentar
        if (strpos(trim($line), '#') === 0) {
            continue;
        }
        // Pisahkan key dan value berdasarkan tanda '=' pertama
        $parts = explode('=', $line, 2);
        if (count($parts) === 2) {
            $key = trim($parts[0]);
            $value = trim($parts[1]);
            // Bersihkan tanda petik pembungkus jika ada
            $value = trim($value, '"\'');
            
            // Set ke environment variabel
            putenv("{$key}={$value}");
            $_ENV[$key] = $value;
            $_SERVER[$key] = $value;
        }
    }
}

// Ambil kredensial dari environment variable (menggunakan fallback aman untuk lokal)
$host     = getenv('DB_HOST') ?: '127.0.0.1';
$dbname   = getenv('DB_NAME') ?: 'swift_ride';
$username = getenv('DB_USER') ?: 'root';
$password = getenv('DB_PASS') ?: ''; // Kredensial asli tersimpan aman di berkas .env Anda

try {
    $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];
    $pdo = new PDO($dsn, $username, $password, $options);
} catch (PDOException $e) {
    // Keamanan: Catat detail kesalahan ke log server secara rahasia
    error_log("Database connection failure: " . $e->getMessage());
    
    // Matikan proses dengan status 500
    http_response_code(500);
    die("Maaf, terjadi masalah koneksi ke server. Silakan coba kembali nanti.");
}
