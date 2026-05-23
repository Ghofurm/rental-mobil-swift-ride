<?php
// admin/cars/create.php — Tambah Armada Mobil Baru

session_start();

define('BASE_URL', '../../');
require_once '../../config/db.php';
require_once '../../includes/csrf.php';
require_once '../../includes/auth_check.php';

$pageTitle = 'Tambah Mobil';
$errors    = [];
$old       = []; // data lama untuk repopulate form

// ─── Proses Form POST ─────────────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_verify();

    // Sanitasi & validasi input
    $old = $_POST;

    $brand        = trim($_POST['brand']        ?? '');
    $model        = trim($_POST['model']        ?? '');
    $type         = trim($_POST['type']         ?? '');
    $daily_rate   = trim($_POST['daily_rate']   ?? '');
    $image        = trim($_POST['image']        ?? '');
    $status       = trim($_POST['status']       ?? 'available');
    $transmission = trim($_POST['transmission'] ?? 'Automatic');
    $fuel         = trim($_POST['fuel']         ?? 'Petrol');
    $seats        = trim($_POST['seats']        ?? '5');
    $description  = trim($_POST['description']  ?? '');

    // Validasi
    if (!$brand || mb_strlen($brand) > 50)
        $errors['brand'] = 'Merek wajib diisi (maks. 50 karakter).';
    if (!$model || mb_strlen($model) > 50)
        $errors['model'] = 'Model wajib diisi (maks. 50 karakter).';
    if (!$type || mb_strlen($type) > 30)
        $errors['type'] = 'Tipe wajib diisi (maks. 30 karakter).';
    if (!is_numeric($daily_rate) || (float)$daily_rate <= 0)
        $errors['daily_rate'] = 'Tarif harian harus berupa angka positif.';
    if ($image && !filter_var($image, FILTER_VALIDATE_URL))
        $errors['image'] = 'URL gambar tidak valid.';
    if (!in_array($status, ['available', 'rented', 'maintenance'], true))
        $errors['status'] = 'Status tidak valid.';
    if (!in_array($transmission, ['Automatic', 'Manual'], true))
        $errors['transmission'] = 'Transmisi tidak valid.';
    if (!in_array($fuel, ['Petrol', 'Diesel', 'Electric', 'Hybrid'], true))
        $errors['fuel'] = 'Bahan bakar tidak valid.';
    if (!ctype_digit($seats) || (int)$seats < 1 || (int)$seats > 20)
        $errors['seats'] = 'Jumlah kursi harus antara 1–20.';

    if (empty($errors)) {
        $stmt = $pdo->prepare("
            INSERT INTO cars (brand, model, type, daily_rate, image, status, transmission, fuel, seats, description)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            $brand, $model, $type,
            (float)$daily_rate,
            $image, $status, $transmission, $fuel,
            (int)$seats, $description
        ]);

        $_SESSION['flash_success'] = "Mobil {$brand} {$model} berhasil ditambahkan!";
        header('Location: ../index.php');
        exit;
    }
}

require_once '../../includes/admin_header.php';

// Helper: tampilkan pesan error per field
function fieldError(array $errors, string $field): string {
    if (isset($errors[$field])) {
        return '<p class="mt-1.5 text-xs text-red-400">' . htmlspecialchars($errors[$field], ENT_QUOTES, 'UTF-8') . '</p>';
    }
    return '';
}
function inputClass(array $errors, string $field): string {
    return 'input-field w-full rounded-xl px-4 py-3 text-white placeholder-slate-500 text-sm'
         . (isset($errors[$field]) ? ' border-red-500/60' : '');
}
function old(array $old, string $key, string $default = ''): string {
    return htmlspecialchars($old[$key] ?? $default, ENT_QUOTES, 'UTF-8');
}
?>

<div class="max-w-3xl animate-fade-in">
    <!-- Breadcrumb -->
    <nav class="text-sm text-slate-500 mb-6 flex items-center gap-2">
        <a href="../index.php" class="hover:text-slate-300 transition-colors">Dashboard</a>
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
        <span class="text-slate-300">Tambah Mobil</span>
    </nav>

    <div class="bg-[#131926] rounded-2xl border border-slate-800/50 p-6 lg:p-8">
        <h2 class="text-lg font-semibold text-white mb-6">Informasi Kendaraan Baru</h2>

        <form method="POST" action="create.php" novalidate>
            <?php echo csrf_field(); ?>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                <!-- Merek -->
                <div>
                    <label for="car-brand" class="block text-sm font-medium text-slate-300 mb-1.5">Merek <span class="text-red-400">*</span></label>
                    <input type="text" id="car-brand" name="brand" maxlength="50" required
                           class="<?php echo inputClass($errors, 'brand'); ?>"
                           placeholder="Toyota, BMW, Honda..." value="<?php echo old($old, 'brand'); ?>">
                    <?php echo fieldError($errors, 'brand'); ?>
                </div>

                <!-- Model -->
                <div>
                    <label for="car-model" class="block text-sm font-medium text-slate-300 mb-1.5">Model <span class="text-red-400">*</span></label>
                    <input type="text" id="car-model" name="model" maxlength="50" required
                           class="<?php echo inputClass($errors, 'model'); ?>"
                           placeholder="Alphard, 3 Series..." value="<?php echo old($old, 'model'); ?>">
                    <?php echo fieldError($errors, 'model'); ?>
                </div>

                <!-- Tipe -->
                <div>
                    <label for="car-type" class="block text-sm font-medium text-slate-300 mb-1.5">Tipe / Kategori <span class="text-red-400">*</span></label>
                    <input type="text" id="car-type" name="type" maxlength="30" required
                           class="<?php echo inputClass($errors, 'type'); ?>"
                           placeholder="Luxury, SUV, MPV..." value="<?php echo old($old, 'type'); ?>">
                    <?php echo fieldError($errors, 'type'); ?>
                </div>

                <!-- Tarif Harian -->
                <div>
                    <label for="car-rate" class="block text-sm font-medium text-slate-300 mb-1.5">Tarif Harian (Rp) <span class="text-red-400">*</span></label>
                    <input type="number" id="car-rate" name="daily_rate" min="1" step="1000" required
                           class="<?php echo inputClass($errors, 'daily_rate'); ?>"
                           placeholder="1500000" value="<?php echo old($old, 'daily_rate'); ?>">
                    <?php echo fieldError($errors, 'daily_rate'); ?>
                </div>

                <!-- Transmisi -->
                <div>
                    <label for="car-transmission" class="block text-sm font-medium text-slate-300 mb-1.5">Transmisi</label>
                    <select id="car-transmission" name="transmission" class="<?php echo inputClass($errors, 'transmission'); ?> cursor-pointer">
                        <option value="Automatic" <?php echo old($old, 'transmission', 'Automatic') === 'Automatic' ? 'selected' : ''; ?>>Automatic</option>
                        <option value="Manual"    <?php echo old($old, 'transmission') === 'Manual' ? 'selected' : ''; ?>>Manual</option>
                    </select>
                </div>

                <!-- Bahan Bakar -->
                <div>
                    <label for="car-fuel" class="block text-sm font-medium text-slate-300 mb-1.5">Bahan Bakar</label>
                    <select id="car-fuel" name="fuel" class="<?php echo inputClass($errors, 'fuel'); ?> cursor-pointer">
                        <?php foreach (['Petrol','Diesel','Electric','Hybrid'] as $f): ?>
                        <option value="<?php echo $f; ?>" <?php echo old($old, 'fuel', 'Petrol') === $f ? 'selected' : ''; ?>><?php echo $f; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Jumlah Kursi -->
                <div>
                    <label for="car-seats" class="block text-sm font-medium text-slate-300 mb-1.5">Jumlah Kursi</label>
                    <input type="number" id="car-seats" name="seats" min="1" max="20"
                           class="<?php echo inputClass($errors, 'seats'); ?>"
                           value="<?php echo old($old, 'seats', '5'); ?>">
                    <?php echo fieldError($errors, 'seats'); ?>
                </div>

                <!-- Status -->
                <div>
                    <label for="car-status" class="block text-sm font-medium text-slate-300 mb-1.5">Status</label>
                    <select id="car-status" name="status" class="<?php echo inputClass($errors, 'status'); ?> cursor-pointer">
                        <option value="available"   <?php echo old($old, 'status', 'available') === 'available'   ? 'selected' : ''; ?>>Tersedia</option>
                        <option value="rented"      <?php echo old($old, 'status') === 'rented'      ? 'selected' : ''; ?>>Disewa</option>
                        <option value="maintenance" <?php echo old($old, 'status') === 'maintenance' ? 'selected' : ''; ?>>Maintenance</option>
                    </select>
                </div>

                <!-- URL Gambar (full width) -->
                <div class="md:col-span-2">
                    <label for="car-image" class="block text-sm font-medium text-slate-300 mb-1.5">URL Gambar</label>
                    <input type="url" id="car-image" name="image" maxlength="512"
                           class="<?php echo inputClass($errors, 'image'); ?>"
                           placeholder="https://..." value="<?php echo old($old, 'image'); ?>">
                    <?php echo fieldError($errors, 'image'); ?>
                    <p class="mt-1.5 text-xs text-slate-600">Kosongkan jika tidak ada gambar. Gunakan URL gambar publik (misalnya dari Unsplash).</p>
                </div>

                <!-- Deskripsi (full width) -->
                <div class="md:col-span-2">
                    <label for="car-description" class="block text-sm font-medium text-slate-300 mb-1.5">Deskripsi (opsional)</label>
                    <textarea id="car-description" name="description" rows="3" maxlength="1000"
                              class="<?php echo inputClass($errors, 'description'); ?> resize-none"
                              placeholder="Deskripsi singkat tentang kendaraan ini..."><?php echo old($old, 'description'); ?></textarea>
                </div>

            </div>

            <!-- Tombol Aksi -->
            <div class="flex items-center gap-3 mt-8 pt-6 border-t border-slate-800/50">
                <button type="submit" id="btn-submit-create" class="px-6 py-3 rounded-xl bg-gradient-to-r from-brand-600 to-indigo-500 text-white text-sm font-semibold hover:from-indigo-500 hover:to-brand-600 transition-all shadow-lg shadow-indigo-500/20">
                    Simpan Kendaraan
                </button>
                <a href="../index.php" class="px-6 py-3 rounded-xl bg-slate-700 text-slate-300 text-sm font-medium hover:bg-slate-600 transition-colors">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>

<?php require_once '../../includes/admin_footer.php'; ?>
