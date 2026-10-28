<?php
/**
 * Review checks: php tests/review_regression.php
 * Tanpa database sungguhan: DB palsu merekam query supaya terbukti bahwa review hanya
 * menyentuh kolom impression/updated_at dan persetujuan massal menolak kasus yang tidak aman.
 */
define('BASEPATH', dirname(__DIR__) . '/system/');
define('APPPATH', dirname(__DIR__) . '/application/');
define('ENVIRONMENT', 'testing');
require BASEPATH . 'core/Common.php';
class CI_Model {}
require APPPATH . 'models/Audiometri_model.php';

class FakeDb {
    public $rows = [];
    public $updates = [];
    public $conds = [];
    public $affected = 0;
    public function where($k, $v = null, $escape = null) { $this->conds[] = [$k, $v]; return $this; }
    public function or_where($k, $v = null) { $this->conds[] = ['OR ' . $k, $v]; return $this; }
    public function group_start() { return $this; }
    public function group_end() { return $this; }
    public function get($table) { return new FakeResult($this); }
    public function update($table, $data) {
        $id = null;
        $onlyEmpty = false;
        foreach ($this->conds as $c) {
            if ($c[0] === 'id') { $id = (int) $c[1]; }
            if ($c[0] === 'impression IS NULL') { $onlyEmpty = true; }
        }
        $this->updates[] = ['id' => $id, 'data' => $data, 'only_if_empty' => $onlyEmpty];
        $this->affected = 0;
        if (isset($this->rows[$id])) {
            $cur = trim((string) ($this->rows[$id]['impression'] ?? ''));
            if (!$onlyEmpty || $cur === '') {
                $this->rows[$id] = array_merge($this->rows[$id], $data);
                $this->affected = 1;
            }
        }
        $this->conds = [];
        return true;
    }
    public function affected_rows() { return $this->affected; }
    public function error() { return ['message' => '']; }
}
class FakeResult {
    private $db;
    public function __construct($db) { $this->db = $db; }
    public function row_array() {
        $id = null;
        foreach ($this->db->conds as $c) {
            if ($c[0] === 'id') { $id = (int) $c[1]; }
        }
        $this->db->conds = [];
        return $this->db->rows[$id] ?? null;
    }
}

$passed = 0;
$failed = 0;
function check($name, callable $test) {
    global $passed, $failed;
    try {
        if (!$test()) { throw new RuntimeException('Expected invariant is not satisfied'); }
        echo "PASS: {$name}\n";
        $passed++;
    } catch (Throwable $e) {
        echo "FAIL: {$name} -- " . $e->getMessage() . "\n";
        $failed++;
    }
}

function row($id, $ac, $impression = null) {
    $r = ['id' => $id, 'nama' => 'Pasien ' . $id, 'impression' => $impression, 'tanggal_tes' => '2026-10-27'];
    foreach (['right', 'left'] as $ear) {
        foreach (['250', '500', '1000', '2000', '3000', '4000', '6000'] as $i => $f) {
            $r[$ear . '_ac_' . $f] = $ac[$ear][$i] ?? null;
            $r[$ear . '_bc_' . $f] = null;
        }
    }
    return $r;
}
function model(FakeDb $db) {
    $m = (new ReflectionClass('Audiometri_model'))->newInstanceWithoutConstructor();
    $m->db = $db;
    return $m;
}

$normal = ['right' => [30, 25, 20, 15, 10, 10, 10], 'left' => [30, 25, 20, 15, 10, 10, 10]];
$mild   = ['right' => [30, 30, 35, 40, 45, 40, 40], 'left' => [30, 25, 20, 15, 10, 10, 10]];
$dip    = ['right' => [30, 25, 20, 15, 10, 10, 10], 'left' => [20, 20, 20, 20, 20, 20, 70]];

check('Jelas normal: audiogram normal standar', function () use ($normal) {
    return model(new FakeDb())->is_clearly_normal(row(1, $normal));
});
check('Bukan jelas normal: rata-rata AC >= 25', function () use ($mild) {
    return !model(new FakeDb())->is_clearly_normal(row(1, $mild));
});
check('Bukan jelas normal: ada frekuensi > 30 dB walau rata-rata rendah', function () use ($dip) {
    return !model(new FakeDb())->is_clearly_normal(row(1, $dip));
});
check('Bukan jelas normal: satu telinga tanpa data AC', function () {
    return !model(new FakeDb())->is_clearly_normal(row(1, ['right' => [30, 25, 20, 15, 10, 10, 10], 'left' => []]));
});
check('Bukan jelas normal: terlalu sedikit titik terukur (< 4)', function () {
    return !model(new FakeDb())->is_clearly_normal(row(1, ['right' => [10, 10, 10], 'left' => [10, 10, 10]]));
});

check('update_impression_only hanya menulis impression dan updated_at', function () use ($normal) {
    $db = new FakeDb();
    $db->rows[5] = row(5, $normal);
    $res = model($db)->update_impression_only(5, 'Tuli saraf derajat ringan bilateral.');
    $keys = array_keys($db->updates[0]['data']);
    sort($keys);
    return $res['status'] && $keys === ['impression', 'updated_at'] && $db->rows[5]['nama'] === 'Pasien 5';
});
check('update_impression_only menolak impression kosong', function () use ($normal) {
    $db = new FakeDb();
    $db->rows[5] = row(5, $normal);
    $res = model($db)->update_impression_only(5, '   ');
    return !$res['status'] && count($db->updates) === 0;
});
check('update_impression_only menolak ID tidak valid dan data tak ada', function () {
    $db = new FakeDb();
    $m = model($db);
    return !$m->update_impression_only(0, 'x')['status']
        && !$m->update_impression_only(99, 'x')['status']
        && count($db->updates) === 0;
});
check('only_if_empty tidak menimpa impression yang sudah ada', function () use ($normal) {
    $db = new FakeDb();
    $db->rows[7] = row(7, $normal, 'Isi dokter lain.');
    $res = model($db)->update_impression_only(7, 'Baru.', true);
    return $res['status'] && $res['changed'] === false && $db->rows[7]['impression'] === 'Isi dokter lain.';
});

check('Persetujuan massal: hanya yang jelas normal dan masih kosong yang disetujui', function () use ($normal, $mild, $dip) {
    $db = new FakeDb();
    $db->rows[1] = row(1, $normal);
    $db->rows[2] = row(2, $mild);
    $db->rows[3] = row(3, $normal, 'Sudah direview dokter.');
    $db->rows[4] = row(4, $dip);
    $res = model($db)->bulk_approve_normal([1, 2, 3, 4, 99]);
    return $res['approved'] === [1]
        && $db->rows[1]['impression'] === Audiometri_model::NORMAL_IMPRESSION
        && $db->rows[2]['impression'] === null
        && $db->rows[3]['impression'] === 'Sudah direview dokter.'
        && $db->rows[4]['impression'] === null
        && isset($res['skipped'][2], $res['skipped'][3], $res['skipped'][4], $res['skipped'][99]);
});
check('Persetujuan massal: ID ganda, non-positif, dan melebihi batas dibersihkan', function () use ($normal) {
    $db = new FakeDb();
    for ($i = 1; $i <= 250; $i++) {
        $db->rows[$i] = row($i, $normal);
    }
    $res = model($db)->bulk_approve_normal(array_merge([1, 1, 1, 0, -5], range(1, 250)));
    return count($res['approved']) === 200 && count(array_unique($res['approved'])) === 200;
});
check('Persetujuan massal: teks selalu kalimat standar dari server', function () use ($normal) {
    $db = new FakeDb();
    $db->rows[1] = row(1, $normal);
    model($db)->bulk_approve_normal([1]);
    return $db->updates[0]['data']['impression'] === 'Ambang dengar kedua telinga dalam batas normal.'
        && $db->updates[0]['only_if_empty'] === true;
});

echo "\n{$passed} passed, {$failed} failed\n";
exit($failed ? 1 : 0);
