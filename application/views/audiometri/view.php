<?php
// File: application/views/audiometri/view.php
$is_reviewed = !empty($test_data['impression']);
?>
<div class="container-fluid p-4">
	<!-- Top Bar Navigation & Actions -->
	<div class="page-heading">
		<div>
			<span class="eyebrow"><i class="fas fa-file-medical me-1"></i> Rekam Medis Audiometri</span>
			<h1>Detail Pemeriksaan Pasien</h1>
			<p>Data lengkap audiogram nada murni, tabel ambang pendengaran, dan kesimpulan dokter.</p>
		</div>
		<div class="d-flex align-items-center gap-2 flex-wrap">
			<a href="<?= html_escape(base_url('list_tests')) ?>" class="btn btn-outline-secondary">
				<i class="fas fa-arrow-left me-1"></i> Kembali
			</a>
			<a href="<?= html_escape(base_url('edit/' . $test_data['id'])) ?>" class="btn btn-outline-primary">
				<i class="fas fa-pen-to-square me-1"></i> Edit Data
			</a>
			<a href="<?= html_escape(base_url('export_pdf/' . $test_data['id'])) ?>" class="btn btn-outline-danger" target="_blank">
				<i class="fas fa-file-pdf me-1"></i> Unduh PDF
			</a>
			<button onclick="showDeleteModal(<?= html_escape((int) $test_data['id']) ?>)" class="btn btn-danger">
				<i class="fas fa-trash-can me-1"></i> Hapus
			</button>
		</div>
	</div>

	<!-- Patient Dossier Hero Card -->
	<div class="card mb-4 shadow-sm border-0">
		<div class="card-body p-4">
			<div class="row align-items-center g-4">
				<div class="col-lg-7">
					<div class="d-flex align-items-start gap-3">
						<div style="width: 54px; height: 54px; border-radius: 16px; background: linear-gradient(135deg, #2563eb 0%, #06b6d4 100%); color: #fff; display: grid; place-items: center; font-size: 22px; font-weight: 800; flex-shrink: 0; box-shadow: 0 4px 14px rgba(37, 99, 235, 0.3);">
							<?= html_escape(strtoupper(substr($test_data['nama'] ?? 'P', 0, 1))) ?>
						</div>
						<div>
							<div class="d-flex align-items-center gap-2 flex-wrap">
								<h3 class="fw-bold mb-0 text-dark"><?= html_escape($test_data['nama']) ?></h3>
								<span class="badge bg-light text-dark border font-monospace">RM: <?= html_escape($test_data['no_rm']) ?></span>
								<?php if ($is_reviewed): ?>
									<span class="badge bg-success"><i class="fas fa-circle-check"></i> Sudah Direview</span>
								<?php else: ?>
									<span class="badge bg-warning"><i class="fas fa-clock"></i> Menunggu Review</span>
								<?php endif; ?>
							</div>
							<div class="text-muted mt-2 d-flex align-items-center gap-3 flex-wrap small">
								<span><i class="fas fa-venus-mars text-primary me-1"></i> <?= html_escape($test_data['jenis_kelamin'] ?? '-') ?></span>
								<span>&bull;</span>
								<span><i class="fas fa-user-clock text-primary me-1"></i> <?= html_escape($test_data['umur'] ?? '-') ?> Tahun</span>
								<span>&bull;</span>
								<span><i class="fas fa-building text-primary me-1"></i> <?= html_escape($test_data['perusahaan'] ?? '-') ?></span>
								<span>&bull;</span>
								<span><i class="fas fa-briefcase text-primary me-1"></i> <?= html_escape($test_data['jabatan'] ?? '-') ?></span>
							</div>
						</div>
					</div>
				</div>
				<div class="col-lg-5">
					<div class="p-3 bg-light rounded-3 d-flex justify-content-around text-center border">
						<div>
							<small class="text-uppercase text-muted fw-bold d-block" style="font-size: 11px;">Tanggal Tes</small>
							<strong class="text-dark fs-6"><?= html_escape(date('d M Y', strtotime($test_data['tanggal_tes']))) ?></strong>
						</div>
						<div class="border-end"></div>
						<div>
							<small class="text-uppercase text-muted fw-bold d-block" style="font-size: 11px;">Terakhir Disimpan</small>
							<strong class="text-dark fs-6"><?= html_escape(!empty($test_data['updated_at']) ? date('d/m/Y H:i', strtotime($test_data['updated_at'])) : '-') ?></strong>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<!-- Audiogram Charts -->
	<div class="card mb-4 shadow-sm border-0">
		<div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
			<h5 class="card-title mb-0 fw-bold text-dark">
				<i class="fas fa-chart-line text-primary me-2"></i>
				Audiogram Nada Murni (Pure Tone Audiometry)
			</h5>
			<span class="badge bg-light text-secondary border">AC &amp; BC Comparison</span>
		</div>
		<div class="card-body p-4">
			<div class="row g-4">
				<!-- Right Ear Chart -->
				<div class="col-lg-6">
					<div class="ear-chart-view border rounded-3 p-3 bg-white">
						<div class="ear-title d-flex justify-content-between align-items-center mb-3">
							<span class="fw-bold"><i class="fas fa-ear-listen me-1 text-danger"></i> TELINGA KANAN (AS)</span>
							<span class="badge bg-danger text-white">RED / MERAH</span>
						</div>
						<div class="chart-container-view mb-3" style="background: #fafafa; border-radius: 8px; border: 1px solid #e2e8f0;">
							<svg width="100%" height="100%" id="right-chart-view">
								<!-- Grid akan di-generate oleh JavaScript -->
							</svg>
						</div>
						<!-- Right Ear Data -->
						<div class="table-responsive">
							<div class="card border">
								<div class="card-header bg-light py-2 px-3 border-bottom d-flex justify-content-between align-items-center">
									<strong class="text-danger small"><i class="fas fa-table me-1"></i> Ambang Pendengaran Kanan</strong>
								</div>
								<div class="card-body p-0">
									<table class="table table-sm mb-0">
										<thead class="table-light">
											<tr>
												<th>Frekuensi</th>
												<th>250</th>
												<th>500</th>
												<th>1000</th>
												<th>2000</th>
												<th>3000</th>
												<th>4000</th>
												<th>6000</th>
												<th>Rata²</th>
											</tr>
										</thead>
										<tbody>
											<?php
											$frequencies = ['250', '500', '1000', '2000', '3000', '4000', '6000'];
											?>
											<tr>
												<td style="font-weight: bold;">AC (dB)</td>
												<td style="padding-left: 10px;"><?= html_escape($test_data['right_ac_250'] !== null ? $test_data['right_ac_250'] : '-') ?></td>
												<td style="padding-left: 10px;"><?= html_escape($test_data['right_ac_500']) ?></td>
												<td style="padding-left: 10px;"><?= html_escape($test_data['right_ac_1000']) ?></td>
												<td style="padding-left: 10px;"><?= html_escape($test_data['right_ac_2000']) ?></td>
												<td style="padding-left: 10px;"><?= html_escape($test_data['right_ac_3000']) ?></td>
												<td style="padding-left: 10px;"><?= html_escape($test_data['right_ac_4000']) ?></td>
												<td style="padding-left: 10px;"><?= html_escape($test_data['right_ac_6000']) ?></td>
												<?php
												$right_ac_values = [];
												foreach ($frequencies as $freq) {
													$value = $test_data['right_ac_' . $freq];
													if ($value !== null) $right_ac_values[] = $value;
												}
												?>
												<td style="padding-left: 10px;"><?= html_escape(!empty($right_ac_values) ? number_format(array_sum($right_ac_values) / count($right_ac_values), 1) : '-') ?></td>
											</tr>
											<tr>
												<td style="font-weight: bold;">BC (dB)</td>
												<td style="padding-left: 10px;"><?= html_escape($test_data['right_bc_250'] !== null ? $test_data['right_bc_250'] : '-') ?></td>
												<td style="padding-left: 10px;"><?= html_escape($test_data['right_bc_500']) ?></td>
												<td style="padding-left: 10px;"><?= html_escape($test_data['right_bc_1000']) ?></td>
												<td style="padding-left: 10px;"><?= html_escape($test_data['right_bc_2000']) ?></td>
												<td style="padding-left: 10px;"><?= html_escape($test_data['right_bc_3000']) ?></td>
												<td style="padding-left: 10px;"><?= html_escape($test_data['right_bc_4000']) ?></td>
												<td style="padding-left: 10px;"><?= html_escape($test_data['right_bc_6000']) ?></td>
												<?php
												$right_bc_values = [];
												foreach ($frequencies as $freq) {
													$value = $test_data['right_bc_' . $freq];
													if ($value !== null) $right_bc_values[] = $value;
												}
												?>
												<td style="padding-left: 10px;"><?= html_escape(!empty($right_bc_values) ? number_format(array_sum($right_bc_values) / count($right_bc_values), 1) : '-') ?></td>
											</tr>
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
				</div>

				<!-- Left Ear Chart -->
				<div class="col-lg-6">
					<div class="ear-chart-view border rounded-3 p-3 bg-white">
						<div class="ear-title d-flex justify-content-between align-items-center mb-3">
							<span class="fw-bold"><i class="fas fa-ear-listen me-1 text-primary"></i> TELINGA KIRI (AD)</span>
							<span class="badge bg-primary text-white">BLUE / BIRU</span>
						</div>
						<div class="chart-container-view mb-3" style="background: #fafafa; border-radius: 8px; border: 1px solid #e2e8f0;">
							<svg width="100%" height="100%" id="left-chart-view">
								<!-- Grid akan di-generate oleh JavaScript -->
							</svg>
						</div>
						<!-- Left Ear Data -->
						<div class="table-responsive">
							<div class="card border">
								<div class="card-header bg-light py-2 px-3 border-bottom d-flex justify-content-between align-items-center">
									<strong class="text-primary small"><i class="fas fa-table me-1"></i> Ambang Pendengaran Kiri</strong>
								</div>
								<div class="card-body p-0">
									<table class="table table-sm mb-0">
										<thead class="table-light">
											<tr>
												<th>Frekuensi</th>
												<th>250</th>
												<th>500</th>
												<th>1000</th>
												<th>2000</th>
												<th>3000</th>
												<th>4000</th>
												<th>6000</th>
												<th>Rata²</th>
											</tr>
										</thead>
										<tbody>
											<tr>
												<td style="font-weight: bold;">AC (dB)</td>
												<td style="padding-left: 10px;"><?= html_escape($test_data['left_ac_250'] !== null ? $test_data['left_ac_250'] : '-') ?></td>
												<td style="padding-left: 10px;"><?= html_escape($test_data['left_ac_500']) ?></td>
												<td style="padding-left: 10px;"><?= html_escape($test_data['left_ac_1000']) ?></td>
												<td style="padding-left: 10px;"><?= html_escape($test_data['left_ac_2000']) ?></td>
												<td style="padding-left: 10px;"><?= html_escape($test_data['left_ac_3000']) ?></td>
												<td style="padding-left: 10px;"><?= html_escape($test_data['left_ac_4000']) ?></td>
												<td style="padding-left: 10px;"><?= html_escape($test_data['left_ac_6000']) ?></td>
												<?php
												$left_ac_values = [];
												foreach ($frequencies as $freq) {
													$value = $test_data['left_ac_' . $freq];
													if ($value !== null) $left_ac_values[] = $value;
												}
												?>
												<td style="padding-left: 10px;"><?= html_escape(!empty($left_ac_values) ? number_format(array_sum($left_ac_values) / count($left_ac_values), 1) : '-') ?></td>
											</tr>
											<tr>
												<td style="font-weight: bold;">BC (dB)</td>
												<td style="padding-left: 10px;"><?= html_escape($test_data['left_bc_250'] !== null ? $test_data['left_bc_250'] : '-') ?></td>
												<td style="padding-left: 10px;"><?= html_escape($test_data['left_bc_500']) ?></td>
												<td style="padding-left: 10px;"><?= html_escape($test_data['left_bc_1000']) ?></td>
												<td style="padding-left: 10px;"><?= html_escape($test_data['left_bc_2000']) ?></td>
												<td style="padding-left: 10px;"><?= html_escape($test_data['left_bc_3000']) ?></td>
												<td style="padding-left: 10px;"><?= html_escape($test_data['left_bc_4000']) ?></td>
												<td style="padding-left: 10px;"><?= html_escape($test_data['left_bc_6000']) ?></td>
												<?php
												$left_bc_values = [];
												foreach ($frequencies as $freq) {
													$value = $test_data['left_bc_' . $freq];
													if ($value !== null) $left_bc_values[] = $value;
												}
												?>
												<td style="padding-left: 10px;"><?= html_escape(!empty($left_bc_values) ? number_format(array_sum($left_bc_values) / count($left_bc_values), 1) : '-') ?></td>
											</tr>
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<!-- Legend -->
		<div class="row mb-4">
			<div class="legend-view">
				<div class="legend-item">
					<div class="legend-symbol"></div>
					<span>AC Right</span>
				</div>
				<div class="legend-item">
					<div class="diamond-symbol"></div>
					<span>AC Left</span>
				</div>
				<div class="legend-item">
					<div style="color: red;">◀</div>
					<span>BC Right</span>
				</div>
				<div class="legend-item">
					<div style="color: red;">▶</div>
					<span>BC Left</span>
				</div>
			</div>
		</div>
	</div>

	<!-- Audiometric Data Tables -->


	<!-- Impression / Clinical Conclusion -->
	<div class="card mb-4 shadow-sm border-0">
		<div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
			<h5 class="card-title mb-0 fw-bold text-dark">
				<i class="fas fa-stethoscope text-primary me-2"></i>
				Impression & Kesimpulan Dokter
			</h5>
			<?php if ($is_reviewed): ?>
				<span class="badge bg-success"><i class="fas fa-circle-check"></i> Terverifikasi</span>
			<?php else: ?>
				<a href="<?= html_escape(base_url('review_dokter/' . $test_data['id'])) ?>" class="btn btn-sm btn-warning">
					<i class="fas fa-pen-to-square me-1"></i> Tinjau Sekarang
				</a>
			<?php endif; ?>
		</div>
		<div class="card-body p-4">
			<div class="p-4 rounded-3 border <?= $is_reviewed ? 'bg-light' : 'bg-warning-subtle text-dark' ?>" style="min-height: 100px;">
				<?php if ($is_reviewed): ?>
					<div class="d-flex align-items-start gap-3">
						<i class="fas fa-quote-left text-muted fs-3 opacity-25 flex-shrink-0"></i>
						<div class="flex-grow-1">
							<p class="fs-5 mb-0 fw-medium text-dark" style="white-space: pre-line; line-height: 1.7;">
								<?= html_escape($test_data['impression']) ?>
							</p>
						</div>
					</div>
				<?php else: ?>
					<div class="text-center py-3 text-muted">
						<i class="fas fa-hourglass-half fa-2x mb-2 text-warning d-block"></i>
						<span class="fw-bold d-block text-dark">Belum Direview oleh Dokter</span>
						<small>Pemeriksaan ini sedang menunggu tinjauan dan pengisian impression oleh dokter pemeriksa.</small>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</div>

	<!-- Action Footer Buttons -->
	<div class="d-flex justify-content-between align-items-center flex-wrap gap-2 pt-2 pb-4">
		<a href="<?= html_escape(base_url('list_tests')) ?>" class="btn btn-secondary">
			<i class="fas fa-arrow-left me-1"></i> Kembali ke Daftar
		</a>
		<div class="d-flex gap-2 flex-wrap">
			<a href="<?= html_escape(base_url('edit/' . $test_data['id'])) ?>" class="btn btn-outline-primary">
				<i class="fas fa-pen-to-square me-1"></i> Edit Data
			</a>
			<a href="<?= html_escape(base_url('export_pdf/' . $test_data['id'])) ?>" class="btn btn-outline-danger" target="_blank">
				<i class="fas fa-file-pdf me-1"></i> Unduh PDF
			</a>
			<a href="<?= html_escape(base_url('create')) ?>" class="btn btn-primary">
				<i class="fas fa-plus me-1"></i> Pemeriksaan Baru
			</a>
		</div>
	</div>
</div>

<!-- Hidden data for JavaScript -->
<script type="application/json" id="test-data">
	<?= json_encode($test_data, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>
</script>


<script>
	function showDeleteModal(id) {
		Swal.fire({
			title: 'Konfirmasi Hapus',
			text: 'Yakin ingin menghapus data ini? Data yang dihapus tidak dapat dikembalikan!',
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#d33',
			cancelButtonColor: '#3085d6',
			confirmButtonText: 'Ya, Hapus!',
			cancelButtonText: 'Batal',
			reverseButtons: true
		}).then((result) => {
			if (result.isConfirmed) {
				// Show loading state
				Swal.fire({
					title: 'Menghapus...',
					text: 'Mohon tunggu sebentar',
					allowOutsideClick: false,
					allowEscapeKey: false,
					showConfirmButton: false,
					didOpen: () => {
						Swal.showLoading();
					}
				});

				// Make AJAX request to delete
				fetch(<?= json_encode(base_url('delete/'), JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?> + id, {
					method: 'POST',
					body: withCsrf(),
					headers: {
						'X-Requested-With': 'XMLHttpRequest'
					}
				})
				.then(response => response.json())
				.then(data => {
					if (data.status) {
						Swal.fire({
							title: 'Berhasil!',
							text: data.message || 'Data berhasil dihapus',
							icon: 'success',
							timer: 3000,
							timerProgressBar: true,
							showConfirmButton: false
						}).then(() => {
							window.location.href = <?= json_encode(base_url('list_tests'), JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>;
						});
					} else {
						Swal.fire({
							title: 'Gagal!',
							text: data.message || 'Gagal menghapus data',
							icon: 'error',
							confirmButtonColor: '#3085d6'
						});
					}
				})
				.catch(error => {
					console.error('Error:', error);
					Swal.fire({
						title: 'Error!',
						text: 'Terjadi kesalahan saat menghapus data',
						icon: 'error',
						confirmButtonColor: '#3085d6'
					});
				});
			}
		});
	}

	// JavaScript untuk menampilkan audiogram
	const testData = JSON.parse(document.getElementById('test-data').textContent);

	function initViewCharts() {
		createViewChart('right-chart-view');
		createViewChart('left-chart-view');
		updateViewChart('right', 'right-chart-view');
		updateViewChart('left', 'left-chart-view');
	}

	function createViewChart(chartId) {
		const svg = document.getElementById(chartId);
		if (!svg) return;

		svg.innerHTML = '';

		const width = svg.clientWidth || 400;
		const height = svg.clientHeight || 300;

		// Create grid
		const gridGroup = document.createElementNS('http://www.w3.org/2000/svg', 'g');

		// Vertical lines (frequencies)
		const frequencies = [250, 500, 1000, 2000, 3000, 4000, 6000];
		frequencies.forEach((freq, i) => {
			const x = (i + 1) * (width / 8);
			const line = document.createElementNS('http://www.w3.org/2000/svg', 'line');
			line.setAttribute('x1', x);
			line.setAttribute('y1', 0);
			line.setAttribute('x2', x);
			line.setAttribute('y2', height);
			line.setAttribute('class', 'chart-line');
			gridGroup.appendChild(line);

			// Frequency labels
			const text = document.createElementNS('http://www.w3.org/2000/svg', 'text');
			text.setAttribute('x', x);
			text.setAttribute('y', height - 5);
			text.setAttribute('text-anchor', 'middle');
			text.setAttribute('class', 'axis-label');
			text.textContent = freq;
			gridGroup.appendChild(text);
		});

		// Horizontal lines (dB levels)
		for (let db = -10; db <= 140; db += 10) {
			const y = ((db + 10) / 150) * height;
			const line = document.createElementNS('http://www.w3.org/2000/svg', 'line');
			line.setAttribute('x1', 0);
			line.setAttribute('y1', y);
			line.setAttribute('x2', width);
			line.setAttribute('y2', y);
			line.setAttribute('class', 'chart-line');
			gridGroup.appendChild(line);

			// dB labels
			const text = document.createElementNS('http://www.w3.org/2000/svg', 'text');
			text.setAttribute('x', 5);
			text.setAttribute('y', y - 5);
			text.setAttribute('class', 'axis-label');
			text.textContent = db;
			gridGroup.appendChild(text);
		}

		svg.appendChild(gridGroup);
	}

	function updateViewChart(ear, chartId) {
		const svg = document.getElementById(chartId);
		if (!svg) return;

		const width = svg.clientWidth || 400;
		const height = svg.clientHeight || 300;

		const frequencies = [250, 500, 1000, 2000, 3000, 4000, 6000];

		// Draw BC (Air Conduction) line
		const acPoints = [];
		frequencies.forEach((freq, i) => {
			const value = parseFloat(testData[`${ear}_bc_${freq}`]);

			if (!isNaN(value)) {
				const x = (i + 1) * (width / 8);
				const y = ((value + 10) / 150) * height;
				acPoints.push({
					x,
					y
				});

				// Create BC point
				const text = document.createElementNS('http://www.w3.org/2000/svg', 'text');
				text.setAttribute('x', x);
				text.setAttribute('y', y + 5);
				text.setAttribute('text-anchor', 'middle');
				text.setAttribute('alignment-baseline', 'middle');
				text.setAttribute('class', 'arrow-symbol');
				text.textContent = ear === 'right' ? '◀' : '▶';
				svg.appendChild(text);
			}
		});

		// Create BC line connecting points
		if (acPoints.length > 1) {
			const path = document.createElementNS('http://www.w3.org/2000/svg', 'path');
			let d = `M ${acPoints[0].x} ${acPoints[0].y}`;
			for (let i = 1; i < acPoints.length; i++) {
				d += ` L ${acPoints[i].x} ${acPoints[i].y}`;
			}
			path.setAttribute('d', d);
			path.setAttribute('class', 'hearing-line');
			svg.appendChild(path);
		}

		// Draw AC (Bone Conduction) line
		const bcPoints = [];
		frequencies.forEach((freq, i) => {
			const value = parseFloat(testData[`${ear}_ac_${freq}`]);

			if (!isNaN(value)) {
				const x = (i + 1) * (width / 8);
				const y = ((value + 10) / 150) * height;
				bcPoints.push({
					x,
					y
				});

				// Create AC point
				if (ear === 'left') {
					const diamond = document.createElementNS('http://www.w3.org/2000/svg', 'rect');
					diamond.setAttribute('x', x - 5);
					diamond.setAttribute('y', y - 5);
					diamond.setAttribute('width', 7);
					diamond.setAttribute('height', 7);
					diamond.setAttribute('transform', `rotate(45 ${x} ${y})`);
					diamond.setAttribute('class', 'diamond');
					svg.appendChild(diamond);
				} else {
					const circle = document.createElementNS('http://www.w3.org/2000/svg', 'circle');
					circle.setAttribute('cx', x);
					circle.setAttribute('cy', y);
					circle.setAttribute('class', 'hearing-point bc');
					svg.appendChild(circle);
				}
			}
		});

		// Create AC line connecting points
		if (bcPoints.length > 1) {
			const path = document.createElementNS('http://www.w3.org/2000/svg', 'path');
			let d = `M ${bcPoints[0].x} ${bcPoints[0].y}`;
			for (let i = 1; i < bcPoints.length; i++) {
				d += ` L ${bcPoints[i].x} ${bcPoints[i].y}`;
			}
			path.setAttribute('d', d);
			path.setAttribute('class', 'hearing-line bc');
			svg.appendChild(path);
		}
	}

	// Initialize charts when page loads
	window.addEventListener('load', initViewCharts);
	window.addEventListener('resize', () => {
		setTimeout(initViewCharts, 100);
	});
</script>

<!-- Styles handled cleanly by workspace.css and calm.css -->
