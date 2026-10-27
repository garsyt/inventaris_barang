<?php
// barang_edit.php - Update: Edit Data Barang Berdasarkan ID
require_once __DIR__ . '/includes/auth.php';
check_login();
require_once __DIR__ . '/config/database.php';

$id = filter_var($_GET['id'] ?? 0, FILTER_VALIDATE_INT);
if (!$id) {
    header("Location: index.php");
    exit();
}

$error_message = '';

// Mengambil data lama berdasarkan ID (Prepared Statement)
$stmt = $conn->prepare("SELECT * FROM barang WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$barang = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$barang) {
    header("Location: index.php");
    exit();
}

// Memproses pembaruan data
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $kode_barang   = trim($_POST['kode_barang'] ?? '');
    $nama_barang   = trim($_POST['nama_barang'] ?? '');
    $kategori      = trim($_POST['kategori'] ?? '');
    $jumlah        = filter_var($_POST['jumlah'] ?? 0, FILTER_VALIDATE_INT);
    $harga_satuan  = filter_var($_POST['harga_satuan'] ?? 0, FILTER_VALIDATE_FLOAT);
    $tanggal_masuk = trim($_POST['tanggal_masuk'] ?? '');

    if (empty($kode_barang) || empty($nama_barang) || empty($kategori) || $jumlah === false || $harga_satuan === false || empty($tanggal_masuk)) {
        $error_message = $lang['error_fill_all'];
    } else {
        $update_stmt = $conn->prepare("UPDATE barang SET kode_barang = ?, nama_barang = ?, kategori = ?, jumlah = ?, harga_satuan = ?, tanggal_masuk = ? WHERE id = ?");
        $update_stmt->bind_param("sssidsi", $kode_barang, $nama_barang, $kategori, $jumlah, $harga_satuan, $tanggal_masuk, $id);

        if ($update_stmt->execute()) {
            $_SESSION['msg_success'] = $lang['success_edit'];
            header("Location: index.php");
            exit();
        } else {
            $error_message = "Gagal memperbarui data: " . $update_stmt->error;
        }
        $update_stmt->close();
    }
}

require_once __DIR__ . '/includes/header.php';
?>

<div class="row justify-content-center">
    <div class="col-md-8 col-lg-6">
        <div class="card shadow-sm">
            <div class="card-header bg-warning text-white">
                <h5 class="mb-0 fs-6"><i class="fa-solid fa-pen-to-square me-2"></i><?= htmlspecialchars($lang['edit_item']); ?></h5>
            </div>
            <div class="card-body p-4">
                <?php if (!empty($error_message)): ?>
                    <div class="alert alert-danger small mb-3"><?= htmlspecialchars($error_message); ?></div>
                <?php endif; ?>

                <form action="barang_edit.php?id=<?= $id; ?>" method="POST">
                    <div class="mb-3">
                        <label class="form-label small fw-bold"><?= htmlspecialchars($lang['code']); ?></label>
                        <input type="text" name="kode_barang" class="form-control" value="<?= htmlspecialchars($barang['kode_barang']); ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold"><?= htmlspecialchars($lang['name']); ?></label>
                        <input type="text" name="nama_barang" class="form-control" value="<?= htmlspecialchars($barang['nama_barang']); ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold"><?= htmlspecialchars($lang['category']); ?></label>
                        <select name="kategori" class="form-select" required>
                            <?php 
                            $kategori_list = ['Elektronik', 'Furnitur', 'Peralatan Kantor', 'Lainnya'];
                            foreach ($kategori_list as $kat):
                            ?>
                                <option value="<?= $kat; ?>" <?= $barang['kategori'] === $kat ? 'selected' : ''; ?>><?= $kat; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label small fw-bold"><?= htmlspecialchars($lang['quantity']); ?></label>
                            <input type="number" name="jumlah" class="form-control" min="0" value="<?= htmlspecialchars($barang['jumlah']); ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label small fw-bold"><?= htmlspecialchars($lang['price']); ?></label>
                            <input type="number" step="0.01" name="harga_satuan" class="form-control" min="0" value="<?= htmlspecialchars($barang['harga_satuan']); ?>" required>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label small fw-bold"><?= htmlspecialchars($lang['entry_date']); ?></label>
                        <input type="date" name="tanggal_masuk" class="form-control" value="<?= htmlspecialchars($barang['tanggal_masuk']); ?>" required>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="index.php" class="btn btn-secondary"><i class="fa-solid fa-arrow-left me-1"></i><?= htmlspecialchars($lang['cancel']); ?></a>
                        <button type="submit" class="btn btn-warning text-white"><i class="fa-solid fa-rotate me-1"></i><?= htmlspecialchars($lang['update']); ?></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>