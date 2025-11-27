<?php
/**
 * Login_model Class
 *
 * @author	Awan Pribadi Basuki <awan_pribadi@yahoo.com>
 */
class Cari_model extends CI_Model {
	/**
	 * Constructor
	 */
	function __construct()
	{
		parent::__construct();
	}
	var $table = 'data_jamaah';
	
	function get_last_ten_jamaah($limit, $offset){
		$this->db->select('data_jamaah.id_jamaah, data_jamaah.nama_jamaah');
		$this->db->from('data_jamaah');
		$this->db->order_by('data_jamaah.nama_jamaah', 'asc');
		$this->db->limit($limit, $offset);
		return $this->db->get();
	}
	
	function add($jamaah){
		$this->db->insert($this->table, $jamaah);
				//log 
		$this->db->insert($this->table.'_log', array('aksi'=>'Tambah', 
			'nama_user'=>$this->session->userdata('nama_admin'), 
			'id_user'=>$this->session->userdata('id_admin'),  
			'data_baru'=>json_encode($jamaah)));
		//end log
	}
	
	function get_jamaah_by_id($id){
		return $this->db->get_where($this->table, array('id_jamaah' => $id))->row();	
	}
	
	function update($id, $jamaah){
		$jamaah['id'] = $id;
		//log 
		$this->db->insert($this->table.'_log', array('aksi'=>'Update', 
			'nama_user'=>$this->session->userdata('nama_admin'), 
			'id_user'=>$this->session->userdata('id_admin'),  
			'data_lama'=>json_encode($this->get_jamaah_by_id($id)->row_array()),
			'data_baru'=>json_encode($jamaah)));
		//end log
		$this->db->where('id_jamaah', $id);
		$this->db->update($this->table, $jamaah);
	}
	
	function delete($id){
		//log
		$this->db->insert($this->table.'_log', 
			array('aksi'=>'Delete', 
				'nama_user'=>$this->session->userdata('nama_admin'), 
				'id_user'=>$this->session->userdata('id_admin'),  
				'data_lama'=>json_encode(
					array(
						$this->table=>$this->get_jamaah_by_id($id)->row_array()
					)
				)
			)
		);
		//end log
		$this->db->delete($this->table, array('id_jamaah' => $id));
	}
}
// END Login_model Class

/* End of file login_model.php */ 
/* Location: ./system/application/model/login_model.php */