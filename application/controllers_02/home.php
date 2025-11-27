<?php
/**
 * Login Class
 *
 * @author	Awan Pribadi Basuki <awan_pribadi@yahoo.com>
 */
class Home extends CI_Controller {
	/**
	 * Constructor
	 */
	function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->load->model('main_model', '', TRUE);
	}
	
	/**
	 * Memeriksa user state, jika dalam keadaan login akan menampilkan halaman absen,
	 * jika tidak akan meload halaman login
	 */
	function index()
	{
		$this->home();
	}
	function login(){
	 	$this->load->view('login/login_view');   
	}
	function home(){
	    $data = array();
	    $q=$this->db->query("select estimasi_keberangkatan,program,penerbangan,hotel_makkah,hotel_madinah,harga as harga_dolar from data_jamaah_paket where ket='AKTIF'");
	    $data['paket'] = $q->result();
	    $q=$this->db->query("select * from data_maskapai");
	    $data['maskapai'] = $q->result();
	    $q=$this->db->query("select * from data_hotel");
	    $data['hotel'] = $q->result();
	    $data['kurs']=$this->main_model->get_kurs();
	    $q=$this->db->query("select * from web_konten where tampil = 1 and CURDATE() <= waktu_tayang");
	    $konten = array();
	    foreach( $q->result() as $r){
	        //print_r($r);
	        $konten[$r->kategori][]=array('judul'=>$r->judul,'isi'=>$r->isi,'file'=>$r->file);
	    }
	    $data['konten']=$konten;
		$this->load->view('frontend/index',$data);
	}
}
// END Login Class

/* End of file login.php */
/* Location: ./system/application/controllers/login.php */
