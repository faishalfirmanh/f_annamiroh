<?php

/**
 * Kelas Class
 *
 * @author	Moch Yasin
 */
class Rekap extends CI_Controller
{
	/**
	 * Constructor
	 */
	var $j = array();
	var $paketnya = array();
	var $group_rev = array();
	var $crud = null;
	var $paket = array();
	var $jamaahs = array();
	var $paket_jamaah_ids = array(); // <-- PROPERTI BARU
	var $transaksi_data = array();
	var $biaya_tambahan_master_list = array(); // <-- INI HARUS ADA
	
	// Properti baru untuk menyimpan data biaya tambahan yang sudah didecode dari JSON
	var $transaksi_biaya_tambahan = array(); 
	var $js_loaded = false; // Flag untuk memastikan JS hanya di-load sekali  
	
	
	function __construct()
	{
		parent::__construct();
		if ($this->session->userdata('login') != TRUE) {
			redirect('login');
		}
		$this->load->database();
		$this->load->helper('url');
		$this->load->model('main_model', '', TRUE);
		$this->load->model('laporan_model', '', TRUE);
		$this->load->library('grocery_CRUD');
		$this->crud = new grocery_CRUD();
		$this->_init();
	}
	private function _init()
	{
		$this->output->set_template('admin');
		$ide = $this->session->userdata('level');
		$this->output->set_output_data('menu', $this->main_model->get_menu($ide));
		$this->load->js('assets/themes/default/js/jquery-1.9.1.min.js');
		$this->load->js('assets/themes/default/hero_files/bootstrap-transition.js');
		$this->load->js('assets/themes/default/hero_files/bootstrap-collapse.js');


		$d = $this->db->query("select id_jamaah, nama_jamaah,no_ktp from data_jamaah");
		foreach ($d->result() as $row) {
			$this->j[$row->id_jamaah] = $row->nama_jamaah . "-" . $row->no_ktp;
		}
		$query = $this->db->query("SELECT id,CONCAT(estimasi_keberangkatan,'-',Program,'-',CAST(FORMAT(harga,2,'de_DE') 
		      AS CHAR CHARACTER SET utf8)) AS detail FROM data_jamaah_paket");
		foreach ($query->result() as $row) {

			$this->paket[$row->id] = $row->detail;
		}
		
		
		$query = $this->db->query("SELECT id, nama_biaya FROM biaya_tambahan_master WHERE aktif = 'YA' ORDER BY id ASC");
		foreach ($query->result() as $row) {
			$this->biaya_tambahan_master_list[$row->id] = $row->nama_biaya;
		}
	}
	private function show($module  = '')
	{
		$this->crud->set_theme('twitter-bootstrap');
		$output = $this->crud->render();
		
		// $output->meta_keywords = "Something 2";
		$this->load->view('ci_simplicity/admin', $output);
	}
	function index()
	{
		redirect('master/jamaah');
	}
	function pembayaran(){
	   // 	$this->crud->set_table('data_jamaah_paket')->where('total_seat >',0)
	    	$this->crud->set_table('data_jamaah_paket')
			->unset_read()->columns('estimasi_keberangkatan')->unset_edit()
			->display_as('estimasi_keberangkatan', 'Nama Paket')
			->unset_delete()->unset_add()
			->set_subject('Pilih Paket')->where('KET', 'AKTIF')->where('TAMPIL', 'YA')->where('id',491);
			$this->db->select('*');
			$query = $this->db->get('transaksi_paket');
			foreach ($query->result() as $row) {
        		$paket_id = $row->paket_umroh; 
                $jamaah_id = $row->jamaah;
				if (isset($this->j[$row->paket_umroh])){
					$this->j[$row->paket_umroh]++;
                }
				else{
				    $this->j[$row->paket_umroh] = 1;
				}

				// B. Simpan Daftar ID Jamaah ke properti baru
                if (!isset($this->paket_jamaah_ids[$paket_id])) {
                    $this->paket_jamaah_ids[$paket_id] = array();
                }
                // Pastikan ID jamaah tidak kosong sebelum disimpan
                if (!empty($jamaah_id)) { 
                    $this->paket_jamaah_ids[$paket_id][] = $jamaah_id;
                }
                
                  if (!isset($this->transaksi_data[$paket_id])) {
                $this->transaksi_data[$paket_id] = array();
                }
                if (!isset($this->transaksi_data[$paket_id][$jamaah_id])) {
                     $this->transaksi_data[$paket_id][$jamaah_id] = array();
                }
                // Simpan seluruh objek baris transaksi
                $this->transaksi_data[$paket_id][$jamaah_id][] = $row; 
			}
			//print_r($this->j);
			$this->crud->callback_column('estimasi_keberangkatan', array($this, '_callback_webpage_url'));
			$this->show();
	}

	function get_details($master_id) {
    $jamaah_ids=    isset($this->paket_jamaah_ids[$master_id])     ?$this->paket_jamaah_ids[$master_id]     :    array();
    $html_output="";
            // ... (HTML Pembuka, H4, Div Table-responsive, Table) ...
        $html_output .= '<div class="detail-container">';
        $html_output .= '<h4>Detail Transaksi Jamaah (Paket ID: ' . $master_id . ')</h4>';
        $html_output .= '<div class="table-responsive">';
        $html_output .= '<table class="table table-bordered table-sm table-striped rekap-biaya-table" style="min-width:100%;">'; // Tambah class rekap-biaya-table
        $html_output .= '<thead><tr>';
        $html_output .= '<th>No.</th>';
        $html_output .= '<th>Nama Jamaah - NIK</th>';
        $html_output .= '<th>Kode</th>';
        $html_output .= '<th>Agen ID</th>';
        $html_output .= '<th class="text-right">Harga Normal</th>';
            
            // --- Kolom Biaya Tambahan Dinamis (Header) ---
            $biaya_tambahan_count = count($this->biaya_tambahan_master_list);
            foreach ($this->biaya_tambahan_master_list as $biaya_id => $nama_biaya) {
                // Beri class yang sama untuk identifikasi kolom yang bisa diedit
                $html_output .= '<th class="text-right biaya-tambahan-col" style="min-width: 120px;">' . htmlspecialchars($nama_biaya) . '</th>'; 
            }
            // --- End Header Kolom Biaya Tambahan ---
            
        $html_output .= '<th class="text-right">Harga Final</th>';
        $html_output .= '<th class="text-right">Debet</th>';
        $html_output .= '<th class="text-right">Kredit</th>';
        $html_output .= '<th class="text-right">Saldo</th>';
        $html_output .= '<th class="text-right">Kekurangan</th>';
        $html_output .= '<th>Status Lunas</th>';
        $html_output .= '<th>Permintaan Tambahan</th>';
        $html_output .= '</tr></thead>';
        $html_output .= '<tbody>';
       
        $no = 1;
        $colspan = 11 + $biaya_tambahan_count;
        
        if (empty($jamaah_ids)) {
          $html_output .= '<tr><td colspan="' . $colspan . '">Belum ada jamaah yang mendaftar.</td></tr>';
        } else {
          foreach ($jamaah_ids as $jamaah_id) {
            $jamaah_detail = isset($this->j[$jamaah_id]) ? $this->j[$jamaah_id] : 'Data Jamaah Tidak Ditemukan';
            
            $transaksi_list = isset($this->transaksi_data[$master_id][$jamaah_id]) 
                      ? $this->transaksi_data[$master_id][$jamaah_id] 
                      : array();
      
            $rowspan_count = count($transaksi_list);
            $is_first_row = true;
            
            if ($rowspan_count > 0) {
              foreach ($transaksi_list as $transaksi) {
                            $transaksi_id = $transaksi->id;
                            
                            // --- Hitung Total Biaya Tambahan dan Harga Final ---
                            $total_biaya_tambahan = 0;
                            $detail_biaya_tambahan = isset($this->transaksi_biaya_tambahan[$transaksi_id]) ? $this->transaksi_biaya_tambahan[$transaksi_id] : array();
                            
                            foreach ($detail_biaya_tambahan as $biaya_id => $jumlah) {
                                $total_biaya_tambahan += floatval($jumlah);
                            }
                            
                            $harga_final_value = $transaksi->harga_normal + $total_biaya_tambahan;
                            $harga_final_formatted = number_format($harga_final_value, 0, ',', '.');
                            // --- END Perhitungan Harga Final ---
                            
                $harga_normal = number_format($transaksi->harga_normal, 0, ',', '.');
                // ... (Pengambilan data lainnya) ...
                            $debet = number_format($transaksi->debet, 0, ',', '.');
                $kredit = number_format($transaksi->kredit, 0, ',', '.');
                $saldo = number_format($transaksi->saldo, 0, ',', '.');
                $kekurangan = number_format($transaksi->kekurangan, 0, ',', '.');
                            $kode = $transaksi->kode;
                $agen = $transaksi->agen;
                            $permintaan = $transaksi->permintaan_tambahan ? nl2br(htmlspecialchars($transaksi->permintaan_tambahan)) : 'Tidak ada';
                            $status_lunas_badge = ($transaksi->status_lunas == 1) 
                          ? '<span class="badge" style="background-color: green; color: white; padding: 5px;">LUNAS</span>'
                          : '<span class="badge" style="background-color: orange; color: white; padding: 5px;">BELUM LUNAS</span>';
                
                $html_output .= '<tr>';
                
                // Kolom Rowspan (No. dan Nama)
                if ($is_first_row) {
                  $html_output .= '<td rowspan="' . $rowspan_count . '">' . $no++ . '</td>';
                  $html_output .= '<td rowspan="' . $rowspan_count . '">' . $jamaah_detail . '</td>';
                  $is_first_row = false;
                }
                
                // Kolom detail transaksi
                $html_output .= '<td>' . $kode . '</td>';
                $html_output .= '<td>' . $agen . '</td>';
                $html_output .= '<td class="text-right">' . $harga_normal . '</td>'; // Harga Normal (Tidak Editable)
                            
                            // --- TAMPILKAN NILAI BIAYA TAMBAHAN (Editable Inline) ---
                            foreach ($this->biaya_tambahan_master_list as $biaya_id => $nama_biaya) {
                                $nilai_biaya = isset($detail_biaya_tambahan[$biaya_id]) ? floatval($detail_biaya_tambahan[$biaya_id]) : 0;
                                $nilai_formatted = number_format($nilai_biaya, 0, ',', '.');
                                
                                // Tambahkan class `editable-biaya` dan data-attributes untuk AJAX
                                $html_output .= '<td class="text-right editable-biaya" ';
                                $html_output .= 'data-transaksi-id="' . $transaksi_id . '" ';
                                $html_output .= 'data-biaya-id="' . $biaya_id . '" ';
                                $html_output .= 'data-nilai-raw="' . $nilai_biaya . '" '; // Nilai mentah untuk input
                                $html_output .= '>';
                                $html_output .= $nilai_formatted;
                                $html_output .= '</td>';
                            }
                            // --- END TAMPILKAN NILAI BIAYA TAMBAHAN ---
                            
                $html_output .= '<td class="text-right">' . $harga_final_formatted . '</td>'; // Harga Final (Hasil Hitungan)
                            
                            // ... (Kolom Debet, Kredit, Saldo, Kekurangan, Status Lunas, Permintaan) ...
                            $html_output .= '<td class="text-right">' . $debet . '</td>';
                $html_output .= '<td class="text-right">' . $kredit . '</td>';
                $html_output .= '<td class="text-right">' . $saldo . '</td>';
                $html_output .= '<td class="text-right">' . $kekurangan . '</td>';
                $html_output .= '<td>' . $status_lunas_badge . '</td>';
                $html_output .= '<td>' . $permintaan . '</td>';
                $html_output .= '</tr>';
              }
            } else {
                        // ... (Jika tidak ada data transaksi) ...
              $html_output .= '<tr>';
              $html_output .= '<td>' . $no++ . '</td>';
              $html_output .= '<td>' . $jamaah_detail . '</td>';
              $html_output .= '<td colspan="' . ($colspan - 2) . '">Data Transaksi Tidak Ditemukan.</td>';
              $html_output .= '</tr>';
            }
          }
        }
        // ... (HTML Penutup) ...
        $html_output .= '</tbody>';
        $html_output .= '</table>';
        $html_output .= '</div>'; 
        $html_output .= '</div>';
        
        return $html_output;
      }

	function update_biaya_tambahan()
		{
			if (!$this->input->is_ajax_request()) {
				exit('No direct script access allowed');
			}

			$transaksi_id = $this->input->post('transaksi_id');
			$biaya_master_id = $this->input->post('biaya_master_id');
			$nilai_baru = (float) str_replace(['.', ','], ['', '.'], $this->input->post('nilai_baru')); // Membersihkan input

			if (empty($transaksi_id) || empty($biaya_master_id)) {
				echo json_encode(['status' => 'error', 'message' => 'Data ID tidak lengkap.']);
				return;
			}

			$data = [
				'transaksi_id' => $transaksi_id,
				'biaya_master_id' => $biaya_master_id,
				'jumlah_biaya' => $nilai_baru
			];

			// Cek apakah data sudah ada (Unique Key: transaksi_id, biaya_master_id)
			$this->db->where('transaksi_id', $transaksi_id);
			$this->db->where('biaya_master_id', $biaya_master_id);
			$existing = $this->db->get('transaksi_biaya_tambahan')->row();

			if ($existing) {
				// Update data yang sudah ada
				$this->db->where('id', $existing->id);
				$this->db->update('transaksi_biaya_tambahan', ['jumlah_biaya' => $nilai_baru]);
			} else {
				// Insert data baru
				$this->db->insert('transaksi_biaya_tambahan', $data);
			}

			// Ambil Harga Normal transaksi untuk hitung Harga Final baru
			$transaksi = $this->db->select('harga_normal')->where('id', $transaksi_id)->get('transaksi_paket')->row();
			
			// Hitung ulang Total Biaya Tambahan untuk transaksi ini
			$total_biaya_tambahan = $this->db->select_sum('jumlah_biaya')->where('transaksi_id', $transaksi_id)->get('transaksi_biaya_tambahan')->row()->jumlah_biaya;
			
			$harga_final_baru = $transaksi->harga_normal + $total_biaya_tambahan;

			// Kirim respons kembali ke client
			echo json_encode([
				'status' => 'success',
				'nilai_formatted' => number_format($nilai_baru, 0, ',', '.'),
				'nilai_raw' => $nilai_baru,
				'harga_final_baru_formatted' => number_format($harga_final_baru, 0, ',', '.'),
				'total_biaya_tambahan' => $total_biaya_tambahan,
				'transaksi_id' => $transaksi_id
			]);
		}
  

    public function _callback_webpage_url($value, $row)
    {
        $jumlae = isset($this->j[$row->id]) ? intval($this->j[$row->id]) : 0;
        $row->total_seat = isset( $row->total_seat)? intval($row->total_seat):0;
        $sisa = $row->total_seat - $jumlae;
        
        // Gunakan $row->id (ID Paket) sebagai master_id untuk get_details
        $detail_html = $this->get_details($row->id);
        
        $kolom_utama = '';
        
        if ($sisa > 0) {
            $kolom_utama = "<a href='#'>$value - $jumlae orang sisa $sisa</a>";
        } else {
            $kolom_utama = "<a href='#'>$value - $jumlae orang seat PENUH</a>";
        }
    
        // Gabungkan output utama dengan detail HTML yang dimuat sejak awal
        $output = $kolom_utama;
        // Tambahkan styling agar detail terpisah secara visual di bawah tautan utama
        $output .= '<div class="gcrud-detail-autoloaded" style="margin-top: 10px; padding: 10px 0; border-top: 1px dashed #eee;">';
        $output .= $detail_html;
        $output .= '</div>';
        
        return $output;
    }

}
// END Rekap Class

/* End of file Relas.php */
/* Location: ./system/application/controllers/Rekap.php */