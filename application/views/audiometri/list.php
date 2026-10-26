<?php
// File: application/views/audiometri/list.php
?>
<div class="container-fluid p-4">
	<div class="page-heading">
		<div><span class="eyebrow">Data pemeriksaan</span><h1>Semua pemeriksaan</h1><p>Temukan data pasien, pantau status review, dan akses hasil pemeriksaan.</p></div>
		<a href="<?= html_escape(base_url('create')) ?>" class="btn btn-primary"><i class="fas fa-plus me-2" aria-hidden="true"></i>Pemeriksaan baru</a>
	</div>

	<!-- Filter Section -->
	<div class="card mb-4">
		<div class="card-header bg-light">
			<h5 class="card-title mb-0">
				<i class="fas fa-filter me-2"></i>
				Filter Pencarian
			</h5>
		</div>
		<div class="card-body">
			<form method="get" action="<?= html_escape(base_url('list_tests')) ?>">
				<div class="row">
					<div class="col-md-3 mb-3">
						<label for="nama" class="form-label">Nama</label>
						<input type="text" class="form-control" id="nama" name="nama"
							value="<?= html_escape($filters['nama'] ?? '') ?>" placeholder="Nama pasien">
					</div>
					<div class="col-md-3 mb-3">
						<label for="perusahaan" class="form-label">Perusahaan</label>
						<input type="text" class="form-control" id="perusahaan" name="perusahaan"
							value="<?= html_escape($filters['perusahaan'] ?? '') ?>" placeholder="Nama perusahaan">
					</div>
					<div class="col-md-2 mb-3">
						<label for="tanggal_dari" class="form-label">Tanggal Dari</label>
						<input type="date" class="form-control" id="tanggal_dari" name="tanggal_dari"
							value="<?= html_escape($filters['tanggal_dari'] ?? '') ?>">
					</div>
					<div class="col-md-2 mb-3">
						<label for="tanggal_sampai" class="form-label">Tanggal Sampai</label>
						<input type="date" class="form-control" id="tanggal_sampai" name="tanggal_sampai"
							value="<?= html_escape($filters['tanggal_sampai'] ?? '') ?>">
					</div>
					<div class="col-md-2 mb-3">
						<label class="form-label">&nbsp;</label>
						<div class="d-grid gap-2">
							<button type="submit" class="btn btn-primary">
								<i class="fas fa-search me-1"></i> Cari
							</button>
						</div>
					</div>
				</div>
				<?php if (!empty($filters)): ?>
					<div class="row">
						<div class="col-12">
							<a href="<?= html_escape(base_url('list_tests')) ?>" class="btn btn-outline-secondary btn-sm">
								<i class="fas fa-times me-1"></i> Reset Filter
							</a>
						</div>
					</div>
				<?php endif; ?>
			</form>
		</div>
	</div>

	<!-- Results Section -->
	<div class="card">
		<div class="card-header bg-light d-flex justify-content-between align-items-center">
			<h5 class="card-title mb-0">
				Hasil Pencarian (<?= html_escape(number_format($total_tests)) ?> data)
			</h5>
			<div class="btn-group">
				<a href="<?= html_escape(base_url('create')) ?>" class="btn btn-primary btn-sm">
					<i class="fas fa-plus me-1"></i> Tes Baru
				</a>
				<a href="<?= html_escape(base_url('export_excel')) ?>" class="btn btn-success btn-sm">
					<i class="fas fa-file-excel me-1"></i> Export Excel
				</a>
			</div>
		</div>
		<div class="card-body p-0">
			<?php if (!empty($tests)): ?>
				<div class="table-responsive">
					<table class="table table-hover mb-0">
						<thead class="table-light">
							<tr>
								<th>No</th>
								<th>Tanggal Tes</th>
								<th>No RM</th>
								<th>Nama</th>
								<th>Umur</th>
								<th>Perusahaan</th>
								<th>Jabatan</th>
								<th>Status</th>
								<th>Aksi</th>
							</tr>
						</thead>
						<tbody>
							<?php
							$no = (($current_page - 1) * 10) + 1;
							foreach ($tests as $test):
							?>
								<tr>
									<td><?= html_escape($no++) ?></td>
									<td><?= html_escape(date('d/m/Y', strtotime($test['tanggal_tes']))) ?></td>
									<td><?= html_escape($test['no_rm']) ?></td>
									<td>
										<strong><?= html_escape($test['nama']) ?></strong><br>
										<small class="text-muted"><?= html_escape($test['jenis_kelamin']) ?></small>
									</td>
									<td><?= html_escape($test['umur']) ?> tahun</td>
									<td><?= html_escape($test['perusahaan']) ?></td>
									<td><?= html_escape($test['jabatan']) ?></td>
									<td>
										<?php
										$impression = $test['impression'];
										if (!empty($impression)) {
											echo '<span class="badge bg-success"><i class="fas fa-check me-1"></i>Sudah Direview</span>';
										} else {
											echo '<span class="badge bg-danger"><i class="fas fa-clock me-1"></i>Menunggu Review</span>';
										}
										?>
									</td>
									<td>
										<div class="btn-group btn-group-sm">
											<a href="<?= html_escape(base_url('view/' . $test['id'])) ?>"
												class="btn btn-outline-primary" title="Lihat Detail">
												<i class="fas fa-eye"></i>
											</a>
											<a href="<?= html_escape(base_url('edit/' . $test['id'])) ?>"
												class="btn btn-outline-secondary" title="Edit">
												<i class="fas fa-edit"></i>
											</a>
											<a href="<?= html_escape(base_url('export_pdf/' . $test['id'])) ?>"
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

				<!-- Pagination -->
				<?php if (!empty($pagination)): ?>
					<div class="card-footer bg-light">
						<div class="d-flex justify-content-between align-items-center">
							<div>
								Menampilkan <?= html_escape((($current_page - 1) * 10) + 1) ?> -
								<?= html_escape(min($current_page * 10, $total_tests)) ?> dari <?= html_escape(number_format($total_tests)) ?> data
							</div>
							<div>
								<?= $pagination ?>
							</div>
						</div>
					</div>
				<?php endif; ?>

			<?php else: ?>
				<div class="text-center py-5">
					<i class="fas fa-search fa-3x text-muted mb-3"></i>
					<h5 class="text-muted">Tidak ada data yang ditemukan</h5>
					<p class="text-muted">Coba ubah kriteria pencarian atau buat tes baru</p>
					<a href="<?= html_escape(base_url('create')) ?>" class="btn btn-primary">
						<i class="fas fa-plus me-1"></i> Buat Tes Baru
					</a>
				</div>
			<?php endif; ?>
		</div>
	</div>
</div>

<style>
	.bg-orange {
		background-color: #fd7e14 !important;
	}
</style>
