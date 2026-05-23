<?php
// includes/csrf.php — CSRF Token Helper
// Sertakan di bagian atas halaman yang memiliki form state-changing

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Buat CSRF token baru dan simpan di session.
 * Jika sudah ada, gunakan kembali token yang ada.
 */
function csrf_token(): string {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Render hidden input field CSRF untuk digunakan dalam form.
 */
function csrf_field(): string {
    return '<input type="hidden" name="csrf_token" value="' . htmlspecialchars(csrf_token(), ENT_QUOTES, 'UTF-8') . '">';
}

/**
 * Validasi CSRF token dari request POST.
 * Jika tidak valid, hentikan eksekusi.
 */
function csrf_verify(): void {
    $token = $_POST['csrf_token'] ?? '';
    if (!hash_equals(csrf_token(), $token)) {
        http_response_code(403);
        // Keamanan: Jangan tampilkan detail teknis ke pengguna
        die('Permintaan tidak valid (CSRF token salah). Silakan kembali dan coba lagi.');
    }
}
