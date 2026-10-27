<?php
// File: application/views/audiometri/laporan.php
$total = (int) $report['total'];
$pct = function ($part, $whole) {
	return $whole > 0 ? round(($part / $whole) * 100) : 0;
};

$level_meta = [
	'Normal' => ['Normal', 'bg-success'],
	'Mild hearing loss' => ['Gangguan Ringan', 'bg-info'],
	'Moderate hearing loss' => ['Gangguan Sedang', 'bg-warning'],
	'Moderately severe hearing loss' => ['Sedang - Berat', 'bg-warning'],
	'Severe hearing loss' => ['Gangguan Berat', 'bg-danger'],
	'Profound hearing loss' => ['Sangat Berat', 'bg-danger'],
];

$export_query = http_build_query(array_filter($filters, function ($value) {
	return $value !== '';
}));
$export_url = base_url('export_excel') . ($export_query !== '' ? '?' . $export_query : '');

$format_date = function ($date) {
	return $date !== '' ? date('d M Y', strtotime($date)) : '';
};
if ($filters['tanggal_dari'] === '' && $filters['tanggal_sampai'] === '') {
	$period_label = 'Semua waktu';
} elseif ($filters['tanggal_dari'] !== '' && $filters['tanggal_sampai'] !== '') {
	$period_label = $format_date($filters['tanggal_dari']) . ' - ' . $format_date($filters['tanggal_sampai']);
} elseif ($filters['tanggal_dari'] !== '') {
	$period_label = 'Sejak ' . $format_date($filters['tanggal_dari']);
} else {
	$period_label = 'Sampai ' . $format_date($filters['tanggal_sampai']);
}

$trend_max = !empty($report['trend']) ? max($report['trend']) : 0;
?>
<div class="container-fluid p-3 p-lg-4">
	<div class="page-heading">
		<div>
			<span class="eyebrow"><i class="bi bi-bar-chart-line me-1" aria-hidden="true"></i> Laporan</span>
			<h1>Laporan Periode</h1>
			<p>
				Periode: <strong><?= html_escape($period_label) ?></strong>
				<?php if ($filters['perusahaan'] !== ''): ?>
					&bull; Perusahaan: <strong><?= html_escape($filters['perusahaan']) ?></strong>
				<?php endif; ?>
			</p>
		</div>
		<div class="d-flex flex-wrap gap-2 no-print">
			<button type="button" class="btn btn-outline-secondary" onclick="window.print()">
				<i class="bi bi-printer me-1" aria-hidden="true"></i> Cetak
			</button>
			<a href="<?= html_escape($export_url) ?>" class="btn btn-success<?= $total === 0 ? ' disabled' : '' ?>"<?= $total === 0 ? ' aria-disabled="true" tabindex="-1"' : '' ?>>
				<i class="bi bi-file-earmark-excel me-1" aria-hidden="true"></i> Unduh Excel (<?= html_escape(number_format($total)) ?> data)
			</a>
		</div>
	</div>

	<!-- Filter -->
	<div class="card mb-4 no-print">
		<div class="card-header">
			<h2 class="h6 card-title mb-0">
				<i class="bi bi-funnel text-primary me-2" aria-hidden="true"></i>Filter Periode
			</h2>
		</div>
		<div class="card-body">
			<div class="d-flex flex-wrap gap-2 mb-3">
				<?php foreach ($presets as $preset):
					$is_active = $preset['dari'] === $filters['tanggal_dari'] && $preset['sampai'] === $filters['tanggal_sampai'];
					$preset_query = http_build_query([
						'tanggal_dari' => $preset['dari'],
						'tanggal_sampai' => $preset['sampai'],
						'perusahaan' => $filters['perusahaan'],
					]);
				?>
					<a href="<?= html_escape(base_url('laporan') . '?' . $preset_query) ?>"
						class="btn btn-sm <?= $is_active ? 'btn-primary' : 'btn-outline-primary' ?>"<?= $is_active ? ' aria-current="true"' : '' ?>>
						<?= html_escape($preset['label']) ?>
					</a>
				<?php endforeach; ?>
			</div>
			<form method="get" action="<?= html_escape(base_url('laporan')) ?>">
				<div class="row g-3">
					<div class="col-lg-3 col-md-6">
						<label for="lap_tanggal_dari" class="form-label">Dari Tanggal</label>
						<input type="date" class="form-control" id="lap_tanggal_dari" name="tanggal_dari"
							value="<?= html_escape($filters['tanggal_dari']) ?>">
					</div>
					<div class="col-lg-3 col-md-6">
						<label for="lap_tanggal_sampai" class="form-label">Sampai Tanggal</label>
						<input type="date" class="form-control" id="lap_tanggal_sampai" name="tanggal_sampai"
							value="<?= html_escape($filters['tanggal_sampai']) ?>">
					</div>
					<div class="col-lg-4 col-md-8">
						<label for="lap_perusahaan" class="form-label">Perusahaan (Opsional)</label>
						<div class="input-group">
							<span class="input-group-text"><i class="bi bi-building" aria-hidden="true"></i></span>
							<input type="text" class="form-control" id="lap_perusahaan" name="perusahaan" maxlength="100"
								value="<?= html_escape($filters['perusahaan']) ?>" placeholder="Nama perusahaan...">
						</div>
					</div>
					<div class="col-lg-2 col-md-4 d-flex align-items-end">
						<button type="submit" class="btn btn-primary w-100">
							<i class="bi bi-search me-1" aria-hidden="true"></i> Tampilkan
						</button>
					</div>
				</div>
			</form>
		</div>
	</div>

	<?php if ($total === 0): ?>
		<div class="card">
			<div class="card-body text-center py-5 empty-state">
				<i class="bi bi-inbox display-4 text-body-secondary" aria-hidden="true"></i>
				<h3 class="h5 mt-3">Tidak Ada Pemeriksaan pada Periode Ini</h3>
				<p class="text-body-secondary mb-0">Ubah rentang tanggal atau pilih "Semua Waktu" untuk melihat laporan.</p>
			</div>
		</div>
	<?php else: ?>
		<!-- Ringkasan -->
		<?php $measured = $total - $report['no_data']; ?>
		<div class="row g-3 mb-4">
			<div class="col-sm-6 col-xl-3">
				<div class="card stat-card h-100">
					<div class="card-body d-flex justify-content-between align-items-start">
						<div>
							<span class="stat-label">Total Pemeriksaan</span>
							<p class="stat-value"><?= html_escape(number_format($total)) ?></p>
						</div>
						<div class="stat-icon bg-primary-subtle text-primary"><i class="bi bi-file-earmark-medical" aria-hidden="true"></i></div>
					</div>
					<div class="card-footer bg-transparent small text-body-secondary">
						<?= html_escape($report['gender']['Laki-laki']) ?> laki-laki &bull; <?= html_escape($report['gender']['Perempuan']) ?> perempuan
					</div>
				</div>
			</div>
			<div class="col-sm-6 col-xl-3">
				<div class="card stat-card h-100">
					<div class="card-body d-flex justify-content-between align-items-start">
						<div>
							<span class="stat-label">Sudah Direview</span>
							<p class="stat-value"><?= html_escape($pct($report['reviewed'], $total)) ?>%</p>
						</div>
						<div class="stat-icon bg-success-subtle text-success"><i class="bi bi-check2-circle" aria-hidden="true"></i></div>
					</div>
					<div class="card-footer bg-transparent small text-body-secondary">
						<?= html_escape($report['reviewed']) ?> selesai &bull; <?= html_escape($report['pending']) ?> menunggu
					</div>
				</div>
			</div>
			<div class="col-sm-6 col-xl-3">
				<div class="card stat-card h-100">
					<div class="card-body d-flex justify-content-between align-items-start">
						<div>
							<span class="stat-label">Perlu Perhatian</span>
							<p class="stat-value text-warning-emphasis"><?= html_escape($report['abnormal']) ?></p>
						</div>
						<div class="stat-icon bg-warning-subtle text-warning-emphasis"><i class="bi bi-exclamation-triangle" aria-hidden="true"></i></div>
					</div>
					<div class="card-footer bg-transparent small text-body-secondary">
						<?= html_escape($pct($report['abnormal'], $measured)) ?>% dari <?= html_escape($measured) ?> tes terukur
					</div>
				</div>
			</div>
			<div class="col-sm-6 col-xl-3">
				<div class="card stat-card h-100">
					<div class="card-body d-flex justify-content-between align-items-start">
						<div>
							<span class="stat-label">Rata-rata Umur</span>
							<p class="stat-value"><?= $report['avg_age'] !== null ? html_escape(number_format($report['avg_age'], 1)) : '-' ?></p>
						</div>
						<div class="stat-icon bg-info-subtle text-info-emphasis"><i class="bi bi-people" aria-hidden="true"></i></div>
					</div>
					<div class="card-footer bg-transparent small text-body-secondary">
						<?= html_escape(count($report['companies'])) ?> perusahaan
					</div>
				</div>
			</div>
		</div>

		<div class="row g-4 mb-4">
			<!-- Distribusi -->
			<div class="col-lg-5">
				<div class="card h-100">
					<div class="card-header">
						<h2 class="h6 card-title mb-0"><i class="bi bi-ear text-primary me-2" aria-hidden="true"></i>Distribusi Tingkat Pendengaran</h2>
					</div>
					<div class="card-body">
						<p class="small text-body-secondary">Berdasarkan rata-rata AC pada telinga yang lebih buruk.</p>
						<?php foreach ($report['levels'] as $level => $count):
							$meta = isset($level_meta[$level]) ? $level_meta[$level] : [$level, 'bg-secondary'];
							$percent = $pct($count, $total);
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
						<?php if ($report['no_data'] > 0): ?>
							<p class="small text-body-secondary mb-0"><i class="bi bi-info-circle me-1" aria-hidden="true"></i><?= html_escape($report['no_data']) ?> tes belum memiliki data AC.</p>
						<?php endif; ?>
					</div>
				</div>
			</div>

			<!-- Tren -->
			<div class="col-lg-7">
				<div class="card h-100">
					<div class="card-header">
						<h2 class="h6 card-title mb-0">
							<i class="bi bi-graph-up text-primary me-2" aria-hidden="true"></i>Tren Jumlah Pemeriksaan <?= $report['trend_unit'] === 'month' ? 'per Bulan' : 'per Hari' ?>
						</h2>
					</div>
					<div class="card-body">
						<div class="trend-chart" role="img" aria-label="Grafik batang jumlah pemeriksaan">
							<?php foreach ($report['trend'] as $key => $count):
								$label = $report['trend_unit'] === 'month' ? date('M y', strtotime($key . '-01')) : date('d/m', strtotime($key));
								$height = $trend_max > 0 ? max(4, round(($count / $trend_max) * 100)) : 0;
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
			<!-- Per perusahaan -->
			<div class="col-lg-6">
				<div class="card h-100">
					<div class="card-header">
						<h2 class="h6 card-title mb-0"><i class="bi bi-buildings text-primary me-2" aria-hidden="true"></i>Per Perusahaan</h2>
					</div>
					<div class="card-body p-0">
						<div class="table-responsive">
							<table class="table table-hover align-middle mb-0">
								<thead>
									<tr>
										<th scope="col">Perusahaan</th>
										<th scope="col" class="text-end">Pemeriksaan</th>
										<th scope="col" class="text-end">Perlu Perhatian</th>
									</tr>
								</thead>
								<tbody>
									<?php foreach (array_slice($report['companies'], 0, 15) as $company): ?>
										<tr>
											<td class="fw-semibold"><?= html_escape($company['name']) ?></td>
											<td class="text-end"><?= html_escape($company['count']) ?></td>
											<td class="text-end">
												<?php if ($company['abnormal'] > 0): ?>
													<span class="badge bg-warning text-dark"><?= html_escape($company['abnormal']) ?></span>
												<?php else: ?>
													<span class="text-body-secondary">0</span>
												<?php endif; ?>
											</td>
										</tr>
									<?php endforeach; ?>
								</tbody>
							</table>
						</div>
					</div>
					<?php if (count($report['companies']) > 15): ?>
						<div class="card-footer bg-transparent small text-body-secondary">
							Menampilkan 15 dari <?= html_escape(count($report['companies'])) ?> perusahaan teratas.
						</div>
					<?php endif; ?>
				</div>
			</div>

			<!-- Perlu tindak lanjut -->
			<div class="col-lg-6">
				<div class="card h-100">
					<div class="card-header">
						<h2 class="h6 card-title mb-0"><i class="bi bi-exclamation-triangle text-warning me-2" aria-hidden="true"></i>Perlu Tindak Lanjut</h2>
					</div>
					<div class="card-body p-0">
						<?php if (!empty($report['attention'])): ?>
							<div class="table-responsive">
								<table class="table table-hover align-middle mb-0">
									<thead>
										<tr>
											<th scope="col">Pasien</th>
											<th scope="col">Tanggal</th>
											<th scope="col">Hasil</th>
											<th scope="col" class="text-end no-print"><span class="visually-hidden">Aksi</span></th>
										</tr>
									</thead>
									<tbody>
										<?php foreach ($report['attention'] as $item):
											$meta = isset($level_meta[$item['level']]) ? $level_meta[$item['level']] : [$item['level'], 'bg-secondary'];
										?>
											<tr>
												<td>
													<strong class="d-block"><?= html_escape($item['nama']) ?></strong>
													<small class="text-body-secondary"><?= html_escape($item['no_rm']) ?> &bull; <?= html_escape($item['perusahaan']) ?></small>
												</td>
												<td class="text-nowrap"><?= html_escape(date('d M Y', strtotime($item['tanggal']))) ?></td>
												<td><span class="badge <?= html_escape($meta[1]) ?><?= $meta[1] === 'bg-warning' ? ' text-dark' : '' ?>"><?= html_escape($meta[0]) ?></span></td>
												<td class="text-end no-print">
													<a href="<?= html_escape(base_url('view/' . $item['id'])) ?>" class="btn btn-outline-secondary btn-sm" title="Lihat Rekam Medis" aria-label="Lihat Rekam Medis <?= html_escape($item['nama']) ?>">
														<i class="bi bi-eye" aria-hidden="true"></i>
													</a>
												</td>
											</tr>
										<?php endforeach; ?>
									</tbody>
								</table>
							</div>
							<?php if ($report['abnormal'] > count($report['attention'])): ?>
								<div class="card-footer bg-transparent small text-body-secondary">
									Menampilkan 10 kasus terberat dari <?= html_escape($report['abnormal']) ?> kasus. Unduh Excel untuk daftar lengkap.
								</div>
							<?php endif; ?>
						<?php else: ?>
							<div class="text-center py-5 empty-state">
								<i class="bi bi-emoji-smile display-5 text-body-secondary" aria-hidden="true"></i>
								<p class="mt-3 mb-0 text-body-secondary">Tidak ada hasil di luar batas normal pada periode ini.</p>
							</div>
						<?php endif; ?>
					</div>
				</div>
			</div>
		</div>
	<?php endif; ?>
</div>
