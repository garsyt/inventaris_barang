<?php
// barang_hapus.php - Delete: Proses Hapus Data Aman Menggunakan POST
require_once __DIR__ . '/includes/auth.php';
check_login();
require_once __DIR__ . '/config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validasi token CSRF untuk mencegah serangan Cross-Site Request Forgery
    $token = $_POST['csrf_token'] ?? '';
    if (!hash_equals($_SESSION['csrf_token'] ?? '', $token)) {
        die("Validasi token CSRF gagal!");
    }

    $id = filter_var($_POST['id'] ?? 0, FILTER_VALIDATE_INT);

    if ($id) {
        $stmt = $conn->prepare("DELETE FROM barang WHERE id = ?");
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            $_SESSION['msg_success'] = $lang['success_delete'];
        }
        $stmt->close();
    }
}

header("Location: index.php");
exit();
?>      