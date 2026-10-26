<?php
// File: application/views/audiometri/header.php
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-name" content="<?= html_escape($this->security->get_csrf_token_name()) ?>">
    <meta name="csrf-hash" content="<?= html_escape($this->security->get_csrf_hash()) ?>">
    <title><?= isset($title) ? html_escape($title) . ' - ' : '' ?>Audiometri</title>
    <script>
        // Adds the CodeIgniter CSRF token to a FormData/URLSearchParams body.
        function withCsrf(body) {
            body = body || new FormData();
            var name = document.querySelector('meta[name="csrf-name"]').content;
            var hash = document.querySelector('meta[name="csrf-hash"]').content;
            if (!body.has(name)) {
                body.append(name, hash);
            }
            return body;
        }
    </script>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="<?= html_escape(base_url('assets/images/logo/logo-kop.png')) ?>">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<link href="<?= html_escape(base_url('assets/css/workspace.css')) ?>?v=<?= html_escape(@filemtime(FCPATH . 'assets/css/workspace.css')) ?>" rel="stylesheet">
</head>
<body class="workspace">
    <a class="skip-link" href="#main-content">Lewati ke konten</a>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-custom">
        <div class="container-fluid">
            <a class="navbar-brand" href="<?= html_escape(base_url('audiometri')) ?>">
                <span class="brand-mark"><i class="fas fa-headphones"></i></span>
                <span>Audiometri<small class="brand-caption">Pemeriksaan pendengaran</small></span>
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Buka menu navigasi">
                <i class="fas fa-bars" aria-hidden="true"></i>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link <?= html_escape(($this->router->method == 'index') ? 'active' : '') ?>"
                           href="<?= html_escape(base_url('audiometri')) ?>">
                            <i class="fas fa-home me-1"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= html_escape(($this->router->method == 'create') ? 'active' : '') ?>"
                           href="<?= html_escape(base_url('audiometri/create')) ?>">
                            <i class="fas fa-plus me-1"></i> Tes Baru
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= html_escape(($this->router->method == 'list_tests') ? 'active' : '') ?>"
                           href="<?= html_escape(base_url('audiometri/list_tests')) ?>">
                            <i class="fas fa-list me-1"></i> Daftar Tes
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= html_escape(($this->router->method == 'doctor_view') ? 'active' : '') ?>"
                           href="<?= html_escape(base_url('audiometri/doctor_view')) ?>">
                            <i class="fa-solid fa-user-doctor"></i> Panel Dokter
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-chart-bar me-1"></i> Laporan
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="<?= html_escape(base_url('audiometri/export_excel')) ?>">
                                <i class="fas fa-file-excel me-1"></i> Export Excel
                            </a></li>
                            <li><a class="dropdown-item" href="#" onclick="showReportModal()">
                                <i class="fas fa-chart-pie me-1"></i> Laporan Statistik
                            </a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Content Wrapper -->
    <main class="content-wrapper" id="main-content" tabindex="-1">
