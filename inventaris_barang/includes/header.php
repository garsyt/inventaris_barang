<?php
// includes/header.php - Komponen Header, Bootstrap CDN, dan Navbar
require_once __DIR__ . '/auth.php';
?>
<!DOCTYPE html>
<html lang="<?= htmlspecialchars($current_lang); ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($lang['app_title']); ?></title>
    <!-- Bootstrap 5 CSS via CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome untuk Ikon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background-color: #f8f9fa; }
        .navbar-brand { font-weight: 600; }
        .card { border-radius: 10px; border: none; }
    </style>
</head>
<body>

<?php if (isset($_SESSION['user_id'])): ?>
<!-- Navbar Tampil Jika Sudah Login -->
<nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm mb-4">
    <div class="container">
        <a class="navbar-brand" href="index.php">
            <i class="fa-solid fa-boxes-stacked me-2"></i><?= htmlspecialchars($lang['app_title']); ?>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link text-white" href="index.php">
                        <i class="fa-solid fa-gauge me-1"></i><?= htmlspecialchars($lang['dashboard']); ?>
                    </a>
                </li>
            </ul>
            <div class="d-flex align-items-center gap-3">
                <!-- Dropdown Pengenal & Switch Bahasa -->
                <span class="text-white small">
                    <i class="fa-solid fa-user me-1"></i><?= htmlspecialchars($_SESSION['nama_lengkap'] ?? 'Admin'); ?>
                </span>
                
                <div class="btn-group btn-group-sm">
                    <a href="?lang=id" class="btn btn-outline-light <?= $current_lang === 'id' ? 'active' : ''; ?>">ID</a>
                    <a href="?lang=en" class="btn btn-outline-light <?= $current_lang === 'en' ? 'active' : ''; ?>">EN</a>
                </div>

                <a href="logout.php" class="btn btn-sm btn-danger">
                    <i class="fa-solid fa-right-from-bracket me-1"></i><?= htmlspecialchars($lang['logout']); ?>
                </a>
            </div>
        </div>
    </div>
</nav>
<?php endif; ?>

<div class="container pb-5">
    