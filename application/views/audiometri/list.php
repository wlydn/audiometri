<?php
// File: application/views/audiometri/list.php
?>
<div class="container-fluid p-4">
	<div class="page-heading">
		<div>
			<span class="eyebrow"><i class="fas fa-folder-open me-1"></i> Data Pemeriksaan</span>
			<h1>Rekam Medis Audiometri</h1>
			<p>Kelola dan telusuri seluruh riwayat pemeriksaan pendengaran pasien secara terpusat.</p>
		</div>
		<div class="d-flex gap-2">
			<a href="<?= html_escape(base_url('export_excel')) ?>" class="btn btn-outline-success">
				<i class="fas fa-file-excel me-1"></i> Ekspor Excel
			</a>
			<a href="<?= html_escape(base_url('create')) ?>" class="btn btn-primary">
				<i class="fas fa-plus me-1"></i> Pemeriksaan Baru
			</a>
		</div>
	</div>

	<!-- Filter Card -->
	<div class="card mb-4 shadow-sm border-0">
		<div class="card-header bg-white py-3 border-bottom d-flex align-items-center">
			<h6 class="card-title mb-0 fw-bold text-dark">
				<i class="fas fa-filter text-primary me-2"></i>
				Filter & Pencarian
			</h6>
		</div>
		<div class="card-body p-4">
			<form method="get" action="<?= html_escape(base_url('list_tests')) ?>">
				<div class="row g-3">
					<div class="col-lg-3 col-md-6">
						<label for="nama" class="form-label small fw-bold text-secondary">Nama Pasien</label>
						<div class="input-group">
							<span class="input-group-text bg-light border-end-0"><i class="fas fa-user text-muted small"></i></span>
							<input type="text" class="form-control border-start-0" id="nama" name="nama"
								value="<?= html_escape($filters['nama'] ?? '') ?>" placeholder="Cari nama pasien...">
						</div>
					</div>
					<div class="col-lg-3 col-md-6">
						<label for="perusahaan" class="form-label small fw-bold text-secondary">Perusahaan</label>
						<div class="input-group">
							<span class="input-group-text bg-light border-end-0"><i class="fas fa-building text-muted small"></i></span>
							<input type="text" class="form-control border-start-0" id="perusahaan" name="perusahaan"
								value="<?= html_escape($filters['perusahaan'] ?? '') ?>" placeholder="Cari perusahaan...">
						</div>
					</div>
					<div class="col-lg-2 col-md-6">
						<label for="tanggal_dari" class="form-label small fw-bold text-secondary">Dari Tanggal</label>
						<input type="date" class="form-control" id="tanggal_dari" name="tanggal_dari"
							value="<?= html_escape($filters['tanggal_dari'] ?? '') ?>">
					</div>
					<div class="col-lg-2 col-md-6">
						<label for="tanggal_sampai" class="form-label small fw-bold text-secondary">Sampai Tanggal</label>
						<input type="date" class="form-control" id="tanggal_sampai" name="tanggal_sampai"
							value="<?= html_escape($filters['tanggal_sampai'] ?? '') ?>">
					</div>
					<div class="col-lg-2 col-md-12 d-flex align-items-end gap-2">
						<button type="submit" class="btn btn-primary flex-grow-1">
							<i class="fas fa-search me-1"></i> Terapkan
						</button>
						<?php if (!empty($filters['nama']) || !empty($filters['perusahaan']) || !empty($filters['tanggal_dari']) || !empty($filters['tanggal_sampai'])): ?>
							<a href="<?= html_escape(base_url('list_tests')) ?>" class="btn btn-outline-secondary" title="Reset Filter">
								<i class="fas fa-rotate-left"></i>
							</a>
						<?php endif; ?>
					</div>
				</div>
			</form>
		</div>
	</div>

	<!-- Results Table Card -->
	<div class="card shadow-sm border-0">
		<div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
			<div class="d-flex align-items-center gap-2">
				<h6 class="card-title mb-0 fw-bold text-dark">Daftar Pasien</h6>
				<span class="badge bg-primary text-white fw-bold"><?= html_escape(number_format($total_tests)) ?> Total</span>
			</div>
			<div class="text-muted small">
				Halaman <?= html_escape($current_page) ?>
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
							$no = (($current_page - 1) * 10) + 1;
							foreach ($tests as $test):
							?>
								<tr>
									<td class="text-muted fw-bold"><?= html_escape($no++) ?></td>
									<td>
										<div class="d-flex align-items-center gap-2">
											<div style="width: 34px; height: 34px; border-radius: 50%; background: #eff6ff; color: #2563eb; display: grid; place-items: center; font-weight: 700; font-size: 13px; flex-shrink: 0;">
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
										<div class="d-flex align-items-center gap-1 text-secondary">
											<i class="far fa-calendar-alt small"></i>
											<span class="fw-semibold"><?= html_escape(date('d M Y', strtotime($test['tanggal_tes']))) ?></span>
										</div>
									</td>
									<td>
										<?php
										$impression = $test['impression'];
										if (!empty($impression)) {
											echo '<span class="badge bg-success"><i class="fas fa-circle-check"></i> Sudah Direview</span>';
										} else {
											echo '<span class="badge bg-warning"><i class="fas fa-hourglass-half"></i> Menunggu Review</span>';
										}
										?>
									</td>
									<td class="text-end">
										<div class="btn-group btn-group-sm">
											<a href="<?= html_escape(base_url('view/' . $test['id'])) ?>"
												class="btn btn-outline-primary" title="Lihat Rekam Medis">
												<i class="fas fa-eye"></i>
											</a>
											<a href="<?= html_escape(base_url('edit/' . $test['id'])) ?>"
												class="btn btn-outline-secondary" title="Edit Data">
												<i class="fas fa-pen-to-square"></i>
											</a>
											<a href="<?= html_escape(base_url('export_pdf/' . $test['id'])) ?>"
												class="btn btn-outline-info" title="Unduh PDF" target="_blank">
												<i class="fas fa-file-pdf"></i>
											</a>
										</div>
									</td>
								</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
				</div>

				<!-- Pagination Bar -->
				<?php if (!empty($pagination)): ?>
					<div class="card-footer bg-white border-top py-3">
						<div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
							<div class="text-muted small">
								Menampilkan <strong class="text-dark"><?= html_escape((($current_page - 1) * 10) + 1) ?></strong> -
								<strong class="text-dark"><?= html_escape(min($current_page * 10, $total_tests)) ?></strong> dari <strong class="text-dark"><?= html_escape(number_format($total_tests)) ?></strong> total pemeriksaan
							</div>
							<div>
								<?= $pagination ?>
							</div>
						</div>
					</div>
				<?php endif; ?>

			<?php else: ?>
				<div class="text-center py-5">
					<i class="fas fa-magnifying-glass fa-3x text-muted mb-3 opacity-50"></i>
					<h5 class="fw-bold text-dark">Tidak Ada Data Ditemukan</h5>
					<p class="text-muted">Coba sesuaikan kata kunci pencarian atau buat pemeriksaan baru.</p>
					<a href="<?= html_escape(base_url('create')) ?>" class="btn btn-primary mt-2">
						<i class="fas fa-plus me-1"></i> Input Pemeriksaan Baru
					</a>
				</div>
			<?php endif; ?>
		</div>
	</div>
</div>
