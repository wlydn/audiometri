<?php
defined('BASEPATH') or exit('No direct script access allowed');

// use DateTime;

/**
 * Controller untuk aplikasi audiometri - FINAL FIX VERSION
 * 
 * @author ArChiee
 * @version 2.0.3 - COMPLETELY REMOVED permit_empty dependency
 */
/**
 * @property CI_Input $input
 * @property CI_Session $session
 * @property CI_Form_validation $form_validation
 * @property Audiometri_model $Audiometri_model
 * @property CI_Security $security
 * @property CI_Pagination $pagination
 * @property Pdf_generator $pdf_generator
 * @property CI_Output $output
 */
class Audiometri extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();

		$this->_send_security_headers();

		// Canonicalize old controller-prefixed bookmarks without redirecting POST bodies.
		if (in_array($this->input->method(TRUE), ['GET', 'HEAD'], true)
			&& $this->uri->segment(1) === 'audiometri') {
			$this->load->helper('url');
			$path = implode('/', array_slice($this->uri->segment_array(), 1));
			$query = $this->input->server('QUERY_STRING');
			redirect(base_url($path) . ($query ? '?' . $query : ''), 'location', 301);
			return;
		}

		// Enable error reporting untuk debugging
		if (ENVIRONMENT !== 'production') {
			error_reporting(E_ALL);
			ini_set('display_errors', 1);
		}

		// Load dependencies dengan error handling
		try {
			$this->load->model('Audiometri_model');
			$this->load->library(['session', 'form_validation']);
			$this->load->helper(['url', 'form', 'html', 'security']);

			// Set custom error delimiters
			$this->form_validation->set_error_delimiters('<small class="text-danger">', '</small>');
		} catch (Exception $e) {
			log_message('error', 'Failed to load dependencies: ' . $e->getMessage());
			show_error('System initialization failed. Please contact administrator.');
		}

		// Set timezone
		date_default_timezone_set('Asia/Jakarta');
	}

	/**
	 * Halaman dashboard utama
	 */
	public function index()
	{
		try {
			$data['title'] = 'Dashboard';

			// Load stats dengan error handling
			$data['overview'] = $this->Audiometri_model->get_dashboard_overview();
			$data['recent_tests'] = $this->Audiometri_model->get_tests(6, 0);

			$this->load->view('audiometri/header', $data);
			$this->load->view('audiometri/dashboard', $data);
			$this->load->view('audiometri/footer');
		} catch (Exception $e) {
			log_message('error', 'Dashboard error: ' . $e->getMessage());
			$this->_show_error_page('Dashboard tidak dapat dimuat', $e->getMessage());
		}
	}

	/**
	 * Halaman input/form tes baru
	 */
	public function create()
	{
		try {
			$data['title'] = 'Tes Baru';
			$data['action'] = 'create';
			$data['test_data'] = null;

			// Handle form submission
			if ($this->input->post()) {
				$this->_handle_form_submission();
				return;
			}

			$this->load->view('audiometri/header', $data);
			$this->load->view('audiometri/form', $data);
			$this->load->view('audiometri/footer');
		} catch (Exception $e) {
			log_message('error', 'Create form error: ' . $e->getMessage());
			$this->_show_error_page('Form tidak dapat dimuat', $e->getMessage());
		}
	}

	/**
	 * Halaman edit tes yang sudah ada
	 */
	public function edit($id = null)
	{
		try {
			if (!$id || !is_numeric($id)) {
				$this->session->set_flashdata('error', 'ID tidak valid');
				redirect('list_tests');
				return;
			}

			$test_data = $this->Audiometri_model->get_test($id);
			if (!$test_data) {
				$this->session->set_flashdata('error', 'Data tes tidak ditemukan');
				redirect('list_tests');
				return;
			}

			$data['title'] = 'Edit Tes';
			$data['action'] = 'edit';
			$data['test_data'] = $test_data;

			// Handle form submission
			if ($this->input->post()) {
				$this->_handle_form_submission($id);
				return;
			}

			$this->load->view('audiometri/header', $data);
			$this->load->view('audiometri/form', $data);
			$this->load->view('audiometri/footer');
		} catch (Exception $e) {
			log_message('error', 'Edit form error: ' . $e->getMessage());
			$this->_show_error_page('Form edit tidak dapat dimuat', $e->getMessage());
		}
	}

	/**
	 * Halaman daftar semua tes
	 */
	public function list_tests()
	{
		try {
			$page = $this->_clean_page($this->input->get('page'));
			$limit = 10;
			$offset = ($page - 1) * $limit;

			// Filter parameters: hanya string, dipangkas, dan dibatasi panjangnya
			$filters = [
				'nama' => $this->_clean_text($this->input->get('nama'), 100),
				'perusahaan' => $this->_clean_text($this->input->get('perusahaan'), 100),
				'tanggal_dari' => $this->_clean_report_date($this->input->get('tanggal_dari')),
				'tanggal_sampai' => $this->_clean_report_date($this->input->get('tanggal_sampai'))
			];

			// Remove empty filters
			$filters = array_filter($filters, function ($value) {
				return $value !== '';
			});

			$data['title'] = 'Daftar Pasien';
			$data['tests'] = $this->Audiometri_model->get_tests($limit, $offset, $filters);
			$data['total_tests'] = $this->Audiometri_model->count_tests($filters);
			$data['filters'] = $filters;
			$data['current_page'] = $page;

			// Setup custom pagination
			$data['pagination'] = $this->_generate_custom_pagination($data['total_tests'], $limit, $page, $filters);

			$this->load->view('audiometri/header', $data);
			$this->load->view('audiometri/list', $data);
			$this->load->view('audiometri/footer');
		} catch (Exception $e) {
			log_message('error', 'List tests error: ' . $e->getMessage());
			$this->_show_error_page('Daftar tes tidak dapat dimuat', $e->getMessage());
		}
	}

	/**
	 * Detail tes audiometri
	 */
	public function view($id = null)
	{
		try {
			if (!$id || !is_numeric($id)) {
				$this->session->set_flashdata('error', 'ID tidak valid');
				redirect('list_tests');
				return;
			}

			$test_data = $this->Audiometri_model->get_test($id);
			if (!$test_data) {
				$this->session->set_flashdata('error', 'Data tes tidak ditemukan');
				redirect('list_tests');
				return;
			}

			$data['title'] = 'Detail Tes';
			$data['test_data'] = $test_data;

			$this->load->view('audiometri/header', $data);
			$this->load->view('audiometri/view', $data);
			$this->load->view('audiometri/footer');
		} catch (Exception $e) {
			log_message('error', 'View test error: ' . $e->getMessage());
			$this->_show_error_page('Detail tes tidak dapat dimuat', $e->getMessage());
		}
	}

	/**
	 * Hapus tes audiometri
	 */
	public function delete($id = null)
	{
		if ($this->input->method(TRUE) !== 'POST') {
			$this->output->set_header('Allow: POST');
			show_error('Method not allowed', 405);
			return;
		}

		try {
			if (!$id || !is_numeric($id)) {
				$response = ['status' => false, 'message' => 'ID tidak valid'];
				$this->_json_response($response);
				return;
			}

			$result = $this->Audiometri_model->delete_test($id);

			if ($this->input->is_ajax_request()) {
				$this->_json_response($result);
			} else {
				if ($result['status']) {
					$this->session->set_flashdata('success', $result['message']);
				} else {
					$this->session->set_flashdata('error', $result['message']);
				}
				redirect('list_tests');
			}
		} catch (Exception $e) {
			log_message('error', 'Delete test error: ' . $e->getMessage());
			$response = ['status' => false, 'message' => 'Terjadi kesalahan sistem'];
			$this->_json_response($response);
		}
	}

	/**
	 * AJAX endpoint untuk update grafik real-time
	 */
	public function update_chart()
	{
		try {
			if (!$this->input->is_ajax_request()) {
				show_404();
				return;
			}

			$post_data = $this->input->post();

			// Validasi data POST
			if (empty($post_data)) {
				$this->_json_response([
					'status' => false,
					'message' => 'Data tidak valid'
				]);
				return;
			}

			// Generate impression menggunakan model
			$impression = $this->Audiometri_model->generate_impression($post_data);

			$this->_json_response([
				'status' => true,
				'impression' => $impression
			]);
		} catch (Exception $e) {
			log_message('error', 'Update chart error: ' . $e->getMessage());
			$this->_json_response([
				'status' => false,
				'message' => 'Gagal memperbarui grafik'
			]);
		}
	}

	/**
	 * Search by No RM (AJAX)
	 */
	public function search_by_no_rm()
	{
		try {
			if (!$this->input->is_ajax_request()) {
				show_404();
				return;
			}

			$no_rm = $this->_clean_text($this->input->post('no_rm'), 50);
			if ($no_rm === '') {
				$this->_json_response([
					'status' => false,
					'message' => 'No RM tidak boleh kosong'
				]);
				return;
			}

			$results = $this->Audiometri_model->search_by_no_rm($no_rm);

			$this->_json_response([
				'status' => true,
				'data' => $results,
				'count' => count($results)
			]);
		} catch (Exception $e) {
			log_message('error', 'Search by no RM error: ' . $e->getMessage());
			$this->_json_response([
				'status' => false,
				'message' => 'Pencarian gagal'
			]);
		}
	}

	/**
	 * Handle form submission (create/edit) - NO permit_empty DEPENDENCY
	 */
	private function _handle_form_submission($id = null)
	{
		try {
			// STEP 1: Validate required fields first
			$validation_result = $this->_validate_required_fields();

			if (!$validation_result['status']) {
				if ($this->input->is_ajax_request()) {
					$this->_json_response($validation_result);
					return;
				}

				$this->session->set_flashdata('error', $validation_result['message']);
				if ($id) {
					redirect('edit/' . $id);
				} else {
					redirect('create');
				}
				return;
			}

			// STEP 2: Validate and prepare all form data
			$form_data_result = $this->_prepare_and_validate_form_data($id); // Pass ID to know if it's edit

			if (!$form_data_result['status']) {
				if ($this->input->is_ajax_request()) {
					$this->_json_response($form_data_result);
					return;
				}

				$this->session->set_flashdata('error', $form_data_result['message']);
				if ($id) {
					redirect('edit/' . $id);
				} else {
					redirect('create');
				}
				return;
			}

			$data = $form_data_result['data'];

			// STEP 3: Save or update data
			if ($id) {
				$result = $this->Audiometri_model->update_test($id, $data);
				$redirect_success = 'view/' . $id;
				$redirect_error = 'edit/' . $id;
			} else {
				$result = $this->Audiometri_model->save_test($data);
				$redirect_success = isset($result['id']) ? 'view/' . $result['id'] : 'list_tests';
				$redirect_error = 'create';
			}

			// STEP 4: Handle response
			if ($this->input->is_ajax_request()) {
				$this->_json_response($result);
			} else {
				if ($result['status']) {
					$this->session->set_flashdata('success', $result['message']);
					redirect($redirect_success);
				} else {
					$this->session->set_flashdata('error', $result['message']);
					redirect($redirect_error);
				}
			}
		} catch (Exception $e) {
			log_message('error', 'Form submission error: ' . $e->getMessage());

			$error_message = 'Terjadi kesalahan saat menyimpan data';
			if (ENVIRONMENT !== 'production') {
				$error_message .= ': ' . $e->getMessage();
			}

			if ($this->input->is_ajax_request()) {
				$this->_json_response([
					'status' => false,
					'message' => $error_message
				]);
			} else {
				$this->session->set_flashdata('error', $error_message);
				if ($id) {
					redirect('edit/' . $id);
				} else {
					redirect('create');
				}
			}
		}
	}

	/**
	 * Validate required fields WITHOUT CodeIgniter form validation
	 */
	private function _validate_required_fields()
	{
		$errors = [];

		// Required field validation
		$required_fields = [
			'perusahaan' => 'Perusahaan',
			'jabatan' => 'Jabatan',
			'nama' => 'Nama',
			'umur' => 'Umur',
			'jenis_kelamin' => 'Jenis Kelamin',
			'no_rm' => 'No RM',
			'tanggal_tes' => 'Tanggal Tes'
		];

		foreach ($required_fields as $field => $label) {
			$value = $this->input->post($field);
			if (!is_scalar($value) || trim((string) $value) === '') {
				$errors[] = "{$label} wajib diisi";
			}
		}

		// Additional validations
		$umur = $this->input->post('umur');
		if (!empty($umur)) {
			if (!is_scalar($umur) || !preg_match('/^[0-9]+$/D', (string) $umur) || $umur < 1 || $umur > 150) {
				$errors[] = "Umur harus berupa angka antara 1-150";
			}
		}

		$jenis_kelamin = $this->input->post('jenis_kelamin');
		if (!empty($jenis_kelamin) && !in_array($jenis_kelamin, ['Laki-laki', 'Perempuan'])) {
			$errors[] = "Jenis kelamin tidak valid";
		}

		$tanggal_tes = $this->input->post('tanggal_tes');
		if (!empty($tanggal_tes)) {
			$date_validation = $this->_validate_date_format($tanggal_tes);
			if (!$date_validation['status']) {
				$errors[] = $date_validation['message'];
			}
		}

		if (!empty($errors)) {
			return [
				'status' => false,
				'message' => 'Mohon periksa input data: ' . implode(', ', $errors),
				'errors' => $errors
			];
		}

		return ['status' => true];
	}

	/**
	 * Prepare and validate form data (INCLUDING audiometric values)
	 */
	private function _prepare_and_validate_form_data($id = null)
	{
		try {
			// Basic patient data
			$data = [
				'perusahaan' => trim($this->input->post('perusahaan')),
				'jabatan' => trim($this->input->post('jabatan')),
				'nama' => trim($this->input->post('nama')),
				'umur' => (int)$this->input->post('umur'),
				'jenis_kelamin' => $this->input->post('jenis_kelamin'),
				'no_rm' => trim($this->input->post('no_rm')),
				'tanggal_tes' => $this->input->post('tanggal_tes')
			];

			// Only include impression field during edit operations
			if ($id !== null) {
				$data['impression'] = trim($this->input->post('impression'));
			}

			// Validate and add audiometric values
			$frequencies = ['250', '500', '1000', '2000', '3000', '4000', '6000'];
			$ears = ['right', 'left'];
			$types = ['ac', 'bc'];
			$audiometric_errors = [];

			foreach ($ears as $ear) {
				foreach ($types as $type) {
					foreach ($frequencies as $freq) {
						$field = "{$ear}_{$type}_{$freq}";
						$value = $this->input->post($field);

						// Handle empty/null values
						if ($value === null || $value === '') {
							$data[$field] = null;
						} else {
							// Validate numeric and range
							if (!is_numeric($value)) {
								$audiometric_errors[] = "Nilai {$ear} {$type} {$freq}Hz harus berupa angka";
							} else {
								$db_value = floatval($value);
								if ($db_value < -10 || $db_value > 140) {
									$audiometric_errors[] = "Nilai {$ear} {$type} {$freq}Hz harus antara -10 hingga 140 dB";
								} else {
									$data[$field] = $db_value;
								}
							}
						}
					}
				}
			}

			// Check for audiometric validation errors
			if (!empty($audiometric_errors)) {
				return [
					'status' => false,
					'message' => 'Validasi audiometri gagal: ' . implode(', ', $audiometric_errors),
					'errors' => $audiometric_errors
				];
			}

			return [
				'status' => true,
				'data' => $data
			];
		} catch (Exception $e) {
			log_message('error', 'Prepare form data error: ' . $e->getMessage());
			return [
				'status' => false,
				'message' => 'Gagal memproses data form'
			];
		}
	}

	/**
	 * Validate date format
	 */
	private function _validate_date_format($date)
	{
		if (!is_string($date) || trim($date) === '') {
			return ['status' => false, 'message' => 'Tanggal tes wajib diisi dalam format YYYY-MM-DD'];
		}

		$d = DateTime::createFromFormat('Y-m-d', $date);
		if (!$d || $d->format('Y-m-d') !== $date) {
			return ['status' => false, 'message' => 'Format tanggal tidak valid (gunakan format: YYYY-MM-DD)'];
		}

		// Check if date is not in future
		if ($d > new DateTime()) {
			return ['status' => false, 'message' => 'Tanggal tes tidak boleh di masa depan'];
		}

		// Check if date is not too old (optional - max 5 years ago)
		$five_years_ago = new DateTime('-5 years');
		if ($d < $five_years_ago) {
			return ['status' => false, 'message' => 'Tanggal tes tidak boleh lebih dari 5 tahun yang lalu'];
		}

		return ['status' => true];
	}

	/**
	 * Generate custom pagination - MANUAL IMPLEMENTATION
	 */
	private function _generate_custom_pagination($total_rows, $per_page, $current_page, $filters = [])
	{
		if ($total_rows <= $per_page) {
			return ''; // No pagination needed
		}

		$total_pages = ceil($total_rows / $per_page);
		$current_page = max(1, min($current_page, $total_pages));
		
		// Build base URL with filters
		$base_url = base_url('list_tests');
		$query_params = [];
		
		if (!empty($filters['nama'])) {
			$query_params['nama'] = $filters['nama'];
		}
		if (!empty($filters['perusahaan'])) {
			$query_params['perusahaan'] = $filters['perusahaan'];
		}
		if (!empty($filters['tanggal_dari'])) {
			$query_params['tanggal_dari'] = $filters['tanggal_dari'];
		}
		if (!empty($filters['tanggal_sampai'])) {
			$query_params['tanggal_sampai'] = $filters['tanggal_sampai'];
		}
		
		$query_string = !empty($query_params) ? '&' . http_build_query($query_params) : '';
		
		$pagination = '<nav><ul class="pagination justify-content-center">';
		
		// First page link
		// if ($current_page > 1) {
		// 	$pagination .= '<li class="page-item">';
		// 	$pagination .= '<a class="page-link" href="' . $base_url . '?page=1' . $query_string . '">First</a>';
		// 	$pagination .= '</li>';
		// }
		
		// Previous page link
		if ($current_page > 1) {
			$prev_page = $current_page - 1;
			$pagination .= '<li class="page-item">';
			$pagination .= '<a class="page-link" href="' . html_escape($base_url . '?page=' . $prev_page . $query_string) . '">&laquo;</a>';
			$pagination .= '</li>';
		}
		
		// Page number links
		$start_page = max(1, $current_page - 2);
		$end_page = min($total_pages, $current_page + 2);
		
		for ($i = $start_page; $i <= $end_page; $i++) {
			if ($i == $current_page) {
				$pagination .= '<li class="page-item active">';
				$pagination .= '<a class="page-link" href="#">' . $i . '</a>';
				$pagination .= '</li>';
			} else {
				$pagination .= '<li class="page-item">';
				$pagination .= '<a class="page-link" href="' . html_escape($base_url . '?page=' . $i . $query_string) . '">' . $i . '</a>';
				$pagination .= '</li>';
			}
		}
		
		// Next page link
		if ($current_page < $total_pages) {
			$next_page = $current_page + 1;
			$pagination .= '<li class="page-item">';
			$pagination .= '<a class="page-link" href="' . html_escape($base_url . '?page=' . $next_page . $query_string) . '">&raquo;</a>';
			$pagination .= '</li>';
		}
		
		// Last page link
		// if ($current_page < $total_pages) {
		// 	$pagination .= '<li class="page-item">';
		// 	$pagination .= '<a class="page-link" href="' . $base_url . '?page=' . $total_pages . $query_string . '">Last</a>';
		// 	$pagination .= '</li>';
		// }
		
		$pagination .= '</ul></nav>';
		
		return $pagination;
	}

	/**
	 * Show custom error page
	 */
	private function _show_error_page($title, $message = '')
	{
		$data['title'] = $title;
		$data['error_message'] = $message;

		$this->load->view('audiometri/header', $data);
		$this->load->view('audiometri/error_page', $data);
		$this->load->view('audiometri/footer');
	}

	/**
	 * JSON response helper dengan error handling
	 */
	private function _json_response($data)
	{
		try {
			$this->output
				->set_content_type('application/json')
				->set_output(json_encode($data));
		} catch (Exception $e) {
			log_message('error', 'JSON response error: ' . $e->getMessage());
			$this->output
				->set_content_type('application/json')
				->set_output(json_encode([
					'status' => false,
					'message' => 'Response encoding error'
				]));
		}
	}

	// File: application/controllers/Audiometri.php - ADD EXPORT_PDF METHOD
	// Tambahkan method ini ke controller yang sudah ada

	/**
	 * Export ke PDF - FIXED VERSION
	 */
	public function export_pdf($id = null)
	{
		try {
			if (!$id || !is_numeric($id)) {
				if ($this->input->is_ajax_request()) {
					$this->_json_response(['status' => false, 'message' => 'Invalid ID']);
					return;
				}
				show_404();
				return;
			}

			$test_data = $this->Audiometri_model->get_test($id);

			// Get logo
			$data['logo_base64'] = $this->get_logo_for_pdf();
			if (!$test_data) {
				$this->session->set_flashdata('error', 'Data tes tidak ditemukan');
				redirect('list_tests');
				return;
			}

			// Load library PDF dengan error handling
			try {
				$this->load->library('Pdf_generator');
			} catch (Exception $e) {
				log_message('error', 'Failed to load PDF library: ' . $e->getMessage());

				// Fallback: Generate HTML view yang bisa di-print
				$this->_generate_html_for_print($test_data);
				return;
			}

			// Prepare data untuk PDF
			$data['test_data'] = $test_data;

			// Generate HTML content
			$html = $this->load->view('audiometri/pdf_template', $data, TRUE);

			// Generate filename
			$filename = 'Audiometri_' .
				str_replace(' ', '_', $test_data['nama']) . '_' .
				date('Y-m-d', strtotime($test_data['tanggal_tes'])) . '.pdf';

			// Generate PDF
			$this->pdf_generator->generate($html, $filename, 'D'); // 'D' untuk download

		} catch (Exception $e) {
			log_message('error', 'Export PDF error: ' . $e->getMessage());

			if (ENVIRONMENT !== 'production') {
				show_error('PDF Export Error: ' . $e->getMessage());
			} else {
				$this->session->set_flashdata('error', 'Gagal mengexport PDF. Silakan coba lagi.');
				redirect('view/' . $id);
			}
		}
	}

	/**
	 * Fallback method untuk generate HTML yang bisa di-print
	 */
	private function _generate_html_for_print($test_data)
	{
		$data['test_data'] = $test_data;
		$data['title'] = 'Print Hasil Audiometri';

		// Load view khusus untuk print
		$this->load->view('audiometri/print_template', $data);
	}

	/**
	 * Helper method untuk generate audiogram chart SVG
	 */
	public function _generate_audiogram_chart($ear, $test_data)
	{
		try {
			$width = 280;
			$height = 240;
			$margin_left = 30;
			$margin_right = 20;
			$margin_top = 20;
			$margin_bottom = 40;

			$chart_width = $width - $margin_left - $margin_right;
			$chart_height = $height - $margin_top - $margin_bottom;

			$frequencies = [250, 500, 1000, 2000, 3000, 4000, 6000];
			$db_range = [-10, 140];

			$svg = '<svg class="chart-svg" width="' . $width . '" height="' . $height . '" xmlns="http://www.w3.org/2000/svg">';

			// Background
			$svg .= '<rect width="' . $width . '" height="' . $height . '" fill="#fff" stroke="#000" stroke-width="1"/>';

			// Draw grid
			for ($i = 0; $i < count($frequencies); $i++) {
				$x = $margin_left + ($i * $chart_width / (count($frequencies) - 1));
				$svg .= '<line x1="' . $x . '" y1="' . $margin_top . '" x2="' . $x . '" y2="' . ($height - $margin_bottom) . '" stroke="#ccc" stroke-width="0.5"/>';

				// Frequency labels
				$svg .= '<text x="' . $x . '" y="' . ($height - 5) . '" text-anchor="middle" font-size="8" fill="#000">' . $frequencies[$i] . '</text>';
			}

			// Horizontal lines (dB levels)
			for ($db = -10; $db <= 140; $db += 10) {
				$y = $margin_top + (($db - $db_range[0]) / ($db_range[1] - $db_range[0])) * $chart_height;
				$stroke_width = ($db % 20 == 0) ? "1" : "0.5";
				$stroke_color = ($db % 20 == 0) ? "#999" : "#ccc";
				$svg .= '<line x1="' . $margin_left . '" y1="' . $y . '" x2="' . ($width - $margin_right) . '" y2="' . $y . '" stroke="' . $stroke_color . '" stroke-width="' . $stroke_width . '"/>';

				// dB labels
				if ($db % 20 == 0) {
					$svg .= '<text x="5" y="' . ($y + 3) . '" font-size="8" fill="#000">' . $db . '</text>';
				}
			}

			// Plot AC (Air Conduction) data
			$ac_points = array();
			foreach ($frequencies as $i => $freq) {
				$value = $test_data[$ear . '_ac_' . $freq];
				if ($value !== null && is_numeric($value)) {
					$x = $margin_left + ($i * $chart_width / (count($frequencies) - 1));
					$y = $margin_top + (($value - $db_range[0]) / ($db_range[1] - $db_range[0])) * $chart_height;
					$ac_points[] = $x . ',' . $y;

					// Add AC symbol (arrow)
					$symbol = ($ear == 'right') ? '◀' : '▶';
					$color = ($ear == 'right') ? '#0066CC' : '#CC0000';
					$svg .= '<text x="' . $x . '" y="' . ($y + 3) . '" text-anchor="middle" fill="' . $color . '" font-size="10" font-weight="bold">' . $symbol . '</text>';
				}
			}

			// Draw AC line
			if (count($ac_points) > 1) {
				$color = ($ear == 'right') ? '#0066CC' : '#CC0000';
				$svg .= '<polyline points="' . implode(' ', $ac_points) . '" stroke="' . $color . '" stroke-width="2" fill="none" stroke-dasharray="3,3"/>';
			}

			// Plot BC (Bone Conduction) data
			$bc_points = array();
			foreach ($frequencies as $i => $freq) {
				$value = $test_data[$ear . '_bc_' . $freq];
				if ($value !== null && is_numeric($value)) {
					$x = $margin_left + ($i * $chart_width / (count($frequencies) - 1));
					$y = $margin_top + (($value - $db_range[0]) / ($db_range[1] - $db_range[0])) * $chart_height;
					$bc_points[] = $x . ',' . $y;

					// Add BC symbol
					$color = ($ear == 'right') ? '#0066CC' : '#CC0000';
					if ($ear == 'right') {
						// Circle for right ear BC
						$svg .= '<circle cx="' . $x . '" cy="' . $y . '" r="3" fill="' . $color . '" stroke="#000" stroke-width="1"/>';
					} else {
						// Diamond for left ear BC
						$svg .= '<rect x="' . ($x - 3) . '" y="' . ($y - 3) . '" width="6" height="6" fill="' . $color . '" stroke="#000" stroke-width="1" transform="rotate(45 ' . $x . ' ' . $y . ')"/>';
					}
				}
			}

			// Draw BC line
			if (count($bc_points) > 1) {
				$color = ($ear == 'right') ? '#0066CC' : '#CC0000';
				$svg .= '<polyline points="' . implode(' ', $bc_points) . '" stroke="' . $color . '" stroke-width="2" fill="none"/>';
			}

			$svg .= '</svg>';

			return $svg;
		} catch (Exception $e) {
			log_message('error', 'Error generating audiogram chart: ' . $e->getMessage());
			return '<svg width="280" height="240"><text x="140" y="120" text-anchor="middle" fill="red">Error generating chart</text></svg>';
		}
	}
	private function get_logo_for_pdf()
	{
		try {
			// Method 1: Absolute file path (RECOMMENDED)
			$logo_file_path = FCPATH . 'assets/images/logo/logo-kop.png';

			if (!file_exists($logo_file_path)) {
				log_message('error', 'Logo file not found at: ' . $logo_file_path);
				return null;
			}

			// Try to get file info first
			$image_info = @getimagesize($logo_file_path);
			if ($image_info === false) {
				log_message('error', 'Invalid image file at: ' . $logo_file_path);
				return null;
			}

			// For PDF generation, return file path
			if ($this->input->get('format') === 'pdf') {
				return $logo_file_path;
			}

			// For other uses, return base64
			$image_data = file_get_contents($logo_file_path);
			if ($image_data === false) {
				log_message('error', 'Failed to read logo file at: ' . $logo_file_path);
				return null;
			}

			return 'data:' . $image_info['mime'] . ';base64,' . base64_encode($image_data);
		} catch (Exception $e) {
			log_message('error', 'Error processing logo: ' . $e->getMessage());
			return null;
		}
	}

	/**
	 * Export data to Excel
	 */
	public function export_excel()
	{
		try {
			// Tanpa filter = semua data; dengan filter = hanya periode/perusahaan terpilih
			$filters = $this->_get_report_filters(false);
			$data['filters'] = $filters;
			$data['audiometri_data'] = $this->Audiometri_model->get_report_rows($filters);

			// Load the export view
			$this->load->view('audiometri/export_excel', $data);
		} catch (Exception $e) {
			log_message('error', 'Export Excel error: ' . $e->getMessage());
			$this->session->set_flashdata('error', 'Gagal mengexport data ke Excel');
			redirect('list_tests');
		}
	}

	/**
	 * Laporan periode: ringkasan, distribusi tingkat pendengaran, tren, dan ekspor
	 */
	public function laporan()
	{
		try {
			$filters = $this->_get_report_filters(true);
			$rows = $this->Audiometri_model->get_report_rows($filters);

			$data['title'] = 'Laporan Periode';
			$data['filters'] = $filters;
			$data['presets'] = $this->_report_presets();
			$data['report'] = $this->Audiometri_model->build_period_report($rows);

			$this->load->view('audiometri/header', $data);
			$this->load->view('audiometri/laporan', $data);
			$this->load->view('audiometri/footer');
		} catch (Exception $e) {
			log_message('error', 'Laporan error: ' . $e->getMessage());
			$this->_show_error_page('Laporan tidak dapat dimuat', $e->getMessage());
		}
	}

	/**
	 * Filter laporan dari query string (tanggal_dari, tanggal_sampai, perusahaan).
	 * Jika parameter tanggal tidak ada sama sekali, laporan default ke bulan berjalan.
	 */
	private function _get_report_filters($default_to_current_month = false)
	{
		$get = $this->input->get();
		$get = is_array($get) ? $get : [];
		$has_date = array_key_exists('tanggal_dari', $get) || array_key_exists('tanggal_sampai', $get);

		if (!$has_date && $default_to_current_month) {
			$dari = date('Y-m-01');
			$sampai = date('Y-m-t');
		} else {
			$dari = $this->_clean_report_date(isset($get['tanggal_dari']) ? $get['tanggal_dari'] : '');
			$sampai = $this->_clean_report_date(isset($get['tanggal_sampai']) ? $get['tanggal_sampai'] : '');
		}

		if ($dari !== '' && $sampai !== '' && $dari > $sampai) {
			list($dari, $sampai) = [$sampai, $dari];
		}

		$perusahaan = (isset($get['perusahaan']) && is_string($get['perusahaan'])) ? trim($get['perusahaan']) : '';
		$perusahaan = function_exists('mb_substr') ? mb_substr($perusahaan, 0, 100) : substr($perusahaan, 0, 100);

		return [
			'tanggal_dari' => $dari,
			'tanggal_sampai' => $sampai,
			'perusahaan' => $perusahaan
		];
	}

	/**
	 * Mengembalikan tanggal Y-m-d yang valid atau string kosong
	 */
	private function _clean_report_date($value)
	{
		if (!is_string($value)) {
			return '';
		}

		$value = trim($value);
		$date = DateTime::createFromFormat('Y-m-d', $value);

		return ($date && $date->format('Y-m-d') === $value) ? $value : '';
	}

	/**
	 * Teks input aman: hanya string, tanpa karakter kontrol, dipangkas, dibatasi panjang.
	 * Nilai array/non-string menjadi string kosong.
	 */
	private function _clean_text($value, $max_length = 100)
	{
		if (!is_string($value)) {
			return '';
		}

		$value = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/', '', $value);
		$value = trim((string) $value);

		return function_exists('mb_substr') ? mb_substr($value, 0, $max_length) : substr($value, 0, $max_length);
	}

	/**
	 * Nomor halaman: bilangan bulat 1..100000, selain itu 1.
	 */
	private function _clean_page($value)
	{
		if (!is_string($value) || !ctype_digit($value)) {
			return 1;
		}

		return max(1, min(100000, (int) $value));
	}

	/**
	 * Header keamanan dikirim dari aplikasi agar tetap berlaku di server selain Apache.
	 * CSP mengizinkan inline script/style (dipakai view saat ini) dan CDN jsDelivr saja.
	 */
	private function _send_security_headers()
	{
		$csp = "default-src 'self'; "
			. "script-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net; "
			. "style-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net; "
			. "font-src 'self' https://cdn.jsdelivr.net data:; "
			. "img-src 'self' data:; connect-src 'self'; object-src 'none'; "
			. "base-uri 'self'; form-action 'self'; frame-ancestors 'none'";

		$headers = [
			'Content-Security-Policy: ' . $csp,
			'X-Content-Type-Options: nosniff',
			'X-Frame-Options: DENY',
			'Referrer-Policy: strict-origin-when-cross-origin',
			'Permissions-Policy: camera=(), microphone=(), geolocation=(), payment=()',
			'Cache-Control: private, no-store, max-age=0',
		];

		if (strpos((string) $this->config->item('base_url'), 'https://') === 0) {
			$headers[] = 'Strict-Transport-Security: max-age=31536000';
		}

		foreach ($headers as $header) {
			$this->output->set_header($header);
		}
	}

	/**
	 * Pilihan periode cepat untuk halaman laporan
	 */
	private function _report_presets()
	{
		$today = date('Y-m-d');
		$last_month = strtotime('first day of last month');

		return [
			['label' => 'Hari Ini', 'dari' => $today, 'sampai' => $today],
			['label' => '7 Hari Terakhir', 'dari' => date('Y-m-d', strtotime('-6 days')), 'sampai' => $today],
			['label' => 'Bulan Ini', 'dari' => date('Y-m-01'), 'sampai' => date('Y-m-t')],
			['label' => 'Bulan Lalu', 'dari' => date('Y-m-01', $last_month), 'sampai' => date('Y-m-t', $last_month)],
			['label' => 'Tahun Ini', 'dari' => date('Y-01-01'), 'sampai' => date('Y-12-31')],
			['label' => 'Semua Waktu', 'dari' => '', 'sampai' => '']
		];
	}

	/**
	 * Doctor view - shows today's patients for impression editing
	 */
	public function doctor_view()
	{
		try {
			$data['title'] = 'Panel Dokter - Data Pasien Hari Ini';

			// Get today's tests
			// Get today's tests with date filter
			$today = date('Y-m-d');
			$data['tests'] = $this->Audiometri_model->get_tests(null, 0, ['tanggal_dari' => $today, 'tanggal_sampai' => $today]);

			$this->load->view('audiometri/header', $data);
			$this->load->view('audiometri/doctor_view', $data);
			$this->load->view('audiometri/footer');
		} catch (Exception $e) {
			log_message('error', 'Doctor view error: ' . $e->getMessage());
			$this->_show_error_page('Panel dokter tidak dapat dimuat', $e->getMessage());
		}
	}

	/**
	 * Update impression only (for doctors)
	 */
	public function update_impression()
	{
		try {
			if (!$this->input->is_ajax_request()) {
				show_404();
				return;
			}

			$test_id = $this->input->post('test_id');
			$impression = $this->_clean_text($this->input->post('impression'), 5000);

			if (!is_scalar($test_id) || !ctype_digit((string) $test_id) || (int) $test_id < 1) {
				$this->_json_response([
					'status' => false,
					'message' => 'ID tes tidak valid'
				]);
				return;
			}

			if ($impression === '') {
				$this->_json_response([
					'status' => false,
					'message' => 'Impression tidak boleh kosong'
				]);
				return;
			}

			// Update test data with impression only
			$result = $this->Audiometri_model->update_test($test_id, ['impression' => $impression]);
			$this->_json_response($result);
		} catch (Exception $e) {
			log_message('error', 'Update impression error: ' . $e->getMessage());
			$this->_json_response([
				'status' => false,
				'message' => 'Terjadi kesalahan sistem'
			]);
		}
	}

	/**
	 * Get patient data for modal view
	 */
	public function get_patient_data($id = null)
	{
		try {
			if (!$id || !is_numeric($id)) {
				echo '<div class="alert alert-danger">ID tidak valid</div>';
				return;
			}

			$test_data = $this->Audiometri_model->get_test($id);
			if (!$test_data) {
				echo '<div class="alert alert-danger">Data tidak ditemukan</div>';
				return;
			}

			// Load partial view for patient data
			$this->load->view('audiometri/patient_data_partial', ['test_data' => $test_data]);
		} catch (Exception $e) {
			log_message('error', 'Get patient data error: ' . $e->getMessage());
			echo '<div class="alert alert-danger">Gagal memuat data pasien</div>';
		}
	}

	public function review_dokter($id = null)
	{
		// Validasi ID
		if (!$id || !is_numeric($id)) {
			show_404();
			return;
		}

		// Load model
		$this->load->model('Audiometri_model');

		// Ambil data test berdasarkan ID
		$data['title'] = 'Data Audiometri';
		$data['test_data'] = $this->Audiometri_model->get_test($id);
		$data['action'] = 'review_dokter';

		if (!$data['test_data']) {
			show_404();
			return;
		}

		// Handle POST request
		if ($this->input->method(TRUE) == 'POST') {
			try {
				// Get impression from POST
				$impression = $this->input->post('impression');

				if (empty($impression)) {
					if ($this->input->is_ajax_request()) {
						$this->_json_response([
							'status' => false,
							'message' => 'Impression tidak boleh kosong'
						]);
						return;
					}
					$this->session->set_flashdata('error', 'Impression tidak boleh kosong');
					redirect('review_dokter/' . $id);
					return;
				}

				// Get existing test data
				$existing_data = $this->Audiometri_model->get_test($id);
				if (!$existing_data) {
					if ($this->input->is_ajax_request()) {
						$this->_json_response([
							'status' => false,
							'message' => 'Data tidak ditemukan'
						]);
						return;
					}
					$this->session->set_flashdata('error', 'Data tidak ditemukan');
					redirect('review_dokter/' . $id);
					return;
				}

				// Update only impression field
				$update_data = $existing_data;
				$update_data['impression'] = $impression;

				// Save update
				$result = $this->Audiometri_model->update_test($id, $update_data);

				if ($this->input->is_ajax_request()) {
					$this->_json_response($result);
					return;
				}

				if ($result['status']) {
					$this->session->set_flashdata('success', 'Impression berhasil disimpan!');
				} else {
					$this->session->set_flashdata('error', $result['message']);
				}
				redirect('review_dokter/' . $id);

			} catch (Exception $e) {
				log_message('error', 'Review dokter update error: ' . $e->getMessage());
				if ($this->input->is_ajax_request()) {
					$this->_json_response([
						'status' => false,
						'message' => 'Terjadi kesalahan sistem'
					]);
					return;
				}
				$this->session->set_flashdata('error', 'Terjadi kesalahan sistem');
				redirect('review_dokter/' . $id);
			}
		}

		// Load view
		$this->load->view('audiometri/header', $data);
		$this->load->view('audiometri/review_dokter', $data);
		$this->load->view('audiometri/footer');
	}
}
