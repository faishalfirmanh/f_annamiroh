<?php
/**
 * Login_model Class
 *
 * @author	Awan Pribadi Basuki <awan_pribadi@yahoo.com>
 */
class User_model extends CI_Model {
	/**
	 * Constructor
	 */
	function __construct()
	{
		parent::__construct();
	}
	
	function reset_pwd($id, $new_password){
		$this->db->where('id_admin', $id);
		$this->db->update('admin', $new_password);
	}
	
	function get_admin_by_id($id){
		$this->db->select('*');
		$this->db->from('admin');
		$this->db->where('id_admin', $id);
		return $this->db->get();	
	}
	
	function count_all_num_rows(){
		return $this->db->count_all('admin');
	}
	
	function get_last_ten_user($limit, $offset){
		$this->db->select('*');
		$this->db->from('admin');
		$this->db->where('level', 2);
		$this->db->order_by('nama_admin', 'asc');
		$this->db->limit($limit, $offset);
		return $this->db->get();
	}
}
// END Login_model Class

/* End of file login_model.php */ 
/* Location: ./system/application/model/login_model.php */