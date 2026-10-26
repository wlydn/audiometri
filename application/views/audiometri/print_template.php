<?php
// File: application/views/audiometri/print_template.php - FALLBACK PRINT TEMPLATE
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Print - Hasil Tes Audiometri</title>
    <style>
        @media print {
            .no-print { display: none !important; }
            body { margin: 0; font-size: 12px; }
            @page { margin: 15mm; }
        }

        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            font-size: 10px;
            line-height: 1.3;
        }

        .header {
            text-align: center;
            border: 2px solid #000;
            padding: 15px;
            margin-bottom: 20px;
        }

        .header h1 {
            font-size: 18px;
            margin: 0 0 10px 0;
        }

        .patient-info {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .patient-info td {
            border: 1px solid #000;
            padding: 8px;
            font-size: 11px;
        }

        .patient-info .label {
            font-weight: bold;
            background: #f0f0f0;
            width: 20%;
        }

        .data-section {
            margin: 20px 0;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0;
        }

        .data-table th,
        .data-table td {
            border: 1px solid #000;
            padding: 5px;
            text-align: center;
            font-size: 9px;
        }

        .data-table th {
            background: #e0e0e0;
            font-weight: bold;
        }

        .impression {
            border: 1px solid #000;
            padding: 15px;
            margin-top: 20px;
        }

        .print-info {
            background: #e3f2fd;
            padding: 15px;
            margin: 15px 0;
            border: 1px solid #2196f3;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <!-- Print Instructions -->
    <div class="print-info no-print">
        <h3>📄 Preview Hasil Audiometri</h3>
        <p><strong>Cara Print:</strong> Gunakan Ctrl+P atau menu Print di browser untuk mencetak dokumen ini ke PDF atau printer.</p>
        <button onclick="window.print()" style="background: #2196f3; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer;">
            🖨️ Print Sekarang
        </button>
        <button onclick="history.back()" style="background: #666; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; margin-left: 10px;">
            ← Kembali
        </button>
    </div>

    <!-- Document Content -->
    <div class="header">
        <h1>HASIL TES AUDIOMETRI</h1>
        <p>dr.Ade Burhanudin, Sp.THT-KL</p>
    </div>

    <table class="patient-info">
        <tr>
            <td class="label">Perusahaan</td>
            <td><?= html_escape($test_data['perusahaan']) ?></td>
            <td class="label">Nama</td>
            <td><?= html_escape($test_data['nama']) ?></td>
        </tr>
        <tr>
            <td class="label">Jabatan</td>
            <td><?= html_escape($test_data['jabatan']) ?></td>
            <td class="label">Umur</td>
            <td><?= html_escape($test_data['umur']) ?> Tahun</td>
        </tr>
        <tr>
            <td class="label">Tanggal Tes</td>
            <td><?= html_escape(date('d M Y', strtotime($test_data['tanggal_tes']))) ?></td>
            <td class="label">Jenis Kelamin</td>
            <td><?= html_escape($test_data['jenis_kelamin']) ?></td>
        </tr>
        <tr>
            <td class="label">No.RM</td>
            <td><?= html_escape($test_data['no_rm']) ?></td>
            <td class="label">Tanggal Cetak</td>
            <td><?= html_escape(date('d M Y H:i')) ?></td>
        </tr>
    </table>

    <div class="data-section">
        <h3 style="text-align: center; background: #e0e0e0; padding: 10px; margin: 0;">AUDIOGRAM DATA</h3>

        <h4>TELINGA KANAN</h4>
        <table class="data-table">
            <tr>
                <th>Type</th>
                <th>250Hz</th>
                <th>500Hz</th>
                <th>1000Hz</th>
                <th>2000Hz</th>
                <th>3000Hz</th>
                <th>4000Hz</th>
                <th>6000Hz</th>
                <th>Rata-rata</th>
            </tr>
            <tr>
                <td style="font-weight: bold;">AC</td>
                <?php
                $frequencies = ['250', '500', '1000', '2000', '3000', '4000', '6000'];
                $right_ac_values = [];
                foreach ($frequencies as $freq):
                    $value = $test_data['right_ac_'.$freq];
                    if ($value !== null) $right_ac_values[] = $value;
                    echo '<td>' . ($value !== null ? number_format($value, 0) . ' dB' : '-') . '</td>';
                endforeach;
                ?>
                <td style="font-weight: bold;">
                    <?= html_escape(!empty($right_ac_values) ? number_format(array_sum($right_ac_values) / count($right_ac_values), 1) . ' dB' : '-') ?>
                </td>
            </tr>
            <tr>
                <td style="font-weight: bold;">BC</td>
                <?php
                $right_bc_values = [];
                foreach ($frequencies as $freq):
                    $value = $test_data['right_bc_'.$freq];
                    if ($value !== null) $right_bc_values[] = $value;
                    echo '<td>' . ($value !== null ? number_format($value, 0) . ' dB' : '-') . '</td>';
                endforeach;
                ?>
                <td style="font-weight: bold;">
                    <?= html_escape(!empty($right_bc_values) ? number_format(array_sum($right_bc_values) / count($right_bc_values), 1) . ' dB' : '-') ?>
                </td>
            </tr>
        </table>

        <h4>TELINGA KIRI</h4>
        <table class="data-table">
            <tr>
                <th>Type</th>
                <th>250Hz</th>
                <th>500Hz</th>
                <th>1000Hz</th>
                <th>2000Hz</th>
                <th>3000Hz</th>
                <th>4000Hz</th>
                <th>6000Hz</th>
                <th>Rata-rata</th>
            </tr>
            <tr>
                <td style="font-weight: bold;">AC</td>
                <?php
                $left_ac_values = [];
                foreach ($frequencies as $freq):
                    $value = $test_data['left_ac_'.$freq];
                    if ($value !== null) $left_ac_values[] = $value;
                    echo '<td>' . ($value !== null ? number_format($value, 0) . ' dB' : '-') . '</td>';
                endforeach;
                ?>
                <td style="font-weight: bold;">
                    <?= html_escape(!empty($left_ac_values) ? number_format(array_sum($left_ac_values) / count($left_ac_values), 1) . ' dB' : '-') ?>
                </td>
            </tr>
            <tr>
                <td style="font-weight: bold;">BC</td>
                <?php
                $left_bc_values = [];
                foreach ($frequencies as $freq):
                    $value = $test_data['left_bc_'.$freq];
                    if ($value !== null) $left_bc_values[] = $value;
                    echo '<td>' . ($value !== null ? number_format($value, 0) . ' dB' : '-') . '</td>';
                endforeach;
                ?>
                <td style="font-weight: bold;">
                    <?= html_escape(!empty($left_bc_values) ? number_format(array_sum($left_bc_values) / count($left_bc_values), 1) . ' dB' : '-') ?>
                </td>
            </tr>
        </table>
    </div>

    <div class="impression">
        <h3>Impression:</h3>
        <p><?= html_escape($test_data['impression']) ?></p>
    </div>

    <div style="margin-top: 30px; font-size: 8px; text-align: center; color: #666;">
        <p>Dokumen ini dibuat otomatis oleh Sistem Audiometri • Dicetak pada: <?= html_escape(date('d F Y H:i:s')) ?> WIB</p>
    </div>
</body>
</html>
