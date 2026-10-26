<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="patient-detail-summary p-3 bg-light rounded-3 border">
    <div class="row g-2">
        <div class="col-sm-4 text-muted small fw-bold text-uppercase">No RM</div>
        <div class="col-sm-8 fw-semibold text-dark font-monospace"><?= html_escape($test_data['no_rm'] ?? '-') ?></div>
        
        <div class="col-sm-4 text-muted small fw-bold text-uppercase">Nama Pasien</div>
        <div class="col-sm-8 fw-bold text-primary"><?= html_escape($test_data['nama'] ?? '-') ?></div>
        
        <div class="col-sm-4 text-muted small fw-bold text-uppercase">Jenis Kelamin</div>
        <div class="col-sm-8 text-dark"><?= html_escape($test_data['jenis_kelamin'] ?? '-') ?></div>
        
        <div class="col-sm-4 text-muted small fw-bold text-uppercase">Umur</div>
        <div class="col-sm-8 text-dark"><?= html_escape($test_data['umur'] ?? '-') ?> tahun</div>
        
        <div class="col-sm-4 text-muted small fw-bold text-uppercase">Perusahaan</div>
        <div class="col-sm-8 text-dark"><?= html_escape($test_data['perusahaan'] ?? '-') ?></div>
        
        <div class="col-sm-4 text-muted small fw-bold text-uppercase">Jabatan</div>
        <div class="col-sm-8 text-dark"><?= html_escape($test_data['jabatan'] ?? '-') ?></div>
        
        <div class="col-sm-4 text-muted small fw-bold text-uppercase">Tanggal Tes</div>
        <div class="col-sm-8 text-secondary fw-semibold"><?= !empty($test_data['tanggal_tes']) ? html_escape(date('d F Y', strtotime($test_data['tanggal_tes']))) : '-' ?></div>
        
        <div class="col-sm-4 text-muted small fw-bold text-uppercase">Hasil Review</div>
        <div class="col-sm-8 text-dark">
            <?php if (!empty($test_data['impression'])): ?>
                <span class="badge bg-success me-1">Sudah Direview</span>
                <span><?= nl2br(html_escape($test_data['impression'])) ?></span>
            <?php else: ?>
                <span class="badge bg-warning text-dark"><i class="fas fa-clock me-1"></i>Belum direview</span>
            <?php endif; ?>
        </div>
    </div>
</div>
