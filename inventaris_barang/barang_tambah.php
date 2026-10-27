<?php
// barang_tambah.php - Create: Tambah Data Barang Baru
require_once __DIR__ . '/includes/auth.php';
check_login();
require_once __DIR__ . '/config/database.php';

$error_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitasi input form
    $kode_barang   = trim($_POST['kode_barang'] ?? '');
    $nama_barang   = trim($_POST['nama_barang'] ?? '');
    $kategori      = trim($_POST['kategori'] ?? '');
    $jumlah        = filter_var($_POST['jumlah'] ?? 0, FILTER_VALIDATE_INT);
    $harga_satuan  = filter_var($_POST['harga_satuan'] ?? 0, FILTER_VALIDATE_FLOAT);
    $tanggal_masuk = trim($_POST['tanggal_masuk'] ?? '');

    // Validasi kelengkapan data menggunakan percabangan if-else
    if (empty($kode_barang) || empty($nama_barang) || empty($kategori) || $jumlah === false || $harga_satuan === false || empty($tanggal_masuk)) {
        $error_message = $lang['error_fill_all'];
    } else {
        // Simpan data menggunakan MySQLi Prepared Statement
        $stmt = $conn->prepare("INSERT INTO barang (kode_barang, nama_barang, kategori, jumlah, harga_satuan, tanggal_masuk) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssids", $kode_barang, $nama_barang, $kategori, $jumlah, $harga_satuan, $tanggal_masuk);

        if ($stmt->execute()) {
            $_SESSION['msg_success'] = $lang['success_add'];
            header("Location: index.php");
            exit();
        } else {
            $error_message = "Gagal menyimpan data: " . $stmt->error;
        }
        $stmt->close();
    }
}

require_once __DIR__ . '/includes/header.php';
?>

<div class="row justify-content-center">
    <div class="col-md-8 col-lg-6">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0 fs-6"><i class="fa-solid fa-plus me-2"></i><?= htmlspecialchars($lang['add_item']); ?></h5>
            </div>
            <div class="card-body p-4">
                <?php if (!empty($error_message)): ?>
                    <div class="alert alert-danger small mb-3"><?= htmlspecialchars($error_message); ?></div>
                <?php endif; ?>

                <form action="barang_tambah.php" method="POST">
                    <div class="mb-3">
                        <label class="form-label small fw-bold"><?= htmlspecialchars($lang['code']); ?></label>
                        <input type="text" name="kode_barang" class="form-control" placeholder="Contoh: BRG-006" value="<?= htmlspecialchars($_POST['kode_barang'] ?? ''); ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold"><?= htmlspecialchars($lang['name']); ?></label>
                        <input type="text" name="nama_barang" class="form-control" value="<?= htmlspecialchars($_POST['nama_barang'] ?? ''); ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold"><?= htmlspecialchars($lang['category']); ?></label>
                        <select name="kategori" class="form-select" required>
                            <option value="">-- Pilih Kategori --</option>
                            <option value="Elektronik">Elektronik</option>
                            <option value="Furnitur">Furnitur</option>
                            <option value="Peralatan Kantor">Peralatan Kantor</option>
                            <option value="Lainnya">Lainnya</option>
                        </select>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label small fw-bold"><?= htmlspecialchars($lang['quantity']); ?></label>
                            <input type="number" name="jumlah" class="form-control" min="0" value="<?= htmlspecialchars($_POST['jumlah'] ?? '0'); ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label small fw-bold"><?= htmlspecialchars($lang['price']); ?></label>
                            <input type="number" step="0.01" name="harga_satuan" class="form-control" min="0" value="<?= htmlspecialchars($_POST['harga_satuan'] ?? '0'); ?>" required>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label small fw-bold"><?= htmlspecialchars($lang['entry_date']); ?></label>
                        <input type="date" name="tanggal_masuk" class="form-control" value="<?= htmlspecialchars($_POST['tanggal_masuk'] ?? date('Y-m-d')); ?>" required>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="index.php" class="btn btn-secondary"><i class="fa-solid fa-arrow-left me-1"></i><?= htmlspecialchars($lang['cancel']); ?></a>
                        <button type="submit" class="btn btn-primary"><i class="fa-solid fa-floppy-disk me-1"></i><?= htmlspecialchars($lang['save']); ?></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>