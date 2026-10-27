<?php
// includes/auth.php - Memulai session dan memproteksi halaman dari akses tanpa login

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Fitur Penggantian Bahasa (Dual Language)
if (isset($_GET['lang'])) {
    $allowed_langs = ['id', 'en'];
    if (in_array($_GET['lang'], $allowed_langs)) {
        $_SESSION['lang'] = $_GET['lang'];
    }
}

// Atur bahasa default ke Bahasa Indonesia jika belum diset
$current_lang = $_SESSION['lang'] ?? 'id';
$lang_file = __DIR__ . '/../lang/' . $current_lang . '.php';

if (file_exists($lang_file)) {
    $lang = require $lang_file;
} else {
    $lang = require __DIR__ . '/../lang/id.php';
}

// Fungsi proteksi halaman: Wajib login
function check_login() {
    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit();
    }
}
?>