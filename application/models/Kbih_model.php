<?php
/**
 * Login_model Class
 *
 * @author	Awan Pribadi Basuki <awan_pribadi@yahoo.com>
 */
class Kbih_model extends CI_Model {
	/**
	 * Constructor
	 */
	function __construct()
	{
		parent::__construct();
	}
	var $table = 'kbih';
	
	function update_kredit($id, $kbih){
	$kbih['id'] = $id;
		//log 
		$this->db->insert($this->table.'_log', array('aksi'=>'Update', 
			'nama_user'=>$this->session->userdata('nama_admin'), 
			'id_user'=>$this->session->userdata('id_admin'),  
			'data_lama'=>json_encode($this->db->get_where($this->table, array('id_kbih' => $id))->row_array()),
			'data_baru'=>json_encode($kbih)));
		//end log
		$this->db->where('id_kbih', $id);
		$this->db->update($this->table, $kbih);
	}
	
	function get_kbih_by_id($id){
		return $this->db->get_where($this->table, array('id_kbih' => $id))->row();	
	}
	
	function del_kbih($id){
		//log
		$this->db->insert($this->table.'_log', 
			array('aksi'=>'Delete', 
				'nama_user'=>$this->session->userdata('nama_admin'), 
				'id_user'=>$this->session->userdata('id_admin'),  
				'data_lama'=>json_encode(
					array(
						$this->table=>$this->db->get_where($this->table, array('id_kbih' => $id))->row_array()
					)
				)
			)
		);
		//end log
		$this->db->delete($this->table, array('id_kbih' => $id));
	}
	
	function insert($kbih){
		$this->db->insert($this->table, $kbih);
				//log 
		$this->db->insert($this->table.'_log', array('aksi'=>'Tambah', 
			'nama_user'=>$this->session->userdata('nama_admin'), 
			'id_user'=>$this->session->userdata('id_admin'),  
			'data_baru'=>json_encode($kbih)));
		//end log
	}
	
	function count_all_num_rows($id){
		$query = $this->db->query('SELECT * FROM kbih WHERE id_jamaah ='.$id);
		return $query->num_rows();
	}
	
	function get_last_ten_kbih($id, $limit, $offset){
		$this->db->select('*');
		$this->db->from('kbih');
		$this->db->join('admin', 'admin.id_admin=kbih.id_admin');
		$this->db->where('id_jamaah = '.$id);
		$this->db->order_by('tgl_bayar', 'desc');
		$this->db->order_by('id_kbih', 'desc');
		$this->db->limit($limit, $offset);
		return $this->db->get();
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