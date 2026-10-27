<?php
// login.php - Halaman otentikasi masuk sistem
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/config/database.php';

// Jika pengguna sudah login, langsung alihkan ke halaman utama/dashboard
if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$error_message = '';

// Memproses data yang dikirim dari form login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 1. Sanitasi input awal dengan trim()
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    // 2. Validasi kelengkapan form menggunakan percabangan if-else
    if (empty($username) || empty($password)) {
        $error_message = $lang['error_fill_all'];
    } else {
        // 3. Eksekusi query dengan Prepared Statement MySQLi (Mencegah SQL Injection)
        $stmt = $conn->prepare("SELECT id, username, password, nama_lengkap FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($user = $result->fetch_assoc()) {
            // 4. Verifikasi hash password
            if (password_verify($password, $user['password'])) {
                // Regenerasi ID Session demi keamanan dari Session Fixation Attacks
                session_regenerate_id(true);

                $_SESSION['user_id']      = $user['id'];
                $_SESSION['username']     = $user['username'];
                $_SESSION['nama_lengkap'] = $user['nama_lengkap'];

                header("Location: index.php");
                exit();
            } else {
                $error_message = $lang['invalid_login'];
            }
        } else {
            $error_message = $lang['invalid_login'];
        }
        $stmt->close();
    }
}

// Memanggil header HTML (navbar tidak akan muncul karena belum login)
require_once __DIR__ . '/includes/header.php';
?>

<div class="row justify-content-center mt-5">
    <div class="col-md-5 col-lg-4">
        <div class="card shadow-lg">
            <div class="card-header bg-primary text-white text-center py-3">
                <h4 class="mb-0 fs-5"><i class="fa-solid fa-lock me-2"></i><?= htmlspecialchars($lang['login_title']); ?></h4>
            </div>
            <div class="card-body p-4">
                
                <!-- Pesan Error Validasi / Login Gagal -->
                <?php if (!empty($error_message)): ?>
                    <div class="alert alert-danger alert-dismissible fade show small" role="alert">
                        <i class="fa-solid fa-triangle-exclamation me-1"></i><?= htmlspecialchars($error_message); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <!-- Form Login (Metode POST) -->
                <form action="login.php" method="POST">
                    <div class="mb-3">
                        <label for="username" class="form-label text-secondary small fw-bold"><?= htmlspecialchars($lang['username']); ?></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fa-solid fa-user"></i></span>
                            <input type="text" class="form-control" id="username" name="username" value="<?= htmlspecialchars($_POST['username'] ?? ''); ?>" required autofocus>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="password" class="form-label text-secondary small fw-bold"><?= htmlspecialchars($lang['password']); ?></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fa-solid fa-key"></i></span>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 py-2">
                        <i class="fa-solid fa-right-to-bracket me-1"></i><?= htmlspecialchars($lang['btn_login']); ?>
                    </button>
                </form>

            </div>
            
            <!-- Switcher Bahasa di Halaman Login -->
            <div class="card-footer bg-light text-center py-2">
                <span class="small text-muted me-2">Bahasa / Language:</span>
                <div class="btn-group btn-group-sm">
                    <a href="?lang=id" class="btn btn-outline-secondary <?= $current_lang === 'id' ? 'active' : ''; ?>">ID</a>
                    <a href="?lang=en" class="btn btn-outline-secondary <?= $current_lang === 'en' ? 'active' : ''; ?>">EN</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>