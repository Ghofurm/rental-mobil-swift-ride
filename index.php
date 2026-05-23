<?php
// index.php

// Aktifkan pelaporan error internal (untuk verifikasi aman)
ini_set('display_errors', 0);
ini_set('log_errors', 1);
error_reporting(E_ALL);

// Integrasikan file database secara opsional (jika database belum siap, gunakan fallback data lokal yang statis)
$db_connected = false;
$cars = [];

if (file_exists(__DIR__ . '/config/db.php')) {
    try {
        require_once __DIR__ . '/config/db.php';
        if (isset($pdo)) {
            $stmt = $pdo->query("SELECT * FROM cars WHERE status = 'available' ORDER BY daily_rate DESC");
            $cars = $stmt->fetchAll();
            $db_connected = true;
        }
    } catch (Exception $e) {
        error_log("Gagal memuat database pada index.php: " . $e->getMessage());
    }
}

// Fallback data statis premium jika database MySQL belum diinisialisasi
if (!$db_connected || empty($cars)) {
    $cars = [
        [
            'brand' => 'Tesla',
            'model' => 'Model Y Long Range',
            'type' => 'Electric',
            'daily_rate' => 2200000.00,
            'image' => 'https://images.unsplash.com/photo-1619767886558-efdc259cde1a?auto=format&fit=crop&q=80&w=800',
            'transmission' => 'Automatic',
            'fuel' => 'Electric',
            'seats' => 5
        ],
        [
            'brand' => 'BMW',
            'model' => '3 Series Sedan',
            'type' => 'Luxury',
            'daily_rate' => 1900000.00,
            'image' => 'https://images.unsplash.com/photo-1555215695-3004980ad54e?auto=format&fit=crop&q=80&w=800',
            'transmission' => 'Automatic',
            'fuel' => 'Petrol',
            'seats' => 5
        ],
        [
            'brand' => 'Mercedes-Benz',
            'model' => 'C-Class Cabriolet',
            'type' => 'Luxury',
            'daily_rate' => 2100000.00,
            'image' => 'https://images.unsplash.com/photo-1617814076367-b759c7d7e738?auto=format&fit=crop&q=80&w=800',
            'transmission' => 'Automatic',
            'fuel' => 'Petrol',
            'seats' => 5
        ],
        [
            'brand' => 'Hyundai',
            'model' => 'Ioniq 5 Signature',
            'type' => 'Electric',
            'daily_rate' => 1700000.00,
            'image' => 'https://images.unsplash.com/photo-1669062335191-118e9527ec3a?auto=format&fit=crop&q=80&w=800',
            'transmission' => 'Automatic',
            'fuel' => 'Electric',
            'seats' => 5
        ],
        [
            'brand' => 'Porsche',
            'model' => 'Macan GTS',
            'type' => 'Sports SUV',
            'daily_rate' => 3500000.00,
            'image' => 'https://images.unsplash.com/photo-1503376780353-7e6692767b70?auto=format&fit=crop&q=80&w=800',
            'transmission' => 'Automatic',
            'fuel' => 'Petrol',
            'seats' => 5
        ],
        [
            'brand' => 'Toyota',
            'model' => 'Alphard Hybrid',
            'type' => 'Premium MPV',
            'daily_rate' => 2800000.00,
            'image' => 'https://images.unsplash.com/photo-1549399542-7e3f8b79c341?auto=format&fit=crop&q=80&w=800',
            'transmission' => 'Automatic',
            'fuel' => 'Hybrid',
            'seats' => 7
        ]
    ];
}

// Sertakan layout Header
require_once __DIR__ . '/includes/header.php';
?>

<!-- Hero Section -->
<section id="home" class="relative pt-24 pb-20 md:pt-32 md:pb-28 overflow-hidden">
    <!-- Glowing background elements -->
    <div class="absolute top-1/4 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[500px] h-[500px] bg-brand-500/10 rounded-full blur-[120px] pointer-events-none"></div>
    <div class="absolute bottom-10 right-10 w-[300px] h-[300px] bg-indigo-500/5 rounded-full blur-[100px] pointer-events-none"></div>

    <div class="max-w-7xl mx-auto px-6 relative z-10 grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
        <!-- Hero Texts -->
        <div class="lg:col-span-7 space-y-6 text-center lg:text-left">
            <div class="inline-flex items-center space-x-2 px-3 py-1.5 rounded-full bg-slate-900 border border-slate-800 text-xs font-semibold text-brand-500 tracking-wide uppercase">
                <span class="w-1.5 h-1.5 bg-brand-500 rounded-full animate-ping"></span>
                <span>Layanan Sewa Premium Nomor 1</span>
            </div>
            <h1 class="text-4xl sm:text-5xl md:text-6xl font-display font-bold tracking-tight text-white leading-none">
                Berkendara Menuju <br class="hidden sm:inline">
                <span class="bg-gradient-to-r from-brand-500 via-indigo-400 to-white bg-clip-text text-transparent">Kebebasan Tanpa Batas</span>
            </h1>
            <p class="text-base sm:text-lg text-slate-400 max-w-2xl mx-auto lg:mx-0 leading-relaxed">
                Swift Ride menghadirkan pengalaman sewa mobil premium yang modern, minimalis, dan sepenuhnya transparan. Pilih armada terbaik untuk setiap momen perjalanan Anda.
            </p>
            <div class="flex flex-col sm:flex-row items-center justify-center lg:justify-start gap-4">
                <a href="#fleet" class="w-full sm:w-auto px-8 py-4 rounded-full bg-gradient-to-r from-brand-600 to-indigo-500 hover:shadow-lg hover:shadow-brand-600/20 font-semibold text-white tracking-wide transition-all duration-300 text-center">
                    Jelajahi Armada
                </a>
                <a href="#features" class="w-full sm:w-auto px-8 py-4 rounded-full bg-slate-900/80 hover:bg-slate-800 border border-slate-800/80 font-semibold text-slate-300 hover:text-white transition-all duration-300 text-center">
                    Mengapa Kami?
                </a>
            </div>
        </div>
        
        <!-- Hero Graphic (Premium Minimalist UI Render mockup) -->
        <div class="lg:col-span-5 relative flex justify-center">
            <div class="w-full max-w-md p-1 bg-gradient-to-tr from-brand-500/20 to-transparent rounded-3xl backdrop-blur-3xl">
                <div class="bg-brand-card/90 rounded-[22px] p-6 border border-slate-800/50 space-y-6">
                    <div class="flex justify-between items-center">
                        <span class="text-xs text-slate-500 font-semibold uppercase tracking-wider">Mobil Terpopuler</span>
                        <span class="px-2 py-0.5 rounded bg-brand-500/10 text-[10px] text-brand-500 font-bold uppercase">Tesla Model Y</span>
                    </div>
                    <img src="https://images.unsplash.com/photo-1619767886558-efdc259cde1a?auto=format&fit=crop&q=80&w=800" alt="Tesla Model Y Promo" class="w-full h-48 object-cover rounded-xl shadow-lg border border-slate-800/40">
                    <div class="flex justify-between items-center">
                        <div>
                            <h3 class="font-display font-bold text-white">Tesla Model Y</h3>
                            <p class="text-xs text-slate-400">Paling Hemat • Full Electric</p>
                        </div>
                        <div class="text-right">
                            <span class="block text-sm font-bold text-brand-500">Rp 2,2jt</span>
                            <span class="text-[10px] text-slate-500">per hari</span>
                        </div>
                    </div>
                    <a href="#fleet" class="block w-full py-3 rounded-xl bg-brand-600 hover:bg-brand-700 font-semibold text-white text-center text-sm transition-colors">
                        Sewa Model Ini
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Features / Value Proposition Section -->
<section id="features" class="py-20 border-t border-slate-900/60 bg-brand-dark/30">
    <div class="max-w-7xl mx-auto px-6">
        <div class="text-center max-w-2xl mx-auto space-y-4 mb-16">
            <span class="text-xs font-semibold text-brand-500 tracking-widest uppercase">Keunggulan Layanan</span>
            <h2 class="text-3xl sm:text-4xl font-display font-bold text-white">
                Mengapa Memilih Swift Ride?
            </h2>
            <p class="text-sm sm:text-base text-slate-400">
                Kami mendesain seluruh proses sewa mobil dari awal hingga akhir agar berjalan mulus, cepat, dan transparan bagi kenyamanan berkendara Anda.
            </p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Card 1 -->
            <div class="bg-brand-card/40 border border-slate-800/60 p-8 rounded-3xl hover:border-brand-500/30 transition-all duration-300 group space-y-4">
                <div class="w-12 h-12 rounded-2xl bg-brand-500/10 flex items-center justify-center text-brand-500 group-hover:bg-brand-500 group-hover:text-white transition-all duration-300">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-white font-display">Asuransi & Keamanan Penuh</h3>
                <p class="text-sm text-slate-400 leading-relaxed">
                    Setiap unit armada kami dilindungi oleh perlindungan asuransi komprehensif, memberikan rasa tenang ekstra dalam perjalanan Anda.
                </p>
            </div>
            
            <!-- Card 2 -->
            <div class="bg-brand-card/40 border border-slate-800/60 p-8 rounded-3xl hover:border-brand-500/30 transition-all duration-300 group space-y-4">
                <div class="w-12 h-12 rounded-2xl bg-brand-500/10 flex items-center justify-center text-brand-500 group-hover:bg-brand-500 group-hover:text-white transition-all duration-300">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-white font-display">Harga Jujur & Transparan</h3>
                <p class="text-sm text-slate-400 leading-relaxed">
                    Tidak ada biaya tambahan atau biaya tersembunyi. Harga yang Anda lihat di situs kami adalah harga final yang Anda bayarkan.
                </p>
            </div>
            
            <!-- Card 3 -->
            <div class="bg-brand-card/40 border border-slate-800/60 p-8 rounded-3xl hover:border-brand-500/30 transition-all duration-300 group space-y-4">
                <div class="w-12 h-12 rounded-2xl bg-brand-500/10 flex items-center justify-center text-brand-500 group-hover:bg-brand-500 group-hover:text-white transition-all duration-300">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-white font-display">Proses Instan & Lepas Kunci</h3>
                <p class="text-sm text-slate-400 leading-relaxed">
                    Kemudahan verifikasi data secara daring yang cepat. Cukup pilih mobil, lakukan pembayaran, dan mobil siap diantar ke tempat Anda.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Fleet/Car List Section -->
<section id="fleet" class="py-20">
    <div class="max-w-7xl mx-auto px-6">
        <div class="flex flex-col md:flex-row md:items-end justify-between mb-16">
            <div class="max-w-xl space-y-4">
                <span class="text-xs font-semibold text-brand-500 tracking-widest uppercase">Armada Pilihan</span>
                <h2 class="text-3xl sm:text-4xl font-display font-bold text-white">
                    Pilih Mobil Sesuai Gaya Anda
                </h2>
                <p class="text-sm text-slate-400">
                    Semua kendaraan kami selalu berada dalam kondisi prima, terawat dengan standar kebersihan dan keamanan yang sangat ketat.
                </p>
            </div>
            
            <!-- Dynamic Database Connection Badge -->
            <div class="mt-4 md:mt-0 flex items-center space-x-2 bg-slate-900 border border-slate-800 px-4 py-2 rounded-full">
                <span class="w-2.5 h-2.5 rounded-full <?php echo $db_connected ? 'bg-emerald-500' : 'bg-amber-500'; ?>"></span>
                <span class="text-xs font-medium text-slate-400">
                    <?php echo $db_connected ? 'Database MySQL Terkoneksi' : 'Menggunakan Data Lokal (Fallback)'; ?>
                </span>
            </div>
        </div>
        
        <!-- Car Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php foreach ($cars as $car): ?>
                <div class="bg-brand-card/50 border border-slate-800/80 rounded-[28px] overflow-hidden hover:border-brand-500/50 hover:shadow-2xl hover:shadow-brand-500/5 transition-all duration-300 group flex flex-col">
                    <!-- Image Wrapper -->
                    <div class="relative h-56 overflow-hidden bg-slate-900 flex items-center justify-center">
                        <img 
                            src="<?php echo htmlspecialchars($car['image'], ENT_QUOTES, 'UTF-8'); ?>" 
                            alt="<?php echo htmlspecialchars($car['brand'] . ' ' . $car['model'], ENT_QUOTES, 'UTF-8'); ?>"
                            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                            loading="lazy"
                        >
                        <span class="absolute top-4 left-4 px-3 py-1 rounded-full bg-brand-dark/80 backdrop-blur-sm text-[10px] font-bold text-brand-200 border border-slate-800/50 uppercase tracking-wider">
                            <?php echo htmlspecialchars($car['type'], ENT_QUOTES, 'UTF-8'); ?>
                        </span>
                    </div>
                    
                    <!-- Content Card Details -->
                    <div class="p-6 flex flex-col flex-grow space-y-4">
                        <div class="flex justify-between items-start">
                            <div>
                                <h3 class="text-xl font-bold font-display text-white group-hover:text-brand-500 transition-colors duration-200">
                                    <?php echo htmlspecialchars($car['brand'] . ' ' . $car['model'], ENT_QUOTES, 'UTF-8'); ?>
                                </h3>
                                <p class="text-xs text-slate-500 mt-1">Transmisi: <?php echo htmlspecialchars($car['transmission'] ?? 'M/T', ENT_QUOTES, 'UTF-8'); ?></p>
                            </div>
                        </div>
                        
                        <!-- Specifications Icons layout -->
                        <div class="grid grid-cols-3 gap-2 border-y border-slate-800/60 py-3 text-xs text-slate-400">
                            <div class="flex items-center space-x-1.5 justify-center">
                                <span class="text-brand-500">⚡</span>
                                <span><?php echo htmlspecialchars($car['fuel'] ?? 'Bensin', ENT_QUOTES, 'UTF-8'); ?></span>
                            </div>
                            <div class="flex items-center space-x-1.5 justify-center">
                                <span class="text-brand-500">⚙️</span>
                                <span><?php echo htmlspecialchars($car['transmission'] ?? 'Auto', ENT_QUOTES, 'UTF-8'); ?></span>
                            </div>
                            <div class="flex items-center space-x-1.5 justify-center">
                                <span class="text-brand-500">👥</span>
                                <span><?php echo htmlspecialchars((string)($car['seats'] ?? 5), ENT_QUOTES, 'UTF-8'); ?> Kursi</span>
                            </div>
                        </div>
                        
                        <!-- Call to Action inside Card Footer -->
                        <div class="flex items-center justify-between pt-2 mt-auto">
                            <div>
                                <span class="block text-lg font-bold text-white">
                                    Rp <?php echo number_format($car['daily_rate'], 0, ',', '.'); ?>
                                </span>
                                <span class="text-[10px] text-slate-500">tarif per hari</span>
                            </div>
                            <a href="#" class="px-5 py-2.5 rounded-full bg-slate-900 border border-slate-800 text-xs font-semibold text-brand-200 hover:bg-brand-600 hover:text-white hover:border-transparent transition-all duration-300">
                                Sewa Mobil
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Call to Action Banner Section -->
<section class="py-16 bg-gradient-to-tr from-brand-dark via-slate-950 to-brand-dark border-t border-slate-900/60">
    <div class="max-w-5xl mx-auto px-6 text-center">
        <div class="bg-gradient-to-r from-brand-600/10 to-indigo-600/10 border border-brand-500/20 rounded-[36px] p-8 md:p-16 relative overflow-hidden space-y-6">
            <div class="absolute -top-10 -right-10 w-40 h-40 bg-brand-500/10 rounded-full blur-2xl"></div>
            <div class="absolute -bottom-10 -left-10 w-40 h-40 bg-indigo-500/10 rounded-full blur-2xl"></div>
            
            <h2 class="text-3xl md:text-4xl font-display font-bold text-white">
                Siap Melakukan Perjalanan Bernilai Anda?
            </h2>
            <p class="text-sm md:text-base text-slate-300 max-w-2xl mx-auto leading-relaxed">
                Hubungi staf representatif kami sekarang juga untuk mendapatkan penawaran harga sewa harian hingga bulanan terbaik yang disesuaikan dengan kebutuhan Anda.
            </p>
            <div class="pt-4">
                <a href="https://wa.me/6281234567890" target="_blank" rel="noopener noreferrer" class="inline-flex items-center space-x-2 px-8 py-4 rounded-full bg-emerald-600 hover:bg-emerald-700 hover:shadow-lg hover:shadow-emerald-600/20 font-semibold text-white tracking-wide transition-all duration-300">
                    <span>💬 Hubungi via WhatsApp</span>
                </a>
            </div>
        </div>
    </div>
</section>

<?php
// Sertakan layout Footer
require_once __DIR__ . '/includes/footer.php';
?>
