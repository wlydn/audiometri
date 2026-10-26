<!-- Audiometri Form View -->
<div class="container-fluid">
	<div class="audiometri-container">
		<div class="header">
			<h1>HASIL TES AUDIOMETRI</h1>
			<p>data hasil tes audiometri</p>
		</div>

		<?php if ($this->session->flashdata('error')): ?>
			<div class="alert alert-danger alert-dismissible fade show" role="alert">
				<?= html_escape($this->session->flashdata('error')) ?>
				<button type="button" class="close" data-dismiss="alert">&times;</button>
			</div>
		<?php endif; ?>

		<?php if ($this->session->flashdata('success')): ?>
			<div class="alert alert-success d-flex align-items-center" role="alert">
				<?= html_escape($this->session->flashdata('success')) ?>
				<svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Success:">
					<use xlink:href="#check-circle-fill" />
				</svg>
				<div>
				</div>
			</div>
		<?php endif; ?>

		<?= form_open(current_url(), ['id' => 'audiometri-review']) ?>
		<div class="patient-info">
			<div class="info-group">
				<label for="perusahaan">Perusahaan:</label>
				<span><?= html_escape($test_data['perusahaan'] ?? 'N/A') ?></span>
			</div>

			<div class="info-group">
				<label for="jabatan">Jabatan:</label>
				<span><?= html_escape($test_data['jabatan'] ?? 'N/A') ?></span>
			</div>

			<div class="info-group">
				<label for="nama">Nama:</label>
				<span><?= html_escape($test_data['nama'] ?? 'N/A') ?></span>
			</div>

			<div class="info-group">
				<label for="umur">Umur:</label>
				<span><?= html_escape($test_data['umur'] ?? 'N/A') ?> Tahun</span>
			</div>

			<div class="info-group">
				<label for="tanggal_tes">Tanggal Tes:</label>
				<span><?= html_escape(date('d F Y', strtotime($test_data['tanggal_tes']))) ?></span>
			</div>

			<div class="info-group">
				<label for="jenis_kelamin">Jenis Kelamin:</label>
				<span><?= html_escape($test_data['jenis_kelamin'] ?? 'N/A') ?></span>
			</div>

			<div class="info-group">
				<label for="no_rm">No RM:</label>
				<span><?= html_escape($test_data['no_rm'] ?? 'N/A') ?></span>
			</div>
		</div>

		<div class="audiogram-section">
			<h3 class="audiogram-title">AUDIOGRAM</h3>

			<div class="ear-charts">
				<!-- Right Ear Chart -->
				<div class="ear-chart">
					<div class="ear-title">TELINGA KANAN</div>
					<div class="chart-container">
						<svg width="100%" height="100%" id="right-chart-view">
							<!-- Grid akan di-generate oleh JavaScript -->
						</svg>
					</div>
					<div class="frequency-table">
						<div class="table-header right-ear-header">
							<h4>Data Telinga Kanan</h4>
						</div>
						<table class="audiometry-table">
							<thead>
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
								<tr class="ac-row">
									<td><strong>AC (dB)</strong></td>
									<td id="right-ac-250-display"><?= html_escape($test_data['right_ac_250'] !== null ? $test_data['right_ac_250'] : '-') ?></td>
									<td id="right-ac-500-display"><?= html_escape($test_data['right_ac_500']) ?></td>
									<td id="right-ac-1000-display"><?= html_escape($test_data['right_ac_1000']) ?></td>
									<td id="right-ac-2000-display"><?= html_escape($test_data['right_ac_2000']) ?></td>
									<td id="right-ac-3000-display"><?= html_escape($test_data['right_ac_3000']) ?></td>
									<td id="right-ac-4000-display"><?= html_escape($test_data['right_ac_4000']) ?></td>
									<td id="right-ac-6000-display"><?= html_escape($test_data['right_ac_6000']) ?></td>
									<?php
									$right_ac_values = [];
									foreach ($frequencies as $freq) {
										$right_ac_values[] = $test_data['right_ac_' . $freq];
									}
									$right_ac_average = array_sum($right_ac_values) / count($right_ac_values);
									?>
									<td id="right-ac-average" class="average-cell"><?= html_escape(!empty($right_ac_values) ? number_format(array_sum($right_ac_values) / count($right_ac_values), 1) : '0.0') ?></td>
								</tr>
								<tr class="bc-row">
									<td><strong>BC (dB)</strong></td>
									<td id="right-bc-250-display"><?= html_escape($test_data['right_bc_250'] !== null ? $test_data['right_bc_250'] : '-') ?></td>
									<td id="right-bc-500-display"><?= html_escape($test_data['right_bc_500']) ?></td>
									<td id="right-bc-1000-display"><?= html_escape($test_data['right_bc_1000']) ?></td>
									<td id="right-bc-2000-display"><?= html_escape($test_data['right_bc_2000']) ?></td>
									<td id="right-bc-3000-display"><?= html_escape($test_data['right_bc_3000']) ?></td>
									<td id="right-bc-4000-display"><?= html_escape($test_data['right_bc_4000']) ?></td>
									<td id="right-bc-6000-display"><?= html_escape($test_data['right_bc_6000']) ?></td>
									<?php
									$right_bc_values = [];
									foreach ($frequencies as $freq) {
										$value = $test_data['right_bc_' . $freq];
										if ($value !== null) $right_bc_values[] = $value;
									}
									?>
									<td id="right-bc-average" class="average-cell"><?= html_escape(!empty($right_bc_values) ? number_format(array_sum($right_bc_values) / count($right_bc_values), 1) : '0.0') ?></td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>

				<!-- Left Ear Chart -->
				<div class="ear-chart">
					<div class="ear-title">TELINGA KIRI</div>
					<div class="chart-container">
						<svg width="100%" height="100%" id="left-chart-view">
							<!-- Grid akan di-generate oleh JavaScript -->
						</svg>
					</div>

					<div class="frequency-table">
						<div class="table-header left-ear-header">
							<h4>Data Telinga Kiri</h4>
						</div>
						<table class="audiometry-table">
							<thead>
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
								<tr class="ac-row">
									<td><strong>AC (dB)</strong></td>
									<td id="left-ac-250-display"><?= html_escape($test_data['left_ac_250'] !== null ? $test_data['left_ac_250'] : '-') ?></td>
									<td id="left-ac-500-display"><?= html_escape($test_data['left_ac_500']) ?></td>
									<td id="left-ac-1000-display"><?= html_escape($test_data['left_ac_1000']) ?></td>
									<td id="left-ac-2000-display"><?= html_escape($test_data['left_ac_2000']) ?></td>
									<td id="left-ac-3000-display"><?= html_escape($test_data['left_ac_3000']) ?></td>
									<td id="left-ac-4000-display"><?= html_escape($test_data['left_ac_4000']) ?></td>
									<td id="left-ac-6000-display"><?= html_escape($test_data['left_ac_6000']) ?></td>
									<?php
									$left_ac_values = [];
									foreach ($frequencies as $freq) {
										$left_ac_values[] = $test_data['left_ac_' . $freq];
									}
									$left_ac_average = array_sum($left_ac_values) / count($left_ac_values);
									?>
									<td id="left-ac-average" class="average-cell"><?= html_escape(!empty($left_ac_values) ? number_format(array_sum($left_ac_values) / count($left_ac_values), 1) : '0.0') ?></td>
								</tr>
								<tr class="bc-row">
									<td><strong>BC (dB)</strong></td>
									<td id="left-bc-250-display"><?= html_escape($test_data['left_bc_250'] !== null ? $test_data['left_bc_250'] : '-') ?></td>
									<td id="left-bc-500-display"><?= html_escape($test_data['left_bc_500']) ?></td>
									<td id="left-bc-1000-display"><?= html_escape($test_data['left_bc_1000']) ?></td>
									<td id="left-bc-2000-display"><?= html_escape($test_data['left_bc_2000']) ?></td>
									<td id="left-bc-3000-display"><?= html_escape($test_data['left_bc_3000']) ?></td>
									<td id="left-bc-4000-display"><?= html_escape($test_data['left_bc_4000']) ?></td>
									<td id="left-bc-6000-display"><?= html_escape($test_data['left_bc_6000']) ?></td>
									<?php
									$left_bc_values = [];
									foreach ($frequencies as $freq) {
										$value = $test_data['left_bc_' . $freq];
										if ($value !== null) $left_bc_values[] = $value;
									}
									?>
									<td id="left-bc-average" class="average-cell"><?= html_escape(!empty($left_bc_values) ? number_format(array_sum($left_bc_values) / count($left_bc_values), 1) : '0.0') ?></td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
			</div>

			<!-- Legend -->
			<div class="legend">
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

			<?php if ($action == 'review_dokter'): ?>
				<div class="impression">
					<h3>Impression:</h3>
					<textarea id="impression" name="impression"
						class="form-control" rows="4"
						placeholder="Masukkan hasil analisis audiometri, interpretasi, dan rekomendasi..."><?= set_value('impression', $test_data['impression'] ?? '') ?></textarea>
					<small class="form-text text-muted mt-2">
						Silakan isi dengan interpretasi hasil audiometri, tingkat gangguan pendengaran, dan rekomendasi tindak lanjut jika diperlukan.
					</small>
					<?= form_error('impression', '<small class="text-danger">', '</small>') ?>
				</div>
			<?php endif; ?>

			<div class="controls">
				<button type="button" class="btn btn-success" onclick="showSimpanModal()">
					<i class="fas fa-save"></i> Update Data
				</button>
			</div>
		</div>
		<?= form_close() ?>
	</div>
</div>

<script>
	function showSimpanModal() {
		Swal.fire({
			title: 'Update Data Audiometri',
			text: 'Apakah Anda yakin ingin mengupdate data audiometri ini?',
			icon: 'question',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Ya, Update!',
			cancelButtonText: 'Batal',
			reverseButtons: true
		}).then((result) => {
			if (result.isConfirmed) {
				submitFormWithLoading();
			}
		});
	}

	function submitFormWithLoading() {
		Swal.fire({
			title: 'Mengupdate Data...',
			text: 'Mohon tunggu sebentar',
			allowOutsideClick: false,
			allowEscapeKey: false,
			showConfirmButton: false,
			didOpen: () => {
				Swal.showLoading();
			}
		});

		const form = document.getElementById('audiometri-review');
		const formData = new FormData(form);

		fetch(form.action, {
			method: 'POST',
			body: formData,
			headers: {
				'X-Requested-With': 'XMLHttpRequest'
			}
		})
		.then(response => response.json())
		.then(data => {
			if (data.status) {
				// Show success message
				Swal.fire({
					title: 'Berhasil!',
					text: data.message || 'Data berhasil diupdate',
					icon: 'success',
					timer: 3000,
					timerProgressBar: true,
					showConfirmButton: false
				}).then(() => {
					// After 3 seconds, redirect to doctor_view
					window.location.href = <?= json_encode(base_url('audiometri/doctor_view'), JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>;
				});
			} else {
				Swal.fire({
					title: 'Gagal!',
					text: data.message || 'Gagal mengupdate data',
					icon: 'error',
					confirmButtonColor: '#3085d6'
				});
			}
		})
		.catch(error => {
			console.error('Error:', error);
			Swal.fire({
				title: 'Error!',
				text: 'Terjadi kesalahan saat mengupdate data',
				icon: 'error',
				confirmButtonColor: '#3085d6'
			});
		});
	}
</script>


<script>
	// JavaScript untuk menampilkan audiogram
	const testData = <?= json_encode($test_data, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>;

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

		// Draw AC (Air Conduction) line
		const acPoints = [];
		frequencies.forEach((freq, i) => {
			const value = parseFloat(testData[`${ear}_ac_${freq}`]);

			if (!isNaN(value)) {
				const x = (i + 1) * (width / 8);
				const y = ((value + 10) / 150) * height;
				acPoints.push({
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

		// Draw BC (Bone Conduction) line
		const bcPoints = [];
		frequencies.forEach((freq, i) => {
			const value = parseFloat(testData[`${ear}_bc_${freq}`]);

			if (!isNaN(value)) {
				const x = (i + 1) * (width / 8);
				const y = ((value + 10) / 150) * height;
				bcPoints.push({
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
	/* CSS dari versi HTML asli dengan beberapa modifikasi untuk integrasi CI */
	.audiometri-container {
		max-width: 1200px;
		margin: 0 auto;
		background: white;
		border-radius: 15px;
		box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
		overflow: hidden;
	}

	.header {
		background: linear-gradient(45deg, #2c3e50, #3498db);
		color: white;
		padding: 30px;
		text-align: center;
	}

	.header h1 {
		font-size: 2.5em;
		margin-bottom: 10px;
		text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
	}

	.header p {
		font-size: 1.2em;
		opacity: 0.9;
	}

	.patient-info {
		display: grid;
		grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
		gap: 20px;
		padding: 30px;
		background: #f8f9fa;
	}

	.info-group {
		background: white;
		padding: 10px;
		border-radius: 10px;
		box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
	}

	.info-group label {
		display: block;
		font-weight: bold;
		color: #2c3e50;
		margin-bottom: 8px;
	}

	.info-group input,
	.info-group select {
		width: 100%;
		padding: 12px;
		border: 2px solid #e0e0e0;
		border-radius: 8px;
		font-size: 16px;
		transition: all 0.3s ease;
	}

	.info-group input:focus,
	.info-group select:focus {
		outline: none;
		border-color: #3498db;
		box-shadow: 0 0 10px rgba(52, 152, 219, 0.2);
	}

	.audiogram-section {
		padding: 30px;
	}

	.audiogram-title {
		text-align: center;
		font-size: 2em;
		color: #2c3e50;
		margin-bottom: 30px;
		padding: 10px;
		background: linear-gradient(45deg, #f39c12, #e67e22);
		color: white;
		border-radius: 10px;
		text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.3);
	}

	.ear-charts {
		display: grid;
		grid-template-columns: 1fr 1fr;
		gap: 30px;
		margin-bottom: 30px;
	}

	.ear-chart {
		background: white;
		border-radius: 15px;
		padding: 25px;
		box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
		border: 3px solid #e0e0e0;
	}

	.ear-title {
		text-align: center;
		font-size: 1.5em;
		font-weight: bold;
		color: #2c3e50;
		margin-bottom: 20px;
		padding: 5px;
		background: linear-gradient(45deg, #3498db, #2980b9);
		color: white;
		border-radius: 8px;
	}

	.chart-container {
		position: relative;
		height: 400px;
		border: 2px solid #34495e;
		margin: 20px 0;
		background: #ffffff;
	}

	.conduction-title,
	.conduction-title2 {
		background: linear-gradient(45deg, #16a085, #1abc9c);
		color: white;
		padding: 12px 5px;
		font-weight: bold;
		font-size: 12px;
		text-align: center;
		border-radius: 8px;
		margin-bottom: 1px;
		text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.3);
	}

	.frequency-inputs {
		display: grid;
		grid-template-columns: repeat(8, 1fr);
		gap: 10px;
		margin-bottom: 20px;
	}

	.freq-input-group {
		text-align: center;
	}

	.freq-input-group label {
		display: block;
		font-weight: bold;
		font-size: 12px;
		margin-bottom: 5px;
		color: #2c3e50;
	}

	.freq-input-group input {
		width: 100%;
		padding: 8px;
		border: 2px solid #e0e0e0;
		border-radius: 5px;
		text-align: center;
		font-size: 14px;
		transition: all 0.3s ease;
	}

	.freq-input-group input:focus {
		outline: none;
		border-color: #3498db;
		box-shadow: 0 0 8px rgba(52, 152, 219, 0.3);
	}

	.chart-grid {
		position: absolute;
		top: 0;
		left: 0;
		right: 0;
		bottom: 0;
		pointer-events: none;
	}

	.chart-line {
		stroke: #bdc3c7;
		stroke-width: 1;
	}

	.chart-line.major {
		stroke: #7f8c8d;
		stroke-width: 1.5;
	}

	.hearing-line {
		stroke: #3498db;
		stroke-width: 3;
		fill: none;
		stroke-dasharray: none;
	}

	.hearing-line.bc {
		stroke: #e74c3c;
		stroke-width: 3;
		stroke-dasharray: 5, 5;
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

	.hearing-point:hover {
		r: 6;
		fill: #c0392b;
	}

	.axis-label {
		fill: #2c3e50;
		font-size: 12px;
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

	.controls {
		display: flex;
		justify-content: center;
		gap: 20px;
		margin: 30px 0;
		flex-wrap: wrap;
	}

	.btn {
		padding: 15px 30px;
		border: none;
		border-radius: 8px;
		font-size: 16px;
		font-weight: bold;
		cursor: pointer;
		transition: all 0.3s ease;
		text-transform: uppercase;
		letter-spacing: 1px;
		text-decoration: none;
		display: inline-block;
	}

	.btn-primary {
		background: linear-gradient(45deg, #3498db, #2980b9);
		color: white;
		box-shadow: 0 5px 15px rgba(52, 152, 219, 0.3);
	}

	.btn-primary:hover {
		transform: translateY(-2px);
		box-shadow: 0 8px 25px rgba(52, 152, 219, 0.4);
		color: white;
		text-decoration: none;
	}

	.btn-secondary {
		background: linear-gradient(45deg, #95a5a6, #7f8c8d);
		color: white;
		box-shadow: 0 5px 15px rgba(149, 165, 166, 0.3);
	}

	.btn-secondary:hover {
		transform: translateY(-2px);
		box-shadow: 0 8px 25px rgba(149, 165, 166, 0.4);
		color: white;
		text-decoration: none;
	}

	.btn-success {
		background: linear-gradient(45deg, #27ae60, #229954);
		color: white;
		box-shadow: 0 5px 15px rgba(39, 174, 96, 0.3);
	}

	.btn-success:hover {
		transform: translateY(-2px);
		box-shadow: 0 8px 25px rgba(39, 174, 96, 0.4);
		color: white;
		text-decoration: none;
	}

	.btn-info {
		background: linear-gradient(45deg, #3498db, #2980b9);
		color: white;
		box-shadow: 0 5px 15px rgba(52, 152, 219, 0.3);
	}

	.btn-info:hover {
		transform: translateY(-2px);
		box-shadow: 0 8px 25px rgba(52, 152, 219, 0.4);
		color: white;
		text-decoration: none;
	}

	.impression {
		background: linear-gradient(135deg, #f8f9fa, #e9ecef);
		padding: 25px;
		border-radius: 10px;
		margin-top: 30px;
		border-left: 5px solid #3498db;
	}

	.impression h3 {
		color: #2c3e50;
		margin-bottom: 15px;
		font-size: 1.3em;
	}

	.impression p {
		color: #34495e;
		line-height: 1.6;
		font-size: 16px;
	}

	.legend {
		display: flex;
		justify-content: center;
		gap: 30px;
		margin-top: 20px;
		padding: 20px;
		background: #f8f9fa;
		border-radius: 10px;
		flex-wrap: wrap;
	}

	.legend-item {
		display: flex;
		align-items: center;
		gap: 10px;
		font-weight: bold;
		color: #2c3e50;
	}

	.legend-symbol {
		width: 20px;
		height: 20px;
		border-radius: 50%;
		background: #3498db;
	}

	.diamond-symbol {
		width: 15px;
		height: 15px;
		transform: rotate(45deg);
		background: #3498db;
	}

	@media (max-width: 768px) {
		.ear-charts {
			grid-template-columns: 1fr;
		}

		.frequency-inputs {
			grid-template-columns: repeat(4, 1fr);
		}

		.controls {
			flex-direction: column;
			align-items: center;
		}

		.legend {
			flex-direction: column;
			align-items: center;
		}
	}

	.frequency-table {
		margin-top: 20px;
		border-radius: 10px;
		overflow: hidden;
		box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
	}

	.table-header {
		padding: 12px 15px;
		color: white;
		font-weight: bold;
		text-align: center;
	}

	.right-ear-header {
		background: linear-gradient(45deg, #17a2b8, #138496);
	}

	.left-ear-header {
		background: linear-gradient(45deg, #ffc107, #e0a800);
	}

	.table-header h4 {
		margin: 0;
		font-size: 16px;
	}

	.audiometry-table {
		width: 100%;
		border-collapse: collapse;
		background: white;
		font-size: 12px;
	}

	.audiometry-table th,
	.audiometry-table td {
		padding: 8px 6px;
		text-align: center;
		border: 1px solid #dee2e6;
	}

	.audiometry-table th {
		background: #f8f9fa;
		font-weight: bold;
		color: #495057;
		font-size: 11px;
	}

	.audiometry-table tbody tr:nth-child(even) {
		background: #f8f9fa;
	}

	.audiometry-table tbody tr:hover {
		background: #e9ecef;
	}

	.average-cell {
		background: #d4edda !important;
		font-weight: bold;
		color: #155724;
	}

	.ac-row td:first-child {
		background: #d1ecf1;
		color: #0c5460;
	}

	.bc-row td:first-child {
		background: #fff3cd;
		color: #856404;
	}

	@media (max-width: 768px) {
		.audiometry-table {
			font-size: 10px;
		}

		.audiometry-table th,
		.audiometry-table td {
			padding: 6px 4px;
		}
	}
</style>
