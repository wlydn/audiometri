<?php
// File: application/views/audiometri/footer.php
?>
        </main> <!-- End Content Wrapper -->

        <!-- Footer -->
        <footer class="app-footer">
            <div class="d-flex flex-column flex-md-row justify-content-between gap-1">
                <span>Audiometri &middot; Manajemen pemeriksaan pendengaran</span>
                <span>&copy; <?= html_escape(date('Y')) ?> ArChiee</span>
            </div>
        </footer>
    </div> <!-- End .app-main -->

    <!-- Report Modal -->
    <div class="modal fade" id="reportModal" tabindex="-1" aria-labelledby="reportModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="reportModalLabel">Filter Laporan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    <form id="reportForm" action="<?= html_escape(base_url('export_excel')) ?>" method="get">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="tanggal_dari" class="form-label">Tanggal Dari</label>
                                    <input type="date" class="form-control" id="tanggal_dari" name="tanggal_dari">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="tanggal_sampai" class="form-label">Tanggal Sampai</label>
                                    <input type="date" class="form-control" id="tanggal_sampai" name="tanggal_sampai">
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="perusahaan_filter" class="form-label">Perusahaan (Opsional)</label>
                            <input type="text" class="form-control" id="perusahaan_filter" name="perusahaan"
                                   placeholder="Masukkan nama perusahaan">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" form="reportForm" formaction="<?= html_escape(base_url('laporan')) ?>" class="btn btn-primary">
                        <i class="bi bi-bar-chart-line me-1" aria-hidden="true"></i> Lihat Laporan
                    </button>
                    <button type="submit" form="reportForm" class="btn btn-success">
                        <i class="bi bi-download me-1" aria-hidden="true"></i> Download Excel
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Pratinjau cetak: tampil di halaman yang sama, tanpa tab/jendela baru -->
    <div class="modal fade" id="printPreviewModal" tabindex="-1" aria-labelledby="printPreviewModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered modal-fullscreen-md-down">
            <div class="modal-content print-preview-content">
                <div class="print-preview-toolbar">
                    <div class="print-preview-title">
                        <i class="bi bi-printer" aria-hidden="true"></i><span id="printPreviewModalLabel">Pratinjau Cetak</span>
                    </div>
                    <div class="print-preview-zoom" role="group" aria-label="Zoom tampilan">
                        <button type="button" class="pp-btn" id="printPreviewZoomOut" aria-label="Perkecil" title="Perkecil" disabled>
                            <i class="bi bi-dash-lg" aria-hidden="true"></i>
                        </button>
                        <button type="button" class="pp-btn pp-zoom-value" id="printPreviewZoomValue" title="Sesuaikan dengan lebar" aria-label="Sesuaikan dengan lebar" disabled>100%</button>
                        <button type="button" class="pp-btn" id="printPreviewZoomIn" aria-label="Perbesar" title="Perbesar" disabled>
                            <i class="bi bi-plus-lg" aria-hidden="true"></i>
                        </button>
                    </div>
                    <div class="print-preview-actions">
                        <a id="printPreviewPdf" href="#" class="pp-btn" target="_blank" rel="noopener" title="Unduh PDF (versi sebelumnya)" aria-label="Unduh PDF (versi sebelumnya)">
                            <i class="bi bi-download" aria-hidden="true"></i><span class="d-none d-lg-inline">PDF versi sebelumnya</span>
                        </a>
                        <button type="button" class="pp-btn pp-btn-primary" id="printPreviewPrint" disabled>
                            <i class="bi bi-printer" aria-hidden="true"></i><span>Cetak</span>
                        </button>
                        <button type="button" class="pp-btn" data-bs-dismiss="modal" aria-label="Tutup" title="Tutup">
                            <i class="bi bi-x-lg" aria-hidden="true"></i>
                        </button>
                    </div>
                </div>
                <div class="print-preview-body">
                    <div id="printPreviewLoading" class="print-preview-loading">
                        <div class="spinner-border" role="status"><span class="visually-hidden">Memuat...</span></div>
                    </div>
                    <div id="printPreviewError" class="alert alert-danger print-preview-error d-none" role="alert"></div>
                    <iframe id="printPreviewFrame" class="print-preview-frame d-none" title="Pratinjau hasil audiometri" sandbox="allow-same-origin allow-modals"></iframe>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

    <!-- Custom JS -->
    <script>
        function showReportModal() {
            bootstrap.Modal.getOrCreateInstance(document.getElementById('reportModal')).show();
        }

        // Pratinjau cetak di modal. Dokumen diambil lewat fetch lalu dimasukkan ke iframe srcdoc.
        (function() {
            var PREVIEW_URL = <?= json_encode(base_url('print_preview/'), JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>;
            var PDF_URL = <?= json_encode(base_url('export_pdf/'), JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>;
            var token = 0;

            var modalEl = document.getElementById('printPreviewModal');
            if (!modalEl) {
                return;
            }
            var frame = document.getElementById('printPreviewFrame');
            var loading = document.getElementById('printPreviewLoading');
            var errorBox = document.getElementById('printPreviewError');
            var printBtn = document.getElementById('printPreviewPrint');
            var pdfLink = document.getElementById('printPreviewPdf');
            var zoomIn = document.getElementById('printPreviewZoomIn');
            var zoomOut = document.getElementById('printPreviewZoomOut');
            var zoomValue = document.getElementById('printPreviewZoomValue');
            var ZOOM_STEP = 0.1;
            var ZOOM_MIN = 0.4;
            var ZOOM_MAX = 2;
            var zoom = 1;

            // Skala agar halaman A4 (210 mm = sekitar 794 px) pas dengan lebar jendela, maksimal 100%
            var PAGE_WIDTH = 794;
            function fitScale() {
                return Math.max(ZOOM_MIN, Math.min(1, (frame.clientWidth - 32) / PAGE_WIDTH));
            }

            function applyZoom(value) {
                zoom = Math.max(ZOOM_MIN, Math.min(ZOOM_MAX, value));
                var doc = frame.contentDocument;
                if (doc && doc.documentElement) {
                    doc.documentElement.style.zoom = zoom;
                }
                zoomValue.textContent = Math.round(zoom * 100) + '%';
                zoomOut.disabled = zoom <= ZOOM_MIN + 0.001;
                zoomIn.disabled = zoom >= ZOOM_MAX - 0.001;
            }

            function setControls(enabled) {
                printBtn.disabled = !enabled;
                zoomValue.disabled = !enabled;
                zoomOut.disabled = !enabled;
                zoomIn.disabled = !enabled;
            }

            function reset() {
                token++;
                frame.classList.add('d-none');
                frame.removeAttribute('srcdoc');
                errorBox.classList.add('d-none');
                errorBox.textContent = '';
                loading.classList.remove('d-none');
                setControls(false);
                zoomValue.textContent = '100%';
            }

            function fail(message) {
                loading.classList.add('d-none');
                errorBox.textContent = message;
                errorBox.classList.remove('d-none');
            }

            window.openPrintPreview = function(id) {
                id = parseInt(id, 10);
                if (!id || id < 1) {
                    return;
                }
                reset();
                var mine = token;
                pdfLink.href = PDF_URL + encodeURIComponent(id);
                bootstrap.Modal.getOrCreateInstance(modalEl).show();

                fetch(PREVIEW_URL + encodeURIComponent(id), {
                    credentials: 'same-origin',
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                })
                .then(function(response) {
                    if (!response.ok) {
                        throw new Error('HTTP ' + response.status);
                    }
                    return response.text();
                })
                .then(function(html) {
                    if (mine !== token) {
                        return; // modal sudah ditutup / diganti pasien lain
                    }
                    frame.addEventListener('load', function onLoad() {
                        frame.removeEventListener('load', onLoad);
                        if (mine !== token) {
                            return;
                        }
                        loading.classList.add('d-none');
                        frame.classList.remove('d-none');
                        setControls(true);
                        applyZoom(fitScale());
                    });
                    frame.srcdoc = html;
                })
                .catch(function() {
                    if (mine === token) {
                        fail('Pratinjau tidak dapat dimuat. Gunakan "Unduh PDF (versi sebelumnya)".');
                    }
                });
            };

            zoomIn.addEventListener('click', function() { applyZoom(zoom + ZOOM_STEP); });
            zoomOut.addEventListener('click', function() { applyZoom(zoom - ZOOM_STEP); });
            zoomValue.addEventListener('click', function() { applyZoom(fitScale()); });

            printBtn.addEventListener('click', function() {
                if (frame.contentWindow) {
                    frame.contentWindow.focus();
                    frame.contentWindow.print();
                }
            });

            modalEl.addEventListener('hidden.bs.modal', reset);
        })();

        // Live clock in topbar, fixed to Asia/Jakarta so it matches server time
        (function() {
            var el = document.getElementById('topbar-clock');
            if (!el) {
                return;
            }
            var fmt;
            try {
                fmt = new Intl.DateTimeFormat('id-ID', {
                    timeZone: 'Asia/Jakarta',
                    hour: '2-digit',
                    minute: '2-digit',
                    second: '2-digit',
                    hour12: false
                });
            } catch (e) {
                return;
            }
            function tick() {
                el.textContent = fmt.format(new Date()).replace(/\./g, ':');
            }
            tick();
            setInterval(tick, 1000);
        })();

        // Auto-hide dismissible (flash) alerts after 5 seconds
        setTimeout(function() {
            document.querySelectorAll('.alert-dismissible').forEach(function(el) {
                bootstrap.Alert.getOrCreateInstance(el).close();
            });
        }, 5000);

        // Confirm delete actions with SweetAlert
        function confirmDelete(url, message = 'Yakin ingin menghapus data ini?') {
            Swal.fire({
                title: 'Konfirmasi Hapus',
                text: message,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = url;
                }
            });
        }

        // AJAX delete function with SweetAlert
        function deleteRecord(id, redirectUrl = null) {
            Swal.fire({
                title: 'Konfirmasi Hapus',
                text: 'Yakin ingin menghapus data ini? Data yang dihapus tidak dapat dikembalikan!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    // Show loading
                    Swal.fire({
                        title: 'Menghapus...',
                        text: 'Mohon tunggu sebentar',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        showConfirmButton: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

					fetch(<?= json_encode(base_url('delete/'), JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?> + id, {
						method: 'POST',
						body: withCsrf(),
						headers: {
							'X-Requested-With': 'XMLHttpRequest'
						}
					})
                    .then(response => response.json())
                    .then(data => {
                        if (data.status) {
                            Swal.fire({
                                title: 'Berhasil!',
                                text: data.message || 'Data berhasil dihapus',
                                icon: 'success',
                                timer: 3000,
                                timerProgressBar: true,
                                showConfirmButton: false
                            }).then(() => {
                                if (redirectUrl) {
                                    window.location.href = redirectUrl;
                                } else {
                                    location.reload();
                                }
                            });
                        } else {
                            Swal.fire({
                                title: 'Gagal!',
                                text: data.message || 'Gagal menghapus data',
                                icon: 'error',
                                confirmButtonColor: '#3085d6'
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire({
                            title: 'Error!',
                            text: 'Terjadi kesalahan saat menghapus data',
                            icon: 'error',
                            confirmButtonColor: '#3085d6'
                        });
                    });
                }
            });
        }
    </script>
</body>
</html>
