<?php
// File: application/views/audiometri/dashboard.php
?>
<div class="container-fluid p-4">
    <div class="page-heading">
        <div>
            <span class="eyebrow">Ruang pemeriksaan</span>
            <h1>Ringkasan audiometri</h1>
            <p>Pantau pemeriksaan terbaru dan kelola hasil tes pasien.</p>
        </div>
        <a href="<?= html_escape(base_url('audiometri/create')) ?>" class="btn btn-primary">
            <i class="fas fa-plus me-2" aria-hidden="true"></i> Pemeriksaan baru
        </a>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card stat-card h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="card-title"><?= html_escape(number_format($stats['total_tests'])) ?></h4>
                            <p class="card-text">Total Tes</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-clipboard-list fa-2x"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-primary border-0">
                    <small>Sejak sistem berjalan</small>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card stat-card h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="card-title"><?= html_escape(number_format($stats['tests_this_month'])) ?></h4>
                            <p class="card-text">Tes Bulan Ini</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-calendar-days fa-2x"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-success border-0">
                    <small><?= html_escape(date('F Y')) ?></small>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card stat-card h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="card-title"><?= html_escape(count($stats['top_companies'])) ?></h4>
                            <p class="card-text">Perusahaan teratas</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-building fa-2x"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-info border-0">
                    <small>Maksimal lima perusahaan teratas</small>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card stat-card h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="stat-label">Tindak lanjut</span>
                            <h4 class="card-title"><?= html_escape(count(array_filter($recent_tests, function ($test) { return empty($test['impression']); }))) ?></h4>
                            <p class="card-text">Menunggu review dokter</p>
                        </div>
                        <span class="stat-icon"><i class="fas fa-clipboard-check" aria-hidden="true"></i></span>
                    </div>
                </div>
                <div class="card-footer border-0"><small>Dari <?= html_escape(count($recent_tests)) ?> pemeriksaan terbaru</small></div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Recent Tests -->
        <div class="col-md-8 mb-4">
            <div class="card h-100">
                <div class="card-header bg-light">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-history me-2"></i>
                        Tes Terbaru
                    </h5>
                </div>
                <div class="card-body">
                    <?php if (!empty($recent_tests)): ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>Nama</th>
                                        <th>Perusahaan</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($recent_tests as $test): ?>
                                    <tr>
                                        <td><?= html_escape(date('d/m/Y', strtotime($test['tanggal_tes']))) ?></td>
                                        <td><?= html_escape($test['nama']) ?></td>
                                        <td><?= html_escape($test['perusahaan']) ?></td>
                                        <td>
                                            <?php
                                            $impression = $test['impression'];
                                            if (!empty($impression)) {
                                                echo '<span class="badge bg-success">Sudah Direview</span>';
                                            } else {
                                                echo '<span class="badge bg-danger">Belum Direview</span>';
                                            }
                                            ?>
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <a href="<?= html_escape(base_url('audiometri/view/'.$test['id'])) ?>"
                                                   class="btn btn-outline-primary" title="Lihat Detail">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="<?= html_escape(base_url('audiometri/edit/'.$test['id'])) ?>"
                                                   class="btn btn-outline-secondary" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <a href="<?= html_escape(base_url('audiometri/export_pdf/'.$test['id'])) ?>"
                                                   class="btn btn-outline-info" title="Export PDF" target="_blank">
                                                    <i class="fas fa-file-pdf"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="text-center mt-3">
                            <a href="<?= html_escape(base_url('audiometri/list_tests')) ?>" class="btn btn-primary">
                                Lihat Semua Tes <i class="fas fa-arrow-right ms-1"></i>
                            </a>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-4">
                            <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Belum ada tes yang dilakukan</p>
                            <a href="<?= html_escape(base_url('audiometri/create')) ?>" class="btn btn-primary">
                                Buat Tes Pertama
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Top Companies -->
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <div class="card-header bg-light">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-building me-2"></i>
                        Perusahaan Teratas
                    </h5>
                </div>
                <div class="card-body">
                    <?php if (!empty($stats['top_companies'])): ?>
                        <?php foreach ($stats['top_companies'] as $company): ?>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <h6 class="mb-0"><?= html_escape($company['perusahaan']) ?></h6>
                                <small class="text-muted"><?= html_escape($company['count']) ?> tes</small>
                            </div>
                            <div class="progress" style="width: 60px; height: 20px;">
                                <?php
                                $percentage = ($company['count'] / $stats['total_tests']) * 100;
                                ?>
                                <div class="progress-bar bg-primary" style="width: <?= html_escape(max(0, min(100, (float) $percentage))) ?>%"></div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="text-muted text-center">Belum ada data perusahaan</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row mt-4 quick-actions">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-light">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-bolt me-2"></i>
                        Aksi Cepat
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-3 mb-3">
                            <a href="<?= html_escape(base_url('audiometri/create')) ?>" class="btn btn-lg btn-primary w-100">
                                <i class="fas fa-plus fa-2x mb-2"></i><br>
                                Tes Baru
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="<?= html_escape(base_url('audiometri/list_tests')) ?>" class="btn btn-lg btn-secondary w-100">
                                <i class="fas fa-list fa-2x mb-2"></i><br>
                                Daftar Tes
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <button onclick="showReportModal()" class="btn btn-lg btn-success w-100">
                                <i class="fas fa-file-excel fa-2x mb-2"></i><br>
                                Export Excel
                            </button>
                        </div>
                        <div class="col-md-3 mb-3">
                            <button onclick="showSearchModal()" class="btn btn-lg btn-info w-100">
                                <i class="fas fa-search fa-2x mb-2"></i><br>
                                Cari Data
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
                <h5 class="modal-title">Pencarian Cepat</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form action="<?= html_escape(base_url('audiometri/list_tests')) ?>" method="get">
                    <div class="mb-3">
                        <label for="search_nama" class="form-label">Nama Pasien</label>
                        <input type="text" class="form-control" id="search_nama" name="nama"
                               placeholder="Masukkan nama pasien">
                    </div>
                    <div class="mb-3">
                        <label for="search_perusahaan" class="form-label">Perusahaan</label>
                        <input type="text" class="form-control" id="search_perusahaan" name="perusahaan"
                               placeholder="Masukkan nama perusahaan">
                    </div>
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search me-1"></i> Cari
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
