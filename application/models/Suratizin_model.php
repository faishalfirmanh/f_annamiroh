<?php


class Suratizin_model extends CI_Model
{
	/**
	 * Constructor
	 */
	function __construct()
	{
		parent::__construct();
	}
	var $table = 'suratIzin';

	

	function add($jamaah)
	{
		$this->db->insert($this->table, $jamaah);
		//log 
		$this->db->insert($this->table . '_log', array(
			'aksi' => 'Tambah',
			'nama_user' => $this->session->userdata('nama_admin'),
			'id_user' => $this->session->userdata('id_admin'),
			'data_baru' => json_encode($jamaah)
		));
		//end log
	}

	public function get_alamat_select($idJamaah)
{
    $sql = "
        SELECT
            p.name AS nama_prov,
            c.name AS nama_kota,
            d.name AS nama_kec,
            v.name AS nama_desa
        FROM data_jamaah j

        LEFT JOIN location_provinces p
            ON p.id = j.location_prov

        LEFT JOIN location_city c
            ON c.id = j.location_city

        LEFT JOIN location_districts d
            ON d.id = j.location_disct

        LEFT JOIN location_villages v
            ON v.id = j.location_village

        WHERE j.id_jamaah = ?
        LIMIT 1
    ";

    $query = $this->db->query($sql, array($idJamaah));

    if ($query->num_rows() == 0) {
        return "Data jamaah tidak ditemukan";
    }

    $row = $query->row();

    $alamat = array();

    if (!empty($row->nama_desa)) {
        $alamat[] = $row->nama_desa;
    }

    if (!empty($row->nama_kec)) {
        $alamat[] = $row->nama_kec;
    }

    if (!empty($row->nama_kota)) {
        $alamat[] = $row->nama_kota;
    }

    if (!empty($row->nama_prov)) {
        $alamat[] = $row->nama_prov;
    }

    return implode(', ', $alamat);
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

}
// END Login_model Class

/* End of file login_model.php */
/* Location: ./system/application/model/login_model.php */
