<?php
/**
 * Login_model Class
 *
 * @author	Moch Yasin <yasin@saya.me>
 */
class log_model extends CI_Model {
	/**
	 * Constructor
	 */
	function __construct()
	{
		parent::__construct();
	}
	var $table = 'data_log';

	function count_all_num_rows($tabel){
		return $this->db->query('SELECT COUNT(id) AS id_log FROM '.$tabel);
	}
	
	function get_last_ten_log($tabel,$limit, $offset){
		$this->db->select('*');
		$this->db->from($tabel);
		$this->db->limit($limit, $offset);
		return $this->db->get();
	}
	
	
	function get_log_by_id($tabel,$id){
		$this->db->select('*');
		$this->db->from($tabel);
		$this->db->where('id', $id);
		return $this->db->get();	
	}

}
// END Login_model Class

/* End of file login_model.php */ 
/* Location: ./system/application/model/login_model.php */