<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="patient-detail-summary p-3 bg-body-tertiary rounded-3 border">
    <dl class="row g-2 mb-0">
        <dt class="col-sm-4 stat-label">No RM</dt>
        <dd class="col-sm-8 mb-0 fw-semibold font-monospace"><?= html_escape($test_data['no_rm'] ?? '-') ?></dd>

        <dt class="col-sm-4 stat-label">Nama Pasien</dt>
        <dd class="col-sm-8 mb-0 fw-bold text-primary"><?= html_escape($test_data['nama'] ?? '-') ?></dd>

        <dt class="col-sm-4 stat-label">Jenis Kelamin</dt>
        <dd class="col-sm-8 mb-0"><?= html_escape($test_data['jenis_kelamin'] ?? '-') ?></dd>

        <dt class="col-sm-4 stat-label">Umur</dt>
        <dd class="col-sm-8 mb-0"><?= html_escape($test_data['umur'] ?? '-') ?> tahun</dd>

        <dt class="col-sm-4 stat-label">Perusahaan</dt>
        <dd class="col-sm-8 mb-0"><?= html_escape($test_data['perusahaan'] ?? '-') ?></dd>

        <dt class="col-sm-4 stat-label">Jabatan</dt>
        <dd class="col-sm-8 mb-0"><?= html_escape($test_data['jabatan'] ?? '-') ?></dd>

        <dt class="col-sm-4 stat-label">Tanggal Tes</dt>
        <dd class="col-sm-8 mb-0 text-body-secondary fw-semibold"><?= !empty($test_data['tanggal_tes']) ? html_escape(date('d F Y', strtotime($test_data['tanggal_tes']))) : '-' ?></dd>

        <dt class="col-sm-4 stat-label">Hasil Review</dt>
        <dd class="col-sm-8 mb-0">
            <?php if (!empty($test_data['impression'])): ?>
                <span class="badge bg-success me-1">Sudah Direview</span>
                <span><?= nl2br(html_escape($test_data['impression'])) ?></span>
            <?php else: ?>
                <span class="badge bg-warning text-dark"><i class="bi bi-hourglass-split me-1" aria-hidden="true"></i>Belum direview</span>
            <?php endif; ?>
        </dd>
    </dl>
</div>
