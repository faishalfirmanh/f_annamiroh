<?php

/**
 * Kelas Class
 *
 * @author	Awan Pribadi Basuki <awan_pribadi@yahoo.com>
 */
class Transaksi_Op extends CI_Controller
{
	/**
	 * Constructor
	 */
	var $t = array();
	var $r = 0;
	var $crud = null;
	var $j = array();
	var $l = '';

	function __construct()
	{
		parent::__construct();
		if ($this->session->userdata('login') != TRUE) {
			redirect('login');
		}
		$this->load->database();
		setlocale(LC_MONETARY, 'id_ID');
		$this->load->helper('url');
		$this->load->model('main_model', '', TRUE);
		$this->load->model('Riwayat_transaksi_jamaah_model', '', TRUE);
		//$this->main_model->check_access();
		$this->load->library('grocery_CRUD');
		$this->crud = new Grocery_CRUD();
		$this->_init();
	}

	private function _init()
	{
		$this->output->set_template('admin');
		
		$ide = $this->session->userdata('level');

		$this->output->set_output_data('menu', $this->main_model->get_menu($ide));
		$this->crud->set_language("indonesian");
		$this->load->js('assets/themes/default/js/jquery-1.9.1.min.js');
		$this->load->js('assets/themes/default/js/jquery-migrate-3.4.1.js');

		$this->load->js('assets/themes/default/hero_files/bootstrap-transition.js');
		$this->load->js('assets/themes/default/hero_files/bootstrap-collapse.js');
		// $this->load->js('https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js');
		// $this->load->css('https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css');
		// $this->crud->set_css('https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css');
		// $this->crud->set_js('https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js');


	}

	/**
	 * Memeriksa user state, jika dalam keadaan login akan menampilkan halaman kelas,
	 * jika tidak akan meredirect ke halaman login
	 */
	function index()
	{
		redirect('master/jamaah');
	}

	private function showPembayaran($paket)
	{
		$this->crud->set_theme('twitter-bootstrap')->unset_export();
		$output = $this->crud->render();
		// $kurs = $this->main_model->get_kurs();
		// $this->load->section('sidebar', 'ci_simplicity/kurs',array('kurs'=>$kurs));
		$this->output->js_files[] = '<script>
			$(document).ready(function() {
				// Inisialisasi Select2 pada elemen berdasarkan ID
				$("#select_paket_umroh").select2(); 
			});
		</script>';
		//$output->link_tujuan_custom = site_url('master/link_share_jamaah/'.$paket);
	$output->link_tujuan_custom = site_url('JamaahLinkShare/formInputLinkShare/'.$paket);
		$this->load->view('ci_simplicity/admin_pembayaran', $output);
	}
	
	private function show()
	{
		$this->crud->set_theme('twitter-bootstrap')->unset_export();
		$output = $this->crud->render();
		// $kurs = $this->main_model->get_kurs();
		// $this->load->section('sidebar', 'ci_simplicity/kurs',array('kurs'=>$kurs));
		$this->output->js_files[] = '<script>
			$(document).ready(function() {
				// Inisialisasi Select2 pada elemen berdasarkan ID
				$("#select_paket_umroh").select2(); 
			});
		</script>';
		$this->load->view('ci_simplicity/admin', $output);
	}


	private function _show_v2()
	{
		$this->crud->set_theme('twitter-bootstrap');
		$output = $this->crud->render();

		$this->load->view('ci_simplicity/admin_v2', $output);
	}



	function jamaah($id_jamaah, $jenis_transaksi)
	{
		$this->crud = new grocery_CRUD();
		$this->crud->set_table('data_jamaah_paket');
		$this->crud->set_subject('Data Paket Umroh');
		$this->crud->set_theme('twitter-bootstrap');
		$this->crud->set_relation('hotel', 'data_hotel', 'nama');
		$this->crud->set_relation('Penerbangan', 'data_maskapai', 'nama');
		$this->crud->set_subject('Data Paket Umroh');
		$this->crud->unset_read()->columns('estimasi_keberangkatan', 'Program', 'Penerbangan', 'hotel', 'harga', 'sisa_kursi');
		$this->show();
	}
	function get_row($table, $id, $id_val)
	{
		$query = $this->db->get_where($table, array($id => $id_val));
		foreach ($query->result() as $row) {
			return $row;
		}
		return null;
	}
	function get($table, $id, $id_val, $kolom)
	{
		$query = $this->db->get_where($table, array($id => $id_val));
		foreach ($query->result() as $row) {
			return $row->$kolom;
		}
		return null;
	}
	public function _callback_webpage_url($value, $row)
	{
		// print_r($row);
		// var_dump("2222");
		// die();
		// $this->db->where('paket_umroh', $row->id);
		// $this->db->from('transaksi_paket');
		// $j=$this->db->count_all_results();
		$jumlae = isset($this->j[$row->id]) ? $this->j[$row->id] : 0;
		return "<a href='" . site_url('transaksi_op/pembayaran/' . $row->id) . "' target='_blank'>$value</a> - <a href='" . site_url('transaksi_op/manifest/' . $row->id) . "'target='_blank'>$jumlae orang</a>";
	}
	function _harga_rp($value, $row)
	{
		$harga = number_format($this->main_model->get_kurs() * $row->harga_dolar, 0, ".", ",");
		return $harga;
	}
	function harga_field_callback_1()
	{
		return '<input type="text" maxlength="50" value="000' . $this->r . '" name="harga" style="width:462px">';
	}

	function _rupiah($value, $row)
	{

		// return number_format((float)$value,0,'.',',');
		return number_format($value, 0, ",", ".");
	}
	function edit_callback($value, $row)
	{
		$x = $row->jamaah;
		if ($value == '')
			$value = '?';
		return "<a href='" . site_url('master/jamaah/edit/' . $x) . "' target='_blank'>$value</a>";
	}
	function edit_callback1($value, $row)
	{
		$x = $row->jamaah;
		$value = isset($this->jamaahnya[$x]) ? $this->jamaahnya[$x][1] : '';
		if ($value == '')
			$value = '?';
		return "<a href='" . site_url('master/jamaah/edit/' . $x) . "' target='_blank'>$value</a>";
	}
	function edit_callback2($value, $row)
	{
		$x = $row->jamaah;
		$value = isset($this->jamaahnya[$x]) ? $this->jamaahnya[$x][2] : '';
		if ($value == '')
			$value = '?';
		return "<a href='" . site_url('master/jamaah/edit/' . $x) . "' target='_blank'>$value</a>";
	}
	function edit_callback3($value, $row)
	{
		$x = $row->jamaah;
		$value = isset($this->jamaahnya[$x]) ? $this->jamaahnya[$x][3] : '';
		if ($value == '')
			$value = '?';
		return "<a href='" . site_url('master/jamaah/edit/' . $x) . "' target='_blank'>$value</a>";
	}
	function edit_callback4($value, $row)
	{
		$x = $row->jamaah;
		$value = isset($this->jamaahnya[$x]) ? $this->jamaahnya[$x][4] : '';
		if ($value == '')
			$value = '?';
		return "<a href='" . site_url('master/jamaah/edit/' . $x) . "' target='_blank'>$value</a>";
	}
	function edit_callback5($value, $row)
	{
		$x = $row->jamaah;
		$value = isset($this->jamaahnya[$x]) ? $this->jamaahnya[$x][5] : '';
		if ($value == '')
			$value = '?';
		return "<a href='" . site_url('master/jamaah/edit/' . $x) . "' target='_blank'>$value</a>";
	}
	function _date_format($value, $row)
	{
		if ($value == '')
			return '';
		return $value = date_format(new DateTime($value), "d-m-Y");

	}

	function edit_callback5_with_date($value, $row)
	{
		$x = $row->jamaah;
		$value = isset($this->jamaahnya[$x]) ? $this->jamaahnya[$x][5] : '';
		if ($value == '')
			$value = '?';
		else
			$value = date_format(new DateTime($value), "d-m-Y");
		return "<a href='" . site_url('master/jamaah/edit/' . $x) . "' target='_blank'>$value</a>";
	}
	function edit_callback6($value, $row)
	{
		$x = $row->jamaah;
		$value = isset($this->jamaahnya[$x]) ? $this->jamaahnya[$x][6] : '';
		if ($value == '')
			$value = '?';
		return "<a href='" . site_url('master/jamaah/edit/' . $x) . "' target='_blank'>$value</a>";
	}
	function edit_callback6_with_date($value, $row)
	{
		$x = $row->jamaah;
		$value = isset($this->jamaahnya[$x]) ? $this->jamaahnya[$x][6] : '';
		if ($value == '')
			$value = '?';
		else
			$value = date_format(new DateTime($value), "d-m-Y");

		return "<a href='" . site_url('master/jamaah/edit/' . $x) . "' target='_blank'>$value</a>";
	}
	function edit_callback7($value, $row)
	{
		$x = $row->jamaah;
		$value = isset($this->jamaahnya[$x]) ? $this->jamaahnya[$x][7] : '';
		if ($value == '')
			$value = '?';
		return "<a href='" . site_url('master/jamaah/edit/' . $x) . "' target='_blank'>$value</a>";
	}
	function edit_callback8($value, $row)
	{
		$x = $row->jamaah;
		$value = isset($this->jamaahnya[$x]) ? $this->jamaahnya[$x][8] : '';
		if ($value == '')
			$value = '?';
		return "<a href='" . site_url('master/jamaah/edit/' . $x) . "' target='_blank'>$value</a>";
	}
	function edit_callback8_with_date($value, $row)
	{
		$x = $row->jamaah;
		$value = isset($this->jamaahnya[$x]) ? $this->jamaahnya[$x][8] : '';
		if ($value == '' || $value == '0000-00-00')
			$value = '?';
		else
			$value = date_format(new DateTime($value), "d-m-Y");

		return "<a href='" . site_url('master/jamaah/edit/' . $x) . "' target='_blank'>$value</a>";
	}
	function edit_callback9($value, $row)
	{
		$x = $row->jamaah;
		$value = isset($this->jamaahnya[$x]) ? $this->jamaahnya[$x][9] : '';
		if ($value == '')
			$value = '?';
		return "<a href='" . site_url('master/jamaah/edit/' . $x) . "' target='_blank'>$value</a>";
	}

	function edit_callback10($value, $row)
	{
		$x = $row->jamaah;
		$value = isset($this->jamaahnya[$x]) ? $this->jamaahnya[$x][10] : '';
		if ($value == '')
			$value = '?';
		return "<a href='" . site_url('master/jamaah/edit/' . $x) . "' target='_blank'>$value</a>";
	}
	function edit_callback11($value, $row)
	{
		$x = $row->jamaah;
		$value = isset($this->jamaahnya[$x]) ? $this->jamaahnya[$x][11] : '';
		if ($value == '')
			$value = '?';
		return "<a href='" . site_url('master/jamaah/edit/' . $x) . "' target='_blank'>$value</a>";
	}
	function edit_callback12($value, $row)
	{
		$x = $row->jamaah;
		$value = isset($this->jamaahnya[$x]) ? $this->jamaahnya[$x][12] : '';
		if ($value == '')
			$value = '?';
		return "<a href='" . site_url('master/jamaah/edit/' . $x) . "' target='_blank'>$value</a>";
	}
	function edit_callback13($value, $row)
	{
		$x = $row->jamaah;
		$value = isset($this->jamaahnya[$x]) ? $this->jamaahnya[$x][13] : '';
		$nama = $this->get('data_jamaah_agen', 'id', $value, 'nama');
		if ($value == '' || $value == '0')
			$nama = '?';
		return "<a href='" . site_url('master/jamaah/edit/' . $x) . "' target='_blank'>" . $nama . "</a>";
	}
	function kekurangan_manifest($value, $row)
	{
		//print_r($row);
		$value = $row->harga - $row->kredit + $row->debet;
		//$value .= $row->harga.','.$row->kredit.','.$row-debet;
		return "<a href='" . site_url('transaksi_op/histori/' . $row->id) . "' target='_blank'>" . $this->_rupiah($value, $row) . "</a>";
	}
	function kekurangan_pembayaran($value, $row)
	{
		//print_r($row);
		$value = $row->harga - $row->kredit + $row->debet;
		//$value .= $row->harga.','.$row->kredit.','.$row-debet;
		return "<a href='" . site_url('transaksi_op/histori/' . $row->id) . "' target='_blank'>" . $this->_rupiah($value, $row) . "</a>";
	}
	function manifest($paket = 0)
	{
		$this->l = $paket;
		$state = $this->crud->getState();


		$s = $this->get('data_jamaah_paket', 'id', $paket, 'estimasi_keberangkatan') . '<br>';

		$d = $this->db->query("select id_jamaah,nama_jamaah,title,place,age,place,issued, passport,expired,office,no_tlp,hp_jamaah,alamat_jamaah,tgl_lahir,no_ktp, agen from data_jamaah");
		// print_r($d->result());
		foreach ($d->result() as $row) {
			$this->jamaahnya[$row->id_jamaah] = array(
				$row->nama_jamaah,
				$row->title,
				$row->age,
				$row->passport,
				$row->place,
				$row->issued,
				$row->expired,
				$row->office,
				$row->tgl_lahir,
				$row->no_tlp,
				$row->hp_jamaah,
				$row->alamat_jamaah,
				$row->no_ktp,
				$row->agen
			);
		}
		// print_r($this->jamaahnya);

		$r = $this->get('data_jamaah_paket', 'id', $paket, 'harga');
		$this->r = $r;
		if ($r <> null)
			$s .= 'Rp.' . $this->main_model->uang($r);
		$this->grocery_crud->callback_add_field('harga', array($this, 'harga_field_callback_1'))->unset_edit();
		$this->crud
			->set_table('transaksi_paket')
			->set_subject('Manifest Jamaah ' . $s)
			->unset_read()->columns('jamaah', 'kekurangan', 'title', 'age', 'passport', 'place', 'expired', 'issued', 'office', 'tgl_lahir', 'tlp', 'hp', 'alamat', 'no_ktp', 'agen');
		$this->crud->callback_column('kekurangan', array($this, 'kekurangan_manifest'));
		$this->crud->callback_column('title', array($this, 'edit_callback1'));
		$this->crud->callback_column('age', array($this, 'edit_callback2'));
		$this->crud->callback_column('passport', array($this, 'edit_callback3'));
		$this->crud->callback_column('place', array($this, 'edit_callback4'));
		$this->crud->callback_column('issued', array($this, 'edit_callback5_with_date'));
		$this->crud->callback_column('expired', array($this, 'edit_callback6_with_date'));
		$this->crud->callback_column('office', array($this, 'edit_callback7'));

		$this->crud->callback_column('tgl_lahir', array($this, 'edit_callback8_with_date'));
		$this->crud->callback_column('tlp', array($this, 'edit_callback9'));
		$this->crud->callback_column('hp', array($this, 'edit_callback10'));
		$this->crud->callback_column('alamat', array($this, 'edit_callback11'));
		$this->crud->callback_column('no_ktp', array($this, 'edit_callback12'));
		$this->crud->callback_column('agen', array($this, 'edit_callback13'));
		//'','','',''

		$this->crud
			->field_type('kode', 'readonly')
			->set_relation('jamaah', 'data_jamaah', '{nama_jamaah}', 'nama_jamaah <> ""')
			->display_as('jamaah', 'Nama Jamaah')
			->display_as('kekurangan', 'Kekurangan (IDR)')
			->display_as('title', 'Sebutan')
			->display_as('age', 'Usia (Th)')
			->display_as('passport', 'No Paspor')
			->display_as('place', 'Lokasi')
			->display_as('expired', 'Tgl Habis Berlaku')
			->display_as('issued', 'Tgl Pengeluaran')
			->display_as('tgl_lahir', 'Tgl Lahir')
			->display_as('tlp', 'No Telepon')
			->display_as('hp', 'No Hp')
			->display_as('no_ktp', 'NIK')
			->display_as('agen', 'Agen');

		if ($paket != 0)
			$this->crud->where(array('paket_umroh' => $paket));

		$this->crud->unset_add()->unset_edit()->unset_delete();

		$this->crud->set_import(base_url() . 'transaksi_op/manifest_import');
		$this->crud->set_theme('twitter-bootstrap');
		$output = $this->crud->render();
		$this->load->view('ci_simplicity/admin', $output);
	}

	function manifest_import()
	{
		$this->load->view('ci_simplicity/manifest_import');
	}

	function manifest_import_do()
	{
		$this->load->library(array('excel'));
		if (isset($_FILES['fileExcel']['name']) && $_FILES['fileExcel']['name'] != '') {
			$path = $_FILES["fileExcel"]["tmp_name"];
			$object = PHPExcel_IOFactory::load($path);
			foreach ($object->getWorksheetIterator() as $worksheet) {
				$highestRow = $worksheet->getHighestRow();
				$highestColumn = $worksheet->getHighestColumn();
				for ($row = 2; $row <= $highestRow; $row++) {
					$nama_jamaah = $worksheet->getCellByColumnAndRow(0, $row)->getValue();
					$title = $worksheet->getCellByColumnAndRow(1, $row)->getValue();
					$passport = $worksheet->getCellByColumnAndRow(2, $row)->getValue();
					$expired = $worksheet->getCellByColumnAndRow(3, $row)->getValue();
					$issued = $worksheet->getCellByColumnAndRow(4, $row)->getValue();
					$office = $worksheet->getCellByColumnAndRow(5, $row)->getValue();
					$tgl_lahir = $worksheet->getCellByColumnAndRow(6, $row)->getValue();
					$no_tlp = $worksheet->getCellByColumnAndRow(7, $row)->getValue();
					$hp_jamaah = $worksheet->getCellByColumnAndRow(8, $row)->getValue();
					$alamat_jamaah = $worksheet->getCellByColumnAndRow(9, $row)->getValue();
					$no_ktp = $worksheet->getCellByColumnAndRow(10, $row)->getValue();

					if ($no_ktp == null)
						continue;
					$no_ktps[] = $no_ktp;
					$data[] = array(
						'nama_jamaah' => $nama_jamaah,
						'title' => $title,
						'passport' => $passport,
						'expired' => $expired,
						'issued' => $issued,
						'office' => $office,
						'tgl_lahir' => $tgl_lahir,
						'no_tlp' => $no_tlp,
						'hp_jamaah' => $hp_jamaah,
						'alamat_jamaah' => $alamat_jamaah,
						'no_ktp' => $no_ktp
					);
				}

				$this->load->model('transaksi_op_model', 'transaksi_op');
				$existing_data = $this->transaksi_op->get_by_no_ktp($no_ktps);
				if (!$existing_data) {
					$this->transaksi_op->add($data);
					$this->load->view('ci_simplicity/manifest_import', ['data' => $data, 'success' => true]);
				} else {
					$this->load->view('ci_simplicity/manifest_import', ['data' => $existing_data, 'success' => false]);
				}

			}
		} else {
			$this->load->view('ci_simplicity/manifest_import', ['data' => [], 'success' => false]);
		}
	}



	function update_kekurangan_on_harga($post_array, $primary_key)
	{

		$harga = isset($post_array['harga']) ? $post_array['harga'] : 0;
		$kredit = isset($post_array['kredit']) ? $post_array['kredit'] : 0;
		$debet = isset($post_array['debet']) ? $post_array['debet'] : 0;
		$kekurangan = $harga - $kredit + $debet;

		$this->db->update('transaksi_paket', array('kekurangan' => $kekurangan), array('id' => $primary_key));

		return true;
	}

	function historyJamaah($id_jamaah)
	{
		if (empty($id_jamaah)) {
			show_error('ID Jamaah tidak ditemukan.');
			return;
		}

		try {
			$this->crud = new grocery_CRUD();
			$this->crud->set_theme('datatables');
			$this->crud->set_subject('Riwayat Transaksi Jamaah: ID ' . $id_jamaah);

			$this->crud->set_table('view_riwayat_transaksi_lengkap');

			$this->crud->set_primary_key('id_transaksi_paket', 'view_riwayat_transaksi_lengkap');

			$this->crud->fields(
				'id_transaksi_paket', // DIPERBAIKI: Harus dimasukkan jika ada di VIEW
				'tanggal_transfer',
				'debet',
				'kredit',
				'keterangan',
				'nama_jamaah',
				'nama_transaksi',
				'nama_paket',
				'jamaah_id_filter'
			);


			$this->crud->where('jamaah_id_filter', $id_jamaah);

			$this->crud->unset_add();
			$this->crud->unset_edit();
			$this->crud->unset_delete();
			$this->crud->unset_read();
			$this->crud->unset_export();
			$this->crud->unset_print();


			$this->crud->columns(

				'id_transaksi_paket',
				'tanggal_transfer',
				'debet',
				'kredit',
				'nama_transaksi',
				'nama_paket',
				'keterangan', // Tampilkan kolom keterangan
				'nama_jamaah'

			);

			$this->crud->display_as('id_transaksi_paket', 'ID Transaksi')
				->display_as('tanggal_transfer', 'Tgl Transfer')
				->display_as('debet', 'Nominal Debet')
				->display_as('kredit', 'Nominal Kredit')
				->display_as('saldo', 'Saldo')
				->display_as('nama_jamaah', 'Nama Jamaah')
				->display_as('nama_transaksi', 'Jenis Transaksi')
				->display_as('nama_paket', 'Paket Umroh');

			$this->show();

		} catch (Exception $e) {
			show_error($e->getMessage() . ' --- ' . $e->getTraceAsString());
		}
	}

	function listJamaah()
	{
		$user_id = $this->session->userdata('id_admin');
		$user = $this->db->from('admin')->where('id_admin', $user_id)->get()->row();
		$this->crud = new grocery_CRUD();
		$this->crud->set_table('data_jamaah')->unset_read()->columns('id_jamaah', 'agen', 'no_ktp', 'nama_jamaah')->fields('id_jamaah', 'agen', 'no_ktp', 'nama_jamaah');

		$this->crud->where("id_jamaah IN (SELECT tp.jamaah FROM transaksi_paket tp)");
		$this->crud->unset_edit();
		$this->crud->unset_delete();
		$this->crud->set_theme('datatables');
		//$this->crud->set_subject('Data Leader Umroh')
		$this->crud->display_as('id_jamaah', 'No Jamaah');
		$this->crud->display_as('nama_jamaah', 'Nama Jamaah');
		$this->crud->display_as('no_ktp', 'No Ktp');
		$this->crud->display_as('agen', 'Agen ');
		$this->crud->add_action(
			'Detail',
			'icon-trash',
			'Transaksi_op/historyJamaah'
		);
		$this->show();
	}


	public function _callback_detil($value, $row)
	{
		$sum = $this->db->select('sum(harga*qty) tagihan,SUM(debet) debit, SUM(kredit) kredit')->from('transaksi_paket')->where('paket_umroh', $row->id)->get()->row();
		$debit = ($sum->debit);
		$kredit = ($sum->kredit);
		$saldo = $sum->kredit - $sum->debit;
		$jumlae = isset($this->j[$row->id]) ? $this->j[$row->id] : 0;
		$total_tagihan = $sum->tagihan;
		$kekurangan = ($total_tagihan) - $kredit + $debit;
		$tt = $this->format_rp($total_tagihan);
		$ddebit = $this->format_rp($debit);
		$dkredit = $this->format_rp($kredit);
		$dsaldo = $this->format_rp($saldo);
		$dkekurangan = $this->format_rp($kekurangan);
		if ($total_tagihan <= 0)
			$dkekurangan = 'Data paket tidak tersedia';
		//	return 0;
		return "<a href='" . site_url('transaksi/pembayaran/' . $row->id) . "' target='_blank'> 
		 <table>
        <thead>
            <tr>
                <th>Kredit</th>
                <th>Debit</th>
                
                <th>Tagihan</th>
                <th>Saldo</th>
                <th>Kekurangan</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>$dkredit</td>
                <td>$ddebit</td>
               
                <td>$tt</td>
                <td>$dsaldo</td>
                <td>$dkekurangan</td>
            </tr>
        </tbody>
    </table></a>";
	}
	public function _callback_tagihan($value, $row)
	{
		//echo '<pre>';
		$q = 0;
		$r = 0;
		//print_r($row);
		$total = $row->qty * $row->harga;
		//echo '</pre>';
		// $q = $row["qty"];
		// $r = $row["tagihan"];
		return $this->format_rp($total) . "<br>(" . $this->format_rp($row->harga) . ")";

		//$row['qty']*$row['tagihan'];
	}
	function pembayaran($paket = 0, $jamaah = 0)
	{
	    

       // var_dump($paket);
       // echo "<br>";
       //var_dump($jamaah); 
       // die();

		$user_id = $this->session->userdata('id_admin');
		$user = $this->db->from('admin')->where('id_admin', $user_id)->get()->row();

		$this->crud->set_exceptions([$user->level]);

		$this->crud->set_is_invoice(false);

		//  var_dump($paket);
		//  var_dump($jamaah);
		//  die();
		if ($paket == 0) {
			$this->crud->where('KET', 'AKTIF');
			$this->crud->set_table('data_jamaah_paket')->unset_read()
				->unset_read()->columns('estimasi_keberangkatan', 'qty', 'detil', 'tanggal_keberangkatan', 'Program')->fields('estimasi_keberangkatan', 'Program', 'harga', 'KET')->display_as('estimasi_keberangkatan', 'Pilih Paket')->unset_delete()->unset_add()->set_subject('Pilih Paket')->order_by('tanggal_keberangkatan', 'DESC');
			$this->db->select('paket_umroh');
			$this->crud->required_fields('qty');
			$this->crud
				->display_as('detil', 'Detail')
				->display_as('tanggal_keberangkatan', 'Tgl Keberangkatan');
			$this->crud->callback_column('tanggal_keberangkatan', array($this, '_date_format'));

			$query = $this->db->get('transaksi_paket');
			foreach ($query->result() as $row) {
				if (isset($this->j[$row->paket_umroh]))
					$this->j[$row->paket_umroh]++;
				else
					$this->j[$row->paket_umroh] = 1;
			}


			$this->crud->callback_column('estimasi_keberangkatan', array($this, '_callback_webpage_url'));
			$this->crud->callback_column('harga', array($this, '_harga_rp'));
			$this->crud->callback_column('detil', array($this, '_callback_detil'));
		} elseif ($jamaah == 0) {

			// var_dump("aass");
			// die();
			$s = $this->get('data_jamaah_paket', 'id', $paket, 'estimasi_keberangkatan') . '<br>';
			$this->crud->callback_after_update(array($this, 'update_kekurangan_on_harga'));
			$d = $this->db->query("select id_jamaah,nama_jamaah from data_jamaah");
			// print_r($d);
			foreach ($d->result() as $row) {
				$this->jamaahnya[$row->id_jamaah] = $row->nama_jamaah;
			}
			// print_r($this->jamaahnya);
			$r = $this->get('transaksi_paket', 'id', $jamaah, 'harga');
			$this->r = $r;

			$this->grocery_crud->callback_add_field('harga', array($this, 'harga_field_callback_1'))->unset_edit();
			$this->crud->set_table('transaksi_paket')->set_subject('Pembelian paket umroh ' . $s)
				->unset_read()->columns('jamaah', 'harga', 'kredit', 'debet', 'id_tipe_koper', 't_koper_jamaah', 'kode', 'agen', 'qty', 'metode');
			$this->crud->set_top('Pembelian paket umroh ' . $s);
			$this->crud->required_fields('agen', 'jamaah', 'paket_umroh','qty');
			$this->crud->field_type('kode', 'readonly')->set_relation('jamaah', 'data_jamaah', '{nama_jamaah}-{no_ktp}-{alamat_jamaah}-{no_tlp}', 'nama_jamaah <> ""');
			// $this->crud->callback_column('detil',array($this,'__jamaah'));

			//---default
			// $this->crud->callback_add_field('paket_umroh', function () use ($paket) {
			// 	$CI =& get_instance();
			// 	$paket_list = $CI->db->get('data_jamaah_paket')->result();
			// 	$html = '<select name="paket_umroh" id="select_paket_umroh" style="width:300px;" class="form-control select2">';

			// 	// Tambahkan opsi default/kosong
			// 	$html .= '<option value="">Pilih Paket Umroh</option>';
			// 	foreach ($paket_list as $row) {
			// 		$selected = (isset($paket) && $row->id == $paket) ? 'selected' : '';
			// 		$html .= '<option value="' . $row->id . '" ' . $selected . '>' .
			// 			$row->estimasi_keberangkatan .
			// 			'</option>';
			// 	}
			// 	$html .= '</select>';
			// 	return $html;
			// });
			//----default

			$this->crud->set_relation('paket_umroh', 'data_jamaah_paket', 'estimasi_keberangkatan');
			//$this->crud->callback_column('harga',array($this,'_rupiah'));
			//	$this->crud->callback_column('saldo',array($this,'_rupiah'));

			$this->crud->callback_column('kekurangan', array($this, 'kekurangan_pembayaran'));
			$this->crud->callback_column('debet', array($this, '__debet'));
			$this->crud->callback_column('kredit', array($this, '__kredit'));
			$this->crud->callback_column('t_koper_jamaah', array($this, '_count_barang_keluar'));
			$this->crud->callback_column('harga', array($this, '_callback_tagihan'));

			$this->crud->set_relation('agen', 'data_jamaah', 'nama_jamaah', ['is_agen' => 1]);
			$this->crud->set_relation('id_tipe_koper', 'm_tipe_koper', 'nama');

			$this->crud->add_fields(array('jamaah', 'harga', 'agen', 'paket_umroh', 'kekurangan', 'harga_normal', 'qty', 'tgl_deposit', 'tgl_pelunasan', 'permintaan_tambahan', 'metode'));
			$this->crud->callback_before_insert(array($this, '_update_kekurangan'));
			$this->crud->field_type('harga_normal', 'hidden', $r);
			$this->crud->field_type('kekurangan', 'hidden', $r);
			//$this->crud->field_type('paket_umroh', 'hidden', $paket);
			$this->crud->edit_fields('jamaah', 'harga', 'paket_umroh', 'qty');
			$this->crud->display_as('id_tipe_koper', 'Jenis Perlengkapan');
			$this->crud->display_as('t_koper_jamaah', 'Jumlah Barang yg Diambil');
			$this->crud->display_as('harga', 'Total Tagihan (IDR)');
			$this->crud->display_as('tgl_pelunasan', 'Tgl Pelunasan');
			$this->crud->display_as('tgl_deposit', 'Tgl Deposit');
			$this->crud->display_as('permintaan_tambahan', 'Permintaan Tambahan');
			$this->crud->display_as('debet', 'Debit (IDR)');
			$this->crud->display_as('kredit', 'Kredit (IDR)');
			/**
			 * author: Irul
			 * date: 08/03/2024 
			 * revision: add metode column
			 */
			$this->crud->display_as('metode', 'Cara Bayar');

			$this->crud->unset_texteditor('permintaan_tambahan', 'full_text');
			$this->crud->data['-tes'] = '-';

			$this->crud->callback_after_insert(array($this, 'fix_code_after_insert'))->where(array('paket_umroh' => $paket));
		} else {//masuk lewat master->jamaah
            // BARU
           // var_dump(22);
           // die();
			$s = $this->get('data_jamaah_paket', 'id', $paket, 'estimasi_keberangkatan') . '<br>';
			$d = $this->db->query("select id_jamaah,nama_jamaah from data_jamaah");
			// print_r($d);
			foreach ($d->result() as $row) {
				$this->jamaahnya[$row->id_jamaah] = $row->nama_jamaah;
			}
			// print_r($this->jamaahnya);
			$r = $this->get('data_jamaah_paket', 'id', $paket, 'harga');
			$this->r = $r;
			$s .= 'Rp.' . $this->main_model->uang($r);
			//$this->grocery_crud->callback_add_field('harga',array($this,'harga_field_callback_1'));
			$this->crud->unset_add();
			$this->crud->set_table('transaksi_paket')
				->set_subject('Pembelian paket umroh ' . $s)
				->set_top('Pembelian paket umroh ' . $s)
				->unset_read()->columns('jamaah', 'harga', 'kredit', 'debet', 'id_tipe_koper', 't_koper_jamaah', 'kode', 'agen');
			$this->crud->field_type('kode', 'readonly')->set_relation('jamaah', 'data_jamaah', '{nama_jamaah}-{no_ktp}-{alamat_jamaah}-{no_tlp}', 'nama_jamaah <> ""');
			// $this->crud->callback_column('detil',array($this,'__jamaah'));

			$this->crud->set_relation('paket_umroh', 'data_jamaah_paket', 'estimasi_keberangkatan');
			$this->crud->callback_column('harga', array($this, '_rupiah'));
			$this->crud->callback_column('saldo', array($this, '_rupiah'));
			$this->crud->callback_column('kekurangan', array($this, 'kekurangan_pembayaran'));
			$this->crud->callback_column('debet', array($this, '__debet'));
			$this->crud->callback_column('kredit', array($this, '__kredit'));
			$this->crud->callback_column('t_koper_jamaah', array($this, '_count_barang_keluar'));
			$this->crud->set_relation('agen', 'data_jamaah_agen', 'nama');
			//$this->crud->add_fields(array('jamaah','harga','paket_umroh','kekurangan','harga_normal','agen'));
			//$this->crud->callback_before_insert(array($this,'_update_kekurangan'));
			$this->crud->field_type('harga_normal', 'hidden', $r);
			$this->crud->set_relation('id_tipe_koper', 'm_tipe_koper', 'nama');

			$this->crud->field_type('kekurangan', 'hidden', $r);
			$this->crud->edit_fields('jamaah', 'paket_umroh');
			$this->crud->display_as('id_tipe_koper', 'Jenis Perlengkapan');
			$this->crud->display_as('t_koper_jamaah', 'Jumlah barang yg diambil');

			$this->crud->data['-tes'] = '-';

			//$this->crud->callback_after_insert(array($this, 'fix_code_after_insert'));
            $this->crud->where(array('paket_umroh' => $paket, 'jamaah' => $jamaah));
			$this->crud->callback_delete(array($this, '_delete_data_jamaah_paket'));
		
		}

		if ($this->session->userdata('level') == 7) {
			$this->crud->unset_columns('harga', 'saldo', 'kredit', 'debet');
		}

		$this->showPembayaran($paket);

    }

	function _delete_data_jamaah_paket($primary_key)
	{
		//$user_id = $ide = $this->session->userdata('id_admin');
		//return $this->db->update('data_jamaah_paket', array('deleted' => '1', 'deleted_at' => date("Y-m-d H:i:s"), 'deleted_by' => $user_id), array('id' => $primary_key));

        //var_dump(333);
        //die();
        $user_id = $this->session->userdata('id_admin');
        return $this->db->delete('transaksi_paket',array('id'=>$primary_key));

    }

	// function pembayaran2($paket = 0, $jamaah = 0)
	// {
	// 	if ($paket == 0) {
	// 		$this->crud->where('KET', 'AKTIF');
	// 		$this->crud->set_table('data_jamaah_paket')->unset_read()->columns('estimasi_keberangkatan', 'qty', 'detil', 'tanggal_keberangkatan', 'Program', 'kekurangan')->fields('estimasi_keberangkatan', 'Program', 'harga', 'KET')->display_as('estimasi_keberangkatan', 'Pilih Paket')->unset_delete()->unset_add()->set_subject('Pilih Paket')->order_by('tanggal_keberangkatan', 'DESC');
	// 		$this->db->select('paket_umroh');

	// 		$query = $this->db->get('transaksi_paket');
	// 		foreach ($query->result() as $row) {
	// 			if (isset($this->j[$row->paket_umroh]))
	// 				$this->j[$row->paket_umroh]++;
	// 			else
	// 				$this->j[$row->paket_umroh] = 1;
	// 		}
	// 		$this->crud->callback_column('estimasi_keberangkatan', array($this, '_callback_webpage_url'));
	// 		$this->crud->callback_column('harga', array($this, '_harga_rp'));
	// 	} elseif ($jamaah == 0) {
	// 		$s = $this->get('data_jamaah_paket', 'id', $paket, 'estimasi_keberangkatan') . '<br>';
	// 		$this->crud->callback_after_update(array($this, 'update_kekurangan_on_harga'));
	// 		$d = $this->db->query("select id_jamaah,nama_jamaah from data_jamaah");
	// 		// print_r($d);
	// 		foreach ($d->result() as $row) {
	// 			$this->jamaahnya[$row->id_jamaah] = $row->nama_jamaah;
	// 		}
	// 		// print_r($this->jamaahnya);
	// 		$r = $this->get('transaksi_paket', 'id', $jamaah, 'harga');
	// 		$this->r = $r;

	// 		$this->grocery_crud->callback_add_field('harga', array($this, 'harga_field_callback_1'))->unset_edit();
	// 		$this->crud->set_table('transaksi_paket')->set_subject('Pembelian paket umroh ' . $s)->unset_read()->columns('jamaah', 'harga', 'kredit', 'debet', 'kode', 'agen');
	// 		$this->crud->field_type('kode', 'readonly')->set_relation('jamaah', 'data_jamaah', '{nama_jamaah}-{no_ktp}-{alamat_jamaah}-{no_tlp}', 'nama_jamaah <> ""');
	// 		// $this->crud->callback_column('detil',array($this,'__jamaah'));

	// 		$this->crud->set_relation('paket_umroh', 'data_jamaah_paket', 'estimasi_keberangkatan');
	// 		//$this->crud->callback_column('harga',array($this,'_rupiah'));
	// 		//	$this->crud->callback_column('saldo',array($this,'_rupiah'));
	// 		$this->crud->callback_column('kekurangan', array($this, 'kekurangan_pembayaran'));
	// 		$this->crud->callback_column('debet', array($this, '__debet'));
	// 		$this->crud->callback_column('kredit', array($this, '__kredit'));
	// 		$this->crud->set_relation('agen', 'data_jamaah_agen', 'nama');
	// 		$this->crud->add_fields(array('jamaah', 'harga', 'paket_umroh', 'kekurangan', 'harga_normal', 'agen'));
	// 		$this->crud->callback_before_insert(array($this, '_update_kekurangan'));
	// 		$this->crud->field_type('harga_normal', 'hidden', $r);
	// 		$this->crud->field_type('kekurangan', 'hidden', $r);
	// 		//$this->crud->field_type('paket_umroh', 'hidden', $paket);
	// 		$this->crud->edit_fields('jamaah', 'harga', 'paket_umroh');
	// 		$this->crud->data['-tes'] = '-';

	// 		$this->crud->callback_after_insert(array($this, 'fix_code_after_insert'))->where(array('paket_umroh' => $paket));
	// 	} else {
	// 		$s = $this->get('data_jamaah_paket', 'id', $paket, 'estimasi_keberangkatan') . '<br>';

	// 		$d = $this->db->query("select id_jamaah,nama_jamaah from data_jamaah");
	// 		// print_r($d);
	// 		foreach ($d->result() as $row) {
	// 			$this->jamaahnya[$row->id_jamaah] = $row->nama_jamaah;
	// 		}
	// 		// print_r($this->jamaahnya);
	// 		$r = $this->get('data_jamaah_paket', 'id', $paket, 'harga');
	// 		$this->r = $r;
	// 		$s .= 'Rp.' . $this->main_model->uang($r);
	// 		//$this->grocery_crud->callback_add_field('harga',array($this,'harga_field_callback_1'));
	// 		$this->crud->unset_add();
	// 		$this->crud->set_table('transaksi_paket')->set_subject('Pembelian paket umroh ' . $s)->unset_read()->columns('jamaah', 'id_tipe_koper', 't_koper_jamaah', 'debet', 'kode', 'agen');
	// 		$this->crud->field_type('kode', 'readonly')->set_relation('jamaah', 'data_jamaah', '{nama_jamaah}-{no_ktp}-{alamat_jamaah}-{no_tlp}', 'nama_jamaah <> ""');
	// 		// $this->crud->callback_column('detil',array($this,'__jamaah'));

	// 		$this->crud->set_relation('paket_umroh', 'data_jamaah_paket', 'estimasi_keberangkatan');
	// 		$this->crud->callback_column('harga', array($this, '_rupiah'));
	// 		$this->crud->callback_column('saldo', array($this, '_rupiah'));
	// 		$this->crud->callback_column('kekurangan', array($this, 'kekurangan_pembayaran'));
	// 		$this->crud->callback_column('debet', array($this, '__debet'));
	// 		// $this->crud->callback_column('kredit', array($this, '__kredit'));
	// 		$this->crud->callback_column('t_koper_jamaah', array($this, '_count_barang_keluar'));
	// 		$this->crud->set_relation('agen', 'data_jamaah_agen', 'nama');
	// 		//$this->crud->add_fields(array('jamaah','harga','paket_umroh','kekurangan','harga_normal','agen'));
	// 		//$this->crud->callback_before_insert(array($this,'_update_kekurangan'));
	// 		$this->crud->field_type('harga_normal', 'hidden', $r);
	// 		$this->crud->set_relation('id_tipe_koper', 'm_tipe_koper', 'nama');

	// 		$this->crud->field_type('kekurangan', 'hidden', $r);
	// 		$this->crud->edit_fields('jamaah', 'paket_umroh');
	// 		$this->crud->display_as('id_tipe_koper', 'Jenis Perlengkapan');
	// 		$this->crud->display_as('t_koper_jamaah', 'Jumlah barang yg diambil');

	// 		$this->crud->data['-tes'] = '-';

	// 		//$this->crud->callback_after_insert(array($this, 'fix_code_after_insert'));
	// 		$this->crud->where(array('paket_umroh' => $paket, 'jamaah' => $jamaah));
	// 	}

	// 	$this->show();
	// }

	// function pembayaran1($paket = 0, $jamaah = 0)
	// {
	// 	if ($paket == 0) {
	// 		$this->crud->where('KET', 'AKTIF');
	// 		$this->crud->set_table('data_jamaah_paket')->unset_read()->columns('estimasi_keberangkatan', 'qty', 'detil', 'tanggal_keberangkatan', 'Program', 'kekurangan')->fields('estimasi_keberangkatan', 'Program', 'harga', 'KET')->display_as('estimasi_keberangkatan', 'Pilih Paket')->unset_delete()->unset_add()->set_subject('Pilih Paket')->order_by('tanggal_keberangkatan', 'DESC');
	// 		$this->db->select('paket_umroh');

	// 		$query = $this->db->get('transaksi_paket');
	// 		foreach ($query->result() as $row) {
	// 			if (isset($this->j[$row->paket_umroh]))
	// 				$this->j[$row->paket_umroh]++;
	// 			else
	// 				$this->j[$row->paket_umroh] = 1;
	// 		}
	// 		$this->crud->callback_column('estimasi_keberangkatan', array($this, '_callback_webpage_url'));
	// 		$this->crud->callback_column('harga', array($this, '_harga_rp'));
	// 	} elseif ($jamaah == 0) {
	// 		$s = $this->get('data_jamaah_paket', 'id', $paket, 'estimasi_keberangkatan') . '<br>';
	// 		$this->crud->callback_after_update(array($this, 'update_kekurangan_on_harga'));
	// 		$d = $this->db->query("select id_jamaah,nama_jamaah from data_jamaah");
	// 		// print_r($d);
	// 		foreach ($d->result() as $row) {
	// 			$this->jamaahnya[$row->id_jamaah] = $row->nama_jamaah;
	// 		}
	// 		// print_r($this->jamaahnya);
	// 		$r = $this->get('transaksi_paket', 'id', $jamaah, 'harga');
	// 		$this->r = $r;

	// 		$this->grocery_crud->callback_add_field('harga', array($this, 'harga_field_callback_1'))->unset_edit();
	// 		$this->crud->set_table('transaksi_paket')->set_subject('Pembelian paket umroh ' . $s)->unset_read()->columns('jamaah', 'harga', 'kredit', 'debet', 'kode', 'agen');
	// 		$this->crud->field_type('kode', 'readonly')->set_relation('jamaah', 'data_jamaah', '{nama_jamaah}-{no_ktp}-{alamat_jamaah}-{no_tlp}', 'nama_jamaah <> ""');
	// 		// $this->crud->callback_column('detil',array($this,'__jamaah'));

	// 		$this->crud->set_relation('paket_umroh', 'data_jamaah_paket', 'estimasi_keberangkatan');
	// 		//$this->crud->callback_column('harga',array($this,'_rupiah'));
	// 		//	$this->crud->callback_column('saldo',array($this,'_rupiah'));
	// 		$this->crud->callback_column('kekurangan', array($this, 'kekurangan_pembayaran'));
	// 		$this->crud->callback_column('debet', array($this, '__debet'));
	// 		$this->crud->callback_column('kredit', array($this, '__kredit'));
	// 		$this->crud->set_relation('agen', 'data_jamaah_agen', 'nama');
	// 		$this->crud->add_fields(array('jamaah', 'harga', 'paket_umroh', 'kekurangan', 'harga_normal', 'agen'));
	// 		$this->crud->callback_before_insert(array($this, '_update_kekurangan'));
	// 		$this->crud->field_type('harga_normal', 'hidden', $r);
	// 		$this->crud->field_type('kekurangan', 'hidden', $r);
	// 		//$this->crud->field_type('paket_umroh', 'hidden', $paket);
	// 		$this->crud->edit_fields('jamaah', 'harga', 'paket_umroh');
	// 		$this->crud->data['-tes'] = '-';

	// 		$this->crud->callback_after_insert(array($this, 'fix_code_after_insert'))->where(array('paket_umroh' => $paket));
	// 	} else {
	// 		$s = $this->get('data_jamaah_paket', 'id', $paket, 'estimasi_keberangkatan') . '<br>';

	// 		$d = $this->db->query("select id_jamaah,nama_jamaah from data_jamaah");
	// 		// print_r($d);
	// 		foreach ($d->result() as $row) {
	// 			$this->jamaahnya[$row->id_jamaah] = $row->nama_jamaah;
	// 		}
	// 		// print_r($this->jamaahnya);
	// 		$r = $this->get('data_jamaah_paket', 'id', $paket, 'harga');
	// 		$this->r = $r;
	// 		$s .= 'Rp.' . $this->main_model->uang($r);
	// 		//$this->grocery_crud->callback_add_field('harga',array($this,'harga_field_callback_1'));
	// 		$this->crud->unset_add();
	// 		$this->crud->set_table('transaksi_paket')->set_subject('Pembelian paket umroh ' . $s)->unset_read()->columns('jamaah', 'id_tipe_koper', 'kredit', 'debet', 'kode', 'agen');
	// 		$this->crud->field_type('kode', 'readonly')->set_relation('jamaah', 'data_jamaah', '{nama_jamaah}-{no_ktp}-{alamat_jamaah}-{no_tlp}', 'nama_jamaah <> ""');
	// 		// $this->crud->callback_column('detil',array($this,'__jamaah'));

	// 		$this->crud->set_relation('paket_umroh', 'data_jamaah_paket', 'estimasi_keberangkatan');
	// 		// $this->crud->callback_column('harga', array($this, '_rupiah'));
	// 		$this->crud->callback_column('saldo', array($this, '_rupiah'));
	// 		$this->crud->callback_column('kekurangan', array($this, 'kekurangan_pembayaran'));
	// 		$this->crud->callback_column('debet', array($this, '__debet'));
	// 		$this->crud->callback_column('kredit', array($this, '__kredit'));
	// 		$this->crud->set_relation('agen', 'data_jamaah_agen', 'nama');
	// 		$this->crud->set_relation('id_tipe_koper', 'm_tipe_koper', 'nama');
	// 		//$this->crud->add_fields(array('jamaah','harga','paket_umroh','kekurangan','harga_normal','agen'));
	// 		//$this->crud->callback_before_insert(array($this,'_update_kekurangan'));
	// 		$this->crud->field_type('harga_normal', 'hidden', $r);
	// 		$this->crud->field_type('kekurangan', 'hidden', $r);
	// 		// $this->crud->edit_fields('jamaah', 'id_tipe_koper', 'paket_umroh');
	// 		$this->crud->edit_fields('id_tipe_koper');
	// 		$this->crud->display_as('id_tipe_koper', 'Jenis Perlengkapan');

	// 		// $this->crud->edit_fields('jamaah', 'harga', 'paket_umroh');
	// 		$this->crud->data['-tes'] = '-';

	// 		//$this->crud->callback_after_insert(array($this, 'fix_code_after_insert'));
	// 		$this->crud->where(array('paket_umroh' => $paket, 'jamaah' => $jamaah));
	// 	}

	// 	$this->show();
	// }

	function barang_keluar($id_koper_jamaah = null)
	{
		if (!$id_koper_jamaah || $this->session->userdata('level') != 7) {
			redirect('master/jamaah');
		}

		if ($this->session->userdata('level') != 7) {
			$this->crud->unset_add()->unset_delete()->unset_edit(); //buat batasi crud
		}

		$this->crud->set_theme('twitter-bootstrap');
		$this->crud->set_table('t_barang_keluar');
		$output = $this->crud->render();

		$data['js_files'] = $output->js_files;
		$data['css_files'] = $output->css_files;


		$data['idKoperJamaah'] = $id_koper_jamaah;
		$data['urlSave'] = base_url("transaksi_op/add_barang_keluar");
		$data['koperJamaah'] = $cekKoperJamaah = $this->db->get_where('t_koper_jamaah', ['id' => $id_koper_jamaah])->row();
		if (!$cekKoperJamaah) {
			redirect('master/jamaah');
		}
		// $data['idPaket'] = $cekKoperJamaah->id_paket;
		// $data['idJamaah'] = $cekKoperJamaah->id_jamaah;

		$data['barangKeluar'] = $this->db->select('bk.*, b.nama AS nama_barang')
			->join('m_barang b', 'b.id = bk.id_barang')
			->get_where('t_barang_keluar bk', ['bk.id_koper_jamaah' => $id_koper_jamaah])->result();
		$this->load->view('transaksi/transaksi_barang_keluar', $data);
	}


	//JIKA 1 RESI & TANGGAL UNTUK BANYAK BARANG 
	// public function add_barang_keluar()
	// {
	// 	$this->db->trans_begin();
	// 	$param = $this->input->post();
	// 	try {
	// 		$cekKoperJamaah = $this->db->get_where('t_koper_jamaah',  ['id' => $param['id_koper_jamaah']])->row();
	// 		if (!$cekKoperJamaah) {
	// 			redirect('master/jamaah');
	// 		}

	// 		$dataKoperJamaah = [
	// 			'tanggal_keluar'        => $param['tanggal_keluar'],
	// 			'nomor_resi'            => $param['nomor_resi'],
	// 			'updated_at'            => date('Y-m-d H:i:s'),
	// 			'upated_by'            => $this->session->userdata('id_admin'),
	// 			'admin_input_keluar'    => $this->session->userdata('id_admin'),
	// 		];

	// 		$this->db->update('t_koper_jamaah', $dataKoperJamaah, ['id' => $param['id_koper_jamaah']]);

	// 		$getBarangKeluar  = $this->db->get_where('t_barang_keluar', ['id_koper_jamaah' => $param['id_koper_jamaah']])->result();

	// 		foreach (@$getBarangKeluar as $barangKeluar) {
	// 			if (array_key_exists('qty_' . $barangKeluar->id, $param)) {
	// 				$this->db->update('t_barang_keluar', ['is_active' => 1, 'jumlah' => $param['qty_' . $barangKeluar->id]], ['id' => $barangKeluar->id]);

	// 				$this->_after_insert_update_keluar_barang(['jumlah' => $param['qty_' . $barangKeluar->id], 'id_barang' => $barangKeluar->id_barang], $barangKeluar->id);
	// 			} else {
	// 				$this->db->update('t_barang_keluar', ['is_active' => null, 'jumlah' => 0], ['id' => $barangKeluar->id]);
	// 				$this->_after_delete_keluar_barang($barangKeluar->id);
	// 			}
	// 		}

	// 		$this->db->trans_commit();
	// 		redirect("transaksi_op/pembayaran2/" . $cekKoperJamaah->id_paket . "/" . $cekKoperJamaah->id_jamaah);
	// 	} catch (\Throwable $th) {
	// 		$this->db->trans_rollback();
	// 		redirect($_SERVER['HTTP_REFERER']);
	// 	}
	// }

	//JIKA 1 RESI & TANGGAL UNTUK 1 BARANG 
	public function add_barang_keluar()
	{
		$this->db->trans_begin();
		$param = $this->input->post();
		try {
			$cekKoperJamaah = $this->db->get_where('t_koper_jamaah', ['id' => $param['id_koper_jamaah']])->row();
			if (!$cekKoperJamaah) {
				redirect('master/jamaah');
			}

			$dataKoperJamaah = [
				'tanggal_keluar' => $param['tanggal_keluar'],
				'nomor_resi' => $param['nomor_resi'],
				'updated_at' => date('Y-m-d H:i:s'),
				'upated_by' => $this->session->userdata('id_admin'),
				'admin_input_keluar' => $this->session->userdata('id_admin'),
			];

			$this->db->update('t_koper_jamaah', $dataKoperJamaah, ['id' => $param['id_koper_jamaah']]);

			$getBarangKeluar = $this->db->get_where('t_barang_keluar', ['id_koper_jamaah' => $param['id_koper_jamaah']])->result();

			foreach (@$getBarangKeluar as $barangKeluar) {
				if (array_key_exists('qty_' . $barangKeluar->id, $param)) {
					$dataBarangKeluar =
						[
							'id_jamaah' => $cekKoperJamaah->id_jamaah,
							'id_paket' => $cekKoperJamaah->id_paket,
							'tanggal_keluar' => $param['tanggal_keluar'],
							'nomor_resi' => $param['nomor_resi'],
							'admin_input_keluar' => $this->session->userdata('id_admin'),
							'is_active' => 1,
							'jumlah' => $param['qty_' . $barangKeluar->id]
						];

					$this->db->update('t_barang_keluar', $dataBarangKeluar, ['id' => $barangKeluar->id]);

					$this->_after_insert_update_keluar_barang(['tanggal' => $param['tanggal_keluar'], 'jumlah' => $param['qty_' . $barangKeluar->id], 'id_barang' => $barangKeluar->id_barang], $barangKeluar->id);
				} else {
					$this->db->update('t_barang_keluar', ['is_active' => null, 'jumlah' => 0], ['id' => $barangKeluar->id]);
					$this->_after_delete_keluar_barang($barangKeluar->id);
				}
			}

			$this->db->trans_commit();
			redirect("laporan/barang_keluar/" . $cekKoperJamaah->id);
		} catch (\Throwable $th) {
			$this->db->trans_rollback();
			redirect($_SERVER['HTTP_REFERER']);
		}
	}

	function _after_insert_update_keluar_barang($post_array, $primary_key)
	{
		$where = ['tipe' => 'out', 'cf1' => $primary_key, 'cf3' => 't_barang_keluar'];
		$logBarangKeluar = $this->db->get_where('t_log_barang', $where)->row();
		$findBarang = $this->db->get_where('m_barang', ['id' => $post_array['id_barang']])->row();

		if ($logBarangKeluar) {
			$data = [
				'keterangan' => "Barang " . $findBarang->nama . " Keluar : " . $post_array['jumlah'],
				'id_barang' => $post_array['id_barang'],
				'tanggal' => $post_array['tanggal'],
				'is_deleted' => null,
				'jumlah' => 0 - $post_array['jumlah'],
				'updated_at' => date('Y-m-d H:i:s'),
				'updated_by' => $this->session->userdata('id_admin')
			];

			return $this->db->update('t_log_barang', $data, $where);
		} else {
			//create
			$data = [
				'keterangan' => "Barang " . $findBarang->nama . " Keluar : " . $post_array['jumlah'],
				'id_barang' => $post_array['id_barang'],
				'tanggal' => $post_array['tanggal'],
				'is_deleted' => null,
				'jumlah' => 0 - $post_array['jumlah'],
				'tipe' => 'out',
				'cf1' => $primary_key,
				'cf3' => 't_barang_keluar',
				'created_at' => date('Y-m-d H:i:s'),
				'created_by' => $this->session->userdata('id_admin')
			];
			return $this->db->insert('t_log_barang', $data);
		}
	}

	function _after_delete_keluar_barang($primary_key)
	{
		$where = ['tipe' => 'out', 'cf1' => $primary_key, 'cf3' => 't_barang_keluar'];
		$data = [
			'jumlah' => 0,
			'is_deleted' => 1,
			'updated_at' => date('Y-m-d H:i:s'),
			'updated_by' => $this->session->userdata('id_admin')
		];
		return $this->db->update('t_log_barang', $data, $where);
	}

	function _format_rp($value, $row)
	{
		return number_format($value, null, ',', '.');
	}

	function format_rp($value)
	{
		return number_format(intval($value), null, ',', '.');
	}

	function _count_barang_keluar($value, $row)
	{
		if (!$value) {
			return "<a href='" . site_url('master/koper_jamaah/add/' . $row->id) . "'  target=''> Tambahkan Jenis Koper Jamaah </a>";
		}
		$barangKeluar = $this->db->query("SELECT COUNT(*) AS total FROM t_barang_keluar WHERE is_active = 1 AND id_koper_jamaah =" . $value)->row();
		$string = " <a href='" . site_url('laporan/barang_keluar/' . $value) . "'  target=''> " . $barangKeluar->total . "</a>";
		if ($this->session->userdata('level') == 7) {
			$string .= " <a href='" . site_url('transaksi_op/barang_keluar/' . $value) . "'  target='_blank'> +</a>";
		}
		return $string;
	}

	function __jamaah($value, $row)
	{
		$x = $this->jamaahnya[$value];
		return "<a href='" . site_url('master/jamaah/edit/' . $value) . "' target='_blank'>$x</a>";
	}
	function arsip_pembayaran($paket = 0, $jamaah = 0)
	{
		if ($paket == 0) {
			$this->crud->where('KET', 'ARSIP');
			$this->crud->set_table('data_jamaah_paket')->unset_read()->columns('estimasi_keberangkatan', 'Program', 'harga')->fields('estimasi_keberangkatan', 'Program', 'harga', 'KET')->display_as('estimasi_keberangkatan', 'Pilih Paket')->unset_delete()->unset_add()->set_subject('Pilih Paket');
			$this->db->select('paket_umroh');
			$query = $this->db->get('transaksi_paket');
			foreach ($query->result() as $row) {
				if (isset($this->j[$row->paket_umroh]))
					$this->j[$row->paket_umroh]++;
				else
					$this->j[$row->paket_umroh] = 1;
			}
			$this->crud->callback_column('estimasi_keberangkatan', array($this, '_callback_webpage_url'));
			$this->crud->callback_column('harga', array($this, '_harga_rp'));
		} elseif ($jamaah == 0) {
			$s = $this->get('data_jamaah_paket', 'id', $paket, 'estimasi_keberangkatan') . '<br>';
			$r = $this->get('data_jamaah_paket', 'id', $paket, 'harga');
			// $kurs = $this->main_model->get_kurs();
			// $s.=$dolar.'<br>';
			// $r=ceil($kurs*$dolar/1000)*1000;
			$this->r = $r;
			$s .= 'Rp.' . $this->main_model->uang($r);
			$this->grocery_crud->callback_add_field('harga', array($this, 'harga_field_callback_1'))->unset_edit()->unset_delete();
			$this->crud->set_table('transaksi_paket')
				->set_subject('Pembelian paket umroh ' . $s)
				->set_top('Pembelian paket umroh ' . $s)
				->unset_read()->columns('jamaah', 'harga', 'kredit', 'kekurangan', 'debet', 'saldo', 'kode', 'agen');
			$this->crud->field_type('kode', 'readonly');
			// $this->crud->set_relation('paket_umroh','data_jamaah_paket','estimasi_keberangkatan');callback_column('jamaah',array($this,'__jamaah'))->
			$this->crud->callback_column('kekurangan', array($this, '_kekurangan'));
			$this->crud->callback_column('debet', array($this, '__debet'));
			$this->crud->callback_column('kredit', array($this, '__kredit'));
			$this->crud->set_relation('agen', 'data_jamaah_agen', 'nama');
			$this->crud->add_fields(array('jamaah', 'harga', 'paket_umroh', 'kekurangan', 'harga_normal', 'agen'));
			$this->crud->callback_before_insert(array($this, '_update_kekurangan'));
			$this->crud->field_type('harga_normal', 'hidden', $r);
			$this->crud->field_type('kekurangan', 'hidden', $r);
			$this->crud->field_type('paket_umroh', 'hidden', $paket);
			$this->crud->data['-tes'] = '-';

			$this->crud->callback_after_insert(array($this, 'fix_code_after_insert'))->where(array('paket_umroh' => $paket))->unset_edit()->unset_delete();
		}

		$this->show();
	}

	public function _kekurangan($value, $row)
	{
		return "<a href='" . site_url('transaksi_op/histori/' . $row->id) . "' target='_blank'>" . $this->_rupiah($value, $row) . "</a>";
	}
	public function __debet($value, $row)
	{
		return "<a href='" . site_url('transaksi_op/debet/' . $row->id) . "'  target='_blank'>" . $this->_rupiah($value, $row) . "</a> <a href='" . site_url('transaksi_op/debet/' . $row->id) . "/add'  target='_blank'>+</a>";
	}
	public function __kredit($value, $row)
	{
		return "<a href='" . site_url('transaksi_op/kredit/' . $row->id) . "'  target='_blank'>" . $this->_rupiah($value, $row) . "</a> <a href='" . site_url('transaksi_op/kredit/' . $row->id) . "/add'  target='_blank'>+</a>";
	}
	public function __kuitansi_kredit($value, $row)
	{
		$value = $this->rp($value);
		return "<a class='btn btn-link' href='" . site_url('kuitansi/kredit/' . $row->id) . "'  target='_blank'>$value</a>";
	}
	public function __kuitansi_debet($value, $row)
	{
		$value = $this->rp($value);

		//return "<a class='btn btn-link' href='" . site_url('kuitansi/debit/' . $row->id) . "'  target='_blank'>$value</a>";
		//open modal
		return "<a data-target='#my-exact-debit' data-title='Isi Nama Penerima' data-my-id='$row->id' role='button' class='btn btn-link my-modal-con' data-toggle='modal-debit' >$value</a>";
	}

	public function receiver_debit_update($id, $receiver_debit)
	{
		$this->db->where('id', $id);
		$this->db->update('pembayaran_transaksi_paket', ['receiver_debit' => urldecode($receiver_debit)]);

	}

	function _update_kekurangan($post_array)
	{

		$post_array['kekurangan'] = $post_array['harga'];

		return $post_array;
	}
	function fix_code_after_insert($post_array, $primary_key)
	{
		$this->db->update('transaksi_paket', array('kode' => base_convert($primary_key, 10, 36)), array('id' => $primary_key));
		// echo $this->db->last_query();
		return true;
	}
	/*

	*/
	function debet($id = 0)
	{
		if ($id == 0)
			redirect('transaksi_op/pembayaran');
		$this->crud->set_table('pembayaran_transaksi_paket');
		$j = $this->get_row('transaksi_paket', 'id', $id);
		$jamaah = $this->get('data_jamaah', 'id_jamaah', $j->jamaah, 'nama_jamaah');
		$ide = $this->session->userdata('id_admin');
        // $this->crud->callback_column('debet',array($this,'__kuitansi_kredit'));
        //
        
        //--total sesuai status terbaru
	    $total_valid = $this->db->select_sum('debet', 'total_debet')
							->select_sum('kredit', 'total_kredit')
							->where('id_transaksi_paket', $id)
							->where('status_pembayaran', 1) // Filter kunci
							->where('deleted', null)
							->get('pembayaran_transaksi_paket')->row();


        //--
        //var_dump($total_valid);
        
		$p = $this->get_row('data_jamaah_paket', 'id', $j->paket_umroh);
		$paket = $p->estimasi_keberangkatan;
		$harga = $j->harga;
		list($debet, $kredit) = $this->get_sum($id, $harga);
		$kurang = $harga - $total_valid->total_kredit + $debet;
		$saldo =  $total_valid->total_kredit - $debet;
		$this->crud
			->set_subject("Transaksi Debet $jamaah | Paket :$paket | Harga:" . $this->format_rp($harga) . " | Pembayaran:" . $this->format_rp($total_valid->total_kredit) . " | Kekurangan: " . $this->format_rp($kurang) . "<br>Transaksi Debet : " . $this->format_rp($debet) . "| saldo = " . $this->format_rp($saldo))
			->set_top("Transaksi Debet $jamaah | Paket :$paket | Harga:" . $this->format_rp($harga) . " | Pembayaran:" . $this->format_rp($total_valid->total_kredit) . " | Kekurangan: " . $this->format_rp($kurang) . "<br>Transaksi Debit : " . $this->format_rp($debet) . " | saldo = " . $this->format_rp($saldo));
		// $this->crud->set_subject("Debet Transaksi $jamaah<br>Paket :$paket<br>Harga:$harga <br>Kekurangan: ".$j->kekurangan);
		$this->crud->unset_read()->columns('jenis_transaksi', 'keterangan', 'tanggal', 'tanggal_transfer', 'kredit', 'debet', 'teller', 'bukti');
		$this->crud->display_as('tanggal_transfer', 'Tgl Transfer')
			->display_as('kredit', 'Kredit (IDR)')
			->display_as('debet', 'Debit (IDR)');

     	$this->crud->field_type('bank_id', 'hidden');
     	//$this->crud->field_type('bukti', 'hidden');
        
        $this->crud->callback_column('kredit', array($this, '_format_rp'));
		$this->crud->callback_column('debet', array($this, '_format_rp'));

		$this->crud->callback_column('debet', array($this, '__kuitansi_debet'));
		$this->crud->callback_column('kredit', array($this, '__kuitansi_kredit'));
		
		$this->crud->callback_column('tanggal', array($this, '_date_format'));
		$this->crud->callback_column('tanggal_transfer', array($this, '_date_format'));
		$this->crud->field_type('teller', 'hidden', $ide)
			->field_type('id_transaksi_paket', 'hidden', $id)
			->where('id_transaksi_paket', $id)
			//->where('debet > 0')
			->set_relation('jenis_transaksi', 'jenis_transaksi_pengeluaran', 'nama_transaksi')
			->unset_texteditor('keterangan');
		$state = $this->crud->getState();
		// echo "state=$state";
        if ($state == 'ajax_list') {
			$this->crud->set_relation('teller', 'admin', 'username');
        }

        //var_dump(222);
        //die();

		$this->crud->set_field_upload('bukti', 'assets/uploads/bukti');
		$this->crud->fields('id_transaksi_paket','bank_id', 'jenis_transaksi', 'tanggal', 'tanggal_transfer', 'debet', 'keterangan', 'teller', 'bukti')->unset_edit()->unset_delete();
		$this->show();
	}

	private function get_sum($id_transaksi_paket, $harga)
	{

		$d = $this->db->query("select kredit,debet from pembayaran_transaksi_paket where  id_transaksi_paket = $id_transaksi_paket AND deleted IS NULL");
		$debet = 0;
		$kredit = 0;
		foreach ($d->result() as $row) {
			$debet += $row->debet;
			$kredit += $row->kredit;
		}
		// return array('debet'=>$debet,'kredit'=>$kredit);
		$saldo = $kredit - $debet;
		$this->db->update('transaksi_paket', array('kekurangan' => ($harga - $kredit), 'debet' => $debet, 'kredit' => $kredit, 'saldo' => $saldo), array('id' => $id_transaksi_paket));
		// $this->db->update('transaksi_paket',array('kode'=>base_convert($primary_key,10,36)),array('id'=>$primary_key));
		return array($debet, $kredit);
	}
	function rp($value)
	{
		return number_format($value, 0, ",", ".");

		// return number_format((float)$value,0,'.',',');
    }


    function _set_flash_sukses($post_array, $primary_key)
    {
        $this->session->set_flashdata('message', 'Data berhasil disimpan!');
        return true;
    }

	public function _callback_user_id($value, $row)
{
    // Menggunakan $this->db langsung lebih efisien daripada load berkali-kali
    $dataAdmin = $this->db->get_where('admin', array('id_admin' => $value))->row();
    
    // Jika data ditemukan, return nama. Jika tidak, return "Tidak Diketahui" atau ID-nya
    return isset($dataAdmin->nama_admin) ? $dataAdmin->nama_admin : $dataAdmin->username;
}


	function _set_status_pembayaran($post_array)
{
    $post_array['status_pembayaran'] = 1;
    return $post_array;
}

	 function kredit($id = 0)
	{
		if ($id == 0) {
			redirect('transaksi_op/pembayaran');
		}

		//mencegah text liar masuk ke JSON
		if ($this->input->is_ajax_request()) {
			ob_start();
		}

		$this->crud->set_table('pembayaran_transaksi_paket');
		$j = $this->get_row('transaksi_paket', 'id', $id);
		$jamaah = $this->get('data_jamaah', 'id_jamaah', $j->jamaah, 'nama_jamaah');
		//
		$this->crud->set_relation('bank_id', 'master_bank', 'nama_bank', ['is_active' => 1])->display_as('bank_id', 'Bank');

		$this->crud->required_fields('tanggal', 'tanggal_transfer', 'bank_id','jenis_transaksi','kredit'); // Removed 'bukti' as required; make optional if editing

	
		$this->crud->callback_add_field('keterangan', array($this, '_callback_keterangan_default'));

		$this->crud->fields('id_transaksi_paket', 'bank_id', 'jenis_transaksi', 'tanggal', 'tanggal_transfer', 'kredit', 'keterangan', 'bukti', 'teller','status_pembayaran');
		//$this->crud->field_type('file_bukti_hash', 'hidden');

		$this->crud->callback_column('debet', array($this, '__kuitansi_debet'));
		$this->crud->callback_column('kredit', array($this, '_format_rp'));
            
		$this->crud->callback_column('kredit', array($this, '__kuitansi_kredit'));


		$ide = $this->session->userdata('id_admin');
		$p = $this->get_row('data_jamaah_paket', 'id', $j->paket_umroh);

		$total_valid = $this->db->select_sum('debet', 'total_debet')
							->select_sum('kredit', 'total_kredit')
							->where('id_transaksi_paket', $id)
							->where('status_pembayaran', 1) // Filter kunci
							->where('deleted', null)
							->get('pembayaran_transaksi_paket')->row();


		$harga = $j->harga;
		
		$debet_val  = $total_valid->total_debet ?? 0;

		$kredit_val = $total_valid->total_kredit ?? 0;
		$kurang_raw = $harga - $kredit_val + $debet_val;

		$paket = $p->estimasi_keberangkatan;
		//list($debet, $kredit_val) = $this->get_sum($id, $harga);
		//$kurang = $harga - $kredit_val + $debet;
		//$kurang = $this->rp(intval($kurang));
		$kurang = $this->rp(intval($kurang_raw));
		$harga = $this->rp($harga);
		$debet = $this->rp($debet_val);
		$kredit = $this->rp($kredit_val);
		
		$this->crud->set_subject("Transaksi Kredit $jamaah | Paket :$paket | Harga:$harga | Pembayaran:$kredit | Kekurangan: " . $kurang . " Transaksi Debet : $debet")
			->set_top("Transaksi Kredit $jamaah | Paket :$paket | Harga:$harga | Pembayaran:$kredit | Kekurangan: " . $kurang . " Transaksi Debet : $debet");
		
		//$this->crud->display_as('teller', 'Nama Teller');
		//$this->crud->callback_column('teller', array($this, '_callback_user_id'));

		$this->crud->unset_read()->columns('jenis_transaksi', 'keterangan', 'tanggal', 'tanggal_transfer', 'kredit', 'debet', 'teller', 'bukti', 'bank_id','status_pembayaran');
		$this->crud->display_as('jenis_transaksi', 'Jenis Transaksi')
			->display_as('tanggal_transfer', 'Tgl Transfer')
			->display_as('kredit', 'Kredit (IDR)')
			->display_as('debet', 'Debit (IDR)');
		$this->crud->field_type('status_pembayaran', 'hidden', 1);//hide input
		$this->crud->field_type('teller', 'hidden', $ide)
			->field_type('id_transaksi_paket', 'hidden', $id)
			->where('id_transaksi_paket', $id)
			//->where('kredit >',0)
			->set_relation('jenis_transaksi', 'jenis_transaksi_pengeluaran', 'nama_transaksi')
			->unset_texteditor('keterangan');

		$this->crud->display_as('status_pembayaran','status');
	

		$this->crud->callback_column('status_pembayaran', function($value, $row) {
			if ($value == 0) {
			// Sesuaikan nama fungsi: approve_transaksi
			$url = site_url('GlobalController/approve_transaksi/'.$row->id.'/'.$row->id_transaksi_paket);
			

		return '<span class="label label-danger">Belum Approve</span> ' . 
				'<button 
					class="btn btn-xs btn-success btn-approve" 
					data-id="'.$row->id.'" 
					data-paket="'.$row->id_transaksi_paket.'">
					<i class="fa fa-check"></i> Approve
				</button>';


		
			} else {
				return '<span class="label label-success">Sudah Approve</span>';
			}
		});


		$this->crud->callback_column('keterangan', array($this, '_full_text'));

		$state = $this->crud->getState();
		if ($state == 'ajax_list') {
			$this->crud->set_relation('teller', 'admin', 'nama_admin');
		}


		$this->crud->callback_delete([$this, '_delete_kredit']);
		$this->crud->where('deleted', null);
		//$this->crud->fields('id_transaksi_paket','bank_id', 'jenis_transaksi', 'tanggal', 'tanggal_transfer', 'kredit', 'keterangan', 'bukti','file_bukti_hash')->unset_edit();
		$this->crud->set_top("Transaksi Kredit $jamaah | Paket :$paket | Harga: " . $harga . " | Pembayaran:" . $this->format_rp($kredit) . " | Kekurangan: " . $kurang . " Transaksi Debet : $debet");
		$this->crud->callback_before_upload(array($this, '_cek_gambar_duplikat'));
	
		$this->crud->set_field_upload('bukti', 'assets/uploads/bukti');

		$this->crud->callback_before_insert(array($this, '_validasi_bukti_bank'));
        $this->crud->callback_before_update(array($this, '_validasi_bukti_bank'));

		$this->_show_v2();
	}

public function _validasi_bukti_bank($post_array)
{
    if (!empty($post_array['bank_id'])) {

        $bank = $this->db
            ->where('id', $post_array['bank_id'])
            ->get('master_bank')
            ->row();
        
        // Jika bank BUKAN kasir_cash
        if ($bank && $bank->nama_bank != 'kasir_cash') {
            
            /* Cek apakah ada file baru yang diupload ($_FILES) 
               ATAU sudah ada nama file di field bukti ($post_array)
            */
            $is_empty = true;

            if (!empty($post_array['bukti'])) {
                $is_empty = false;
            }
            
            if (!empty($_FILES['bukti']['name'])) {
                $is_empty = false;
            }

            if ($is_empty) {
                // Menghentikan proses dan memunculkan pesan error di layar
                header('Content-Type: application/json');
                echo json_encode([
                    'success' => false,
                    'error_message' => 'Field Bukti wajib diisi untuk bank selain kasir_cash!'
                ]);
                exit; 
            }
        }
    }

    // WAJIB return variabel $post_array agar query SQL tidak kosong
    return $post_array;
}

public function _approve_transaksi($id_pembayaran, $id_paket)
{
    // 1. Update status_pembayaran menjadi 1
	if (!$id_pembayaran || !$id_paket) {
        redirect('transaksi_op/pembayaran');
    }

    $this->db->where('id', $id_pembayaran);
    $this->db->update('pembayaran_transaksi_paket', array(
		'status_pembayaran' => 1,
		'teller'=>$this->session->userdata('id_admin')
	));

    // 2. Set pesan sukses
    $this->session->set_flashdata('message', 'Pembayaran Berhasil di Approve!');

    // 3. Kembalikan ke halaman kredit sebelumnya
    redirect('transaksi_op/kredit/' . $id_paket);
}


public function _callback_status_pembayaran($value, $row)
{
  if ($value == 0) {
      return '<span class="text-warning" style="font-weight:bold;color:red !important">Belum di approve</span>';
  }else{
      return '<span class="text-success" style="font-weight:bold !important">Suda Di Approve</span>';
  }
}


public function _callback_keterangan_default()
{
    $value = "-man ";
    return '<textarea name="keterangan" class="form-control" rows="5">' . $value . '</textarea>';
}

	public function _simpan_hash_gambar($post_array)
{
    // Removed var_dump and die for production use
	// var_dump(333);
	// die();
    if (!empty($_FILES['bukti']['tmp_name'])) {

        $file_tmp = $_FILES['bukti']['tmp_name'];
        $hash = md5_file($file_tmp); // Consider upgrading to hash_file('sha256', $file_tmp) for better security

        if (!$hash) {
            return false;
        }

        // Cek duplikat
        $exists = $this->db
            ->where('file_bukti_hash', $hash)
            //->where('deleted IS NULL', null, false)
            ->count_all_results('pembayaran_transaksi_paket');

        if ($exists > 0) {
            return false; // Grocery CRUD otomatis batal insert/update
        }

        $post_array['file_bukti_hash'] = $hash;
    }
    $post_array['teller'] = $this->session->userdata('id_admin');
    return $post_array;
}

 	public function _cek_gambar_duplikat($files_to_upload, $field_info)
{
	
    foreach ($files_to_upload as $file) {

        if (!empty($file['tmp_name']) && file_exists($file['tmp_name'])) {
            $hash = md5_file($file['tmp_name']);
			$original_name = $file['name'];

			$cek_nama = $this->db
				->where("SUBSTR(bukti, LOCATE('-', bukti) + 1) = " . $this->db->escape($original_name))
				->count_all_results('pembayaran_transaksi_paket');

            if ($cek_nama > 0) {
                return 'GAGAL: Bukti pembayaran sudah pernah diupload sebelumnya!';
            }
        }
    }

    return true;
}

    public function master_bank()
	{
		$this->load->database();
		$id_admin = $this->session->userdata('id_admin'); 
		$level = $this->session->userdata('level');
		$cek_level_admin = $this->db->get_where('group_level', array('id' => $level))->row();

		$this->crud->set_table('master_bank');
		$this->crud->set_subject('Data Master Bank');
		
		// Konfigurasi Kolom
		$this->crud->columns('nama_bank', 'is_active');
		$this->crud->display_as('nama_bank', 'Nama Bank');
		$this->crud->display_as('is_active', 'Status');

		// 1. MENGATUR INPUT FORM (Dropdown 0/1)
		// Menggunakan dropdown agar user memilih label, tapi yang tersimpan ke DB adalah angkanya
		$this->crud->field_type('is_active', 'dropdown', array(
			'1' => 'Active',
			'0' => 'Not Active'
		));

		// 2. MENGATUR TAMPILAN DI TABEL (List View)
		$this->crud->callback_column('is_active', array($this, '_callback_status'));

		$this->crud->set_theme('datatables');
		$this->crud->unset_delete();
		$this->show();
    }

	public function _callback_status($value, $row)
	{
		if ($value == 1) {
			return '<span class="text-success" style="font-weight:bold !important">Active</span>';
		} else {
			return '<span class="text-danger" style="font-weight:bold;color:red;">Tidak Active</span>';
		}
	}

	public function _full_text($value, $row)
	{
		return $value; 
	}

	function _delete_kredit($primary_key)
	{
		$user_id = $ide = $this->session->userdata('id_admin');
		return $this->db->update('pembayaran_transaksi_paket', array('deleted' => '1', 'deleted_at' => date("Y-m-d H:i:s"), 'deleted_by' => $user_id), array('id' => $primary_key));
	}

	function histori($id = 0)
	{
		$this->crud->set_table('pembayaran_transaksi_paket');
		$j = $this->get_row('transaksi_paket', 'id', $id);
		$jamaah = $this->get('data_jamaah', 'id_jamaah', $j->jamaah, 'nama_jamaah');

		$ide = $this->session->userdata('id_admin');
		$p = $this->get_row('data_jamaah_paket', 'id', $j->paket_umroh);
		$paket = $p->estimasi_keberangkatan;
		$harga = $j->harga;
		list($debet, $kredit) = $this->get_sum($id, $harga);
		$kurang = $harga - $kredit + $debet;
		$this->crud->set_subject("Transaksi Kredit $jamaah | Paket :$paket | Harga:$harga | Pembayaran:$kredit | Kekurangan: " . $kurang . "<br>Transaksi Debet : $debet")
			->set_top("Transaksi Kredit $jamaah | Paket :$paket | Harga:" . $this->format_rp($harga) . " | Pembayaran:" . $this->format_rp($kredit) . " | Kekurangan: " . $this->format_rp($kurang) . "<br>Transaksi Debet : " . $this->format_rp($debet))->unset_add();
		$this->crud->unset_read()->columns('jenis_transaksi', 'keterangan', 'tanggal', 'debet', 'kredit', 'teller');
		$this->crud->field_type('teller', 'hidden', $ide)
			->field_type('id_transaksi_paket', 'hidden', $id)
			->where('id_transaksi_paket', $id)
			->set_relation('jenis_transaksi', 'jenis_transaksi_pengeluaran', 'nama_transaksi')
			->unset_texteditor('keterangan');
		$this->crud->display_as('debet', 'Debit (Rp)');
		$this->crud->display_as('kredit', 'Kredit (Rp)');
		$this->crud->callback_column('debet', array($this, '_format_rp'));
		$this->crud->callback_column('kredit', array($this, '_format_rp'));
		$this->crud->callback_column('tanggal', array($this, '_date_format'));
		$state = $this->crud->getState();
		// echo "state=$state";
		if ($state == 'ajax_list') {
			$this->crud->set_relation('teller', 'admin', 'nama');
		}
		$this->crud->unset_edit()->unset_delete();
		$this->show();
	}
	function note($id_jamaah = 0)
	{
		$this->crud = new grocery_CRUD();
		$this->crud->set_table('note')->unset_read()->columns('note', 'tanggal');
		$ide = $this->session->userdata('id_admin');
		$this->crud->field_type('user', 'hidden', $ide);
		$this->crud->set_theme('twitter-bootstrap')->where('user', $ide);
		$this->show();
	}

	private function fungsiCurl($url)
	{
		$data = curl_init();
		curl_setopt($data, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($data, CURLOPT_URL, $url);
		curl_setopt($data, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-GB; rv:1.8.1.6) Gecko/20070725 Firefox/2.0.0.6");
		$hasil = curl_exec($data);
		curl_close($data);
		return $hasil;
	}
	function update_kurs($update = 0)
	{

		$this->crud = new grocery_CRUD();
		$this->crud->set_table('kurs')->unset_read()->columns('nilai', 'nilai_namiroh', 'tanggal', 'status');
		$ide = $this->session->userdata('id_admin');
		$this->crud->set_relation('status', 'status_aktif', 'keterangan')->order_by('tanggal', 'desc')->display_as('tanggal', 'Waktu update');
		//get from bank
		$url = $this->fungsiCurl('http://www.bankmandiri.co.id/resource/kurs.asp');
		$pecah = explode('<table class="tbl-view" cellpadding="0" cellspacing="0" border="0" width="100%">', $url);
		$pecah2 = explode('</table>', $pecah[1]);
		$pecah3 = explode('<th>&nbsp;</th>', $pecah2[0]);
		//echo( $pecah3[2]);
		$pecah4 = explode('<td>&nbsp;&nbsp;</td>', $pecah3[2]);
		$kurs = str_replace('<td align="right">', "", $pecah4[29]);
		$kurs = str_replace('</td>', "", $kurs);
		$kurs = str_replace('.', "", $kurs);
		// echo "k=$kurs<br>";
		$kurs = (int) $kurs;
		if ($update == 1) {
			//set all to inactive
			//insert new
			$this->db->update('kurs', array('status' => 2));
			$timezone = 7;
			$data = array(
				'tanggal' => gmdate("Y-m-d H:i:s", time() + 3600 * ($timezone)),
				'nilai' => $kurs,
				'status' => 1,
				'nilai_namiroh' => $kurs + 50
			);

			$this->db->insert('kurs', $data);
			redirect('transaksi_op/update_kurs');
		}
		$kurs += 50;
		$this->crud->set_theme('twitter-bootstrap')->unset_export();
		$output = $this->crud->render();
		// echo "kurs = $kurs";
		$this->load->section('sidebar', 'ci_simplicity/kurs_online', array('kurs' => $kurs, 'kursnamiroh' => $this->main_model->get_kurs()));
		$this->load->view('ci_simplicity/admin', $output);				//activate it
	}
	function kurs()
	{
		$this->crud = new grocery_CRUD();
		$this->crud->set_table('kurs')->unset_read()->columns('nilai', 'nilai_namiroh', 'tanggal', 'status');
		$ide = $this->session->userdata('id_admin');
		$this->crud->set_relation('status', 'status_aktif', 'keterangan')->order_by('tanggal', 'desc')->display_as('tanggal', 'Waktu update');
		$this->show();
	}
	function log()
	{
	}
}
// END Kelas Class

/* End of file kelas.php */
/* Location: ./system/application/controllers/kelas.php */
