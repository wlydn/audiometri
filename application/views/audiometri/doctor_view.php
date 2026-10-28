<?php
// File: application/views/audiometri/doctor_view.php
$reviewed_count = 0;
$pending_count = 0;
$CI =& get_instance();
$clear_normal = [];
foreach ($tests as $t) {
    if (empty($t['impression']) && $CI->Audiometri_model->is_clearly_normal($t)) {
        $clear_normal[(int) $t['id']] = true;
    }
    if (!empty($t['impression'])) {
        $reviewed_count++;
    } else {
        $pending_count++;
    }
}
?>
<div class="container-fluid p-3 p-lg-4">
	<div class="page-heading">
		<div>
			<span class="eyebrow"><i class="bi bi-clipboard2-pulse me-1" aria-hidden="true"></i> Panel Dokter</span>
			<h1>Review Pemeriksaan Hari Ini</h1>
			<p>Tinjau grafik audiogram dan verifikasi kesimpulan medis (impression) untuk pasien hari ini.</p>
		</div>
		<div class="d-flex align-items-center gap-2 flex-wrap">
			<span class="badge bg-primary-subtle text-primary-emphasis border border-primary-subtle fw-semibold px-3 py-2">
				<i class="bi bi-people me-1" aria-hidden="true"></i> <?= html_escape(number_format(count($tests))) ?> Pasien
			</span>
			<span class="badge bg-success-subtle text-success-emphasis border border-success-subtle fw-semibold px-3 py-2">
				<i class="bi bi-check-circle-fill me-1" aria-hidden="true"></i> <?= html_escape(number_format($reviewed_count)) ?> Selesai
			</span>
			<?php if ($pending_count > 0): ?>
				<span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle fw-semibold px-3 py-2">
					<i class="bi bi-clock me-1" aria-hidden="true"></i> <?= html_escape(number_format($pending_count)) ?> Menunggu
				</span>
			<?php endif; ?>
		</div>
	</div>

	<!-- Info Banner -->
	<div class="alert alert-info d-flex align-items-start gap-3 mb-4" role="alert">
		<i class="bi bi-info-circle-fill fs-4" aria-hidden="true"></i>
		<div>
			<strong class="d-block">Informasi Panel Review:</strong>
			<span>Daftar di bawah menampilkan pasien yang melakukan tes pada hari ini (<strong><?= html_escape(date('d F Y')) ?></strong>). Anda dapat langsung membuka form review untuk melengkapi kesimpulan medis.</span>
		</div>
	</div>

	<!-- Results Card -->
	<div class="card">
		<div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
			<h2 class="h6 card-title mb-0">
				<i class="bi bi-clipboard-check text-primary me-2" aria-hidden="true"></i>Data Pasien Hari Ini &bull; <?= html_escape(date('d/m/Y')) ?>
			</h2>
			<div class="text-body-secondary small">
				<i class="bi bi-calendar3 me-1" aria-hidden="true"></i> <?= html_escape(date('l, d F Y')) ?>
			</div>
		</div>
		<div class="card-body p-0">
			<?php if (!empty($tests)): ?>
				<div id="bulk-bar" class="d-flex align-items-center flex-wrap gap-2 px-3 py-2 border-bottom bg-body-tertiary">
					<div class="form-check mb-0">
						<input class="form-check-input" type="checkbox" id="select-all-normal" <?= empty($clear_normal) ? 'disabled' : '' ?>>
						<label class="form-check-label small" for="select-all-normal">Pilih semua yang jelas normal (<?= html_escape(number_format(count($clear_normal))) ?>)</label>
					</div>
					<button type="button" class="btn btn-sm btn-success ms-auto" id="bulk-approve-btn" disabled>
						<i class="bi bi-check2-all me-1" aria-hidden="true"></i> Setujui sebagai Normal (<span id="bulk-count">0</span>)
					</button>
				</div>
				<div class="table-responsive">
					<table class="table table-hover align-middle mb-0">
						<thead>
							<tr>
								<th scope="col" style="width:2.5rem"><span class="visually-hidden">Pilih</span></th>
								<th scope="col">No</th>
								<th scope="col">Pasien</th>
								<th scope="col">No RM</th>
								<th scope="col">Perusahaan &amp; Jabatan</th>
								<th scope="col">Tanggal Tes</th>
								<th scope="col">Status Review</th>
								<th scope="col" class="text-end">Aksi</th>
							</tr>
						</thead>
						<tbody>
							<?php
							$no = 1;
							foreach ($tests as $test):
								$is_reviewed = !empty($test['impression']);
							?>
								<tr class="<?= !$is_reviewed ? 'row-pending' : '' ?>">
									<td>
										<?php if (isset($clear_normal[(int) $test['id']])): ?>
											<input class="form-check-input normal-check" type="checkbox" value="<?= html_escape($test['id']) ?>" aria-label="Pilih <?= html_escape($test['nama']) ?>">
										<?php endif; ?>
									</td>
									<td class="text-body-secondary fw-bold"><?= html_escape($no++) ?></td>
									<td>
										<div class="d-flex align-items-center gap-2">
											<span class="avatar <?= $is_reviewed ? 'avatar-success' : 'avatar-warning' ?>" aria-hidden="true"><?= html_escape(strtoupper(substr($test['nama'] ?? 'P', 0, 1))) ?></span>
											<div>
												<strong class="d-block"><?= html_escape($test['nama']) ?></strong>
												<small class="text-body-secondary"><?= html_escape($test['jenis_kelamin']) ?> &bull; <?= html_escape($test['umur']) ?> thn</small>
											</div>
										</div>
									</td>
									<td>
										<span class="font-monospace small text-body-secondary"><?= html_escape($test['no_rm']) ?></span>
									</td>
									<td>
										<div class="fw-semibold"><?= html_escape($test['perusahaan']) ?></div>
										<small class="text-body-secondary"><?= html_escape($test['jabatan']) ?></small>
									</td>
									<td>
										<span class="text-body-secondary fw-semibold"><?= html_escape(date('d/m/Y', strtotime($test['tanggal_tes']))) ?></span>
									</td>
									<td>
										<?php if ($is_reviewed): ?>
											<span class="badge bg-success-subtle text-success-emphasis fw-semibold"><i class="bi bi-check-circle me-1" aria-hidden="true"></i>Sudah Direview</span>
										<?php else: ?>
											<span class="badge bg-warning-subtle text-warning-emphasis fw-semibold"><i class="bi bi-hourglass-split me-1" aria-hidden="true"></i>Menunggu Review</span>
											<?php if (isset($clear_normal[(int) $test['id']])): ?>
												<span class="badge bg-success-subtle text-success-emphasis fw-semibold ms-1">Jelas normal</span>
											<?php endif; ?>
										<?php endif; ?>
									</td>
									<td class="text-end text-nowrap">
										<a href="<?= html_escape(base_url('review_dokter/' . $test['id'])) ?>"
											class="btn btn-sm <?= $is_reviewed ? 'btn-outline-secondary' : 'btn-outline-primary' ?>"
											title="Buka Lembar Review">
											<i class="bi bi-clipboard2-pulse me-1" aria-hidden="true"></i> Review
										</a>
									</td>
								</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
				</div>
			<?php else: ?>
				<div class="text-center py-5 empty-state">
					<i class="bi bi-calendar-check display-4 text-body-secondary" aria-hidden="true"></i>
					<h3 class="h5 mt-3">Tidak Ada Pemeriksaan Hari Ini</h3>
					<p class="text-body-secondary">Belum ada pasien yang menjalani tes audiometri pada <?= html_escape(date('d F Y')) ?>.</p>
					<a href="<?= html_escape(base_url('list_tests')) ?>" class="btn btn-outline-primary mt-2">
						<i class="bi bi-folder2-open me-1" aria-hidden="true"></i> Lihat Seluruh Riwayat Tes
					</a>
				</div>
			<?php endif; ?>
		</div>
	</div>
</div>

<?php if (!empty($clear_normal)): ?>
<script>
(function () {
	const selectAll = document.getElementById('select-all-normal');
	const btn = document.getElementById('bulk-approve-btn');
	const counter = document.getElementById('bulk-count');
	const checks = Array.from(document.querySelectorAll('.normal-check'));

	function selected() {
		return checks.filter(function (c) { return c.checked; }).map(function (c) { return c.value; });
	}

	function sync() {
		const n = selected().length;
		counter.textContent = n;
		btn.disabled = n === 0;
		selectAll.checked = n > 0 && n === checks.length;
		selectAll.indeterminate = n > 0 && n < checks.length;
	}

	selectAll.addEventListener('change', function () {
		checks.forEach(function (c) { c.checked = selectAll.checked; });
		sync();
	});
	checks.forEach(function (c) { c.addEventListener('change', sync); });

	btn.addEventListener('click', function () {
		const ids = selected();
		if (!ids.length) return;

		Swal.fire({
			title: 'Setujui sebagai normal?',
			html: ids.length + ' pasien akan disimpan dengan impression:<br><strong>Ambang dengar kedua telinga dalam batas normal.</strong><br><br>Pastikan Anda sudah memeriksa grafiknya.',
			icon: 'question',
			showCancelButton: true,
			confirmButtonText: 'Ya, setujui',
			cancelButtonText: 'Batal',
			reverseButtons: true
		}).then(function (result) {
			if (!result.isConfirmed) return;

			Swal.fire({ title: 'Menyimpan...', allowOutsideClick: false, showConfirmButton: false, didOpen: function () { Swal.showLoading(); } });

			const body = new FormData();
			ids.forEach(function (id) { body.append('ids[]', id); });

			fetch(<?= json_encode(base_url('bulk_approve_normal'), JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>, {
				method: 'POST',
				body: withCsrf(body),
				headers: { 'X-Requested-With': 'XMLHttpRequest' }
			})
			.then(function (r) { return r.json(); })
			.then(function (data) {
				Swal.fire({
					title: data.status ? 'Berhasil' : 'Tidak ada yang disetujui',
					text: data.message,
					icon: data.status ? 'success' : 'warning'
				}).then(function () { window.location.reload(); });
			})
			.catch(function () {
				Swal.fire({ title: 'Error', text: 'Terjadi kesalahan saat menyimpan', icon: 'error' });
			});
		});
	});

	sync();
})();
</script>
<?php endif; ?>

