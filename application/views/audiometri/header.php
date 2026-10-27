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
    <title><?= isset($title) ? html_escape($title) . ' - ' : '' ?>Audiometri Workstation</title>
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

    <!-- Bootstrap 5.3.3 (Bootswatch Flatly) -->
    <link href="https://cdn.jsdelivr.net/npm/bootswatch@5.3.3/dist/flatly/bootstrap.min.css" rel="stylesheet" integrity="sha384-Gn6TIhloBHiLpI1VM8qQG+H8QQhHXqsiUlMLS4uhr9gqQzFsOhMTo0lSTMbOrLoI" crossorigin="anonymous">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet" integrity="sha384-XGjxtQfXaH2tnPFa9x+ruJTuLE3Aa6LhHSWRr1XeTyhezb4abCG4ccI5AkVDxqC+" crossorigin="anonymous">

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.26.25/dist/sweetalert2.all.min.js" integrity="sha384-nLoOnA/BDh8A/jxqtckg4DumuCGOBYUnNJLZdQz/zfYNp3wcjGSoWTAzgko06G/2" crossorigin="anonymous"></script>

    <!-- Theme overrides (must load after Flatly) -->
    <link href="<?= html_escape(base_url('assets/css/custom.css')) ?>?v=<?= html_escape(@filemtime(FCPATH . 'assets/css/custom.css')) ?>" rel="stylesheet">
    <link href="<?= html_escape(base_url('assets/css/calm.css')) ?>?v=<?= html_escape(@filemtime(FCPATH . 'assets/css/calm.css')) ?>" rel="stylesheet">
</head>
<body class="workspace">
    <a class="skip-link" href="#main-content">Lewati ke konten utama</a>
    <?php
    $method = $this->router->method;
    $navigation = [
        ['index', '', 'bi-speedometer2', 'Dashboard', ['index']],
        ['create', 'create', 'bi-file-earmark-plus', 'Pemeriksaan Baru', ['create']],
        ['list_tests', 'list_tests', 'bi-folder2-open', 'Data Pemeriksaan', ['list_tests', 'view', 'edit']],
        ['doctor_view', 'doctor_view', 'bi-clipboard2-pulse', 'Panel Review Dokter', ['doctor_view', 'review_dokter']],
    ];
    ?>
    <!-- Sidebar: offcanvas drawer on mobile, fixed column from lg and up -->
    <aside class="app-sidebar offcanvas-lg offcanvas-start" tabindex="-1" id="workspace-navigation" aria-label="Navigasi utama">
        <div class="offcanvas-header d-lg-none">
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" data-bs-target="#workspace-navigation" aria-label="Tutup menu navigasi"></button>
        </div>
        <div class="offcanvas-body app-sidebar-body">
            <a class="app-brand" href="<?= html_escape(base_url()) ?>">
                <span class="brand-mark"><i class="bi bi-soundwave" aria-hidden="true"></i></span>
                <div class="brand-text">
                    <span class="brand-title">AUDIOMETRI <span class="badge text-bg-light">PRO</span></span>
                    <span class="brand-caption">Hearing Diagnostic System</span>
                </div>
            </a>

            <div class="nav-section-label">Menu Klinis</div>
            <nav class="app-nav" aria-label="Pemeriksaan">
                <?php foreach ($navigation as $item): $active = in_array($method, $item[4], true); ?>
                    <a href="<?= html_escape(base_url($item[1])) ?>" class="<?= $active ? 'active' : '' ?>" <?= $active ? 'aria-current="page"' : '' ?>>
                        <i class="bi <?= html_escape($item[2]) ?>" aria-hidden="true"></i>
                        <span><?= html_escape($item[3]) ?></span>
                    </a>
                <?php endforeach; ?>
            </nav>

            <div class="nav-section-label">Laporan & Alat</div>
            <nav class="app-nav" aria-label="Laporan">
                <a href="<?= html_escape(base_url('export_excel')) ?>">
                    <i class="bi bi-file-earmark-excel" aria-hidden="true"></i>
                    <span>Ekspor Data Excel</span>
                </a>
                <a href="<?= html_escape(base_url('laporan')) ?>" class="<?= $method === 'laporan' ? 'active' : '' ?>" <?= $method === 'laporan' ? 'aria-current="page"' : '' ?>>
                    <i class="bi bi-bar-chart-line" aria-hidden="true"></i>
                    <span>Laporan Periode</span>
                </a>
            </nav>

            <div class="sidebar-note">
                <div class="sidebar-system-card">
                    <span class="sidebar-status-dot"></span>
                    <div class="sidebar-system-info">
                        <strong>Workstation Aktif</strong>
                        Modul Diagnostik Terhubung
                    </div>
                </div>
            </div>
        </div>
    </aside>

    <div class="app-main">
        <header class="app-topbar">
            <div class="topbar-context">
                <button class="btn btn-outline-secondary menu-toggle d-lg-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#workspace-navigation" aria-controls="workspace-navigation" aria-label="Buka menu navigasi">
                    <i class="bi bi-list" aria-hidden="true"></i>
                </button>
                <div class="text-truncate">
                    <small>Web Audiometri</small>
                    <strong><?= html_escape($title ?? 'Pemeriksaan Pendengaran') ?></strong>
                </div>
            </div>

            <div class="topbar-actions d-flex align-items-center gap-2">
                <?php if ($method !== 'create'): ?>
                    <a href="<?= html_escape(base_url('create')) ?>" class="btn btn-primary btn-sm d-none d-md-inline-flex align-items-center gap-1">
                        <i class="bi bi-plus-lg" aria-hidden="true"></i> Tes Baru
                    </a>
                <?php endif; ?>
                <div class="topbar-date d-none d-sm-inline-flex">
                    <i class="bi bi-calendar3" aria-hidden="true"></i>
                    <time datetime="<?= date('Y-m-d') ?>"><?= date('d M Y') ?></time>
                </div>
            </div>
        </header>

        <!-- Content Wrapper -->
        <main class="content-wrapper" id="main-content" tabindex="-1">

