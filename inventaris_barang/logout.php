<?php
// logout.php - Menghancurkan session dan mengalihkan pengguna kembali ke halaman login
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 1. Kosongkan semua variabel session
$_SESSION = array();

// 2. Hapus cookie session dari peramban jika ada
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(), 
        '', 
        time() - 42000,
        $params["path"], 
        $params["domain"],
        $params["secure"], 
        $params["httponly"]
    );
}

// 3. Hancurkan session secara penuh
session_destroy();

// 4. Redirect kembali ke halaman login
header("Location: login.php");
exit();
?>