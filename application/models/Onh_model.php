<?php
/**
 * Login_model Class
 *
 * @author	Awan Pribadi Basuki <awan_pribadi@yahoo.com>
 */
class Onh_model extends CI_Model {
	/**
	 * Constructor
	 */
	function __construct()
	{
		parent::__construct();
	}
	var $table = 'tb_pembayaran';
	var $table1 = 'tb_pembayaran_umroh';
	
	function get_last_pembayaran($id){
		return $this->db->query('SELECT angsuran_ke FROM tb_pembayaran WHERE id_jamaah ='.$id.' AND nominal!=0 ORDER BY angsuran_ke DESC LIMIT 1');
	}
	
	function total_debet($id){
		return $this->db->query('SELECT SUM(debet) AS debet FROM tb_pembayaran WHERE id_jamaah ='.$id.' AND debet!=0');
	}
	
	function count_debet($id){
		$query = $this->db->query('SELECT id_jamaah FROM tb_pembayaran WHERE id_jamaah ='.$id.' AND debet!=0');
		return $query->num_rows();
	}
	
	function debet($id, $limit, $offset){
		$this->db->select('*');
		$this->db->from('tb_pembayaran');
		$this->db->join('admin', 'admin.id_admin=tb_pembayaran.id_admin');
		$this->db->where('id_jamaah = '.$id.' AND debet!=0');
		$this->db->order_by('tgl_bayar', 'desc');
		$this->db->order_by('id_pembayaran', 'desc');
		$this->db->limit($limit, $offset);
		return $this->db->get();
	}
	
	function del_onh($id){
		//log
		$this->db->insert($this->table.'_log', 
			array('aksi'=>'Delete', 
				'nama_user'=>$this->session->userdata('nama_admin'), 
				'id_user'=>$this->session->userdata('id_admin'),  
				'data_lama'=>json_encode(
					array(
						$this->db->get_where($this->table, array('id_pembayaran' => $id))->row_array()
					)
				)
			)
		);
		//end log
		$this->db->delete($this->table, array('id_pembayaran' => $id));
	}
	
	function update_kredit($id, $onh){
		$onh['id'] = $id;
		//log 
		$this->db->insert($this->table.'_log', array('aksi'=>'Update', 
			'nama_user'=>$this->session->userdata('nama_admin'), 
			'id_user'=>$this->session->userdata('id_admin'),  
			'data_lama'=>json_encode($this->db->get_where($this->table, array('id_pembayaran' => $id))->row_array()),
			'data_baru'=>json_encode($onh)));
		//end log
		$this->db->where('id_pembayaran', $id);
		$this->db->update($this->table, $onh);
	}
	
	function get_pembayaran_by_id($id){
		return $this->db->get_where($this->table, array('id_pembayaran' => $id))->row();	
	}
	
	
	function add($onh){
		$this->db->insert($this->table, $onh);
				//log 
		$this->db->insert($this->table.'_log', array('aksi'=>'Tambah', 
			'nama_user'=>$this->session->userdata('nama_admin'), 
			'id_user'=>$this->session->userdata('id_admin'),  
			'data_baru'=>json_encode($onh)));
		//end log
	}
	function add_umroh($onh,$modul=1){
		$this->db->insert($this->table1, $onh);
				//log 
		$this->db->insert($this->table1.'_log', array('aksi'=>'Tambah', 
			'nama_user'=>$this->session->userdata('nama_admin'), 
			'id_user'=>$this->session->userdata('id_admin'),  
			'data_baru'=>json_encode($onh)));
		//end log
	}
	
	function count_all_num_rows($id){
		$query = $this->db->query('SELECT id_jamaah FROM tb_pembayaran WHERE id_jamaah ='.$id);
		return $query->num_rows();
	}
	
	function count_kredit($id){
		$query = $this->db->query('SELECT id_jamaah FROM tb_pembayaran WHERE id_jamaah ='.$id.' AND nominal!=0');
		return $query->num_rows();
	}
	
	function kredit($id, $limit, $offset){
		$this->db->select('*');
		$this->db->from('tb_pembayaran');
		$this->db->join('admin', 'admin.id_admin=tb_pembayaran.id_admin');
		$this->db->where('id_jamaah = '.$id.' AND nominal!=0');
		$this->db->order_by('tgl_bayar', 'desc');
		$this->db->order_by('id_pembayaran', 'desc');
		$this->db->limit($limit, $offset);
		return $this->db->get();
	}
	function kredit_umroh($id, $limit, $offset,$modul){
		$this->db->select('*');
		$this->db->from('tb_pembayaran_umroh');
		$this->db->join('admin', 'admin.id_admin=tb_pembayaran_umroh.id_admin');
		$this->db->where('id_jamaah = '.$id.' AND nominal!=0 and jenis='.$modul);
		$this->db->order_by('tgl_bayar', 'desc');
		$this->db->order_by('id_pembayaran', 'desc');
		$this->db->limit($limit, $offset);
		return $this->db->get();
	}
	
	function get_last_ten_onh($id, $limit, $offset){
		$this->db->select('*');
		$this->db->from('tb_pembayaran');
		$this->db->join('admin', 'admin.id_admin=tb_pembayaran.id_admin');
		$this->db->where('id_jamaah = '.$id);
		$this->db->order_by('tgl_bayar', 'desc');
		$this->db->order_by('id_pembayaran', 'desc');
		$this->db->limit($limit, $offset);
		return $this->db->get();
	}
	
	function total_kredit($id){
		return $this->db->query('SELECT SUM(nominal) AS kredit FROM tb_pembayaran WHERE id_jamaah ='.$id);
	}
	function total_kredit_umroh($id,$modul){
		return $this->db->query('SELECT SUM(nominal) AS kredit FROM tb_pembayaran_umroh WHERE id_jamaah ='.$id.' and jenis = '.$modul);
	}
	
	function total($id){
		return $this->db->query('SELECT SUM(nominal)-SUM(debet) AS total FROM tb_pembayaran WHERE id_jamaah ='.$id);
	}
}
// END Login_model Class

/* End of file login_model.php */ 
/* Location: ./system/application/model/login_model.php */