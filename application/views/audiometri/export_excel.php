<?php
header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=Data_Tes_Audiometri.xls");
?>

<table border="1">
    <thead>
        <tr>
            <th colspan="23" style="text-align: center;">DATA TES AUDIOMETRI</th>
        </tr>
        <?php
        $period_parts = [];
        if (!empty($filters['tanggal_dari']) || !empty($filters['tanggal_sampai'])) {
            $period_parts[] = 'Periode: '
                . (!empty($filters['tanggal_dari']) ? date('d/m/Y', strtotime($filters['tanggal_dari'])) : '...')
                . ' s/d '
                . (!empty($filters['tanggal_sampai']) ? date('d/m/Y', strtotime($filters['tanggal_sampai'])) : '...');
        }
        if (!empty($filters['perusahaan'])) {
            $period_parts[] = 'Perusahaan: ' . $filters['perusahaan'];
        }
        if (!empty($period_parts)): ?>
        <tr>
            <th colspan="23" style="text-align: center;"><?= html_escape(implode(' | ', $period_parts)) ?></th>
        </tr>
        <?php endif; ?>
        <tr>
            <th>No</th>
            <th>Tanggal Tes</th>
            <th>No RM</th>
            <th>Nama</th>
            <th>Umur</th>
            <th>Jenis Kelamin</th>
            <th>Perusahaan</th>
            <th>Jabatan</th>
            <!-- Telinga Kanan AC -->
            <th>AC Right 250</th>
            <th>AC Right 500</th>
            <th>AC Right 1000</th>
            <th>AC Right 2000</th>
            <th>AC Right 3000</th>
            <th>AC Right 4000</th>
            <th>AC Right 6000</th>
            <!-- Telinga Kiri AC -->
            <th>AC Left 250</th>
            <th>AC Left 500</th>
            <th>AC Left 1000</th>
            <th>AC Left 2000</th>
            <th>AC Left 3000</th>
            <th>AC Left 4000</th>
            <th>AC Left 6000</th>
            <th>Impression</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $no = 1;
        foreach($audiometri_data as $data): ?>
        <tr>
            <td><?= html_escape($no++) ?></td>
            <td><?= html_escape(date('d/m/Y', strtotime($data['tanggal_tes']))) ?></td>
            <td><?= html_escape($data['no_rm']) ?></td>
            <td><?= html_escape($data['nama']) ?></td>
            <td><?= html_escape($data['umur']) ?></td>
            <td><?= html_escape($data['jenis_kelamin']) ?></td>
            <td><?= html_escape($data['perusahaan']) ?></td>
            <td><?= html_escape($data['jabatan']) ?></td>
            <!-- Telinga Kanan AC -->
            <td><?= html_escape($data['right_ac_250']) ?></td>
            <td><?= html_escape($data['right_ac_500']) ?></td>
            <td><?= html_escape($data['right_ac_1000']) ?></td>
            <td><?= html_escape($data['right_ac_2000']) ?></td>
            <td><?= html_escape($data['right_ac_3000']) ?></td>
            <td><?= html_escape($data['right_ac_4000']) ?></td>
            <td><?= html_escape($data['right_ac_6000']) ?></td>
            <!-- Telinga Kiri AC -->
            <td><?= html_escape($data['left_ac_250']) ?></td>
            <td><?= html_escape($data['left_ac_500']) ?></td>
            <td><?= html_escape($data['left_ac_1000']) ?></td>
            <td><?= html_escape($data['left_ac_2000']) ?></td>
            <td><?= html_escape($data['left_ac_3000']) ?></td>
            <td><?= html_escape($data['left_ac_4000']) ?></td>
            <td><?= html_escape($data['left_ac_6000']) ?></td>
            <td><?= html_escape($data['impression']) ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
    <tfoot>
        <tr>
            <td colspan="23">
                <strong>Diekspor pada: </strong><?= html_escape(date('d/m/Y H:i:s')) ?>
            </td>
        </tr>
    </tfoot>
</table>
