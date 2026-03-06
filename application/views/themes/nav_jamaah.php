<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal Jamaah</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        :root { --primary-color: #0061f2; --bg-color: #f8f9fc; }
        body { background-color: var(--bg-color); font-family: 'Segoe UI', sans-serif; }
        @media (min-width: 992px) {
            .main-content { margin-left: 260px; padding: 40px; }
            .sidebar { width: 260px; height: 100vh; position: fixed; background: white; border-right: 1px solid #e3e6f0; padding: 20px; }
            .bottom-nav { display: none; }
        }
        @media (max-width: 991px) {
            .sidebar { display: none; }
            .main-content { padding: 20px; padding-bottom: 100px; }
            .bottom-nav { position: fixed; bottom: 0; width: 100%; background: white; display: flex; justify-content: space-around; padding: 10px 0; box-shadow: 0 -2px 10px rgba(0,0,0,0.05); border-top: 1px solid #eee; z-index: 1000; }
        }
        .nav-link-custom { color: #858796; text-decoration: none; font-size: 0.8rem; text-align: center; }
        .nav-link-custom.active { color: var(--primary-color); font-weight: bold; }
    </style>
</head>
<body>

    <div class="sidebar shadow-sm">
        <h4 class="fw-bold text-primary mb-5 px-3">Jamaah App</h4>
        <nav class="nav flex-column gap-2">
            <a class="nav-link <?= ($this->uri->segment(2) == 'dashboard') ? 'active btn btn-light text-primary fw-bold' : 'text-dark' ?>" href="<?= site_url('JamaahLinkShare/dashboard') ?>">
                <i class="bi bi-cash me-2"></i> List Transaksi
            </a>
            <a class="nav-link <?= ($this->uri->segment(2) == 'dashboard') ? ' btn btn-light text-primary fw-bold' : 'text-dark' ?>" href="<?= site_url('JamaahLinkShare/dashboard') ?>">
                <i class="bi bi-house-door me-2"></i> Input Transaksi
            </a>


            <a class="nav-link text-dark" href="#"><i class="bi bi-person me-2"></i> Profil Saya</a>
            <hr>
            <a class="nav-link text-danger" href="<?= site_url('JamaahLinkShare/logout_api') ?>"><i class="bi bi-power me-2"></i> Keluar</a>
        </nav>
    </div>

    <div class="bottom-nav">
        <a href="<?= site_url('JamaahLinkShare/dashboard') ?>" class="nav-link-custom <?= ($this->uri->segment(2) == 'dashboard') ? 'active' : '' ?>">
            <i class="bi bi-house-door d-block fs-4"></i> Home
        </a>
        <a href="#" class="nav-link-custom">
            <i class="bi bi-wallet2 d-block fs-4"></i> Tagihan
        </a>
        <a href="#" class="nav-link-custom">
            <i class="bi bi-person d-block fs-4"></i> Profil
        </a>
    </div>
