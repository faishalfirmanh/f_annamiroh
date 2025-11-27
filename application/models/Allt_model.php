<?php
/**
 * Login_model Class
 *
 * @author	Awan Pribadi Basuki <awan_pribadi@yahoo.com>
 */
class Allt_model extends CI_Model {
	/**
	 * Constructor
	 */
	function __construct()
	{
		parent::__construct();
	}
	var $table = 'semua_data1';
	
	function update_kredit($id, $adm){
		
		$adm['id'] = $id;
		//log 
		$this->db->insert($this->table.'_log', array('aksi'=>'Update', 
			'nama_user'=>$this->session->userdata('nama_admin'), 
			'id_user'=>$this->session->userdata('id_admin'),  
			'data_lama'=>json_encode($this->db->get_where($this->table, array('id_semua_data' => $id))->row_array()),
			'data_baru'=>json_encode($adm)));
		//end log
		$this->db->where('id_semua_data', $id);
		unset($adm['id']);
		$this->db->update($this->table, $adm);
	}
	
	function get_all_by_id($id){
		return $this->db->get_where($this->table, array('id_semua_data' => $id))->row();	
	}
	
	function del_allt($id){
		//log
		$this->db->insert($this->table.'_log', 
			array('aksi'=>'Delete', 
				'nama_user'=>$this->session->userdata('nama_admin'), 
				'id_user'=>$this->session->userdata('id_admin'),  
				'data_lama'=>json_encode(
					array(
						$this->table=>$this->db->get_where($this->table, array('id_semua_data' => $id))->row_array()
					)
				)
			)
		);
		//end log
		$this->db->delete($this->table, array('id_semua_data' => $id));
	}
	
	function insert($adm){
		$this->db->insert($this->table, $adm);
				//log 
		$this->db->insert($this->table.'_log', array('aksi'=>'Tambah', 
			'nama_user'=>$this->session->userdata('nama_admin'), 
			'id_user'=>$this->session->userdata('id_admin'),  
			'data_baru'=>json_encode($adm)));
		//end log
	}
	
	function count_all_num_rows(){
		$query = $this->db->query('SELECT id_semua_data FROM semua_data1');
		return $query->num_rows();
	}
	
	function get_last_ten_allt($limit, $offset){
		$this->db->select('*');
		$this->db->from('semua_data1');
		$this->db->join('admin', 'admin.id_admin=semua_data1.id_admin');
		$this->db->order_by('tgl_bayar', 'desc');
		$this->db->order_by('id_semua_data', 'desc');
		$this->db->limit($limit, $offset);
		return $this->db->get();
	}
	

	function total($id){
		return $this->db->query('SELECT SUM(nominal) AS total FROM semua_data1');
	}
}
// END Login_model Class

/* End of file login_model.php */ 
/* Location: ./system/application/model/login_model.php */