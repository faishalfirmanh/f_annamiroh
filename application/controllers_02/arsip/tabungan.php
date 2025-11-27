<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tabungan extends CI_Controller {
    var $title = 'jamaah';
	public function __construct()
	{
		parent::__construct();

		$this->load->database();
		$this->load->helper('url');
		$this->load->model('main_model', '', TRUE);
		$this->load->library('grocery_CRUD');
	}
	function jamaah(){
		
	}
	function agen(){
		
	}
	function leader(){
		
	}
	// public function news(){
		// $data = array();
		// $data['menu1'] = $this->main_model->get_menu();
		// $data['menu2'] = $this->main_model->get_menu(2);
		// $this->load->view('t/index',$data);
	// }
	public function _example_output($output = null)
	{
		$this->load->view('example.php',$output);
	}

	function agen(){
		if ($this->session->userdata('login') != TRUE)
			redirect('login');
		$this->load->library('grocery_CRUD');
		$crud = new grocery_CRUD();
		$crud->set_theme('datatables');
	
		$crud->set_table('data_jamaah_agen');
		$crud->set_subject('Data Agen Umroh');
	
		
		$output = $crud->render();
		$data['title'] = $this->title;
		$data['nama_menu'] = 'Menu';
		$data['menu_kiri'] = 'jamaah/jamaah_left';
		$data['h2_title'] = 'Data Jamaah';
		$data['main_view'] = 'transaksi_template';
		$data['isi'] = 'jamaah/jamaah_view';
		$data['output'] = $output;
		$this->load->view('template_new',$output);
	} 
	function paket(){
		if ($this->session->userdata('login') != TRUE)
			redirect('login');
		$this->load->library('grocery_CRUD');
		$crud = new grocery_CRUD();
		$crud->set_theme('datatables');
	
		$crud->set_table('data_jamaah_paket');
		$crud->set_subject('Data Paket Umroh');
	
		
		$output = $crud->render();
		$data['title'] = $this->title;
		$data['nama_menu'] = 'Menu';
		$data['menu_kiri'] = 'jamaah/jamaah_left';
		$data['h2_title'] = 'Data Jamaah';
		$data['main_view'] = 'transaksi_template';
		$data['isi'] = 'jamaah/jamaah_view';
		$data['output'] = $output;
		$this->load->view('template_new',$output);
	}
	public function index()
	{
		$this->agen();
	}



}