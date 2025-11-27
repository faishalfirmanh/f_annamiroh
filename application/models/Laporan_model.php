<?php
/**
 * Login_model Class
 *
 * @author	Moch Yasin
 */
class Laporan_model extends CI_Model {
	/**
	 * Constructor
	 */
	function __construct()
	{
		parent::__construct();
	}
	var $table = 'kbih';
	function get_pendapatan_keberangkatan(){
        /*
        tabel: pembayaran_transaksi_paket(id_transaksi_paket , debet, saldo
        tabel:transaksi_paket: id, paket_umroh. 
        
        */
        $hasil_kodebooking = array();
        $hasil_group = array();
        //inisialisasi for all group set to 0;
        //inisialisasi for all kode booking to 0;
        $query = $this->db->query('select * from v_rekap_keberangkatan_02');
        foreach($query->result() as $row){
			$hasil_kodebooking[$row->paket_umroh] = $row->pemasukan;
		}
        // print_r($hasil_kodebooking);
        return $hasil_kodebooking;
    }
	function get_menu($group = 1){
		$query = $this->db->query('SELECT link,menu,kategori FROM page_akses WHERE aktif=1 and `group` ='.$group);

		$hasil = array();
		$berhak = array();
		foreach($query->result() as $row){
			$hasil[$row->kategori][]=array($row->link, $row->menu);
			$berhak[$row->link] = 1;
		}
			$query = $this->db->query('SELECT link,menu from user_shortcut where `level` ='.$group .' order by urutan');
		foreach($query->result() as $row){
			$hasil['shortcut'][]=array($row->link, $row->menu);
			$berhak[$row->link] = 1;
		}	
		//print_r($berhak);
		$cek = $this->uri->segment(1).'/'.$this->uri->segment(2);
		//echo "Cek:".$cek;
		if(isset($berhak[$cek])){
		    if($berhak[$cek]!=1){
		        redirect('home');
		    }
		}
		else{
		   // echo "sek yo";
		    redirect('home');
		    //redirect($berhak[0]);
		}
		$hasil['nama'] = $this->session->userdata('nama_admin');
		return $hasil;
	}
	function get_kurs(){
		return $this->get('kurs','status',1,'nilai_namiroh');
	}
	function get($table,$id,$id_val,$kolom){
		$query = $this->db->get_where($table, array($id => $id_val));
		foreach ($query->result() as $row)
		{
				return $row->$kolom;
		}
		return null;
	}
	
	function uang($uang){
	  $rp = "";
	  $digit = strlen($uang);
	  
	  while($digit > 3) {
		$rp = "." . substr($uang,-3) . $rp;
		$lebar = strlen($uang) - 3;
		$uang  = substr($uang,0,$lebar);
		$digit = strlen($uang);  
	  }
	  $rp = $uang . $rp . ",-";
	  return $rp;
	}
	
	function total($id){
		return $this->db->query('SELECT SUM(nominal) AS total FROM kbih WHERE id_jamaah ='.$id);
	}
}
// END Login_model Class

/* End of file login_model.php */ 
/* Location: ./system/application/model/login_model.php */