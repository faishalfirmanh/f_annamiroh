<?php

/**
 * Kelas Class
 *
 * @author	Moch Yasin
 */
class AjaxController extends CI_Controller
{
	
	public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->helper('url');
        $this->load->js('assets/themes/default/js/jquery-1.9.1.min.js');
		$this->load->js('assets/themes/default/hero_files/bootstrap-transition.js');
		$this->load->js('assets/themes/default/hero_files/bootstrap-collapse.js');
    }
	
	function packageSeatCalculation()
	{
		$subquery = "(
                  SELECT SUM(qty) 
                  FROM transaksi_paket tp 
                  JOIN data_jamaah dj ON tp.jamaah = dj.id_jamaah 
                  WHERE tp.paket_umroh = p.id 
                  AND LOWER(dj.nama_jamaah) != 'jamaah baru dummy')";
		$this->db->select("
					p.id, 
					p.travel, 
					p.estimasi_keberangkatan, 
					p.jumlah_pendaftar, 
					p.qty, 
					p.total_seat, 
					p.Program,
					p.tanggal_keberangkatan, 
					p.estimasi_tgl_keberangkatan, 
					p.ket,
					$subquery as totalPendaftarReal
				", FALSE);

   		 $this->db->from('data_jamaah_paket p');
		 $this->db->where('p.tanggal_keberangkatan > CURRENT_DATE()', NULL, FALSE);
		// $this->db->where("DATEDIFF(p.tanggal_keberangkatan, CURRENT_DATE()) <", 25);
        $this->db->where('p.tanggal_keberangkatan <= DATE_ADD(CURRENT_DATE(), INTERVAL 25 DAY)', NULL, FALSE);
		 $this->db->having('totalPendaftarReal < p.qty');
		 $this->db->order_by('p.id', 'DESC');
		 $query = $this->db->get();
		
		if (!$query) {
             $error = $this->db->error();
             $response = [
                'status' => false,
                'message' => 'DB Error: ' . $error['message']
             ];
             // Set header 500 agar masuk ke block 'error' di ajax
             $this->output->set_status_header(500);
         } else {
             $response = [
                'status' => true,
                'total'  => $query->num_rows(),
                'data'   => $query->result()
            ];
         }
		ob_end_clean();
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response));
	}
}
// END Kelas Class

/* End of file kelas.php */
/* Location: ./system/application/controllers/kelas.php */