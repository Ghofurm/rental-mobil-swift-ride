<?php
// config/db.php — Koneksi & Inisialisasi Otomatis Database

// Keamanan: Cegah akses langsung ke file ini
if (basename($_SERVER['PHP_SELF']) === basename(__FILE__)) {
    header("HTTP/1.1 403 Forbidden");
    exit("Akses ditolak.");
}

// Fallback untuk mb_strlen jika extension mbstring tidak diaktifkan
if (!function_exists('mb_strlen')) {
    function mb_strlen($string, $encoding = null) {
        return strlen($string);
    }
}

// ─── Loader .env Sederhana ─────────────────────────────────────────────────
$envPath = dirname(__DIR__) . '/.env';
if (file_exists($envPath)) {
    $lines = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) continue;
        $parts = explode('=', $line, 2);
        if (count($parts) === 2) {
            $key   = trim($parts[0]);
            $value = trim(trim($parts[1]), "\"'");
            putenv("{$key}={$value}");
            $_ENV[$key]    = $value;
            $_SERVER[$key] = $value;
        }
    }
}

// ─── Baca Kredensial dari Environment ─────────────────────────────────────
$host     = getenv('DB_HOST') ?: '127.0.0.1';
$dbname   = getenv('DB_NAME') ?: 'swift_ride';
$username = getenv('DB_USER') ?: 'root';
$password = getenv('DB_PASS') ?: '';

// ─── Opsi PDO Global ───────────────────────────────────────────────────────
$pdoOptions = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

// ─── Step 1: Koneksi tanpa nama database, lalu buat jika belum ada ─────────
try {
    if (!extension_loaded('pdo_mysql')) {
        throw new PDOException("Extension 'pdo_mysql' tidak ditemukan di server Anda. Silakan aktifkan di php.ini.");
    }
    $pdo = new PDO("mysql:host={$host};charset=utf8mb4", $username, $password, $pdoOptions);
    // Buat database jika belum ada
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `{$dbname}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    // Pilih database
    $pdo->exec("USE `{$dbname}`");
} catch (PDOException $e) {
    error_log("DB init failure: " . $e->getMessage());
    http_response_code(500);
    die("Maaf, terjadi masalah koneksi ke server. Silakan coba kembali nanti.");
}

// ─── Step 2: Buat Tabel yang Diperlukan ────────────────────────────────────
try {
    // Tabel armada mobil
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS `cars` (
            `id`           INT AUTO_INCREMENT PRIMARY KEY,
            `brand`        VARCHAR(50)   NOT NULL,
            `model`        VARCHAR(50)   NOT NULL,
            `type`         VARCHAR(30)   NOT NULL,
            `daily_rate`   DECIMAL(12,2) NOT NULL,
            `image`        VARCHAR(512)  NOT NULL DEFAULT '',
            `status`       ENUM('available','rented','maintenance') NOT NULL DEFAULT 'available',
            `transmission` VARCHAR(20)   NOT NULL DEFAULT 'Automatic',
            `fuel`         VARCHAR(20)   NOT NULL DEFAULT 'Petrol',
            `seats`        TINYINT       NOT NULL DEFAULT 5,
            `description`  TEXT,
            `created_at`   TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            `updated_at`   TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ");

    // Tabel admin
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS `admins` (
            `id`         INT AUTO_INCREMENT PRIMARY KEY,
            `name`       VARCHAR(100) NOT NULL,
            `email`      VARCHAR(150) NOT NULL UNIQUE,
            `password`   VARCHAR(255) NOT NULL,
            `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ");

    // ─── Step 3: Buat Admin Default jika tabel admins kosong ─────────────
    $count = $pdo->query("SELECT COUNT(*) FROM `admins`")->fetchColumn();
    if ((int)$count === 0) {
        // TODO(security): Ganti password default ini segera melalui panel admin
        //                 setelah pertama kali login. Gunakan password yang kuat.
        $defaultEmail    = 'admin@swiftride.id';
        $defaultPassword = password_hash('password123', PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO `admins` (`name`, `email`, `password`) VALUES (?, ?, ?)");
        $stmt->execute(['Super Admin', $defaultEmail, $defaultPassword]);
    }

} catch (PDOException $e) {
    error_log("DB schema init failure: " . $e->getMessage());
    http_response_code(500);
    die("Maaf, gagal menginisialisasi skema database. Periksa log server.");
}
// $pdo sekarang siap digunakan di seluruh aplikasi
