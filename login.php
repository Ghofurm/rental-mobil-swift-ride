<?php
// login.php — Halaman Login Admin Swift Ride

session_start();

// Jika sudah login, langsung ke dashboard
if (!empty($_SESSION['admin_id'])) {
    header('Location: admin/index.php');
    exit;
}

// Definisikan BASE_URL sebelum memuat db.php
define('BASE_URL', './');

require_once 'config/db.php';
require_once 'includes/csrf.php';

$error   = '';
$timeout = isset($_GET['reason']) && $_GET['reason'] === 'timeout';

// ─── Proses Login (POST) ──────────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_verify();

    // Sanitasi input — hanya ambil value, validasi di sisi logika
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    // Validasi dasar format email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Format email tidak valid.';
    } elseif (strlen($password) < 8) {
        $error = 'Password minimal 8 karakter.';
    } else {
        // Cari admin berdasarkan email menggunakan prepared statement
        $stmt = $pdo->prepare("SELECT `id`, `name`, `password` FROM `admins` WHERE `email` = ? LIMIT 1");
        $stmt->execute([$email]);
        $admin = $stmt->fetch();

        // Verifikasi password menggunakan password_verify (timing-safe)
        if ($admin && password_verify($password, $admin['password'])) {
            // Login berhasil: regenerasi session ID untuk mencegah session fixation
            session_regenerate_id(true);
            $_SESSION['admin_id']   = $admin['id'];
            $_SESSION['admin_name'] = $admin['name'];
            $_SESSION['last_activity'] = time();

            header('Location: admin/index.php');
            exit;
        } else {
            // Keamanan: Pesan error generik agar tidak mengungkap apakah email terdaftar
            $error = 'Email atau password salah.';
            // Keamanan: JANGAN log kredensial — hanya log upaya gagal tanpa detail password
            error_log("Login attempt failed for email: " . substr($email, 0, 3) . "***");
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin — Swift Ride</title>
    <meta name="robots" content="noindex, nofollow">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            500: '#6366f1',
                            600: '#4f46e5',
                            700: '#4338ca',
                            dark: '#0b0f19',
                            card: '#131926',
                        }
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        display: ['Outfit', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <style>
        body { background-color: #0b0f19; color: #f8fafc; font-family: 'Inter', sans-serif; }
        .glassmorphism {
            background: rgba(19, 25, 38, 0.85);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255,255,255,0.07);
        }
        .input-field {
            background: rgba(255,255,255,0.04);
            border: 1px solid rgba(255,255,255,0.1);
            transition: border-color 0.2s, box-shadow 0.2s;
        }
        .input-field:focus {
            outline: none;
            border-color: #6366f1;
            box-shadow: 0 0 0 3px rgba(99,102,241,0.15);
        }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in-up { animation: fadeInUp 0.5s ease forwards; }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-6">

    <!-- Background decorative blobs -->
    <div class="fixed inset-0 overflow-hidden pointer-events-none" aria-hidden="true">
        <div class="absolute -top-40 -right-40 w-96 h-96 bg-indigo-600/10 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-40 -left-40 w-96 h-96 bg-brand-500/10 rounded-full blur-3xl"></div>
    </div>

    <div class="w-full max-w-md animate-fade-in-up">
        <!-- Card Login -->
        <div class="glassmorphism rounded-2xl p-8 shadow-2xl">

            <!-- Header -->
            <div class="text-center mb-8">
                <a href="index.php" class="inline-flex items-center space-x-2 font-display text-2xl font-bold tracking-tight mb-6">
                    <span class="bg-gradient-to-r from-brand-500 to-indigo-400 bg-clip-text text-transparent">Swift</span>
                    <span class="text-white">Ride</span>
                </a>
                <h1 class="text-xl font-semibold text-white">Portal Admin</h1>
                <p class="text-sm text-slate-400 mt-1">Masuk untuk mengelola armada kendaraan</p>
            </div>

            <!-- Alert timeout -->
            <?php if ($timeout): ?>
            <div class="mb-6 flex items-center gap-3 bg-amber-500/10 border border-amber-500/30 rounded-xl px-4 py-3 text-sm text-amber-400">
                <svg class="w-4 h-4 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                Sesi Anda habis karena tidak aktif. Silakan login kembali.
            </div>
            <?php endif; ?>

            <!-- Alert error -->
            <?php if ($error): ?>
            <div id="login-error" class="mb-6 flex items-center gap-3 bg-red-500/10 border border-red-500/30 rounded-xl px-4 py-3 text-sm text-red-400">
                <svg class="w-4 h-4 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
                <?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?>
            </div>
            <?php endif; ?>

            <!-- Form Login -->
            <form id="login-form" method="POST" action="login.php" novalidate>
                <?php echo csrf_field(); ?>

                <!-- Email -->
                <div class="mb-5">
                    <label for="login-email" class="block text-sm font-medium text-slate-300 mb-2">Email Admin</label>
                    <input
                        type="email"
                        id="login-email"
                        name="email"
                        class="input-field w-full rounded-xl px-4 py-3 text-white placeholder-slate-500 text-sm"
                        placeholder="admin@swiftride.id"
                        value="<?php echo htmlspecialchars($_POST['email'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                        required
                        autocomplete="username"
                    >
                </div>

                <!-- Password -->
                <div class="mb-6">
                    <label for="login-password" class="block text-sm font-medium text-slate-300 mb-2">Password</label>
                    <div class="relative">
                        <input
                            type="password"
                            id="login-password"
                            name="password"
                            class="input-field w-full rounded-xl px-4 py-3 pr-12 text-white placeholder-slate-500 text-sm"
                            placeholder="••••••••"
                            required
                            autocomplete="current-password"
                            minlength="8"
                        >
                        <button type="button" id="toggle-password" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-500 hover:text-slate-300 transition-colors" aria-label="Tampilkan password">
                            <svg id="eye-icon" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Submit -->
                <button
                    type="submit"
                    id="login-submit"
                    class="w-full py-3 rounded-xl font-semibold text-sm text-white bg-gradient-to-r from-brand-600 to-indigo-500 hover:from-indigo-500 hover:to-brand-600 transition-all duration-300 shadow-lg shadow-indigo-500/20 active:scale-[0.98]"
                >
                    Masuk ke Dashboard
                </button>
            </form>

            <p class="text-center text-xs text-slate-600 mt-6">
                <a href="index.php" class="hover:text-slate-400 transition-colors">← Kembali ke Halaman Utama</a>
            </p>
        </div>
    </div>

    <script>
        // Toggle show/hide password
        const toggleBtn = document.getElementById('toggle-password');
        const passwordInput = document.getElementById('login-password');
        toggleBtn.addEventListener('click', () => {
            const isPassword = passwordInput.type === 'password';
            passwordInput.type = isPassword ? 'text' : 'password';
        });
    </script>
</body>
</html>
