<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="container-fluid p-3 p-lg-4">
    <div class="row justify-content-center py-5">
        <div class="col-md-8 col-lg-6 col-xl-5">
            <div class="card text-center">
                <div class="card-body p-4 p-md-5">
                    <span class="stat-icon bg-danger-subtle text-danger mx-auto mb-3" aria-hidden="true">
                        <i class="bi bi-exclamation-triangle-fill"></i>
                    </span>
                    <h1 class="h4 fw-bold mb-2"><?= html_escape($title ?? 'Terjadi Kesalahan') ?></h1>
                    <p class="text-body-secondary mb-4">Permintaan Anda belum dapat diproses. Silakan coba kembali atau hubungi administrator sistem jika masalah berlanjut.</p>
                    <?php if (ENVIRONMENT !== 'production' && !empty($error_message)): ?>
                        <div class="alert alert-danger text-start mb-4" role="alert">
                            <pre class="mb-0 small font-monospace text-wrap"><?= html_escape($error_message) ?></pre>
                        </div>
                    <?php endif; ?>
                    <a class="btn btn-primary px-4" href="<?= html_escape(base_url()) ?>">
                        <i class="bi bi-arrow-left me-1" aria-hidden="true"></i> Kembali ke Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
