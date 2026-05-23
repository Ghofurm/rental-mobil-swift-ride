<?php
// cars.php

// Aktifkan pelaporan error internal
ini_set('display_errors', 0);
ini_set('log_errors', 1);
error_reporting(E_ALL);

$db_connected = false;
$cars = [];
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$type_filter = isset($_GET['type']) ? trim($_GET['type']) : '';

if (file_exists(__DIR__ . '/config/db.php')) {
    try {
        require_once __DIR__ . '/config/db.php';
        if (isset($pdo)) {
            $query = "SELECT * FROM cars WHERE status = 'available'";
            $params = [];
            
            if (!empty($search)) {
                $query .= " AND (brand LIKE :search OR model LIKE :search)";
                $params[':search'] = '%' . $search . '%';
            }
            
            if (!empty($type_filter)) {
                $query .= " AND type = :type";
                $params[':type'] = $type_filter;
            }
            
            $query .= " ORDER BY daily_rate DESC";
            
            $stmt = $pdo->prepare($query);
            $stmt->execute($params);
            $cars = $stmt->fetchAll();
            $db_connected = true;
        }
    } catch (Exception $e) {
        error_log("Gagal memuat database pada cars.php: " . $e->getMessage());
    }
}

// Fallback data statis premium jika database MySQL belum diinisialisasi
if (!$db_connected || empty($cars)) {
    $static_cars = [
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
    
    // Terapkan filter pencarian & tipe secara manual jika menggunakan data fallback statis
    $cars = array_filter($static_cars, function($car) use ($search, $type_filter) {
        $match = true;
        if (!empty($search)) {
            $brand_match = stripos($car['brand'], $search) !== false;
            $model_match = stripos($car['model'], $search) !== false;
            $match = $brand_match || $model_match;
        }
        if ($match && !empty($type_filter)) {
            $match = $car['type'] === $type_filter;
        }
        return $match;
    });
}

// Sertakan layout Header
require_once __DIR__ . '/includes/header.php';
?>

<!-- Title & Filter Section -->
<section class="pt-24 pb-12 relative overflow-hidden">
    <div class="absolute top-1/4 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[500px] h-[500px] bg-brand-500/10 rounded-full blur-[120px] pointer-events-none"></div>
    <div class="max-w-7xl mx-auto px-6 relative z-10">
        <div class="text-center max-w-3xl mx-auto space-y-4 mb-12">
            <span class="text-xs font-semibold text-brand-500 tracking-widest uppercase">Armada Pilihan</span>
            <h1 class="text-4xl sm:text-5xl font-display font-bold text-white">Katalog Mobil Premium</h1>
            <p class="text-sm sm:text-base text-slate-400">
                Jelajahi armada eksklusif kami yang siap menemani perjalanan bisnis atau liburan berkelas Anda.
            </p>
        </div>

        <!-- Search and Filter Bar Form -->
        <form method="GET" action="cars.php" class="max-w-4xl mx-auto bg-brand-card/70 border border-slate-800/80 p-4 rounded-3xl backdrop-blur-md grid grid-cols-1 md:grid-cols-12 gap-4 items-center">
            <!-- Search Input -->
            <div class="md:col-span-6 relative">
                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-500">🔍</span>
                <input 
                    type="text" 
                    name="search" 
                    value="<?php echo htmlspecialchars($search, ENT_QUOTES, 'UTF-8'); ?>"
                    placeholder="Cari merek atau model mobil..."
                    class="w-full pl-11 pr-4 py-3 bg-slate-900/60 border border-slate-800 rounded-2xl text-sm text-white placeholder-slate-500 focus:outline-none focus:border-brand-500 transition-colors"
                >
            </div>

            <!-- Type Filter Select -->
            <div class="md:col-span-4">
                <select 
                    name="type" 
                    class="w-full px-4 py-3 bg-slate-900/60 border border-slate-800 rounded-2xl text-sm text-slate-300 focus:outline-none focus:border-brand-500 transition-colors"
                >
                    <option value="">Semua Tipe</option>
                    <option value="Electric" <?php echo $type_filter === 'Electric' ? 'selected' : ''; ?>>Electric</option>
                    <option value="Luxury" <?php echo $type_filter === 'Luxury' ? 'selected' : ''; ?>>Luxury</option>
                    <option value="Sports SUV" <?php echo $type_filter === 'Sports SUV' ? 'selected' : ''; ?>>Sports SUV</option>
                    <option value="Premium MPV" <?php echo $type_filter === 'Premium MPV' ? 'selected' : ''; ?>>Premium MPV</option>
                </select>
            </div>

            <!-- Submit Button -->
            <div class="md:col-span-2">
                <button 
                    type="submit" 
                    class="w-full py-3 bg-brand-600 hover:bg-brand-700 font-semibold text-white rounded-2xl text-sm transition-colors duration-200"
                >
                    Filter
                </button>
            </div>
        </form>
    </div>
</section>

<!-- Fleet List Section -->
<section class="pb-24">
    <div class="max-w-7xl mx-auto px-6">
        <!-- Database Connection Info badge -->
        <div class="flex justify-end mb-6">
            <div class="inline-flex items-center space-x-2 bg-slate-900/60 border border-slate-800 px-4 py-1.5 rounded-full">
                <span class="w-2 h-2 rounded-full <?php echo $db_connected ? 'bg-emerald-500' : 'bg-amber-500'; ?>"></span>
                <span class="text-[10px] font-medium text-slate-400">
                    <?php echo $db_connected ? 'Database MySQL Terkoneksi' : 'Menggunakan Data Lokal (Fallback)'; ?>
                </span>
            </div>
        </div>

        <?php if (empty($cars)): ?>
            <!-- No results -->
            <div class="text-center py-20 bg-brand-card/20 border border-slate-900 rounded-3xl">
                <span class="text-4xl">🚗</span>
                <h3 class="text-lg font-bold text-white mt-4">Tidak ada mobil ditemukan</h3>
                <p class="text-sm text-slate-500 mt-2">Coba bersihkan pencarian atau filter Anda dan coba lagi.</p>
                <a href="cars.php" data-link class="inline-block mt-6 px-6 py-2.5 rounded-full bg-slate-900 border border-slate-800 text-xs font-semibold text-brand-300 hover:bg-slate-800">
                    Reset Filter
                </a>
            </div>
        <?php else: ?>
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
                            
                            <!-- Specifications Icons -->
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
                                <a href="https://wa.me/6281234567890?text=Halo%20Swift%20Ride,%20saya%20ingin%20menyewa%20mobil%20<?php echo urlencode($car['brand'] . ' ' . $car['model']); ?>" target="_blank" rel="noopener noreferrer" class="px-5 py-2.5 rounded-full bg-slate-900 border border-slate-800 text-xs font-semibold text-brand-200 hover:bg-brand-600 hover:text-white hover:border-transparent transition-all duration-300">
                                    Sewa Mobil
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php
// Sertakan layout Footer
require_once __DIR__ . '/includes/footer.php';
?>
