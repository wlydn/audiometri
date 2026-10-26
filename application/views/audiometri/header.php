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

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome 6 -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <link href="<?= html_escape(base_url('assets/css/workspace.css')) ?>?v=<?= html_escape(@filemtime(FCPATH . 'assets/css/workspace.css')) ?>" rel="stylesheet">
    <link href="<?= html_escape(base_url('assets/css/calm.css')) ?>?v=<?= html_escape(@filemtime(FCPATH . 'assets/css/calm.css')) ?>" rel="stylesheet">
</head>
<body class="workspace">
    <a class="skip-link" href="#main-content">Lewati ke konten utama</a>
    <?php
    $method = $this->router->method;
    $navigation = [
        ['index', '', 'fa-chart-pie', 'Dashboard', ['index']],
        ['create', 'create', 'fa-file-waveform', 'Pemeriksaan Baru', ['create']],
        ['list_tests', 'list_tests', 'fa-table-list', 'Data Pemeriksaan', ['list_tests', 'view', 'edit']],
        ['doctor_view', 'doctor_view', 'fa-user-doctor', 'Panel Review Dokter', ['doctor_view', 'review_dokter']],
    ];
    ?>
    <aside class="app-sidebar" aria-label="Navigasi utama">
        <a class="app-brand" href="<?= html_escape(base_url()) ?>">
            <span class="brand-mark"><i class="fas fa-wave-square" aria-hidden="true"></i></span>
            <div class="brand-text">
                <span class="brand-title">AUDIOMETRI <span class="brand-badge">PRO</span></span>
                <span class="brand-caption">Hearing Diagnostic System</span>
            </div>
        </a>
        <div class="collapse d-lg-block" id="workspace-navigation">
            <div class="nav-section-label">Menu Klinis</div>
            <nav class="app-nav" aria-label="Pemeriksaan">
                <?php foreach ($navigation as $item): $active = in_array($method, $item[4], true); ?>
                    <a href="<?= html_escape(base_url($item[1])) ?>" class="<?= $active ? 'active' : '' ?>" <?= $active ? 'aria-current="page"' : '' ?>>
                        <i class="fas <?= html_escape($item[2]) ?>" aria-hidden="true"></i>
                        <span><?= html_escape($item[3]) ?></span>
                    </a>
                <?php endforeach; ?>
            </nav>

            <div class="nav-section-label">Laporan & Alat</div>
            <nav class="app-nav" aria-label="Laporan">
                <a href="<?= html_escape(base_url('export_excel')) ?>">
                    <i class="fas fa-file-excel" aria-hidden="true"></i>
                    <span>Ekspor Data Excel</span>
                </a>
                <a href="#" onclick="showReportModal(); return false;">
                    <i class="fas fa-filter" aria-hidden="true"></i>
                    <span>Filter Laporan Periode</span>
                </a>
            </nav>
        </div>

        <div class="sidebar-note">
            <div class="sidebar-system-card">
                <span class="sidebar-status-dot"></span>
                <div class="sidebar-system-info">
                    <strong>Workstation Aktif</strong>
                    Modul Diagnostik Terhubung
                </div>
            </div>
        </div>
    </aside>

    <header class="app-topbar">
        <div class="topbar-context">
            <button class="btn btn-outline-secondary menu-toggle" type="button" data-bs-toggle="collapse" data-bs-target="#workspace-navigation" aria-controls="workspace-navigation" aria-expanded="false" aria-label="Buka menu navigasi">
                <i class="fas fa-bars" aria-hidden="true"></i>
            </button>
            <div>
                <small>Klinik & Keselamatan Kerja / Audiometri</small>
                <strong><?= html_escape($title ?? 'Pemeriksaan Pendengaran') ?></strong>
            </div>
        </div>

        <div class="topbar-actions">
            <?php if ($method !== 'create'): ?>
                <a href="<?= html_escape(base_url('create')) ?>" class="btn btn-primary btn-sm d-none d-md-inline-flex">
                    <i class="fas fa-plus"></i> Tes Baru
                </a>
            <?php endif; ?>
            <div class="topbar-date">
                <i class="far fa-calendar-alt" aria-hidden="true"></i>
                <time datetime="<?= date('Y-m-d') ?>"><?= date('d M Y') ?></time>
            </div>
        </div>
    </header>

    <!-- Content Wrapper -->
    <main class="content-wrapper" id="main-content" tabindex="-1">
