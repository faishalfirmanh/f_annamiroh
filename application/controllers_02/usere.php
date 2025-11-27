<?php

class Usere extends CI_Controller {
	/**
	 * Constructor
	 */
	 var $t = array();
	 var $j = array();
	 var $crud='';
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
	function shortcut(){
        $this->crud = new grocery_CRUD();
		$this->crud->set_table('user_shortcut')->columns('link','menu','level','urutan')->set_relation('level','group_level','nama')->order_by('level')->order_by('urutan');
		//$ide= $this->session->userdata('level');
		//$this->crud->field_type('level', 'hidden', $ide);
		$this->crud->set_theme('twitter-bootstrap');		
	    $this->show();
    }
	private function _init()
	{
		$this->output->set_template('admin');
		$ide= $this->session->userdata('level');
		$this->output->set_output_data('menu',$this->main_model->get_menu($ide));
		$this->load->js('assets/themes/default/js/jquery-1.9.1.min.js');
		$this->load->js('assets/themes/default/hero_files/bootstrap-transition.js');
		$this->load->js('assets/themes/default/hero_files/bootstrap-collapse.js');
	}
	function get_row($table,$id,$id_val){
		$query = $this->db->get_where($table, array($id => $id_val));
		foreach ($query->result() as $row)
		{
				return $row;
		}
		return null;
	}
	private function show($module  = ''){
		$this->crud->set_theme('twitter-bootstrap')->unset_export();
		$output = $this->crud->render();
		$this->load->view('ci_simplicity/admin',$output);
	}

	function index(){
	    $fk= $this->session->userdata('fk');
	    $this->output->set_title('Halaman Pengguna');
		$this->crud->set_table('transaksi_paket');
		$this->crud->set_subject('Paket Pembelian')->columns('kode','harga','kredit')->where(array('jamaah'=>$fk))->unset_edit()->unset_add()->unset_delete();
		$this->show();
		
	}
	function unique_field_name($field_name) {
	    return 's'.substr(md5($field_name),0,8); //This s is because is better for a string to begin with a letter and not with a number
    }
	function _jamaah($value,$row){
        $d= (explode("-",$value));
    	return $this->j[$d[0]].'/'.$d[1];
	}
	function agen_leader($fk){
	     $d= $this->db->query("select id_jamaah, nama_jamaah from data_jamaah");
		foreach($d->result() as $row){
		    $this->j[$row->id_jamaah] = $row->nama_jamaah;
		}
		$this->crud->set_table('transaksi_paket');
		$this->crud->set_subject('Paket Pembelian')->columns('jamaah','kode','harga','kredit','kekurangan')->where(array('transaksi_paket.agen'=>$fk))->unset_edit()->unset_add()->unset_delete()->set_relation('jamaah','data_jamaah','{nama_jamaah}-{no_ktp}');
		$this->show();
		
	}
	function agen(){
	     $fk= $this->session->userdata('fk');
	     //echo "fk=$fk";1
	     $d= $this->db->query("select id_jamaah, nama_jamaah from data_jamaah");
		foreach($d->result() as $row){
		    $this->j[$row->id_jamaah] = $row->nama_jamaah;
		}
		$this->crud->set_table('transaksi_paket');
		$this->crud->set_subject('Paket Pembelian')->columns('jamaah','kode','harga','kredit','kekurangan')->where(array('transaksi_paket.agen'=>$fk))->unset_edit()->unset_add()->unset_delete()->set_relation('jamaah','data_jamaah','{nama_jamaah}-{no_ktp}');
		$this->show();
		
	}
	function index_()
	{
	    $paket = 1;
	    $this->crud= new Grocery_CRUD();
	    echo "tes";
	    $fk= $this->session->userdata('fk');
	    echo "<br> fk=$fk";
	    $x = $this->get_row('transaksi_paket','jamaah',$fk);
	    $j =  $this->get_row('data_jamaah','id_jamaah',$fk);
			$dolar = $this->get('data_jamaah_paket','id',$x->paket_umroh,'harga_dolar');
			$kurs = $this->main_model->get_kurs();
			$s.=$dolar.'<br>';
			$r=ceil($kurs*$dolar/1000)*1000;
			$this->r = $r;
			$s.='Rp.'.$this->main_model->uang($r);
			$this->grocery_crud->callback_add_field('harga',array($this,'harga_field_callback_1'));
			$this->crud->set_table('transaksi_paket')->set_subject('Pembelian paket umroh '.$s)->set_relation('jamaah','data_jamaah','{nama_jamaah}-{no_ktp}','nama_jamaah <> ""')->columns('jamaah','harga','kredit','kekurangan','debet','saldo','kode','agen');
			$this->crud->field_type('kode', 'readonly');
			// $this->crud->set_relation('paket_umroh','data_jamaah_paket','estimasi_keberangkatan');
			$this->crud->callback_column('kekurangan',array($this,'_kekurangan'));
			$this->crud->callback_column('debet',array($this,'__debet'));
			$this->crud->callback_column('kredit',array($this,'__kredit'));
			$this->crud->set_relation('agen','data_jamaah_agen','nama');
			$this->crud->add_fields(array('jamaah','harga','paket_umroh','kekurangan','harga_normal','agen'));
			$this->crud->callback_before_insert(array($this,'_update_kekurangan'));
			$this->crud->field_type('harga_normal', 'hidden', $r);
			$this->crud->field_type('kekurangan', 'hidden', $r);
			$this->crud->field_type('paket_umroh', 'hidden', $paket);
			$this->crud->data['-tes']='-';
			
			$this->crud->callback_after_insert(array($this, 'fix_code_after_insert'))->where(array('paket_umroh'=>$paket));
	}


	function log(){
		
	}
	
}
// END Kelas Class

/* End of file kelas.php */
/* Location: ./system/application/controllers/kelas.php */