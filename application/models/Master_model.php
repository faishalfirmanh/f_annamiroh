<?php
/**
 * Login_model Class
 *
 * @author	Awan Pribadi Basuki <awan_pribadi@yahoo.com>
 */
class Master_model extends CI_Model {
	/**
	 * Constructor
	 */
	function __construct(){
		parent::__construct();
	}
	//var $table = 'kecamatan';
	
	//Kecamatan
	
	function update_kecamatan($id_kecamatan, $kecamatans){
		//log 
		$kecamatans['id_kecamatan'] = $id_kecamatan;
		$this->db->insert('kecamatan_log', array('aksi'=>'Update', 
			'nama_user'=>$this->session->userdata('nama_admin'), 
			'id_user'=>$this->session->userdata('id_admin'),  
			'data_lama'=>json_encode($this->get_kabupaten_by_kecamatan($id_kecamatan)->row_array()),
			'data_baru'=>json_encode($kecamatans)));
		//end log
		$this->db->where('id_kecamatan', $id_kecamatan);
		$this->db->update('kecamatan', $kecamatans);
	}
	
	function get_kabupaten_by_kecamatan($id){
		$this->db->select('*');
		$this->db->from('kecamatan');
		$this->db->where('id_kecamatan', $id);
		return $this->db->get();
	}
	
	function add_kecamatan($kecamatan){
		$this->db->insert('kecamatan', $kecamatan);
				//log 
		$this->db->insert('kecamatan_log', array('aksi'=>'Tambah', 
			'nama_user'=>$this->session->userdata('nama_admin'), 
			'id_user'=>$this->session->userdata('id_admin'),  
			'data_baru'=>json_encode($kecamatan)));
		//end log
	}
	
	function valid_kecamatan($id_kabupaten, $kecamatan)
	{
		$query = $this->db->query("SELECT id_kecamatan FROM kecamatan WHERE id_kabupaten='".$id_kabupaten."' AND (kecamatan LIKE '%".$kecamatan."%')");
		if ($query->num_rows() > 0)
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}
	
	function get_last_ten_kecamatan($id_kabupaten){
		$this->db->select('*');
		$this->db->from('kecamatan');
		$this->db->where('id_kabupaten', $id_kabupaten);
		$this->db->order_by('kecamatan', 'asc');
		return $this->db->get();
	}
	
	//Kabupaten
	function valid_kabupaten($kabupaten)
	{
		$query = $this->db->query("SELECT kabupaten FROM kabupaten WHERE kabupaten LIKE '%".$kabupaten."%'");
		if ($query->num_rows() > 0)
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}
	
	function get_kabupaten()
	{
		$this->db->order_by('kabupaten');
		return $this->db->get('kabupaten');
	}
	
	function update_kabupaten($id, $kabupaten){
		$kabupaten['id_kabupaten'] = $id;
		//log 
		$this->db->insert('kabupaten_log', array('aksi'=>'Update', 
			'nama_user'=>$this->session->userdata('nama_admin'), 
			'id_user'=>$this->session->userdata('id_admin'),  
			'data_lama'=>json_encode($this->get_kabupaten_by_id($id)->row_array()),
			'data_baru'=>json_encode($kabupaten)));
		//end log
		$this->db->where('id_kabupaten', $id);
		$this->db->update('kabupaten', $kabupaten);
	}
	
	function get_kabupaten_by_id($id){
		$this->db->select('*');
		$this->db->from('kabupaten');
		$this->db->where('id_kabupaten', $id);
		return $this->db->get();	
	}
	
	function add_kabupaten($kabupaten){
		$this->db->insert('kabupaten', $kabupaten);
				//log 
		$this->db->insert('kabupaten_log', array('aksi'=>'Tambah', 
			'nama_user'=>$this->session->userdata('nama_admin'), 
			'id_user'=>$this->session->userdata('id_admin'),  
			'data_baru'=>json_encode($kabupaten)));
		//end log
	}
	
	function count_all_num_rows(){
		return $this->db->count_all('kabupaten');
	}
	
	function get_last_ten_kabupaten($limit, $offset){
		$this->db->select('*');
		$this->db->from('kabupaten');
		$this->db->order_by('kabupaten', 'asc');
		$this->db->limit($limit, $offset);
		return $this->db->get();
	}
	
	function get_kecamatan()
	{
		$this->db->order_by('kecamatan');
		return $this->db->get('kecamatan');
	}
}
// END Login_model Class

/* End of file login_model.php */ 
/* Location: ./system/application/model/login_model.php */