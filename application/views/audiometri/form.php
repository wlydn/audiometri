<!-- Audiometri Form View -->
<div class="container-fluid">
	<div class="audiometri-container">
		<div class="header">
			<span class="eyebrow">Ruang pemeriksaan</span>
			<h1><?= html_escape($action == 'edit' ? 'Edit pemeriksaan' : 'Pemeriksaan baru') ?></h1>
			<p>Lengkapi identitas pasien, kemudian catat ambang pendengaran. Kolom bertanda * wajib diisi.</p>
		</div>

		<?php if ($this->session->flashdata('error')): ?>
			<div class="alert alert-danger alert-dismissible fade show" role="alert">
				<?= html_escape($this->session->flashdata('error')) ?>
				<button type="button" class="close" data-dismiss="alert">&times;</button>
			</div>
		<?php endif; ?>

		<?php if ($this->session->flashdata('success')): ?>
			<div class="alert alert-success alert-dismissible fade show" role="alert">
				<?= html_escape($this->session->flashdata('success')) ?>
				<button type="button" class="close" data-dismiss="alert">&times;</button>
			</div>
		<?php endif; ?>

		<form id="audiometri-form" method="post" action="<?= html_escape(current_url()) ?>">
			<input type="hidden" name="<?= html_escape($this->security->get_csrf_token_name()) ?>" value="<?= html_escape($this->security->get_csrf_hash()) ?>">
			<div class="patient-info">
				<div class="info-group">
					<label for="perusahaan">Perusahaan: <span class="text-danger">*</span></label>
					<input type="text" id="perusahaan" name="perusahaan"
						value="<?= set_value('perusahaan', $test_data['perusahaan'] ?? '') ?>"
						placeholder="Masukkan nama perusahaan" required>
					<?= form_error('perusahaan', '<small class="text-danger">', '</small>') ?>
				</div>

				<div class="info-group">
					<label for="jabatan">Jabatan: <span class="text-danger">*</span></label>
					<input type="text" id="jabatan" name="jabatan"
						value="<?= set_value('jabatan', $test_data['jabatan'] ?? '') ?>"
						placeholder="Masukkan jabatan" required>
					<?= form_error('jabatan', '<small class="text-danger">', '</small>') ?>
				</div>

				<div class="info-group">
					<label for="nama">Nama: <span class="text-danger">*</span></label>
					<input type="text" id="nama" name="nama"
						value="<?= set_value('nama', $test_data['nama'] ?? '') ?>"
						placeholder="Masukkan nama lengkap" required>
					<?= form_error('nama', '<small class="text-danger">', '</small>') ?>
				</div>

				<div class="info-group">
					<label for="umur">Umur: <span class="text-danger">*</span></label>
					<input type="text" id="umur" name="umur" min="1" max="150"
						value="<?= set_value('umur', $test_data['umur'] ?? '') ?>"
						placeholder="Masukkan umur" required>
					<?= form_error('umur', '<small class="text-danger">', '</small>') ?>
				</div>

				<div class="info-group">
					<label for="tanggal_tes">Tanggal Tes: <span class="text-danger">*</span></label>
					<input type="date" id="tanggal_tes" name="tanggal_tes"
						value="<?= set_value('tanggal_tes', $test_data['tanggal_tes'] ?? date('Y-m-d')) ?>" required>
					<?= form_error('tanggal_tes', '<small class="text-danger">', '</small>') ?>
				</div>

				<div class="info-group">
					<label for="jenis_kelamin">Jenis Kelamin: <span class="text-danger">*</span></label>
					<select id="jenis_kelamin" name="jenis_kelamin" required>
						<option value="">Pilih Jenis Kelamin</option>
						<option value="Laki-laki" <?= set_select('jenis_kelamin', 'Laki-laki', ($test_data['jenis_kelamin'] ?? '') === 'Laki-laki') ?>>Laki-laki</option>
						<option value="Perempuan" <?= set_select('jenis_kelamin', 'Perempuan', ($test_data['jenis_kelamin'] ?? '') === 'Perempuan') ?>>Perempuan</option>
					</select>
					<?= form_error('jenis_kelamin', '<small class="text-danger">', '</small>') ?>
				</div>

				<div class="info-group">
					<label for="no_rm">No RM: <span class="text-danger">*</span></label>
					<input type="text" id="no_rm" name="no_rm"
						value="<?= set_value('no_rm', $test_data['no_rm'] ?? '') ?>"
						placeholder="Nomor Rekam Medis" required>
					<button type="button" class="btn btn-info btn-sm mt-2" onclick="searchByNoRM()">
						Cari Riwayat
					</button>
					<?= form_error('no_rm', '<small class="text-danger">', '</small>') ?>
				</div>
			</div>

			<div class="audiogram-section">
				<h1 class="audiogram-title">AUDIOGRAM</h1>

				<div class="ear-charts">
					<!-- Right Ear Chart -->
					<div class="ear-chart">
						<div class="ear-title">TELINGA KANAN</div>

						<!-- AC Right Section -->
						<div class="conduction-section">
							<div class="frequency-inputs">
								<div class="freq-input-group">
									<label>FREKUENSI</label>
									<div class="conduction-title">AC-Right</div>
								</div>
								<?php
								$frequencies = ['250', '500', '1000', '2000', '3000', '4000', '6000'];
								foreach ($frequencies as $freq):
								?>
									<div class="freq-input-group">
										<label><?= html_escape($freq) ?> Hz</label>
										<input type="number" id="right-ac-<?= html_escape($freq) ?>" name="right_ac_<?= html_escape($freq) ?>"
											placeholder="dB" min="-10" max="140" step="5"
											value="<?= set_value('right_ac_' . $freq, $test_data['right_ac_' . $freq] ?? '') ?>">
									</div>
								<?php endforeach; ?>
							</div>

							<!-- BC Right Section -->
							<div class="frequency-inputs">
								<div class="freq-input-group">
									<div class="conduction-title2">BC-Right</div>
									<label style="color: transparent;">FREKUENSI</label>
								</div>
								<?php foreach ($frequencies as $freq): ?>
									<div class="freq-input-group">
										<input type="number" id="right-bc-<?= html_escape($freq) ?>" name="right_bc_<?= html_escape($freq) ?>"
											placeholder="dB" min="-10" max="140" step="5"
											value="<?= set_value('right_bc_' . $freq, $test_data['right_bc_' . $freq] ?? '') ?>">
									</div>
								<?php endforeach; ?>
							</div>
						</div>

						<div class="chart-container">
							<svg width="100%" height="100%" id="right-chart">
								<!-- Grid akan di-generate oleh JavaScript -->
							</svg>
						</div>
					</div>

					<!-- Left Ear Chart -->
					<div class="ear-chart">
						<div class="ear-title">TELINGA KIRI</div>

						<!-- AC Left Section -->
						<div class="conduction-section">
							<div class="frequency-inputs">
								<div class="freq-input-group">
									<label>FREKUENSI</label>
									<div class="conduction-title">AC-Left</div>
								</div>
								<?php foreach ($frequencies as $freq): ?>
									<div class="freq-input-group">
										<label><?= html_escape($freq) ?> Hz</label>
										<input type="number" id="left-ac-<?= html_escape($freq) ?>" name="left_ac_<?= html_escape($freq) ?>"
											placeholder="dB" min="-10" max="140" step="5"
											value="<?= set_value('left_ac_' . $freq, $test_data['left_ac_' . $freq] ?? '') ?>">
									</div>
								<?php endforeach; ?>
							</div>

							<!-- BC Left Section -->
							<div class="frequency-inputs">
								<div class="freq-input-group">
									<div class="conduction-title2">BC-Left</div>
									<label style="color: transparent;">FREKUENSI</label>
								</div>
								<?php foreach ($frequencies as $freq): ?>
									<div class="freq-input-group">
										<input type="number" id="left-bc-<?= html_escape($freq) ?>" name="left_bc_<?= html_escape($freq) ?>"
											placeholder="dB" min="-10" max="140" step="5"
											value="<?= set_value('left_bc_' . $freq, $test_data['left_bc_' . $freq] ?? '') ?>">
									</div>
								<?php endforeach; ?>
							</div>
						</div>

						<div class="chart-container">
							<svg width="100%" height="100%" id="left-chart">
								<!-- Grid akan di-generate oleh JavaScript -->
							</svg>
						</div>
					</div>
				</div>

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

				<?php if ($action == 'edit'): ?>
				<div class="impression">
					<h3>Impression:</h3>
					<textarea id="impression-text" name="impression"
						class="form-control" rows="4"
						placeholder="Masukkan hasil analisis audiometri, interpretasi, dan rekomendasi..."><?= set_value('impression', $test_data['impression'] ?? '') ?></textarea>
						<small class="form-text text-muted mt-2">
							Silakan isi dengan interpretasi hasil audiometri, tingkat gangguan pendengaran, dan rekomendasi tindak lanjut jika diperlukan.
						</small>
					<?= form_error('impression', '<small class="text-danger">', '</small>') ?>
				</div>
				<?php endif; ?>

				<div class="controls">
					<button type="button" class="btn btn-primary" onclick="updateCharts()">Update Grafik</button>
					<button type="button" class="btn btn-secondary" onclick="clearAudiometricData()">Clear Data Audiometri</button>
					<button type="button" class="btn btn-success" onclick="showSimpanModal()">
						<i class="fas fa-save"></i> <?= html_escape($action == 'edit' ? 'Update' : 'Simpan') ?> Data
					</button>
					<?php if ($action == 'edit'): ?>
						<a href="<?= html_escape(base_url('export_pdf/' . $test_data['id'])) ?>" class="btn btn-info" target="_blank">
							<i class="fas fa-file-pdf"></i> Export PDF
						</a>
					<?php endif; ?>
				</div>
			</div>
		</form>
	</div>
</div>

<script>
	function showSimpanModal() {
		// Validate required fields
		const requiredFields = ['perusahaan', 'jabatan', 'nama', 'umur', 'jenis_kelamin', 'no_rm', 'tanggal_tes'];
		let isValid = true;
		let firstInvalidField = null;
		let missingFields = [];

		requiredFields.forEach(field => {
			const input = document.getElementById(field);
			if (!input.value.trim()) {
				isValid = false;
				input.style.borderColor = '#dc3545';
				if (!firstInvalidField) firstInvalidField = input;

				// Get field label for better error message
				const label = document.querySelector(`label[for="${field}"]`);
				const fieldName = label ? label.textContent.replace(':', '').replace('*', '').trim() : field;
				missingFields.push(fieldName);
			} else {
				input.style.borderColor = '#ced4da';
			}
		});

		if (!isValid) {
			Swal.fire({
				title: 'Data Tidak Lengkap!',
				html: `Mohon lengkapi field berikut:<br><br><strong>${missingFields.join('<br>')}</strong>`,
				icon: 'warning',
				confirmButtonColor: '#3085d6',
				confirmButtonText: 'OK'
			}).then(() => {
				if (firstInvalidField) {
					firstInvalidField.focus();
				}
			});
			return;
		}

		// Show SweetAlert confirmation if validation passes
		Swal.fire({
			title: '<?= $action == 'edit' ? 'Update' : 'Simpan' ?> Data Audiometri',
			text: 'Apakah Anda yakin ingin <?= $action == 'edit' ? 'mengupdate' : 'menyimpan' ?> data audiometri ini?',
			icon: 'question',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Ya, <?= $action == 'edit' ? 'Update' : 'Simpan' ?>!',
			cancelButtonText: 'Batal',
			reverseButtons: true
		}).then((result) => {
			if (result.isConfirmed) {
				submitFormWithLoading();
			}
		});
	}
</script>


<!-- Modal untuk riwayat No RM -->
<div class="modal fade" id="rmHistoryModal" tabindex="-1" role="dialog">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Riwayat Tes - No RM: <span id="modal-no-rm"></span></h5>
				<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">&times;</button>
			</div>
			<div class="modal-body" id="rm-history-content">
				<div class="text-center">
					<div class="spinner-border" role="status">
						<span class="sr-only">Loading...</span>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
			</div>
		</div>
	</div>
</div>

<script>
	// JavaScript untuk audiogram (diambil dari versi HTML asli dengan modifikasi)
	let isFormSubmitting = false;

	// Initialize charts
	function initCharts() {
		createChart('right-chart');
		createChart('left-chart');

		// Update charts if data exists
		if (hasExistingData()) {
			updateCharts();
		}
	}

	function hasExistingData() {
		const frequencies = ['250', '500', '1000', '2000', '3000', '4000', '6000'];
		const ears = ['right', 'left'];
		const types = ['ac', 'bc'];

		for (let ear of ears) {
			for (let type of types) {
				for (let freq of frequencies) {
					const input = document.getElementById(`${ear}-${type}-${freq}`);
					if (input && input.value && input.value.trim() !== '') {
						return true;
					}
				}
			}
		}
		return false;
	}

	function createChart(chartId) {
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

	function updateCharts() {
		updateChart('right', 'right-chart');
		updateChart('left', 'left-chart');
		// updateImpressionAjax();
	}

	function updateChart(ear, chartId) {
		const svg = document.getElementById(chartId);
		if (!svg) return;

		const width = svg.clientWidth || 400;
		const height = svg.clientHeight || 300;

		// Remove existing hearing lines and points
		const existingLines = svg.querySelectorAll('.hearing-line');
		existingLines.forEach(line => line.remove());
		const existingPoints = svg.querySelectorAll('.hearing-point');
		existingPoints.forEach(point => point.remove());
		const existingTexts = svg.querySelectorAll('.arrow-symbol');
		existingTexts.forEach(text => text.remove());
		const existingDiamonds = svg.querySelectorAll('.diamond');
		existingDiamonds.forEach(diamond => diamond.remove());

		const frequencies = [250, 500, 1000, 2000, 3000, 4000, 6000];

		// Draw BC (Air Conduction) line
		const acPoints = [];
		frequencies.forEach((freq, i) => {
			const input = document.getElementById(`${ear}-bc-${freq}`);
			const value = parseFloat(input.value);

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
			path.setAttribute('class', 'hearing-line bc');
			svg.appendChild(path);
		}

		// Draw AC (Bone Conduction) line
		const bcPoints = [];
		frequencies.forEach((freq, i) => {
			const input = document.getElementById(`${ear}-ac-${freq}`);
			const value = parseFloat(input.value);

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

	// function updateImpressionAjax() {
	//     const formData = new FormData(document.getElementById('audiometri-form'));

	//     fetch(<?= json_encode(base_url('update_chart'), JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>, {
	//         method: 'POST',
	//         body: formData,
	//         headers: {
	//             'X-Requested-With': 'XMLHttpRequest'
	//         }
	//     })
	//     .then(response => response.json())
	//     .then(data => {
	//         if (data.status) {
	//             document.getElementById('impression-text').textContent = data.impression;
	//         }
	//     })
	//     .catch(error => {
	//         console.error('Error:', error);
	//     });
	// }

	function clearAudiometricData() {
		if (!confirm('Yakin ingin menghapus semua data audiometri?')) {
			return;
		}

		// Clear frequency inputs
		document.querySelectorAll('.freq-input-group input[type="number"]').forEach(input => {
			input.value = '';
		});

		// Clear charts
		document.querySelectorAll('.hearing-line, .hearing-point, .arrow-symbol, .diamond').forEach(el => {
			el.remove();
		});

		// Clear impression field
		document.getElementById('impression-text').value = '';
	}

	function searchByNoRM() {
		const noRM = document.getElementById('no_rm').value;
		if (!noRM.trim()) {
			alert('Masukkan No RM terlebih dahulu');
			return;
		}

		document.getElementById('modal-no-rm').textContent = noRM;
		$('#rmHistoryModal').modal('show');

		fetch(<?= json_encode(base_url('search_by_no_rm'), JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>, {
				method: 'POST',
				headers: {
					'Content-Type': 'application/x-www-form-urlencoded',
					'X-Requested-With': 'XMLHttpRequest'
				},
				body: withCsrf(new URLSearchParams({ no_rm: noRM })).toString()
			})
			.then(response => response.json())
			.then(data => {
				const escapeHistoryHtml = value => String(value ?? '').replace(/[&<>"']/g, character => ({
                    '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;'
                }[character]));
                let content = '';
				if (data.status && data.count > 0) {
					content = '<div class="table-responsive"><table class="table table-striped">';
					content += '<thead><tr><th>Tanggal</th><th>Nama</th><th>Perusahaan</th><th>Aksi</th></tr></thead><tbody>';

					data.data.forEach(test => {
						content += `<tr>
                    <td>${escapeHistoryHtml(test.tanggal_tes)}</td>
                    <td>${escapeHistoryHtml(test.nama)}</td>
                    <td>${escapeHistoryHtml(test.perusahaan)}</td>
                    <td>
                        <a href="${escapeHistoryHtml(<?= json_encode(base_url('view/'), JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?> + encodeURIComponent(test.id))}" class="btn btn-sm btn-primary" target="_blank">
                            Lihat Detail
                        </a>
                    </td>
                </tr>`;
					});

					content += '</tbody></table></div>';
				} else {
					content = '<div class="alert alert-info">Tidak ada riwayat tes untuk No RM ini.</div>';
				}

				document.getElementById('rm-history-content').innerHTML = content;
			})
			.catch(error => {
				console.error('Error:', error);
				document.getElementById('rm-history-content').innerHTML =
					'<div class="alert alert-danger">Terjadi kesalahan saat mengambil data.</div>';
			});
	}

	// Auto-update charts when input values change
	document.addEventListener('input', function(e) {
		if (e.target.matches('.freq-input-group input[type="number"]') && !isFormSubmitting) {
			clearTimeout(window.updateTimeout);
			window.updateTimeout = setTimeout(updateCharts, 500);
		}
	});

	// Form submission handler with SweetAlert loading
	function submitFormWithLoading() {
		// Show loading state
		Swal.fire({
			title: '<?= $action == 'edit' ? 'Mengupdate' : 'Menyimpan' ?> Data...',
			text: 'Mohon tunggu sebentar',
			allowOutsideClick: false,
			allowEscapeKey: false,
			showConfirmButton: false,
			didOpen: () => {
				Swal.showLoading();
			}
		});

		const form = document.getElementById('audiometri-form');
		isFormSubmitting = true;

		// Submit form using AJAX to handle response with SweetAlert
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
				Swal.fire({
					title: 'Berhasil!',
					text: data.message || 'Data berhasil <?= $action == 'edit' ? 'diupdate' : 'disimpan' ?>',
					icon: 'success',
					timer: 3000,
					timerProgressBar: true,
					showConfirmButton: false
				}).then(() => {
					// Redirect based on action
					<?php if ($action == 'edit'): ?>
						window.location.href = <?= json_encode(base_url('view/' . ($test_data['id'] ?? '')), JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>;
					<?php else: ?>
						if (data.id) {
							window.location.href = <?= json_encode(base_url('view/'), JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?> + data.id;
						} else {
							window.location.href = <?= json_encode(base_url('list_tests'), JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>;
						}
					<?php endif; ?>
				});
			} else {
				Swal.fire({
					title: 'Gagal!',
					text: data.message || 'Gagal <?= $action == 'edit' ? 'mengupdate' : 'menyimpan' ?> data',
					icon: 'error',
					confirmButtonColor: '#3085d6'
				});
				isFormSubmitting = false;
			}
		})
		.catch(error => {
			console.error('Error:', error);
			// Fallback to regular form submission if AJAX fails
			form.submit();
		});
	}

	// Legacy form submission handler (fallback)
	function submitForm() {
		submitFormWithLoading();
	}

	// Form validation before submit
	document.getElementById('audiometri-form').addEventListener('submit', function(e) {
		if (!isFormSubmitting) {
			e.preventDefault();
			showSimpanModal();
			return false;
		}
	});

	// Initialize on load
	window.addEventListener('load', initCharts);
	window.addEventListener('resize', () => {
		setTimeout(initCharts, 100);
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
		padding: 20px;
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
		padding: 20px;
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
		padding: 15px;
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
		cursor: pointer;
		transition: all 0.3s ease;
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
</style>
