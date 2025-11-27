<?php
/**
 * Login_model Class
 *
 * @author	Awan Pribadi Basuki <awan_pribadi@yahoo.com>
 */
class Admin_model extends CI_Model {
	/**
	 * Constructor
	 */
	function __construct()
	{
		parent::__construct();
	}
	
	function count_jamaah_by_lokasi($kec, $kab, $th){
		$and = '';
		if (!empty($kec)){
			$and.= ' AND data_jamaah.id_kecamatan = '.$kec;
		} 
		if (!empty($kab)){
			$and.= ' AND kecamatan.id_kabupaten = '.$kab;
		} 
		if (!empty($th)){
			$and.= ' AND tahun = '.$th;
		}

		return $this->db->query('SELECT COUNT(id_jamaah) AS id_jamaah FROM data_jamaah INNER JOIN kecamatan ON data_jamaah.id_kecamatan=kecamatan.id_kecamatan '.$and);

	}
	
	function cari_jamaah_by_lokasi($kec, $kab, $th, $limit, $offset){
		
		$this->db->select('*');
		$this->db->from('data_jamaah');
		$this->db->join('kecamatan', 'data_jamaah.id_kecamatan=kecamatan.id_kecamatan');
		if (!empty($kec)){
			$this->db->where('data_jamaah.id_kecamatan = '.$kec);
		}
		
		if (!empty($kab)){
			$this->db->where('kecamatan.id_kabupaten = '.$kab);
		} 
		
		if (!empty($th)){
			$this->db->where('tahun = '.$th);
		}
		
		$this->db->order_by('nama_jamaah', 'asc');
		$this->db->limit($limit, $offset);
		return $this->db->get();
	}
}
// END Login_model Class

/* End of file login_model.php */ 
/* Location: ./system/application/model/login_model.php */