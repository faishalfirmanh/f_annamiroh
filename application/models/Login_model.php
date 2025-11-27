<?php
/**
 * Login_model Class
 *
 * @author	Awan Pribadi Basuki <awan_pribadi@yahoo.com>
 */
class Login_model extends CI_Model {
	/**
	 * Constructor
	 */
	function  __construct() {
		parent::__construct();
	}
	
	// Inisialisasi nama tabel user
	var $table = 'admin';
	
	/**
	 * Cek tabel user, apakah ada user dengan username dan password tertentu
	 */
	 function get_row($table,$id,$id_val){
		$query = $this->db->get_where($table, array($id => $id_val));
		foreach ($query->result() as $row)
		{
				return $row;
		}
		return null;
	}
	function check_user($username, $password)
	{
		// $query = $this->db->get_where($this->table, array('username' => $username, 'blokir' => 0), 1, 0);
		$query = $this->db->get_where($this->table, array('username' => $username, 'password' => $password, 'blokir' => 0), 1, 0);
		
		if ($query->num_rows() > 0)
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}
	
	function get_admin($username, $password){
	    
	     
		$x =  $this->db->get_where($this->table, array('username' => $username, 'password' => $password))->row();
	//	$s = $this->get_row('transaksi_paket','jamaah',$x->fk);
	   
	   // print_r($x);
	   // print_r($j);
	    if($x->level == 6){
	         $j =  $this->get_row('data_jamaah','id_jamaah',$x->fk);
	        $x->nama_admin=$j->nama_jamaah;
	    }
	    //exit();
	    if($x->level == 5 || $x->level == 4){
	        $j =  $this->get_row('data_jamaah_agen','id',$x->fk);
	        $x->nama_admin=$j->nama;
	    }
	    return $x;
	}
}
// END Login_model Class

/* End of file login_model.php */ 
/* Location: ./system/application/model/login_model.php */