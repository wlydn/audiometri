<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<dl class="row mb-0">
    <dt class="col-sm-4">No RM</dt>
    <dd class="col-sm-8"><?= html_escape($test_data['no_rm'] ?? '') ?></dd>
    <dt class="col-sm-4">Nama</dt>
    <dd class="col-sm-8"><?= html_escape($test_data['nama'] ?? '') ?></dd>
    <dt class="col-sm-4">Jenis kelamin</dt>
    <dd class="col-sm-8"><?= html_escape($test_data['jenis_kelamin'] ?? '') ?></dd>
    <dt class="col-sm-4">Umur</dt>
    <dd class="col-sm-8"><?= html_escape($test_data['umur'] ?? '') ?> tahun</dd>
    <dt class="col-sm-4">Perusahaan</dt>
    <dd class="col-sm-8"><?= html_escape($test_data['perusahaan'] ?? '') ?></dd>
    <dt class="col-sm-4">Jabatan</dt>
    <dd class="col-sm-8"><?= html_escape($test_data['jabatan'] ?? '') ?></dd>
    <dt class="col-sm-4">Tanggal tes</dt>
    <dd class="col-sm-8"><?= html_escape($test_data['tanggal_tes'] ?? '') ?></dd>
    <dt class="col-sm-4">Hasil review</dt>
    <dd class="col-sm-8"><?= nl2br(html_escape($test_data['impression'] ?? 'Belum direview')) ?></dd>
</dl>
