<?php
// File: application/views/audiometri/dashboard.php
$pending_count = count(array_filter($recent_tests, function ($test) { return empty($test['impression']); }));
?>
<div class="container-fluid p-4">
    <!-- Hero Banner / Page Heading -->
    <div class="page-heading">
        <div>
            <span class="eyebrow"><i class="fas fa-stethoscope me-1"></i> Diagnostic Intelligence</span>
            <h1>Ringkasan Audiometri</h1>
            <p>Selamat datang di sistem manajemen pemeriksaan pendengaran kerja dan klinis.</p>
        </div>
        <div class="d-flex gap-2">
            <button onclick="showSearchModal()" class="btn btn-secondary">
                <i class="fas fa-magnifying-glass"></i> Cari Data
            </button>
            <a href="<?= html_escape(base_url('create')) ?>" class="btn btn-primary">
                <i class="fas fa-plus"></i> Pemeriksaan Baru
            </a>
        </div>
    </div>

    <!-- 4 High-End Metric Cards -->
    <div class="row g-3 mb-4">
        <!-- Metric 1: Total Tests -->
        <div class="col-sm-6 col-xl-3">
            <div class="card stat-card h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <span class="text-muted fw-bold text-uppercase" style="font-size: 11px; letter-spacing: 0.5px;">Total Tes Selesai</span>
                            <h3 class="card-title mt-1 mb-0"><?= html_escape(number_format($stats['total_tests'])) ?></h3>
                        </div>
                        <div class="stat-icon" style="background: #eff6ff; color: #2563eb;">
                            <i class="fas fa-file-waveform"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <span class="text-muted" style="font-size: 11.5px;">Akumulasi seluruh tes</span>
                    <i class="fas fa-arrow-trend-up text-primary" style="font-size: 12px;"></i>
                </div>
            </div>
        </div>

        <!-- Metric 2: Tests This Month -->
        <div class="col-sm-6 col-xl-3">
            <div class="card stat-card h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <span class="text-muted fw-bold text-uppercase" style="font-size: 11px; letter-spacing: 0.5px;">Tes Bulan Ini</span>
                            <h3 class="card-title mt-1 mb-0"><?= html_escape(number_format($stats['tests_this_month'])) ?></h3>
                        </div>
                        <div class="stat-icon" style="background: #ecfdf5; color: #10b981;">
                            <i class="fas fa-calendar-check"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <span class="text-muted" style="font-size: 11.5px;"><?= html_escape(date('F Y')) ?></span>
                    <span class="badge bg-success" style="font-size: 10px;">Bulan Berjalan</span>
                </div>
            </div>
        </div>

        <!-- Metric 3: Top Companies -->
        <div class="col-sm-6 col-xl-3">
            <div class="card stat-card h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <span class="text-muted fw-bold text-uppercase" style="font-size: 11px; letter-spacing: 0.5px;">Perusahaan Mitra</span>
                            <h3 class="card-title mt-1 mb-0"><?= html_escape(count($stats['top_companies'])) ?></h3>
                        </div>
                        <div class="stat-icon" style="background: #f5f3ff; color: #7c3aed;">
                            <i class="fas fa-building-user"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <span class="text-muted" style="font-size: 11.5px;">Mitra industri aktif</span>
                    <span class="text-muted" style="font-size: 11.5px;">Top 5</span>
                </div>
            </div>
        </div>

        <!-- Metric 4: Pending Doctor Reviews -->
        <div class="col-sm-6 col-xl-3">
            <div class="card stat-card h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <span class="text-muted fw-bold text-uppercase" style="font-size: 11px; letter-spacing: 0.5px;">Menunggu Review</span>
                            <h3 class="card-title mt-1 mb-0 text-warning"><?= html_escape($pending_count) ?></h3>
                        </div>
                        <div class="stat-icon" style="background: #fffbeb; color: #d97706;">
                            <i class="fas fa-user-doctor"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <span class="text-muted" style="font-size: 11.5px;">Memerlukan impression dokter</span>
                    <a href="<?= html_escape(base_url('doctor_view')) ?>" class="text-warning fw-bold" style="font-size: 11.5px;">Review <i class="fas fa-arrow-right"></i></a>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Recent Tests Section -->
        <div class="col-lg-8">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-clock-rotate-left text-primary"></i>
                        <span>Pemeriksaan Terbaru</span>
                    </h5>
                    <a href="<?= html_escape(base_url('list_tests')) ?>" class="btn btn-sm btn-outline-primary">
                        Lihat Semua <i class="fas fa-chevron-right ms-1"></i>
                    </a>
                </div>
                <div class="card-body p-0">
                    <?php if (!empty($recent_tests)): ?>
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th>Pasien</th>
                                        <th>Perusahaan</th>
                                        <th>Tanggal Tes</th>
                                        <th>Review Dokter</th>
                                        <th class="text-end">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($recent_tests as $test): ?>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <div style="width: 32px; height: 32px; border-radius: 50%; background: #eff6ff; color: #2563eb; display: grid; place-items: center; font-weight: 700; font-size: 12px; flex-shrink: 0;">
                                                    <?= html_escape(strtoupper(substr($test['nama'] ?? 'P', 0, 1))) ?>
                                                </div>
                                                <div>
                                                    <strong class="d-block text-dark"><?= html_escape($test['nama']) ?></strong>
                                                    <small class="text-muted"><?= html_escape($test['jenis_kelamin'] ?? '-') ?>, <?= html_escape($test['umur'] ?? '-') ?> thn</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-light text-dark border"><?= html_escape($test['perusahaan']) ?></span>
                                        </td>
                                        <td>
                                            <span class="text-secondary fw-semibold"><?= html_escape(date('d M Y', strtotime($test['tanggal_tes']))) ?></span>
                                        </td>
                                        <td>
                                            <?php if (!empty($test['impression'])): ?>
                                                <span class="badge bg-success">
                                                    <i class="fas fa-circle-check"></i> Selesai
                                                </span>
                                            <?php else: ?>
                                                <span class="badge bg-warning">
                                                    <i class="fas fa-hourglass-half"></i> Menunggu
                                                </span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-end">
                                            <div class="btn-group btn-group-sm">
                                                <a href="<?= html_escape(base_url('view/' . $test['id'])) ?>" class="btn btn-outline-primary" title="Lihat Rekam">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="<?= html_escape(base_url('edit/' . $test['id'])) ?>" class="btn btn-outline-secondary" title="Edit Data">
                                                    <i class="fas fa-pen-to-square"></i>
                                                </a>
                                                <a href="<?= html_escape(base_url('export_pdf/' . $test['id'])) ?>" class="btn btn-outline-info" title="Unduh PDF" target="_blank">
                                                    <i class="fas fa-file-pdf"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-5">
                            <i class="fas fa-folder-open fa-3x text-muted mb-3"></i>
                            <h6 class="text-muted">Belum ada pemeriksaan audiometri</h6>
                            <a href="<?= html_escape(base_url('create')) ?>" class="btn btn-primary mt-2">
                                <i class="fas fa-plus me-1"></i> Input Pemeriksaan Pertama
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Top Companies & Actions Section -->
        <div class="col-lg-4">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-building text-primary"></i>
                        <span>Rekanan Perusahaan</span>
                    </h5>
                </div>
                <div class="card-body">
                    <?php if (!empty($stats['top_companies'])): ?>
                        <div class="d-flex flex-column gap-3">
                            <?php foreach ($stats['top_companies'] as $company): ?>
                                <?php
                                $total = !empty($stats['total_tests']) ? (int) $stats['total_tests'] : 1;
                                $percentage = round(($company['count'] / $total) * 100);
                                ?>
                                <div>
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <span class="fw-bold text-dark font-monospace" style="font-size: 13px;"><?= html_escape($company['perusahaan']) ?></span>
                                        <span class="badge bg-light text-primary border fw-bold"><?= html_escape($company['count']) ?> tes</span>
                                    </div>
                                    <div class="progress" style="height: 6px; border-radius: 9999px; background: #e2e8f0;">
                                        <div class="progress-bar bg-primary" role="progressbar" style="width: <?= html_escape($percentage) ?>%; border-radius: 9999px;"></div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <p class="text-muted text-center py-4 mb-0">Belum ada data perusahaan</p>
                    <?php endif; ?>

                    <hr class="my-4 text-muted">

                    <h6 class="fw-bold mb-3 text-secondary text-uppercase" style="font-size: 11px; letter-spacing: 0.8px;">Aksi Cepat Sistem</h6>
                    <div class="row g-2">
                        <div class="col-6">
                            <a href="<?= html_escape(base_url('create')) ?>" class="btn btn-outline-primary w-100 py-3 text-center d-flex flex-column align-items-center justify-content-center">
                                <i class="fas fa-plus mb-1" style="font-size: 18px;"></i>
                                <span style="font-size: 12px;">Tes Baru</span>
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="<?= html_escape(base_url('doctor_view')) ?>" class="btn btn-outline-warning w-100 py-3 text-center d-flex flex-column align-items-center justify-content-center">
                                <i class="fas fa-user-doctor mb-1" style="font-size: 18px;"></i>
                                <span style="font-size: 12px;">Panel Dokter</span>
                            </a>
                        </div>
                        <div class="col-6">
                            <button onclick="showReportModal()" class="btn btn-outline-success w-100 py-3 text-center d-flex flex-column align-items-center justify-content-center">
                                <i class="fas fa-file-excel mb-1" style="font-size: 18px;"></i>
                                <span style="font-size: 12px;">Ekspor Data</span>
                            </button>
                        </div>
                        <div class="col-6">
                            <button onclick="showSearchModal()" class="btn btn-outline-secondary w-100 py-3 text-center d-flex flex-column align-items-center justify-content-center">
                                <i class="fas fa-magnifying-glass mb-1" style="font-size: 18px;"></i>
                                <span style="font-size: 12px;">Pencarian</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Search Modal -->
<div class="modal fade" id="searchModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">
                    <i class="fas fa-magnifying-glass text-primary me-2"></i>Pencarian Cepat
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body p-4">
                <form action="<?= html_escape(base_url('list_tests')) ?>" method="get">
                    <div class="mb-3">
                        <label for="search_nama" class="form-label">Nama Pasien</label>
                        <input type="text" class="form-control" id="search_nama" name="nama"
                               placeholder="Masukkan nama pasien...">
                    </div>
                    <div class="mb-4">
                        <label for="search_perusahaan" class="form-label">Perusahaan</label>
                        <input type="text" class="form-control" id="search_perusahaan" name="perusahaan"
                               placeholder="Masukkan nama perusahaan...">
                    </div>
                    <div class="d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="fas fa-magnifying-glass me-1"></i> Cari Pasien
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function showSearchModal() {
    new bootstrap.Modal(document.getElementById('searchModal')).show();
}
</script>
