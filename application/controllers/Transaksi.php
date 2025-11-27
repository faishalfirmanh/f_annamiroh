<?php

/**
 * Kelas Class
 *
 * @author	Awan Pribadi Basuki <awan_pribadi@yahoo.com>
 */
class Transaksi extends CI_Controller
{
	/**
	 * Constructor
	 */
	var $t = array();
	var $r = 0;
	var $crud = null;
	var $j = array();
	var $seat = array();
	function __construct()
	{
		parent::__construct();
		if ($this->session->userdata('login') != TRUE) {
			redirect('login');
		}
		$this->load->database();
		$this->load->helper('url');
		$this->load->model('main_model', '', TRUE);
		$this->load->library('grocery_CRUD');
		$route = $this->router->fetch_method();
		if(
			$route != 'pembayaran_invoice' && 
			$route != 'transaksi_kolektif_invoice' && 
			$route != 'transaksi_kolektif_pembayaran_invoice' &&
			$route != 'transaksi_kolektif_rincian_invoice'
			) 
			
			$this->_init();
		
	}

	private function _init()
	{
		$this->output->set_template('admin');
		$this->crud = new Grocery_CRUD();
		$ide = $this->session->userdata('level');
		$this->output->set_output_data('menu', $this->main_model->get_menu($ide));
		$this->crud->set_language("indonesian");
		$this->load->js('assets/themes/default/js/jquery-1.9.1.min.js');
		$this->load->js('assets/themes/default/hero_files/bootstrap-transition.js');
		$this->load->js('assets/themes/default/hero_files/bootstrap-collapse.js');
	}

	/**
	 * Memeriksa user state, jika dalam keadaan login akan menampilkan halaman kelas,
	 * jika tidak akan meredirect ke halaman login
	 */
	function index()
	{
		redirect('master/jamaah');
	}
	private function show()
	{
		$this->crud->set_theme('twitter-bootstrap')->unset_export();
		$output = $this->crud->render();
		// $kurs = $this->main_model->get_kurs();
		// $this->load->section('sidebar', 'ci_simplicity/kurs',array('kurs'=>$kurs));
		$this->load->view('ci_simplicity/admin', $output);
	}
	function jamaah($id_jamaah, $jenis_transaksi)
	{
		$this->crud = new grocery_CRUD();
		$this->crud->set_table('data_jamaah_paket');
		$this->crud->set_subject('Data Paket Umroh');
		$this->crud->set_theme('twitter-bootstrap');
		$this->crud->set_relation('hotel_makkah', 'data_hotel', 'nama');
		$this->crud->set_relation('hotel_madinah', 'data_hotel', 'nama');
		$this->crud->set_relation('Penerbangan', 'data_maskapai', 'nama');
		$this->crud->set_subject('Data Paket Umroh');
		$this->crud->unset_read()->columns('estimasi_keberangkatan', 'Program', 'Penerbangan', 'hotel', 'harga', 'sisa_kursi');
		$this->show();
	}

	function stok_masuk_barang()
	{
		// kasih callback di after insert dan update barang ke log barang
		if ($this->session->userdata('level')  != 7) {
			$this->crud->unset_add()->unset_delete()->unset_edit(); //buat batasi crud
		}

		$this->crud->set_table('t_barang_masuk');
		$this->crud->set_subject('Data Barang Masuk');
		$this->crud->set_theme('datatables');
		$this->crud->fields('id_barang', 'jumlah', 'tanggal', 'harga_beli', 'created_by');
		$this->crud->required_fields('id_barang', 'jumlah', 'tanggal', 'harga_beli');
		$this->crud->field_type('created_by', 'hidden', $this->session->userdata('id_admin'));
		$this->crud->set_relation('id_barang', 'm_barang', '{nama}');
		$this->crud->unset_read()->columns('id_barang', 'jumlah', 'tanggal', 'harga_beli', 'created_at');
		$this->crud->display_as('id_barang', 'Barang');
		$this->crud->display_as('created_at', 'Dibuat');

		$this->crud->callback_after_insert(array($this, '_after_insert_stok_masuk_barang'));
		$this->crud->callback_after_update(array($this, '_after_update_stok_masuk_barang'));
		$this->crud->callback_after_delete(array($this, '_after_delete_stok_masuk_barang'));

		$this->show();
	}

	function _after_insert_stok_masuk_barang($post_array, $primary_key)
	{
		$findBarang =  $this->db->get_where('m_barang', ['id' =>  $post_array['id_barang']])->row();
		$data = [
			'keterangan' => "Barang " . $findBarang->nama . " Masuk : " . $post_array['jumlah'],
			'id_barang' => $post_array['id_barang'],
			'tanggal' => implode("-", array_reverse(explode("/", $post_array['tanggal']))),
			'jumlah' => $post_array['jumlah'],
			'tipe' => 'in',
			'cf1' => $primary_key,
			'cf3' => 't_barang_masuk',
			'created_at' => date('Y-m-d H:i:s'),
			'created_by' => $this->session->userdata('id_admin')
		];

		return $this->db->insert('t_log_barang', $data);
	}

	function _after_update_stok_masuk_barang($post_array, $primary_key)
	{
		$where = ['tipe' => 'in', 'cf1' => $primary_key, 'cf3' => 't_barang_masuk'];
		$findBarang =  $this->db->get_where('m_barang', ['id' =>  $post_array['id_barang']])->row();
		$data = [
			'keterangan' => "Barang " . $findBarang->nama . " Masuk : " . $post_array['jumlah'],
			'id_barang' => $post_array['id_barang'],
			'tanggal' => implode("-", array_reverse(explode("/", $post_array['tanggal']))),
			'jumlah' => $post_array['jumlah'],
			'updated_at' => date('Y-m-d H:i:s'),
			'updated_by' => $this->session->userdata('id_admin')
		];
		return $this->db->update('t_log_barang', $data, $where);
	}


	function _after_delete_stok_masuk_barang($primary_key)
	{
		$where = ['tipe' => 'in', 'cf1' => $primary_key, 'cf3' => 't_barang_masuk'];
		$data = [
			'is_deleted' => 1,
			'updated_at' => date('Y-m-d H:i:s'),
			'updated_by' => $this->session->userdata('id_admin')
		];
		return $this->db->update('t_log_barang', $data, $where);
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
		$jumlae = isset($this->j[$row->id]) ? $this->j[$row->id] : 0;

		$sisa = $row->total_seat - $jumlae;
		if (isset($sisa)) {
			if ($sisa > 0)
				return "<a href='" . site_url('transaksi/pembayaran/' . $row->id) . "' target='_blank'>$value-$jumlae orang sisa $sisa</a>";
		}
		return "<a href='" . site_url('transaksi/pembayaran/' . $row->id) . "' target='_blank'>$value-$jumlae orang seat PENUH</a>";
	}
	public function _peserta_paket($value, $row)
	{
		$jumlae = isset($this->j[$row->id]) ? $this->j[$row->id] : 0;
		$sisa = $row->total_seat - $jumlae;
		if (isset($sisa)) {
			if ($sisa > 0)
				return "$value-terisi $jumlae orang, sisa $sisa";
		}
		return "$value-terisi $jumlae orang, seat PENUH";
	}
	function _harga_rp($value, $row)
	{
		$harga = '';
		if($value != '') $harga = $this->format_rp($value);
		return $harga;

		// $harga = number_format($this->main_model->get_kurs() * $row->harga_dolar, 0, ".", ",");
		// return $harga .' '. $value;
	}

	function format_rp($value)
	{
		$harga = '';
		if(is_numeric($value)) {
		    if($value != '') $harga = number_format($value,0,',','.');
		}
		
		return $harga;
	}
	function _harga_dolar($value, $row)
	{
		$harga = $this->format_dolar($value);
		return $harga;
	}

	function format_dolar($value)
	{
		$harga = '';
		if($value != '') $harga = number_format($value,0,'.',',');
		return $harga;
	}
	function harga_field_callback_1()
	{
		return '<input type="text" maxlength="50" value="000' . $this->r . '" name="harga" style="width:462px">';
	}
	function pakete()
	{
		$this->crud->set_table('data_jamaah_paket')->unset_read()->columns('estimasi_keberangkatan', 'Program', 'tanggal_keberangkatan', 'total_seat', 'harga', 'Penerbangan', 'rute')->unset_edit()->display_as('estimasi_keberangkatan', 'Paket')->unset_delete()->unset_add()->set_subject('Paket')->where('KET', 'AKTIF')->where('TAMPIL', 'YA');
		$this->crud->set_relation('Penerbangan', 'data_maskapai', 'nama');

		$this->db->select('paket_umroh');
		$query = $this->db->get('transaksi_paket');
		foreach ($query->result() as $row) {
			if (isset($this->j[$row->paket_umroh]))
				$this->j[$row->paket_umroh]++;
			else
				$this->j[$row->paket_umroh] = 1;
		}
		$this->crud->callback_column('estimasi_keberangkatan', array($this, '_peserta_paket'));
		// $this->crud->callback_column('harga',array($this,'_harga_rp'));
		$this->show();
	}
	//disini
	function ceklis($kolom = null, $data = null)
	{
		$this->crud->set_table('data_jamaah_paket')
			->unset_read()->columns(
				'estimasi_keberangkatan',
				'ceklis_paspor',
				'ceklis_faksin',
				'ceklis_visa',
				'ceklis_tiket',
				'ceklis_dokumen_manifest',
				'ceklis_dokumen_roomlist',
				'ceklis_dokumen_pembagian_bis',
				'ceklis_copy_tiket',
				'ceklis_copy_visa',
				'ceklis_id_card',
				'ceklis_tag_bagasi',
				'ceklis_stiker_zamzam',
				'ceklis_uang_baksis',
				'ceklis_tukar_riyal',
				'ceklis_uang_handling',
				'ceklis_lounge',
				'ceklis_program_perjalanan',
				'ceklis_jasa_kursi_roda',
				'ceklis_surat_tugas',
				'ceklis_perjanjian_perwakilan',
				'ceklis_perjanjian_jamaah',
				'ceklist_gaji_guide',
				'ceklist_operasinal_guide',
				'ceklis_saku_tl'
			)
			->fields(
				'jumlah_paspor',
				'ceklis_paspor',
				'jumlah_vaksin',
				'ceklis_faksin',
				'jumlah_visa',
				'ceklis_visa',
				'jumlah_tiket',
				'ceklis_tiket',
				'ceklis_dokumen_manifest',
				'ceklis_dokumen_roomlist',
				'ceklis_dokumen_pembagian_bis',
				'ceklis_copy_tiket',
				'ceklis_copy_visa',
				'jumlah_id_card',
				'ceklis_id_card',
				'jumlah_tag_bagasi',
				'ceklis_tag_bagasi',
				'jumlah_banner',
				'jumlah_stiker_zamzam',
				'ceklis_stiker_zamzam',
				'jumlah_uang_baksis',
				'ceklis_uang_baksis',
				'jumlah_tukar_riyal',
				'ceklis_tukar_riyal',
				'jumlah_uang_handling',
				'ceklis_uang_handling',
				'ceklis_lounge',
				'ceklis_program_perjalanan',
				'jumlah_jasa_kursi_roda',
				'ceklis_jasa_kursi_roda',
				'ceklis_surat_tugas',
				'ceklis_perjanjian_perwakilan',
				'ceklis_perjanjian_jamaah',
				'jumlah_gaji_guide',
				'ceklist_gaji_guide',
				'jumlah_operasional_guide',
				'ceklist_operasinal_guide',
				'jumlah_saku_tl',
				'ceklis_saku_tl'
			);
		$state = $this->crud->getState();
		if ($state == 'list' || $state = 'ajax_list') {
			/*
	        $this->crud->display_as('ceklis_paspor','<p title="Ceklist Paspor">1</p>');
	        $this->crud->display_as('ceklis_faksin','<p title="Ceklist Faksin">2</p>');
	        $this->crud->display_as('ceklis_visa','<p title="Ceklist Visa">3</p>');
	        $this->crud->display_as('ceklis_tiket','<p title="Ceklist Tiket">4</p>');
	        $this->crud->display_as('ceklis_dokumen_manifest','<p title="Ceklist Dokumen Manifest">5</p>');
	        $this->crud->display_as('ceklis_dokumen_roomlist','<p title="Ceklist Dokumen Roomlist">6</p>');
	        $this->crud->display_as('ceklis_dokumen_pembagian_bis','<p title="Ceklist Dokumen Pembagian Bis">7</p>');
	        $this->crud->display_as('ceklis_copy_tiket','<p title="Ceklist Copy Tiket">8</p>');
	        $this->crud->display_as('ceklis_copy_visa','<p title="Ceklist Copy Visa">9</p>');
	        $this->crud->display_as('ceklis_id_card','<p title="Ceklist ID Card">10</p>');
	        $this->crud->display_as('ceklis_tag_bagasi','<p title="Ceklist Bagasi">11</p>');
	        $this->crud->display_as('ceklis_stiker_zamzam','<p title="Ceklist Stiker Zam zam">12</p>');
	        $this->crud->display_as('ceklis_uang_baksis','<p title="Ceklist Uang Baksis">13</p>');
	        $this->crud->display_as('ceklis_tukar_riyal','<p title="Ceklist Tukar Riyal">14</p>');
	        $this->crud->display_as('ceklis_uang_handling','<p title="Ceklist Uang Handling">15</p>');
	        $this->crud->display_as('ceklis_lounge','<p title="Ceklist Lounge">16</p>');
	        $this->crud->display_as('ceklis_program_perjalanan','<p title="Ceklist Program Perjalanan">17</p>');
	        $this->crud->display_as('ceklis_jasa_kursi_roda','<p title="Ceklist Kursi Roda">18</p>');
	        $this->crud->display_as('ceklis_surat_tugas','<p title="Ceklist Surat Tugas">19</p>');
	        $this->crud->display_as('ceklis_perjanjian_perwakilan','<p title="Ceklist Perjanjian Perwakilan">20</p>');
	        $this->crud->display_as('ceklis_perjanjian_jamaah','<p title="Ceklist Perjanjian Jamaah">21</p>');
	        $this->crud->display_as('ceklist_gaji_guide','<p title="Ceklist Gaji Guide">22</p>');
	        $this->crud->display_as('ceklist_operasinal_guide','<p title="Ceklist Operasional Guide">23</p>');
	        $this->crud->display_as('ceklis_saku_tl','<p title="Ceklist Uang Saku TL">24</p>');*/
		}
		echo '
        <style>.rotate {
  font-family: "Tahoma", "Geneva", sans-serif;
  color: #000000;
  -webkit-transform: rotate(121deg);
  -moz-transform: rotate(121deg);
  -ms-transform: rotate(121deg);
  -o-transform: rotate(121deg);
  transform: rotate(121deg);
}
</style>';
		$this->crud->unset_delete()->unset_add()->set_subject('Kelengkapan Data')
			->where('KET', 'AKTIF')->where('TAMPIL', 'YA');
		$this->crud->set_theme('twitter-bootstrap')->unset_export();
		$output = $this->crud->render();
		$this->load->view('ci_simplicity/admin', $output);
	}
	function pembayaran($paket = 0, $jamaah = 0)
	{

		// revisi 26 maret 2024, limit akses
		$user_id = $this->session->userdata('id_admin');
		$user = $this->db->from('admin')->where('id_admin', $user_id)->get()->row();
		$this->crud->set_exceptions([$user->level]);

		if ($paket == 0) {
			$this->crud->set_table('data_jamaah_paket')
			->unset_read()->columns('estimasi_keberangkatan', 'qty', 'total_seat', 'tanggal_keberangkatan', 'Program', 'harga', 'harga_dolar', 'detil')->unset_edit()
			->display_as('estimasi_keberangkatan', 'Pilih Paket')
			->display_as('total_seat', 'Total Seat')
			->display_as('tanggal_keberangkatan', 'Tgl Keberangkatan')
			->display_as('harga_dolar', 'Harga (USD)')
			->display_as('harga', 'Harga (IDR)')
			->display_as('detil', 'Detail')
			->unset_delete()->unset_add()
			->set_subject('Pilih Paket')->where('KET', 'AKTIF')->where('TAMPIL', 'YA');
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
			$this->crud->callback_column('harga_dolar', array($this, '_harga_dolar'));
		} elseif ($jamaah == 0) {
			$s = $this->get('data_jamaah_paket', 'id', $paket, 'estimasi_keberangkatan') . '<br>$';
			$dolar = $this->get('data_jamaah_paket', 'id', $paket, 'harga_dolar');
			$kurs = $this->main_model->get_kurs();
			$s .= $this->format_dolar($dolar) . '<br>';
			$r = ceil($kurs * $dolar / 1000) * 1000;
			$this->r = $r;
			$s .= 'Rp.' . $this->main_model->uang($r);
			$this->grocery_crud->callback_add_field('harga', array($this, 'harga_field_callback_1'));
			$this->crud->set_table('transaksi_paket')
			->set_subject('Pembelian paket umroh ' . $s)
			->set_top('Pembelian paket umroh ' . $s)
			->set_is_invoice(true)
			->set_relation('jamaah', 'data_jamaah', '{nama_jamaah}-{no_ktp}', 'nama_jamaah <> ""')->unset_read()->columns('jamaah', 'harga', 'kredit', 'kekurangan', 'debet', 'saldo', 'kode', 'agen');
			$this->crud->field_type('kode', 'readonly');
			// $this->crud->set_relation('paket_umroh','data_jamaah_paket','estimasi_keberangkatan');
			$this->crud->callback_column('kekurangan', array($this, '_kekurangan'));
			$this->crud->callback_column('debet', array($this, '__debet'));
			$this->crud->callback_column('kredit', array($this, '__kredit'));
			$this->crud->callback_column('harga', array($this, '_harga_rp'));
			$this->crud->callback_column('saldo', array($this, '_harga_rp'));
			$this->crud->set_relation('agen', 'data_jamaah_agen', '{nama}/{id}');
			$this->crud->add_fields(array('jamaah', 'harga', 'paket_umroh', 'kekurangan', 'harga_normal', 'agen'));
			$this->crud->callback_before_insert(array($this, '_update_kekurangan'));
			$this->crud->field_type('harga_normal', 'hidden', $r);
			$this->crud->field_type('kekurangan', 'hidden', $r);
			$this->crud->field_type('paket_umroh', 'hidden', $paket);
			$this->crud->data['-tes'] = '-';

			$this->crud->set_top('Pembelian paket umroh ' . $s);

			$this->crud->callback_after_insert(array($this, 'fix_code_after_insert'))->where(array('paket_umroh' => $paket));

			$this->crud
				->display_as('harga', 'Total Tagihan')
				->display_as('kredit', 'Kredit (IDR)')
				->display_as('debet', 'Debit (IDR)');
		}

		$this->show();
	}

	function transaksi_kolektif(){
		$this->crud
			->required_fields('paket_id', 'nominal', 'jumlah_jamaah')
			->set_subject('Pembayaran Transaksi Umroh Kolektif')
			->set_top('Pembayaran Transaksi Umroh Kolektif')
			->set_is_collective_transaction(true)
			->set_table('transaksi_kolektif')
			->where('transaksi_kolektif.deleted_by IS NULL')
			->set_relation('jamaah_id', 'data_jamaah', '{nama_jamaah} - {no_ktp} - {hp_jamaah} - {alamat_jamaah}')
			->set_relation('agen_id', 'data_jamaah', 'nama_jamaah', ['is_agen' => 1])
			// ->set_relation('jamaah_id', 'data_jamaah', '{no_ktp} - {nama_jamaah}') // revisi 16 maret 2024, remove jamaah
			->set_relation('paket_id', 'data_jamaah_paket', '{estimasi_keberangkatan} - {program} - {harga}')
			->display_as('jamaah_id', 'Nama Jamaah')
			->display_as('agen_id', 'Nama Agen')
			// ->display_as('jamaah_id', 'Jamaah') // revisi 16 maret 2024, remove jamaah
			->display_as('paket_id', 'Paket')
			->display_as('jumlah_upgrade_kamar_double', 'Jumlah Upgrade Kamar Double')
			->display_as('harga_upgrade_kamar_double', 'Harga Upgrade Kamar Double')
			->display_as('jumlah_upgrade_kamar_triple', 'Jumlah Upgrade Kamar Triple')
			->display_as('harga_upgrade_kamar_triple', 'Harga Upgrade Kamar Triple')
			// revisi 16 maret 2024, mengganti wording
			->display_as('biaya_tambahan_paspor', 'Biaya Paspor Kolektif')
			->display_as('biaya_tambahan_vaksin', 'Biaya Vaksin Kolektif')
			// ->display_as('uang_muka', 'DP')
			->display_as('tanggal_deposit_minimum', 'Tanggal Deposit Minimum 50%')
			->display_as('total_biaya', 'Total Biaya')
			->display_as('tanggal_pelunasan_maksimal', 'Tanggal Pelunasan Maksimal')
			// ->display_as('estimasi_keberangkatan', 'Estimasi Keberangkatan') // revisi 16 maret 2024, dihilangkan diganti harga
			// revisi 16 maret 2024, mengganti wording
			->display_as('biaya_lain', 'Biaya Lain-lain Kolektif')
			->display_as('biaya_lain_alias', 'Alias Biaya Lain-lain Kolektif')
			->display_as('biaya_lain_2', 'Biaya Lain-lain Kolektif 2')
			->display_as('biaya_lain_alias_2', 'Alias Biaya Lain-lain Kolektif 2')
			// revisi 16 maret 2024, menambahkan kolom
			->display_as('biaya_perlengkapan', 'Biaya Perlengkapan Kolektif')
			->display_as('total_tagihan', 'Total Tagihan')
			// ->display_as('uang_muka', 'DP')
			->display_as('debit', 'Debit (Rp)')
			->display_as('kredit', 'Kredit (Rp)')
			->display_as('kekurangan', 'Sisa Tagihan (Rp)')
			// ->display_as('metode', 'Cara Bayar')
			->display_as('created_at', 'Tanggal Buat')
			->display_as('updated_at', 'Tanggal Ubah')
			->display_as('total_tagihan_dg_diskon', 'Total Tagihan - Diskon')
			->display_as('total_deposit', 'Total Deposit')
			->display_as('harga', 'Harga per Paket')
			->display_as('program', 'Paket')
			->display_as('jumlah_jamaah', 'Jumlah Jamaah')
			
			->unset_read()
			->columns([
				'no_invoice', 
				'jamaah_id', 
				'agen_id',
				// 'total_tagihan',
				// revisi 16 maret 2024, menambahkan DP
				'total_tagihan_dg_diskon',
				// 'uang_muka',
				'pembiayaan',
				'debit',
				'kredit',
				'total_deposit',
				'kekurangan',
				'jumlah_jamaah',
				// 'estimasi_keberangkatan', // revisi 16 maret 2024, dihilangkan diganti harga
				'harga',
				'program',
				'created_at',
				'updated_at'])
			->add_fields(
				'jamaah_id', 
				'agen_id',
				// 'jamaah_id', // revisi 16 maret 2024, remove jamaah
				'paket_id', 
				'jumlah_jamaah',
				'jumlah_upgrade_kamar_double', 
				'harga_upgrade_kamar_double', 
				'jumlah_upgrade_kamar_triple', 
				'harga_upgrade_kamar_triple', 
				'biaya_tambahan_paspor',
				'biaya_tambahan_vaksin',
				'biaya_lain',
				'biaya_lain_alias',
				'biaya_lain_2',
				'biaya_lain_alias_2',
				// revisi 16 maret 2024, menambahkan field
				'biaya_perlengkapan',
				// 'uang_muka',
				// 'metode',
				'diskon',
				// 'fee',
				// 'tanggal_deposit_minimum',
				'catatan'
			)
			->edit_fields(
				'no_invoice',
				'jamaah_id', 
				'agen_id',
				// 'jamaah_id', // revisi 16 maret 2024, remove jamaah
				'paket_id', 
				'jumlah_jamaah',
				'jumlah_upgrade_kamar_double', 
				'harga_upgrade_kamar_double', 
				'jumlah_upgrade_kamar_triple', 
				'harga_upgrade_kamar_triple', 
				'biaya_tambahan_paspor',
				'biaya_tambahan_vaksin',
				'biaya_lain',
				'biaya_lain_alias',
				'biaya_lain_2',
				'biaya_lain_alias_2',
				// revisi 16 maret 2024, menambahkan field
				'biaya_perlengkapan',
				'total_biaya',
				// 'uang_muka',
				// 'metode',
				'diskon',
				// 'fee',
				'tagihan',
				// 'tanggal_deposit_minimum',
				// 'tanggal_pelunasan_maksimal',
				'catatan'
				)
			->callback_edit_field('total_biaya', 
				function ($value = '', $primary_key = null){
					return $this->total_biaya_callback($value, $primary_key);
			})
			->callback_edit_field('tagihan', 
				function ($value = '', $primary_key = null){
					return $this->tagihan_callback($value, $primary_key);
			})
			->callback_edit_field('tanggal_pelunasan_maksimal', 
				function($value = '', $primary_key = null){
					return $this->tanggal_pelunasan_maksimal_callback($value, $primary_key);
			})
			->callback_edit_field('no_invoice', function ($value, $primary_key) {
				return '<input id="field-no_invoice" class="form-control" name="no_invoice" type="text" value="'.$value.'" maxlength="255" disabled>';
			})
			
			// ->callback_after_update(array($this, 'uang_muka_update'))
			->callback_column('jamaah_id', array($this, 'jamaah_id_callback'))
			// 'estimasi_keberangkatan', // jarevisi 16 maret 2024, dihilangkan diganti harga
			// ->callback_column('estimasi_keberangkatan', array($this, 'estimasi_keberangkatan_callback'))
			->callback_column('harga', array($this, 'harga_callback'))
			->callback_column('program', array($this, 'program_callback'))
			->callback_column('total_tagihan', array($this, 'total_tagihan_callback'))
			->callback_column('debit', array($this, 'debit_callback'))
			->callback_column('kredit', array($this, 'kredit_callback'))
			->callback_column('total_deposit', array($this, 'total_deposit_callback'))
			->callback_column('kekurangan', array($this, 'kekurangan_callback'))
			->callback_column('jumlah_jamaah', array($this, 'jumlah_jamaah_callback'))
			// ->callback_column('uang_muka', array($this, 'format_number_callback'))
			->callback_column('pembiayaan', array($this, 'pembiayaan_callback'))
			->callback_column('updated_at', array($this, 'updated_at_kolektif_callback'))
			->callback_column('created_at', array($this, 'created_at_kolektif_callback'))
			->callback_column('total_tagihan_dg_diskon', array($this, 'total_tagihan_dg_diskon_callback'))
			->unset_fields('created_at', 'updated_at')
			->callback_delete( array($this,'soft_delete_transaksi_kolektif_callback'))
			->callback_after_update(array($this, 'updated_by_transaksi_kolektif_callback'))
			->callback_after_insert(array($this, 'insert_no_invoice_update'))
			->order_by('created_at', 'desc');
		$this->show();
	}

	// revisi 16 maret 2024, remove jamaah parent

	function transaksi_kolektif_anak($kolektif_id){
		$paket = $this->get_paket_umroh_by_transaksi_kolektif_id($kolektif_id);
		$total_jamaah = $this->get_jumlah_jamaah_by_transaksi_kolektif_id($kolektif_id);

		if($total_jamaah != $paket->jumlah_jamaah) $this->crud->set_warning('PERHATIAN! Jumlah jamaah belum sesuai kuota ' . $total_jamaah . ' / ' . $paket->jumlah_jamaah);


		$this->crud
			->where('transaksi_kolektif_id', $kolektif_id)
			->set_subject('Daftar Jamaah'.  ' (' . $paket->program . ')')
			->set_top('Daftar Jamaah'.  ' (' . $paket->program . ')')
			->set_table('transaksi_kolektif_anak')
			->unset_read()
			->required_fields('jamaah_anak_id')
			->columns([
				// revisi 16 maret 2024, remove jamaah parent
				// 'nama_jamaah', 
				'jamaah_anak_id', 
				'biaya_lain', 
				'biaya_tambahan_paspor', 
				'biaya_tambahan_vaksin',
				'biaya_perlengkapan'])
			->fields(
				'transaksi_kolektif_id', 
				// revisi 16 maret 2024, remove jamaah parent
				// 'jamaah_induk_id',
				// 'nama_jamaah',
				'jamaah_anak_id',
				'biaya_lain',
				'biaya_tambahan_paspor',
				'biaya_tambahan_vaksin',
				'biaya_perlengkapan',
				'catatan') // revisi 16 maret 2024, tambah kolom baru
			->change_field_type('transaksi_kolektif_id', 'hidden', $kolektif_id)
			// revisi 16 maret 2024, remove jamaah parent
			// ->change_field_type('jamaah_induk_id', 'hidden', $jamaah_id)
			->set_relation('jamaah_anak_id', 'data_jamaah', '{no_ktp} - {nama_jamaah}')
			->where('id_jamaah !=', $kolektif_id)
			->display_as('jamaah_anak_id', 'Nama Jamaah')
			// revisi 16 maret 2024, remove jamaah parent
			// ->display_as('nama_jamaah', 'Nama Jamaah')
			->display_as('biaya_lain', 'Biaya Lain-lain')
			->display_as('biaya_tambahan_paspor', 'Biaya Paspor')
			->display_as('biaya_tambahan_vaksin', 'Biaya Vaksin')
			->display_as('biaya_perlengkapan', 'Biaya Perlengkapan')
			// revisi 16 maret 2024, format number
			->callback_column('biaya_lain', array($this, 'format_number_callback'))
			->callback_column('biaya_tambahan_paspor', array($this, 'format_number_callback'))
			->callback_column('biaya_tambahan_vaksin', array($this, 'format_number_callback'))
			->callback_column('biaya_perlengkapan', array($this, 'format_number_callback'));
			// revisi 16 maret 2024, remove jamaah parent
			// ->callback_column('nama_jamaah', array($this, 'nama_jamaah_column_callback'))
			// ->callback_field('nama_jamaah', 
			// 	function () use ($jamaah_id) {
			// 		return $this->nama_jamaah_callback($jamaah_id);
			// });

		$this->show();
	}

	function transaksi_kolektif_kontrak($kolektif_id){
		$paket = $this->get_paket_umroh_by_transaksi_kolektif_id($kolektif_id);
		$this->crud
			->where('transaksi_kolektif_id', $kolektif_id)
			->required_fields('catatan', 'nomor_kontrak', 'nominal')->unique_fields('nomor_kontrak')
			->set_subject('Pembayaran Transaksi Kolektif Kontrak'.  ' (' . $paket->program . ')')
			->set_top('Pembayaran Transaksi Kolektif Kontrak'.  ' (' . $paket->program . ')')
			->set_table('transaksi_kolektif_kontrak')
			->unset_read()->columns([
				'nama_kontrak',
				'nomor_kontrak',
				'catatan',
				'tanggal_pencairan',
				'nominal',
				'created_at',
				'updated_at'
			])
			->fields(
				'transaksi_kolektif_id',
				'nama_kontrak',
				'nomor_kontrak',
				'tanggal_pencairan',
				'nominal',
				'catatan')
			->change_field_type('transaksi_kolektif_id', 'hidden', $kolektif_id)
			->display_as('nama_kontrak', 'Nama Kontrak')
			->display_as('nomor_kontrak', 'No Kontrak')
			->display_as('tanggal_pencairan', 'Tanggal Pencairan')
			->display_as('nominal', 'Nominal (Rp)')
			->display_as('created_at', 'Tanggal Buat')
			->display_as('updated_at', 'Tanggal Ubah')
			->callback_column('nominal', array($this, 'format_number_callback'));
		$this->show();
	}

	function transaksi_kolektif_pembayaran($kolektif_id, $jenis){
		$paket = $this->get_paket_umroh_by_transaksi_kolektif_id($kolektif_id);
		$user_id = $this->session->userdata('id_admin');
		$required_fields = ['nominal', 'metode', 'tanggal_transfer', 'jenis_transaksi_id', 'penerima'];

		$columns = [
			'no_invoice',
			'no_transaksi', // revisi 16 maret 2024, tambahkan
			'nominal',
			'tanggal_transfer',
			'metode',
			'jenis_transaksi_id',
			'penerima',
			'user_id', 
			'created_at', 
			'updated_at'
		];
		$add_columns = [
			'transaksi_kolektif_id',
			'tanda',
			'nominal',
			'metode',
			'tanggal_transfer',
			'jenis_transaksi_id',
			'penerima',
			'keterangan', 
			'user_id', 
			'updated_by'
			
		];
		$edit_columns = [
			'no_invoice',
			'transaksi_kolektif_id',
			'tanda',
			'nominal',
			'metode',
			'tanggal_transfer',
			'jenis_transaksi_id',
			'penerima',
			'keterangan', 
			'user_id', 
			'updated_by'
			
		];

		if($jenis == 'debit'){
			$this->crud->display_as('penerima', 'Nama Penerima');
		}

		if($jenis == 'kredit'){
			$this->crud->display_as('penerima', 'Nama Penyetor');
		}
		
		$this->crud
			->where('transaksi_kolektif_id', $kolektif_id)
			->required_fields($required_fields)
			->where('deleted_at IS NULL')
			->where('tanda', ($jenis == 'debit' ? '-' : '+'))
			->set_subject('Pembayaran Transaksi Kolektif ' . ucfirst($jenis) .  ' (' . $paket->program . ')')
			->set_top('Pembayaran Transaksi Kolektif '. ucfirst($jenis) .  ' (' . $paket->program . ')')
			->set_is_payment_collective_transaction(true)
			->set_table('transaksi_kolektif_pembayaran')
			->unset_read()
			->columns(
				$columns
			)
			->add_fields(
				$add_columns
			)
			->edit_fields(
				$edit_columns
			)
			->display_as('metode', 'Cara Pembayaran')
			->display_as('tanggal_transfer', 'Tanggal Bayar')
			->display_as('jenis_transaksi_id', 'Jenis Transaksi')
			->display_as('user_id', 'Teller')
			->display_as('created_at', 'Tanggal Buat')
			->display_as('updated_at', 'Tanggal Ubah')
			->display_as('no_invoice', 'No Invoice')
			->display_as('no_transaksi', 'No Transaksi')
			
			->change_field_type('transaksi_kolektif_id', 'hidden', $kolektif_id)
			->change_field_type('tanda', 'hidden', ($jenis == 'debit' ? '-' : '+'))
			->change_field_type('user_id', 'hidden', $user_id)
			->change_field_type('updated_by', 'hidden', $user_id)
			->set_relation('jenis_transaksi_id', 'jenis_transaksi', 'nama_transaksi')
			->callback_column('user_id', array($this, 'user_id_callback'))
			->callback_column('nominal', array($this, 'nominal_callback'))
			->callback_column('no_transaksi', array($this, 'no_transaksi_callback'))
			->callback_column('no_invoice', array($this, 'no_invoice_callback'))
			->callback_column('created_at', array($this, 'created_at_kolektif_pembayaran_callback'))
			->callback_column('updated_at', array($this, 'updated_at_kolektif_pembayaran_callback'))
			->callback_edit_field('no_invoice', function ($value, $primary_key) {
				return '<input id="field-no_invoice" class="form-control" name="no_invoice" type="text" value="'.$value.'" maxlength="255" disabled>';
			})
			->callback_delete( array($this,'soft_delete_transaksi_kolektif_pembayaran_callback'))
			->callback_after_insert(array($this, 'insert_no_invoice_pembayaran_callback'))
			->where('jenis_transaksi_id >', 0)
			->order_by('created_at', 'desc');

		$this->show();
	}

	function transaksi_kolektif_laporan_harian(){
		$this->crud
			->set_table('transaksi_kolektif_pembayaran')
			->unset_read()->columns([
				'no_invoice',
				'no_transaksi',
				'keterangan',
				'created_at',
				'debit',
				'kredit',
				'metode',
				'jenis_transaksi_id',
				'user_id',
				'deleted_at',
				'updated_at'
			])
			->display_as('no_invoice', 'No Invoice')
			->display_as('no_transaksi', 'No Transaksi')
			->display_as('keterangan', 'Catatan')
			->display_as('debit', 'Debit (Rp)')
			->display_as('kredit', 'Kredit (Rp)')
			->display_as('metode', 'Cara Bayar')
			->display_as('jamaah_nik_paket', 'Jamaah / NIK / Paket')
			->display_as('created_at', 'Tanggal Transaksi')
			->display_as('jenis_transaksi_id', 'Jenis Transaksi')
			->display_as('user_id', 'Teller')
			->display_as('deleted_at', 'Histori Hapus')
			->display_as('updated_at', 'Waktu Update Terakhir')
			// revisi 16 maret 2024, hapus jamaah
			// ->callback_column('jamaah_nik_paket', array($this, 'jamaah_nik_paket_callback'))

			// revisi 16 maret 2024, tambah kolom no transaksi
			->callback_column('no_invoice', array($this, 'no_invoice_callback'))
			->callback_column('no_transaksi', array($this, 'no_transaksi_callback'))
			->callback_column('debit', array($this, 'debit_laporan_callback'))
			->callback_column('kredit', array($this, 'kredit_laporan_callback'))
			->callback_column('jenis_transaksi_id', array($this, 'jenis_transaksi_callback'))
			->callback_column('user_id', array($this, 'user_id_callback'))
			->callback_column('deleted_at', array($this, 'histori_deleted_callback'))
			->callback_column('updated_at', array($this, 'histori_updated_callback'))
			->unset_add()
			->unset_edit()
			->unset_delete()
			->order_by('id', 'desc');

		$jamaah_count = $this->db
			->from('transaksi_kolektif_anak tka')
			->join('transaksi_kolektif tk', 'tk.id = tka.transaksi_kolektif_id')
			->select('tka.id')
			->where('tk.deleted_at IS NULL')
			->get()->num_rows(); 
		$debit = $this->db
			->from('transaksi_kolektif_pembayaran')
			->select("SUM(nominal) debit")
			->where('deleted_at IS NULL')
			->where('tanda', '-')
			->get()->row(); 

		$kredit = $this->db
			->from('transaksi_kolektif_pembayaran')
			->select("SUM(nominal) kredit")
			->where('deleted_at IS NULL')
			->where('tanda', '+')
			->get()->row(); 

			$extra = [
				'jamaah_count' => number_format($jamaah_count, 0, ',', '.'),
				'debit_sum' => isset($debit->debit) ? number_format($debit->debit, 2, ',', '.') : '0',
				'kredit_sum' => isset($kredit->kredit) ? number_format($kredit->kredit, 2, ',', '.') : '0',
				'tag' => 'kolektif_laporan_harian'
			];

			$this->crud->set_footer($extra);
		
		$this->show();
	}

	// revisi 16 maret 2024, hapus jamaah
	function transaksi_kolektif_invoice($kolektif_id){
		
		$data = $this->get_transaksi_kolektif($kolektif_id,
			'
			no_invoice,
			tk.created_at,
			tk.paket_id, 
			harga, 
			tk.paket_id, 
			harga,
			djp.estimasi_keberangkatan,
			djp.program,
			dh1.nama hotel_makkah,
			dh2.nama hotel_madinah,
			jumlah_upgrade_kamar_double, 
			harga_upgrade_kamar_double, 
			jumlah_upgrade_kamar_triple, 
			harga_upgrade_kamar_triple, 
			biaya_tambahan_paspor, 
			biaya_tambahan_vaksin,
			dj.nama_jamaah,
			IF(dj.is_agen = 1, dj.nama_jamaah, "") nama_agen,
			tk.catatan,
			tk.biaya_lain,
			tk.biaya_lain_alias,
			tk.biaya_lain_2,
			tk.biaya_lain_alias_2,
			tk.diskon,
			tk.biaya_perlengkapan biaya_perlengkapan_kolektif,
			tk.jumlah_jamaah
			'
		);

		$jamaah_anak = $this->db
			->select(
				'nama_jamaah nama_jamaah_anak, 
				biaya_lain biaya_lain_anak,
				biaya_tambahan_paspor biaya_tambahan_paspor_anak,
				biaya_tambahan_vaksin biaya_tambahan_vaksin_anak, biaya_perlengkapan')
			->from('transaksi_kolektif_anak')
			->join('data_jamaah', 'id_jamaah = jamaah_anak_id')
			->where('transaksi_kolektif_id', $kolektif_id)
			->get()->result();

		$data->total_jamaah = intval(count($jamaah_anak));

		$data->subtotal = $this->get_total_biaya($kolektif_id);
		$data->total_biaya = $data->subtotal - $data->diskon;

		$data->tanggal_pelunasan_maksimal = $this->tanggal_pelunasan_maksimal_callback('', $kolektif_id);
		// $data->tanggal_deposit_minimum = (new DateTime($data->tanggal_deposit_minimum))->format('d/m/Y');

		$data->jamaah_anak = $jamaah_anak;

		$data->pembiyaan = $this->get_pembiayaan($kolektif_id);

		$data->debit = $this->db
			->select('SUM(nominal) total_nominal')
			->from('transaksi_kolektif_pembayaran')
			->where('transaksi_kolektif_id', $kolektif_id)
			->where('tanda', '-')
			->where('deleted_at is NULL')
			->get()->row();
		$data->kredit = $this->db
		->select('SUM(nominal) total_nominal')
		->from('transaksi_kolektif_pembayaran')
		->where('transaksi_kolektif_id', $kolektif_id)
		->where('tanda', '+')
		->where('deleted_at is NULL')
		->get()->row();

		$this->load->view('transaksi/transaksi_kolektif_invoice', $data);
	}

	function transaksi_kolektif_rincian_invoice($kolektif_id){
		$data = $this->get_transaksi_kolektif($kolektif_id,
			'
			no_invoice,
			tk.created_at,
			tk.paket_id, 
			harga, 
			tk.paket_id, 
			harga,
			djp.estimasi_keberangkatan,
			djp.program,
			dh1.nama hotel_makkah,
			dh2.nama hotel_madinah,
			jumlah_upgrade_kamar_double, 
			harga_upgrade_kamar_double, 
			jumlah_upgrade_kamar_triple, 
			harga_upgrade_kamar_triple, 
			biaya_tambahan_paspor, 
			biaya_tambahan_vaksin,
			dj.nama_jamaah,
			IF(dj.is_agen = 1, dj.nama_jamaah, "") nama_agen,
			tk.catatan,
			tk.biaya_lain,
			tk.biaya_lain_alias,
			tk.biaya_lain_2,
			tk.biaya_lain_alias_2,
			tk.diskon,
			tk.biaya_perlengkapan biaya_perlengkapan_kolektif,
			jumlah_jamaah
			'
		);

		$jamaah_anak = $this->db
			->select(
				'nama_jamaah nama_jamaah_anak, 
				biaya_lain biaya_lain_anak,
				biaya_tambahan_paspor biaya_tambahan_paspor_anak,
				biaya_tambahan_vaksin biaya_tambahan_vaksin_anak, biaya_perlengkapan')
			->from('transaksi_kolektif_anak')
			->join('data_jamaah', 'id_jamaah = jamaah_anak_id')
			->where('transaksi_kolektif_id', $kolektif_id)
			->get()->result();

		$data->total_jamaah = intval(count($jamaah_anak));

		$data->subtotal = $this->get_total_biaya($kolektif_id);
		$data->total_biaya = $data->subtotal - $data->diskon;

		$data->tanggal_pelunasan_maksimal = $this->tanggal_pelunasan_maksimal_callback('', $kolektif_id);
		// $data->tanggal_deposit_minimum = (new DateTime($data->tanggal_deposit_minimum))->format('d/m/Y');

		$data->jamaah_anak = $jamaah_anak;

		$data->pembiyaan = $this->get_pembiayaan($kolektif_id);

		$data->debit = $this->db
			->select('SUM(nominal) total_nominal')
			->from('transaksi_kolektif_pembayaran')
			->where('transaksi_kolektif_id', $kolektif_id)
			->where('tanda', '-')
			->where('deleted_at is NULL')
			->get()->row();
		$data->kredit = $this->db
		->select('SUM(nominal) total_nominal')
		->from('transaksi_kolektif_pembayaran')
		->where('transaksi_kolektif_id', $kolektif_id)
		->where('tanda', '+')
		->where('deleted_at is NULL')
		->get()->row();


		$data->rincian_pembayaran = $this->db
		->select('keterangan, created_at, nominal, tanda, nama_admin')
		->from('transaksi_kolektif_pembayaran')
		->join('admin', 'id_admin = user_id')
		->where('transaksi_kolektif_id', $kolektif_id)
		->get()->result();

		$data->kontrak = $this->db
			->select('catatan, tanggal_pencairan, nominal, nomor_kontrak, nama_kontrak')
			->from('transaksi_kolektif_kontrak')
			->where('transaksi_kolektif_id', $kolektif_id)
			->get()->result();
		$data->total_pembiayaan = $this->db
			->select('SUM(nominal) nominal')
			->from('transaksi_kolektif_kontrak')
			->where('transaksi_kolektif_id', $kolektif_id)
			->get()->row();
		
		$paket = $this->get_paket_umroh_by_transaksi_kolektif_id($kolektif_id);
		$data->program = $paket->program;

		$this->load->view('transaksi/transaksi_kolektif_rincian_invoice', $data);
	}

	// revisi 26 maret 2024, cetak invoice
	function transaksi_kolektif_pembayaran_invoice($id){
		$this->load->helper('number');
		$this->load->helper('date');

		$data = $this->db->from('transaksi_kolektif_pembayaran tkp')
			->select('
				tkp.id,
				djp.estimasi_keberangkatan, 
				djp.Program program, 
				tkp.nominal, 
				jt.nama_transaksi, 
				tkp.keterangan,
				tkp.tanggal_transfer, 
				tkp.created_at,
				tkp.penerima,
				a.nama,
				tkp.tanda,
				dj.nama_jamaah,
				tk.no_invoice')
			->join('transaksi_kolektif tk', 'tkp.transaksi_kolektif_id = tk.id')
			->join('data_jamaah_paket djp', 'tk.paket_id = djp.id')
			->join('jenis_transaksi jt', 'jt.id = tkp.jenis_transaksi_id')
			->join('admin a', 'tkp.user_id = a.id_admin')
			->join('data_jamaah dj', 'dj.id_jamaah = tk.jamaah_id', 'left')
			->where('tkp.id', $id)->get()->row();

		$this->load->view('transaksi/transaksi_kolektif_pembayaran_invoice', $data);

	}

	public function soft_delete_transaksi_kolektif_pembayaran_callback($primary_key){
		$user_id = $this->session->userdata('id_admin');
    	return $this->db->update('transaksi_kolektif_pembayaran', 
			array(
				'deleted_at' => date('Y-m-d H:i:s'),
				'deleted_by' => $user_id
			),
			array('id' => $primary_key)
		);
	}

	public function soft_delete_transaksi_kolektif_callback($primary_key){
		$user_id = $this->session->userdata('id_admin');
    	return $this->db->update('transaksi_kolektif', 
			array(
				'deleted_at' => date('Y-m-d H:i:s'),
				'deleted_by' => $user_id
			),
			array('id' => $primary_key)
		);
	}

	// rev 19 mei 2024, mengambil paket umroh

	public function get_paket_umroh_by_transaksi_kolektif_id($kolektif_id){
		$paket = $this->db->select('djp.estimasi_keberangkatan, djp.program, tk.jumlah_jamaah')
			->from('transaksi_kolektif tk')
			->where('tk.id', $kolektif_id)
			->join('data_jamaah_paket djp', 'tk.paket_id =  djp.id')->get()->row();
		
		return $paket;
	}

	public function get_jumlah_jamaah_by_transaksi_kolektif_id($kolektif_id){
		$paket = $this->db->select('COUNT(tka.id) total')
			->from('transaksi_kolektif_anak tka')
			->where('tka.transaksi_kolektif_id', $kolektif_id)->get()->row();
		
		return $paket->total;
	}

	// rev 19 mei 2024, tampilkan user yang mengupdate
	public function updated_by_transaksi_kolektif_callback($post_array, $primary_key){
		$user_id = $this->session->userdata('id_admin');
    	return $this->db->update('transaksi_kolektif', 
			array(
				'updated_by' => $user_id
			),
			array('id' => $primary_key)
		);
	}


	// revisi 16 maret, mengambil invoice dari induk
	function no_invoice_callback($value, $row){
		$transaksi = $this->db->select('no_invoice')
		->from('transaksi_kolektif')
		->where('id', $row->transaksi_kolektif_id)
		->get()->row();
		
		return $transaksi->no_invoice;
	}

	// revisi 16 maret 2024, no transaksi = no invoice + id transaksi
	function no_transaksi_callback($value, $row){
		return $this->no_invoice_callback(0, $row) . '-' . $row->id;
	}
	// revisi 16 maret 2024, perhitungan pembiayaan
	function pembiayaan_callback($value, $row){
		$pembiayaan = $this->get_pembiayaan($row->id);
		return '<a href="'.site_url('transaksi/transaksi_kolektif_kontrak/' . $row->id).'" target="_blank">'.  number_format($pembiayaan, 0 ,',','.'). '</a>';
		
	}
	// revisi 16 maret 2024, perhitungan pembiayaan
	function get_pembiayaan($transaksi_id){
		$pembiyaan = $this->db->select('SUM(nominal) sum_nominal')
		->from('transaksi_kolektif_kontrak')
		->where('transaksi_kolektif_id', $transaksi_id)
		->get()->row();

		return $pembiyaan->sum_nominal;
	}

	function nama_jamaah_callback($jamaah){
		$jamaah = $this->db->from('data_jamaah dj')
		->select('dj.no_ktp, dj.nama_jamaah')
		->where('dj.id_jamaah', $jamaah)
		->get()->row();

		return $jamaah->no_ktp .' - '. $jamaah->nama_jamaah;
	}

	// revisi 16 maret 2024 hapus jamaah di transaksi kolektif

	// function jamaah_nik_paket_callback($value, $row){
	// 	$jamaah = $this->db
	// 		->select('nama_jamaah, no_ktp, program')
	// 		->from('transaksi_kolektif')
	// 		->join('data_jamaah_paket', 'data_jamaah_paket.id = transaksi_kolektif.paket_id')
	// 		->join('data_jamaah', 'data_jamaah.id_jamaah = transaksi_kolektif.jamaah_id')
	// 		->where('transaksi_kolektif.id', $row->transaksi_kolektif_id)
	// 		->get()->row();

	// 	return $jamaah->nama_jamaah .' / '. $jamaah->no_ktp .'/'. $jamaah->program;

	// }

	// revisi 16 maret 2024, tambahkan kolom total tagihan - diskon

	function total_tagihan_dg_diskon_callback($value, $row){
		$tagihan = $this->get_tagihan($row->id);
		$result =  $tagihan - $row->diskon;
		return $result != 0 ?  number_format($result, 0 ,',','.') : 0;
	}

	function format_date_callback($value, $row){
		return  (new DateTime($value))->format('d/m/Y H:i');
		
	}

	function created_at_kolektif_callback($value, $row){
		$datetime = (new DateTime($value))->format('d/m/Y H:i');

		$username = '';

		if(isset($row->created_by)){
			$user = $this->db->select('username')->from('admin')->where('id_admin', $row->created_by)->get()->row();
			$username = $user->username; 
			
		}
		
		return  $datetime . ' / ' . $username;
	}

	function updated_at_kolektif_callback($value, $row){
		$datetime = (new DateTime($value))->format('d/m/Y H:i');

		$username = '';

		if(isset($row->updated_by)){
			$user = $this->db->select('username')->from('admin')->where('id_admin', $row->updated_by)->get()->row();
			$username = $user->username; 
			
		}
		
		return  $datetime . ' / ' . $username;
	
	}

	function created_at_kolektif_pembayaran_callback($value, $row){
		$datetime = (new DateTime($value))->format('d/m/Y H:i');
		return  $datetime . ' / ' . $row->user_id;
	}

	function updated_at_kolektif_pembayaran_callback($value, $row){
		$datetime = (new DateTime($value))->format('d/m/Y H:i');

		$username = '';

		if(isset($row->updated_by)){
			$user = $this->db->select('username')->from('admin')->where('id_admin', $row->updated_by)->get()->row();
			$username = $user->username; 
			
		}
		
		return  $datetime . ' / ' . $username;
	}

	function format_number_callback($value, $row){
		return number_format($value, 0 ,',','.');
	}

	function debit_laporan_callback($value, $row){
		if($row->tanda == '-'){
			return number_format($row->nominal, 0 ,',','.');
		}

		return '0';
	}

	function kredit_laporan_callback($value, $row){
		if($row->tanda == '+'){
			return number_format($row->nominal, 0 ,',','.');
		}

		return '0';
	}

	function jenis_transaksi_callback($value, $row){
		$jenis_transaksi = $this->db
			->select('nama_transaksi')
			->from('jenis_transaksi')
			->where('id', $row->jenis_transaksi_id)
			->get()->row();
		$jenis = isset($jenis_transaksi) ? $jenis_transaksi->nama_transaksi : 'DP';
		return $jenis;
	}

	function histori_deleted_callback($value, $row){
		$user = $this->db
			->select('nama_admin')
			->from('admin')
			->where('id_admin', $row->deleted_by)
			->get()->row();

		if(!isset($value)) return;

		return (isset($user->nama_admin ) ? $user->nama_admin : '') .' / '. ($value != null ? (new DateTime($value))->format('d/m/Y H:i') : '');
	}

	function histori_updated_callback($value, $row){
		$user = $this->db
			->select('nama_admin')
			->from('admin')
			->where('id_admin', $row->updated_by)
			->get()->row();

		if(!isset($value)) return;

		return (isset($user->nama_admin ) ? $user->nama_admin : '') .' / '. ($value != null ? (new DateTime($value))->format('d/m/Y H:i') : '');
	}

	function user_id_callback($value, $row){
		$user = $this->db
			->select('nama_admin')
			->from('admin')
			->where('id_admin', $row->user_id)
			->get()->row();
		return $user->nama_admin;
	}

	function nama_jamaah_column_callback($value, $row){
		$jamaah = $this->db->from('data_jamaah dj')
		->select('dj.no_ktp, dj.nama_jamaah')
		->where('dj.id_jamaah', $row->jamaah_induk_id)
		->get()->row();

		return $jamaah->no_ktp .' - '. $jamaah->nama_jamaah;
	}

	function nominal_callback($value, $row){
		return number_format($value, 0 ,',','.');
	}

	function debit_callback($value, $row){
		$debit = $this->get_debit($row->id);

		$total = $debit->total_nominal != null ?  number_format($debit->total_nominal, 0 ,',','.') :  number_format(0, 0 ,',','.');
		return '<a href="'.site_url('transaksi/transaksi_kolektif_pembayaran/' . $row->id . '/debit').'" target="_blank">'. $total . '</a>';
	}

	function kredit_callback($value, $row){
		// rev 16 maret 2024, kredit mengurangi tagihan
		$kredit = $this->get_kredit($row->id);

		$total = $kredit != null ?  number_format($kredit, 0 ,',','.') :  number_format(0, 0 ,',','.');
		return '<a href="'.site_url('transaksi/transaksi_kolektif_pembayaran/' . $row->id . '/kredit').'" target="_blank">'. $total . '</a>';
	}

	// rev 19 mei 2024, menambahkan kolom total deposit

	function total_deposit_callback($value, $row){
		// rev 16 maret 2024, kredit mengurangi tagihan
		$kredit = $this->get_kredit($row->id);
		$debit = $this->get_debit($row->id);

		$total_deposit = intval($kredit) - intval($debit->total_nominal);

		$total = $total_deposit != null ?  number_format($total_deposit, 0 ,',','.') :  number_format(0, 0 ,',','.');

		return $total;
	}

	// rev 16 maret 2024, pemisihan perhitungan kredit
	function get_kredit($kolektif_id){
		$kredit = $this->db->from('transaksi_kolektif_pembayaran')
		->select('tanda, SUM(nominal) total_nominal')
		->join('jenis_transaksi', 'jenis_transaksi.id = jenis_transaksi_id')
		->where('transaksi_kolektif_id', $kolektif_id)
		->where('tanda', '+')
		->where('deleted_at IS NULL')
		->get()->row();

		return $kredit->total_nominal;
	}

	

	function kekurangan_callback($value, $row){
		$debit = $this->get_debit($row->id); // rev 16 maret 2024, kredit mengurangi tagihan
		$kredit = $this->get_kredit($row->id);
		$tagihan = $this->get_tagihan($row->id);

		$transaksi_kolektif = $this->qry_transaksi_kolektif()
			->select('diskon')
			->where('tk.id', $row->id)
			->get()->row();
		
		$total = ($tagihan - $transaksi_kolektif->diskon ) - 
				($kredit - $debit->total_nominal) -
				$this->get_pembiayaan($row->id);
				
		$total = ($total != null || $total != 0 ) ?  number_format( $total, 0 ,',','.' ) :  number_format(0, 0 ,',','.');
		return $total;
	}

	function jumlah_jamaah_callback($value, $row){
		$total_jamaah = $this->get_jumlah_jamaah_by_transaksi_kolektif_id($row->id);
		return '<a target="_blank" href="'.site_url('/transaksi/transaksi_kolektif_anak/'. $row->id).'">' . $total_jamaah .' / '. $value . '</a>';
	}

	function total_biaya_callback($value = '', $primary_key = null){
		
		$total = $this->get_total_biaya($primary_key);
		
		return $total != 0 ? number_format($total, 0 ,',','.') : 0;
	}

	function tagihan_callback($value = '', $primary_key = null){
		$transaksi = $this->db->from('transaksi_kolektif')->select('diskon')->where('id', $primary_key)->get()->row();
		$tagihan = $this->get_tagihan($primary_key) - $transaksi->diskon;
		
		return $tagihan != 0 ? number_format($tagihan, 0 ,',','.') :  number_format(0, 0 ,',','.');
	}

	function tanggal_pelunasan_maksimal_callback($value = '', $primary_key = null){
		$transaksi_kolektif = $this->qry_transaksi_kolektif()
		->select('tk.created_at')
		->where('tk.id', $primary_key)
		->get()->row();

		$date = new DateTime( isset($transaksi_kolektif->created_at) ? $transaksi_kolektif->created_at : null);
		$date->add(new DateInterval('P30D'));

		return $date->format('d/m/Y');
	}

	function jamaah_id_callback($value = '', $row){
		$transaksi_kolektif = $this->qry_transaksi_kolektif()
		->select('dj.nama_jamaah')
		->where('tk.id', $row->id)
		->join('data_jamaah dj', 'tk.jamaah_id = dj.id_jamaah')
		->get()->row();

		return $transaksi_kolektif->nama;
	}

	// 'estimasi_keberangkatan', // revisi 16 maret 2024, dihilangkan diganti harga

	// function estimasi_keberangkatan_callback($value = '', $row){
	
	// 	$transaksi_kolektif = $this->qry_transaksi_kolektif()
	// 	->select('djp.estimasi_keberangkatan')
	// 	->where('tk.id', $row->id)
	// 	->join('data_jamaah_paket djp', 'tk.paket_id = djp.id')
	// 	->get()->row();

	// 	return $transaksi_kolektif->estimasi_keberangkatan;
	// }

	function harga_callback($value = '', $row){
	
		$transaksi_kolektif = $this->qry_transaksi_kolektif()
		->select('djp.harga')
		->where('tk.id', $row->id)
		->join('data_jamaah_paket djp', 'tk.paket_id = djp.id')
		->get()->row();
		$harga = isset($transaksi_kolektif) && $transaksi_kolektif->harga > 0 ? number_format( $transaksi_kolektif->harga, 0 ,',','.' ) : '0';
		return $harga;
	}

	function program_callback($value = '', $row){
	
		$transaksi_kolektif = $this->qry_transaksi_kolektif()
		->select('djp.program')
		->where('tk.id', $row->id)
		->join('data_jamaah_paket djp', 'tk.paket_id = djp.id')
		->get()->row();

		if(isset($transaksi_kolektif)) return $transaksi_kolektif->program;
		return;
		
	}

	function total_tagihan_callback($value = '', $row){
		
		return $this->tagihan_callback($value = '', $row->id);

	}

	function insert_no_invoice_update($post_array, $primary_key){

		$user_id = $this->session->userdata('id_admin');
		$no_invoice = $this->generate_no_invoice();
		$post_array['created_by'] = $user_id;
		$post_array['no_invoice'] = $no_invoice;

	 
		$this->db->update('transaksi_kolektif',$post_array, ['id' => $primary_key]);

		
		return true;
	}

	function uang_muka_update($post_array, $primary_key){
		$user_id = $this->session->userdata('id_admin');
		$this->db->update('transaksi_kolektif_pembayaran', array(
			'nominal' =>  $post_array['uang_muka']
		), array(
			'transaksi_kolektif_id' => $primary_key,
			'jenis_transaksi_id' => -1,
			'updated_by' => $user_id
		));

		return true;
	}

	function insert_no_invoice_pembayaran_callback($post_array, $primary_key){
		$this->db->update('transaksi_kolektif_pembayaran', array('no_invoice' => $this->generate_no_invoice()), array('id' => $primary_key));
		return true;
	}

	function get_total_biaya($primary_key){
		$total = 0;
		$transaksi_kolektif = $this->get_transaksi_kolektif($primary_key, 
			'tk.paket_id, 
			harga,
			jumlah_upgrade_kamar_double, 
			harga_upgrade_kamar_double, 
			jumlah_upgrade_kamar_triple, 
			harga_upgrade_kamar_triple, 
			biaya_tambahan_paspor, 
			biaya_tambahan_vaksin,
			tk.biaya_lain,
			tk.biaya_lain_2,
			tk.biaya_perlengkapan,
			tk.diskon,
			tk.jumlah_jamaah'
		);

		$total = $this->get_total($primary_key, $transaksi_kolektif);

		return $total;
	}

	function get_total($primary_key, $transaksi_kolektif){
		$total = 
		// revisi 16 maret 2024, semua total harga ada di setiap jamaah
		// (isset($transaksi_kolektif->harga) ? intval($transaksi_kolektif->harga) : 0) + 
		(isset($transaksi_kolektif->jumlah_upgrade_kamar_double) ? intval($transaksi_kolektif->jumlah_upgrade_kamar_double * $transaksi_kolektif->harga_upgrade_kamar_double) : 0) + 
		(isset($transaksi_kolektif->jumlah_upgrade_kamar_double) ? intval($transaksi_kolektif->jumlah_upgrade_kamar_triple * $transaksi_kolektif->harga_upgrade_kamar_triple) : 0)+ 
		(isset($transaksi_kolektif->biaya_tambahan_paspor) ? intval($transaksi_kolektif->biaya_tambahan_paspor) : 0) + 
		(isset($transaksi_kolektif->biaya_tambahan_vaksin) ? intval($transaksi_kolektif->biaya_tambahan_vaksin) : 0) +
		(isset($transaksi_kolektif->biaya_lain) ? intval($transaksi_kolektif->biaya_lain) : 0) +
		(isset($transaksi_kolektif->biaya_lain_2) ? intval($transaksi_kolektif->biaya_lain_2) : 0) +
		(isset($transaksi_kolektif->biaya_perlengkapan) ? intval($transaksi_kolektif->biaya_perlengkapan) : 0);

		$transaksi_kolektif_anak = $this->db
			->select(
				'id, 
				biaya_lain, 
				biaya_tambahan_paspor, 
				biaya_tambahan_vaksin,
				biaya_perlengkapan') // revisi 16 maret 2024, menambahkan kolom baru
			->from('transaksi_kolektif_anak')
			->where('transaksi_kolektif_id', $primary_key)
			->get()->result();
	
		
		$total += (isset($transaksi_kolektif->harga) ? intval($transaksi_kolektif->harga) : 0) * 
				(isset($transaksi_kolektif->jumlah_jamaah) ? intval($transaksi_kolektif->jumlah_jamaah) : 0);
		foreach($transaksi_kolektif_anak as $t){
			$total +=  
			// (isset($transaksi_kolektif->harga) ? intval($transaksi_kolektif->harga) : 0) + 
			(isset($t->biaya_lain) ? intval($t->biaya_lain) : 0) + 
			(isset($t->biaya_tambahan_paspor) ? intval($t->biaya_tambahan_paspor) : 0) + 
			(isset($t->biaya_tambahan_vaksin) ? intval($t->biaya_tambahan_vaksin) : 0) +
			(isset($t->biaya_perlengkapan) ? intval($t->biaya_perlengkapan) : 0); // revisi 16 maret 2024, menambahkan kolom baru
		}
		
		return $total != 0 ? $total : 0;
	}

	function get_transaksi_kolektif($primary_key, $select = '*'){
		$transaksi_kolektif = $this->qry_transaksi_kolektif()
		->select($select)
		->where('tk.id', $primary_key)
		->join('data_jamaah_paket djp', 'tk.paket_id = djp.id')
		// ->join('data_jamaah dj1', 'dj1.id_jamaah = tk.jamaah_id')// 16 maret 2024, hapus jamaah
		->join('data_hotel dh1', 'dh1.id = djp.hotel_makkah', 'left')
		->join('data_hotel dh2', 'dh2.id = djp.hotel_madinah', 'left')
		->join('data_jamaah dj', 'dj.id_jamaah = tk.jamaah_id', 'left')
		// ->join('transaksi_kolektif_kontrak tkk', 'tk.id = tkk.transaksi_kolektif_id', 'left')
		->where('tk.deleted_at IS NULL')
		->get()->row();

		return $transaksi_kolektif;
	}

	function get_tagihan($id){
		
		$tagihan = $this->get_total_biaya($id);

		return $tagihan;
	}

	function get_debit($id){
		$debit = $this->db->from('transaksi_kolektif_pembayaran')
			->select('tanda, SUM(nominal) total_nominal')
			->join('jenis_transaksi', 'jenis_transaksi.id = jenis_transaksi_id')
			->where('transaksi_kolektif_id', $id)
			->where('tanda', '-')
			->where('deleted_at IS NULL')
			->get()->row();
		
		return $debit;

	}

	function qry_transaksi_kolektif(){
		$transaksi_kolektif = $this->db
			->from('transaksi_kolektif tk');

		return $transaksi_kolektif;
	}

	function generate_no_invoice(){
		return date('Ymdhis');
	}

	function pembayaran_invoice($id_paket, $id_trx, $agen = null){
		$tx = null;
		// $teller = $this->get('admin','id_admin',$j->teller,'nama_admin');
		if($agen != 0){
			$tx = $this->db->select("COUNT(tp.id) jumlah_jamaah, SUM(tp.kredit) dp")
			->from('transaksi_paket tp')
			->where('paket_umroh', $id_paket)
			->where('agen', $agen)
			->get()->row();
		}
		

		$data = [];
		$jamaah = $this->db->from('transaksi_paket tp');
		$select = "tp.jamaah, dj.nama_jamaah, djp1.estimasi_keberangkatan, djp1.Program program,
		djp2.estimasi_keberangkatan estimasi_keberangkatan_pengganti,
		dh11.nama hotel_makkah, dh21.nama hotel_madinah, 
		dh12.nama hotel_makkah_pengganti, dh22.nama hotel_madinah_pengganti,
		tp.permintaan_tambahan, djp1.estimasi_tgl_keberangkatan, djp2.estimasi_tgl_keberangkatan estimasi_tgl_keberangkatan_pengganti,
		dj.no_tlp, dj.hp_jamaah, tp.tgl_deposit, tp.tgl_pelunasan, tp.harga, djp2.Program program_pengganti, tp.kode
		";

		$jamaah = $jamaah->where('tp.id', $id_trx)
		->join('data_jamaah dj', 'tp.jamaah = dj.id_jamaah', 'left')
		->join('data_jamaah_paket djp1', 'tp.paket_umroh = djp1.id', 'left')
		->join('data_jamaah_paket djp2', 'tp.paket_umroh = djp2.paket_id', 'left')
		->join('data_hotel dh11', 'djp1.hotel_makkah = dh11.id', 'left')
		->join('data_hotel dh21', 'djp1.hotel_madinah = dh21.id', 'left')
		->join('data_hotel dh12', 'djp2.hotel_makkah = dh12.id', 'left')
		->join('data_hotel dh22', 'djp2.hotel_madinah = dh22.id', 'left');
		if($agen != 0){
			$jumlah_jamaah  = intval($tx->jumlah_jamaah);
			$dp = intval($tx->dp);
			$select .= ", 
			$jumlah_jamaah jumlah_jamaah, ($jumlah_jamaah * tp.harga) total, dja.nama nama_agen, $dp dp";
			$jamaah = $jamaah
			->join('data_jamaah_agen dja', 'tp.agen = dja.id', 'left');
		}else{
			$select .= ",
			'' nama_agen, 1 jumlah_jamaah, tp.harga total, kredit dp";
		}
		
		$jamaah = $jamaah->select($select)->get()->row();
		
		
		// var_dump($teller);

		$this->load->view('transaksi/transaksi_pembelian_invoice', $jamaah);
	}

	public function _kekurangan($value, $row)
	{	
		$harga = number_format($value,0,',','.');
		return "<a href='" . site_url('transaksi/histori/' . $row->id) . "' target='_blank'>$harga</a>";
	}
	public function __debet($value, $row)
	{
		$harga = number_format($value,0,',','.');
		return "<a href='" . site_url('transaksi/debet/' . $row->id) . "'  target='_blank'>$harga</a> <a href='" . site_url('transaksi/debet/' . $row->id) . "/add'  target='_blank'>+</a>";
	}
	public function __kredit($value, $row)
	{
		$harga = number_format($value,0,',','.');
		return "<a href='" . site_url('transaksi/kredit/' . $row->id) . "'  target='_blank'>$harga</a> <a href='" . site_url('transaksi/kredit/' . $row->id) . "/add'  target='_blank'>+</a>";
	}
	public function __kuitansi_kredit($value, $row)
	{
		return "<a href='" . site_url('kuitansi/kredit/' . $row->id) . "'  target='_blank'>".$this->format_rp($value)."</a>";
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
			redirect('transaksi/pembayaran');
		$this->crud->set_table('pembayaran_transaksi_paket');
		$j = $this->get_row('transaksi_paket', 'id', $id);
		$jamaah = $this->get('data_jamaah', 'id_jamaah', $j->jamaah, 'nama_jamaah');
		$ide = $this->session->userdata('id_admin');
		// $this->crud->callback_column('debet',array($this,'__kuitansi_kredit'));
		$p = $this->get_row('data_jamaah_paket', 'id', $j->paket_umroh);
		$paket = $p->estimasi_keberangkatan;
		$harga = $p->harga;
		list($debet, $kredit) = $this->get_sum($id, $harga);
		$kurang = intval($harga) - intval($kredit);
		$saldo = $kredit - $debet;
		$this->crud->set_top("Transaksi Debet $jamaah | Paket :$paket | Harga: ".$this->format_rp($harga)." | Pembayaran: ".$this->format_rp($kredit)." | Kekurangan: " . $this->format_rp($kurang) . "<br>Transaksi Debet : ".$this->format_rp($debet)." | saldo = ".$this->format_rp($saldo));

		$this->crud->set_subject("Transaksi Debet $jamaah | Paket :$paket | Harga: " . $this->format_rp($harga)." | Pembayaran:".$this->format_rp($kredit)." | Kekurangan: " . $this->format_rp($kurang) . "<br>Transaksi Debet : ". $this->format_rp($debet) ." | saldo = ". $this->format_rp($saldo));
		// $this->crud->set_subject("Debet Transaksi $jamaah<br>Paket :$paket<br>Harga:$harga <br>Kekurangan: ".$j->kekurangan);
		$this->crud->unset_read()->columns('jenis_transaksi', 'keterangan', 'tanggal', 'tanggal_transfer', 'debet', 'teller');
		$this->crud->display_as('jenis_transaksi', 'Jenis Transaksi')
				   ->display_as('tanggal_transfer', 'Tgl Transfer')
				   ->display_as('debet', 'Debit (IDR)');
		$this->crud->field_type('teller', 'hidden', $ide)
			->field_type('id_transaksi_paket', 'hidden', $id)
			->where('id_transaksi_paket', $id)
			->where('debet > 0')
			->set_relation('jenis_transaksi', 'jenis_transaksi_pengeluaran', 'nama_transaksi')
			->unset_texteditor('keterangan');
		$this->crud->callback_add_field('tanggal', function() {
			return '
			<input disabled type="text" name="date" value="'.date('d/m/Y').'" class="datepicker-input" /> (dd/mm/yyyy)';
		});
		$state = $this->crud->getState();
		// echo "state=$state";
		if ($state == 'ajax_list') {
			$this->crud->set_relation('teller', 'admin', 'nama');
		}
		$this->crud->callback_column('debet', array($this, '_harga_rp'));
		$this->crud->callback_after_insert(array($this, 'update_date_now'));
		$this->crud->fields('id_transaksi_paket', 'jenis_transaksi', 'tanggal', 'tanggal_transfer', 'debet', 'keterangan', 'teller');
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
		$this->db->update('transaksi_paket', array('kekurangan' => (intval($harga) - $kredit), 'debet' => $debet, 'kredit' => $kredit, 'saldo' => $saldo), array('id' => $id_transaksi_paket));
		// $this->db->update('transaksi_paket',array('kode'=>base_convert($primary_key,10,36)),array('id'=>$primary_key));
		return array($debet, $kredit);
	}
	function kredit($id = 0)
	{
		if ($id == 0)
			redirect('transaksi/pembayaran');
		$this->crud->set_table('pembayaran_transaksi_paket');
		$j = $this->get_row('transaksi_paket', 'id', $id);
		$jamaah = $this->get('data_jamaah', 'id_jamaah', $j->jamaah, 'nama_jamaah');
		//
		$this->crud->callback_column('kredit', array($this, '__kuitansi_kredit'));
		$this->crud->callback_column('debet', array($this, '_harga_rp'));
		$ide = $this->session->userdata('id_admin');
		$p = $this->get_row('data_jamaah_paket', 'id', $j->paket_umroh);
		$paket = $p->estimasi_keberangkatan;
		$harga = $p->harga;
		list($debet, $kredit) = $this->get_sum($id, $harga);
		$kurang = intval($harga) - $kredit;
		$price = isset($p->harga) && $p->harga != 0 ?  "Rp " .$p->harga :  "$ " .$p->harga_dolar;
		// $this->crud->set_subject("Kredit Transaksi $jamaah<br>Paket :$paket<br>Harga:$harga <br>Pembayaran:$kredit<br>Kekurangan: ".$kurang."<br>Transaksi Debet : $debet");
		$this->crud->set_top("Transaksi Kredit $jamaah | Paket :$paket | Harga: " .$price." | Pembayaran: ".$this->format_rp($kredit)." | Kekurangan: " .$this->format_rp($kurang). "<br>Transaksi Kredit : ".$this->format_rp($debet));
		$this->crud->set_subject("Transaksi Kredit $jamaah | Paket :$paket | Harga: " .$price." | Pembayaran:".$this->format_rp($kredit)." | Kekurangan: " . $this->format_rp($kurang) . "<br>Transaksi Kredit : ". $this->format_rp($debet));
		$this->crud->unset_read()->columns('jenis_transaksi', 'keterangan', 'tanggal', 'tanggal_transfer', 'kredit', 'debet', 'teller');
		$this->crud->field_type('teller', 'hidden', $ide)
			->field_type('id_transaksi_paket', 'hidden', $id)
			->where('id_transaksi_paket', $id)
			->where('kredit >', 0)
			->set_relation('jenis_transaksi', 'jenis_transaksi_pengeluaran', 'nama_transaksi')
			->unset_texteditor('keterangan');
		$state = $this->crud->getState();
		// echo "state=$state";
		if ($state == 'ajax_list') {
			$this->crud->set_relation('teller', 'admin', 'nama');
		}
		$this->crud->fields('id_transaksi_paket', 'jenis_transaksi', 'tanggal', 'tanggal_transfer', 'kredit', 'debet', 'keterangan', 'teller');

		$this->crud
			->display_as('jenis_transaksi', 'Jenis Transaksi')
			->display_as('tanggal_transfer', 'Tgl Transfer')
			->display_as('kredit', 'Kredit (IDR)')
			->display_as('debet', 'Debit (IDR)');
			if( $this->crud->getState() == 'edit' ) { //add these only in edit form
				$this->crud->set_css('assets/grocery_crud/css/ui/simple/'.grocery_CRUD::JQUERY_UI_CSS);
				$this->crud->set_js_lib('assets/grocery_crud/js/'.grocery_CRUD::JQUERY);
				$this->crud->set_js_lib('assets/grocery_crud/js/jquery_plugins/ui/'.grocery_CRUD::JQUERY_UI_JS);
				$this->crud->set_js_config('assets/grocery_crud/js/jquery_plugins/config/jquery.datepicker.config.js');
			}

		$this->crud->callback_add_field('tanggal', function() {
			return '
			<input disabled type="text" name="date" value="'.date('d/m/Y').'" class="datepicker-input" /> (dd/mm/yyyy)';
		});

		$this->crud->callback_after_insert(array($this, 'update_date_now'));

		$sum = $this->db->select('SUM(debet) debit, SUM(kredit) kredit')->from('pembayaran_transaksi_paket')->where('id_transaksi_paket', $id)->where('deleted IS NULL')->get()->row();
		$extra = [
			'tag' => 'transaksi_kredit',
			'debit' => $this->format_rp($sum->debit),
			'kredit' => $this->format_rp($sum->kredit),
			'saldo' => $this->format_rp(intval($sum->debit) -  intval($sum->kredit))
		];

		$this->crud->set_footer($extra);
		$this->show();
	}

	function update_date_now($post_array,$primary_key) {
		$this->db->where('id', $primary_key)->update('pembayaran_transaksi_paket',  ['tanggal' => date('Y-m-d')]);
	}

	function histori($id = 0)
	{
		$this->crud->set_table('pembayaran_transaksi_paket');
		$j = $this->get_row('transaksi_paket', 'id', $id);
		$jamaah = $this->get('data_jamaah', 'id_jamaah', $j->jamaah, 'nama_jamaah');

		$ide = $this->session->userdata('id_admin');
		$p = $this->get_row('data_jamaah_paket', 'id', $j->paket_umroh);
		$paket = $p->estimasi_keberangkatan;
		$harga = $p->harga;
		list($debet, $kredit) = $this->get_sum($id, $harga);
		$kurang = $harga - $kredit;
		$this->crud
			->set_subject("Transaksi Kredit $jamaah | Paket :$paket | Harga:$harga | Pembayaran:$kredit | Kekurangan: " . $kurang . "<br>Transaksi Debet : $debet")
			->set_top("Transaksi Kredit $jamaah | Paket :$paket | Harga:$harga | Pembayaran:$kredit | Kekurangan: " . $kurang . "<br>Transaksi Debet : $debet")
			
			->unset_add();
		$this->crud->unset_read()->columns('jenis_transaksi', 'keterangan', 'tanggal', 'debet', 'kredit', 'teller');
		$this->crud->field_type('teller', 'hidden', $ide)
			->field_type('id_transaksi_paket', 'hidden', $id)
			->where('id_transaksi_paket', $id)
			->set_relation('jenis_transaksi', 'jenis_transaksi_pengeluaran', 'nama_transaksi')
			->unset_texteditor('keterangan');
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
		$this->crud->display_as('nilai_namiroh', 'Nilai Namiroh');
		$this->crud->callback_column('nilai', array($this, '_harga_rp'));
		$this->crud->callback_column('nilai_namiroh', array($this, '_harga_rp'));
		$ide = $this->session->userdata('id_admin');
		$this->crud->set_relation('status', 'status_aktif', 'keterangan')->order_by('tanggal', 'desc')->display_as('tanggal', 'Waktu Update');
		//get from bank
		$url = $this->fungsiCurl('http://www.bankmandiri.co.id/resource/kurs.asp');
		$pecah = explode('<table class="tbl-view" cellpadding="0" cellspacing="0" border="0" width="100%">', $url);
		if(isset($pecah[1])) $pecah2 = explode('</table>', $pecah[1]);
		if(isset($pecah2[0])) $pecah3 = explode('<th>&nbsp;</th>', $pecah2[0]);
		//echo( $pecah3[2]);
		if(isset($pecah3[2])) $pecah4 = explode('<td>&nbsp;&nbsp;</td>', $pecah3[2]);
		$kurs = str_replace('<td align="right">', "", "");
		if(isset($pecah4[29])) $kurs = str_replace('<td align="right">', "", $pecah4[29]);
		$kurs = str_replace('</td>', "", $kurs);
		$kurs = str_replace('.', "", $kurs);
		// echo "k=$kurs<br>";
		$kurs = (int)$kurs;
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
			redirect('transaksi/update_kurs');
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