<?php

/**
 * Kelas Class
 *
 * @author	Moch Yasin
 */
class Laporan extends CI_Controller
{
	/**
	 * Constructor
	 */
	var $j = array();
	var $paketnya = array();
	var $group_rev = array();
	var $crud = null;
	var $paket = array();

	function __construct()
	{
		parent::__construct();
		if ($this->session->userdata('login') != TRUE) {
			redirect('login');
		}
		$this->load->database();
		$this->load->helper('url');
		$this->load->model('main_model', '', TRUE);
		$this->load->model('laporan_model', '', TRUE);
		$this->load->library('grocery_CRUD');
		$this->crud = new grocery_CRUD();
		$this->_init();
	}
	private function _init()
	{
		$this->output->set_template('admin');
		$ide = $this->session->userdata('level');
		$this->output->set_output_data('menu', $this->main_model->get_menu($ide));
		$this->load->js('assets/themes/default/js/jquery-1.9.1.min.js');
		$this->load->js('assets/themes/default/hero_files/bootstrap-transition.js');
		$this->load->js('assets/themes/default/hero_files/bootstrap-collapse.js');


		$d = $this->db->query("select id_jamaah, nama_jamaah,no_ktp from data_jamaah");
		foreach ($d->result() as $row) {
			$this->j[$row->id_jamaah] = $row->nama_jamaah . "-" . $row->no_ktp;
		}
		$query = $this->db->query("SELECT id,CONCAT(estimasi_keberangkatan,'-',Program,'-',CAST(FORMAT(harga,2,'de_DE') 
		      AS CHAR CHARACTER SET utf8)) AS detail FROM data_jamaah_paket");
		foreach ($query->result() as $row) {

			$this->paket[$row->id] = $row->detail;
		}
	}
	private function show($module  = '')
	{
		$this->crud->set_theme('twitter-bootstrap');
		$output = $this->crud->render();
		
		// $output->meta_keywords = "Something 2";
		$this->load->view('ci_simplicity/admin', $output);
	}
	function index()
	{
		redirect('master/jamaah');
	}

	function barang_keluar($id_koper_jamaah = 0)
	{


		// START PAKE FILTER

		$param = $this->input->post();
		$data['param'] = $param;
		$whereStringLaporanBarangKeluar = '';

		$dataSesssionLaporanBarangKeluar =  $this->session->userdata('dataSesssionLaporanBarangKeluar');
		$tempWhere = [];

		if (isset($param['tanggal_awal'])) {
			$whereStringLaporanBarangKeluar .= ' AND kp.tanggal_keluar >= "' . $param['tanggal_awal'] . '"';
			$dataSesssionLaporanBarangKeluar['tanggal_awal'] = $param['tanggal_awal'];
		}

		if (isset($param['tanggal_akhir'])) {
			$whereStringLaporanBarangKeluar .= ' AND kp.tanggal_keluar <= "' . $param['tanggal_akhir'] . '"';
			$dataSesssionLaporanBarangKeluar['tanggal_akhir'] = $param['tanggal_akhir'];
		}

		if (isset($param['nama_barang'])) {
			$whereStringLaporanBarangKeluar .= ' AND b.id = ' . $param['nama_barang'];
			$dataSesssionLaporanBarangKeluar['nama_barang'] = $param['nama_barang'];
		}

		if (isset($param['nama_paket'])) {
			$whereStringLaporanBarangKeluar .= ' AND jp.id = ' . $param['nama_paket'];
			$dataSesssionLaporanBarangKeluar['nama_paket'] = $param['nama_paket'];
		}

		if (isset($param['nama_jamaah'])) {
			$whereStringLaporanBarangKeluar .= ' AND j.id_jamaah = ' . $param['nama_jamaah'];
			$dataSesssionLaporanBarangKeluar['nama_jamaah'] = $param['nama_jamaah'];
		}

		if ($whereStringLaporanBarangKeluar != '') {
			$this->session->set_userdata('dataSesssionLaporanBarangKeluar', $dataSesssionLaporanBarangKeluar);
		}

		$sqlTglAwal = '';
		$data['selTglAwal'] = @$param['tanggal_awal'];
		if (@$this->session->userdata('dataSesssionLaporanBarangKeluar')['tanggal_awal'] != 0 && $this->session->userdata('dataSesssionLaporanBarangKeluar')['tanggal_awal'] != null) {
			$data['selTglAwal'] =  $this->session->userdata('dataSesssionLaporanBarangKeluar')['tanggal_awal'];
			$sqlTglAwal = ' AND kp.tanggal_keluar >= "' . $this->session->userdata('dataSesssionLaporanBarangKeluar')['tanggal_awal'] . '"';

			$tempWhere['bk.tanggal_keluar >='] = $this->session->userdata('dataSesssionLaporanBarangKeluar')['tanggal_awal'];
		}

		$sqlTglAkhir = '';
		$data['selTglAkhir'] = @$param['tanggal_akhir'];
		if (@$this->session->userdata('dataSesssionLaporanBarangKeluar')['tanggal_akhir'] != 0 && $this->session->userdata('dataSesssionLaporanBarangKeluar')['tanggal_akhir'] != null) {
			$data['selTglAkhir'] =  $this->session->userdata('dataSesssionLaporanBarangKeluar')['tanggal_akhir'];
			$sqlTglAkhir = ' AND kp.tanggal_keluar <= "' . $this->session->userdata('dataSesssionLaporanBarangKeluar')['tanggal_akhir'] . '"';

			$tempWhere['bk.tanggal_keluar <='] = $this->session->userdata('dataSesssionLaporanBarangKeluar')['tanggal_akhir'];
		}

		// $sqlBarang = '';
		// $data['selBarang'] = @$param['nama_barang'];
		// if (@$this->session->userdata('dataSesssionLaporanBarangKeluar')['nama_barang'] != 0 && $this->session->userdata('dataSesssionLaporanBarangKeluar')['nama_barang'] != null) {
		// 	$data['selBarang'] =  $this->session->userdata('dataSesssionLaporanBarangKeluar')['nama_barang'];
		// 	$sqlBarang = ' AND b.id = ' . $this->session->userdata('dataSesssionLaporanBarangKeluar')['nama_barang'];

		// 	$tempWhere['bk.id_barang'] = $this->session->userdata('dataSesssionLaporanBarangKeluar')['nama_barang'];
		// }

		$sqlPaket = '';
		$data['selPaket'] = @$param['nama_paket'];
		if (@$this->session->userdata('dataSesssionLaporanBarangKeluar')['nama_paket'] != 0 && $this->session->userdata('dataSesssionLaporanBarangKeluar')['nama_paket'] != null) {
			$data['selPaket'] =  $this->session->userdata('dataSesssionLaporanBarangKeluar')['nama_paket'];
			$sqlPaket = ' AND b.id = ' . $this->session->userdata('dataSesssionLaporanBarangKeluar')['nama_paket'];

			$tempWhere['bk.id_paket ='] = $this->session->userdata('dataSesssionLaporanBarangKeluar')['nama_paket'];
		}

		$sqlJamaah = '';
		$data['selJamaah'] = @$param['nama_jamaah'];
		if (@$this->session->userdata('dataSesssionLaporanBarangKeluar')['nama_jamaah'] != 0 && $this->session->userdata('dataSesssionLaporanBarangKeluar')['nama_jamaah'] != null) {
			$data['selJamaah'] =  $this->session->userdata('dataSesssionLaporanBarangKeluar')['nama_jamaah'];
			$sqlJamaah = ' AND b.id = ' . $this->session->userdata('dataSesssionLaporanBarangKeluar')['nama_jamaah'];

			$tempWhere['bk.id_jamaah ='] = $this->session->userdata('dataSesssionLaporanBarangKeluar')['nama_jamaah'];
		}

		// var_dump("sessAtas", $dataSesssionLaporanBarangKeluar);
		// var_dump("sessClick", $this->session->userdata('dataSesssionLaporanBarangKeluar'));
		// var_dump("sqlALl", $whereStringLaporanBarangKeluar);
		// END PAKE FILTER

		// CUSTOM QUERY
		$this->crud->set_theme('twitter-bootstrap');
		$this->crud->set_subject('Laporan Barang Keluar');
		// $this->crud->set_model('custom_query_model');
		// $this->crud->set_table('t_barang_keluar')
		// 	->unset_add()->unset_delete()->unset_edit()
		// 	->unset_read()->columns(
		// 		'tanggal',
		// 		'nama_admin',
		// 		'nama_barang',
		// 		'jumlah',
		// 		'nama_jamaah',
		// 		'nama_paket',
		// 		'nomor_resi'
		// 	);
		// $this->crud->basic_model->set_query_str('SELECT 
		// bk.id id, kp.tanggal_keluar tanggal , a.nama nama_admin, b.nama nama_barang,
		// bk.jumlah jumlah, CONCAT_WS("-",j.nama_jamaah) AS nama_jamaah, 
		// jp.estimasi_keberangkatan nama_paket, kp.nomor_resi nomor_resi
		// FROM t_barang_keluar bk
		// JOIN t_koper_jamaah kp ON kp.id = bk.id_koper_jamaah ' . $sqlTglAwal . ' ' . $sqlTglAkhir . '
		// JOIN m_barang b ON b.id = bk.id_barang ' . $sqlBarang . '
		// JOIN admin a ON a.id_admin = kp.admin_input_keluar
		// JOIN data_jamaah_paket jp ON jp.id = kp.id_paket ' . $sqlPaket . '
		// JOIN data_jamaah j ON j.id_jamaah = kp.id_jamaah ' . $sqlJamaah . '
		// WHERE bk.is_active = 1 AND bk.jumlah > 0'); //Query text here
		// 	// END CUSTOM QUERY


		$a = ['TANGGAL', 'NAMA_JAMAAH', 'PAKET'];
		// $a = ['TANGGAL','ADMIN','NO_RESI', 'NAMA_JAMAAH', 'PAKET'];
		$data['allBarang'] = $allBarang = $this->db->get('m_barang')->result();
		$where = "WHERE lb.tipe = 'out'";
		if (count($tempWhere) > 0) {
			// $where = ' WHERE 1=1';
			// var_dump($tempWhere);
			foreach ($tempWhere as $k => $v) {
				$where .= " AND " . $k . "'" . $v . "'";
			}

			// var_dump($where);
		}

			// JIKA ADA ID KOPERNYA
			if ($id_koper_jamaah != 0) {
				$where .= ' AND bk.id_koper_jamaah = '.$id_koper_jamaah;
				$data['urlSave'] = base_url("laporan/barang_keluar/" . $id_koper_jamaah);
				$data['idKoperJamaah'] = $id_koper_jamaah;
			}

		$ss = '';
		foreach ($allBarang as $b) {
// 			$name = str_replace(' ', '_', $b->nama);
			$name = preg_replace("/[^a-zA-Z0-9\s]/", "", $b->nama);
			$name = str_replace(' ', '_', $name);
			array_push($a, $name);
			$ss .= ", SUM(IF(lb.id_barang = " . $b->id . ", lb.jumlah, 0)) AS " . $name;
		}


		// $aa = "
		// select
		// if(lb.cf3 = 't_barang_masuk', concat(lb.cf3),concat(lb.cf3,'-',kj.id)) AS helpCol, lb.id,
		// CAST(lb.created_at AS date) AS TANGGAL,  a.nama AS ADMIN, bk.nomor_resi AS NO_RESI,
		// if(lb.cf3 = 't_barang_masuk', concat('BARANG MASUK'),concat(dj.nama_jamaah)) AS NAMA_JAMAAH
		// , if(lb.cf3 = 't_barang_masuk', concat('-'),concat(djp.estimasi_keberangkatan)) AS PAKET 
		// " . $ss . "	
		// from t_log_barang lb
		// join m_barang b on b.id = lb.id_barang
		// left join t_barang_masuk bm on bm.id = lb.cf1
		// left join t_barang_keluar bk on bk.id = lb.cf1
		// left join t_koper_jamaah kj on kj.id = bk.id_koper_jamaah
		// left join data_jamaah dj on dj.id_jamaah = kj.id_jamaah
		// left join data_jamaah_paket djp on djp.id = kj.id_paket
		// join admin a on a.id_admin = bk.admin_input_keluar

		// " . $where .  "
		// group by helpCol order by helpCol
		// ";

		$aa = "
		select
		if(lb.cf3 = 't_barang_masuk', concat(lb.cf3),concat(lb.cf3,'-',kj.id)) AS helpCol, lb.id,
		CAST(lb.created_at AS date) AS TANGGAL,
		if(lb.cf3 = 't_barang_masuk', concat('BARANG MASUK'),concat(dj.nama_jamaah)) AS NAMA_JAMAAH
		, if(lb.cf3 = 't_barang_masuk', concat('-'),concat(djp.estimasi_keberangkatan)) AS PAKET 
		" . $ss . "	
		from t_log_barang lb
		join m_barang b on b.id = lb.id_barang
		left join t_barang_masuk bm on bm.id = lb.cf1
		left join t_barang_keluar bk on bk.id = lb.cf1
		left join t_koper_jamaah kj on kj.id = bk.id_koper_jamaah
		left join data_jamaah dj on dj.id_jamaah = kj.id_jamaah
		left join data_jamaah_paket djp on djp.id = kj.id_paket
		join admin a on a.id_admin = bk.admin_input_keluar

		" . $where .  "
		group by helpCol order by helpCol
		";
		// var_dump($aa);

		$this->crud->set_model('custom_query_model');
		$this->crud->set_table('t_barang_keluar')
			->unset_add()->unset_delete()->unset_edit()
			->unset_read()->columns(
				$a
			);

		
		$this->crud->basic_model->set_query_str($aa);

		// xxxxxxx

		// $this->crud->set_table('t_barang_keluar')->unset_add()->unset_delete()->unset_edit();
		// $this->crud->unset_read()->columns('tanggal_keluar', 'admin_input_keluar', 'id_barang', 'jumlah', 'id_jamaah', 'id_paket', 'nomor_resi');
		// $this->crud->set_relation('admin_input_keluar', 'admin', 'nama');
		// $this->crud->set_relation('id_barang', 'm_barang', 'nama');
		// $this->crud->set_relation('id_jamaah', 'data_jamaah', 'nama_jamaah');
		// $this->crud->set_relation('id_paket', 'data_jamaah_paket', 'estimasi_keberangkatan');

		// $this->crud->display_as('admin_input_keluar', 'Admin Input');
		// $this->crud->display_as('id_barang', 'Barang');
		// $this->crud->display_as('id_jamaah', 'Jamaah');
		// $this->crud->display_as('nomor_resi', 'No Resi / Penerima');
		// $this->crud->display_as('id_paket', 'Paket');

		// $this->crud->where(['is_active' => 1, 'jumlah >' => 0]);


		// if (count($tempWhere) > 0) {
		// 	$this->crud->where($tempWhere);
		// }

		$data['urlSave'] = base_url("laporan/barang_keluar");
		$data['allJamaah'] = $this->db->get('data_jamaah')->result();
		$data['allPaket'] = $this->db->get('data_jamaah_paket')->result();
		$data['allBarang'] = $this->db->get('m_barang')->result();

		// // JIKA ADA ID KOPERNYA
		// if ($id_koper_jamaah != 0) {
		// 	$this->crud->where(['id_koper_jamaah' => $id_koper_jamaah]);
		// 	$data['urlSave'] = base_url("laporan/barang_keluar/" . $id_koper_jamaah);
		// 	$data['idKoperJamaah'] = $id_koper_jamaah;
		// }

		$data['output'] = $this->crud->render();

		$this->load->view('laporan/laporan_barang_keluar', $data);
	}

	function barang_keluar1	($id_koper_jamaah = 0)
	{


		// START PAKE FILTER

		$param = $this->input->post();
		$data['param'] = $param;
		$whereStringLaporanBarangKeluar = '';

		$dataSesssionLaporanBarangKeluar =  $this->session->userdata('dataSesssionLaporanBarangKeluar');
		$tempWhere = [];

		if (isset($param['tanggal_awal'])) {
			$whereStringLaporanBarangKeluar .= ' AND kp.tanggal_keluar >= "' . $param['tanggal_awal'] . '"';
			$dataSesssionLaporanBarangKeluar['tanggal_awal'] = $param['tanggal_awal'];
		}

		if (isset($param['tanggal_akhir'])) {
			$whereStringLaporanBarangKeluar .= ' AND kp.tanggal_keluar <= "' . $param['tanggal_akhir'] . '"';
			$dataSesssionLaporanBarangKeluar['tanggal_akhir'] = $param['tanggal_akhir'];
		}

		if (isset($param['nama_barang'])) {
			$whereStringLaporanBarangKeluar .= ' AND b.id = ' . $param['nama_barang'];
			$dataSesssionLaporanBarangKeluar['nama_barang'] = $param['nama_barang'];
		}

		if (isset($param['nama_paket'])) {
			$whereStringLaporanBarangKeluar .= ' AND jp.id = ' . $param['nama_paket'];
			$dataSesssionLaporanBarangKeluar['nama_paket'] = $param['nama_paket'];
		}

		if (isset($param['nama_jamaah'])) {
			$whereStringLaporanBarangKeluar .= ' AND j.id_jamaah = ' . $param['nama_jamaah'];
			$dataSesssionLaporanBarangKeluar['nama_jamaah'] = $param['nama_jamaah'];
		}

		if ($whereStringLaporanBarangKeluar != '') {
			$this->session->set_userdata('dataSesssionLaporanBarangKeluar', $dataSesssionLaporanBarangKeluar);
		}

		$sqlTglAwal = '';
		$data['selTglAwal'] = @$param['tanggal_awal'];
		if (@$this->session->userdata('dataSesssionLaporanBarangKeluar')['tanggal_awal'] != 0 && $this->session->userdata('dataSesssionLaporanBarangKeluar')['tanggal_awal'] != null) {
			$data['selTglAwal'] =  $this->session->userdata('dataSesssionLaporanBarangKeluar')['tanggal_awal'];
			$sqlTglAwal = ' AND kp.tanggal_keluar >= "' . $this->session->userdata('dataSesssionLaporanBarangKeluar')['tanggal_awal'] . '"';

			$tempWhere['tanggal_keluar >='] = $this->session->userdata('dataSesssionLaporanBarangKeluar')['tanggal_awal'];
		}

		$sqlTglAkhir = '';
		$data['selTglAkhir'] = @$param['tanggal_akhir'];
		if (@$this->session->userdata('dataSesssionLaporanBarangKeluar')['tanggal_akhir'] != 0 && $this->session->userdata('dataSesssionLaporanBarangKeluar')['tanggal_akhir'] != null) {
			$data['selTglAkhir'] =  $this->session->userdata('dataSesssionLaporanBarangKeluar')['tanggal_akhir'];
			$sqlTglAkhir = ' AND kp.tanggal_keluar <= "' . $this->session->userdata('dataSesssionLaporanBarangKeluar')['tanggal_akhir'] . '"';

			$tempWhere['tanggal_keluar <='] = $this->session->userdata('dataSesssionLaporanBarangKeluar')['tanggal_akhir'];
		}

		$sqlBarang = '';
		$data['selBarang'] = @$param['nama_barang'];
		if (@$this->session->userdata('dataSesssionLaporanBarangKeluar')['nama_barang'] != 0 && $this->session->userdata('dataSesssionLaporanBarangKeluar')['nama_barang'] != null) {
			$data['selBarang'] =  $this->session->userdata('dataSesssionLaporanBarangKeluar')['nama_barang'];
			$sqlBarang = ' AND b.id = ' . $this->session->userdata('dataSesssionLaporanBarangKeluar')['nama_barang'];

			$tempWhere['id_barang'] = $this->session->userdata('dataSesssionLaporanBarangKeluar')['nama_barang'];
		}

		$sqlPaket = '';
		$data['selPaket'] = @$param['nama_paket'];
		if (@$this->session->userdata('dataSesssionLaporanBarangKeluar')['nama_paket'] != 0 && $this->session->userdata('dataSesssionLaporanBarangKeluar')['nama_paket'] != null) {
			$data['selPaket'] =  $this->session->userdata('dataSesssionLaporanBarangKeluar')['nama_paket'];
			$sqlPaket = ' AND b.id = ' . $this->session->userdata('dataSesssionLaporanBarangKeluar')['nama_paket'];

			$tempWhere['id_paket'] = $this->session->userdata('dataSesssionLaporanBarangKeluar')['nama_paket'];
		}

		$sqlJamaah = '';
		$data['selJamaah'] = @$param['nama_jamaah'];
		if (@$this->session->userdata('dataSesssionLaporanBarangKeluar')['nama_jamaah'] != 0 && $this->session->userdata('dataSesssionLaporanBarangKeluar')['nama_jamaah'] != null) {
			$data['selJamaah'] =  $this->session->userdata('dataSesssionLaporanBarangKeluar')['nama_jamaah'];
			$sqlJamaah = ' AND b.id = ' . $this->session->userdata('dataSesssionLaporanBarangKeluar')['nama_jamaah'];

			$tempWhere['t_barang_keluar.id_jamaah'] = $this->session->userdata('dataSesssionLaporanBarangKeluar')['nama_jamaah'];
		}

		// var_dump("sessAtas", $dataSesssionLaporanBarangKeluar);
		// var_dump("sessClick", $this->session->userdata('dataSesssionLaporanBarangKeluar'));
		// var_dump("sqlALl", $whereStringLaporanBarangKeluar);
		// END PAKE FILTER

		// CUSTOM QUERY
		$this->crud->set_theme('twitter-bootstrap');
		$this->crud->set_subject('Laporan Barang Keluar');
		// $this->crud->set_model('custom_query_model');
		// $this->crud->set_table('t_barang_keluar')
		// 	->unset_add()->unset_delete()->unset_edit()
		// 	->unset_read()->columns(
		// 		'tanggal',
		// 		'nama_admin',
		// 		'nama_barang',
		// 		'jumlah',
		// 		'nama_jamaah',
		// 		'nama_paket',
		// 		'nomor_resi'
		// 	);
		// $this->crud->basic_model->set_query_str('SELECT 
		// bk.id id, kp.tanggal_keluar tanggal , a.nama nama_admin, b.nama nama_barang,
		// bk.jumlah jumlah, CONCAT_WS("-",j.nama_jamaah) AS nama_jamaah, 
		// jp.estimasi_keberangkatan nama_paket, kp.nomor_resi nomor_resi
		// FROM t_barang_keluar bk
		// JOIN t_koper_jamaah kp ON kp.id = bk.id_koper_jamaah ' . $sqlTglAwal . ' ' . $sqlTglAkhir . '
		// JOIN m_barang b ON b.id = bk.id_barang ' . $sqlBarang . '
		// JOIN admin a ON a.id_admin = kp.admin_input_keluar
		// JOIN data_jamaah_paket jp ON jp.id = kp.id_paket ' . $sqlPaket . '
		// JOIN data_jamaah j ON j.id_jamaah = kp.id_jamaah ' . $sqlJamaah . '
		// WHERE bk.is_active = 1 AND bk.jumlah > 0'); //Query text here
		// 	// END CUSTOM QUERY

		$this->crud->set_table('t_barang_keluar')->unset_add()->unset_delete()->unset_edit();
		$this->crud->unset_read()->columns('tanggal_keluar', 'admin_input_keluar', 'id_barang', 'jumlah', 'id_jamaah', 'id_paket', 'nomor_resi');
		$this->crud->set_relation('admin_input_keluar', 'admin', 'nama');
		$this->crud->set_relation('id_barang', 'm_barang', 'nama');
		$this->crud->set_relation('id_jamaah', 'data_jamaah', 'nama_jamaah');
		$this->crud->set_relation('id_paket', 'data_jamaah_paket', 'estimasi_keberangkatan');

		$this->crud->display_as('admin_input_keluar', 'Admin Input');
		$this->crud->display_as('id_barang', 'Barang');
		$this->crud->display_as('id_jamaah', 'Jamaah');
		$this->crud->display_as('nomor_resi', 'No Resi / Penerima');
		$this->crud->display_as('id_paket', 'Paket');

		$this->crud->where(['is_active' => 1, 'jumlah >' => 0]);


		if (count($tempWhere) > 0) {
			$this->crud->where($tempWhere);
		}

		$data['urlSave'] = base_url("laporan/barang_keluar");
		$data['allJamaah'] = $this->db->get('data_jamaah')->result();
		$data['allPaket'] = $this->db->get('data_jamaah_paket')->result();
		$data['allBarang'] = $this->db->get('m_barang')->result();

		// JIKA ADA ID KOPERNYA
		if ($id_koper_jamaah != 0) {
			$this->crud->where(['id_koper_jamaah' => $id_koper_jamaah]);
			$data['urlSave'] = base_url("laporan/barang_keluar/" . $id_koper_jamaah);
			$data['idKoperJamaah'] = $id_koper_jamaah;
		}

		$data['output'] = $this->crud->render();

		$this->load->view('laporan/laporan_barang_keluar', $data);
	}


	
	function stok_barang()
	{
		// START PAKE FILTER
		$param = $this->input->post();
		$data['param'] = $param;
		$whereStringLaporanStokBarang = '';

		$dataSesssionLaporanStokBarang =  $this->session->userdata('dataSesssionLaporanStokBarang');
		$tempWhere = [];


		if (isset($param['nama_barang'])) {
			$whereStringLaporanStokBarang .= ' AND b.id = ' . $param['nama_barang'];
			$dataSesssionLaporanStokBarang['nama_barang'] = $param['nama_barang'];
		}

		if ($whereStringLaporanStokBarang != '') {
			$this->session->set_userdata('dataSesssionLaporanStokBarang', $dataSesssionLaporanStokBarang);
		}

		$sqlBarang = '';
		$data['selBarang'] = @$param['nama_barang'];
		if (@$this->session->userdata('dataSesssionLaporanStokBarang')['nama_barang'] != 0 && $this->session->userdata('dataSesssionLaporanStokBarang')['nama_barang'] != null) {
			$data['selBarang'] =  $this->session->userdata('dataSesssionLaporanStokBarang')['nama_barang'];
			$sqlBarang = ' AND b.id = ' . $this->session->userdata('dataSesssionLaporanStokBarang')['nama_barang'];

			$tempWhere['id'] = $this->session->userdata('dataSesssionLaporanStokBarang')['nama_barang'];
		}

		// var_dump("sessAtas", $dataSesssionLaporanStokBarang);
		// var_dump("sessClick", $this->session->userdata('dataSesssionLaporanStokBarang'));
		// var_dump("sqlALl", $whereStringLaporanStokBarang);
		// END PAKE FILTER

		// CUSTOM QUERY
		$this->crud->set_theme('twitter-bootstrap');
		$this->crud->set_subject('Laporan Stok Barang');
		// $this->crud->set_model('custom_query_model');
		// $this->crud->set_table('t_barang_keluar')
		// 	->unset_add()->unset_delete()->unset_edit()
		// 	->unset_read()->columns(
		// 		'tanggal',
		// 		'nama_admin',
		// 		'nama_barang',
		// 		'jumlah',
		// 		'nama_jamaah',
		// 		'nama_paket',
		// 		'nomor_resi'
		// 	);
		// $this->crud->basic_model->set_query_str('SELECT 
		// bk.id id, kp.tanggal_keluar tanggal , a.nama nama_admin, b.nama nama_barang,
		// bk.jumlah jumlah, CONCAT_WS("-",j.nama_jamaah) AS nama_jamaah, 
		// jp.estimasi_keberangkatan nama_paket, kp.nomor_resi nomor_resi
		// FROM t_barang_keluar bk
		// JOIN t_koper_jamaah kp ON kp.id = bk.id_koper_jamaah ' . $sqlTglAwal . ' ' . $sqlTglAkhir . '
		// JOIN m_barang b ON b.id = bk.id_barang ' . $sqlBarang . '
		// JOIN admin a ON a.id_admin = kp.admin_input_keluar
		// JOIN data_jamaah_paket jp ON jp.id = kp.id_paket ' . $sqlPaket . '
		// JOIN data_jamaah j ON j.id_jamaah = kp.id_jamaah ' . $sqlJamaah . '
		// WHERE bk.is_active = 1 AND bk.jumlah > 0'); //Query text here
		// 	// END CUSTOM QUERY

		$this->crud->set_table('m_barang')->unset_add()->unset_delete()->unset_edit();
		$this->crud->unset_read()->columns('nama', 'stok_masuk', 'stok_keluar', 'sisa_stok');
		$this->crud->callback_column('stok_masuk', array($this, '_calculate_in_log_barang'));
		$this->crud->callback_column('stok_keluar', array($this, '_calculate_out_log_barang'));
		$this->crud->callback_column('sisa_stok', array($this, '_calculate_current_log_barang'));

		if (count($tempWhere) > 0) {
			$this->crud->where($tempWhere);
		}

		$data['output'] = $this->crud->render();
		$data['urlSave'] = base_url("laporan/stok_barang");
		$data['allBarang'] = $this->db->get('m_barang')->result();

		$this->load->view('laporan/laporan_stok_barang', $data);
	}
    function rekom_paspor(){
        $this->crud->set_table('surat_rekom_paspor')->unset_add()->unset_delete()->unset_export()->unset_print()
        ->unset_columns('jamaah_id')
        ->unset_edit()->set_relation('user_id','admin','nama_admin')->set_relation('imigrasi','ref_imigrasi','nama_imigrasi')
        ->display_as('created_at','Tanggal dibuat')->display_as('nomor_urut','Nomor Surat')->order_by('nomor_urut','desc')
        ->display_as('jamaah_id','Nama Jamaah')->display_as('user_id','Pembuat');
        $this->show();
    }
	function stok_barang1Old() //
	{
		// START PAKE FILTER
		$param = $this->input->post();
		$data['param'] = $param;
		$whereStringLaporanStokBarang = '';
		$where = '';

		$dataSesssionLaporanStokBarang =  $this->session->userdata('dataSesssionLaporanStokBarang');
		$tempWhere = [];

		if (isset($param['tanggal_awal'])) {
			$whereStringLaporanStokBarang .= ' AND TANGGAL >= "' . $param['tanggal_awal'] . '"';
			$dataSesssionLaporanStokBarang['tanggal_awal'] = $param['tanggal_awal'];
		}

		if (isset($param['tanggal_akhir'])) {
			$whereStringLaporanStokBarang .= ' AND TANGGAL <= "' . $param['tanggal_akhir'] . '"';
			$dataSesssionLaporanStokBarang['tanggal_akhir'] = $param['tanggal_akhir'];
		}

		if ($whereStringLaporanStokBarang != '') {
			$this->session->set_userdata('dataSesssionLaporanStokBarang', $dataSesssionLaporanStokBarang);
		}

		$data['selTglAwal'] = @$param['tanggal_awal'];
		if (@$this->session->userdata('dataSesssionLaporanStokBarang')['tanggal_awal'] != 0 && $this->session->userdata('dataSesssionLaporanStokBarang')['tanggal_awal'] != null) {
			$data['selTglAwal'] =  $this->session->userdata('dataSesssionLaporanStokBarang')['tanggal_awal'];
			$tempWhere['lb.tanggal >='] = $this->session->userdata('dataSesssionLaporanStokBarang')['tanggal_awal'];
		}

		$data['selTglAkhir'] = @$param['tanggal_akhir'];
		if (@$this->session->userdata('dataSesssionLaporanStokBarang')['tanggal_akhir'] != 0 && $this->session->userdata('dataSesssionLaporanStokBarang')['tanggal_akhir'] != null) {
			$data['selTglAkhir'] =  $this->session->userdata('dataSesssionLaporanStokBarang')['tanggal_akhir'];
			$tempWhere['lb.tanggal <='] = $this->session->userdata('dataSesssionLaporanStokBarang')['tanggal_akhir'];
		}

		// CUSTOM QUERY
		$this->crud->set_theme('twitter-bootstrap');
		$this->crud->set_subject('Laporan Stok Barang');
		$a = ['TANGGAL', 'NAMA_JAMAAH', 'PAKET'];
		$data['urlSave'] = base_url("laporan/stok_barang1");
		$data['allBarang'] = $allBarang = $this->db->get('m_barang')->result();

		$where = '';
		if (count($tempWhere) > 0) {
			$where = ' WHERE 1=1';
			// var_dump($tempWhere);
			foreach ($tempWhere as $k => $v) {
				$where .= " AND " . $k . "'" . $v . "'";
			}

			// var_dump($where);
		}

		$ss = '';
		foreach ($allBarang as $b) {
			$name = str_replace(' ', '_', $b->nama);
			array_push($a, $name);
			$ss .= ", SUM(IF(lb.id_barang = " . $b->id . ", lb.jumlah, 0)) AS " . $name;
		}

		$aa = "
		select 
		if(lb.cf3 = 't_barang_masuk', concat(lb.cf3),concat(lb.cf3,'-',kj.id)) AS helpCol, lb.id,
		CAST(lb.created_at AS date) AS TANGGAL, 
		if(lb.cf3 = 't_barang_masuk', concat('BARANG MASUK'),concat(dj.nama_jamaah)) AS NAMA_JAMAAH
		, if(lb.cf3 = 't_barang_masuk', concat('-'),concat(djp.estimasi_keberangkatan)) AS PAKET 
		" . $ss . "	
		from t_log_barang lb
		join m_barang b on b.id = lb.id_barang
		left join t_barang_masuk bm on bm.id = lb.cf1
		left join t_barang_keluar bk on bk.id = lb.cf1
		left join t_koper_jamaah kj on kj.id = bk.id_koper_jamaah
		left join data_jamaah dj on dj.id_jamaah = kj.id_jamaah
		left join data_jamaah_paket djp on djp.id = kj.id_paket
		" . $where . "
		group by helpCol order by helpCol
		";
		// var_dump($aa);

		$this->crud->set_model('custom_query_model');
		$this->crud->set_table('t_barang_keluar')
			->unset_add()->unset_delete()->unset_edit()
			->unset_read()->columns(
				$a
			);
		$this->crud->basic_model->set_query_str($aa);


		$data['output'] = $this->crud->render();

		$this->load->view('laporan/laporan_stok_barang', $data);
	}

	function stok_barang1() //NEW
	{
		// START PAKE FILTER
		$param = $this->input->post();
		$data['param'] = $param;
		$whereStringLaporanStokBarang = '';
		$where = '';

		$dataSesssionLaporanStokBarang =  $this->session->userdata('dataSesssionLaporanStokBarang');
		$tempWhere = [];

		if (isset($param['tanggal_awal'])) {
			$whereStringLaporanStokBarang .= ' AND TANGGAL >= "' . $param['tanggal_awal'] . '"';
			$dataSesssionLaporanStokBarang['tanggal_awal'] = $param['tanggal_awal'];
		}

		if (isset($param['tanggal_akhir'])) {
			$whereStringLaporanStokBarang .= ' AND TANGGAL <= "' . $param['tanggal_akhir'] . '"';
			$dataSesssionLaporanStokBarang['tanggal_akhir'] = $param['tanggal_akhir'];
		}

		if ($whereStringLaporanStokBarang != '') {
			$this->session->set_userdata('dataSesssionLaporanStokBarang', $dataSesssionLaporanStokBarang);
		}

		$data['selTglAwal'] = @$param['tanggal_awal'];
		if (@$this->session->userdata('dataSesssionLaporanStokBarang')['tanggal_awal'] != 0 && $this->session->userdata('dataSesssionLaporanStokBarang')['tanggal_awal'] != null) {
			$data['selTglAwal'] =  $this->session->userdata('dataSesssionLaporanStokBarang')['tanggal_awal'];
			$tempWhere['lb.tanggal >='] = $this->session->userdata('dataSesssionLaporanStokBarang')['tanggal_awal'];
		}

		$data['selTglAkhir'] = @$param['tanggal_akhir'];
		if (@$this->session->userdata('dataSesssionLaporanStokBarang')['tanggal_akhir'] != 0 && $this->session->userdata('dataSesssionLaporanStokBarang')['tanggal_akhir'] != null) {
			$data['selTglAkhir'] =  $this->session->userdata('dataSesssionLaporanStokBarang')['tanggal_akhir'];
			$tempWhere['lb.tanggal <='] = $this->session->userdata('dataSesssionLaporanStokBarang')['tanggal_akhir'];
		}

		// CUSTOM QUERY
		$this->crud->set_theme('twitter-bootstrap');
		$this->crud->set_subject('Laporan Stok Barang');
		$a = ['TANGGAL', 'NAMA_JAMAAH', 'PAKET'];
		$data['urlSave'] = base_url("laporan/stok_barang1");
		$data['allBarang'] = $allBarang = $this->db->get('m_barang')->result();

		$where = '';
		if (count($tempWhere) > 0) {
			$where = ' WHERE 1=1';
			// var_dump($tempWhere);
			foreach ($tempWhere as $k => $v) {
				$where .= " AND " . $k . "'" . $v . "'";
			}

			// var_dump($where);
		}

		$ss = '';
		$tt = '';
		foreach ($allBarang as $b) {
// 			$name = str_replace(' ', '_', $b->nama);
            $name = preg_replace("/[^a-zA-Z0-9\s]/", "", $b->nama);
			$name = str_replace(' ', '_', $name);
			array_push($a, $name);
			$ss .= ", SUM(IF(lb.id_barang = " . $b->id . ", lb.jumlah, 0)) AS " . $name;

			$tt .= ", SUM($name)";
		}

		$aa = "
		select 
		if(lb.cf3 = 't_barang_masuk', concat(lb.cf3),concat(lb.cf3,'-',kj.id)) AS helpCol, lb.id,
		CAST(lb.created_at AS date) AS TANGGAL, 
		if(lb.cf3 = 't_barang_masuk', concat('BARANG MASUK'),concat(dj.nama_jamaah)) AS NAMA_JAMAAH
		, if(lb.cf3 = 't_barang_masuk', concat('-'),concat(djp.estimasi_keberangkatan)) AS PAKET 
		" . $ss . "	
		from t_log_barang lb
		join m_barang b on b.id = lb.id_barang
		left join t_barang_masuk bm on bm.id = lb.cf1
		left join t_barang_keluar bk on bk.id = lb.cf1
		left join t_koper_jamaah kj on kj.id = bk.id_koper_jamaah
		left join data_jamaah dj on dj.id_jamaah = kj.id_jamaah
		left join data_jamaah_paket djp on djp.id = kj.id_paket
		" . $where . "
		group by helpCol order by helpCol
		";

		$bb = "
		select * from($aa) as A
		union all
		select '','','','','TOTAL' $tt from ($aa) as B
		";

		$this->crud->set_model('custom_query_model');
		$this->crud->set_table('t_barang_keluar')
			->unset_add()->unset_delete()->unset_edit()
			->unset_read()->columns(
				$a
			);
		$this->crud->basic_model->set_query_str($bb);
		// $this->crud->basic_model->set_query_str($aa);


		$data['output'] = $this->crud->render();

		$this->load->view('laporan/laporan_stok_barang', $data);
	}

	public function _calculate_in_log_barang($value, $row)
	{
		$query = $this->db->select("SUM(jumlah) AS jumlah")->get_where('t_log_barang', ['id_barang' => $row->id, 'tipe' => 'in'])->row();
		return  $query->jumlah ?? '-';
	}


	public function _calculate_out_log_barang($value, $row)
	{
		$query = $this->db->select("SUM(jumlah) AS jumlah")->get_where('t_log_barang', ['id_barang' => $row->id, 'tipe' => 'out'])->row();
		return $query->jumlah  ?? '-';
	}


	public function _calculate_current_log_barang($value, $row)
	{
		$query = $this->db->select("SUM(jumlah) AS jumlah")->get_where('t_log_barang', ['id_barang' => $row->id])->row();
		return $query->jumlah ?? '-';
	}

	/*
	


*/
	function unique_field_name($field_name)
	{
		return 's' . substr(md5($field_name), 0, 8); //This s is because is better for a string to begin with a letter and not with a number
	}
	function bulanan($tahun = 0, $bulan = 0)
	{
		if ($tahun == 0 && $bulan == 0) {
			$this->crud->set_table('v_rekap_bulanan');
			$this->crud->set_primary_key('id');
			$this->crud->set_subject('Data Transaksi Bulanan')->unset_add()->unset_edit()->unset_delete();
			// $this->crud->set_relation('jenis_transaksi','jenis_transaksi_pengeluaran','nama_transaksi')->unset_read()->columns('tahun','bulan','jenis_transaksi','debet','kredit');
			$this->crud->set_relation('bulan', 'bulan', 'bulan');
			//print_r($this->j);

			$this->crud->callback_column('bulan', array($this, '_callback_bulanan'));

			$this->crud->callback_column($this->unique_field_name('bulan'), array($this, '_callback_bulanan'));
		} else {
			$this->crud->set_table('pembayaran_transaksi_paket');
			$this->crud->set_subject('Data Transaksi Harian')->unset_add()->unset_edit()->unset_delete()->where('year(tanggal)', $tahun)->where('month(tanggal)', $bulan);
			$this->crud->set_relation('teller', 'admin', 'nama')->set_relation('jenis_transaksi', 'jenis_transaksi_pengeluaran', 'nama_transaksi')->set_relation('id_transaksi_paket', 'transaksi_paket', '{jamaah}-{kode}')->unset_read()->columns('id', 'id_transaksi_paket', 'tanggal', 'tanggal_transfer', 'debet', 'kredit', 'jenis_transaksi', 'keterangan', 'teller')->display_as('id', 'Nomor Kuitansi')->display_as('id_transaksi_paket', 'Jamaah / NIK');
			// print_r($this->j);
			$this->crud->callback_column($this->unique_field_name('id_transaksi_paket'), array($this, '_jamaah'));;
		}
		$this->crud->callback_column('debet', array($this, '_rupiah'));
		$this->crud->callback_column('kredit', array($this, '_rupiah'));

		$this->show();
	}

	function tahunan($tahun = 0, $bulan = 0)
	{
		//tahun	bulan	jenis_transaksi	debet	kredit
		//select tahun,jenis_transaksi,sum(debet) as debet, sum(kredit) as kredit from v_rekap_bulanan group by tahun, jenis_transaksi 
		$this->crud->set_table('SELECT
  `v_rekap_bulanan`.`tahun` AS `tahun`,
  SUM(`v_rekap_bulanan`.`debet`) AS `debet`,
  SUM(`v_rekap_bulanan`.`kredit`) AS `kredit`
FROM `v_rekap_bulanan` as v_rekap_tahunan');
		$this->crud->set_primary_key('tahun');
		$this->crud->set_subject('Data Transaksi Tahunan')->unset_add()->unset_edit()->unset_delete();
		// $this->crud->set_relation('jenis_transaksi','jenis_transaksi_pengeluaran','nama_transaksi')->unset_read()->columns('tahun','debet','kredit');
		//print_r($this->j);
		$this->crud->callback_column('debet', array($this, '_rupiah'));
		$this->crud->callback_column('kredit', array($this, '_rupiah'));
		$this->show();
	}

	function _rupiah($value, $row)
	{
		return 'Rp. ' . number_format((float)$value);


		//return 9;//$this->main_model->get_kurs()*$row->harga_dolar;
	}

	function _format_rp($value, $row){
		return number_format($value,null,',','.');
	}

	function format_rp($value){
		return number_format($value,null,',','.');
	}

	function _harga_rp($value, $row)
	{
		if ($row->harga == null) return 0;
		return number_format((float)$row->harga);


		//return 9;//$this->main_model->get_kurs()*$row->harga_dolar;
	}
	public function _callback_webpage_url($value, $row)
	{
		$jumlae = isset($this->j[$row->id]) ? $this->j[$row->id] : 0;
		return "<a href='" . site_url('transaksi/pembayaran/' . $row->id) . " target=\"_blank\"'>$value-$jumlae orang</a>";
	}
	public function _callback_bulanan($value, $row)
	{
		return "<a href='" . site_url('laporan/bulanan/' . $row->tahun . '/' . $row->bulan) . "' target=\"_blank\">$value</a>";
	}
	public function _callback_pemasukan($value, $row)
	{
		/*
        tabel: pembayaran_transaksi_paket(id_transaksi_paket , debet, saldo
        tabel:transaksi_paket: id, paket_umroh. 
        get all id_from transaksi paket., lalu dari pembayaran transaksi paket, disum.
        */
		if (isset($this->group_rev[$row->id]))
			return "<a href='" . site_url('transaksi/pembayaran/' . $row->id) . "' target='_blank'>" .
				number_format((float)$this->group_rev[$row->id]) . "</a>";
		else return 0;
	}

	function keberangkatan($paket = 0, $jamaah = 0)
	{
		if ($paket == 0) {
			$this->group_rev = $this->laporan_model->get_pendapatan_keberangkatan();
			$this->crud->set_table('data_jamaah_paket')->unset_read()->columns('estimasi_keberangkatan', 'Program', 'harga', 'pemasukan')->unset_edit()->display_as('estimasi_keberangkatan', 'Pilih Paket')->unset_delete()->unset_add()->set_subject('Pilih Paket');
			$this->db->select('paket_umroh');
			$query = $this->db->get('transaksi_paket');
			foreach ($query->result() as $row) {
				if (isset($this->j[$row->paket_umroh]))
					$this->j[$row->paket_umroh]++;
				else
					$this->j[$row->paket_umroh] = 1;
			}
			$this->crud->callback_column('estimasi_keberangkatan', array($this, '_callback_webpage_url'));
			$this->crud->callback_column('pemasukan', array($this, '_callback_pemasukan'));
			$this->crud->callback_column('harga', array($this, '_harga_rp'));
		} elseif ($jamaah == 0) {
			$s = $this->get('data_jamaah_paket', 'id', $paket, 'estimasi_keberangkatan') . '<br>$';
			// $dolar = $this->get('data_jamaah_paket','id',$paket,'harga_dolar');
			// $kurs = $this->main_model->get_kurs();
			// $s.=$dolar.'<br>';
			// $r=ceil($kurs*$dolar/1000)*1000;
			// $this->r = $r;
			// $s.='Rp.'.$this->main_model->uang($r);

			$this->grocery_crud->callback_add_field('harga', array($this, 'harga_field_callback_1'));
			$this->crud->set_table('transaksi_paket')->set_subject('Pembelian paket umroh ' . $s)->set_relation('jamaah', 'data_jamaah', '{nama_jamaah}-{no_ktp}', 'nama_jamaah <> ""')->unset_read()->columns('jamaah', 'harga', 'kredit', 'kekurangan', 'debet', 'saldo', 'kode', 'agen');
			$this->crud->set_top('Pembelian paket umroh ' . $s);
			$this->crud->field_type('kode', 'readonly');
			// $this->crud->set_relation('paket_umroh','data_jamaah_paket','estimasi_keberangkatan');
			$this->crud->callback_column('kekurangan', array($this, '_kekurangan'));
			$this->crud->callback_column('debet', array($this, '__debet'));
			$this->crud->callback_column('kredit', array($this, '__kredit'));
			$this->crud->set_relation('agen', 'data_jamaah_agen', '{nama}/{id}');
			$this->crud->add_fields(array('jamaah', 'harga', 'paket_umroh', 'kekurangan', 'harga_normal', 'agen'));
			$this->crud->callback_before_insert(array($this, '_update_kekurangan'));
			$this->crud->field_type('harga_normal', 'hidden', $r);
			$this->crud->field_type('kekurangan', 'hidden', $r);
			$this->crud->field_type('paket_umroh', 'hidden', $paket);
			$this->crud->data['-tes'] = '-';

			$this->crud->callback_after_insert(array($this, 'fix_code_after_insert'))->where(array('paket_umroh' => $paket));
		}

		$this->show();
	}
	function harian()
	{
		$this->crud->set_table('pembayaran_transaksi_paket');
		
		$this->crud->set_subject('Data Transaksi Harian')->unset_add()
			->set_theme('datatables')
			->unset_edit()
			->unset_delete();
		// $this->crud->set_relation('teller', 'admin', 'nama')
		// 	->set_relation('jenis_transaksi', 'jenis_transaksi_pengeluaran', 'nama_transaksi')
		// 	->set_relation(
		// 		'id_transaksi_paket',
		// 		'transaksi_paket',
		// 		'{jamaah}-{kode}'
		// 	);

		$state = $this->crud->getState();
		if ($state !== 'ajax_list') {
			/**
			 * author: Irul
			 * date: 08/03/2024
			 * revision: add metode column
			 */
			$this->crud
				->columns('pembayaran_transaksi_paket.id', 'id_transaksi_paket', 'tanggal', 'tanggal_transfer', 'debet', 'kredit', 'jenis_transaksi', 'keterangan', 'teller', 'metode', 'deleted')
				->display_as('pembayaran_transaksi_paket.id', 'Nomor Kuitansi')
				->display_as('id_transaksi_paket', 'Jamaah / NIK / Paket Umroh')
				->display_as('tanggal_transfer', 'Tgl Transfer')
				->display_as('debet', 'Debit (IDR)')
				->display_as('kredit', 'Kredit (IDR)')
				->display_as('jenis_transaksi', 'Jenis Transaksi')
				->display_as('metode', 'Cara Bayar')
				->display_as('deleted_by', 'Histori')
				->callback_read_field('tanggal', function($value){
					return (new DateTime($value))->format('d-m-Y');
				})
				->callback_read_field('tanggal_transfer', function($value){
					return (new DateTime($value))->format('d-m-Y');
				})
				->callback_read_field('debet', function($value){
					return number_format($value,0,',','.');
				})
				->callback_read_field('saldo', function($value){
					return number_format($value,0,',','.');
				})
				->callback_read_field('kredit', function($value){
					return number_format($value,0,',','.');
				})
				->callback_read_field('deleted_by', function($value, $primary_key){
					$delete = '';
					if(isset($value) && $value != '0000-00-00 00:00:00'){
						$_user = '';
						$user = $this->db->select('nama')
						->from('admin')
						->where('id_admin', $value)
						->get()->row();
						if($user) $_user = $user->nama;

						$pembayaran = $this->db
							->select('deleted_at')
							->from('pembayaran_transaksi_paket')
							->where('id', $primary_key)
							->get()->row();
						$delete = 'deleted: ' .  $pembayaran->deleted_at .' | '. $_user;
					}
					return $delete;
				})
				->unset_read_fields('deleted_at', 'deleted', 'receiver_debit');


			$this->crud->set_relation('teller', 'admin', 'nama')
				->set_relation('jenis_transaksi', 'jenis_transaksi_pengeluaran', 'nama_transaksi')
				->set_relation(
					'id_transaksi_paket',
					'transaksi_paket',
					'{jamaah}-{kode}'
				);
			echo ("<script>console.log('1');</script>");
		} else if ($state == 'ajax_list_info') {
			echo ("<script>console.log('2');</script>");

			$this->crud->columns('pembayaran_transaksi_paket.id', 'id_transaksi_paket', 'tanggal', 'tanggal_transfer', 'debet', 'kredit', 'jenis_transaksi', 'keterangan', 'teller', 'deleted')
				->display_as('pembayaran_transaksi_paket.id', 'Nomor Kuitansi')
				->display_as('id_transaksi_paket', 'Jamaah / NIK / Paket Umroh');

				$this->crud->callback_column('debet', array($this, '_format_rp'));
				$this->crud->callback_column('kredit', array($this, '_format_rp'));
				$this->crud->callback_column('tanggal', array($this, '_date_format'));
			$this->crud->set_relation('teller', 'admin', 'nama')
				->set_relation('jenis_transaksi', 'jenis_transaksi_pengeluaran', 'nama_transaksi')
				->set_relation(
					'id_transaksi_paket',
					'transaksi_paket',
					'{jamaah}-{kode}'
				);
		} else {
			$this->crud
				->columns('id', 'id_transaksi_paket', 'tanggal', 'tanggal_transfer', 'debet', 'kredit', 'jenis_transaksi', 'keterangan', 'teller', 'deleted')
				->display_as('id', 'Nomor Kuitansi')
				->display_as('tanggal_transfer', 'Tgl Transfer')
				->display_as('debet', 'Debit (IDR)')
				->display_as('kredit', 'Kredit (IDR)')
				->display_as('jenis_transaksi', 'Jenis Transaksi')
				->display_as('id_transaksi_paket', 'Jamaah / NIK / Paket Umroh');
			$this->crud->set_relation('teller', 'admin', 'nama')
				->set_relation('jenis_transaksi', 'jenis_transaksi_pengeluaran', 'nama_transaksi')
				->set_relation('deleted_by', 'admin', 'nama')
				->set_relation(
					'id_transaksi_paket',
					'transaksi_paket',
					'{jamaah}-{kode}'
				);
			echo ("<script>console.log('3');</script>");

			echo ("<script>console.log('" . $_POST['search_field'] . "');</script>");


			if (isset($_POST['search_field'])) {

				$req = @$_POST['search_text'];
				$field = @$_POST['search_field'];
				if ($field == 'sf69c711f' || empty($field)) {


					echo ("<script>console.log('" . $req . $field . "');</script>");
					
					// $searchFields = $_POST['search_field'];
					// $searchValues = $_POST['search_text'];
					// $key = array_search('emp_first_name', $searchFields);
					// if($key !== false) {
					// 	$crud->or_like('trade_partners.emp_last_name', $searchValues[$key]);
					// }
					// $key =  $this->like($this->j,'%'.$req);

					$matches = [];
					$matchesPaket = [];
					if ($req != '' || $req) {

						foreach ($this->j as $i => $v) {
							if (strpos(strtolower($v), strtolower($req)) !== false) {
								//if $c starts with $input, add to matches list
								$matches[] = $i;
							}
							// if (strpos($c, $input) === 0){
							// 	//if $c starts with $input, add to matches list
							// 	$matches[] = $c;
							// } else if (strcmp($input, $c) < 0){
							// 	//$input comes after $c in alpha order
							// 	//since $colors is sorted, we know that we won't find any more matches
							// 	break;
							// }
						}

						foreach ($this->paket as $i => $v) {
							if (strpos(strtolower($v), strtolower($req)) !== false) {
								//if $c starts with $input, add to matches list
								$matchesPaket[] = $i;
							}
							// if (strpos($c, $input) === 0){
							// 	//if $c starts with $input, add to matches list
							// 	$matches[] = $c;
							// } else if (strcmp($input, $c) < 0){
							// 	//$input comes after $c in alpha order
							// 	//since $colors is sorted, we know that we won't find any more matches
							// 	break;
							// }
						}

						echo ("<script>console.log('wPaket" . json_encode($matchesPaket) . "');</script>");

						if ($matchesPaket !== false) {
							foreach ($matchesPaket as $val) {
								$this->crud->or_like('jf69c711f.paket_umroh', $val);
								// break;
							}
							echo ("<script>console.log('waaaPaket');</script>");

						}

						// print_r($this->j[6418]);


						if ($matches !== false) {
							foreach ($matches as $val) {
								$this->crud->or_like('jf69c711f.jamaah', $val);
							}
							echo ("<script>console.log('waaa');</script>");
						}
						echo ("<script>console.log('w" . json_encode($matches) . "');</script>");

						// if($key !== false) {
						// 	$crud->or_like('sf69c711f', $searchValues[$key]);
						// }
						// print_r($_POST['search_field'],$_POST['search_text']);
					}
				}
			}
		}


		// if (isset($_POST['search_field'])) {

		// 	$req = @$_POST['search_text'];
		// 	$field = @$_POST['search_field'];
		// 	echo ("<script>console.log('" . $req . $field . "');</script>");
		// 	// $searchFields = $_POST['search_field'];
		// 	// $searchValues = $_POST['search_text'];
		// 	// $key = array_search('emp_first_name', $searchFields);
		// 	// if($key !== false) {
		// 	// 	$crud->or_like('trade_partners.emp_last_name', $searchValues[$key]);
		// 	// }
		// 	// $key =  $this->like($this->j,'%'.$req);

		// 	$matches = [];
		// 	foreach ($this->j as $i => $v) {
		// 		if (strpos($v, $req) !== false) {
		// 			//if $c starts with $input, add to matches list
		// 			$matches[] = $i;
		// 		}
		// 		// if (strpos($c, $input) === 0){
		// 		// 	//if $c starts with $input, add to matches list
		// 		// 	$matches[] = $c;
		// 		// } else if (strcmp($input, $c) < 0){
		// 		// 	//$input comes after $c in alpha order
		// 		// 	//since $colors is sorted, we know that we won't find any more matches
		// 		// 	break;
		// 		// }
		// 	}
		// 	// print_r($this->j[6418]);


		// 	if($matches !== false) {
		// 		foreach ($matches as $val){
		// 		$this->crud->or_like('jf69c711f.jamaah', $val);
		// 		}
		// 	echo ("<script>console.log('waaa');</script>");


		// 	}
		// 	echo ("<script>console.log('w" . json_encode($matches) . "');</script>");

		// 	// if($key !== false) {
		// 	// 	$crud->or_like('sf69c711f', $searchValues[$key]);
		// 	// }
		// 	// print_r($_POST['search_field'],$_POST['search_text']);
		// }


		// print_r($this->j);
		// $this->crud->callback_column('tanggal', array($this, '_tanggal'));
		$this->crud->callback_column('debet', array($this, '_format_rp'));
		$this->crud->callback_column('kredit', array($this, '_format_rp'));
		$this->crud->callback_column('tanggal', array($this, '_date_format'));

		$this->crud->callback_column('deleted', array($this, '_histori'));


		$this->crud->display_as('deleted', 'Histori');
		

		$this->crud->callback_column($this->unique_field_name('id_transaksi_paket'), array($this, '_jamaah'));
		$query = $this->db->query("SELECT a.jamaah,b.id as idpaket,CONCAT (estimasi_keberangkatan,'-',Program) 
		    AS paket,a.id FROM transaksi_paket a 
			LEFT JOIN data_jamaah_paket b
			ON a.paket_umroh = b.id");
		foreach ($query->result_array() as $row) {

			//$this->paketnya[$row['id']] = 'paket:'.$row['paket'].':paket';
			// print_r($row);exit();
			$paketx = $row['idpaket'];
			if (isset($this->paket[$paketx]))
				$this->paketnya[$row['id']] = '#' . "<a href='"
					. site_url('transaksi_op/pembayaran/' . $paketx . '/' . $row['jamaah']) .
					"'data-toggle='tooltip' title='Klik untuk pembayaran' target='_blank'>" .
					$this->paket[$paketx] . "</a>";
			else
				$this->paketnya[$row['id']] = '';
			/*
				."<a href='".site_url('transaksi_op/pembayaran/'.$row->paket_umroh.'/'.$row->jamaah)."'data-toggle='tooltip' 
				title='Klik untuk pembayaran' target='_blank'>".$this->paket[$row->paket_umroh]."</a>":'';

				*/
		}
		// print_r($this->j);

		// sf69c711f


		/*
		data_jamaah_paket -->
			transaksi_paket --> 
				pembayaran_transaksi_paket
		*/
		// print_r($this->paketnya);

		// extend data
		$jamaah_count = $this->db
			->from('pembayaran_transaksi_paket')
			->join('transaksi_paket', 'pembayaran_transaksi_paket.id_transaksi_paket = transaksi_paket.id')
			->group_by('jamaah')
			->where('pembayaran_transaksi_paket.deleted', null)
			->get()->num_rows(); 
		$sum = $this->db
			->from('pembayaran_transaksi_paket')
			->select("SUM(debet) debit, SUM(kredit) kredit")
			->where('deleted', null)
			->get()->row(); 
	
		$extra = [
			'jamaah_count' => number_format($jamaah_count, 0, ',', '.'),
			'debit_sum' => number_format($sum->debit, 2, ',', '.'),
			'kredit_sum' =>  number_format($sum->kredit, 2, ',', '.'),
			'tag' => 'laporan_harian'
		];
		$this->crud
		->set_footer($extra);
		$this->show();
	}

	function _histori($value, $row){
		$delete = '';
		if($value == 1){
			$_user = '';
			$user = $this->db->select('nama')
			->from('admin')
			->where('id_admin', $row->deleted_by)
			->get()->row();
			if($user) $_user = $user->nama;
			$delete = 'deleted: ' .  $row->deleted_at .' | '. $_user;
		}
		return $delete;
	}

	function _date_format($value, $row)
	{
		if($value == '') return '';
		return $value = date_format(new DateTime($value),"d/m/Y");
		
	}

	public function _tanggal($value, $row)
	{
		return implode("-", array_reverse(explode("/", $value)));
	}

	function like(array $arr, string $patron): array
	{
		return array_filter($arr, static function (mixed $value) use ($patron): bool {
			return 1 === preg_match(sprintf('/^%s$/i', preg_replace('/(^%)|(%$)/', '.*', $patron)), $value);
		});
	}

	function _jamaah($value, $row)
	{
		// return $value;

		if ($value) {
			$d = (explode("-", $value));
			// echo "Jamaah: <br>".$d[1];
			// print_r($row);
			if (isset($d[0]) && isset($d[1]) && isset($this->j[$d[0]])) {
				$x = base_convert($d[1], 36, 10);
				if(isset($this->paketnya[$x])){
					$paket = $this->paketnya[$x];
					if (isset($paket))
						// print_r($this->j[$d[0]]);

						return $this->j[$d[0]] . '/' . $paket;
					//	return $this->j[$d[0]].'/'.$d[1].'/'.$paket;
					return $this->j[$d[0]] . '/' . $d[1] . '/-';
				}else{
					return '-';
				}
				
			}
		}
		return "-";
	}
	function log()
	{
	}
}
// END Kelas Class

/* End of file kelas.php */
/* Location: ./system/application/controllers/kelas.php */