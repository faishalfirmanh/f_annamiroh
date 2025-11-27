<?php
class Log extends CI_Controller {

	function __construct(){
		parent::__construct();	
		$this->load->model('Log_model', '', TRUE);
	}
	var $title = 'Log';
	var $limit = 15;
	function index(){
		if ($this->session->userdata('login') == TRUE){
			$this->get_Log();		
		}
		else{
			$this->load->view('login/login_view');
		}
	}
	
	function get_Log(){
		$data['title'] = 'Log';
		$data['nama_menu'] = 'Menu Log';
		$data['menu_kiri'] = 'log/log_left';
		$data['h2_title'] = 'Log';
		$data['main_view'] = 'transaksi_template';
		$data['isi'] = 'log/log_view';
		$data['petunjuk'] = '&lt;- Klik Jenis Log yang ada di sebelah kiri';
		
		$this->load->view('template', $data);
		
	}
	function get_last_ten_logs($tabel){
		$this->load->library('grocery_CRUD');
		$crud = new grocery_CRUD();
		
		// $crud->set_theme('datatables');
		$crud->set_table($tabel);
		$crud->set_subject('Data log '.substr($tabel,0,-4));
		$this->load->view('template_new',$crud->render());
	}
	function get_last_ten_log($tabel,$offset = 0){
		$data['title'] = substr($tabel,0,-3);
		$data['nama_menu'] = 'Log '.substr($tabel,0,-4);
		$data['menu_kiri'] = 'log/log_left';
		$data['h2_title'] = 'Data log '.substr($tabel,0,-4);
		$data['main_view'] = 'transaksi_template';
		$data['isi'] = 'log/log_view';
		$uri_segment = 4;
		$offset = $this->uri->segment($uri_segment);
		
		$logs = $this->Log_model->get_last_ten_log($tabel,$this->limit, $offset)->result();
		
		$qr = $this->Log_model->count_all_num_rows($tabel);
		$num = $qr->row_array();
		$num_rows = $num['id_log'];
		
		if ($num_rows > 0){
			$config['base_url'] = site_url('log/get_last_ten_log/'.$tabel);
			$config['total_rows'] = $num_rows;
			$config['per_page']	= $this->limit;
			$config['uri_segment'] = $uri_segment;
			$this->pagination->initialize($config);
			$data['pagination'] = $this->pagination->create_links();
			
			$tmpl = array('table_open' => '<table class="table" border="1">', 
			'row_alt_start' => '<tr class="zebra">', 
			'row_alt_end' => '</tr>');
			
			$this->table->set_template($tmpl);
			$this->table->set_empty("&nbsp;");
			$this->table->set_heading('No', 'Aksi', 'User', 'Id', 'Waktu', 'Data lama', 'Data baru');
			
			$i = 1 + $offset;
			foreach ($logs as $log){
				$this->table->add_row('<div align="right">'.$i++.'</div>', 

			//		'<div align="right">'. $log->id.'</div>', 
					$log->aksi, 
					$log->nama_user,
					$log->id_user,
					$log->waktu,
					$log->data_lama,
					$log->data_baru
					//implode(",",json_decode($log->data_lama,true)),
					//implode(",",json_decode($log->data_baru,true))
				);
			}
			
			$data['table'] = $this->table->generate();
		}
		else{
			$data['message'] = 'Tidak ditemukan satupun data log!';
		}
	// Load view
		
		$this->load->view('template', $data);
	} // end get_last_ten_log

}
/* End of file welcome.php */
/* Location: ./system/application/controllers/welcome.php */