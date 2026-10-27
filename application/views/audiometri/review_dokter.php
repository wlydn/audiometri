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
	<div class="card mb-4">
		<div class="card-header">
			<h2 class="h6 card-title mb-0"><i class="bi bi-person-vcard text-primary me-2" aria-hidden="true"></i>Identitas Pasien</h2>
		</div>
		<div class="card-body">
			<dl class="row g-3 mb-0">
				<div class="col-md-6 col-xl-4">
					<dt class="stat-label">Perusahaan</dt>
					<dd class="mb-0 fw-semibold"><?= html_escape($test_data['perusahaan'] ?? 'N/A') ?></dd>
				</div>
				<div class="col-md-6 col-xl-4">
					<dt class="stat-label">Jabatan</dt>
					<dd class="mb-0 fw-semibold"><?= html_escape($test_data['jabatan'] ?? 'N/A') ?></dd>
				</div>
				<div class="col-md-6 col-xl-4">
					<dt class="stat-label">Nama</dt>
					<dd class="mb-0 fw-semibold"><?= html_escape($test_data['nama'] ?? 'N/A') ?></dd>
				</div>
				<div class="col-md-6 col-xl-3">
					<dt class="stat-label">Umur</dt>
					<dd class="mb-0 fw-semibold"><?= html_escape($test_data['umur'] ?? 'N/A') ?> Tahun</dd>
				</div>
				<div class="col-md-6 col-xl-3">
					<dt class="stat-label">Tanggal Tes</dt>
					<dd class="mb-0 fw-semibold"><?= html_escape(date('d F Y', strtotime($test_data['tanggal_tes']))) ?></dd>
				</div>
				<div class="col-md-6 col-xl-3">
					<dt class="stat-label">Jenis Kelamin</dt>
					<dd class="mb-0 fw-semibold"><?= html_escape($test_data['jenis_kelamin'] ?? 'N/A') ?></dd>
				</div>
				<div class="col-md-6 col-xl-3">
					<dt class="stat-label">No RM</dt>
					<dd class="mb-0 fw-semibold font-monospace"><?= html_escape($test_data['no_rm'] ?? 'N/A') ?></dd>
				</div>
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
					<svg width="14" height="14" viewBox="0 0 14 14" aria-hidden="true"><rect width="14" height="14" rx="2.5" fill="#1976d2"/><polygon points="3.5,7 10.5,2.5 10.5,11.5" fill="#fff"/></svg>
					<span>BC Right</span>
				</div>
				<div class="legend-item">
					<svg width="14" height="14" viewBox="0 0 14 14" aria-hidden="true"><rect width="14" height="14" rx="2.5" fill="#1976d2"/><polygon points="10.5,7 3.5,2.5 3.5,11.5" fill="#fff"/></svg>
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
		<script>
		// Template impression dibangun dari data pemeriksaan (testData) dan dikelompokkan: Normal / Perlu perhatian.
		(function () {
			const freqs = ['250', '500', '1000', '2000', '3000', '4000', '6000'];
			const earNames = { right: 'kanan', left: 'kiri' };
			// Batas sama dengan classify_hearing_level di model
			const levels = [
				[25, 'normal', 0],
				[40, 'ringan', 1],
				[55, 'sedang', 2],
				[70, 'sedang-berat', 3],
				[90, 'berat', 4],
				[Infinity, 'sangat berat', 5]
			];

			function average(ear, type) {
				const vals = freqs
					.map(f => parseFloat(testData[ear + '_' + type + '_' + f]))
					.filter(v => !isNaN(v));
				return vals.length ? vals.reduce((a, b) => a + b, 0) / vals.length : null;
			}

			function level(avg) {
				return levels.find(l => avg <= l[0]);
			}

			function analyze(ear) {
				const ac = average(ear, 'ac');
				if (ac === null) return null;
				const bc = average(ear, 'bc');
				const acLevel = level(ac);
				const result = { ear, ac, bc, normal: acLevel[2] === 0, label: acLevel[1], type: '' };
				if (!result.normal) {
					if (bc === null) {
						result.type = 'gangguan pendengaran';
					} else {
						const bcAbnormal = level(bc)[2] > 0;
						const gap = ac - bc;
						if (!bcAbnormal) result.type = 'tuli konduktif';
						else if (gap >= 15) result.type = 'tuli campuran';
						else result.type = 'tuli sensorineural';
					}
				}
				return result;
			}

			function describe(r) {
				const base = 'Telinga ' + earNames[r.ear];
				const acText = 'rata-rata AC ' + r.ac.toFixed(1) + ' dB';
				if (r.normal) return base + ': pendengaran dalam batas normal (' + acText + ').';
				const bcText = r.bc !== null ? ', BC ' + r.bc.toFixed(1) + ' dB' : '';
				return base + ': ' + r.type + ' derajat ' + r.label + ' (' + acText + bcText + ').';
			}

			function addGroup(container, title, items, btnClass) {
				if (!items.length) return;
				const group = document.createElement('div');
				group.className = 'mb-2';
				const heading = document.createElement('div');
				heading.className = 'fw-semibold small mb-1';
				heading.textContent = title;
				group.appendChild(heading);
				const wrap = document.createElement('div');
				wrap.className = 'd-flex gap-1 flex-wrap';
				items.forEach(item => {
					const btn = document.createElement('button');
					btn.type = 'button';
					btn.className = 'btn btn-sm ' + btnClass;
					btn.textContent = item.label;
					btn.title = item.text;
					btn.addEventListener('click', () => insertDoctorImpression(item.text));
					wrap.appendChild(btn);
				});
				group.appendChild(wrap);
				container.appendChild(group);
			}

			function buildTemplates() {
				const container = document.getElementById('impression-templates');
				if (!container) return;
				const results = ['right', 'left'].map(analyze).filter(Boolean);
				if (!results.length) {
					container.innerHTML = '<span class="text-body-secondary small">Data audiometri belum lengkap untuk membuat template.</span>';
					return;
				}

				const normals = results.filter(r => r.normal);
				const abnormals = results.filter(r => !r.normal);
				const normalItems = [];
				const abnormalItems = [];

				if (normals.length === 2) {
					normalItems.push({ label: 'Normal bilateral', text: 'Pendengaran dalam batas normal bilateral.' });
				} else {
					normals.forEach(r => normalItems.push({ label: 'Normal telinga ' + earNames[r.ear], text: describe(r) }));
				}

				abnormals.forEach(r => abnormalItems.push({
					label: 'Telinga ' + earNames[r.ear] + ': ' + r.type + ' ' + r.label,
					text: describe(r)
				}));
				if (abnormals.length === 2) {
					abnormalItems.push({ label: 'Ringkasan bilateral', text: abnormals.map(describe).join(' ') });
				} else if (abnormals.length === 1 && normals.length === 1) {
					abnormalItems.push({ label: 'Ringkasan kedua telinga', text: results.map(describe).join(' ') });
				}

				addGroup(container, 'Normal', normalItems, 'btn-outline-success');
				addGroup(container, 'Gangguan pendengaran', abnormalItems, 'btn-outline-warning');
			}

			document.addEventListener('DOMContentLoaded', buildTemplates);
		})();
		</script>
	<?php endif; ?>

	<div class="controls d-flex flex-wrap gap-2 pb-4">
		<a href="<?= html_escape(base_url('doctor_view')) ?>" class="btn btn-outline-secondary">
			<i class="bi bi-arrow-left me-1" aria-hidden="true"></i> Kembali ke Daftar
		</a>
		<button type="button" class="btn btn-success" onclick="showSimpanModal()">
			<i class="bi bi-floppy me-1" aria-hidden="true"></i> Simpan Impression
		</button>
	</div>
	<?= form_close() ?>
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

	// Penanda BC: kotak biru dengan segitiga putih (sama seperti hasil cetak)
	function createBcMarker(x, y, ear) {
		const ns = 'http://www.w3.org/2000/svg';
		const g = document.createElementNS(ns, 'g');
		g.setAttribute('class', 'arrow-symbol');
		const box = document.createElementNS(ns, 'rect');
		box.setAttribute('x', x - 7);
		box.setAttribute('y', y - 7);
		box.setAttribute('width', 14);
		box.setAttribute('height', 14);
		box.setAttribute('rx', 2.5);
		box.setAttribute('fill', '#1976d2');
		const tri = document.createElementNS(ns, 'polygon');
		const pts = ear === 'right'
			? [x - 4, y, x + 3, y - 4.5, x + 3, y + 4.5]
			: [x + 4, y, x - 3, y - 4.5, x - 3, y + 4.5];
		tri.setAttribute('points', pts.join(' '));
		tri.setAttribute('fill', '#ffffff');
		g.appendChild(box);
		g.appendChild(tri);
		return g;
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
