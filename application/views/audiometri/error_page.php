<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<section class="card" role="alert">
    <div class="card-body">
        <h1 class="h4"><?= html_escape($title ?? 'Terjadi kesalahan') ?></h1>
        <p>Permintaan belum dapat diproses. Silakan coba kembali atau hubungi administrator.</p>
        <?php if (ENVIRONMENT !== 'production' && !empty($error_message)): ?>
            <pre><?= html_escape($error_message) ?></pre>
        <?php endif; ?>
        <a class="btn btn-primary" href="<?= html_escape(base_url()) ?>">Kembali ke dashboard</a>
    </div>
</section>
