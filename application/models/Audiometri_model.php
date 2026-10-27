<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Model untuk mengelola data audiometri - FIXED VERSION
 * 
 * @author ArChiee
 * @version 2.0.1 - Fixed database and validation issues
 */
class Audiometri_model extends CI_Model {

    private $table = 'audiometri_tests';
    private $frequencies = ['250', '500', '1000', '2000', '3000', '4000', '6000'];
    // Urut dari paling ringan ke paling berat (sama dengan classify_hearing_level)
    private $hearing_levels = ['Normal', 'Mild hearing loss', 'Moderate hearing loss',
        'Moderately severe hearing loss', 'Severe hearing loss', 'Profound hearing loss'];

    public function __construct() {
        parent::__construct();
        
        // Load database dengan error handling
        if (!$this->db->conn_id) {
            try {
                $this->load->database();
            } catch (Exception $e) {
                log_message('error', 'Database connection failed: ' . $e->getMessage());
                show_error('Database connection failed. Please contact administrator.');
            }
        }
        
        // Test database connection
        if (!$this->db->conn_id) {
            show_error('Cannot connect to database. Please check configuration.');
        }
        
        // Check if table exists
        if (!$this->db->table_exists($this->table)) {
            log_message('error', 'Table ' . $this->table . ' does not exist');
            show_error('Database table not found. Please run database migration.');
        }
    }

    /**
     * Menyimpan data tes audiometri baru - ENHANCED VERSION
     */
    public function save_test($data) {
        try {
            // Validasi data wajib
            $required_fields = ['perusahaan', 'jabatan', 'nama', 'umur', 'jenis_kelamin', 'no_rm', 'tanggal_tes'];
            foreach ($required_fields as $field) {
                if (!isset($data[$field]) || empty(trim($data[$field]))) {
                    return [
                        'status' => false, 
                        'message' => "Field {$field} wajib diisi"
                    ];
                }
            }

            // Validasi tipe data
            if (!is_numeric($data['umur']) || $data['umur'] < 1 || $data['umur'] > 150) {
                return [
                    'status' => false,
                    'message' => 'Umur tidak valid (harus antara 1-150 tahun)'
                ];
            }

            if (!in_array($data['jenis_kelamin'], ['Laki-laki', 'Perempuan'])) {
                return [
                    'status' => false,
                    'message' => 'Jenis kelamin tidak valid'
                ];
            }

            // Validasi format tanggal
            if (!$this->_validate_date($data['tanggal_tes'])) {
                return [
                    'status' => false,
                    'message' => 'Format tanggal tidak valid'
                ];
            }

            // Check duplicate No RM untuk tanggal yang sama
            $existing = $this->_check_duplicate_rm($data['no_rm'], $data['tanggal_tes']);
            if ($existing) {
                return [
                    'status' => false,
                    'message' => 'No RM sudah ada untuk tanggal yang sama'
                ];
            }

            // Sanitize data
            $data = $this->_sanitize_data($data);

            // Generate impression otomatis
            // $data['impression'] = $this->generate_impression($data);
            
            // Set timestamps
            $data['created_at'] = date('Y-m-d H:i:s');
            $data['updated_at'] = date('Y-m-d H:i:s');
            
            // Begin transaction
            $this->db->trans_begin();
            
            if ($this->db->insert($this->table, $data)) {
                $insert_id = $this->db->insert_id();
                
                if ($this->db->trans_status() === FALSE) {
                    $this->db->trans_rollback();
                    return [
                        'status' => false, 
                        'message' => 'Gagal menyimpan data: Transaction failed'
                    ];
                } else {
                    $this->db->trans_commit();
                    return [
                        'status' => true, 
                        'message' => 'Data berhasil disimpan',
                        'id' => $insert_id
                    ];
                }
            } else {
                $this->db->trans_rollback();
                $error = $this->db->error();
                log_message('error', 'Database insert error: ' . $error['message']);
                return [
                    'status' => false, 
                    'message' => 'Gagal menyimpan data ke database'
                ];
            }
            
        } catch (Exception $e) {
            $this->db->trans_rollback();
            log_message('error', 'Save test error: ' . $e->getMessage());
            return [
                'status' => false,
                'message' => 'Terjadi kesalahan sistem saat menyimpan data'
            ];
        }
    }

    /**
     * Mengupdate data tes audiometri - ENHANCED VERSION
     */
    public function update_test($id, $data) {
        try {
            // Validasi ID
            if (!is_numeric($id) || $id <= 0) {
                return [
                    'status' => false,
                    'message' => 'ID tidak valid'
                ];
            }

            // Check if record exists
            $existing = $this->get_test($id);
            if (!$existing) {
                return [
                    'status' => false,
                    'message' => 'Data tidak ditemukan'
                ];
            }

            // Validasi data wajib
            $required_fields = ['perusahaan', 'jabatan', 'nama', 'umur', 'jenis_kelamin', 'no_rm', 'tanggal_tes'];
            foreach ($required_fields as $field) {
                if (!isset($data[$field]) || empty(trim($data[$field]))) {
                    return [
                        'status' => false, 
                        'message' => "Field {$field} wajib diisi"
                    ];
                }
            }

            // Check duplicate No RM (exclude current record)
            $existing_duplicate = $this->_check_duplicate_rm($data['no_rm'], $data['tanggal_tes'], $id);
            if ($existing_duplicate) {
                return [
                    'status' => false,
                    'message' => 'No RM sudah ada untuk tanggal yang sama'
                ];
            }

            // Sanitize data
            $data = $this->_sanitize_data($data);

            // Update timestamp
            $data['updated_at'] = date('Y-m-d H:i:s');
            
            // Begin transaction
            $this->db->trans_begin();
            
            $this->db->where('id', $id);
            if ($this->db->update($this->table, $data)) {
                if ($this->db->trans_status() === FALSE) {
                    $this->db->trans_rollback();
                    return [
                        'status' => false, 
                        'message' => 'Gagal mengupdate data: Transaction failed'
                    ];
                } else {
                    $this->db->trans_commit();
                    return [
                        'status' => true, 
                        'message' => 'Data berhasil diupdate'
                    ];
                }
            } else {
                $this->db->trans_rollback();
                $error = $this->db->error();
                log_message('error', 'Database update error: ' . $error['message']);
                return [
                    'status' => false, 
                    'message' => 'Gagal mengupdate data'
                ];
            }
            
        } catch (Exception $e) {
            $this->db->trans_rollback();
            log_message('error', 'Update test error: ' . $e->getMessage());
            return [
                'status' => false,
                'message' => 'Terjadi kesalahan sistem saat mengupdate data'
            ];
        }
    }

    /**
     * Mengambil data tes berdasarkan ID
     */
    public function get_test($id) {
        try {
            if (!is_numeric($id) || $id <= 0) {
                return false;
            }

            $this->db->where('id', $id);
            $result = $this->db->get($this->table)->row_array();
            
            return $result ?: false;
            
        } catch (Exception $e) {
            log_message('error', 'Get test error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Mengambil semua data tes dengan pagination dan filter - ENHANCED VERSION
     */
    public function get_tests($limit = 10, $offset = 0, $filters = []) {
        try {
            $this->db->select('*');
            $this->db->from($this->table);
            
            // Apply filters dengan sanitasi
            if (!empty($filters['nama'])) {
                $this->db->like('nama', $this->db->escape_like_str($filters['nama']));
            }
            if (!empty($filters['perusahaan'])) {
                $this->db->like('perusahaan', $this->db->escape_like_str($filters['perusahaan']));
            }
            if (!empty($filters['tanggal_dari'])) {
                if ($this->_validate_date($filters['tanggal_dari'])) {
                    $this->db->where('tanggal_tes >=', $filters['tanggal_dari']);
                }
            }
            if (!empty($filters['tanggal_sampai'])) {
                if ($this->_validate_date($filters['tanggal_sampai'])) {
                    $this->db->where('tanggal_tes <=', $filters['tanggal_sampai']);
                }
            }
            
            $this->db->order_by('tanggal_tes', 'DESC');
            $this->db->order_by('created_at', 'DESC');
            
            if ($limit > 0) {
                $this->db->limit($limit, $offset);
            }
            
            $result = $this->db->get()->result_array();
            return $result ?: [];
            
        } catch (Exception $e) {
            log_message('error', 'Get tests error: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Menghitung total data untuk pagination
     */
    public function count_tests($filters = []) {
        try {
            $this->db->from($this->table);
            
            // Apply same filters as get_tests
            if (!empty($filters['nama'])) {
                $this->db->like('nama', $this->db->escape_like_str($filters['nama']));
            }
            if (!empty($filters['perusahaan'])) {
                $this->db->like('perusahaan', $this->db->escape_like_str($filters['perusahaan']));
            }
            if (!empty($filters['tanggal_dari'])) {
                if ($this->_validate_date($filters['tanggal_dari'])) {
                    $this->db->where('tanggal_tes >=', $filters['tanggal_dari']);
                }
            }
            if (!empty($filters['tanggal_sampai'])) {
                if ($this->_validate_date($filters['tanggal_sampai'])) {
                    $this->db->where('tanggal_tes <=', $filters['tanggal_sampai']);
                }
            }
            
            return $this->db->count_all_results();
            
        } catch (Exception $e) {
            log_message('error', 'Count tests error: ' . $e->getMessage());
            return 0;
        }
    }

    /**
     * Menghapus data tes
     */
    public function delete_test($id) {
        try {
            if (!is_numeric($id) || $id <= 0) {
                return [
                    'status' => false,
                    'message' => 'ID tidak valid'
                ];
            }

            // Check if record exists
            $existing = $this->get_test($id);
            if (!$existing) {
                return [
                    'status' => false,
                    'message' => 'Data tidak ditemukan'
                ];
            }

            $this->db->trans_begin();
            
            $this->db->where('id', $id);
            if ($this->db->delete($this->table)) {
                if ($this->db->trans_status() === FALSE) {
                    $this->db->trans_rollback();
                    return [
                        'status' => false, 
                        'message' => 'Gagal menghapus data: Transaction failed'
                    ];
                } else {
                    $this->db->trans_commit();
                    return [
                        'status' => true, 
                        'message' => 'Data berhasil dihapus'
                    ];
                }
            } else {
                $this->db->trans_rollback();
                return [
                    'status' => false, 
                    'message' => 'Gagal menghapus data'
                ];
            }
            
        } catch (Exception $e) {
            $this->db->trans_rollback();
            log_message('error', 'Delete test error: ' . $e->getMessage());
            return [
                'status' => false,
                'message' => 'Terjadi kesalahan sistem saat menghapus data'
            ];
        }
    }

    /**
     * Generate impression otomatis berdasarkan data audiometri - ENHANCED VERSION
     */
    public function generate_impression($data) {
        try {
            $impression = 'Hasil analisis audiometri: ';
            $results = [];

            // Analyze right ear AC
            $right_ac_values = $this->get_hearing_values($data, 'right', 'ac');
            if (!empty($right_ac_values)) {
                $avg = array_sum($right_ac_values) / count($right_ac_values);
                $level = $this->classify_hearing_level($avg);
                $results[] = "Telinga kanan AC: {$level} (rata-rata " . number_format($avg, 1) . " dB)";
            }

            // Analyze right ear BC
            $right_bc_values = $this->get_hearing_values($data, 'right', 'bc');
            if (!empty($right_bc_values)) {
                $avg = array_sum($right_bc_values) / count($right_bc_values);
                $level = $this->classify_hearing_level($avg);
                $results[] = "Telinga kanan BC: {$level} (rata-rata " . number_format($avg, 1) . " dB)";
            }

            // Analyze left ear AC
            $left_ac_values = $this->get_hearing_values($data, 'left', 'ac');
            if (!empty($left_ac_values)) {
                $avg = array_sum($left_ac_values) / count($left_ac_values);
                $level = $this->classify_hearing_level($avg);
                $results[] = "Telinga kiri AC: {$level} (rata-rata " . number_format($avg, 1) . " dB)";
            }

            // Analyze left ear BC
            $left_bc_values = $this->get_hearing_values($data, 'left', 'bc');
            if (!empty($left_bc_values)) {
                $avg = array_sum($left_bc_values) / count($left_bc_values);
                $level = $this->classify_hearing_level($avg);
                $results[] = "Telinga kiri BC: {$level} (rata-rata " . number_format($avg, 1) . " dB)";
            }

            if (empty($results)) {
                return 'Data audiometri belum lengkap untuk analisis.';
            }

            return $impression . implode('. ', $results) . '.';
            
        } catch (Exception $e) {
            log_message('error', 'Generate impression error: ' . $e->getMessage());
            return 'Gagal membuat analisis audiometri.';
        }
    }

    /**
     * Mengambil nilai pendengaran dari data
     */
    private function get_hearing_values($data, $ear, $type) {
        $values = [];
        foreach ($this->frequencies as $freq) {
            $key = "{$ear}_{$type}_{$freq}";
            if (isset($data[$key]) && is_numeric($data[$key])) {
                $value = (float)$data[$key];
                if ($value >= -10 && $value <= 140) {
                    $values[] = $value;
                }
            }
        }
        return $values;
    }

    /**
     * Klasifikasi level pendengaran berdasarkan rata-rata dB
     */
    private function classify_hearing_level($avg_db) {
        if ($avg_db <= 25) return 'Normal';
        else if ($avg_db <= 40) return 'Mild hearing loss';
        else if ($avg_db <= 55) return 'Moderate hearing loss';
        else if ($avg_db <= 70) return 'Moderately severe hearing loss';
        else if ($avg_db <= 90) return 'Severe hearing loss';
        else return 'Profound hearing loss';
    }

    /**
     * Mendapatkan statistik dashboard dengan error handling
     */
    public function get_dashboard_stats() {
        try {
            $stats = [];
            
            // Total tes
            $stats['total_tests'] = $this->db->count_all($this->table);
            
            // Tes bulan ini
            $this->db->where('MONTH(tanggal_tes)', date('m'));
            $this->db->where('YEAR(tanggal_tes)', date('Y'));
            $stats['tests_this_month'] = $this->db->count_all_results($this->table);
            
            // Perusahaan terbanyak
            $this->db->select('perusahaan, COUNT(*) as count');
            $this->db->group_by('perusahaan');
            $this->db->order_by('count', 'DESC');
            $this->db->limit(5);
            $result = $this->db->get($this->table)->result_array();
            $stats['top_companies'] = $result ?: [];
            
            return $stats;
            
        } catch (Exception $e) {
            log_message('error', 'Get dashboard stats error: ' . $e->getMessage());
            return [
                'total_tests' => 0,
                'tests_this_month' => 0,
                'top_companies' => []
            ];
        }
    }

    /**
     * Mencari data berdasarkan No RM
     */
    public function search_by_no_rm($no_rm) {
        try {
            if (empty(trim($no_rm))) {
                return [];
            }

            $this->db->where('no_rm', trim($no_rm));
            $this->db->order_by('tanggal_tes', 'DESC');
            $this->db->limit(10); // Limit untuk performa
            
            $result = $this->db->get($this->table)->result_array();
            return $result ?: [];
            
        } catch (Exception $e) {
            log_message('error', 'Search by no RM error: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Export data untuk laporan dengan error handling
     */
    public function export_data($filters = []) {
        try {
            $this->db->select('*');
            $this->db->from($this->table);
            
            // Apply filters
            if (!empty($filters['tanggal_dari'])) {
                if ($this->_validate_date($filters['tanggal_dari'])) {
                    $this->db->where('tanggal_tes >=', $filters['tanggal_dari']);
                }
            }
            if (!empty($filters['tanggal_sampai'])) {
                if ($this->_validate_date($filters['tanggal_sampai'])) {
                    $this->db->where('tanggal_tes <=', $filters['tanggal_sampai']);
                }
            }
            if (!empty($filters['perusahaan'])) {
                $this->db->where('perusahaan', $filters['perusahaan']);
            }
            
            $this->db->order_by('tanggal_tes', 'DESC');
            $this->db->limit(5000); // Limit untuk mencegah memory overflow
            
            $result = $this->db->get()->result_array();
            return $result ?: [];
            
        } catch (Exception $e) {
            log_message('error', 'Export data error: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Validasi format tanggal
     */
    private function _validate_date($date) {
        if (empty($date)) return false;
        
        $d = DateTime::createFromFormat('Y-m-d', $date);
        return $d && $d->format('Y-m-d') === $date;
    }

    /**
     * Check duplicate No RM
     */
    private function _check_duplicate_rm($no_rm, $tanggal_tes, $exclude_id = null) {
        $this->db->where('no_rm', $no_rm);
        $this->db->where('tanggal_tes', $tanggal_tes);
        
        if ($exclude_id) {
            $this->db->where('id !=', $exclude_id);
        }
        
        return $this->db->count_all_results($this->table) > 0;
    }

    /**
     * Sanitize input data
     */
    private function _sanitize_data($data) {
        // Sanitize string fields
        $string_fields = ['perusahaan', 'jabatan', 'nama', 'no_rm'];
        foreach ($string_fields as $field) {
            if (isset($data[$field])) {
                $data[$field] = trim(strip_tags($data[$field]));
            }
        }
        
        // Sanitize numeric fields
        if (isset($data['umur'])) {
            $data['umur'] = (int)$data['umur'];
        }
        
        return $data;
    }

    /**
     * Get all audiometri test data for export
     */
    public function get_all_tests() {
        try {
            $this->db->select('*');
            $this->db->from($this->table);
            $this->db->order_by('tanggal_tes', 'DESC');
            
            $result = $this->db->get()->result_array();
            return $result ?: [];
            
        } catch (Exception $e) {
            log_message('error', 'Get all tests error: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Mengambil data tes untuk laporan/ekspor periode (tanpa pagination).
     * Filter: tanggal_dari, tanggal_sampai (Y-m-d) dan perusahaan (mengandung teks).
     */
    public function get_report_rows($filters = []) {
        try {
            $this->db->select('*');
            $this->db->from($this->table);

            if (!empty($filters['tanggal_dari']) && $this->_validate_date($filters['tanggal_dari'])) {
                $this->db->where('tanggal_tes >=', $filters['tanggal_dari']);
            }
            if (!empty($filters['tanggal_sampai']) && $this->_validate_date($filters['tanggal_sampai'])) {
                $this->db->where('tanggal_tes <=', $filters['tanggal_sampai']);
            }
            if (!empty($filters['perusahaan'])) {
                $this->db->like('perusahaan', $filters['perusahaan']);
            }

            $this->db->order_by('tanggal_tes', 'DESC');
            $this->db->order_by('created_at', 'DESC');

            $result = $this->db->get()->result_array();
            return $result ?: [];

        } catch (Exception $e) {
            log_message('error', 'Get report rows error: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Klasifikasi satu tes berdasarkan rata-rata AC telinga yang lebih buruk.
     * Mengembalikan null jika tidak ada nilai AC yang terukur.
     */
    public function classify_test($row) {
        $averages = [];
        foreach (['right', 'left'] as $ear) {
            $values = $this->get_hearing_values($row, $ear, 'ac');
            if (!empty($values)) {
                $averages[] = array_sum($values) / count($values);
            }
        }

        if (empty($averages)) {
            return null;
        }

        return $this->classify_hearing_level(max($averages));
    }

    /**
     * Menyusun ringkasan laporan periode dari baris tes (tanpa akses database).
     */
    public function build_period_report(array $rows) {
        $report = [
            'total'      => count($rows),
            'reviewed'   => 0,
            'pending'    => 0,
            'gender'     => ['Laki-laki' => 0, 'Perempuan' => 0],
            'avg_age'    => null,
            'levels'     => array_fill_keys($this->hearing_levels, 0),
            'no_data'    => 0,
            'abnormal'   => 0,
            'companies'  => [],
            'trend'      => [],
            'trend_unit' => 'day',
            'attention'  => [],
        ];

        $age_sum = 0;
        $age_count = 0;
        $companies = [];
        $day_counts = [];
        $attention = [];

        foreach ($rows as $row) {
            if (trim((string) (isset($row['impression']) ? $row['impression'] : '')) !== '') {
                $report['reviewed']++;
            } else {
                $report['pending']++;
            }

            $gender = isset($row['jenis_kelamin']) ? $row['jenis_kelamin'] : '';
            if (isset($report['gender'][$gender])) {
                $report['gender'][$gender]++;
            }

            if (isset($row['umur']) && is_numeric($row['umur']) && $row['umur'] > 0) {
                $age_sum += (float) $row['umur'];
                $age_count++;
            }

            $name = trim((string) (isset($row['perusahaan']) ? $row['perusahaan'] : ''));
            if ($name === '') {
                $name = '-';
            }
            if (!isset($companies[$name])) {
                $companies[$name] = ['name' => $name, 'count' => 0, 'abnormal' => 0];
            }
            $companies[$name]['count']++;

            $level = $this->classify_test($row);
            if ($level === null) {
                $report['no_data']++;
            } else {
                $report['levels'][$level]++;
                if ($level !== 'Normal') {
                    $report['abnormal']++;
                    $companies[$name]['abnormal']++;
                    $attention[] = [
                        'id'         => isset($row['id']) ? $row['id'] : null,
                        'nama'       => isset($row['nama']) ? $row['nama'] : '',
                        'no_rm'      => isset($row['no_rm']) ? $row['no_rm'] : '',
                        'perusahaan' => $name,
                        'tanggal'    => isset($row['tanggal_tes']) ? $row['tanggal_tes'] : '',
                        'level'      => $level,
                        'severity'   => (int) array_search($level, $this->hearing_levels, true),
                    ];
                }
            }

            $date = substr((string) (isset($row['tanggal_tes']) ? $row['tanggal_tes'] : ''), 0, 10);
            if ($date !== '') {
                $day_counts[$date] = (isset($day_counts[$date]) ? $day_counts[$date] : 0) + 1;
            }
        }

        if ($age_count > 0) {
            $report['avg_age'] = round($age_sum / $age_count, 1);
        }

        $companies = array_values($companies);
        usort($companies, function ($a, $b) {
            if ($a['count'] !== $b['count']) {
                return $b['count'] - $a['count'];
            }
            return strcmp($a['name'], $b['name']);
        });
        $report['companies'] = $companies;

        usort($attention, function ($a, $b) {
            if ($a['severity'] !== $b['severity']) {
                return $b['severity'] - $a['severity'];
            }
            return strcmp($b['tanggal'], $a['tanggal']);
        });
        $report['attention'] = array_slice($attention, 0, 10);

        if (!empty($day_counts)) {
            ksort($day_counts);
            $days = array_keys($day_counts);
            $span = (strtotime(end($days)) - strtotime(reset($days))) / 86400;

            if ($span > 62) {
                // Rentang panjang: gabungkan per bulan agar grafik tetap terbaca
                $report['trend_unit'] = 'month';
                foreach ($day_counts as $day => $count) {
                    $month = substr($day, 0, 7);
                    $report['trend'][$month] = (isset($report['trend'][$month]) ? $report['trend'][$month] : 0) + $count;
                }
            } else {
                $report['trend'] = $day_counts;
            }
        }

        return $report;
    }


}
