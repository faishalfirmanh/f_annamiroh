<?php
/**
 * Login_model Class
 *
 * @author	Moch yasin
 */
class Main_model extends CI_Model {
	/**
	 * Constructor
	 */
	function __construct()
	{
		parent::__construct();
	}
	var $table = 'kbih';
	
	function get_menu($group = 1){

		$query = $this->db->query('SELECT link,menu,kategori, is_hidden FROM page_akses WHERE aktif=1 and `group` ='.$group);
		// var_dump($query->result()); die();
		$hasil = array();
		$berhak = array();
		foreach($query->result() as $row){
			$hasil[$row->kategori][]=array($row->link, $row->menu,$row->is_hidden );
			$berhak[$row->link] = 1;
		}
			$query = $this->db->query('SELECT link,menu from user_shortcut where `level` ='.$group .' order by urutan');
		foreach($query->result() as $row){
			$hasil['shortcut'][]=array($row->link, $row->menu);
			$berhak[$row->link] = 1;
		}	
		

		$cek = $this->uri->segment(1).'/'.$this->uri->segment(2);
		//echo "Cek:".$cek;
		if(isset($berhak[$cek]) || $cek == 'transaksi_op/receiver_debit_update' || $cek == 'transaksi/pembayaran_invoice'){
		    if( 
				$berhak[$cek]!=1 
				&& $cek != 'transaksi_op/receiver_debit_update' 
				&& $cek != 'transaksi/pembayaran_invoice'){
		        redirect('home');
		    }
		}
		else{
		    redirect('home');
		}
		$hasil['nama'] = $this->session->userdata('nama_admin');

		$hasil = $this->hidden_menu($hasil);
		
		return $hasil;
	}

	function hidden_menu($hasil){
		$i = 0;
		foreach($hasil as $k1 => $h ){
			if(count($hasil) - 1 > $i){
				if(isset($h)){
					foreach($h as $k2 => $s){
						if(isset($s[2])){
							if($s[2] == 1){
								unset($hasil[$k1][$k2]);
							}
						}
					}
					$i++;
				}
			}
			
		}

		return $hasil;
	}
	public function log_activity($user_id, $activity) {
        $data = array(
            'user_id' => $user_id,
            'activity' => $activity,
            'created_at' => date('Y-m-d H:i:s')
        );

        return $this->db->insert('user_activity_logs', $data);
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