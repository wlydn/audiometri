<?php
// File: application/views/audiometri/dashboard.php
$pending_count = count(array_filter($recent_tests, function ($test) { return empty($test['impression']); }));
?>
<div class="container-fluid p-3 p-lg-4">
    <!-- Page Heading -->
    <div class="page-heading">
        <div>
            <span class="eyebrow"><i class="bi bi-heart-pulse me-1" aria-hidden="true"></i> Diagnostic Intelligence</span>
            <h1>Ringkasan Audiometri</h1>
            <p>Selamat datang di sistem manajemen pemeriksaan pendengaran kerja dan klinis.</p>
        </div>
        <div class="d-flex flex-wrap gap-2">
            <button type="button" onclick="showSearchModal()" class="btn btn-outline-secondary">
                <i class="bi bi-search me-1" aria-hidden="true"></i> Cari Data
            </button>
            <a href="<?= html_escape(base_url('create')) ?>" class="btn btn-primary">
                <i class="bi bi-plus-lg me-1" aria-hidden="true"></i> Pemeriksaan Baru
            </a>
        </div>
    </div>

    <!-- Metric Cards -->
    <div class="row g-3 mb-4">
        <div class="col-sm-6 col-xl-3">
            <div class="card stat-card h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <span class="stat-label">Total Tes Selesai</span>
                            <p class="stat-value"><?= html_escape(number_format($stats['total_tests'])) ?></p>
                        </div>
                        <div class="stat-icon bg-primary-subtle text-primary">
                            <i class="bi bi-file-earmark-medical" aria-hidden="true"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-transparent d-flex align-items-center justify-content-between small text-body-secondary">
                    <span>Akumulasi seluruh tes</span>
                    <i class="bi bi-graph-up-arrow text-primary" aria-hidden="true"></i>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="card stat-card h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <span class="stat-label">Tes Bulan Ini</span>
                            <p class="stat-value"><?= html_escape(number_format($stats['tests_this_month'])) ?></p>
                        </div>
                        <div class="stat-icon bg-success-subtle text-success">
                            <i class="bi bi-calendar-check" aria-hidden="true"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-transparent d-flex align-items-center justify-content-between small text-body-secondary">
                    <span><?= html_escape(date('F Y')) ?></span>
                    <span class="badge bg-success">Bulan Berjalan</span>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="card stat-card h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <span class="stat-label">Perusahaan Mitra</span>
                            <p class="stat-value"><?= html_escape(count($stats['top_companies'])) ?></p>
                        </div>
                        <div class="stat-icon bg-info-subtle text-info-emphasis">
                            <i class="bi bi-buildings" aria-hidden="true"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-transparent d-flex align-items-center justify-content-between small text-body-secondary">
                    <span>Mitra industri aktif</span>
                    <span>Top 5</span>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="card stat-card h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <span class="stat-label">Menunggu Review</span>
                            <p class="stat-value text-warning-emphasis"><?= html_escape($pending_count) ?></p>
                        </div>
                        <div class="stat-icon bg-warning-subtle text-warning-emphasis">
                            <i class="bi bi-clipboard2-pulse" aria-hidden="true"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-transparent d-flex align-items-center justify-content-between small text-body-secondary">
                    <span>Memerlukan impression dokter</span>
                    <a href="<?= html_escape(base_url('doctor_view')) ?>" class="fw-bold text-decoration-none">Review <i class="bi bi-arrow-right" aria-hidden="true"></i></a>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Recent Tests -->
        <div class="col-lg-8">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h2 class="h6 card-title mb-0">
                        <i class="bi bi-clock-history text-primary me-2" aria-hidden="true"></i>Pemeriksaan Terbaru
                    </h2>
                    <a href="<?= html_escape(base_url('list_tests')) ?>" class="btn btn-sm btn-outline-primary">
                        Lihat Semua <i class="bi bi-chevron-right ms-1" aria-hidden="true"></i>
                    </a>
                </div>
                <div class="card-body p-0">
                    <?php if (!empty($recent_tests)): ?>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
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
                                                <span class="avatar avatar-primary" aria-hidden="true"><?= html_escape(strtoupper(substr($test['nama'] ?? 'P', 0, 1))) ?></span>
                                                <div>
                                                    <strong class="d-block"><?= html_escape($test['nama']) ?></strong>
                                                    <small class="text-body-secondary"><?= html_escape($test['jenis_kelamin'] ?? '-') ?>, <?= html_escape($test['umur'] ?? '-') ?> thn</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge text-bg-light border"><?= html_escape($test['perusahaan']) ?></span>
                                        </td>
                                        <td>
                                            <span class="text-body-secondary fw-semibold"><?= html_escape(date('d M Y', strtotime($test['tanggal_tes']))) ?></span>
                                        </td>
                                        <td>
                                            <?php if (!empty($test['impression'])): ?>
                                                <span class="badge bg-success"><i class="bi bi-check-circle-fill me-1" aria-hidden="true"></i>Selesai</span>
                                            <?php else: ?>
                                                <span class="badge bg-warning text-dark"><i class="bi bi-hourglass-split me-1" aria-hidden="true"></i>Menunggu</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-end text-nowrap">
                                            <a href="<?= html_escape(base_url('view/' . $test['id'])) ?>" class="btn btn-outline-secondary btn-sm" title="Lihat Rekam" aria-label="Lihat Rekam">
                                                <i class="bi bi-eye" aria-hidden="true"></i>
                                            </a>
                                            <a href="<?= html_escape(base_url('edit/' . $test['id'])) ?>" class="btn btn-outline-secondary btn-sm" title="Edit Data" aria-label="Edit Data">
                                                <i class="bi bi-pencil-square" aria-hidden="true"></i>
                                            </a>
                                            <a href="<?= html_escape(base_url('export_pdf/' . $test['id'])) ?>" class="btn btn-outline-secondary btn-sm" title="Unduh PDF" aria-label="Unduh PDF" target="_blank" rel="noopener">
                                                <i class="bi bi-file-earmark-pdf" aria-hidden="true"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-5 empty-state">
                            <i class="bi bi-folder2-open display-4 text-body-secondary" aria-hidden="true"></i>
                            <h3 class="h6 text-body-secondary mt-3">Belum ada pemeriksaan audiometri</h3>
                            <a href="<?= html_escape(base_url('create')) ?>" class="btn btn-primary mt-2">
                                <i class="bi bi-plus-lg me-1" aria-hidden="true"></i> Input Pemeriksaan Pertama
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>


        <!-- Top Companies & Quick Actions -->
        <div class="col-lg-4">
            <div class="card h-100">
                <div class="card-header">
                    <h2 class="h6 card-title mb-0">
                        <i class="bi bi-building text-primary me-2" aria-hidden="true"></i>Rekanan Perusahaan
                    </h2>
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
                                        <span class="fw-semibold small"><?= html_escape($company['perusahaan']) ?></span>
                                        <span class="badge text-bg-light border"><?= html_escape($company['count']) ?> tes</span>
                                    </div>
                                    <div class="progress progress-thin" role="progressbar" aria-label="Porsi tes <?= html_escape($company['perusahaan']) ?>" aria-valuenow="<?= html_escape($percentage) ?>" aria-valuemin="0" aria-valuemax="100">
                                        <div class="progress-bar bg-primary" style="width: <?= html_escape($percentage) ?>%"></div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <p class="text-body-secondary text-center py-4 mb-0">Belum ada data perusahaan</p>
                    <?php endif; ?>

                    <hr class="my-4">

                    <h3 class="stat-label mb-3">Aksi Cepat Sistem</h3>
                    <div class="row g-2">
                        <div class="col-6">
                            <a href="<?= html_escape(base_url('create')) ?>" class="btn btn-outline-primary quick-action">
                                <i class="bi bi-plus-lg" aria-hidden="true"></i>
                                <span>Tes Baru</span>
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="<?= html_escape(base_url('doctor_view')) ?>" class="btn btn-outline-warning quick-action">
                                <i class="bi bi-clipboard2-pulse" aria-hidden="true"></i>
                                <span>Panel Dokter</span>
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="<?= html_escape(base_url('laporan')) ?>" class="btn btn-outline-success quick-action">
                                <i class="bi bi-bar-chart-line" aria-hidden="true"></i>
                                <span>Laporan Periode</span>
                            </a>
                        </div>
                        <div class="col-6">
                            <button type="button" onclick="showSearchModal()" class="btn btn-outline-secondary quick-action">
                                <i class="bi bi-search" aria-hidden="true"></i>
                                <span>Pencarian</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Search Modal -->
<div class="modal fade" id="searchModal" tabindex="-1" aria-labelledby="searchModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="searchModalLabel">
                    <i class="bi bi-search text-primary me-2" aria-hidden="true"></i>Pencarian Cepat
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body">
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
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="bi bi-search me-1" aria-hidden="true"></i> Cari Pasien
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function showSearchModal() {
    bootstrap.Modal.getOrCreateInstance(document.getElementById('searchModal')).show();
}
</script>

