<?php
// index.php - Halaman utama / Read Data Inventaris
require_once __DIR__ . '/includes/auth.php';
check_login(); // Proteksi session halaman
require_once __DIR__ . '/config/database.php';

// Generate Token CSRF sederhana untuk keamanan hapus data
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Mengambil semua data barang dari database dengan MySQLi
$query = "SELECT * FROM barang ORDER BY id DESC";
$result = $conn->query($query);

require_once __DIR__ . '/includes/header.php';
?>

<!-- Alert Notifikasi Berhasil (Flash Message) -->
<?php if (isset($_SESSION['msg_success'])): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fa-solid fa-circle-check me-2"></i><?= htmlspecialchars($_SESSION['msg_success']); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php unset($_SESSION['msg_success']); ?>
<?php endif; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="mb-0 fw-bold text-dark"><i class="fa-solid fa-boxes-packing me-2 text-primary"></i><?= htmlspecialchars($lang['dashboard']); ?></h3>
        <p class="text-muted small mb-0">Kelola dan pantau stok inventaris barang Anda dengan mudah.</p>
    </div>
    <a href="barang_tambah.php" class="btn btn-primary">
        <i class="fa-solid fa-plus me-1"></i><?= htmlspecialchars($lang['add_item']); ?>
    </a>
</div>

<!-- Tabel Daftar Barang (Read) -->
<div class="card shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover table-striped align-middle mb-0">
                <thead class="table-dark">
                    <tr>
                        <th class="ps-3">No</th>
                        <th><?= htmlspecialchars($lang['code']); ?></th>
                        <th><?= htmlspecialchars($lang['name']); ?></th>
                        <th><?= htmlspecialchars($lang['category']); ?></th>
                        <th class="text-center"><?= htmlspecialchars($lang['quantity']); ?></th>
                        <th class="text-end"><?= htmlspecialchars($lang['price']); ?></th>
                        <th><?= htmlspecialchars($lang['entry_date']); ?></th>
                        <th class="text-center pe-3"><?= htmlspecialchars($lang['actions']); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result && $result->num_rows > 0): ?>
                        <?php $no = 1; while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td class="ps-3"><?= $no++; ?></td>
                                <td><span class="badge bg-secondary"><?= htmlspecialchars($row['kode_barang']); ?></span></td>
                                <td class="fw-semibold"><?= htmlspecialchars($row['nama_barang']); ?></td>
                                <td><?= htmlspecialchars($row['kategori']); ?></td>
                                <td class="text-center">
                                    <span class="badge <?= $row['jumlah'] > 5 ? 'bg-success' : 'bg-danger'; ?>">
                                        <?= (int)$row['jumlah']; ?>
                                    </span>
                                </td>
                                <td class="text-end">Rp <?= number_format($row['harga_satuan'], 0, ',', '.'); ?></td>
                                <td><?= date('d/m/Y', strtotime($row['tanggal_masuk'])); ?></td>
                                <td class="text-center pe-3">
                                    <div class="btn-group btn-group-sm">
                                        <a href="barang_edit.php?id=<?= $row['id']; ?>" class="btn btn-warning text-white" title="<?= htmlspecialchars($lang['edit']); ?>">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </a>
                                        <!-- Tombol Pemicu Modal Hapus (Menerapkan Metode POST Aman) -->
                                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal<?= $row['id']; ?>" title="<?= htmlspecialchars($lang['delete']); ?>">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </div>

                                    <!-- Modal Konfirmasi Hapus Data -->
                                    <div class="modal fade" id="deleteModal<?= $row['id']; ?>" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header bg-danger text-white">
                                                    <h5 class="modal-title fs-6"><i class="fa-solid fa-triangle-exclamation me-2"></i>Konfirmasi Hapus</h5>
                                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body text-start">
                                                    <?= htmlspecialchars($lang['delete_confirm']); ?><br>
                                                    <strong class="text-danger"><?= htmlspecialchars($row['kode_barang']); ?> - <?= htmlspecialchars($row['nama_barang']); ?></strong>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal"><?= htmlspecialchars($lang['cancel']); ?></button>
                                                    <form action="barang_hapus.php" method="POST" class="d-inline">
                                                        <input type="hidden" name="id" value="<?= $row['id']; ?>">
                                                        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token']; ?>">
                                                        <button type="submit" class="btn btn-sm btn-danger"><?= htmlspecialchars($lang['delete']); ?></button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" class="text-center py-4 text-muted"><?= htmlspecialchars($lang['no_data']); ?></td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>