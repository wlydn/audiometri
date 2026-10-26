<?php
// File: application/views/audiometri/view.php
?>
<div class="container-fluid p-4">
	<div class="row mb-4">
		<div class="col-12">
			<div class="d-flex justify-content-between align-items-center">
				<div>
					<h1 class="h2 mb-4">
						<i class="fas fa-eye text-primary me-3"></i>
						Detail Tes Audiometri
					</h1>
					<nav aria-label="breadcrumb">
						<ol class="breadcrumb">
							<li class="breadcrumb-item"><a href="<?= html_escape(base_url()) ?>">Dashboard</a></li>
							<li class="breadcrumb-item"><a href="<?= html_escape(base_url('list_tests')) ?>">Daftar Tes</a></li>
							<li class="breadcrumb-item active"><?= html_escape($test_data['nama']) ?></li>
						</ol>
					</nav>
				</div>
				<div class="btn-group">
					<a href="<?= html_escape(base_url('edit/' . $test_data['id'])) ?>" class="btn btn-warning">
						<i class="fas fa-edit me-1"></i> Edit
					</a>
					<a href="<?= html_escape(base_url('export_pdf/' . $test_data['id'])) ?>"
						class="btn btn-info" target="_blank">
						<i class="fas fa-file-pdf me-1"></i> Export PDF
					</a>
					<button onclick="showDeleteModal(<?= html_escape((int) $test_data['id']) ?>)"
						class="btn btn-danger">
						<i class="fas fa-trash me-1"></i> Hapus
					</button>
				</div>
			</div>
		</div>
	</div>

	<!-- Patient Information -->
	<div class="card mb-2">
		<div class="card-header bg-primary text-white">
			<h5 class="card-title mb-0">
				<i class="fas fa-user me-2"></i>
				Informasi Pasien
			</h5>
		</div>
		<div class="card-body">
			<div class="row">
				<div class="col-md-6">
					<table class="table table-borderless">
						<tr>
							<td class="fw-bold">Nama:</td>
							<td><?= html_escape($test_data['nama']) ?></td>
						</tr>
						<tr>
							<td class="fw-bold">No RM:</td>
							<td><?= html_escape($test_data['no_rm']) ?></td>
						</tr>
						<tr>
							<td class="fw-bold">Umur:</td>
							<td><?= html_escape($test_data['umur']) ?> tahun</td>
						</tr>
						<tr>
							<td class="fw-bold">Jenis Kelamin:</td>
							<td><?= html_escape($test_data['jenis_kelamin']) ?></td>
						</tr>
					</table>
				</div>
				<div class="col-md-6">
					<table class="table table-borderless">
						<tr>
							<td class="fw-bold">Perusahaan:</td>
							<td><?= html_escape($test_data['perusahaan']) ?></td>
						</tr>
						<tr>
							<td class="fw-bold">Jabatan:</td>
							<td><?= html_escape($test_data['jabatan']) ?></td>
						</tr>
						<tr>
							<td class="fw-bold">Tanggal Tes:</td>
							<td><?= html_escape(date('d F Y', strtotime($test_data['tanggal_tes']))) ?></td>
						</tr>
						<tr>
							<td class="fw-bold">Terakhir Update:</td>
							<td><?= html_escape(date('d F Y H:i', strtotime($test_data['updated_at']))) ?></td>
						</tr>
					</table>
				</div>
			</div>
		</div>
	</div>

	<!-- Audiogram Charts -->
	<div class="card mb-4">
		<div class="card-header bg-success text-white">
			<h5 class="card-title mb-0">
				<i class="fas fa-chart-line me-2"></i>
				Audiogram
			</h5>
		</div>
		<div class="card-body">
			<div class="row">
				<!-- Right Ear Chart -->
				<div class="col-md-6 mb-4">
					<div class="ear-chart-view">
						<div class="ear-title">TELINGA KANAN</div>
						<div class="chart-container-view">
							<svg width="100%" height="100%" id="right-chart-view">
								<!-- Grid akan di-generate oleh JavaScript -->
							</svg>
						</div>
						<!-- Right Ear Data -->
						<div class="table-responsive">
							<div class="card">
								<div class="card-header bg-info text-white">
									<h6 class="card-title mb-0">Data Telinga Kanan</h6>
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
				<div class="col-md-6 mb-4">
					<div class="ear-chart-view">
						<div class="ear-title">TELINGA KIRI</div>
						<div class="chart-container-view">
							<svg width="100%" height="100%" id="left-chart-view">
								<!-- Grid akan di-generate oleh JavaScript -->
							</svg>
						</div>
						<!-- Left Ear Data -->
						<div class="table-responsive">
							<div class="card">
								<div class="card-header bg-warning text-dark">
									<h6 class="card-title mb-0">Data Telinga Kiri</h6>
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


	<!-- Impression -->
	<div class="card mb-4">
		<div class="card-header bg-secondary text-white">
			<h5 class="card-title mb-0">
				<i class="fas fa-stethoscope me-2"></i>
				Impression / Kesimpulan
			</h5>
		</div>
		<div class="card-body">
			<div class="impression">
				<p style="font-size: 24px;">
					<?= empty($test_data['impression']) ? 'Belum direview oleh dokter' : html_escape($test_data['impression']) ?>
				</p>
			</div>
		</div>
	</div>

	<!-- Action Buttons -->
	<div class="text-center">
		<a href="<?= html_escape(base_url('list_tests')) ?>" class="btn btn-secondary me-2">
			<i class="fas fa-arrow-left me-1"></i> Kembali ke Daftar
		</a>
		<a href="<?= html_escape(base_url('edit/' . $test_data['id'])) ?>" class="btn btn-warning me-2">
			<i class="fas fa-edit me-1"></i> Edit Data
		</a>
		<a href="<?= html_escape(base_url('export_pdf/' . $test_data['id'])) ?>"
			class="btn btn-info me-2" target="_blank">
			<i class="fas fa-file-pdf me-1"></i> Export PDF
		</a>
		<a href="<?= html_escape(base_url('create')) ?>" class="btn btn-success">
			<i class="fas fa-plus me-1"></i> Tes Baru
		</a>
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

<style>
	/* Styles for view page */
	.ear-chart-view {
		background: white;
		border-radius: 15px;
		padding: 20px;
		box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
		border: 2px solid #e0e0e0;
	}

	.ear-title {
		text-align: center;
		font-size: 1.3em;
		font-weight: bold;
		color: #2c3e50;
		margin-bottom: 15px;
		padding: 12px;
		background: linear-gradient(45deg, #3498db, #2980b9);
		color: white;
		border-radius: 8px;
	}

	.chart-container-view {
		position: relative;
		height: 350px;
		border: 2px solid #34495e;
		margin: 15px 0;
		background: #ffffff;
	}

	.legend-view {
		display: flex;
		justify-content: center;
		gap: 30px;
		padding: 15px;
		border-radius: 10px;
		flex-wrap: wrap;
	}

	.legend-item {
		display: flex;
		align-items: center;
		gap: 8px;
		font-weight: bold;
		color: #2c3e50;
		font-size: 14px;
	}

	.legend-symbol {
		width: 16px;
		height: 16px;
		border-radius: 50%;
		background: #3498db;
	}

	.diamond-symbol {
		width: 12px;
		height: 12px;
		transform: rotate(45deg);
		background: #3498db;
	}

	/* Chart elements styles */
	.chart-line {
		stroke: #bdc3c7;
		stroke-width: 1;
	}

	.hearing-line {
		stroke: #e74c3c;
		stroke-width: 3;
		fill: none;
		stroke-dasharray: 5, 5;
	}

	.hearing-line.bc {
		stroke: #3498db;
		stroke-width: 3;
		stroke-dasharray: none;
	}

	.hearing-point {
		fill: #e74c3c;
		stroke: #c0392b;
		stroke-width: 2;
		r: 4;
	}

	.hearing-point.bc {
		fill: #3498db;
		stroke: #2980b9;
	}

	.impression {
		background: linear-gradient(135deg, #f8f9fa, rgb(234, 245, 241));
		padding: 25px;
		border-radius: 10px;
	}

	.axis-label {
		fill: #2c3e50;
		font-size: 11px;
		font-weight: bold;
	}

	.arrow-symbol {
		font-size: 14px;
		fill: #c0392b;
		font-weight: normal;
		pointer-events: none;
		opacity: 0.8;
	}

	.diamond {
		fill: #3498db;
		stroke: #2980b9;
		pointer-events: none;
	}

	@media (max-width: 768px) {
		.legend-view {
			flex-direction: column;
			align-items: center;
		}

		.chart-container-view {
			height: 250px;
		}
	}
</style>
