<?php
/** Run: php tests/routing_regression.php; no database or HTTP requests. */
define('BASEPATH', dirname(__DIR__) . '/system/');
define('APPPATH', dirname(__DIR__) . '/application/');
require dirname(__DIR__) . '/application/config/routes.php';
require dirname(__DIR__) . '/application/config/config.php';
$failed = 0;
function verify_route($name, $condition) {
    global $failed;
    echo ($condition ? 'PASS: ' : 'FAIL: ') . $name . PHP_EOL;
    if (!$condition) { $failed++; }
}
verify_route('Root opens Audiometri', $route['default_controller'] === 'audiometri');
verify_route('Generated URLs omit index.php', $config['index_page'] === '');
$paths = ['dashboard', 'doctor_view', 'review_dokter/(:num)', 'create', 'edit/(:num)',
    'view/(:num)', 'delete/(:num)', 'list', 'list_tests', 'search', 'search_by_no_rm',
    'export_pdf/(:num)', 'export_excel', 'laporan', 'update_chart'];
foreach ($paths as $path) {
    verify_route('Canonical route matches legacy: ' . $path,
        isset($route[$path], $route['audiometri/' . $path]) && $route[$path] === $route['audiometri/' . $path]);
}
$files = glob(dirname(__DIR__) . '/application/views/audiometri/*.php');
$files[] = dirname(__DIR__) . '/application/controllers/Audiometri.php';
foreach ($files as $file) {
    verify_route('No duplicated URL prefix: ' . basename($file),
        !preg_match("/(?:base_url|site_url|redirect)\\('audiometri(?:\/|')/", file_get_contents($file)));
}
// Use the actual framework URL builder with production-like configuration.
require BASEPATH . 'core/Config.php';
$builder = (new ReflectionClass('CI_Config'))->newInstanceWithoutConstructor();
$builder->config = $config;
$builder->config['base_url'] = 'https://wlydn.my.id/audiometri/';
verify_route('Production dashboard URL', $builder->base_url() === 'https://wlydn.my.id/audiometri/');
verify_route('Production form URL', $builder->site_url('create') === 'https://wlydn.my.id/audiometri/create');
verify_route('Production asset URL', $builder->base_url('assets/css/calm.css') === 'https://wlydn.my.id/audiometri/assets/css/calm.css');
// Re-load configuration under the broken hostname-only environment value.
putenv('APP_BASE_URL=wlydn.my.id/audiometri');
require dirname(__DIR__) . '/application/config/config.php';
$builder->config = $config;
verify_route('Hostname-only environment gains HTTPS and trailing slash', $config['base_url'] === 'https://wlydn.my.id/audiometri/');
verify_route('Menu link is absolute, not nested inside /audiometri/',
    $builder->base_url('create') === 'https://wlydn.my.id/audiometri/create');
verify_route('Stylesheet link is absolute and resolves under app root',
    $builder->base_url('assets/css/workspace.css') === 'https://wlydn.my.id/audiometri/assets/css/workspace.css');
putenv('APP_BASE_URL');
exit($failed ? 1 : 0);
