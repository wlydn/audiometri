<!DOCTYPE html>
<html>

<head>
	<meta charset="UTF-8">
	<title>Hasil Tes Audiometri</title>
	<style>
		@page {
			size: A4;
			margin: 20mm 15mm;
			-webkit-print-color-adjust: exact;
			color-adjust: exact;
			print-color-adjust: exact;
		}

		body {
			font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
			margin: 0;
			padding: 0;
			font-size: 11px;
			line-height: 1.4;
			color: #2c3e50;
			background: #fff;
			width: 210mm;
			min-height: 297mm;
			-webkit-print-color-adjust: exact;
			color-adjust: exact;
			print-color-adjust: exact;
		}

		/* Header Section */
		.header {
			background: white;
			padding: 15px 20px;
			margin: 0 0 20px 0;
			border-radius: 6px;
			box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
			border: 2px solid #0066CC;
			-webkit-print-color-adjust: exact;
			color-adjust: exact;
			print-color-adjust: exact;
		}

		.clinic-header {
			display: flex;
			align-items: center;
		}

		.clinic-logo {
			width: 80px;
			height: 80px;
			margin-right: 20px;
			flex-shrink: 0;
		}

		.clinic-info {
			flex: 1;
		}

		.clinic-name {
			font-size: 24px;
			font-weight: 700;
			color: #0066CC;
			margin: 0;
			text-transform: uppercase;
			letter-spacing: 1px;
		}

		.clinic-tagline {
			font-size: 12px;
			color: #DC2626;
			font-style: italic;
			margin: 2px 0 8px 0;
		}

		.clinic-address {
			font-size: 11px;
			color: #2c3e50;
			margin: 0;
			line-height: 1.3;
		}

		.clinic-phone {
			font-size: 11px;
			color: #2c3e50;
			margin: 2px 0 0 0;
			font-weight: 600;
		}

		.document-title {
			text-align: center;
			background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
			color: white;
			padding: 5px 10px;
			margin: 0;
			border-radius: 4px;
			-webkit-print-color-adjust: exact;
			color-adjust: exact;
			print-color-adjust: exact;
		}

		.document-title h1 {
			font-size: 18px;
			font-weight: 700;
			margin: 0;
			text-transform: uppercase;
			letter-spacing: 0.5px;
		}

		.document-subtitle {
			font-size: 12px;
			margin: 2px 0 0 0;
			opacity: 0.9;
		}

		/* Patient Information */
		.patient-section {
			background: #f8fafc;
			border-radius: 6px;
			padding: 16px;
			margin: 0 0 20px 0;
			border-left: 4px solid #667eea;
			border-right: 4px solid #667eea;
		}

		.patient-section h2 {
			margin: 0 0 15px 0;
			color: #2c3e50;
			font-size: 16px;
			font-weight: 600;
		}

		.patient-info {
			width: 100%;
			border-collapse: collapse;
		}

		.patient-info td {
			padding: 8px 12px;
			font-size: 11px;
			vertical-align: top;
			border-bottom: 1px solid #e2e8f0;
		}

		.patient-info .label {
			font-weight: 600;
			color: #4a5568;
			width: 20%;
			background: #edf2f7;
		}

		.patient-info .value {
			color: #2d3748;
			font-weight: 500;
		}

		/* Audiogram Section */
		.audiogram-section {
			margin: 20px 0;
		}

		.audiogram-title {
			text-align: center;
			font-size: 16px;
			font-weight: 700;
			background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
			color: white;
			padding: 12px;
			margin: 0 0 18px 0;
			border-radius: 6px;
			text-transform: uppercase;
			letter-spacing: 0.5px;
			-webkit-print-color-adjust: exact;
			color-adjust: exact;
			print-color-adjust: exact;
		}

		/* Modern table layout for A4 */
		.ear-charts-table {
			width: 100%;
			border-collapse: separate;
			border-spacing: 15px 0;
			margin: 0;
		}

		.ear-charts-table td {
			width: 47.5%;
			vertical-align: top;
			padding: 0;
		}

		.ear-charts-table .spacer {
			width: 5%;
		}

		.ear-chart {
			background: #fff;
			border-radius: 6px;
			box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
			overflow: hidden;
			border: 1px solid #e2e8f0;
		}

		.ear-title {
			text-align: center;
			font-weight: 600;
			background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);
			color: #2c3e50;
			padding: 10px;
			font-size: 12px;
			text-transform: uppercase;
			letter-spacing: 0.3px;
			margin: 0;
			-webkit-print-color-adjust: exact;
			color-adjust: exact;
			print-color-adjust: exact;
		}

		.chart-container {
			width: 100%;
			height: 303px;
			background: #fff;
			position: relative;
			border-bottom: 1px solid #e2e8f0;
			margin: 0;
			padding: 0;
			overflow: hidden;
		}

		.chart-svg {
			width: 100%;
			height: 100%;
		}

		/* Data Tables */
		.data-table {
			width: 100%;
			border-collapse: collapse;
			font-size: 9px;
			margin: 0;
			table-layout: fixed;
		}

		.data-table th {
			background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
			color: white;
			padding: 8px 2px;
			text-align: center;
			font-weight: 600;
			font-size: 8px;
			-webkit-print-color-adjust: exact;
			color-adjust: exact;
			print-color-adjust: exact;
			width: 11.11%;
			/* 100% / 9 columns = 11.11% each */
		}

		.data-table th:first-child {
			width: 15%;
			/* Slightly wider for "Frekuensi (Hz)" */
		}

		.data-table th:last-child {
			width: 12%;
			/* Slightly wider for "Rata²" */
		}

		.data-table th:not(:first-child):not(:last-child) {
			width: 10.43%;
			/* (100% - 15% - 12%) / 7 = 10.43% for frequency columns */
		}

		.data-table td {
			padding: 6px 2px;
			text-align: center;
			border-bottom: 1px solid #e2e8f0;
			font-weight: 500;
			width: 11.11%;
		}

		.data-table td:first-child {
			width: 15%;
		}

		.data-table td:last-child {
			width: 12%;
		}

		.data-table td:not(:first-child):not(:last-child) {
			width: 10.43%;
		}

		.data-table .row-label {
			background: #f7fafc;
			font-weight: 700;
			color: #4a5568;
			text-align: center;
		}

		.data-table .average-cell {
			background: #edf2f7;
			font-weight: 700;
			color: #2d3748;
			text-align: center;
		}

		/* Impression Section */
		.impression {
			margin: 18px 0 0 0;
			background: #f7fafc;
			border-radius: 6px;
			padding: 12px 12px;
			max-width: 550px;
			border-left: 4px solid #48bb78;
			border-right: 4px solid #48bb78;
		}

		.impression h3 {
			margin: 0 0 10px 0;
			font-size: 14px;
			font-weight: 600;
			color: #2c3e50;
		}

		.impression p {
			font-weight: bold;
			margin: 0;
			font-size: 11px;
			line-height: 1.5;
			color: #4a5568;
		}

		/* Legend */
		.legend {
			position: fixed;
			bottom: 60px;
			right: 15px;
			background: white;
			border-radius: 6px;
			box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
			padding: 12px;
			font-size: 9px;
			border: 1px solid #e2e8f0;
			width: 140px;
		}

		.legend table {
			border-collapse: collapse;
			width: 100%;
		}

		.legend td {
			padding: 3px 6px;
			vertical-align: middle;
		}

		.legend-header {
			font-weight: 700;
			text-align: center;
			color: #2c3e50;
			border-bottom: 2px solid #667eea;
			padding-bottom: 4px;
			margin-bottom: 4px;
		}

		.legend-symbol {
			width: 18px;
			height: 15px;
			display: inline-block;
			text-align: center;
			line-height: 15px;
		}

		.legend-ac-right {
			color: #DC2626;
			font-weight: bold;
			font-size: 11px;
		}

		.legend-ac-left {
			color: #DC2626;
			font-weight: bold;
			font-size: 11px;
		}

		.legend-bc-right {
			width: 10px;
			height: 10px;
			background: #0066CC;
			border-radius: 50%;
			display: inline-block;
			-webkit-print-color-adjust: exact;
			color-adjust: exact;
			print-color-adjust: exact;
		}

		.legend-bc-left {
			width: 10px;
			height: 10px;
			background: #0066CC;
			transform: rotate(45deg);
			display: inline-block;
			-webkit-print-color-adjust: exact;
			color-adjust: exact;
			print-color-adjust: exact;
		}

		/* Footer */
		.footer {
			position: fixed;
			bottom: 10px;
			left: 15px;
			right: 15px;
			text-align: center;
			font-size: 9px;
			color: #718096;
			border-top: 1px solid #e2e8f0;
			padding-top: 8px;
			bottom: 0;
		}

		/* Chart Styles */
		.grid-line {
			stroke: #e2e8f0;
			stroke-width: 0.5;
		}

		.grid-line-major {
			stroke: #cbd5e0;
			stroke-width: 0.8;
		}

		.axis-label {
			font-size: 9px;
			fill: #4a5568;
			font-family: 'Segoe UI', sans-serif;
			font-weight: 500;
		}

		.hearing-line-ac {
			stroke-width: 1.8;
			fill: none;
			stroke-dasharray: 3, 3;
		}

		.hearing-line-bc {
			stroke-width: 1.8;
			fill: none;
		}

		/* Print optimizations */
		@media print {
			* {
				-webkit-print-color-adjust: exact !important;
				color-adjust: exact !important;
				print-color-adjust: exact !important;
			}

			body {
				-webkit-print-color-adjust: exact !important;
				color-adjust: exact !important;
				print-color-adjust: exact !important;
			}

			.header {
				-webkit-print-color-adjust: exact !important;
				color-adjust: exact !important;
				print-color-adjust: exact !important;
				border: 2px solidrgb(3, 100, 197) !important;
			}

			.clinic-name {
				-webkit-print-color-adjust: exact !important;
				color-adjust: exact !important;
				print-color-adjust: exact !important;
				color: #0066CC !important;
			}

			.clinic-tagline {
				-webkit-print-color-adjust: exact !important;
				color-adjust: exact !important;
				print-color-adjust: exact !important;
				color: #DC2626 !important;
			}

			.document-title {
				-webkit-print-color-adjust: exact !important;
				color-adjust: exact !important;
				print-color-adjust: exact !important;
				background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
			}

			.audiogram-title {
				-webkit-print-color-adjust: exact !important;
				color-adjust: exact !important;
				print-color-adjust: exact !important;
				background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%) !important;
			}

			.ear-title {
				-webkit-print-color-adjust: exact !important;
				color-adjust: exact !important;
				print-color-adjust: exact !important;
				background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%) !important;
			}

			.data-table th {
				-webkit-print-color-adjust: exact !important;
				color-adjust: exact !important;
				print-color-adjust: exact !important;
				background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
			}

			.clinic-header {
				display: flex !important;
				align-items: center !important;
			}

			.clinic-logo {
				width: 80px !important;
				height: 80px !important;
				margin-right: 20px !important;
			}

			.legend {
				position: absolute;
				bottom: 60px;
				right: 15px;
				-webkit-print-color-adjust: exact !important;
				color-adjust: exact !important;
				print-color-adjust: exact !important;
			}

			.footer {
				position: absolute;
				bottom: 10px;
				-webkit-print-color-adjust: exact !important;
				color-adjust: exact !important;
				print-color-adjust: exact !important;
			}

			svg * {
				-webkit-print-color-adjust: exact !important;
				color-adjust: exact !important;
				print-color-adjust: exact !important;
			}
		}
	</style>
</head>

<body>
	<!-- Header -->
	<div class="header">
		<!-- Clinic Header -->
		<div class="clinic-header">
			<div class="clinic-logo">
				<!-- Logo akan ditampilkan di sini -->
				<?php if (isset($logo_base64) && $logo_base64): ?>
					<img src="<?= html_escape($logo_base64) ?>" alt="Medisina Logo" width="80" height="80">
				<?php else: ?>
					<div style="width: 80px; height: 80px; background: #f0f0f0; border: 2px dashed #ccc; display: flex; align-items: center; justify-content: center; font-size: 10px;">
						Logo Medissina
					</div>
				<?php endif; ?>
			</div>
			<div class="clinic-info">
				<h1 class="clinic-name">KLINIK UTAMA MEDISSINA</h1>
				<p class="clinic-tagline">the heART of HOSPITALity</p>
				<p class="clinic-address">
					Jl. Raya Celeng Barat No.57 (Depan Polsek Lohbener) Lohbener - Indramayu
				</p>
				<p class="clinic-phone">Telp. (0234) 271213</p>
			</div>
		</div>
	</div>

	<!-- Document Title -->
	<div class="document-title">
		<h1>Hasil Tes Audiometri</h1>
		<p class="document-subtitle">dr. Ade Burhanudin, Sp.THT-KL</p>
	</div>

	<!-- Patient Information -->
	<div class="patient-section">
		<table class="patient-info">
			<tr>
				<td class="label">Perusahaan</td>
				<td class="value"><?= html_escape($test_data['perusahaan']) ?></td>
				<td class="label">Nama Lengkap</td>
				<td class="value"><?= html_escape($test_data['nama']) ?></td>
			</tr>
			<tr>
				<td class="label">Jabatan</td>
				<td class="value"><?= html_escape($test_data['jabatan']) ?></td>
				<td class="label">Usia</td>
				<td class="value"><?= html_escape($test_data['umur']) ?> Tahun</td>
			</tr>
			<tr>
				<td class="label">Tanggal Tes</td>
				<td class="value"><?= html_escape(date('d F Y', strtotime($test_data['tanggal_tes']))) ?></td>
				<td class="label">Jenis Kelamin</td>
				<td class="value"><?= html_escape($test_data['jenis_kelamin']) ?></td>
			</tr>
			<tr>
				<td class="label">No. Rekam Medis</td>
				<td class="value"><?= html_escape($test_data['no_rm']) ?></td>
				<td class="label">Status</td>
				<td class="value">Completed</td>
			</tr>
		</table>
	</div>

	<!-- Audiogram Section -->
	<div class="audiogram-section">
		<div class="audiogram-title">Audiogram Chart</div>

		<table class="ear-charts-table">
			<tr>
				<!-- Right Ear Chart -->
				<td>
					<div class="ear-chart">
						<div class="ear-title">Telinga Kanan (Right Ear)</div>
						<div class="chart-container">
							<?= generate_audiogram_chart('right', $test_data) ?>
						</div>

						<table class="data-table">
							<tr>
								<th>Frekuensi (Hz)</th>
								<th>250</th>
								<th>500</th>
								<th>1000</th>
								<th>2000</th>
								<th>3000</th>
								<th>4000</th>
								<th>6000</th>
								<th>Rata²</th>
							</tr>
							<tr>
								<td class="row-label">AC (dB)</td>
								<?php
								$frequencies = ['250', '500', '1000', '2000', '3000', '4000', '6000'];
								$right_ac_values = [];
								foreach ($frequencies as $freq):
									$value = $test_data['right_ac_' . $freq];
									if ($value !== null) $right_ac_values[] = $value;
								?>
									<td><?= html_escape($value !== null ? number_format($value, 0) : '-') ?></td>
								<?php endforeach; ?>
								<td class="average-cell">
									<?= html_escape(!empty($right_ac_values) ? number_format(array_sum($right_ac_values) / count($right_ac_values), 1) : '-') ?>
								</td>
							</tr>
							<tr>
								<td class="row-label">BC (dB)</td>
								<?php
								$right_bc_values = [];
								foreach ($frequencies as $freq):
									$value = $test_data['right_bc_' . $freq];
									if ($value !== null) $right_bc_values[] = $value;
								?>
									<td><?= html_escape($value !== null ? number_format($value, 0) : '-') ?></td>
								<?php endforeach; ?>
								<td class="average-cell">
									<?= html_escape(!empty($right_bc_values) ? number_format(array_sum($right_bc_values) / count($right_bc_values), 1) : '-') ?>
								</td>
							</tr>
						</table>
					</div>
				</td>

				<!-- Spacer -->
				<td class="spacer"></td>

				<!-- Left Ear Chart -->
				<td>
					<div class="ear-chart">
						<div class="ear-title">Telinga Kiri (Left Ear)</div>
						<div class="chart-container">
							<?= generate_audiogram_chart('left', $test_data) ?>
						</div>

						<table class="data-table">
							<tr>
								<th>Frekuensi (Hz)</th>
								<th>250</th>
								<th>500</th>
								<th>1000</th>
								<th>2000</th>
								<th>3000</th>
								<th>4000</th>
								<th>6000</th>
								<th>Rata²</th>
							</tr>
							<tr>
								<td class="row-label">AC (dB)</td>
								<?php
								$left_ac_values = [];
								foreach ($frequencies as $freq):
									$value = $test_data['left_ac_' . $freq];
									if ($value !== null) $left_ac_values[] = $value;
								?>
									<td><?= html_escape($value !== null ? number_format($value, 0) : '-') ?></td>
								<?php endforeach; ?>
								<td class="average-cell">
									<?= html_escape(!empty($left_ac_values) ? number_format(array_sum($left_ac_values) / count($left_ac_values), 1) : '-') ?>
								</td>
							</tr>
							<tr>
								<td class="row-label">BC (dB)</td>
								<?php
								$left_bc_values = [];
								foreach ($frequencies as $freq):
									$value = $test_data['left_bc_' . $freq];
									if ($value !== null) $left_bc_values[] = $value;
								?>
									<td><?= html_escape($value !== null ? number_format($value, 0) : '-') ?></td>
								<?php endforeach; ?>
								<td class="average-cell">
									<?= html_escape(!empty($left_bc_values) ? number_format(array_sum($left_bc_values) / count($left_bc_values), 1) : '-') ?>
								</td>
							</tr>
						</table>
					</div>
				</td>
			</tr>
		</table>
	</div>

	<!-- Impression -->
	<div class="impression">
		<h3>🩺 Clinical Impression</h3>
		<p style="padding-left: 25px; font-size: 14px;" ><?= html_escape($test_data['impression']) ?></p>
	</div>

	<!-- Legend -->
	<div class="legend">
		<table>
			<tr>
				<td colspan="2" class="legend-header">
					Chart Legend
				</td>
			</tr>
			<tr>
				<td style="font-weight: 600; color: #0066CC;">AC (Blue)</td>
				<td style="font-weight: 600; color: #DC2626;">BC (Red)</td>
			</tr>
			<tr>
				<td>
					<span class="legend-bc-right"></span> Right
					</td>
					<td>
						<span class="legend-symbol legend-ac-right">◀</span> Right
				</td>
			</tr>
			<tr>
				<td>
					<span class="legend-bc-left"></span> Left
					</td>
					<td>
					<span class="legend-symbol legend-ac-left">▶</span> Left
				</td>
			</tr>
			<tr>
				<td colspan="2" style="font-size: 8px; text-align: left; padding-top: 8px; border-top: 1px solid #e2e8f0; color: #718096;">
					AC = Air Conduction <br>
					BC = Bone Conduction
				</td>
			</tr>
		</table>
	</div>

	<!-- Footer -->
	<div class="footer">
		<p>📄 Dokumen ini dibuat otomatis oleh Sistem Audiometri Digital - Klinik Utama Medisina • Dicetak pada: <?= html_escape(date('d F Y • H:i')) ?> WIB</p>
	</div>
</body>

</html>

<?php
// Enhanced SVG audiogram chart generator
function generate_audiogram_chart($ear, $test_data)
{
	$width = 100; // Percentage width to fit container perfectly
	$height = 100; // Percentage height to fit container perfectly
	$viewBox_width = 300;
	$viewBox_height = 260;
	$margin_left = 35;
	$margin_right = 20;
	$margin_top = 15;
	$margin_bottom = 3;

	$chart_width = $viewBox_width - $margin_left - $margin_right;
	$chart_height = $viewBox_height - $margin_top - $margin_bottom;

	$frequencies = [250, 500, 1000, 2000, 3000, 4000, 6000];
	$db_range = [-10, 140];

	$svg = '<svg class="chart-svg" width="' . $width . '%" height="' . $height . '%" viewBox="0 0 ' . $viewBox_width . ' ' . $viewBox_height . '" xmlns="http://www.w3.org/2000/svg" style="-webkit-print-color-adjust: exact; color-adjust: exact; print-color-adjust: exact;">';

	// Background with subtle gradient
	$svg .= '<defs>';
	$svg .= '<linearGradient id="bgGradient' . $ear . '" x1="0%" y1="0%" x2="0%" y2="100%">';
	$svg .= '<stop offset="0%" style="stop-color:#f8fafc;stop-opacity:1" />';
	$svg .= '<stop offset="100%" style="stop-color:#ffffff;stop-opacity:1" />';
	$svg .= '</linearGradient>';
	$svg .= '</defs>';

	$svg .= '<rect width="' . $viewBox_width . '" height="' . $viewBox_height . '" fill="url(#bgGradient' . $ear . ')" stroke="#e2e8f0" stroke-width="1" rx="3"/>';

	// Chart area background
	$svg .= '<rect x="' . $margin_left . '" y="' . $margin_top . '" width="' . $chart_width . '" height="' . $chart_height . '" fill="#ffffff" stroke="#cbd5e0" stroke-width="0.8"/>';

	// Draw grid
	// Vertical lines (frequencies)
	for ($i = 0; $i < count($frequencies); $i++) {
		$x = $margin_left + ($i * $chart_width / (count($frequencies) - 1));
		$svg .= '<line x1="' . $x . '" y1="' . $margin_top . '" x2="' . $x . '" y2="' . ($viewBox_height - $margin_bottom) . '" class="grid-line"/>';

		// Frequency labels - menggunakan angka penuh, bukan 1K/2K
		$freq_label = $frequencies[$i];
		$svg .= '<text x="' . $x . '" y="' . ($margin_top - 5) . '" text-anchor="middle" class="axis-label">' . $freq_label . '</text>';
	}

	// Horizontal lines (dB levels)
	for ($db = -10; $db <= 140; $db += 10) {
		$y = $margin_top + (($db - $db_range[0]) / ($db_range[1] - $db_range[0])) * $chart_height;
		$class = ($db % 20 == 0) ? 'grid-line-major' : 'grid-line';
		$svg .= '<line x1="' . $margin_left . '" y1="' . $y . '" x2="' . ($viewBox_width - $margin_right) . '" y2="' . $y . '" class="' . $class . '"/>';

		// dB labels
		if ($db >= -10) {  // Hanya tampilkan dari 0 ke atas
			$svg .= '<text x="8" y="' . ($y + 3) . '" class="axis-label" text-anchor="middle">' . $db . '</text>';
		}
	}

	// Axis labels
	// $svg .= '<text x="12" y="12" class="axis-label" style="font-weight: 600; font-size: 9px;">dB</text>';

	// Color scheme: AC = RED (both ears), BC = BLUE (both ears) - Medical Standard
	$ac_color = '#0066CC'; // RED for Air Conduction
	$bc_color = '#DC2626'; // BLUE for Bone Conduction

	// Plot AC (Air Conduction) data
	$ac_points = array();
	$ac_symbols = array();

	foreach ($frequencies as $i => $freq) {
		$value = $test_data[$ear . '_ac_' . $freq];
		if ($value !== null && is_numeric($value)) {
			$x = $margin_left + ($i * $chart_width / (count($frequencies) - 1));
			$y = $margin_top + (($value - $db_range[0]) / ($db_range[1] - $db_range[0])) * $chart_height;
			$ac_points[] = $x . ',' . $y;

			// Add AC symbol - RED for both ears
			if ($ear == 'right') {
				// Circle for right ear AC (blue)
				$ac_symbols[] = '<circle cx="' . $x . '" cy="' . $y . '" r="3" fill="' . $ac_color . '" style="-webkit-print-color-adjust: exact; color-adjust: exact; print-color-adjust: exact;"/>';
			} else {
				// Diamond for left ear AC (blue)
				$ac_symbols[] = '<rect x="' . ($x - 3) . '" y="' . ($y - 3) . '" width="6" height="6" fill="' . $ac_color . '" transform="rotate(45 ' . $x . ' ' . $y . ')" style="-webkit-print-color-adjust: exact; color-adjust: exact; print-color-adjust: exact;"/>';
			}
		}
	}

	// Draw AC line
	if (count($ac_points) > 1) {
		$svg .= '<polyline points="' . implode(' ', $ac_points) . '" stroke="' . $ac_color . '" stroke-width="1.8" fill="none" stroke-linecap="round" style="-webkit-print-color-adjust: exact; color-adjust: exact; print-color-adjust: exact;"/>';
	}

	// Plot BC (Bone Conduction) data
	$bc_points = array();
	$bc_symbols = array();

	foreach ($frequencies as $i => $freq) {
		$value = $test_data[$ear . '_bc_' . $freq];
		if ($value !== null && is_numeric($value)) {
			$x = $margin_left + ($i * $chart_width / (count($frequencies) - 1));
			$y = $margin_top + (($value - $db_range[0]) / ($db_range[1] - $db_range[0])) * $chart_height;
			$bc_points[] = $x . ',' . $y;

			// Add BC symbol (arrow) - RED for both ears
			$symbol = ($ear == 'right') ? '◀' : '▶';
			$bc_symbols[] = '<text x="' . $x . '" y="' . ($y + 3) . '" text-anchor="middle" fill="' . $bc_color . '" font-size="11" font-weight="bold" style="-webkit-print-color-adjust: exact; color-adjust: exact; print-color-adjust: exact;">' . $symbol . '</text>';
		}
	}

	// Draw BC line
	if (count($bc_points) > 1) {
		$svg .= '<polyline points="' . implode(' ', $bc_points) . '" stroke="' . $bc_color . '" stroke-width="1.8" stroke-dasharray="3,3" fill="none" stroke-linecap="round" style="-webkit-print-color-adjust: exact; color-adjust: exact; print-color-adjust: exact;"/>';
	}

	// Add all symbols
	$svg .= implode('', $ac_symbols);
	$svg .= implode('', $bc_symbols);

	$svg .= '</svg>';

	return $svg;
}
?>
