<?php
/**
 * Kelas Class
 *
 * @author	Awan Pribadi Basuki <awan_pribadi@yahoo.com>
 */
class Laporan extends CI_Controller {
	/**
	 * Constructor
	 */
	 var $j = array();
    var $crud = null;
	function __construct()
	{
		parent::__construct();
		if ($this->session->userdata('login') != TRUE)
		{
			redirect('login');
		}
		$this->load->database();
		$this->load->helper('url');
		$this->load->model('main_model', '', TRUE);
		$this->load->library('grocery_CRUD');
		$this->crud = new grocery_CRUD();
		$this->_init();
	}
	private function _init()
	{
		$this->output->set_template('admin');
		$ide= $this->session->userdata('level');
		$this->output->set_output_data('menu',$this->main_model->get_menu($ide));
		$this->load->js('assets/themes/default/js/jquery-1.9.1.min.js');
		$this->load->js('assets/themes/default/hero_files/bootstrap-transition.js');
		$this->load->js('assets/themes/default/hero_files/bootstrap-collapse.js');
		
		
		$d= $this->db->query("select id_jamaah, nama_jamaah from data_jamaah");
		foreach($d->result() as $row){
		    $this->j[$row->id_jamaah] = $row->nama_jamaah;
		}
	}
	private function show($module  = ''){
		$this->crud->set_theme('tanggal')->unset_export();
		$output = $this->crud->render();
		$this->load->view('ci_simplicity/admin',$output);
	}
	function index()
	{
		redirect('master/jamaah');
	}
/*


*/
	function unique_field_name($field_name) {
	    return 's'.substr(md5($field_name),0,8); //This s is because is better for a string to begin with a letter and not with a number
    }
	function harian(){
		$this->crud->set_table('pembayaran_transaksi_paket');
		$this->crud->set_subject('Data Transaksi Harian')->unset_add()->unset_edit()->unset_delete();
		$this->crud->set_relation('teller','admin','nama')->set_relation('jenis_transaksi','jenis_transaksi_pengeluaran','nama_transaksi')->set_relation('id_transaksi_paket','transaksi_paket','{jamaah}-{kode}')->columns('id','id_transaksi_paket','tanggal','tanggal_transfer','debet','kredit','jenis_transaksi','keterangan','teller')->display_as('id','Nomor Kuitansi')->display_as('id_transaksi_paket','Jamaah / Kode Booking');
		//print_r($this->j);
		$this->crud->callback_column($this->unique_field_name('id_transaksi_paket'),array($this,'_jamaah'));;
		
		$this->show();
		
	}
    function _jamaah($value,$row){
        if($value){
            $d= (explode("-",$value));
            if(isset($d[0]) && isset($d[1]) && isset($this->j[$d[0]]))
        	    return $this->j[$d[0]].'/'.$d[1];
        }
        return "-";
        
	}
	function log(){
		
	}
	
}
// END Kelas Class

/* End of file kelas.php */
/* Location: ./system/application/controllers/kelas.php */