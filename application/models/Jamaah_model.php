<?php
/**
 * Login_model Class
 *
 * @author	Awan Pribadi Basuki <awan_pribadi@yahoo.com>
 */
class Jamaah_model extends CI_Model {
	/**
	 * Constructor
	 */
	function __construct()
	{
		parent::__construct();
	}
	var $table = 'data_jamaah';
	
	function valid_no_porsi($no_porsi)
	{
		$query = $this->db->get_where('data_jamaah', array('no_porsi' => $no_porsi));
		if ($query->num_rows() > 0)
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}


public function get_nama_estimasi_keberangkatan($jamaah_id)
{
    return $this->db
        ->select('data_jamaah_paket.estimasi_keberangkatan')
        ->from('transaksi_paket')
        ->join(
            'data_jamaah_paket',
            'transaksi_paket.paket_umroh = data_jamaah_paket.id',
            'inner'
        )
        ->where('transaksi_paket.jamaah', $jamaah_id)
        ->get()
        ->row(); // atau ->row_array()
}


	function count_all_num_rows(){
		return $this->db->query('SELECT COUNT(id_jamaah) AS id_jamaah FROM data_jamaah INNER JOIN kecamatan ON data_jamaah.id_kecamatan=kecamatan.id_kecamatan');
	}
	
	function get_last_ten_jamaah($limit, $offset){
		$this->db->select('*');
		$this->db->from('data_jamaah');
		$this->db->join('status_jamaah','status_jamaah.id_status=data_jamaah.id_status');
		$this->db->join('kecamatan','kecamatan.id_kecamatan=data_jamaah.id_kecamatan');
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
		$this->db->select('id_jamaah, tahun, data_jamaah.id_status, status_jamaah.status, bank, nama_jamaah, nama_ortu, alamat_ktp, alamat_jamaah, no_tlp, data_jamaah.id_kecamatan, kecamatan.id_kabupaten, no_rekening, no_porsi, tgl_daftar, 	tgl_porsi, tgl_tempo');
		$this->db->from('data_jamaah');
		$this->db->join('status_jamaah','status_jamaah.id_status=data_jamaah.id_status','left');
		$this->db->join('kecamatan','kecamatan.id_kecamatan=data_jamaah.id_kecamatan','left');
		$this->db->join('kabupaten','kabupaten.id_kabupaten=kecamatan.id_kabupaten','left');
		$this->db->where('id_jamaah', $id);
		return $this->db->get();	
	}
	
	function get_pembayaran_by_id($id){
		$this->db->select('*');
		$this->db->from('tb_pembayaran');
		$this->db->where('id_jamaah', $id);
		return $this->db->get();	
	}
	
	function get_adm_by_id($id){
		$this->db->select('*');
		$this->db->from('adm');
		$this->db->where('id_jamaah', $id);
		return $this->db->get();	
	}
	function update($id, $jamaah){
		$jamaah['id_jamaah'] = $id;
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
						'jamaah'=>$this->get_jamaah_by_id($id)->row_array(),
						'tb_pembayaran'=>$this->get_pembayaran_by_id($id)->row_array(),
						'adm'=>$this->get_adm_by_id($id)->row_array()
					)
				)
			)
		);
		//end log
		$this->db->delete($this->table, array('id_jamaah' => $id));
		$this->db->delete('tb_pembayaran', array('id_jamaah' => $id));
		$this->db->delete('adm', array('id_jamaah' => $id));
	}
	
	function cari_jamaah_by_porsi($porsi){
		$query = $this->db->query('SELECT id_jamaah FROM data_jamaah WHERE no_porsi ='.$porsi);
		return $query->num_rows();
	}
	
	function get_jamaah_by_porsi($porsi){
		return $this->db->get_where($this->table, array('no_porsi' => $porsi))->row();
	}
	//new manual
	 public function insert($data)
    {
        return $this->db->insert($this->table, $data);
    }

    public function get_by_id($id)
    {
        return $this->db->get_where($this->table, ['id_jamaah' => $id])->row();
    }

    public function get_imigrasi()
    {
        return $this->db->get('ref_imigrasi')->result();
    }

    public function get_agen()
    {
        return $this->db->get_where('data_jamaah', ['is_agen' => 1])->result();
    }
}
// END Login_model Class

/* End of file login_model.php */ 
/* Location: ./system/application/model/login_model.php */
