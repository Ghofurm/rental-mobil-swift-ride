<?php
// about.php

// Aktifkan pelaporan error internal
ini_set('display_errors', 0);
ini_set('log_errors', 1);
error_reporting(E_ALL);

// Sertakan layout Header
require_once __DIR__ . '/includes/header.php';
?>

<!-- Hero Section About -->
<section class="pt-24 pb-16 relative overflow-hidden">
    <div class="absolute top-1/4 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[500px] h-[500px] bg-brand-500/10 rounded-full blur-[120px] pointer-events-none"></div>
    <div class="max-w-7xl mx-auto px-6 relative z-10 text-center space-y-6">
        <span class="text-xs font-semibold text-brand-500 tracking-widest uppercase">Tentang Kami</span>
        <h1 class="text-4xl sm:text-5xl font-display font-bold text-white">Menemani Perjalanan Berkelas Anda</h1>
        <p class="text-sm sm:text-base text-slate-400 max-w-2xl mx-auto leading-relaxed">
            Swift Ride didirikan dengan visi untuk mendefinisikan ulang kemudahan rental mobil premium di Indonesia melalui sentuhan teknologi modern dan transparansi total.
        </p>
    </div>
</section>

<!-- Vision & Mission Section -->
<section class="py-16 bg-brand-dark/20 border-t border-slate-900/60">
    <div class="max-w-7xl mx-auto px-6 grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
        <!-- Text content -->
        <div class="space-y-6">
            <h2 class="text-3xl font-display font-bold text-white">Komitmen Pelayanan Premium</h2>
            <p class="text-slate-400 leading-relaxed text-sm">
                Kami percaya bahwa menyewa kendaraan tidak boleh menjadi proses yang berbelit-belit. Oleh karena itu, Swift Ride menghapus kerumitan administrasi tradisional dan menggantinya dengan verifikasi digital yang instan, aman, dan dapat diandalkan.
            </p>
            <div class="space-y-4">
                <div class="flex items-start space-x-3">
                    <span class="text-brand-500 font-bold mt-1">✔</span>
                    <div>
                        <h4 class="font-bold text-white text-sm">Visi</h4>
                        <p class="text-xs text-slate-400 mt-0.5">Menjadi pionir penyedia transportasi premium yang mengintegrasikan kemewahan dengan teknologi digital berkelanjutan.</p>
                    </div>
                </div>
                <div class="flex items-start space-x-3">
                    <span class="text-brand-500 font-bold mt-1">✔</span>
                    <div>
                        <h4 class="font-bold text-white text-sm">Misi</h4>
                        <p class="text-xs text-slate-400 mt-0.5">Menyediakan armada mobil terawat kualitas tertinggi, menjaga kejujuran tarif tanpa biaya tersembunyi, serta menghadirkan kenyamanan prima bagi pelanggan.</p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Showcase Graphic / Stats Card -->
        <div class="bg-brand-card/50 border border-slate-800/80 rounded-[32px] p-8 space-y-6 relative overflow-hidden">
            <div class="absolute -bottom-10 -right-10 w-40 h-40 bg-indigo-500/10 rounded-full blur-2xl"></div>
            
            <h3 class="text-xl font-bold font-display text-white">Swift Ride Dalam Angka</h3>
            <div class="grid grid-cols-2 gap-6 pt-4">
                <div class="space-y-1">
                    <span class="block text-3xl font-bold text-brand-500 font-display">15+</span>
                    <span class="text-xs text-slate-400">Armada Premium</span>
                </div>
                <div class="space-y-1">
                    <span class="block text-3xl font-bold text-brand-500 font-display">1.2k+</span>
                    <span class="text-xs text-slate-400">Penyewa Puas</span>
                </div>
                <div class="space-y-1">
                    <span class="block text-3xl font-bold text-brand-500 font-display">99.8%</span>
                    <span class="text-xs text-slate-400">Keamanan Terjamin</span>
                </div>
                <div class="space-y-1">
                    <span class="block text-3xl font-bold text-brand-500 font-display">24/7</span>
                    <span class="text-xs text-slate-400">Layanan Pelanggan</span>
                </div>
            </div>
            
            <div class="pt-6 border-t border-slate-800/80 flex items-center space-x-3">
                <span class="text-emerald-500">🛡️</span>
                <span class="text-xs text-slate-400 leading-normal">Seluruh armada dilindungi oleh Asuransi Comprehensive Terbaik demi kenyamanan berkendara Anda.</span>
            </div>
        </div>
    </div>
</section>

<!-- Values / Pillars Section -->
<section class="py-16">
    <div class="max-w-7xl mx-auto px-6">
        <div class="text-center max-w-xl mx-auto mb-12">
            <h2 class="text-2xl sm:text-3xl font-display font-bold text-white">Nilai Inti Kami</h2>
            <p class="text-xs sm:text-sm text-slate-400 mt-2">Prinsip dasar yang menuntun kami memberikan pelayanan terbaik setiap harinya.</p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="bg-brand-card/30 border border-slate-800/50 p-6 rounded-2xl space-y-3">
                <span class="text-2xl">✨</span>
                <h4 class="text-base font-bold text-white font-display">Keunggulan Estetika</h4>
                <p class="text-xs text-slate-400 leading-relaxed">Menyediakan hanya mobil-mobil modern premium yang terawat secara estetika maupun performa mesin.</p>
            </div>
            <div class="bg-brand-card/30 border border-slate-800/50 p-6 rounded-2xl space-y-3">
                <span class="text-2xl">🤝</span>
                <h4 class="text-base font-bold text-white font-display">Kejujuran Mutlak</h4>
                <p class="text-xs text-slate-400 leading-relaxed">Harga yang transparan sejak awal. Tanpa biaya tambahan terselubung saat pengembalian.</p>
            </div>
            <div class="bg-brand-card/30 border border-slate-800/50 p-6 rounded-2xl space-y-3">
                <span class="text-2xl">🚀</span>
                <h4 class="text-base font-bold text-white font-display">Inovasi Digital</h4>
                <p class="text-xs text-slate-400 leading-relaxed">Mengedepankan efisiensi melalui proses pemesanan digital yang intuitif dan serba instan.</p>
            </div>
        </div>
    </div>
</section>

<?php
// Sertakan layout Footer
require_once __DIR__ . '/includes/footer.php';
?>
