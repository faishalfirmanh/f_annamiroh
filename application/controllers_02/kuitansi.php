<?php
/**
 * Kelas Class
 *
 * @author	Awan Pribadi Basuki <awan_pribadi@yahoo.com>
 */
class Kuitansi extends CI_Controller {
	/**
	 * Constructor
	 */
	 var $t = array();
	 var $r = 0;
	 var $crud = null;
	 var $j = array();
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
		// $this->load->library('grocery_CRUD');
		// $this->_init();
	}

	/**
	 * Memeriksa user state, jika dalam keadaan login akan menampilkan halaman kelas,
	 * jika tidak akan meredirect ke halaman login
	 */
	function index()
	{
		redirect('master/jamaah');
	}
	
	function get_row($table,$id,$id_val){
		$query = $this->db->get_where($table, array($id => $id_val));
		foreach ($query->result() as $row)
		{
				return $row;
		}
		return null;
	}
	function get($table,$id,$id_val,$kolom){
		$query = $this->db->get_where($table, array($id => $id_val));
		foreach ($query->result() as $row)
		{
				return $row->$kolom;
		}
		return null;
	}

	function _harga_rp($value,$row){
		return $this->main_model->get_kurs()*$row->harga_dolar;
	}
	private function get_sum($id_transaksi_paket,$harga){

		$d= $this->db->query("select kredit,debet from pembayaran_transaksi_paket where  id_transaksi_paket = $id_transaksi_paket");
		$debet = 0;
		$kredit = 0;
		foreach($d->result() as $row){
			$debet += $row->debet;
			$kredit += $row->kredit;
		}
		// return array('debet'=>$debet,'kredit'=>$kredit);
		$saldo = $kredit - $debet;
		$this->db->update('transaksi_paket',array('kekurangan'=>($harga - $kredit),'debet'=>$debet,'kredit'=>$kredit,'saldo'=>$saldo),array('id'=>$id_transaksi_paket));
		// $this->db->update('transaksi_paket',array('kode'=>base_convert($primary_key,10,36)),array('id'=>$primary_key));
		return array($debet,$kredit);
	}
	function kredit($id_kuitansi){
		$j = $this->get_row('pembayaran_transaksi_paket','id',$id_kuitansi);
		$untuk = $this->get('jenis_transaksi','id',$j->jenis_transaksi,'nama_transaksi');
		$teller = $this->get('admin','id_admin',$j->teller,'nama_admin');
		$k = $this->get_row('transaksi_paket','id',$j->id_transaksi_paket);
		$jamaah = $this->get('data_jamaah','id_jamaah',$k->jamaah,'nama_jamaah');
		$p = $this->get_row('data_jamaah_paket','id',$k->paket_umroh);
		$paket = $p->estimasi_keberangkatan;
		$harga = $p->harga;
		list($debet,$kredit) = $this->get_sum($j->id_transaksi_paket,$harga);
		$this->load->view('kuitansi/main',array('jamaah'=>$jamaah,'paket'=>$paket,'jumlah'=>$j->kredit,'banyaknya'=>$this->main_model->uang($j->kredit),'untuk'=>$untuk,'no'=>$j->id,'tanggal'=>$j->tanggal,'teller'=>$teller));
	}	
	
}
