<!-- Audiometri Form View -->
<div class="container-fluid">
	<div class="audiometri-container">
		<div class="header">
			<span class="eyebrow">Panel dokter / Review</span>
			<h1>Review hasil audiometri</h1>
			<p>Periksa identitas dan audiogram pasien sebelum menyimpan impression.</p>
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
				<div class="impression p-4 bg-light rounded-3 border mb-4">
					<div class="d-flex justify-content-between align-items-center mb-2 flex-wrap gap-2">
						<h5 class="fw-bold mb-0 text-dark">
							<i class="fas fa-stethoscope text-primary me-2"></i>Impression / Rekomendasi Dokter:
						</h5>
						<div class="d-flex gap-1 flex-wrap">
							<button type="button" class="btn btn-sm btn-outline-secondary py-1 px-2" style="font-size: 11px;" onclick="insertDoctorImpression('Pendengaran dalam batas normal bilateral.')">Normal</button>
							<button type="button" class="btn btn-sm btn-outline-secondary py-1 px-2" style="font-size: 11px;" onclick="insertDoctorImpression('Tuli konduktif derajat ringan.')">Konduktif Ringan</button>
							<button type="button" class="btn btn-sm btn-outline-secondary py-1 px-2" style="font-size: 11px;" onclick="insertDoctorImpression('Tuli sensorineural derajat ringan.')">Sensorineural Ringan</button>
							<button type="button" class="btn btn-sm btn-outline-secondary py-1 px-2" style="font-size: 11px;" onclick="insertDoctorImpression('Noise-Induced Hearing Loss (NIHL) bilateral.')">NIHL</button>
						</div>
					</div>
					<textarea id="impression" name="impression"
						class="form-control" rows="4"
						placeholder="Masukkan hasil analisis audiometri, interpretasi, dan rekomendasi medis..."><?= set_value('impression', $test_data['impression'] ?? '') ?></textarea>
					<small class="form-text text-muted mt-2 d-block">
						Silakan lengkapi dengan interpretasi hasil audiometri, klasifikasi gangguan pendengaran, dan saran tindak lanjut.
					</small>
					<?= form_error('impression', '<small class="text-danger">', '</small>') ?>
				</div>
				<script>
				function insertDoctorImpression(text) {
					const textarea = document.getElementById('impression');
					if (textarea.value.trim() === '') {
						textarea.value = text;
					} else {
						textarea.value += '\n' + text;
					}
					textarea.focus();
				}
				</script>
			<?php endif; ?>

			<div class="controls d-flex gap-2">
				<a href="<?= html_escape(base_url('doctor_view')) ?>" class="btn btn-secondary">
					<i class="fas fa-arrow-left me-1"></i> Kembali ke Daftar
				</a>
				<button type="button" class="btn btn-success" onclick="showSimpanModal()">
					<i class="fas fa-floppy-disk me-1"></i> Simpan Impression
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
					window.location.href = <?= json_encode(base_url('doctor_view'), JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>;
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

<!-- Styles handled cleanly by workspace.css and calm.css -->




