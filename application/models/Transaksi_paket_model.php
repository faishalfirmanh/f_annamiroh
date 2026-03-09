<?php
/**
 * Login_model Class
 *
 * @author	Awan Pribadi Basuki <awan_pribadi@yahoo.com>
 */
class Transaksi_paket_model extends CI_Model {
	/**
	 * Constructor
	 */
	function __construct()
	{
		parent::__construct();
	}
	var $table = 'transaksi_paket';

    
	public function get_single_transaksi_paket($id_jamaah,$id_paket)
    {
        $this->db->from($this->table);
        $this->db->where('paket_umroh', $id_paket);

        $this->db->group_start();
            $this->db->where('agen', $id_jamaah);
            $this->db->or_where('jamaah', $id_jamaah);
        $this->db->group_end();

        $query = $this->db->get();

        return $query->row();
    }
    
}
// END Login_model Class

/* End of file login_model.php */ 
/* Location: ./system/application/model/login_model.php */