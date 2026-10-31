<!-- Audiometri Review View -->
<?php
$frequencies = ['250', '500', '1000', '2000', '3000', '4000', '6000'];

// Renders the AC/BC table of one ear. Cell ids (<ear>-<type>-<freq>-display and
// <ear>-<type>-average) are kept identical to the previous markup.
$render_review_table = function ($ear, $header_class, $heading) use ($test_data, $frequencies) {
	?>
	<div class="frequency-table">
		<div class="table-header <?= html_escape($header_class) ?>">
			<h3><?= html_escape($heading) ?></h3>
		</div>
		<div class="table-responsive">
			<table class="table table-sm table-bordered text-center align-middle mb-0 audiometry-table">
				<thead>
					<tr>
						<th scope="col" class="text-start">Frekuensi</th>
						<?php foreach ($frequencies as $freq): ?>
							<th scope="col"><?= html_escape($freq) ?></th>
						<?php endforeach; ?>
						<th scope="col">Rata²</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach (['ac' => 'AC (dB)', 'bc' => 'BC (dB)'] as $type => $label): ?>
						<?php
						$values = [];
						foreach ($frequencies as $freq) {
							$value = $test_data[$ear . '_' . $type . '_' . $freq];
							// AC averages every frequency; BC averages only measured ones.
							if ($type === 'ac' || $value !== null) {
								$values[] = $value;
							}
						}
						?>
						<tr class="<?= html_escape($type) ?>-row">
							<th scope="row" class="text-start"><?= html_escape($label) ?></th>
							<?php foreach ($frequencies as $freq): ?>
								<?php $cell = $test_data[$ear . '_' . $type . '_' . $freq]; ?>
								<td id="<?= html_escape($ear . '-' . $type . '-' . $freq) ?>-display"><?= html_escape($cell !== null ? $cell : '-') ?></td>
							<?php endforeach; ?>
							<td id="<?= html_escape($ear . '-' . $type) ?>-average" class="average-cell"><?= html_escape(!empty($values) ? number_format(array_sum($values) / count($values), 1) : '0.0') ?></td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	</div>
	<?php
};
?>
<div class="container-fluid p-3 p-lg-4">
	<div class="page-heading">
		<div>
			<span class="eyebrow"><i class="bi bi-clipboard2-pulse me-1" aria-hidden="true"></i> Panel dokter / Review</span>
			<h1>Review hasil audiometri</h1>
			<p>Periksa identitas dan audiogram pasien sebelum menyimpan impression.</p>
		</div>
	</div>

	<?php if ($this->session->flashdata('error')): ?>
		<div class="alert alert-danger alert-dismissible fade show" role="alert">
			<?= html_escape($this->session->flashdata('error')) ?>
			<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
		</div>
	<?php endif; ?>

	<?php if ($this->session->flashdata('success')): ?>
		<div class="alert alert-success alert-dismissible fade show" role="alert">
			<i class="bi bi-check-circle-fill me-2" aria-hidden="true"></i><?= html_escape($this->session->flashdata('success')) ?>
			<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
		</div>
	<?php endif; ?>

	<?= form_open(current_url(), ['id' => 'audiometri-review']) ?>

	<!-- Identitas Pasien (read-only) -->
	<?php
	$rv_name = trim((string) ($test_data['nama'] ?? ''));
	$rv_reviewed = trim((string) ($test_data['impression'] ?? '')) !== '';
	$rv_facts = [
		['bi-gender-ambiguous', 'Jenis Kelamin', ($test_data['jenis_kelamin'] ?? '') !== '' ? $test_data['jenis_kelamin'] : '-'],
		['bi-person', 'Umur', ($test_data['umur'] ?? '') !== '' ? $test_data['umur'] . ' Tahun' : '-'],
		['bi-building', 'Perusahaan', ($test_data['perusahaan'] ?? '') !== '' ? $test_data['perusahaan'] : '-'],
		['bi-briefcase', 'Jabatan', ($test_data['jabatan'] ?? '') !== '' ? $test_data['jabatan'] : '-'],
	];
	?>
	<div class="card mb-4 patient-card">
		<div class="card-body">
			<div class="patient-head">
				<div class="d-flex align-items-center gap-3">
					<span class="avatar avatar-lg avatar-gradient" aria-hidden="true"><?= html_escape(strtoupper(substr($rv_name !== '' ? $rv_name : 'P', 0, 1))) ?></span>
					<div>
						<h2 class="h4 fw-bold mb-1"><?= html_escape($rv_name !== '' ? $rv_name : 'Tanpa nama') ?></h2>
						<div class="d-flex align-items-center gap-2 flex-wrap">
							<span class="badge text-bg-light border font-monospace">RM: <?= html_escape($test_data['no_rm'] ?? '-') ?></span>
							<?php if ($rv_reviewed): ?>
								<span class="badge bg-success"><i class="bi bi-check-circle-fill me-1" aria-hidden="true"></i>Sudah Direview</span>
							<?php else: ?>
								<span class="badge bg-warning"><i class="bi bi-hourglass-split me-1" aria-hidden="true"></i>Menunggu Review</span>
							<?php endif; ?>
						</div>
					</div>
				</div>
				<div class="patient-dates">
					<div>
						<small class="stat-label d-block">Tanggal Tes</small>
						<strong><?= html_escape(!empty($test_data['tanggal_tes']) ? date('d M Y', strtotime($test_data['tanggal_tes'])) : '-') ?></strong>
					</div>
					<div>
						<small class="stat-label d-block">Terakhir Disimpan</small>
						<strong><?= html_escape(!empty($test_data['updated_at']) ? date('d/m/Y H:i', strtotime($test_data['updated_at'])) : '-') ?></strong>
					</div>
				</div>
			</div>
			<dl class="patient-facts mb-0">
				<?php foreach ($rv_facts as $fact): ?>
					<div class="patient-fact">
						<span class="patient-fact-icon" aria-hidden="true"><i class="bi <?= html_escape($fact[0]) ?>"></i></span>
						<div class="patient-fact-body">
							<dt class="stat-label"><?= html_escape($fact[1]) ?></dt>
							<dd class="mb-0 fw-semibold"><?= html_escape($fact[2]) ?></dd>
						</div>
					</div>
				<?php endforeach; ?>
			</dl>
		</div>
	</div>

	<!-- Audiogram -->
	<div class="card mb-4">
		<div class="card-header">
			<h2 class="h6 card-title mb-0"><i class="bi bi-soundwave text-primary me-2" aria-hidden="true"></i>AUDIOGRAM</h2>
		</div>
		<div class="card-body">
			<div class="ear-charts">
				<!-- Right Ear Chart -->
				<div class="ear-chart">
					<div class="ear-title ear-title-right">TELINGA KANAN</div>
					<div class="chart-container">
						<svg width="100%" height="100%" id="right-chart-view">
							<!-- Grid akan di-generate oleh JavaScript -->
						</svg>
					</div>
					<?php $render_review_table('right', 'right-ear-header', 'Data Telinga Kanan'); ?>
				</div>

				<!-- Left Ear Chart -->
				<div class="ear-chart">
					<div class="ear-title ear-title-left">TELINGA KIRI</div>
					<div class="chart-container">
						<svg width="100%" height="100%" id="left-chart-view">
							<!-- Grid akan di-generate oleh JavaScript -->
						</svg>
					</div>
					<?php $render_review_table('left', 'left-ear-header', 'Data Telinga Kiri'); ?>
				</div>
			</div>

			<!-- Legend -->
			<div class="legend mb-0">
				<div class="legend-item">
					<div class="legend-symbol"></div>
					<span>AC Right</span>
				</div>
				<div class="legend-item">
					<div class="diamond-symbol"></div>
					<span>AC Left</span>
				</div>
				<div class="legend-item">
					<svg width="14" height="14" viewBox="0 0 14 14" aria-hidden="true"><polygon points="2,7 12,1.5 12,12.5" fill="#dc2626"/></svg>
					<span>BC Right</span>
				</div>
				<div class="legend-item">
					<svg width="14" height="14" viewBox="0 0 14 14" aria-hidden="true"><polygon points="12,7 2,1.5 2,12.5" fill="#dc2626"/></svg>
					<span>BC Left</span>
				</div>
			</div>
		</div>
	</div>

	<?php if ($action == 'review_dokter'): ?>
		<!-- Impression -->
		<div class="card mb-4 impression">
			<div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
				<h2 class="h6 card-title mb-0">
					<i class="bi bi-clipboard2-pulse text-primary me-2" aria-hidden="true"></i>Impression / Rekomendasi Dokter:
				</h2>
			</div>
			<div class="card-body border-bottom pb-3">
				<div class="small text-body-secondary mb-2">Template otomatis dari hasil pemeriksaan (klik untuk menambahkan ke impression):</div>
				<div id="impression-templates"></div>
			</div>
			<div class="card-body">
				<label for="impression" class="visually-hidden">Impression / Rekomendasi Dokter</label>
				<textarea id="impression" name="impression"
					class="form-control" rows="4"
					placeholder="Masukkan hasil analisis audiometri, interpretasi, dan rekomendasi medis..."><?= set_value('impression', $test_data['impression'] ?? '') ?></textarea>
				<div class="form-text">
					Silakan lengkapi dengan interpretasi hasil audiometri, klasifikasi gangguan pendengaran, dan saran tindak lanjut.
				</div>
				<?= form_error('impression', '<small class="text-danger d-block mt-1">', '</small>') ?>
			</div>
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
		<script src="<?= html_escape(base_url('assets/js/impression-templates.js')) ?>?v=<?= html_escape(@filemtime(FCPATH . 'assets/js/impression-templates.js')) ?>"></script>
		<script>
		document.addEventListener('DOMContentLoaded', function () {
			ImpressionTemplates.init({
				container: document.getElementById('impression-templates'),
				getData: function () { return testData; },
				onInsert: insertDoctorImpression
			});
		});
		</script>
	<?php endif; ?>

	<div class="controls d-flex flex-wrap gap-2 pb-4">
		<a href="<?= html_escape(base_url('doctor_view')) ?>" class="btn btn-outline-secondary">
			<i class="bi bi-arrow-left me-1" aria-hidden="true"></i> Kembali ke Daftar
		</a>
		<a href="<?= html_escape(base_url('edit/' . $test_data['id'])) ?>" class="btn btn-outline-primary">
			<i class="bi bi-pencil-square me-1" aria-hidden="true"></i> Edit Data Pasien &amp; Audiogram
		</a>
		<button type="button" class="btn btn-success" onclick="showSimpanModal(false)">
			<i class="bi bi-floppy me-1" aria-hidden="true"></i> Simpan Impression
		</button>
		<button type="button" class="btn btn-primary" onclick="showSimpanModal(true)">
			<i class="bi bi-skip-forward-fill me-1" aria-hidden="true"></i> Simpan &amp; Pasien Berikutnya
		</button>
	</div>
	<?= form_close() ?>
</div>

<script>
	function showSimpanModal(goNext) {
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
				submitFormWithLoading(goNext === true);
			}
		});
	}

	function submitFormWithLoading(goNext) {
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
		if (goNext) {
			formData.set('next', '1');
		}

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
					// Pindah ke pasien berikutnya bila diminta, selain itu kembali ke daftar
					const fallback = <?= json_encode(base_url('doctor_view'), JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>;
					window.location.href = (goNext && data.next_url) ? data.next_url : fallback;
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

	// Penanda BC: segitiga merah solid (sama seperti hasil cetak PDF).
	// Telinga kanan menghadap kiri, telinga kiri menghadap kanan.
	function createBcMarker(x, y, ear) {
		const tri = document.createElementNS('http://www.w3.org/2000/svg', 'polygon');
		const pts = ear === 'right'
			? [x - 5.5, y, x + 4.5, y - 5.5, x + 4.5, y + 5.5]
			: [x + 5.5, y, x - 4.5, y - 5.5, x - 4.5, y + 5.5];
		tri.setAttribute('points', pts.join(' '));
		tri.setAttribute('class', 'arrow-symbol');
		return tri;
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
				svg.appendChild(createBcMarker(x, y, ear));
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
