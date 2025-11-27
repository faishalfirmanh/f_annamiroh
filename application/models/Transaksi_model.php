<?php
/**
 * Login_model Class
 *
 * @author	Awan Pribadi Basuki <awan_pribadi@yahoo.com>
 */
class Transaksi_model extends CI_Model {
	/**
	 * Constructor
	 */
	function __construct()
	{
		parent::__construct();
	}
	var $table = 'data_jamaah';
	
function count_all_num_rows(){
		return $this->db->count_all($this->table);
	}
	function get_data_jamaa($id){
		return $this->db->get_where($this->table, array('id_jamaah' => $id))->row();	
	}
}
// END Login_model Class

/* End of file login_model.php */ 
/* Location: ./system/application/model/login_model.php */