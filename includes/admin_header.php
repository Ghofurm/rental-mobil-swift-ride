<?php
// includes/admin_header.php — Layout header untuk panel admin
// $pageTitle harus di-set sebelum meng-include file ini

$pageTitle = $pageTitle ?? 'Dashboard';
$adminName = $_SESSION['admin_name'] ?? 'Admin';
$current_admin_page = basename($_SERVER['PHP_SELF']);

function admin_active($page, $current) {
    return $page === $current
        ? 'bg-brand-500/10 text-brand-500 border-l-2 border-brand-500'
        : 'text-slate-400 hover:bg-slate-800/50 hover:text-white border-l-2 border-transparent';
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8'); ?> — Admin Swift Ride</title>
    <meta name="robots" content="noindex, nofollow">

    <!-- Security Headers via meta (supplemental, real headers set in .htaccess) -->
    <meta http-equiv="X-Content-Type-Options" content="nosniff">
    <meta http-equiv="X-Frame-Options" content="DENY">

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
                            500: '#6366f1', 600: '#4f46e5', 700: '#4338ca',
                            dark: '#0b0f19', card: '#131926',
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
        .sidebar { background: #0d1220; border-right: 1px solid rgba(255,255,255,0.05); }
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: #0b0f19; }
        ::-webkit-scrollbar-thumb { background: #1e293b; border-radius: 3px; }
        ::-webkit-scrollbar-thumb:hover { background: #6366f1; }
        .input-field {
            background: rgba(255,255,255,0.04);
            border: 1px solid rgba(255,255,255,0.1);
            transition: border-color 0.2s, box-shadow 0.2s;
        }
        .input-field option {
            background-color: #131926;
            color: #ffffff;
        }
        .input-field:focus {
            outline: none;
            border-color: #6366f1;
            box-shadow: 0 0 0 3px rgba(99,102,241,0.15);
        }
        @keyframes fadeIn { from { opacity:0; transform:translateY(8px); } to { opacity:1; transform:none; } }
        .animate-fade-in { animation: fadeIn 0.3s ease forwards; }
    </style>
</head>
<body class="antialiased min-h-screen flex">

    <!-- ── Sidebar ─────────────────────────────────────── -->
    <aside class="sidebar w-64 shrink-0 min-h-screen flex flex-col fixed top-0 left-0 z-30 hidden md:flex">
        <!-- Logo -->
        <div class="h-16 flex items-center px-6 border-b border-slate-800/50">
            <a href="<?php echo BASE_URL; ?>index.php" class="flex items-center space-x-2 font-display text-xl font-bold">
                <span class="bg-gradient-to-r from-brand-500 to-indigo-400 bg-clip-text text-transparent">Swift</span>
                <span class="text-white">Ride</span>
            </a>
            <span class="ml-2 text-xs font-semibold text-brand-500 bg-brand-500/10 px-2 py-0.5 rounded-full">Admin</span>
        </div>

        <!-- Navigation -->
        <nav class="flex-1 px-3 py-6 space-y-1 overflow-y-auto">
            <p class="text-xs font-semibold text-slate-600 uppercase tracking-wider px-3 mb-3">Menu Utama</p>

            <a href="<?php echo BASE_URL; ?>admin/index.php" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all duration-200 <?php echo admin_active('index.php', $current_admin_page); ?>">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                Dashboard
            </a>

            <a href="<?php echo BASE_URL; ?>admin/cars/create.php" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all duration-200 <?php echo admin_active('create.php', $current_admin_page); ?>">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                </svg>
                Tambah Mobil
            </a>

            <div class="pt-6 mt-6 border-t border-slate-800/50">
                <p class="text-xs font-semibold text-slate-600 uppercase tracking-wider px-3 mb-3">Lainnya</p>
                <a href="<?php echo BASE_URL; ?>index.php" target="_blank" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-slate-400 hover:bg-slate-800/50 hover:text-white border-l-2 border-transparent transition-all duration-200">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                    </svg>
                    Lihat Website
                </a>
            </div>
        </nav>

        <!-- User info + Logout -->
        <div class="p-4 border-t border-slate-800/50">
            <div class="flex items-center gap-3 mb-3 px-2">
                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-brand-500 to-indigo-600 flex items-center justify-center text-white font-bold text-sm">
                    <?php echo htmlspecialchars(strtoupper(substr($adminName, 0, 1)), ENT_QUOTES, 'UTF-8'); ?>
                </div>
                <div class="min-w-0">
                    <p class="text-sm font-medium text-white truncate"><?php echo htmlspecialchars($adminName, ENT_QUOTES, 'UTF-8'); ?></p>
                    <p class="text-xs text-slate-500">Administrator</p>
                </div>
            </div>
            <form method="POST" action="<?php echo BASE_URL; ?>logout.php">
                <?php echo csrf_field(); ?>
                <button type="submit" class="w-full flex items-center gap-2 px-3 py-2 rounded-lg text-sm font-medium text-red-400 hover:bg-red-500/10 hover:text-red-300 transition-all duration-200">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                    Keluar
                </button>
            </form>
        </div>
    </aside>

    <!-- ── Main Content Area ────────────────────────────── -->
    <div class="flex-1 md:ml-64 flex flex-col min-h-screen">
        <!-- Top Bar -->
        <header class="h-16 flex items-center justify-between px-6 border-b border-slate-800/50 bg-[#0b0f19]/80 backdrop-blur sticky top-0 z-20">
            <div>
                <h1 class="text-lg font-semibold text-white"><?php echo htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8'); ?></h1>
            </div>
            <div class="flex items-center gap-3 text-sm text-slate-400">
                <span>Halo, <?php echo htmlspecialchars($adminName, ENT_QUOTES, 'UTF-8'); ?>!</span>
            </div>
        </header>

        <!-- Flash Messages -->
        <div class="px-6 pt-4">
        <?php if (!empty($_SESSION['flash_success'])): ?>
            <div class="flex items-center gap-3 bg-emerald-500/10 border border-emerald-500/30 rounded-xl px-4 py-3 text-sm text-emerald-400 mb-4 animate-fade-in flash-message">
                <svg class="w-4 h-4 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                <?php echo htmlspecialchars($_SESSION['flash_success'], ENT_QUOTES, 'UTF-8'); unset($_SESSION['flash_success']); ?>
            </div>
        <?php endif; ?>
        <?php if (!empty($_SESSION['flash_error'])): ?>
            <div class="flex items-center gap-3 bg-red-500/10 border border-red-500/30 rounded-xl px-4 py-3 text-sm text-red-400 mb-4 animate-fade-in flash-message">
                <svg class="w-4 h-4 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
                <?php echo htmlspecialchars($_SESSION['flash_error'], ENT_QUOTES, 'UTF-8'); unset($_SESSION['flash_error']); ?>
            </div>
        <?php endif; ?>
        </div>

        <!-- Page Content -->
        <main class="flex-1 px-6 pb-10">
