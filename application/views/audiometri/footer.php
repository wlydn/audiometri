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
                    <button type="submit" form="reportForm" class="btn btn-success">
                        <i class="bi bi-download me-1" aria-hidden="true"></i> Download Excel
                    </button>
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
