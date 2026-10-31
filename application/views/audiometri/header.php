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
    <meta name="color-scheme" content="light dark">
    <script>
        // Tema: 'light' | 'dark' | 'auto' (ikuti perangkat). Dijalankan sebelum CSS dimuat agar tidak berkedip.
        (function () {
            var KEY = 'audiometri-theme';
            var mq = window.matchMedia ? window.matchMedia('(prefers-color-scheme: dark)') : null;
            var ICONS = { light: 'bi-sun-fill', dark: 'bi-moon-stars-fill', auto: 'bi-circle-half' };

            function stored() {
                try {
                    var v = localStorage.getItem(KEY);
                    return (v === 'light' || v === 'dark') ? v : 'auto';
                } catch (e) {
                    return 'auto';
                }
            }

            function resolve(pref) {
                return pref === 'auto' ? (mq && mq.matches ? 'dark' : 'light') : pref;
            }

            function syncUi(pref) {
                var icon = document.getElementById('theme-toggle-icon');
                if (icon) {
                    icon.className = 'bi ' + ICONS[pref];
                }
                var buttons = document.querySelectorAll('[data-theme-choice]');
                for (var i = 0; i < buttons.length; i++) {
                    var on = buttons[i].getAttribute('data-theme-choice') === pref;
                    buttons[i].classList.toggle('active', on);
                    buttons[i].setAttribute('aria-pressed', on ? 'true' : 'false');
                    var check = buttons[i].querySelector('.theme-check');
                    if (check) {
                        check.classList.toggle('d-none', !on);
                    }
                }
            }

            function apply(pref) {
                var root = document.documentElement;
                root.setAttribute('data-bs-theme', resolve(pref));
                root.setAttribute('data-theme-pref', pref);
                syncUi(pref);
            }

            function set(pref) {
                try {
                    if (pref === 'auto') {
                        localStorage.removeItem(KEY);
                    } else {
                        localStorage.setItem(KEY, pref);
                    }
                } catch (e) { /* penyimpanan diblokir: tema berlaku untuk halaman ini saja */ }
                apply(pref);
            }

            apply(stored());

            // Perangkat ganti tema saat pilihan = auto
            if (mq) {
                var onChange = function () {
                    if (stored() === 'auto') {
                        apply('auto');
                    }
                };
                if (mq.addEventListener) {
                    mq.addEventListener('change', onChange);
                } else if (mq.addListener) {
                    mq.addListener(onChange);
                }
            }

            // Sinkron antar tab
            window.addEventListener('storage', function (e) {
                if (e.key === KEY || e.key === null) {
                    apply(stored());
                }
            });

            document.addEventListener('DOMContentLoaded', function () {
                var buttons = document.querySelectorAll('[data-theme-choice]');
                for (var i = 0; i < buttons.length; i++) {
                    buttons[i].addEventListener('click', function () {
                        set(this.getAttribute('data-theme-choice'));
                    });
                }
                syncUi(stored());
            });

            window.AudiometriTheme = { set: set, get: stored };
        })();
    </script>
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
                <div class="topbar-date" role="timer" aria-label="Jam saat ini (WIB)">
                    <i class="bi bi-clock" aria-hidden="true"></i>
                    <time id="topbar-clock" datetime="<?= date('c') ?>"><?= date('H:i:s') ?></time>
                    <span class="topbar-clock-zone">WIB</span>
                </div>
                <div class="topbar-date d-none d-sm-inline-flex">
                    <i class="bi bi-calendar3" aria-hidden="true"></i>
                    <time datetime="<?= date('Y-m-d') ?>"><?= date('d M Y') ?></time>
                </div>
                <div class="dropdown">
                    <button class="btn btn-outline-secondary theme-toggle" type="button" id="theme-toggle" data-bs-toggle="dropdown" aria-expanded="false" aria-label="Pilih tema tampilan" title="Tema tampilan">
                        <i class="bi bi-circle-half" id="theme-toggle-icon" aria-hidden="true"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="theme-toggle">
                        <li>
                            <button type="button" class="dropdown-item d-flex align-items-center gap-2" data-theme-choice="light" aria-pressed="false">
                                <i class="bi bi-sun-fill" aria-hidden="true"></i> Terang
                                <i class="bi bi-check2 ms-auto theme-check d-none" aria-hidden="true"></i>
                            </button>
                        </li>
                        <li>
                            <button type="button" class="dropdown-item d-flex align-items-center gap-2" data-theme-choice="dark" aria-pressed="false">
                                <i class="bi bi-moon-stars-fill" aria-hidden="true"></i> Gelap
                                <i class="bi bi-check2 ms-auto theme-check d-none" aria-hidden="true"></i>
                            </button>
                        </li>
                        <li>
                            <button type="button" class="dropdown-item d-flex align-items-center gap-2" data-theme-choice="auto" aria-pressed="false">
                                <i class="bi bi-circle-half" aria-hidden="true"></i> Ikuti perangkat
                                <i class="bi bi-check2 ms-auto theme-check d-none" aria-hidden="true"></i>
                            </button>
                        </li>
                    </ul>
                </div>
            </div>
        </header>

        <!-- Content Wrapper -->
        <main class="content-wrapper" id="main-content" tabindex="-1">

