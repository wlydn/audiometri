<?php
// File: application/views/audiometri/doctor_view.php
$reviewed_count = 0;
$pending_count = 0;
foreach ($tests as $t) {
    if (!empty($t['impression'])) {
        $reviewed_count++;
    } else {
        $pending_count++;
    }
}
?>
<div class="container-fluid p-4">
	<div class="page-heading">
		<div>
			<span class="eyebrow"><i class="fas fa-user-doctor me-1"></i> Panel Dokter</span>
			<h1>Review Pemeriksaan Hari Ini</h1>
			<p>Tinjau grafik audiogram dan verifikasi kesimpulan medis (impression) untuk pasien hari ini.</p>
		</div>
		<div class="d-flex align-items-center gap-2 flex-wrap">
			<span class="badge bg-primary fs-6 px-3 py-2">
				<i class="fas fa-users me-1"></i> <?= html_escape(number_format(count($tests))) ?> Pasien
			</span>
			<span class="badge bg-success fs-6 px-3 py-2">
				<i class="fas fa-circle-check me-1"></i> <?= html_escape(number_format($reviewed_count)) ?> Selesai
			</span>
			<?php if ($pending_count > 0): ?>
				<span class="badge bg-warning text-dark fs-6 px-3 py-2">
					<i class="fas fa-clock me-1"></i> <?= html_escape(number_format($pending_count)) ?> Menunggu
				</span>
			<?php endif; ?>
		</div>
	</div>

	<!-- Info Banner -->
	<div class="alert alert-info d-flex align-items-center gap-3 mb-4" role="alert">
		<div style="font-size: 24px; color: #0284c7;"><i class="fas fa-circle-info"></i></div>
		<div>
			<strong class="d-block text-dark">Informasi Panel Review:</strong>
			<span>Daftar di bawah menampilkan pasien yang melakukan tes pada hari ini (<strong><?= html_escape(date('d F Y')) ?></strong>). Anda dapat langsung membuka form review untuk melengkapi kesimpulan medis.</span>
		</div>
	</div>

	<!-- Results Card -->
	<div class="card shadow-sm border-0">
		<div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center flex-wrap gap-2">
			<h5 class="card-title mb-0 fw-bold text-dark">
				<i class="fas fa-clipboard-check text-primary me-2"></i>
				Data Pasien Hari Ini &bull; <?= html_escape(date('d/m/Y')) ?>
			</h5>
			<div class="text-muted small">
				<i class="far fa-calendar-alt me-1"></i> <?= html_escape(date('l, d F Y')) ?>
			</div>
		</div>
		<div class="card-body p-0">
			<?php if (!empty($tests)): ?>
				<div class="table-responsive">
					<table class="table table-hover align-middle mb-0">
						<thead>
							<tr>
								<th style="width: 50px;">No</th>
								<th>Pasien</th>
								<th>No RM</th>
								<th>Perusahaan & Jabatan</th>
								<th>Tanggal Tes</th>
								<th>Status Review</th>
								<th class="text-end" style="width: 140px;">Aksi</th>
							</tr>
						</thead>
						<tbody>
							<?php
							$no = 1;
							foreach ($tests as $test):
								$is_reviewed = !empty($test['impression']);
							?>
								<tr class="<?= !$is_reviewed ? 'table-warning bg-opacity-10' : '' ?>">
									<td class="text-muted fw-bold"><?= html_escape($no++) ?></td>
									<td>
										<div class="d-flex align-items-center gap-2">
											<div style="width: 34px; height: 34px; border-radius: 50%; background: <?= $is_reviewed ? '#dcfce7' : '#fef3c7' ?>; color: <?= $is_reviewed ? '#16a34a' : '#d97706' ?>; display: grid; place-items: center; font-weight: 700; font-size: 13px; flex-shrink: 0;">
												<?= html_escape(strtoupper(substr($test['nama'] ?? 'P', 0, 1))) ?>
											</div>
											<div>
												<strong class="d-block text-dark"><?= html_escape($test['nama']) ?></strong>
												<small class="text-muted"><?= html_escape($test['jenis_kelamin']) ?> &bull; <?= html_escape($test['umur']) ?> thn</small>
											</div>
										</div>
									</td>
									<td>
										<span class="badge bg-light text-dark border font-monospace"><?= html_escape($test['no_rm']) ?></span>
									</td>
									<td>
										<div class="fw-semibold text-dark"><?= html_escape($test['perusahaan']) ?></div>
										<small class="text-muted"><?= html_escape($test['jabatan']) ?></small>
									</td>
									<td>
										<span class="text-secondary fw-semibold"><?= html_escape(date('d/m/Y', strtotime($test['tanggal_tes']))) ?></span>
									</td>
									<td>
										<?php if ($is_reviewed): ?>
											<span class="badge bg-success">
												<i class="fas fa-circle-check"></i> Sudah Direview
											</span>
										<?php else: ?>
											<span class="badge bg-warning">
												<i class="fas fa-clock"></i> Menunggu Review
											</span>
										<?php endif; ?>
									</td>
									<td class="text-end">
										<a href="<?= html_escape(base_url('review_dokter/' . $test['id'])) ?>"
											class="btn btn-sm <?= $is_reviewed ? 'btn-outline-primary' : 'btn-primary' ?>"
											title="Buka Lembar Review">
											<i class="fas fa-stethoscope me-1"></i> Review
										</a>
									</td>
								</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
				</div>
			<?php else: ?>
				<div class="text-center py-5">
					<i class="fas fa-calendar-check fa-3x text-muted mb-3 opacity-50"></i>
					<h5 class="fw-bold text-dark">Tidak Ada Pemeriksaan Hari Ini</h5>
					<p class="text-muted">Belum ada pasien yang menjalani tes audiometri pada <?= html_escape(date('d F Y')) ?>.</p>
					<a href="<?= html_escape(base_url('list_tests')) ?>" class="btn btn-outline-primary mt-2">
						<i class="fas fa-folder-open me-1"></i> Lihat Seluruh Riwayat Tes
					</a>
				</div>
			<?php endif; ?>
		</div>
	</div>
</div>
