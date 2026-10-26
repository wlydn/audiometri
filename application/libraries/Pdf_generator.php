<?php
// File: application/libraries/Pdf_generator.php - SIMPLE PDF GENERATOR
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Simple PDF Generator Library
 * Fallback untuk TCPDF atau library PDF lainnya
 */
class Pdf_generator {

    private $CI;

    public function __construct() {
        $this->CI =& get_instance();
    }

    /**
     * Generate PDF dari HTML
     */
    public function generate($html, $filename = 'document.pdf', $output = 'D') {
        try {
            // Coba gunakan TCPDF jika tersedia
            if (class_exists('TCPDF')) {
                $this->_generate_with_tcpdf($html, $filename, $output);
                return;
            }
            
            // Coba gunakan mPDF jika tersedia
            if (class_exists('mPDF')) {
                $this->_generate_with_mpdf($html, $filename, $output);
                return;
            }
            
            // Fallback: Generate HTML response untuk print
            $this->_generate_html_fallback($html, $filename);
            
        } catch (Exception $e) {
            log_message('error', 'PDF Generation failed: ' . $e->getMessage());
            
            // Final fallback
            $this->_generate_html_fallback($html, $filename);
        }
    }
    
    /**
     * Generate dengan TCPDF
     */
    private function _generate_with_tcpdf($html, $filename, $output) {
        require_once APPPATH . 'third_party/tcpdf/tcpdf.php';
        
        $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
        
        $pdf->SetCreator('Audiometri System');
        $pdf->SetAuthor('ArChiee');
        $pdf->SetTitle('Hasil Tes Audiometri');
        
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pdf->SetMargins(15, 15, 15);
        $pdf->SetAutoPageBreak(TRUE, 15);
        
        $pdf->AddPage();
        $pdf->SetFont('helvetica', '', 10);
        
        $pdf->writeHTML($html, true, false, true, false, '');
        $pdf->Output($filename, $output);
    }
    
    /**
     * Generate dengan mPDF
     */
    private function _generate_with_mpdf($html, $filename, $output) {
        require_once APPPATH . 'third_party/mpdf/mpdf.php';
        
        $mpdf = new mPDF('c', 'A4');
        $mpdf->WriteHTML($html);
        
        if ($output == 'D') {
            $mpdf->Output($filename, 'D');
        } else {
            $mpdf->Output($filename, $output);
        }
    }
    
    /**
     * HTML Fallback - untuk print manual
     */
    private function _generate_html_fallback($html, $filename) {
        header('Content-Type: text/html; charset=UTF-8');
        $download_name = preg_replace('/[^A-Za-z0-9_.-]/', '_', str_replace('.pdf', '.html', $filename));
        header('Content-Disposition: inline; filename="'.$download_name.'"');
        
        echo '<!DOCTYPE html>';
        echo '<html><head>';
        echo '<title>'.htmlspecialchars(str_replace('.pdf', '', $filename), ENT_QUOTES, 'UTF-8').'</title>';
        echo '<style>
            @media print {
                .no-print { display: none; }
                body { margin: 0; }
                @page { margin: 15mm; }
            }
            body { font-family: Arial, sans-serif; }
            .print-info { 
                background: #e3f2fd; 
                padding: 15px; 
                margin: 10px 0; 
                border: 1px solid #2196f3; 
                border-radius: 5px; 
            }
        </style>';
        echo '</head><body>';
        
        echo '<div class="print-info no-print">';
        echo '<h3>📄 Preview Hasil Audiometri</h3>';
        echo '<p><strong>Instruksi:</strong> Gunakan Ctrl+P atau menu Print browser untuk mencetak dokumen ini.</p>';
        echo '<button onclick="window.print()" style="background: #2196f3; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer;">🖨️ Print Sekarang</button>';
        echo '</div>';
        
        echo $html;
        echo '</body></html>';
        exit;
    }
}

?>
