<?php

/**
 * Kelas Class
 *
 * @author	Awan Pribadi Basuki <awan_pribadi@yahoo.com>
 */
class LaporanAgenController extends CI_Controller
{


	var $j = array();
	var $paketnya = array();
	var $group_rev = array();
	var $crud = null;
	var $paket = array();

    function __construct()
    {
        parent::__construct();
		if ($this->session->userdata('login') != TRUE) {
			redirect('login');
		}
		$this->load->database();
		$this->load->helper('url');
		$this->db->query("SET sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''))");
		$this->load->model('main_model', '', TRUE);
		$this->load->model('laporan_model', '', TRUE);
        $this->load->model('Single_link_share_jamaah_model');
       // $this->load->model('custom_crud_model');
		$this->load->library('grocery_CRUD');
        $this->load->library('session');
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

	}

    private function show($module  = '')
	{
		$this->crud->set_theme('twitter-bootstrap');
		$output = $this->crud->render();
		
		// $output->meta_keywords = "Something 2";
		$this->load->view('ci_simplicity/admin', $output);
	}

    private function showAgen($module  = '')
	{
		$this->crud->set_theme('twitter-bootstrap');
		$output = $this->crud->render();
		
		// $output->meta_keywords = "Something 2";
		$this->load->view('laporan_view_agen', $output);
	}


   
    public function laporan_agent_jamaahdfd()
{
    $this->load->database();
     $this->load->library('session');
    // 1. Ambil input tanggal dari GET
    $date_start = $this->input->get('date_start', TRUE);
    $date_end = $this->input->get('date_end', TRUE);
      $search_nama = $this->input->get('search_nama');
    // Validasi dan default (pastikan format YYYY-MM-DD)
     if ($date_start) {
        $this->session->set_userdata('filter_date_start', $date_start);
    } else {
        $date_start = $this->session->userdata('filter_date_start') ?: '2025-01-01';
    }

    if ($date_end) {
        $this->session->set_userdata('filter_date_end', $date_end);
    } else {
        $date_end = $this->session->userdata('filter_date_end') ?: date('Y-m-d');
    }

    if ($this->input->get('search_nama') !== null) {
        $this->session->set_userdata('filter_search_nama', $search_nama);
    } else {
        $search_nama = $this->session->userdata('filter_search_nama');
    }

    $this->crud->set_table('data_jamaah');
    $this->crud->where('is_agen', 1);
      if (!empty($search_nama)) {
        $clean_nama = $this->db->escape_like_str($search_nama);
        $this->crud->where("nama_jamaah LIKE '%$clean_nama%'");
    }
    $this->crud->set_subject('Laporan Qty per Agen');
    $this->crud->set_theme('twitter-bootstrap'); // Set theme di awal

    $this->crud->columns('id_jamaah', 'nama_jamaah', 'total_qty_dipesan');
    $this->crud->display_as('total_qty_dipesan', 'Total Qty (' . $date_start . ' s/d ' . $date_end . ')');

    // 2. Callback Column
    $this->crud->callback_column('total_qty_dipesan', function ($value, $row) use ($date_start, $date_end) {
        $query = $this->db->query("
            SELECT SUM(tp.qty) AS total
            FROM transaksi_paket tp
            INNER JOIN data_jamaah_paket djp ON tp.paket_umroh = djp.id
            WHERE (tp.jamaah = ? OR tp.agen = ?)
              AND djp.tanggal_keberangkatan BETWEEN ? AND ?
        ", array($row->id_jamaah, $row->id_jamaah, $date_start, $date_end))->row();

        return $query->total ? $query->total : 0;
    });

    $this->crud->unset_add()->unset_edit()->unset_delete();

    // 3. Render dulu
    $output = $this->crud->render();

    // 4. Tambah data ke output untuk view
    $output->date_start = $date_start;
    $output->date_end = $date_end;
    $output->title = "Laporan Jamaah Agen"; // Koreksi dari 'tittle' ke 'title' agar match view

    $this->load->view('laporan_view_agen', (array)$output);
}

    public function laporan_agent_jamaah()
{
    $this->load->library('session');
   //$this->load->database();
    // 1. Ambil data dari GET (jika tombol filter ditekan)
    $date_start  = $this->input->get('date_start');
    $date_end    = $this->input->get('date_end');
    $search_nama = $this->input->get('search_nama');

    // 2. Jika ada input baru, simpan ke session. Jika tidak ada, ambil dari session lama.
    if ($date_start) {
        $this->session->set_userdata('filter_date_start', $date_start);
    } else {
        $date_start = $this->session->userdata('filter_date_start') ?: '2025-01-01';
    }

    if ($date_end) {
        $this->session->set_userdata('filter_date_end', $date_end);
    } else {
        $date_end = $this->session->userdata('filter_date_end') ?: date('Y-m-d');
    }

    if ($this->input->get('search_nama') !== null) {
        $this->session->set_userdata('filter_search_nama', $search_nama);
    } else {
        $search_nama = $this->session->userdata('filter_search_nama');
    }

    // 3. Terapkan Filter ke Grocery CRUD
    $this->crud->set_table('data_jamaah');
    $this->crud->where('is_agen', 1);
     $this->crud->set_subject('Laporan Qty per Agen');
    $this->crud->set_theme('twitter-bootstrap'); // Set theme di awal


    if (!empty($search_nama)) {
        $clean_nama = $this->db->escape_like_str($search_nama);
        $this->crud->where("nama_jamaah LIKE '%$clean_nama%'");
    }

    // Kolom dan Subject
    $this->crud->columns( 'nama_jamaah', 'total_qty_dipesan');
    $this->crud->display_as('total_qty_dipesan', 'Total Qty (' . $date_start . ' s/d ' . $date_end . ')');

    // Callback Column (Gunakan variabel yang sudah difilter dari session)
    $this->crud->callback_column('total_qty_dipesan', function ($value, $row) use ($date_start, $date_end) {
        $query = $this->db->query("
            SELECT SUM(tp.qty) AS total
            FROM transaksi_paket tp
            INNER JOIN data_jamaah_paket djp ON tp.paket_umroh = djp.id
            WHERE (tp.jamaah = ? OR tp.agen = ?)
              AND djp.tanggal_keberangkatan BETWEEN ? AND ?
        ", array($row->id_jamaah, $row->id_jamaah, $date_start, $date_end))->row();

        return $query->total ? $query->total : 0;
    });

    $this->crud->unset_add()->unset_edit()->unset_delete();
    $output = $this->crud->render();

    // Kirim data ke view untuk mengisi input form
    $output->date_start  = $date_start;
    $output->date_end    = $date_end;
    $output->search_nama = $search_nama;
    $output->tittle      = "Laporan Jamaah Agen";

    $this->load->view('laporan_view_agen', (array)$output);
}






}
