<?php
/** Standalone audit checks: php tests/audit_regression.php (no database writes). */
define('BASEPATH', dirname(__DIR__) . '/system/');
define('APPPATH', dirname(__DIR__) . '/application/');
define('ENVIRONMENT', 'testing');
require BASEPATH . 'core/Common.php';
class CI_Controller { public $input; }
class CI_Model {}
class AuditInput {
    private $values;
    public function __construct(array $values) { $this->values = $values; }
    public function post($key = null) { return $key === null ? $this->values : ($this->values[$key] ?? null); }
}
require APPPATH . 'controllers/Audiometri.php';
require APPPATH . 'models/Audiometri_model.php';
$passed = 0;
$failed = 0;
function check($name, callable $test) {
    global $passed, $failed;
    try {
        if (!$test()) { throw new RuntimeException('Expected invariant is not satisfied'); }
        echo "PASS: {$name}\n";
        $passed++;
    } catch (Throwable $error) {
        echo "FAIL: {$name} -- " . $error->getMessage() . "\n";
        $failed++;
    }
}
$controller = (new ReflectionClass('Audiometri'))->newInstanceWithoutConstructor();
$patient = ['perusahaan' => 'Audit', 'jabatan' => 'Tester', 'nama' => 'Synthetic Patient',
    'umur' => '30', 'jenis_kelamin' => 'Perempuan', 'no_rm' => 'AUDIT-001', 'tanggal_tes' => date('Y-m-d')];
$prepare = new ReflectionMethod('Audiometri', '_prepare_and_validate_form_data');
$prepare->setAccessible(true);
foreach (['0', '-10', '140', ''] as $value) {
    check('Threshold ' . var_export($value, true) . ' preserves numeric/null meaning', function () use ($controller, $patient, $prepare, $value) {
        $controller->input = new AuditInput($patient + ['right_ac_250' => $value]);
        $result = $prepare->invoke($controller);
        $expected = $value === '' ? null : (float) $value;
        return $result['status'] && $result['data']['right_ac_250'] === $expected;
    });
}
check('Out-of-range threshold is rejected', function () use ($controller, $patient, $prepare) {
    $controller->input = new AuditInput($patient + ['right_ac_250' => '141']);
    return $prepare->invoke($controller)['status'] === false;
});
check('Array input is rejected without TypeError', function () use ($controller, $patient) {
    $patient['nama'] = ['unexpected'];
    $controller->input = new AuditInput($patient);
    $method = new ReflectionMethod('Audiometri', '_validate_required_fields');
    $method->setAccessible(true);
    return $method->invoke($controller)['status'] === false;
});
check('Fractional age is rejected', function () use ($controller, $patient) {
    $patient['umur'] = '30.5';
    $controller->input = new AuditInput($patient);
    $method = new ReflectionMethod('Audiometri', '_validate_required_fields');
    $method->setAccessible(true);
    return $method->invoke($controller)['status'] === false;
});
check('All literal view references exist', function () {
    $source = file_get_contents(APPPATH . 'controllers/Audiometri.php');
    preg_match_all("/->view\('([^']+)'/", $source, $matches);
    $missing = [];
    foreach (array_unique($matches[1]) as $view) {
        if (!is_file(APPPATH . 'views/' . $view . '.php')) { $missing[] = $view; }
    }
    if ($missing) { throw new RuntimeException(implode(', ', $missing)); }
    return true;
});
check('Right BC 250 does not display left-ear value', function () {
    $source = file_get_contents(APPPATH . 'views/audiometri/view.php');
    return strpos($source, "\$test_data['right_bc_250'] !== null ? \$test_data['left_bc_250']") === false;
});
check('HTML escaping blocks tags and attribute delimiters', function () {
    $escaped = html_escape('</script><img src=x onerror=alert(1)>"\'&');
    return strpos($escaped, '<') === false && strpos($escaped, '"') === false && strpos($escaped, "'") === false;
});
check('Embedded JSON blocks script breakout and preserves text', function () {
    $payload = '</script><img src=x onerror=alert(1)>"\'&';
    $json = json_encode(['nama' => $payload], JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT);
    return strpos($json, '</script>') === false && json_decode($json, true)['nama'] === $payload;
});
check('Automatic analysis retains zero and ignores absent thresholds', function () {
    $model = (new ReflectionClass('Audiometri_model'))->newInstanceWithoutConstructor();
    $result = $model->generate_impression(['right_ac_250' => 0, 'right_ac_500' => null]);
    return strpos($result, '0.0 dB') !== false && strpos($result, 'Telinga kiri') === false;
});
echo "\nAudit checks: {$passed} passed, {$failed} failed.\n";
exit($failed ? 1 : 0);
