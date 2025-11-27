<?php

/**
 * Kelas Class
 *
 * @author	Awan Pribadi Basuki <awan_pribadi@yahoo.com>
 */
class It extends CI_Controller
{
	/**
	 * Constructor
	 */
	var $t = array();
	var $paket = array();
	var $transaksi_paket = array();
	var $crud = '';

	function __construct()
	{
		parent::__construct();
		if ($this->session->userdata('login') != TRUE) {
			redirect('login');
		}
		$this->load->database();
		$this->load->helper('url');
		$this->load->model('main_model', '', TRUE);
		$this->load->model('master_model', '', TRUE);
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
		
		$this->load->js('assets/themes/default/js/jquery-migrate-3.4.1.js');
		$this->load->js('assets/themes/default/hero_files/bootstrap-transition.js');
		$this->load->js('assets/themes/default/hero_files/bootstrap-collapse.js');
	}
	private function show($module  = '')
	{
		$this->crud->set_theme('twitter-bootstrap');
		$output = $this->crud->render();
		$this->load->view('ci_simplicity/admin', $output);
	}


	function index()
	{
		$this->crud->set_table('jenis_transaksi_pengeluaran');
		$this->crud->set_subject('Jenis Transaksi');
		$this->crud->set_theme('datatables');
		$this->crud->set_subject('Jenis Transaksi')->unset_edit()->unset_delete();
		$this->show();
	}
	function log()
	{
	}
}
// END Kelas Class

/* End of file kelas.php */
/* Location: ./system/application/controllers/kelas.php */