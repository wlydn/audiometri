<?php
// File: application/views/audiometri/view.php
$is_reviewed = !empty($test_data['impression']);
$frequencies = ['250', '500', '1000', '2000', '3000', '4000', '6000'];

// Renders the AC/BC threshold table of one ear (values come straight from $test_data).
$render_threshold_table = function ($ear, $heading_class) use ($test_data, $frequencies) {
	$types = ['ac' => 'AC (dB)', 'bc' => 'BC (dB)'];
	?>
	<div class="table-responsive">
		<table class="table table-sm table-bordered text-center align-middle mb-0">
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
				<?php foreach ($types as $type => $type_label): ?>
					<?php $values = []; ?>
					<tr>
						<th scope="row" class="text-start <?= html_escape($heading_class) ?>"><?= html_escape($type_label) ?></th>
						<?php foreach ($frequencies as $freq): ?>
							<?php
							$value = $test_data[$ear . '_' . $type . '_' . $freq];
							if ($value !== null) {
								$values[] = $value;
							}
							?>
							<td><?= html_escape($value !== null ? $value : '-') ?></td>
						<?php endforeach; ?>
						<td class="fw-bold"><?= html_escape(!empty($values) ? number_format(array_sum($values) / count($values), 1) : '-') ?></td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	</div>
	<?php
};
?>
<div class="container-fluid p-3 p-lg-4">
	<!-- Page Heading & Actions -->
	<div class="page-heading">
		<div>
			<span class="eyebrow"><i class="bi bi-file-earmark-medical me-1" aria-hidden="true"></i> Rekam Medis Audiometri</span>
			<h1>Detail Pemeriksaan Pasien</h1>
			<p>Data lengkap audiogram nada murni, tabel ambang pendengaran, dan kesimpulan dokter.</p>
		</div>
		<div class="d-flex align-items-center gap-2 flex-wrap">
			<a href="<?= html_escape(base_url('list_tests')) ?>" class="btn btn-outline-secondary">
				<i class="bi bi-arrow-left me-1" aria-hidden="true"></i> Kembali
			</a>
			<a href="<?= html_escape(base_url('edit/' . $test_data['id'])) ?>" class="btn btn-outline-primary">
				<i class="bi bi-pencil-square me-1" aria-hidden="true"></i> Edit Data
			</a>
			<a href="<?= html_escape(base_url('export_pdf/' . $test_data['id'])) ?>" class="btn btn-outline-secondary" target="_blank" rel="noopener">
				<i class="bi bi-file-earmark-pdf me-1" aria-hidden="true"></i> Unduh PDF
			</a>
			<button type="button" onclick="showDeleteModal(<?= html_escape((int) $test_data['id']) ?>)" class="btn btn-danger">
				<i class="bi bi-trash3 me-1" aria-hidden="true"></i> Hapus
			</button>
		</div>
	</div>

	<!-- Patient Summary -->
	<div class="card mb-4">
		<div class="card-body">
			<div class="row align-items-center g-4">
				<div class="col-lg-7">
					<div class="d-flex align-items-start gap-3">
						<span class="avatar avatar-lg avatar-gradient" aria-hidden="true"><?= html_escape(strtoupper(substr($test_data['nama'] ?? 'P', 0, 1))) ?></span>
						<div>
							<div class="d-flex align-items-center gap-2 flex-wrap">
								<h2 class="h4 fw-bold mb-0"><?= html_escape($test_data['nama']) ?></h2>
								<span class="badge text-bg-light border font-monospace">RM: <?= html_escape($test_data['no_rm']) ?></span>
								<?php if ($is_reviewed): ?>
									<span class="badge bg-success"><i class="bi bi-check-circle-fill me-1" aria-hidden="true"></i>Sudah Direview</span>
								<?php else: ?>
									<span class="badge bg-warning text-dark"><i class="bi bi-hourglass-split me-1" aria-hidden="true"></i>Menunggu Review</span>
								<?php endif; ?>
							</div>
							<div class="text-body-secondary mt-2 d-flex align-items-center gap-3 flex-wrap small">
								<span><i class="bi bi-gender-ambiguous text-primary me-1" aria-hidden="true"></i><?= html_escape($test_data['jenis_kelamin'] ?? '-') ?></span>
								<span><i class="bi bi-person text-primary me-1" aria-hidden="true"></i><?= html_escape($test_data['umur'] ?? '-') ?> Tahun</span>
								<span><i class="bi bi-building text-primary me-1" aria-hidden="true"></i><?= html_escape($test_data['perusahaan'] ?? '-') ?></span>
								<span><i class="bi bi-briefcase text-primary me-1" aria-hidden="true"></i><?= html_escape($test_data['jabatan'] ?? '-') ?></span>
							</div>
						</div>
					</div>
				</div>
				<div class="col-lg-5">
					<div class="row g-0 text-center bg-body-tertiary border rounded-3 py-3">
						<div class="col-6 border-end">
							<small class="stat-label d-block">Tanggal Tes</small>
							<strong><?= html_escape(date('d M Y', strtotime($test_data['tanggal_tes']))) ?></strong>
						</div>
						<div class="col-6">
							<small class="stat-label d-block">Terakhir Disimpan</small>
							<strong><?= html_escape(!empty($test_data['updated_at']) ? date('d/m/Y H:i', strtotime($test_data['updated_at'])) : '-') ?></strong>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<!-- Audiogram Charts -->
	<div class="card mb-4">
		<div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
			<h2 class="h6 card-title mb-0">
				<i class="bi bi-graph-up text-primary me-2" aria-hidden="true"></i>Audiogram Nada Murni (Pure Tone Audiometry)
			</h2>
			<span class="badge text-bg-light border">AC &amp; BC Comparison</span>
		</div>
		<div class="card-body">
			<div class="row g-4">
				<!-- Right Ear Chart -->
				<div class="col-lg-6">
					<div class="ear-chart-view h-100">
						<div class="ear-title ear-title-right d-flex justify-content-between align-items-center">
							<span><i class="bi bi-ear me-1" aria-hidden="true"></i> TELINGA KANAN (AS)</span>
							<span class="badge bg-danger">RED / MERAH</span>
						</div>
						<div class="chart-container-view">
							<svg width="100%" height="100%" id="right-chart-view">
								<!-- Grid akan di-generate oleh JavaScript -->
							</svg>
						</div>
						<div class="small fw-bold text-danger mb-2"><i class="bi bi-table me-1" aria-hidden="true"></i> Ambang Pendengaran Kanan</div>
						<?php $render_threshold_table('right', 'text-danger'); ?>
					</div>
				</div>

				<!-- Left Ear Chart -->
				<div class="col-lg-6">
					<div class="ear-chart-view h-100">
						<div class="ear-title ear-title-left d-flex justify-content-between align-items-center">
							<span><i class="bi bi-ear me-1" aria-hidden="true"></i> TELINGA KIRI (AD)</span>
							<span class="badge bg-primary">BLUE / BIRU</span>
						</div>
						<div class="chart-container-view">
							<svg width="100%" height="100%" id="left-chart-view">
								<!-- Grid akan di-generate oleh JavaScript -->
							</svg>
						</div>
						<div class="small fw-bold text-primary mb-2"><i class="bi bi-table me-1" aria-hidden="true"></i> Ambang Pendengaran Kiri</div>
						<?php $render_threshold_table('left', 'text-primary'); ?>
					</div>
				</div>
			</div>

			<!-- Legend -->
			<div class="legend-view mt-4 mb-0">
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

	<!-- Impression / Clinical Conclusion -->
	<div class="card mb-4">
		<div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
			<h2 class="h6 card-title mb-0">
				<i class="bi bi-clipboard2-pulse text-primary me-2" aria-hidden="true"></i>Impression &amp; Kesimpulan Dokter
			</h2>
			<?php if ($is_reviewed): ?>
				<span class="badge bg-success"><i class="bi bi-check-circle-fill me-1" aria-hidden="true"></i>Terverifikasi</span>
			<?php else: ?>
				<a href="<?= html_escape(base_url('review_dokter/' . $test_data['id'])) ?>" class="btn btn-sm btn-warning">
					<i class="bi bi-pencil-square me-1" aria-hidden="true"></i> Tinjau Sekarang
				</a>
			<?php endif; ?>
		</div>
		<div class="card-body">
			<div class="impression-box p-4 rounded-3 border <?= $is_reviewed ? 'bg-body-tertiary' : 'bg-warning-subtle' ?>">
				<?php if ($is_reviewed): ?>
					<div class="d-flex align-items-start gap-3">
						<i class="bi bi-quote fs-1 text-body-secondary opacity-50 flex-shrink-0" aria-hidden="true"></i>
						<p class="fs-5 mb-0 fw-medium impression-text"><?= html_escape($test_data['impression']) ?></p>
					</div>
				<?php else: ?>
					<div class="text-center py-3">
						<i class="bi bi-hourglass-split fs-2 text-warning d-block mb-2" aria-hidden="true"></i>
						<span class="fw-bold d-block">Belum Direview oleh Dokter</span>
						<small class="text-body-secondary">Pemeriksaan ini sedang menunggu tinjauan dan pengisian impression oleh dokter pemeriksa.</small>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</div>

	<!-- Action Footer Buttons -->
	<div class="d-flex justify-content-between align-items-center flex-wrap gap-2 pt-2 pb-4">
		<a href="<?= html_escape(base_url('list_tests')) ?>" class="btn btn-outline-secondary">
			<i class="bi bi-arrow-left me-1" aria-hidden="true"></i> Kembali ke Daftar
		</a>
		<div class="d-flex gap-2 flex-wrap">
			<a href="<?= html_escape(base_url('edit/' . $test_data['id'])) ?>" class="btn btn-outline-primary">
				<i class="bi bi-pencil-square me-1" aria-hidden="true"></i> Edit Data
			</a>
			<a href="<?= html_escape(base_url('export_pdf/' . $test_data['id'])) ?>" class="btn btn-outline-secondary" target="_blank" rel="noopener">
				<i class="bi bi-file-earmark-pdf me-1" aria-hidden="true"></i> Unduh PDF
			</a>
			<a href="<?= html_escape(base_url('create')) ?>" class="btn btn-primary">
				<i class="bi bi-plus-lg me-1" aria-hidden="true"></i> Pemeriksaan Baru
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
				svg.appendChild(createBcMarker(x, y, ear));
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
			path.setAttribute('class', 'hearing-line bc');
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
			path.setAttribute('class', 'hearing-line');
			svg.appendChild(path);
		}
	}

	// Initialize charts when page loads
	window.addEventListener('load', initViewCharts);
	window.addEventListener('resize', () => {
		setTimeout(initViewCharts, 100);
	});
</script>
