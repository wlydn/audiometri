<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="container-fluid p-4">
    <div class="row justify-content-center py-5">
        <div class="col-md-7 col-lg-5 text-center">
            <div class="card shadow-sm border-0 p-4">
                <div class="card-body">
                    <div style="width: 64px; height: 64px; border-radius: 50%; background: #fee2e2; color: #dc2626; display: inline-grid; place-items: center; font-size: 28px; margin-bottom: 20px;">
                        <i class="fas fa-triangle-exclamation"></i>
                    </div>
                    <h2 class="h4 fw-bold text-dark mb-2"><?= html_escape($title ?? 'Terjadi Kesalahan') ?></h2>
                    <p class="text-muted mb-4">Permintaan Anda belum dapat diproses. Silakan coba kembali atau hubungi administrator sistem jika masalah berlanjut.</p>
                    <?php if (ENVIRONMENT !== 'production' && !empty($error_message)): ?>
                        <div class="p-3 bg-light rounded text-start mb-4 border">
                            <pre class="mb-0 text-danger small font-monospace"><?= html_escape($error_message) ?></pre>
                        </div>
                    <?php endif; ?>
                    <a class="btn btn-primary px-4 py-2" href="<?= html_escape(base_url()) ?>">
                        <i class="fas fa-arrow-left me-1"></i> Kembali ke Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
