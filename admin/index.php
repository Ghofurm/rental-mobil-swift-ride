<?php
// admin/index.php — Dashboard Admin: Daftar Armada Mobil

session_start();

define('BASE_URL', '../');
require_once '../config/db.php';
require_once '../includes/csrf.php';
require_once '../includes/auth_check.php';

$pageTitle = 'Dashboard';

// ─── Ambil semua data mobil (paginasi sederhana) ─────────────────────────
$search  = trim($_GET['search'] ?? '');
$status  = $_GET['status'] ?? '';
$perPage = 10;
$page    = max(1, (int)($_GET['page'] ?? 1));
$offset  = ($page - 1) * $perPage;

// Bangun query dengan filter yang aman (prepared statement)
$where    = [];
$params   = [];

if ($search !== '') {
    $where[]  = "(brand LIKE ? OR model LIKE ? OR type LIKE ?)";
    $like     = "%{$search}%";
    $params   = array_merge($params, [$like, $like, $like]);
}
if (in_array($status, ['available', 'rented', 'maintenance'], true)) {
    $where[]  = "status = ?";
    $params[] = $status;
}

$whereClause = $where ? 'WHERE ' . implode(' AND ', $where) : '';

// Hitung total untuk paginasi
$totalStmt = $pdo->prepare("SELECT COUNT(*) FROM cars {$whereClause}");
$totalStmt->execute($params);
$total      = (int)$totalStmt->fetchColumn();
$totalPages = (int)ceil($total / $perPage);

// Ambil data halaman ini
$dataStmt = $pdo->prepare(
    "SELECT * FROM cars {$whereClause} ORDER BY created_at DESC LIMIT {$perPage} OFFSET {$offset}"
);
$dataStmt->execute($params);
$cars = $dataStmt->fetchAll();

// ─── Statistik cepat ────────────────────────────────────────────────────
$stats = $pdo->query("
    SELECT
        COUNT(*) AS total,
        SUM(status = 'available')   AS available,
        SUM(status = 'rented')      AS rented,
        SUM(status = 'maintenance') AS maintenance
    FROM cars
")->fetch();

require_once '../includes/admin_header.php';
?>

<!-- ─── Stat Cards ───────────────────────────────────────────────────── -->
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8 animate-fade-in">
    <?php
    $cards = [
        ['label' => 'Total Armada', 'value' => $stats['total'],       'color' => 'from-indigo-500 to-blue-600',   'icon' => 'M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10'],
        ['label' => 'Tersedia',     'value' => $stats['available'],    'color' => 'from-emerald-500 to-green-600', 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
        ['label' => 'Disewa',       'value' => $stats['rented'],       'color' => 'from-amber-500 to-orange-600',  'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'],
        ['label' => 'Maintenance',  'value' => $stats['maintenance'],  'color' => 'from-rose-500 to-red-600',      'icon' => 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z'],
    ];
    foreach ($cards as $card): ?>
    <div class="bg-[#131926] rounded-2xl p-5 border border-slate-800/50">
        <div class="flex items-center justify-between mb-3">
            <p class="text-sm text-slate-400"><?php echo $card['label']; ?></p>
            <div class="w-9 h-9 rounded-xl bg-gradient-to-br <?php echo $card['color']; ?> flex items-center justify-center">
                <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="<?php echo $card['icon']; ?>"/>
                </svg>
            </div>
        </div>
        <p class="text-3xl font-bold text-white font-display"><?php echo (int)$card['value']; ?></p>
    </div>
    <?php endforeach; ?>
</div>

<!-- ─── Filter & Search Bar ──────────────────────────────────────────── -->
<div class="flex flex-col sm:flex-row gap-3 mb-6 items-start sm:items-center justify-between animate-fade-in">
    <form id="filter-form" method="GET" action="index.php" class="flex flex-wrap gap-3 items-center">
        <input
            type="text"
            name="search"
            id="admin-search"
            value="<?php echo htmlspecialchars($search, ENT_QUOTES, 'UTF-8'); ?>"
            placeholder="Cari merek, model..."
            class="input-field rounded-xl px-4 py-2.5 text-sm text-white placeholder-slate-500 w-60"
        >
        <select name="status" id="admin-status-filter" onchange="this.form.submit()" class="input-field rounded-xl px-4 py-2.5 text-sm text-white w-44 cursor-pointer">
            <option value="" <?php if (!$status) echo 'selected'; ?>>Semua Status</option>
            <option value="available"   <?php if ($status === 'available')   echo 'selected'; ?>>Tersedia</option>
            <option value="rented"      <?php if ($status === 'rented')      echo 'selected'; ?>>Disewa</option>
            <option value="maintenance" <?php if ($status === 'maintenance') echo 'selected'; ?>>Maintenance</option>
        </select>
        <button type="submit" class="px-4 py-2.5 rounded-xl bg-brand-600 text-white text-sm font-medium hover:bg-brand-700 transition-colors">Cari</button>
        <?php if ($search || $status): ?>
            <a href="<?php echo BASE_URL; ?>admin/index.php" class="px-4 py-2.5 rounded-xl bg-slate-700 text-slate-300 text-sm font-medium hover:bg-slate-600 transition-colors">Reset</a>
        <?php endif; ?>
    </form>

    <a href="cars/create.php" id="btn-tambah-mobil" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-gradient-to-r from-brand-600 to-indigo-500 text-white text-sm font-semibold hover:from-indigo-500 hover:to-brand-600 transition-all shadow-lg shadow-indigo-500/20 shrink-0">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
        </svg>
        Tambah Mobil
    </a>
</div>

<!-- ─── Tabel Data Mobil ─────────────────────────────────────────────── -->
<div class="bg-[#131926] rounded-2xl border border-slate-800/50 overflow-hidden animate-fade-in">
    <?php if (empty($cars)): ?>
    <div class="flex flex-col items-center justify-center py-20 text-slate-500">
        <svg class="w-12 h-12 mb-4 opacity-40" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <p class="text-sm">Belum ada data mobil<?php echo $search ? " untuk pencarian \"" . htmlspecialchars($search, ENT_QUOTES, 'UTF-8') . "\"" : ''; ?>.</p>
        <a href="cars/create.php" class="mt-4 text-brand-500 text-sm hover:underline">+ Tambah mobil pertama</a>
    </div>
    <?php else: ?>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-slate-800/60 text-xs uppercase tracking-wider text-slate-500">
                    <th class="text-left px-5 py-4">Kendaraan</th>
                    <th class="text-left px-4 py-4 hidden lg:table-cell">Tipe</th>
                    <th class="text-left px-4 py-4 hidden md:table-cell">Transmisi / BBM</th>
                    <th class="text-right px-4 py-4">Tarif/Hari</th>
                    <th class="text-center px-4 py-4">Status</th>
                    <th class="text-center px-4 py-4">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-800/40">
                <?php foreach ($cars as $car): ?>
                <tr class="hover:bg-slate-800/20 transition-colors group">
                    <!-- Kendaraan -->
                    <td class="px-5 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-9 rounded-lg overflow-hidden bg-slate-800 shrink-0">
                                <?php if ($car['image']): 
                                    $imgUrl = $car['image'];
                                    if (!filter_var($imgUrl, FILTER_VALIDATE_URL)) {
                                        $imgUrl = BASE_URL . $imgUrl;
                                    }
                                ?>
                                <img
                                    src="<?php echo htmlspecialchars($imgUrl, ENT_QUOTES, 'UTF-8'); ?>"
                                    alt="<?php echo htmlspecialchars($car['brand'] . ' ' . $car['model'], ENT_QUOTES, 'UTF-8'); ?>"
                                    class="w-full h-full object-cover"
                                    loading="lazy"
                                >
                                <?php else: ?>
                                <div class="w-full h-full flex items-center justify-center text-slate-600">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                </div>
                                <?php endif; ?>
                            </div>
                            <div>
                                <p class="font-medium text-white"><?php echo htmlspecialchars($car['brand'] . ' ' . $car['model'], ENT_QUOTES, 'UTF-8'); ?></p>
                                <p class="text-xs text-slate-500"><?php echo $car['seats']; ?> kursi</p>
                            </div>
                        </div>
                    </td>
                    <!-- Tipe -->
                    <td class="px-4 py-4 text-slate-300 hidden lg:table-cell"><?php echo htmlspecialchars($car['type'], ENT_QUOTES, 'UTF-8'); ?></td>
                    <!-- Transmisi/BBM -->
                    <td class="px-4 py-4 hidden md:table-cell">
                        <span class="text-slate-300"><?php echo htmlspecialchars($car['transmission'], ENT_QUOTES, 'UTF-8'); ?></span>
                        <span class="text-slate-600 mx-1">·</span>
                        <span class="text-slate-400"><?php echo htmlspecialchars($car['fuel'], ENT_QUOTES, 'UTF-8'); ?></span>
                    </td>
                    <!-- Tarif -->
                    <td class="px-4 py-4 text-right font-semibold text-white">
                        Rp <?php echo number_format((float)$car['daily_rate'], 0, ',', '.'); ?>
                    </td>
                    <!-- Status -->
                    <td class="px-4 py-4 text-center">
                        <?php
                        $statusConfig = [
                            'available'   => ['class' => 'bg-emerald-500/15 text-emerald-400', 'label' => 'Tersedia'],
                            'rented'      => ['class' => 'bg-amber-500/15 text-amber-400',     'label' => 'Disewa'],
                            'maintenance' => ['class' => 'bg-rose-500/15 text-rose-400',       'label' => 'Maintenance'],
                        ];
                        $s = $statusConfig[$car['status']] ?? ['class' => 'bg-slate-500/15 text-slate-400', 'label' => $car['status']];
                        ?>
                        <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-medium <?php echo $s['class']; ?>">
                            <?php echo $s['label']; ?>
                        </span>
                    </td>
                    <!-- Aksi -->
                    <td class="px-4 py-4">
                        <div class="flex items-center justify-center gap-2">
                            <a href="cars/edit.php?id=<?php echo (int)$car['id']; ?>"
                               class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-medium bg-indigo-500/10 text-indigo-400 hover:bg-indigo-500/20 transition-colors">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                Edit
                            </a>
                            <button
                                type="button"
                                class="btn-delete inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-medium bg-rose-500/10 text-rose-400 hover:bg-rose-500/20 transition-colors"
                                data-id="<?php echo (int)$car['id']; ?>"
                                data-name="<?php echo htmlspecialchars($car['brand'] . ' ' . $car['model'], ENT_QUOTES, 'UTF-8'); ?>">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                Hapus
                            </button>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Paginasi -->
    <?php if ($totalPages > 1): ?>
    <div class="px-5 py-4 border-t border-slate-800/50 flex items-center justify-between text-sm text-slate-400">
        <span>Menampilkan <?php echo min($offset + 1, $total); ?>–<?php echo min($offset + $perPage, $total); ?> dari <?php echo $total; ?> data</span>
        <div class="flex gap-2">
            <?php for ($p = 1; $p <= $totalPages; $p++): ?>
            <a href="?page=<?php echo $p; ?>&search=<?php echo urlencode($search); ?>&status=<?php echo urlencode($status); ?>"
               class="w-8 h-8 rounded-lg flex items-center justify-center text-xs font-medium transition-colors <?php echo $p === $page ? 'bg-brand-600 text-white' : 'bg-slate-800 hover:bg-slate-700 text-slate-300'; ?>">
                <?php echo $p; ?>
            </a>
            <?php endfor; ?>
        </div>
    </div>
    <?php endif; ?>
    <?php endif; ?>
</div>

<!-- ─── Modal Konfirmasi Hapus ───────────────────────────────────────── -->
<div id="delete-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4">
    <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" id="modal-overlay"></div>
    <div class="relative bg-[#131926] rounded-2xl border border-slate-700 p-6 w-full max-w-sm shadow-2xl">
        <div class="flex items-center gap-4 mb-4">
            <div class="w-12 h-12 rounded-xl bg-rose-500/15 flex items-center justify-center shrink-0">
                <svg class="w-6 h-6 text-rose-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            </div>
            <div>
                <h2 class="text-base font-semibold text-white">Hapus Kendaraan?</h2>
                <p class="text-sm text-slate-400 mt-0.5">Tindakan ini tidak dapat dibatalkan.</p>
            </div>
        </div>
        <p class="text-sm text-slate-300 mb-6">Anda akan menghapus <strong id="modal-car-name" class="text-white"></strong> secara permanen.</p>
        <form id="delete-form" method="POST" action="cars/delete.php">
            <?php echo csrf_field(); ?>
            <input type="hidden" name="id" id="modal-car-id">
            <div class="flex gap-3">
                <button type="button" id="modal-cancel" class="flex-1 py-2.5 rounded-xl bg-slate-700 text-slate-300 text-sm font-medium hover:bg-slate-600 transition-colors">Batal</button>
                <button type="submit" id="modal-confirm-delete" class="flex-1 py-2.5 rounded-xl bg-rose-600 text-white text-sm font-semibold hover:bg-rose-500 transition-colors">Hapus</button>
            </div>
        </form>
    </div>
</div>

<script>
    // ─── Modal Hapus ───────────────────────────────────────
    const modal       = document.getElementById('delete-modal');
    const modalName   = document.getElementById('modal-car-name');
    const modalId     = document.getElementById('modal-car-id');
    const modalCancel = document.getElementById('modal-cancel');
    const overlay     = document.getElementById('modal-overlay');

    document.querySelectorAll('.btn-delete').forEach(btn => {
        btn.addEventListener('click', () => {
            // Gunakan textContent untuk menghindari XSS
            modalName.textContent = btn.dataset.name;
            modalId.value = btn.dataset.id;
            modal.classList.remove('hidden');
        });
    });

    function closeModal() { modal.classList.add('hidden'); }
    modalCancel.addEventListener('click', closeModal);
    overlay.addEventListener('click', closeModal);
    document.addEventListener('keydown', e => { if (e.key === 'Escape') closeModal(); });
</script>

<?php require_once '../includes/admin_footer.php'; ?>
