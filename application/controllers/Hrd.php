<?php

/**
 * Kelas Class
 *
 * @author	Moch Yasin
 */
class Hrd extends CI_Controller
{
	/**
	 * Constructor
	 */
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
	}
	private function show($module  = '')
	{
		$this->crud->set_theme('twitter-bootstrap');
		$output = $this->crud->render();
		
		// $output->meta_keywords = "Something 2";
		$this->load->view('ci_simplicity/admin', $output);
	}


	
    function index(){
        $this->crud->set_table('surat_rekom_paspor')->unset_add()->unset_delete()->unset_export()->unset_print()
        ->unset_columns('jamaah_id')
        ->unset_edit()->set_relation('user_id','admin','nama_admin')->set_relation('imigrasi','ref_imigrasi','nama_imigrasi')
        ->display_as('created_at','Tanggal dibuat')->display_as('nomor_urut','Nomor Surat')->order_by('nomor_urut','desc')
        ->display_as('jamaah_id','Nama Jamaah')->display_as('user_id','Pembuat');
        $this->show();
    }
    function cs_pendaftaran(){  //data master, order by insert date asc
        $this->crud->set_table('data_jamaah')->unset_add()->unset_delete()->unset_print()->columns('user_id','agen','nama_jamaah','tgl_lahir','created_at','no_tlp','hp_jamaah','alamat_jamaah')
       // ->unset_columns('jamaah_id')
        ->unset_edit()->set_relation('user_id','admin','nama_admin')->set_relation('agen','data_jamaah','nama_jamaah')
        ->display_as('created_at','Tanggal dibuat')->order_by('created_at','desc')
        //->display_as('jamaah_id','Nama Jamaah')->display_as('user_id','Pembuat')
        ;
        $this->show();
    }
    
    function get($table, $id, $id_val, $kolom)
	{
		$query = $this->db->get_where($table, array($id => $id_val));
		foreach ($query->result() as $row) {
			return $row->$kolom;
		}
		return null;
	}
    function cs_transaksi(){
		$this->crud->set_table('pembayaran_transaksi_paket');
		$this->crud->unset_read()->columns('jenis_transaksi', 'keterangan', 'tanggal', 'tanggal_transfer', 'kredit', 'debet', 'teller','bukti');
		$this->crud->display_as('jenis_transaksi', 'Jenis Transaksi')
			->display_as('tanggal_transfer', 'Tgl Transfer')
			->display_as('kredit', 'Kredit (IDR)')
			->display_as('debet', 'Debit (IDR)');
			$this->crud	->set_relation('jenis_transaksi', 'jenis_transaksi_pengeluaran', 'nama_transaksi')
			->unset_texteditor('keterangan');
		$state = $this->crud->getState();
			$this->crud->set_relation('teller', 'admin', 'nama');
		$this->crud->callback_delete([$this, '_delete_kredit']);
		$this->crud->where('deleted', null);
		$this->crud->fields('id_transaksi_paket', 'jenis_transaksi', 'tanggal', 'tanggal_transfer', 'kredit', 'keterangan', 'teller','bukti')->unset_edit();
		//$this->crud->set_top("Transaksi Kredit $jamaah | Paket :$paket | Harga: ". $harga . " | Pembayaran:".$this->format_rp($kredit). " | Kekurangan: " . $kurang . "<br>Transaksi Debet : $debet");
        $this->crud->set_field_upload('bukti', 'assets/uploads/bukti');

        
        $this->show();
    }

	function log()
	{
	}
}
// END Kelas Class

/* End of file kelas.php */
/* Location: ./system/application/controllers/kelas.php */