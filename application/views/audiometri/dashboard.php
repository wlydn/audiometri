<?php
// File: application/views/audiometri/dashboard.php
$month_report = $overview['month_report'];
$month_total = (int) $month_report['total'];
$measured = $month_total - (int) $month_report['no_data'];
$pct = function ($part, $whole) {
    return $whole > 0 ? round(($part / $whole) * 100) : 0;
};

$delta = (int) $overview['month_count'] - (int) $overview['prev_count'];
if ($overview['prev_count'] > 0) {
    $delta_text = ($delta >= 0 ? '+' : '') . round(($delta / $overview['prev_count']) * 100) . '% dari bulan lalu';
} else {
    $delta_text = $overview['month_count'] > 0 ? 'Belum ada data bulan lalu' : 'Belum ada tes bulan ini';
}
$delta_class = $delta > 0 ? 'text-success' : ($delta < 0 ? 'text-danger' : 'text-body-secondary');

$level_meta = [
    'Normal' => ['Normal', 'bg-success'],
    'Mild hearing loss' => ['Gangguan Ringan', 'bg-info'],
    'Moderate hearing loss' => ['Gangguan Sedang', 'bg-warning'],
    'Moderately severe hearing loss' => ['Sedang - Berat', 'bg-warning'],
    'Severe hearing loss' => ['Gangguan Berat', 'bg-danger'],
    'Profound hearing loss' => ['Sangat Berat', 'bg-danger'],
];
$trend_max = !empty($overview['trend']) ? max($overview['trend']) : 0;
$month_query = http_build_query(['tanggal_dari' => date('Y-m-01'), 'tanggal_sampai' => date('Y-m-t')]);
?>
<div class="container-fluid p-3 p-lg-4">
    <div class="page-heading">
        <div>
            <span class="eyebrow"><i class="bi bi-heart-pulse me-1" aria-hidden="true"></i> Ringkasan</span>
            <h1>Dashboard Audiometri</h1>
            <p>Kondisi pemeriksaan hari ini dan bulan <?= html_escape(date('F Y')) ?>.</p>
        </div>
        <div class="d-flex flex-wrap gap-2">
            <button type="button" onclick="showSearchModal()" class="btn btn-outline-secondary">
                <i class="bi bi-search me-1" aria-hidden="true"></i> Cari
            </button>
            <a href="<?= html_escape(base_url('laporan') . '?' . $month_query) ?>" class="btn btn-outline-success">
                <i class="bi bi-bar-chart-line me-1" aria-hidden="true"></i> Laporan
            </a>
            <a href="<?= html_escape(base_url('create')) ?>" class="btn btn-primary">
                <i class="bi bi-plus-lg me-1" aria-hidden="true"></i> Pemeriksaan Baru
            </a>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-sm-6 col-xl-3">
            <div class="card stat-card h-100">
                <div class="card-body d-flex justify-content-between align-items-start">
                    <div>
                        <span class="stat-label">Hari Ini</span>
                        <p class="stat-value"><?= html_escape(number_format($overview['today'])) ?></p>
                    </div>
                    <div class="stat-icon bg-primary-subtle text-primary"><i class="bi bi-calendar-day" aria-hidden="true"></i></div>
                </div>
                <div class="card-footer bg-transparent small text-body-secondary"><?= html_escape(date('d M Y')) ?></div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card stat-card h-100">
                <div class="card-body d-flex justify-content-between align-items-start">
                    <div>
                        <span class="stat-label">Bulan Ini</span>
                        <p class="stat-value"><?= html_escape(number_format($overview['month_count'])) ?></p>
                    </div>
                    <div class="stat-icon bg-success-subtle text-success"><i class="bi bi-calendar-check" aria-hidden="true"></i></div>
                </div>
                <div class="card-footer bg-transparent small <?= html_escape($delta_class) ?>"><?= html_escape($delta_text) ?></div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card stat-card h-100">
                <div class="card-body d-flex justify-content-between align-items-start">
                    <div>
                        <span class="stat-label">Perlu Perhatian</span>
                        <p class="stat-value text-warning-emphasis"><?= html_escape(number_format($month_report['abnormal'])) ?></p>
                    </div>
                    <div class="stat-icon bg-warning-subtle text-warning-emphasis"><i class="bi bi-exclamation-triangle" aria-hidden="true"></i></div>
                </div>
                <div class="card-footer bg-transparent small text-body-secondary">
                    <?= html_escape($pct($month_report['abnormal'], $measured)) ?>% dari <?= html_escape($measured) ?> tes terukur bulan ini
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card stat-card h-100">
                <div class="card-body d-flex justify-content-between align-items-start">
                    <div>
                        <span class="stat-label">Menunggu Review</span>
                        <p class="stat-value"><?= html_escape(number_format($overview['pending'])) ?></p>
                    </div>
                    <div class="stat-icon bg-info-subtle text-info-emphasis"><i class="bi bi-clipboard2-pulse" aria-hidden="true"></i></div>
                </div>
                <div class="card-footer bg-transparent d-flex justify-content-between small text-body-secondary">
                    <span>Total <?= html_escape(number_format($overview['total'])) ?> tes</span>
                    <a href="<?= html_escape(base_url('doctor_view')) ?>" class="fw-bold text-decoration-none">Review <i class="bi bi-arrow-right" aria-hidden="true"></i></a>
                </div>
            </div>
        </div>
    </div>
    <div class="row g-4 mb-4">
        <div class="col-lg-5">
            <div class="card h-100">
                <div class="card-header">
                    <h2 class="h6 card-title mb-0"><i class="bi bi-ear text-primary me-2" aria-hidden="true"></i>Tingkat Pendengaran Bulan Ini</h2>
                </div>
                <div class="card-body">
                    <?php if ($month_total > 0): ?>
                        <?php foreach ($month_report['levels'] as $level => $count):
                            $meta = isset($level_meta[$level]) ? $level_meta[$level] : [$level, 'bg-secondary'];
                            $percent = $pct($count, $month_total);
                        ?>
                            <div class="mb-3">
                                <div class="d-flex justify-content-between small mb-1">
                                    <span class="fw-semibold"><?= html_escape($meta[0]) ?></span>
                                    <span class="text-body-secondary"><?= html_escape($count) ?> (<?= html_escape($percent) ?>%)</span>
                                </div>
                                <div class="progress progress-thin" role="progressbar" aria-label="<?= html_escape($meta[0]) ?>" aria-valuenow="<?= html_escape($percent) ?>" aria-valuemin="0" aria-valuemax="100">
                                    <div class="progress-bar <?= html_escape($meta[1]) ?>" style="width: <?= html_escape($percent) ?>%"></div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                        <?php if ($month_report['no_data'] > 0): ?>
                            <p class="small text-body-secondary mb-0"><i class="bi bi-info-circle me-1" aria-hidden="true"></i><?= html_escape($month_report['no_data']) ?> tes belum memiliki data AC.</p>
                        <?php endif; ?>
                    <?php else: ?>
                        <div class="text-center py-4 empty-state">
                            <i class="bi bi-ear display-5 text-body-secondary" aria-hidden="true"></i>
                            <p class="mt-3 mb-0 text-body-secondary">Belum ada pemeriksaan bulan ini.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="col-lg-7">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h2 class="h6 card-title mb-0"><i class="bi bi-graph-up text-primary me-2" aria-hidden="true"></i>Pemeriksaan 6 Bulan Terakhir</h2>
                    <a href="<?= html_escape(base_url('laporan') . '?' . http_build_query(['tanggal_dari' => date('Y-m-01', strtotime('first day of -5 months')), 'tanggal_sampai' => date('Y-m-t')])) ?>" class="btn btn-sm btn-outline-primary">Detail</a>
                </div>
                <div class="card-body">
                    <div class="trend-chart" role="img" aria-label="Grafik batang jumlah pemeriksaan 6 bulan terakhir">
                        <?php foreach ($overview['trend'] as $key => $count):
                            $label = date('M y', strtotime($key . '-01'));
                            $height = $trend_max > 0 ? max(4, round(($count / $trend_max) * 100)) : 4;
                        ?>
                            <div class="trend-col" title="<?= html_escape($label . ': ' . $count . ' pemeriksaan') ?>">
                                <span class="trend-value"><?= html_escape($count) ?></span>
                                <div class="trend-bar" style="height: <?= html_escape($height) ?>%"></div>
                                <span class="trend-label"><?= html_escape($label) ?></span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row g-4">
        <div class="col-lg-7">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h2 class="h6 card-title mb-0"><i class="bi bi-clock-history text-primary me-2" aria-hidden="true"></i>Pemeriksaan Terbaru</h2>
                    <a href="<?= html_escape(base_url('list_tests')) ?>" class="btn btn-sm btn-outline-primary">Lihat Semua <i class="bi bi-chevron-right ms-1" aria-hidden="true"></i></a>
                </div>
                <div class="card-body p-0">
                    <?php if (!empty($recent_tests)): ?>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th scope="col">Pasien</th>
                                        <th scope="col">Tanggal</th>
                                        <th scope="col">Review</th>
                                        <th scope="col" class="text-end"><span class="visually-hidden">Aksi</span></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($recent_tests as $test): ?>
                                        <tr>
                                            <td>
                                                <strong class="d-block"><?= html_escape($test['nama']) ?></strong>
                                                <small class="text-body-secondary"><?= html_escape($test['no_rm']) ?> &bull; <?= html_escape($test['perusahaan']) ?></small>
                                            </td>
                                            <td class="text-nowrap"><?= html_escape(date('d M Y', strtotime($test['tanggal_tes']))) ?></td>
                                            <td>
                                                <?php if (trim((string) $test['impression']) !== ''): ?>
                                                    <span class="badge text-bg-success">Selesai</span>
                                                <?php else: ?>
                                                    <span class="badge text-bg-warning">Menunggu</span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="text-end">
                                                <a href="<?= html_escape(base_url('view/' . $test['id'])) ?>" class="btn btn-outline-secondary btn-sm" title="Lihat Rekam" aria-label="Lihat rekam <?= html_escape($test['nama']) ?>">
                                                    <i class="bi bi-eye" aria-hidden="true"></i>
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
                            <a href="<?= html_escape(base_url('create')) ?>" class="btn btn-primary mt-2"><i class="bi bi-plus-lg me-1" aria-hidden="true"></i> Input Pemeriksaan Pertama</a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h2 class="h6 card-title mb-0"><i class="bi bi-exclamation-triangle text-warning me-2" aria-hidden="true"></i>Perlu Tindak Lanjut</h2>
                    <span class="small text-body-secondary">Bulan ini</span>
                </div>
                <div class="card-body p-0">
                    <?php $attention = array_slice($month_report['attention'], 0, 5); ?>
                    <?php if (!empty($attention)): ?>
                        <ul class="list-group list-group-flush">
                            <?php foreach ($attention as $item):
                                $meta = isset($level_meta[$item['level']]) ? $level_meta[$item['level']] : [$item['level'], 'bg-secondary'];
                            ?>
                                <li class="list-group-item d-flex justify-content-between align-items-center gap-2">
                                    <div class="text-truncate">
                                        <strong class="d-block text-truncate"><?= html_escape($item['nama']) ?></strong>
                                        <small class="text-body-secondary"><?= html_escape(date('d M', strtotime($item['tanggal']))) ?> &bull; <?= html_escape($item['perusahaan']) ?></small>
                                    </div>
                                    <div class="d-flex align-items-center gap-2 flex-shrink-0">
                                        <span class="badge <?= html_escape($meta[1]) ?><?= $meta[1] === 'bg-warning' ? ' text-dark' : '' ?>"><?= html_escape($meta[0]) ?></span>
                                        <a href="<?= html_escape(base_url('view/' . $item['id'])) ?>" class="btn btn-outline-secondary btn-sm" aria-label="Lihat rekam <?= html_escape($item['nama']) ?>"><i class="bi bi-eye" aria-hidden="true"></i></a>
                                    </div>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php else: ?>
                        <div class="text-center py-5 empty-state">
                            <i class="bi bi-emoji-smile display-5 text-body-secondary" aria-hidden="true"></i>
                            <p class="mt-3 mb-0 text-body-secondary">Tidak ada hasil di luar batas normal bulan ini.</p>
                        </div>
                    <?php endif; ?>
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
                <h5 class="modal-title fw-bold" id="searchModalLabel"><i class="bi bi-search text-primary me-2" aria-hidden="true"></i>Pencarian Cepat</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body">
                <form action="<?= html_escape(base_url('list_tests')) ?>" method="get">
                    <div class="mb-3">
                        <label for="search_nama" class="form-label">Nama Pasien</label>
                        <input type="text" class="form-control" id="search_nama" name="nama" maxlength="100" placeholder="Masukkan nama pasien...">
                    </div>
                    <div class="mb-4">
                        <label for="search_perusahaan" class="form-label">Perusahaan</label>
                        <input type="text" class="form-control" id="search_perusahaan" name="perusahaan" maxlength="100" placeholder="Masukkan nama perusahaan...">
                    </div>
                    <div class="d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary px-4"><i class="bi bi-search me-1" aria-hidden="true"></i> Cari Pasien</button>
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

