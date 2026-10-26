<?php
// File: application/views/audiometri/list.php
$has_filter = !empty($filters['nama']) || !empty($filters['perusahaan']) || !empty($filters['tanggal_dari']) || !empty($filters['tanggal_sampai']);
?>
<div class="container-fluid p-3 p-lg-4">
	<div class="page-heading">
		<div>
			<span class="eyebrow"><i class="bi bi-folder2-open me-1" aria-hidden="true"></i> Data Pemeriksaan</span>
			<h1>Rekam Medis Audiometri</h1>
			<p>Kelola dan telusuri seluruh riwayat pemeriksaan pendengaran pasien secara terpusat.</p>
		</div>
		<div class="d-flex flex-wrap gap-2">
			<a href="<?= html_escape(base_url('export_excel')) ?>" class="btn btn-outline-success">
				<i class="bi bi-file-earmark-excel me-1" aria-hidden="true"></i> Ekspor Excel
			</a>
			<a href="<?= html_escape(base_url('create')) ?>" class="btn btn-primary">
				<i class="bi bi-plus-lg me-1" aria-hidden="true"></i> Pemeriksaan Baru
			</a>
		</div>
	</div>

	<!-- Filter Card -->
	<div class="card mb-4">
		<div class="card-header">
			<h2 class="h6 card-title mb-0">
				<i class="bi bi-funnel text-primary me-2" aria-hidden="true"></i>Filter &amp; Pencarian
			</h2>
		</div>
		<div class="card-body">
			<form method="get" action="<?= html_escape(base_url('list_tests')) ?>">
				<div class="row g-3">
					<div class="col-lg-3 col-md-6">
						<label for="nama" class="form-label">Nama Pasien</label>
						<div class="input-group">
							<span class="input-group-text"><i class="bi bi-person" aria-hidden="true"></i></span>
							<input type="text" class="form-control" id="nama" name="nama"
								value="<?= html_escape($filters['nama'] ?? '') ?>" placeholder="Cari nama pasien...">
						</div>
					</div>
					<div class="col-lg-3 col-md-6">
						<label for="perusahaan" class="form-label">Perusahaan</label>
						<div class="input-group">
							<span class="input-group-text"><i class="bi bi-building" aria-hidden="true"></i></span>
							<input type="text" class="form-control" id="perusahaan" name="perusahaan"
								value="<?= html_escape($filters['perusahaan'] ?? '') ?>" placeholder="Cari perusahaan...">
						</div>
					</div>
					<div class="col-lg-2 col-md-6">
						<label for="tanggal_dari" class="form-label">Dari Tanggal</label>
						<input type="date" class="form-control" id="tanggal_dari" name="tanggal_dari"
							value="<?= html_escape($filters['tanggal_dari'] ?? '') ?>">
					</div>
					<div class="col-lg-2 col-md-6">
						<label for="tanggal_sampai" class="form-label">Sampai Tanggal</label>
						<input type="date" class="form-control" id="tanggal_sampai" name="tanggal_sampai"
							value="<?= html_escape($filters['tanggal_sampai'] ?? '') ?>">
					</div>
					<div class="col-lg-2 col-md-12 d-flex align-items-end gap-2">
						<button type="submit" class="btn btn-primary flex-grow-1">
							<i class="bi bi-search me-1" aria-hidden="true"></i> Terapkan
						</button>
						<?php if ($has_filter): ?>
							<a href="<?= html_escape(base_url('list_tests')) ?>" class="btn btn-outline-secondary" title="Reset Filter" aria-label="Reset Filter">
								<i class="bi bi-arrow-counterclockwise" aria-hidden="true"></i>
							</a>
						<?php endif; ?>
					</div>
				</div>
			</form>
		</div>
	</div>

	<!-- Results Table Card -->
	<div class="card">
		<div class="card-header d-flex justify-content-between align-items-center">
			<div class="d-flex align-items-center gap-2">
				<h2 class="h6 card-title mb-0">Daftar Pasien</h2>
				<span class="badge bg-primary"><?= html_escape(number_format($total_tests)) ?> Total</span>
			</div>
			<div class="text-body-secondary small">
				Halaman <?= html_escape($current_page) ?>
			</div>
		</div>
		<div class="card-body p-0">
			<?php if (!empty($tests)): ?>
				<div class="table-responsive">
					<table class="table table-hover align-middle mb-0">
						<thead>
							<tr>
								<th scope="col">No</th>
								<th scope="col">Pasien</th>
								<th scope="col">No RM</th>
								<th scope="col">Perusahaan &amp; Jabatan</th>
								<th scope="col">Tanggal Tes</th>
								<th scope="col">Status Review</th>
								<th scope="col" class="text-end">Aksi</th>
							</tr>
						</thead>
						<tbody>
							<?php
							$no = (($current_page - 1) * 10) + 1;
							foreach ($tests as $test):
							?>
								<tr>
									<td class="text-body-secondary fw-bold"><?= html_escape($no++) ?></td>
									<td>
										<div class="d-flex align-items-center gap-2">
											<span class="avatar avatar-primary" aria-hidden="true"><?= html_escape(strtoupper(substr($test['nama'] ?? 'P', 0, 1))) ?></span>
											<div>
												<strong class="d-block"><?= html_escape($test['nama']) ?></strong>
												<small class="text-body-secondary"><?= html_escape($test['jenis_kelamin']) ?> &bull; <?= html_escape($test['umur']) ?> thn</small>
											</div>
										</div>
									</td>
									<td>
										<span class="badge text-bg-light border font-monospace"><?= html_escape($test['no_rm']) ?></span>
									</td>
									<td>
										<div class="fw-semibold"><?= html_escape($test['perusahaan']) ?></div>
										<small class="text-body-secondary"><?= html_escape($test['jabatan']) ?></small>
									</td>
									<td>
										<span class="text-body-secondary fw-semibold text-nowrap">
											<i class="bi bi-calendar3 me-1" aria-hidden="true"></i><?= html_escape(date('d M Y', strtotime($test['tanggal_tes']))) ?>
										</span>
									</td>
									<td>
										<?php if (!empty($test['impression'])): ?>
											<span class="badge bg-success"><i class="bi bi-check-circle-fill me-1" aria-hidden="true"></i>Sudah Direview</span>
										<?php else: ?>
											<span class="badge bg-warning text-dark"><i class="bi bi-hourglass-split me-1" aria-hidden="true"></i>Menunggu Review</span>
										<?php endif; ?>
									</td>
									<td class="text-end text-nowrap">
										<a href="<?= html_escape(base_url('view/' . $test['id'])) ?>"
											class="btn btn-outline-secondary btn-sm" title="Lihat Rekam Medis" aria-label="Lihat Rekam Medis">
											<i class="bi bi-eye" aria-hidden="true"></i>
										</a>
										<a href="<?= html_escape(base_url('edit/' . $test['id'])) ?>"
											class="btn btn-outline-secondary btn-sm" title="Edit Data" aria-label="Edit Data">
											<i class="bi bi-pencil-square" aria-hidden="true"></i>
										</a>
										<a href="<?= html_escape(base_url('export_pdf/' . $test['id'])) ?>"
											class="btn btn-outline-secondary btn-sm" title="Unduh PDF" aria-label="Unduh PDF" target="_blank" rel="noopener">
											<i class="bi bi-file-earmark-pdf" aria-hidden="true"></i>
										</a>
									</td>
								</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
				</div>

				<!-- Pagination Bar -->
				<?php if (!empty($pagination)): ?>
					<div class="card-footer bg-transparent">
						<div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
							<div class="text-body-secondary small">
								Menampilkan <strong><?= html_escape((($current_page - 1) * 10) + 1) ?></strong> -
								<strong><?= html_escape(min($current_page * 10, $total_tests)) ?></strong> dari <strong><?= html_escape(number_format($total_tests)) ?></strong> total pemeriksaan
							</div>
							<div>
								<?= $pagination ?>
							</div>
						</div>
					</div>
				<?php endif; ?>

			<?php else: ?>
				<div class="text-center py-5 empty-state">
					<i class="bi bi-search display-4 text-body-secondary" aria-hidden="true"></i>
					<h3 class="h5 mt-3">Tidak Ada Data Ditemukan</h3>
					<p class="text-body-secondary">Coba sesuaikan kata kunci pencarian atau buat pemeriksaan baru.</p>
					<a href="<?= html_escape(base_url('create')) ?>" class="btn btn-primary mt-2">
						<i class="bi bi-plus-lg me-1" aria-hidden="true"></i> Input Pemeriksaan Baru
					</a>
				</div>
			<?php endif; ?>
		</div>
	</div>
</div>

