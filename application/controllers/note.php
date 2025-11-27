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
	var $paket_jamaah_ids = array();
	var $transaksi_data = array();
	var $biaya_tambahan_master_list = array();
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
	
	private function show($module  = '')
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
	    	$this->crud->set_table('data_jamaah_paket')
			->unset_read()->columns('estimasi_keberangkatan')->unset_edit()
			->display_as('estimasi_keberangkatan', 'Nama Paket')
			->unset_delete()->unset_add()
			->set_subject('Pilih Paket')->where('KET', 'AKTIF')->where('TAMPIL', 'YA')->where('id',491);
			
			// Pastikan kolom biaya_tambahan_json ikut diambil
			$this->db->select('*'); 
			$query = $this->db->get('transaksi_paket');
			
			// Inisialisasi ulang properti yang digunakan untuk penghitungan dan penyimpanan data
            $jumlah_terisi = array();
            $this->paket_jamaah_ids = array();
            $this->transaksi_data = array();
            $this->transaksi_biaya_tambahan = array(); // Inisialisasi properti baru

			foreach ($query->result() as $row) {
        		$paket_id = $row->paket_umroh; 
                $jamaah_id = $row->jamaah;
                
                // A. Hitung Kursi Terisi
				if (isset($jumlah_terisi[$row->paket_umroh])){
					$jumlah_terisi[$row->paket_umroh]++;
                } else{
				    $jumlah_terisi[$row->paket_umroh] = 1;
				}

				// B. Simpan Daftar ID Jamaah ke properti baru
                if (!isset($this->paket_jamaah_ids[$paket_id])) {
                    $this->paket_jamaah_ids[$paket_id] = array();
                }
                if (!empty($jamaah_id) && !in_array($jamaah_id, $this->paket_jamaah_ids[$paket_id])) { 
                    $this->paket_jamaah_ids[$paket_id][] = $jamaah_id;
                }
                
                // C. Simpan Detail Transaksi Penuh (untuk get_details)
                if (!isset($this->transaksi_data[$paket_id])) {
                    $this->transaksi_data[$paket_id] = array();
                }
                if (!isset($this->transaksi_data[$paket_id][$jamaah_id])) {
                    $this->transaksi_data[$paket_id][$jamaah_id] = array();
                }
                $this->transaksi_data[$paket_id][$jamaah_id][] = $row; 
                
                // D. Decode dan simpan JSON biaya tambahan
                if (!empty($row->biaya_tambahan_json)) {
                    // Simpan data JSON yang sudah didecode berdasarkan ID Transaksi
                    $this->transaksi_biaya_tambahan[$row->id] = json_decode($row->biaya_tambahan_json, true) ?: [];
                    // Konversi nilai menjadi float/angka untuk perhitungan
                    $this->transaksi_biaya_tambahan[$row->id] = array_map('floatval', $this->transaksi_biaya_tambahan[$row->id]);
                } else {
                    $this->transaksi_biaya_tambahan[$row->id] = [];
                }
			}
			
            $this->j = $jumlah_terisi; // Update $this->j dengan hitungan kursi

			$this->crud->callback_column('estimasi_keberangkatan', array($this, '_callback_webpage_url'));
			$this->show();
	}

	function get_details($master_id) {
        $jamaah_ids=    isset($this->paket_jamaah_ids[$master_id])     ?$this->paket_jamaah_ids[$master_id]     :    array();
        $html_output="";
            
        $html_output .= '<div class="detail-container">';
        $html_output .= '<h4>Detail Transaksi Jamaah (Paket ID: ' . $master_id . ')</h4>';
        // Scrollbar Horizontal dan Vertikal Otomatis
        $html_output .= '<div style="overflow-x: auto; overflow-y: auto; max-height: 300px; width: 100%;">';
        $html_output .= '<table class="table table-bordered table-sm table-striped rekap-biaya-table" style="width: auto; table-layout: auto;">'; // Hapus min-width

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
                            
                        // --- MEMBACA DATA JSON DARI PROPERTI BARU ---
                        $detail_biaya_tambahan = isset($this->transaksi_biaya_tambahan[$transaksi_id]) ? $this->transaksi_biaya_tambahan[$transaksi_id] : array();
                            
                        $total_biaya_tambahan = 0;
                        foreach ($detail_biaya_tambahan as $biaya_id => $jumlah) {
                            $total_biaya_tambahan += floatval($jumlah);
                        }
                            
                        $harga_final_value = $transaksi->harga_normal + $total_biaya_tambahan;
                        $harga_final_formatted = number_format($harga_final_value, 0, ',', '.');
                        // --- END Perhitungan Harga Final ---
                            
                $harga_normal = number_format($transaksi->harga_normal, 0, ',', '.');
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
                $html_output .= '<td class="text-right">' . $harga_normal . '</td>';
                            
                            // --- TAMPILKAN NILAI BIAYA TAMBAHAN (Editable Inline) ---
                            foreach ($this->biaya_tambahan_master_list as $biaya_id => $nama_biaya) {
                                $nilai_biaya = isset($detail_biaya_tambahan[$biaya_id]) ? $detail_biaya_tambahan[$biaya_id] : 0;
                                $nilai_formatted = number_format($nilai_biaya, 0, ',', '.');
                                
                                // Tambahkan class `editable-biaya` dan data-attributes untuk AJAX
                                $html_output .= '<td class="text-right editable-biaya" ';
                                $html_output .= 'data-transaksi-id="' . $transaksi_id . '" ';
                                $html_output .= 'data-biaya-id="' . $biaya_id . '" ';
                                $html_output .= 'data-nilai-raw="' . $nilai_biaya . '" '; // Nilai mentah untuk input
                                $html_output .= 'style="cursor: pointer;" title="Klik untuk edit" >'; // Tambah cursor: pointer
                                $html_output .= $nilai_formatted;
                                $html_output .= '</td>';
                            }
                            // --- END TAMPILKAN NILAI BIAYA TAMBAHAN ---
                            
                $html_output .= '<td class="text-right harga-final-' . $transaksi_id . '">' . $harga_final_formatted . '</td>'; // Tambah class untuk update via JS
                            
                $html_output .= '<td class="text-right">' . $debet . '</td>';
                $html_output .= '<td class="text-right">' . $kredit . '</td>';
                $html_output .= '<td class="text-right">' . $saldo . '</td>';
                $html_output .= '<td class="text-right kekurangan-' . $transaksi_id . '">' . $kekurangan . '</td>'; // Tambah class untuk update via JS
                $html_output .= '<td>' . $status_lunas_badge . '</td>';
                $html_output .= '<td>' . $permintaan . '</td>';
                $html_output .= '</tr>';
              }
            } else {
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

	/**
     * FUNGSI BARU: Menyimpan Biaya Tambahan ke kolom JSON dan menghitung ulang kekurangan/harga.
     */
	function save_biaya_tambahan_json()
    {
        if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
        }

        // Ambil data dari AJAX
        $transaksi_id = $this->input->post('transaksi_id');
        $biaya_master_id = $this->input->post('biaya_master_id');
        // Bersihkan input: hapus titik/koma, ganti koma dengan titik (jika format Eropa)
        $nilai_baru = (float) str_replace(['.', ','], ['', '.'], $this->input->post('nilai_baru')); 
        
        if (empty($transaksi_id) || empty($biaya_master_id)) {
            echo json_encode(['status' => 'error', 'message' => 'Data ID Transaksi tidak lengkap.']);
            return;
        }

        // 1. Ambil data transaksi saat ini (termasuk JSON yang sudah ada)
        $transaksi_row = $this->db->select('*')->where('id', $transaksi_id)->get('transaksi_paket')->row();

        if (!$transaksi_row) {
             echo json_encode(['status' => 'error', 'message' => 'Transaksi tidak ditemukan.']);
             return;
        }

        // 2. Decode JSON yang sudah ada
        $biaya_tambahan_data = json_decode($transaksi_row->biaya_tambahan_json, true) ?: [];
        
        // 3. Update nilai baru
        $biaya_tambahan_data[$biaya_master_id] = $nilai_baru;
        
        // 4. Hitung Total Biaya Tambahan dan Harga Final
        $total_biaya_tambahan = array_sum(array_map('floatval', $biaya_tambahan_data));
        $harga_final_baru = $transaksi_row->harga_normal + $total_biaya_tambahan;
        
        // 5. Hitung Kekurangan (Asumsi: Harga Final - Debet + Kredit)
        $kekurangan_baru = $harga_final_baru - $transaksi_row->debet + $transaksi_row->kredit;
        
        // 6. Encode kembali JSON dan update database
        $json_baru = json_encode($biaya_tambahan_data);
        
        $update_data = [
            'biaya_tambahan_json' => $json_baru,
            'harga' => $harga_final_baru, // Update kolom harga (total biaya paket + tambahan)
            'kekurangan' => $kekurangan_baru // Update kekurangan
        ];

        $this->db->where('id', $transaksi_id)->update('transaksi_paket', $update_data);
        
        // 7. Kirim respons kembali ke client
        echo json_encode([
            'status' => 'success',
            'nilai_formatted' => number_format($nilai_baru, 0, ',', '.'),
            'nilai_raw' => $nilai_baru,
            'harga_final_baru_formatted' => number_format($harga_final_baru, 0, ',', '.'),
            'kekurangan_baru_formatted' => number_format($kekurangan_baru, 0, ',', '.'),
            'transaksi_id' => $transaksi_id
        ]);
    }
    
    public function _callback_webpage_url($value, $row)
    {
        $jumlae = isset($this->j[$row->id]) ? intval($this->j[$row->id]) : 0;
        $row->total_seat = isset( $row->total_seat)? intval($row->total_seat):0;
        $sisa = $row->total_seat - $jumlae;
        
        $detail_html = $this->get_details($row->id);
        
        $kolom_utama = '';
        
        if ($sisa > 0) {
            $kolom_utama = "<a href='#'>$value - $jumlae orang sisa $sisa</a>";
        } else {
            $kolom_utama = "<a href='#'>$value - $jumlae orang seat PENUH</a>";
        }
    
        $output = $kolom_utama;
        $output .= '<div class="gcrud-detail-autoloaded" style="margin-top: 10px; padding: 10px 0; border-top: 1px dashed #eee;">';
        $output .= $detail_html;
        $output .= '</div>';

        // Hanya load JS sekali pada baris pertama
        if (!$this->js_loaded) {
            $output .= $this->get_inline_edit_js();
            $this->js_loaded = true;
        }
        
        return $output;
    }
    
    /**
     * FUNGSI BARU: JavaScript untuk fungsionalitas inline edit.
     */
    private function get_inline_edit_js()
    {
        // URL ke fungsi AJAX baru
        $ajax_url = site_url('rekap/save_biaya_tambahan_json'); 
        
        $js = <<<JS
        <script>
        $(document).ready(function() {
            // Cek apakah fungsionalitas sudah terpasang
            if ($('body').data('biaya-tambahan-attached')) {
                return; 
            }
            
            // Pasang event handler
            $('body').on('click', '.editable-biaya', function(e) {
                var cell = $(this);
                
                // Pastikan tidak sedang dalam mode edit
                if (cell.find('input').length) {
                    return;
                }

                var transaksiId = cell.data('transaksi-id');
                var biayaId = cell.data('biaya-id');
                var initialValue = cell.data('nilai-raw');
                
                // 1. Ganti sel dengan input field
                cell.empty();
                var input = $('<input type="text" class="form-control input-sm" style="text-align: right; width: 100px;">');
                
                // Hapus pemformatan angka (titik dan koma) untuk nilai input
                var rawValue = String(initialValue).replace(/[.]/g, '').replace(/[,]/g, '');
                input.val(rawValue);
                
                cell.append(input);
                input.focus();

                // 2. Handler saat Enter ditekan atau fokus hilang (Blur)
                var saveEdit = function() {
                    var newValue = input.val().replace(/[.]/g, '').replace(/[,]/g, ''); // Bersihkan input
                    
                    // Jika nilai tidak berubah atau kosong, kembalikan ke nilai awal
                    if (String(newValue) === String(initialValue) || newValue === '') {
                        var initialFormatted = initialValue.toLocaleString('id-ID', { minimumFractionDigits: 0, maximumFractionDigits: 0 });
                        cell.html(initialFormatted);
                        return;
                    }

                    // Tampilkan Loading
                    cell.html('<i class="fa fa-spinner fa-spin"></i>');

                    // 3. Kirim via AJAX
                    $.ajax({
                        url: '$ajax_url',
                        type: 'POST',
                        dataType: 'json',
                        data: {
                            transaksi_id: transaksiId,
                            biaya_master_id: biayaId,
                            nilai_baru: newValue
                        },
                        success: function(response) {
                            if (response.status === 'success') {
                                // Update sel yang diedit
                                cell.html(response.nilai_formatted);
                                cell.data('nilai-raw', response.nilai_raw);
                                
                                // Update Harga Final dan Kekurangan
                                $('.harga-final-' + transaksiId).html(response.harga_final_baru_formatted);
                                $('.kekurangan-' + transaksiId).html(response.kekurangan_baru_formatted);

                                // Tambahkan pesan sukses
                                alert('Berhasil! Total Harga Final dan Kekurangan telah diperbarui.');

                            } else {
                                alert('Error: ' + response.message);
                                // Kembalikan nilai lama jika gagal
                                cell.html(initialValue.toLocaleString('id-ID', { minimumFractionDigits: 0, maximumFractionDigits: 0 }));
                            }
                        },
                        error: function() {
                            alert('Terjadi kesalahan koneksi.');
                            // Kembalikan nilai lama jika error
                            cell.html(initialValue.toLocaleString('id-ID', { minimumFractionDigits: 0, maximumFractionDigits: 0 }));
                        }
                    });
                };

                input.on('blur', saveEdit);
                input.on('keypress', function(e) {
                    if (e.which === 13) { // Enter key
                        input.off('blur'); // Matikan blur agar tidak double-save
                        saveEdit();
                        return false; 
                    }
                });
            });

            // Set flag agar tidak dijalankan lagi
            $('body').data('biaya-tambahan-attached', true);
        });
        </script>
JS;
        return $js;
    }

}
// END Rekap Class

/* End of file Relas.php */
/* Location: ./system/application/controllers/Rekap.php */