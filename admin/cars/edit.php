<?php
// admin/cars/edit.php — Edit Data Armada Mobil

session_start();

define('BASE_URL', '../../');
require_once '../../config/db.php';
require_once '../../includes/csrf.php';
require_once '../../includes/auth_check.php';

$pageTitle = 'Edit Mobil';
$errors    = [];

// Ambil dan validasi ID dari query string
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id || $id < 1) {
    $_SESSION['flash_error'] = 'ID kendaraan tidak valid.';
    header('Location: ../index.php');
    exit;
}

// Ambil data mobil yang akan diedit
$stmt = $pdo->prepare("SELECT * FROM cars WHERE id = ? LIMIT 1");
$stmt->execute([$id]);
$car = $stmt->fetch();

if (!$car) {
    $_SESSION['flash_error'] = 'Kendaraan tidak ditemukan.';
    header('Location: ../index.php');
    exit;
}

// ─── Proses Form POST ─────────────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_verify();

    // Pastikan id dari POST konsisten dengan query param
    $postId = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
    if ($postId !== $id) {
        http_response_code(403);
        die('Permintaan tidak valid.');
    }

    $brand        = trim($_POST['brand']        ?? '');
    $model        = trim($_POST['model']        ?? '');
    $type         = trim($_POST['type']         ?? '');
    $daily_rate   = trim($_POST['daily_rate']   ?? '');
    $status       = trim($_POST['status']       ?? 'available');
    $transmission = trim($_POST['transmission'] ?? 'Automatic');
    $fuel         = trim($_POST['fuel']         ?? 'Petrol');
    $seats        = trim($_POST['seats']        ?? '5');
    $description  = trim($_POST['description']  ?? '');
    $image        = $car['image']; // Default simpan gambar lama

    // Validasi
    if (!$brand || mb_strlen($brand) > 50)
        $errors['brand'] = 'Merek wajib diisi (maks. 50 karakter).';
    if (!$model || mb_strlen($model) > 50)
        $errors['model'] = 'Model wajib diisi (maks. 50 karakter).';
    if (!$type || mb_strlen($type) > 30)
        $errors['type'] = 'Tipe wajib diisi (maks. 30 karakter).';
    if (!is_numeric($daily_rate) || (float)$daily_rate <= 0)
        $errors['daily_rate'] = 'Tarif harian harus berupa angka positif.';
    if (!in_array($status, ['available', 'rented', 'maintenance'], true))
        $errors['status'] = 'Status tidak valid.';
    if (!in_array($transmission, ['Automatic', 'Manual'], true))
        $errors['transmission'] = 'Transmisi tidak valid.';
    if (!in_array($fuel, ['Petrol', 'Diesel', 'Electric', 'Hybrid'], true))
        $errors['fuel'] = 'Bahan bakar tidak valid.';
    if (!ctype_digit($seats) || (int)$seats < 1 || (int)$seats > 20)
        $errors['seats'] = 'Jumlah kursi harus antara 1–20.';

    // Proses upload gambar jika ada
    if (isset($_FILES['image']) && $_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE) {
        if ($_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $fileTmpPath = $_FILES['image']['tmp_name'];
            $fileName = $_FILES['image']['name'];
            $fileSize = $_FILES['image']['size'];
            $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

            $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp'];
            if (!in_array($fileExtension, $allowedExtensions)) {
                $errors['image'] = 'Format gambar tidak valid. Hanya JPG, PNG, dan WEBP yang diperbolehkan.';
            }

            $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/webp'];
            if (empty($errors['image']) && function_exists('mime_content_type')) {
                $mimeType = mime_content_type($fileTmpPath);
                if (!in_array($mimeType, $allowedMimeTypes)) {
                    $errors['image'] = 'Format file tidak valid.';
                }
            }

            if ($fileSize > 2 * 1024 * 1024) {
                $errors['image'] = 'Ukuran gambar tidak boleh lebih dari 2MB.';
            }

            if (empty($errors['image'])) {
                $newFileName = uniqid('car_', true) . '.' . $fileExtension;
                $uploadFileDir = '../../uploads/';
                $dest_path = $uploadFileDir . $newFileName;

                if (move_uploaded_file($fileTmpPath, $dest_path)) {
                    $image = 'uploads/' . $newFileName;
                    
                    // Hapus gambar lama jika ada dan merupakan file lokal
                    if (!empty($car['image']) && !filter_var($car['image'], FILTER_VALIDATE_URL)) {
                        $oldImagePath = '../../' . $car['image'];
                        if (file_exists($oldImagePath)) {
                            @unlink($oldImagePath);
                        }
                    }
                } else {
                    $errors['image'] = 'Gagal menyimpan gambar baru di server.';
                }
            }
        } else {
            $errors['image'] = 'Gagal mengunggah gambar.';
        }
    }

    if (empty($errors)) {
        $stmt = $pdo->prepare("
            UPDATE cars SET
                brand=?, model=?, type=?, daily_rate=?, image=?,
                status=?, transmission=?, fuel=?, seats=?, description=?
            WHERE id=?
        ");
        $stmt->execute([
            $brand, $model, $type,
            (float)$daily_rate,
            $image, $status, $transmission, $fuel,
            (int)$seats, $description,
            $id
        ]);

        $_SESSION['flash_success'] = "Data {$brand} {$model} berhasil diperbarui!";
        header('Location: ../index.php');
        exit;
    }

    // Repopulate $car dengan data POST jika ada error validasi
    $car = array_merge($car, $old);
}

require_once '../../includes/admin_header.php';

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
function sel(mixed $fieldVal, string $optionVal): string {
    return (string)$fieldVal === $optionVal ? 'selected' : '';
}
?>

<div class="max-w-3xl animate-fade-in">
    <!-- Breadcrumb -->
    <nav class="text-sm text-slate-500 mb-6 flex items-center gap-2">
        <a href="<?php echo BASE_URL; ?>admin/index.php" class="hover:text-slate-300 transition-colors">Dashboard</a>
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
        <span class="text-slate-300">Edit — <?php echo htmlspecialchars($car['brand'] . ' ' . $car['model'], ENT_QUOTES, 'UTF-8'); ?></span>
    </nav>

    <div class="bg-[#131926] rounded-2xl border border-slate-800/50 p-6 lg:p-8">
        <h2 class="text-lg font-semibold text-white mb-6">Edit Informasi Kendaraan</h2>

        <form method="POST" action="edit.php?id=<?php echo (int)$id; ?>" enctype="multipart/form-data" novalidate>
            <?php echo csrf_field(); ?>
            <input type="hidden" name="id" value="<?php echo (int)$id; ?>">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                <!-- Merek -->
                <div>
                    <label for="car-brand" class="block text-sm font-medium text-slate-300 mb-1.5">Merek <span class="text-red-400">*</span></label>
                    <input type="text" id="car-brand" name="brand" maxlength="50" required
                           class="<?php echo inputClass($errors, 'brand'); ?>"
                           value="<?php echo htmlspecialchars($car['brand'], ENT_QUOTES, 'UTF-8'); ?>">
                    <?php echo fieldError($errors, 'brand'); ?>
                </div>

                <!-- Model -->
                <div>
                    <label for="car-model" class="block text-sm font-medium text-slate-300 mb-1.5">Model <span class="text-red-400">*</span></label>
                    <input type="text" id="car-model" name="model" maxlength="50" required
                           class="<?php echo inputClass($errors, 'model'); ?>"
                           value="<?php echo htmlspecialchars($car['model'], ENT_QUOTES, 'UTF-8'); ?>">
                    <?php echo fieldError($errors, 'model'); ?>
                </div>

                <!-- Tipe -->
                <div>
                    <label for="car-type" class="block text-sm font-medium text-slate-300 mb-1.5">Tipe / Kategori <span class="text-red-400">*</span></label>
                    <input type="text" id="car-type" name="type" maxlength="30" required
                           class="<?php echo inputClass($errors, 'type'); ?>"
                           value="<?php echo htmlspecialchars($car['type'], ENT_QUOTES, 'UTF-8'); ?>">
                    <?php echo fieldError($errors, 'type'); ?>
                </div>

                <!-- Tarif Harian -->
                <div>
                    <label for="car-rate" class="block text-sm font-medium text-slate-300 mb-1.5">Tarif Harian (Rp) <span class="text-red-400">*</span></label>
                    <input type="number" id="car-rate" name="daily_rate" min="1" step="1000" required
                           class="<?php echo inputClass($errors, 'daily_rate'); ?>"
                           value="<?php echo htmlspecialchars($car['daily_rate'], ENT_QUOTES, 'UTF-8'); ?>">
                    <?php echo fieldError($errors, 'daily_rate'); ?>
                </div>

                <!-- Transmisi -->
                <div>
                    <label for="car-transmission" class="block text-sm font-medium text-slate-300 mb-1.5">Transmisi</label>
                    <select id="car-transmission" name="transmission" class="<?php echo inputClass($errors, 'transmission'); ?> cursor-pointer">
                        <option value="Automatic" <?php echo sel($car['transmission'], 'Automatic'); ?>>Automatic</option>
                        <option value="Manual"    <?php echo sel($car['transmission'], 'Manual'); ?>>Manual</option>
                    </select>
                </div>

                <!-- Bahan Bakar -->
                <div>
                    <label for="car-fuel" class="block text-sm font-medium text-slate-300 mb-1.5">Bahan Bakar</label>
                    <select id="car-fuel" name="fuel" class="<?php echo inputClass($errors, 'fuel'); ?> cursor-pointer">
                        <?php foreach (['Petrol','Diesel','Electric','Hybrid'] as $f): ?>
                        <option value="<?php echo $f; ?>" <?php echo sel($car['fuel'], $f); ?>><?php echo $f; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Jumlah Kursi -->
                <div>
                    <label for="car-seats" class="block text-sm font-medium text-slate-300 mb-1.5">Jumlah Kursi</label>
                    <input type="number" id="car-seats" name="seats" min="1" max="20"
                           class="<?php echo inputClass($errors, 'seats'); ?>"
                           value="<?php echo (int)$car['seats']; ?>">
                    <?php echo fieldError($errors, 'seats'); ?>
                </div>

                <!-- Status -->
                <div>
                    <label for="car-status" class="block text-sm font-medium text-slate-300 mb-1.5">Status</label>
                    <select id="car-status" name="status" class="<?php echo inputClass($errors, 'status'); ?> cursor-pointer">
                        <option value="available"   <?php echo sel($car['status'], 'available'); ?>>Tersedia</option>
                        <option value="rented"      <?php echo sel($car['status'], 'rented'); ?>>Disewa</option>
                        <option value="maintenance" <?php echo sel($car['status'], 'maintenance'); ?>>Maintenance</option>
                    </select>
                </div>

                <!-- Upload Gambar -->
                <div class="md:col-span-2">
                    <label for="car-image" class="block text-sm font-medium text-slate-300 mb-1.5">Gambar Mobil (Kosongkan jika tidak diubah)</label>
                    <input type="file" id="car-image" name="image" accept="image/*"
                           class="<?php echo inputClass($errors, 'image'); ?>">
                    <?php echo fieldError($errors, 'image'); ?>
                    <!-- Preview gambar saat ini -->
                    <?php if (!empty($car['image'])): 
                        $imgUrl = $car['image'];
                        if (!filter_var($imgUrl, FILTER_VALIDATE_URL)) {
                            $imgUrl = BASE_URL . $imgUrl;
                        }
                    ?>
                    <div class="mt-3 w-32 h-20 rounded-xl overflow-hidden bg-slate-800">
                        <img src="<?php echo htmlspecialchars($imgUrl, ENT_QUOTES, 'UTF-8'); ?>"
                             alt="Preview" class="w-full h-full object-cover" loading="lazy"
                             onerror="this.parentElement.style.display='none'">
                    </div>
                    <?php endif; ?>
                    <p class="mt-1.5 text-xs text-slate-600">Format gambar yang didukung: JPG, PNG, WEBP. Maksimal ukuran file: 2MB.</p>
                </div>

                <!-- Deskripsi -->
                <div class="md:col-span-2">
                    <label for="car-description" class="block text-sm font-medium text-slate-300 mb-1.5">Deskripsi (opsional)</label>
                    <textarea id="car-description" name="description" rows="3" maxlength="1000"
                              class="<?php echo inputClass($errors, 'description'); ?> resize-none"><?php echo htmlspecialchars($car['description'] ?? '', ENT_QUOTES, 'UTF-8'); ?></textarea>
                </div>

            </div>

            <!-- Tombol Aksi -->
            <div class="flex items-center gap-3 mt-8 pt-6 border-t border-slate-800/50">
                <button type="submit" id="btn-submit-edit" class="px-6 py-3 rounded-xl bg-gradient-to-r from-brand-600 to-indigo-500 text-white text-sm font-semibold hover:from-indigo-500 hover:to-brand-600 transition-all shadow-lg shadow-indigo-500/20">
                    Simpan Perubahan
                </button>
                <a href="<?php echo BASE_URL; ?>admin/index.php" class="px-6 py-3 rounded-xl bg-slate-700 text-slate-300 text-sm font-medium hover:bg-slate-600 transition-colors">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>

<?php require_once '../../includes/admin_footer.php'; ?>
