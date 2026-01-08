<?php

/**
 * Kelas Class
 *
 * @author	Awan Pribadi Basuki <awan_pribadi@yahoo.com>
 */
class MasterJamaahLink extends CI_Controller
{
	/**
	 * Constructor
	 */
	var $t = array();
	var $paket = array();
	var $transaksi_paket = array();
	var $crud = '';

	
	function __construct()
    {
        // 1. Jalankan Parent Constuctor (WAJIB PERTAMA)
        parent::__construct();

        // 2. Load Library Wajib
        $this->load->database();
        $this->load->helper('url');
        $this->load->library('session');
        $this->load->model('main_model', '', TRUE);
		$this->load->model('Location_model');
        $this->load->model('master_model', '', TRUE);
        $this->load->library('grocery_CRUD');

        // 3. --- [LOGIKA BYPASS LOGIN KHUSUS] ---
        // Ambil URL saat ini
        $current_url = $_SERVER['REQUEST_URI'];
        
        // Cek: Apakah URL mengandung kata 'jamaahUUID'?
        // stripos = case insensitive (huruf besar/kecil dianggap sama)
        $is_public_page = (stripos($current_url, 'jamaahUUID') !== FALSE);

        // Jika BUKAN halaman public, DAN User BELUM Login, baru ditendang.
        if ( ! $is_public_page ) {
            if ($this->session->userdata('login') != TRUE) {
                redirect('login');
            }
        }
        // ----------------------------------------

        $this->crud = new grocery_CRUD();
        
        $this->crud->unset_edit();
        $this->crud->unset_delete();
        $this->crud->unset_read();

        $this->_init();
    }

	
	private function _init()
    {
        $this->output->set_template('admin');
        
        // 1. Ambil Level User dari Session
        $ide = $this->session->userdata('level');

        // 2. CEK: Apakah $ide ada isinya? (Artinya user sedang login)
        if ( !empty($ide) ) {
            // Jika LOGIN: Ambil menu dari database sesuai level
            $this->output->set_output_data('menu', $this->main_model->get_menu($ide));
        } else {
            // Jika GUEST (Akses Link Langsung): 
            // Jangan jalankan query database menu! Kirim string kosong/array kosong.
            $this->output->set_output_data('menu', array()); 
        }

        $this->load->js('assets/themes/default/js/jquery-1.9.1.min.js');
        $this->load->js('assets/themes/default/js/jquery-migrate-3.4.1.js');
        $this->load->js('assets/themes/default/hero_files/bootstrap-transition.js');
        $this->load->js('assets/themes/default/hero_files/bootstrap-collapse.js');
    }


	private function show($module  = '')
	{
		$this->crud->set_theme('twitter-bootstrap');
		$output = $this->crud->render();
		$this->load->view('ci_simplicity/admin', $output);
	}
	/**
	 * Memeriksa user state, jika dalam keadaan login akan menampilkan halaman kelas,
	 * jika tidak akan meredirect ke halaman login
	 */
	function index()
	{
		redirect('masterjamaahlink/jamaah');
	}

	function hotel()
	{
		$this->crud->set_table('data_hotel');
		$this->crud->set_subject('Data Hotel');
		$this->crud->set_top('Data Hotel');
		$this->crud->display_as('nama', 'Nama Hotel');
		$this->show();
	}
	function kantor_imigrasi()
	{
		$this->crud->set_table('ref_imigrasi');
		$this->crud->set_subject('Data Kantor Imigrasi');
		$this->crud->set_top('Data Kantor Imigrasi');
		$this->show();
	}
	function jamah()
	{
		$this->crud->set_theme('twitter-bootstrap')->unset_delete()->unset_edit();//->unset_add();
		$output = $this->crud->render();
		$this->load->view('ci_simplicity/admin', $output);
	}

	function barang()
	{

		$this->crud->set_table('m_barang');
		if ($this->session->userdata('level')  != 7) {
			$this->crud->unset_delete()->unset_edit(); //buat batasi crud
		}

		$this->crud->set_subject('Data Barang');
		$this->crud->set_theme('bootstrap');
		$this->crud->unset_read()->columns('nama', 'nama_supplier');
		$this->crud->fields('nama', 'nama_supplier');
		$this->crud->required_fields('nama', 'nama_supplier');

		$this->show();
	}


	function koper()
	{
		$this->crud->set_table('data_koper');
		$this->crud->set_subject('Data Koper');
		$this->crud->display_as('nama', 'Nama Koper');
		$this->show();
	}

	function triple()
	{
		$this->crud->set_table('data_triple');
		$this->crud->set_subject('Data Triple');
		$this->crud->display_as('nama', 'Nama Triple');
		$this->show();
	}

	function double()
	{
		$this->crud->set_table('data_double');
		$this->crud->set_subject('Data Double');
		$this->crud->display_as('nama', 'Nama Double');
		$this->show();
	}

	function vaksin()
	{
		$this->crud->set_table('data_vaksin');
		$this->crud->set_subject('Data Vaksin');
		$this->crud->display_as('nama', 'Nama Vaksin');
		$this->show();
	}

	function passport()
	{
		$this->crud->set_table('data_passport');
		$this->crud->set_subject('Data Passport');
		$this->crud->display_as('nama', 'Nama Passport');
		$this->show();
	}



	function _get_barang_dalam_koper($value, $row)
	{
		$barangKOper = $this->db->query("SELECT b.nama FROM t_koper_barang tb JOIN m_barang b ON  b.id = tb.id_barang WHERE tb.id_tipe_koper =" . $row->id)->result();
		$string = "<ol>";
		foreach ($barangKOper as $b) {
			$string  .=  "<li> $b->nama </li>";
		}
		$string .= "<ol>";

		return $string;
	}

	function tipe_koper($type = null, $id_tipe_koper = null)
	{
		// if ($this->session->userdata('level')  == 7) {
		// 	$this->crud->unset_add()->unset_delete()->unset_edit(); //buat batasi crud
		// }
		// $this->crud->set_table('m_tipe_koper');
		// $this->crud->set_subject('Data Tipe Koper');
		// $this->crud->set_theme('datatables');
		// $this->crud->unset_read()->columns('nama', 'detail');
		// $this->crud->fields('nama', 'detail');
		// $this->crud->required_fields('nama');
		// $this->show();



		if ($this->session->userdata('level')  == 7) {
			$this->crud->unset_delete()->unset_edit(); //buat batasi crud
		}

		$this->crud->set_table('m_tipe_koper');
		$this->crud->set_subject('Data Tipe Koper');
			$this->crud->set_theme('datatables');
			$this->crud->unset_read()->columns('nama', 'detail');
			$this->crud->fields('nama', 'detail');
			$this->crud->required_fields('nama');

		if (!$type && !$id_tipe_koper) {
			// $this->crud->set_table('m_tipe_koper');
			$this->crud->set_subject('Data Tipe Koper');
			$this->crud->set_theme('datatables');
			$this->crud->unset_read()->columns('nama', 'detail');
			$this->crud->callback_column('detail', array($this, '_get_barang_dalam_koper'));
			// $this->crud->fields('nama');
			// $this->crud->required_fields('nama');
			$this->show();
		} else 	if ($type == "add") {
			$this->crud->set_theme('twitter-bootstrap');
			// $this->crud->set_table('m_tipe_koper');
			$output = $this->crud->render();

			$data['js_files'] = $output->js_files;
			$data['css_files'] = $output->css_files;


			$data['idTipeKoper'] = null;
			$data['urlSave'] = base_url("masterjamaahlink/add_tipe_koper");
			$data['type'] = "ADD";
			$data['title'] = "Tambah Tipe Koper";
			// $data['tipeKoper'] = $this->db->get_where('m_tipe_koper',  ['id' => $id_tipe_koper])->row();

			$data['barangAll'] = $this->db->select('b.id, b.nama AS nama_barang')
				->get('m_barang b')->result();
			$this->load->view('masterjamaahlink/tipe_koper', $data);
		} else if ($type == "edit") {
			$this->crud->set_theme('twitter-bootstrap');
			// $this->crud->set_table('m_tipe_koper');
			$output = $this->crud->render();

			$data['js_files'] = $output->js_files;
			$data['css_files'] = $output->css_files;


			$data['idTipeKoper'] = $id_tipe_koper;
			$data['urlSave'] = base_url("masterjamaahlink/edit_tipe_koper/" . $id_tipe_koper);
			$data['type'] = "EDIT";
			$data['title'] = "Edit Tipe Koper";
			$data['tipeKoper'] = $this->db->get_where('m_tipe_koper',  ['id' => $id_tipe_koper])->row();

			$data['barangAll'] = $this->db->query('select b.id,b.nama as nama_barang ,if(kb.id is not null, 1,0) as is_active
			from m_barang b 
			left join t_koper_barang kb  on b.id = kb.id_barang and kb.id_tipe_koper =' . $id_tipe_koper)->result();
			$this->load->view('masterjamaahlink/tipe_koper', $data);
		} else if ($type == "delete") {
			// $this->crud->set_table('m_tipe_koper');
			
			$this->show();

		}

	}

	public function add_tipe_koper()
	{
		$this->db->trans_begin();
		$param = $this->input->post();
		try {
			$this->db->insert('m_tipe_koper', ['nama' => $param['nama']]);
			$idTipeKoper = $this->db->insert_id();
			$getBarang  = $this->db->get('m_barang')->result();

			foreach ($getBarang as $barang) {
				if (array_key_exists('checkbox_' . $barang->id, $param)) {
					$this->db->insert('t_koper_barang', ['id_barang' => $barang->id, 'id_tipe_koper' => $idTipeKoper]);
				}
			}

			$this->db->trans_commit();
			redirect("masterjamaahlink/tipe_koper");
		} catch (\Throwable $th) {
			$this->db->trans_rollback();
			redirect($_SERVER['HTTP_REFERER']);
		}
	}


	public function edit_tipe_koper($idTipeKoper)
	{
		$this->db->trans_begin();
		$param = $this->input->post();
		try {
			$this->db->update('m_tipe_koper', ['nama' => $param['nama']], ['id' => $idTipeKoper]);
			$this->db->delete('t_koper_barang', ['id_tipe_koper' => $idTipeKoper]);

			$getBarang  = $this->db->get('m_barang')->result();

			foreach ($getBarang as $barang) {
				if (array_key_exists('checkbox_' . $barang->id, $param)) {
					$this->db->insert('t_koper_barang', ['id_barang' => $barang->id, 'id_tipe_koper' => $idTipeKoper]);
				}
			}

			$this->db->trans_commit();
			redirect("masterjamaahlink/tipe_koper");
		} catch (\Throwable $th) {
			$this->db->trans_rollback();
			redirect($_SERVER['HTTP_REFERER']);
		}
	}




	function koper_barang()
	{
		// $get_barang = $this->db->get('m_barang')->result();
		// $data_barang = [];
		// if ($get_barang != []) {
		// 	foreach ($get_barang as $i) {
		// 		$data_barang[$i->id] = $i->nama;
		// 	}
		// }

		// $get_koper = $this->db->get('m_tipe_koper')->result();
		// $data_koper = [];
		// if ($get_koper != []) {
		// 	foreach ($get_koper as $i) {
		// 		$data_koper[$i->id] = $i->nama;
		// 	}
		// }


		$this->crud->set_table('t_koper_barang');
		if ($this->session->userdata('level')  == 7) {
			$this->crud->unset_delete()->unset_edit(); //buat batasi crud
		}

		$this->crud->set_subject('Data Barang dalam Koper');
		$this->crud->set_theme('datatables');
		$this->crud->fields('id_barang', 'id_tipe_koper', 'created_by');
		$this->crud->required_fields('id_barang', 'id_tipe_koper');
		$this->crud->field_type('created_by', 'hidden', $this->session->userdata('id_admin'));
		// $this->crud->field_type(
		// 	'id_barang',
		// 	'dropdown',
		// 	$data_barang
		// );

		// $this->crud->field_type(
		// 	'id_tipe_koper',
		// 	'dropdown',
		// 	$data_koper
		// )
		$this->crud->set_relation('id_barang', 'm_barang', '{nama}');
		$this->crud->set_relation('id_tipe_koper', 'm_tipe_koper', '{nama}');

		$this->crud->unset_read()->columns('id_barang', 'id_tipe_koper', 'created_at');
		$this->crud->display_as('id_barang', 'Barang');
		$this->crud->display_as('id_tipe_koper', 'Tipe Koper');
		$this->crud->display_as('created_at', 'Dibuat');
		$this->show();
	}

	function koper_jamaah($type = null, $id = null)
	{
		// if ($this->session->userdata('level')  == 7) {
		// $this->crud->unset_add()->unset_delete()->unset_edit(); //buat batasi crud
		// }

		// select tp.id, j.id_jamaah, j.nama_jamaah, jp.Program 
		// from transaksi_paket tp 
		// join data_jamaah j on j.id_jamaah = tp.jamaah
		// join data_jamaah_paket jp on jp.id = tp.paket_umroh
		// where tp.id not in 

		// (select kp.id_transaksi_paket from t_koper_jamaah kp)
		// ambil data jamaah dari tabel transaksi paket yg belum input koper
		// $jamaah = $this->db->query("select tp.id, CONCAT_WS('-', j.nama_jamaah, j.no_ktp,jp.estimasi_keberangkatan) AS nama_jamaah 
		// from transaksi_paket tp 
		// join data_jamaah j on j.id_jamaah = tp.jamaah
		// join data_jamaah_paket jp on jp.id = tp.paket_umroh")->result();
		// $data = [];
		// foreach (@$jamaah as $row) {
		// 	$data[$row->id] = $row->nama_jamaah;
		// }

		// if (@$jamaah) {
		// 	$this->crud->field_type(
		// 		'id_transaksi_paket',
		// 		'dropdown',
		// 		$data
		// 	);
		// }


		// $this->crud->set_table('t_koper_jamaah');
		// $this->crud->set_subject('Data Koper Jamaah');
		if ($id) {

			$jamaah = $this->db->query("select tp.id, CONCAT_WS('-', j.nama_jamaah, j.no_ktp,jp.estimasi_keberangkatan) AS nama_jamaah 
		from transaksi_paket tp 
		join data_jamaah j on j.id_jamaah = tp.jamaah
		join data_jamaah_paket jp on jp.id = tp.paket_umroh where tp.id =" . $id)->result();
			$data = [];
			foreach (@$jamaah as $row) {
				$data[$row->id] = $row->nama_jamaah;
			}

			if (@$jamaah) {
				$this->crud->field_type(
					'id_transaksi_paket',
					'dropdown',
					$data
				);
			}


			$this->crud->set_table('t_koper_jamaah');
			$this->crud->set_subject('Data Koper Jamaah | ' . $jamaah[0]->nama_jamaah);
			$this->crud->set_top('Data Koper Jamaah | ' . $jamaah[0]->nama_jamaah);
		} else {
			$jamaah = $this->db->query("select tp.id, CONCAT_WS('-', j.nama_jamaah, j.no_ktp,jp.estimasi_keberangkatan) AS nama_jamaah 
		from transaksi_paket tp 
		join data_jamaah j on j.id_jamaah = tp.jamaah
		join data_jamaah_paket jp on jp.id = tp.paket_umroh")->result();
			$data = [];
			foreach (@$jamaah as $row) {
				$data[$row->id] = $row->nama_jamaah;
			}

			if (@$jamaah) {
				$this->crud->field_type(
					'id_transaksi_paket',
					'dropdown',
					$data
				);
			}


			$this->crud->set_table('t_koper_jamaah');
			$this->crud->set_subject('Data Koper Jamaah');
		}

		$this->crud->set_theme('datatables');
		$this->crud->fields('id_transaksi_paket', 'id_tipe_koper', 'id_jamaah', 'id_paket', 'created_by');
		$this->crud->required_fields('id_transaksi_paket', 'id_tipe_koper');
		$this->crud->change_field_type('id_jamaah', 'invisible');
		$this->crud->change_field_type('id_paket', 'invisible');

		$this->crud->field_type('created_by', 'hidden', $this->session->userdata('id_admin'));

		$this->crud->callback_before_insert(array($this, '_before_insert_koper_jamaah'));
		$this->crud->callback_after_insert(array($this, '_after_insert_koper_jamaah'));
		$this->crud->callback_after_update(array($this, '_after_update_koper_jamaah'));
		$this->crud->callback_after_delete(array($this, '_after_delete_koper_jamaah'));

		$this->crud->set_relation('id_tipe_koper', 'm_tipe_koper', '{nama}');
		$this->crud->unset_read()->columns('id_transaksi_paket', 'id_tipe_koper');
		$this->crud->callback_column('id_transaksi_paket', array($this, '_jamaah_dan_paket'));

		$this->crud->display_as('id_transaksi_paket', 'Jamaah');
		$this->crud->display_as('id_tipe_koper', 'Tipe Koper');
		$this->crud->display_as('created_at', 'Dibuat');
		$this->show();
	}

	public function _jamaah_dan_paket($value, $row)
	{
		$jamaah = $this->db->query("select CONCAT_WS('-', j.nama_jamaah, j.no_ktp,jp.estimasi_keberangkatan) AS nama_jamaah 
		from transaksi_paket tp 
		join data_jamaah j on j.id_jamaah = tp.jamaah
		join data_jamaah_paket jp on jp.id = tp.paket_umroh
		where tp.id =" . $row->id_transaksi_paket)->row();

		return $jamaah->nama_jamaah;
	}

	function _before_insert_koper_jamaah($post_array)
	{
		$exist = $this->db->get_where('t_koper_jamaah', ['id_transaksi_paket' => $post_array['id_transaksi_paket']])->row();
		if ($exist) {
			return false;
		}
		$data = $this->db->get_where('transaksi_paket', ['id' => $post_array['id_transaksi_paket']])->row();
		$post_array['id_jamaah'] = $data->jamaah;
		$post_array['id_paket'] = $data->paket_umroh;

		return $post_array;
	}


	function _after_insert_koper_jamaah($post_array, $primary_key)
	{
		$get_barang = $this->db->get_where('t_koper_barang', ['id_tipe_koper' => $post_array['id_tipe_koper']])->result();
		foreach (@$get_barang as $row) {
			$this->db->insert('t_barang_keluar', [
				'id_koper_jamaah' => $primary_key,
				'id_barang' => $row->id_barang,
				'jumlah' => 0,
				'created_at' => date('Y-m-d H:i:s')
			]);
		}

		return  $this->db->update('transaksi_paket', ['id_tipe_koper' => $post_array['id_tipe_koper'], 't_koper_jamaah' => $primary_key], ['id' => $post_array['id_transaksi_paket']]);
	}

	function _after_update_koper_jamaah($post_array, $primary_key)
	{
		// $get_barang = $this->db->get_where('t_koper_barang', ['id_tipe_koper' => $post_array['id_tipe_koper']])->result();
		// foreach (@$jamaah as $row) {
		// 	$data[$row->id] = $row->nama_jamaah;
		// }

		return  $this->db->update('transaksi_paket', ['id_tipe_koper' => $post_array['id_tipe_koper'], 't_koper_jamaah' => $primary_key], ['id' => $post_array['id_transaksi_paket']]);
	}


	function _after_delete_koper_jamaah($primary_key)
	{
		return  $this->db->update('transaksi_paket', ['id_tipe_koper' => null, 't_koper_jamaah' => null], ['t_koper_jamaah' => $primary_key]);
	}


	function group_level()
	{
		$this->crud->set_table('group_level')->unset_delete()->unset_edit()->unset_read()->columns('id', 'nama', 'keterangan')->order_by('id');
		$this->crud->set_subject('Data Group');
		$this->show();
	}
	function group_level_kategori()
	{
		$this->crud->set_table('group_level_menu');
		$this->crud->set_subject('Data Kategori Menu');
		$this->show();
	}
	function perhitungan()
	{
		$this->crud->set_table('perhitungan');
		$this->crud->set_subject('Hitung Paket');
		$this->crud->unset_read()->columns(
			'keberangkatan',
			'jual',
			'jumlah_pax',
			'tiket',
			'visa',
			'perlengkapan',
			'handling',
			'operasional',
			'baksis',
			'bus',
			'guide',
			'lama_guide',
			'hotel_makkah',
			'hotel_madinah',
			'jumlah_per_kamar',
			'jumlah_hari_makkah',
			'jumlah_hari_madinah',
			'jumlah_free',
			'kurs'
		);
		$this->crud->fields(
			'keberangkatan',
			'jumlah_pax',
			'tiket',
			'visa',
			'perlengkapan',
			'handling',
			'operasional',
			'baksis',
			'bus',
			'guide',
			'lama_guide',
			'hotel_makkah',
			'hotel_madinah',
			'jumlah_hari_makkah',
			'jumlah_hari_madinah',
			'hotel_makkah1',
			'hotel_madinah1',
			'jumlah_hari_makkah1',
			'jumlah_hari_madinah1',
			'jumlah_free',
			'jumlah_per_kamar',
			'laba',
			'kurs'
		)->callback_column('jual', array($this, '_callback_jual'));
		$this->crud->display_as('jumlah_hari_madinah1', 'Jumlah Hari Madinah (Opsional)');
		// $this->crud->display_as('jumlah_per_kamar','Jumlah jamaah per kamar');
		$this->crud->display_as('jumlah_hari_makkah1', 'Jumlah Hari Makkah (Opsional)');
		$this->crud->display_as('hotel_makkah1', 'Hotel Makkah (Opsional)');
		$this->crud->display_as('hotel_madinah1', 'Hotel Madinah (Opsional)');
		$this->crud->callback_column('keberangkatan', array($this, '_callback_edit'));
		$this->crud->set_rules('jumlah_pax', 'Jumlah Pax', 'numeric');
		$this->crud->set_rules('tiket', 'Tiket', 'numeric');
		$this->crud->set_rules('visa', 'Visa', 'numeric');
		$this->crud->set_rules('perlengkapan', 'Perlengkapan', 'numeric');
		$this->crud->set_rules('handling', 'Handling', 'numeric');
		$this->crud->set_rules('operasional', 'Operasional', 'numeric');
		$this->crud->set_rules('baksis', 'Baksis', 'numeric');
		$this->crud->set_rules('bus', 'Bus', 'numeric');
		$this->crud->set_rules('guide', 'Guide', 'numeric');
		$this->crud->set_rules('lama_guide', 'Guide', 'numeric');
		$this->crud->set_rules('hotel_makkah', 'Hotel Makkah', 'numeric');
		$this->crud->set_rules('hotel_madinah', 'Hotel Madinah', 'numeric');
		$this->crud->set_rules('jumlah_free', 'Hotel Madinah', 'numeric');
		$this->crud->set_rules('laba', 'Hotel Madinah', 'numeric');
		$this->crud->set_rules('kurs', 'Hotel Madinah', 'numeric');
		// $crud->field_type('jumlah_per_kamar','enum',array('1','2','3','4','5','6','7','8','9'));
		// $this->crud->callbackAddForm(function ($data) {
		// $data['jumlah_per_kamar'] = '4';

		// return $data;
		// });
		//$this->grocery_crud->callback_add_field('jumlah_per_kamar',array($this,'_jumlah_per_kamar_callback'));

		// $this->crud->callback_column('tiket',array($this,'_rupiah'));
		// $this->crud->callback_column('visa',array($this,'_rupiah'));
		// $this->crud->callback_column('perlengkapan',array($this,'_rupiah'));
		// $this->crud->callback_column('laba',array($this,'_rupiah'));
		$this->show();
	}
	public function _callback_edit($value, $row)
	{
		return "<a href='" . site_url('masterjamaahlink/perhitungan/edit/' . $row->id) . "' >$value</a>";
	}
	function _jumlah_per_kamar_callback()
	{
		return ' <input type="text" maxlength="2" value="4" name="jumlah_per_kamar">';
	}
	public function _callback_jual($value, $row)
	{
		$total = $row->visa + $row->tiket + $row->perlengkapan;
		$a = "B123=$total<br>";
		//$a='';
		if ($row->jumlah_pax < 1)
			return "<a href='" . site_url('masterjamaahlink/perhitungan/edit/' . $row->id) . "' >Jumlahpax harus diset lebih dari 0</a>";
		if ($row->jumlah_per_kamar < 1)
			return "<a href='" . site_url('masterjamaahlink/perhitungan/edit/' . $row->id) . "' >Jumlah jamaah per kamar harus lebih dari 0</a>";
		$durasi = $row->jumlah_hari_makkah + $row->jumlah_hari_madinah;
		$real = $row->handling + $row->operasional + $row->baksis / $row->jumlah_pax + $row->bus / $row->jumlah_pax + $row->guide * $row->lama_guide / $row->jumlah_pax + $row->hotel_makkah * $row->jumlah_hari_makkah / $row->jumlah_per_kamar + $row->hotel_madinah * $row->jumlah_hari_madinah / $row->jumlah_per_kamar
			+ $row->hotel_makkah1 * $row->jumlah_hari_makkah1 / $row->jumlah_per_kamar + $row->hotel_madinah1 * $row->jumlah_hari_madinah1 / $row->jumlah_per_kamar;
		$a .= "+SAR $real <br>";
		$total = $real * $row->kurs + $total;
		$free1 = $total * $row->jumlah_free / $row->jumlah_pax;
		$total = $total + $free1 + $row->laba;
		$value = number_format((float)$total);
		$t = $a . 'Total=' . $value;
		return "<a href='" . site_url('masterjamaahlink/perhitungan/edit/' . $row->id) . "' >$t</a>";
	}

	function _rupiah($value, $row)
	{
		return number_format((float)$value);
	}
	public function __pilih_group($value, $row)
	{
		return "<a href='" . site_url('masterjamaahlink/akses/' . $row->id_group . '/' . $row->id_kategori) . "' target='_blank'>$value</a>";
	}
	function get($table, $id, $id_val, $kolom)
	{
		$query = $this->db->get_where($table, array($id => $id_val));
		foreach ($query->result() as $row) {
			return $row->$kolom;
		}
		return null;
	}
	//create view pilihan_menu as select nama, group_level.id as id_group, kategori,group_level_menu.id as id_kategori from group_level_menu,group_level
	function akses($group = 0, $kategori = 0)
	{
		if ($group == 0 || $kategori == 0) {
			$this->crud->set_table('pilihan_menu1')
				//->unset_add()
				->unset_delete()
				->unset_edit()
				->unset_read()->columns('nama', 'kategori');
			$this->crud->set_subject('Pilih Group')
				->set_primary_key('id', 'pilihan_menu1')
				->callback_column('kategori', array($this, '__pilih_group'))->order_by('id_group');
			$this->show();
		} else {
			$t = $this->get('group_level', 'id', $group, 'nama');
			$g = $this->get('group_level_menu', 'id', $kategori, 'kategori');
			$this->crud->set_table('page_akses')
				->unset_read()->columns('link', 'menu', 'aktif')
				->field_type('kategori', 'hidden', $kategori)
				->field_type('group', 'hidden', $group)
				->where(array('kategori' => $kategori, 'group' => $group));
			//
			$this->crud->set_subject("Data Hak Akses $t <br> kategori $g");
			$this->crud->set_top("Data Hak Akses $t <br> kategori $g");
			$this->show();
		}
	}

	function maskapai()
	{
		$this->crud->set_table('data_maskapai');
		$this->crud->set_top('Data Maskapai');
		$this->crud->set_subject('Data Maskapai');
		$this->crud->set_theme('datatables');
		$this->crud->display_as('nama', 'Nama Maskapai');
		$this->show();
	}
	function rute()
	{
		$crud = new grocery_CRUD();
		$this->crud->set_table('data_rute');
		$this->crud->set_subject('Data Rute')->set_relation('pesawat_berangkat', 'data_maskapai', 'nama')->set_relation('pesawat_pulang', 'data_maskapai', 'nama');

		$this->show();
	}
	function agen()
	{
		$crud = new grocery_CRUD();
		$this->crud->set_table('data_jamaah_agen');
		$this->crud->set_theme('datatables');
		$this->crud
			->set_subject('Data Agen Umroh')
			->set_top('Data Agen Umroh')
			->unset_read()->columns('id', 'nama', 'alamat', 'telepon', 'email', 'hp', 'keterangan', 'leader')
			->unset_delete()
			->where('data_jamaah_agen.pangkat', 0)
			->display_as('id', 'Nomor Agen')
			->display_as('telepon', 'No Telepon')
			->display_as('hp', 'No HP')
			->display_as('nama', 'Nama Agen');
		$this->crud->set_relation('leader', 'data_jamaah_agen', '{nama}-{id}', array('pangkat' => '1'))->field_type('pangkat', 'hidden', 0); //0 agen, 1 leader

		

		$this->show();
	}
	public function _urle($value, $row)
	{
		return "<a href='" . site_url('usere/agen_leader/' . $row->id) . "'>$value</a>";
	}
	function agen_()
	{
		$fk = $this->session->userdata('fk');
		$crud = new grocery_CRUD();
		$this->crud->set_table('data_jamaah_agen');
		$this->crud->set_theme('datatables');
		$this->crud->set_subject('Data Agen Umroh')->display_as('id', 'Nomor Agen')->unset_read()->columns('id', 'nama', 'alamat', 'telepon', 'email', 'hp', 'keterangan')->unset_delete()->where('data_jamaah_agen.pangkat', 0)->where('leader', $fk);
		$this->crud->field_type('pangkat', 'hidden', 0)->callback_column('nama', array($this, '_urle'));; //0 agen, 1 leader
		$this->show();
	}
	function leader()
	{
		$crud = new grocery_CRUD();
		$this->crud->set_table('data_jamaah_agen')->unset_read()->columns('id', 'nama', 'alamat', 'telepon', 'email', 'hp', 'keterangan')->fields('nama', 'alamat', 'telepon', 'email', 'hp', 'keterangan', 'pangkat');
		$this->crud->set_theme('datatables');
		$this->crud->set_subject('Data Leader Umroh')
		->set_top('Data Leader Umroh')
		->where('pangkat', 1)->field_type('pangkat', 'hidden', 1)->unset_delete();

		$this->crud->display_as('id', 'No Leader');
		$this->crud->display_as('nama', 'Nama Leader');
		$this->crud->display_as('telepon', 'No Telepon');
		$this->crud->display_as('hp', 'No Hp');

		$this->show();
	}

	public function link_share_jamaah()
    {
        // 1. Setup Table
		//$crud = new grocery_CRUD();
		//$crud->unset_jquery();
		$this->crud->set_theme('twitter-bootstrap');
		$state = $this->crud->getState();
		if ($state == 'list' || $state == 'unknown') {
			redirect(site_url('masterjamaahlink/link_share_jamaah/add'));
		}
        $this->crud->set_table('data_jamaah');
        $this->crud->set_subject('Generate Data Dummy Jamaah');

        // 2. Setup Relasi Agen (Sesuaikan nama tabel agen dan field nama agen Anda)
        // Contoh: tabel 'admin', field 'nama_lengkap' atau tabel 'master_agen'
        //$this->crud->set_relation('agen', 'admin', 'nama_jamaah', array('level' => 'agen')); 
		//$crud->set_relation('agen', 'data_jamaah_agen', 'nama'); //old
		$this->crud->set_relation('agen', 'data_jamaah', '{nama_jamaah}',array('is_agen' => '1'));//new
        // 3. Tentukan Field yang muncul di Form Tambah
        // Kita "meminjam" field random_uuid untuk dijadikan inputan "Jumlah Jamaah"
        $this->crud->add_fields('agen', 'random_uuid');
        
        // 4. Ubah Label dan Tipe Field
        $this->crud->display_as('random_uuid', 'Jumlah Jamaah (Qty)');
        $this->crud->display_as('agen', 'Nama Agen');
        
        // Ubah field random_uuid jadi angka (integer) agar user bisa input jumlah
        $this->crud->field_type('random_uuid', 'integer');
        $this->crud->required_fields('agen', 'random_uuid');

        // 5. Callback Insert (Jantung Logikanya)
        // Saat tombol simpan ditekan, fungsi '_generate_bulk_data' akan dijalankan
        $this->crud->callback_insert(array($this, '_generate_bulk_data'));

        // 6. Cleanup Tampilan (Opsional)
        // Kita sembunyikan tombol edit/delete karena ini halaman khusus generate
        $this->crud->unset_edit();
        $this->crud->unset_delete();
        $this->crud->unset_read();
        
        // Hanya tampilkan kolom hasil generate
        $this->crud->columns('nama_jamaah', 'agen', 'no_ktp', 'random_uuid');
        $this->crud->order_by('id_jamaah', 'desc');

        // 7. Render
		// var_dump("aaaaa");
		// die();
        $output = $this->crud->render();
        $this->load->view('ci_simplicity/admin', $output); // Sesuaikan dengan file view admin Anda
    }

    // --- FUNGSI CALLBACK INSERT ---
    public function _generate_bulk_data($post_array)
    {
        // 1. Ambil data dari Form
		$this->load->library('session');
        $this->load->database();
		$id_paket = $this->uri->segment(3);
		//
        $id_agen = $post_array['agen'];
        $jumlah_loop = (int) $post_array['random_uuid']; // Field ini kita pakai sebagai Qty
		$master_paket = $this->db->get_where('data_jamaah_paket', array('id' => $id_paket))->row();
   		$harga_paket = isset($master_paket->harga) ? $master_paket->harga : 0;
		$data_agen = $this->db->get_where('data_jamaah', array('id_jamaah' => $id_agen))->row();
		$nama_agen_label = isset($data_agen->nama_jamaah) ? $data_agen->nama_jamaah : 'Agen Tidak Diketahui';
        // 2. Looping Insert
		$this->db->trans_start();
        for ($i = 0; $i < $jumlah_loop; $i++) {
            
            // Generate UUID Unik
            $uuid = $this->_get_uuid(); 

            $data_insert_jamaah = array(
                'agen'        => $id_agen,
                'no_ktp'      => '00000',
                'title'       => 'MR',
                'tgl_lahir'   => '1945-09-17', // Format Database YYYY-MM-DD
                'nama_jamaah' => 'nama_dummy',
                'no_tlp'      => '00000',
                'hp_jamaah'   => '00000',
                'random_uuid' => $uuid
            );

			$this->db->insert('data_jamaah', $data_insert_jamaah);
			$id_jamaah_baru = $this->db->insert_id();

			$data_transaksi_paket = array(
				'jamaah'      => $id_jamaah_baru,
				'paket_umroh' => $id_paket, // ID 2580 masuk ke sini
				'agen'        => $id_agen,
				'harga'       => $harga_paket,
				'harga_normal'=> $harga_paket,
				'kekurangan'  => $harga_paket,
				'qty'         => 1,
			);
			$this->db->insert('transaksi_paket', $data_transaksi_paket);
		}
        // 3. Return true agar Grocery CRUD tahu proses selesai
        // Kita return true tanpa melakukan insert bawaan CRUD (karena sudah di-loop di atas)
		$this->db->trans_complete();
		if ($this->db->trans_status() === FALSE) {
            $this->session->set_flashdata('message_error', 'Sistem Error: Gagal meng-generate data.');
            return false;
        } else {
            $this->session->set_flashdata('message_success', "Sukses! Berhasil membuat <b>$qty_input</b> link untuk agen <b>$nama_agen_label</b>.");
            return true;
        }
        return true; 
    }

    // --- HELPER FUNCTION UUID ---
    private function _get_uuid()
    {
        // Fungsi generate UUID v4 standar
        return sprintf(
            '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            mt_rand(0, 0xffff), mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0x0fff) | 0x4000,
            mt_rand(0, 0x3fff) | 0x8000,
            mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
        );
    }


	function jamaah_dokumen()
	{
        $this->crud->set_table('data_jamaah')->unique_fields(array('no_ktp'));
       // $this->crud->set_subject('Jamaah')->unset_add();

        // Set the fields for the form
        $this->crud->fields( 'nama_jamaah','nama_tambahan',  'tgl_lahir', 'tempat_lahir', 'estimasi_berangkat','imigrasi',
                      'alamat_jamaah','no_ktp');
        $this->crud->columns('nama_jamaah','nama_tambahan','estimasi_berangkat','imigrasi', 'tgl_lahir', 'tempat_lahir', 
                      'alamat_jamaah', 'title','no_ktp',  'age', 'place', 'passport', 'issued', 'expired', 
                      'office');
        $this->crud->unset_texteditor('alamat_jamaah')->set_relation('imigrasi','ref_imigrasi','nama_imigrasi');

        // Add custom column for download link
        $this->crud->add_action('Download Rekom Paspor Namiroh', '', 'masterjamaahlink/download_rekom_paspor', 'ui-icon-arrowthick-s');
        $this->crud->add_action('Download Rekom Paspor Rihlah Saidah', '', 'masterjamaahlink/rekom_paspor_rihlah', 'ui-icon-arrowthick-s');
        $this->crud->add_action('Download Rekom Paspor Antrav Mustika', '', 'masterjamaahlink/rekom_paspor_antrav', 'ui-icon-arrowthick-s');
        $this->crud->add_action('Download Rekom Paspor Tajalli', '', 'masterjamaahlink/rekom_paspor_tajalli', 'ui-icon-arrowthick-s');
        
        
		$this->show();
	}
	function rekom_paspor_rihlah($primary_key){
	 // Ambil data jamaah dari tabel data_jamaah
		$jamaah = $this->db->query('select * from data_jamaah where id_jamaah = '.$primary_key)->row_array();
		$nomor_urut = $this->db->select_max('id')->get('surat_rekom_paspor')->row()->id + 1; // Menghitung id surat dan menambahkan 1
		$bulan_romawi = ['I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII'][date('n') - 1];
		$tahun = date('Y');
		$nomor_surat = "$nomor_urut/RIHLAH/SR/$bulan_romawi/$tahun";
		$this->rekom_($primary_key,2,'rekom_paspor_rihlah',$nomor_surat);		
		
		
	}
	function rekom_paspor_antrav($primary_key){
	 // Ambil data jamaah dari tabel data_jamaah
		$jamaah = $this->db->query('select * from data_jamaah where id_jamaah = '.$primary_key)->row_array();
		$nomor_urut = $this->db->select_max('id')->get('surat_rekom_paspor')->row()->id + 1; // Menghitung id surat dan menambahkan 1
		$bulan_romawi = ['I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII'][date('n') - 1];
		$tahun = date('Y');
		$nomor_surat = "$nomor_urut/ANTRAV/SR/$bulan_romawi/$tahun";
		$this->rekom_($primary_key,3,'rekom_paspor_antrav',$nomor_surat);		
		
		
		
	}
	function rekom_paspor_tajalli($primary_key){
	 // Ambil data jamaah dari tabel data_jamaah
		$jamaah = $this->db->query('select * from data_jamaah where id_jamaah = '.$primary_key)->row_array();
		//var_dump(jamaah);
//$output  = $this->db->last_query();//
		// Get the contents of the output buffer and clean it
	   // $output = ob_get_clean();
		
		// Ambil nomor urut dari tabel surat_rekom
		$nomor_urut = $this->db->select_max('id')->get('surat_rekom_paspor')->row()->id + 1; // Menghitung id surat dan menambahkan 1
		$bulan_romawi = ['I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII'][date('n') - 1];
		$tahun = date('Y');
		$nomor_surat = "$nomor_urut/TAJALLI/SR/$bulan_romawi/$tahun";
		$this->rekom_($primary_key,4,'rekom_paspor_tajalli',$nomor_surat);
	}
	function rekom_($primary_key,$travel,$template,$nomorsurat=''){
		 // Ambil data jamaah dari tabel data_jamaah
            $jamaah = $this->db->query('select * from data_jamaah where id_jamaah = '.$primary_key)->row_array();
            //var_dump(jamaah);
    //$output  = $this->db->last_query();//
            // Get the contents of the output buffer and clean it
           // $output = ob_get_clean();
            
            // Ambil nomor urut dari tabel surat_rekom
            $nomor_urut = $this->db->select_max('id')->get('surat_rekom_paspor')->row()->id + 1; // Menghitung id surat dan menambahkan 1
            $bulan_romawi = ['I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII'][date('n') - 1];
            $tahun = date('Y');
			if($nomorsurat=='')
				$surat_nomor = "$nomor_urut/AN-NAMIROH/SR/$bulan_romawi/$tahun";
			else
				$surat_nomor = $nomorsurat;
            
       // Siapkan data untuk mengganti placeholder
       
       $tanggal =  $jamaah['tgl_lahir'];
    
        // Membuat objek DateTime dari string tanggal
        $date = new DateTime($tanggal);
        // Mendapatkan hari, bulan, dan tahun
        $hari = $date->format('d');
        $bulan = $date->format('n'); // Bulan dalam angka
        $tahun = $date->format('Y');
        
        // Array bulan dalam bahasa Indonesia
        $bulanIndo = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember'
        ];
        
        // Mengambil nama bulan
        $namaBulan = $bulanIndo[$bulan];
        
        // Menggabungkan menjadi format yang diinginkan
        
        $outpux = "$hari $namaBulan $tahun";
        
        
        $tgl_dibuat = date_create(date('Y-m-d'));
        $hari = date_format($tgl_dibuat, 'd');
        $bulan = date_format($tgl_dibuat, 'n'); // Bulan dalam angka
        $tahun = date_format($tgl_dibuat, 'Y');
        
        $namaBulan = $bulanIndo[$bulan];
        $tanggal_dibuat =  "$hari $namaBulan $tahun";
        
        
        // Mengubah format tanggal
        $kode_imigrasi =  $jamaah['imigrasi'];
        //$imigrasi = $this->db->select('nama_imigrasi')->get('ref_imigrasi')->where('id',$kode_imigrasi)->row()->nama_imigrasi;
        // $imigrasi = $this->db->select('nama_imigrasi')
                       // ->get_where('ref_imigrasi', array('id' => $kode_imigrasi))
                       // ->row()
                       // ->nama_imigrasi;
					   
		$imigrasi_result = $this->db->select('nama_imigrasi')
                            ->get_where('ref_imigrasi', array('id' => $kode_imigrasi))
                            ->row();

		if ($imigrasi_result && isset($imigrasi_result->nama_imigrasi)) {
			$imigrasi = $imigrasi_result->nama_imigrasi;
		} else {
			// Handle the case where the data is not found
			$imigrasi = 'Kantor Imigrasi'; // or set to null, or handle the error as needed
		}

        $data = [
            '5191/AN-NAMIROH/SR/IX/2025' => $surat_nomor,
            '33nama33' => $jamaah['nama_jamaah']." ".$jamaah['nama_tambahan'],
            '33tempat_lahir33' => $jamaah['tempat_lahir'],
            '33tanggallahir33' =>$outpux,
            '33alamat33' => $jamaah['alamat_jamaah'],
            '{{tanggal_dibuat}}' => date('Y-m-d'),
            '33imigrasi33'=>$imigrasi,
            '33tanggal33'=>$tanggal_dibuat
        ];
        
            $template = $this->load->view("dokumen/$template", '', true);
        foreach ($data as $placeholder => $value) {
            $template = str_replace($placeholder, $value, $template);
        }
        // Simpan template sebagai file .docx
        $file_name = "rekomendasi_$nomor_urut.xml";
        file_put_contents($file_name, $template);

        // Simpan informasi surat ke tabel surat_rekom
        $data = [
            'nomor_urut' => $surat_nomor,
            'user_id' => $this->session->userdata('id_admin'), // Menggunakan id_jamaah sebagai user_id,
            'jamaah_id' => $primary_key,
            'nama_jamaah' => $jamaah['nama_jamaah'].' '.$jamaah['nama_tambahan'],
            'tempat_lahir' => $jamaah['tempat_lahir'],
            'tanggal_lahir' =>$outpux,
            'alamat' => $jamaah['alamat_jamaah'],
            'tanggal_dibuat' => date('Y-m-d'),
            'file_name' => $file_name,
            'imigrasi' => $kode_imigrasi,
            'estimasi_berangkat'=> $jamaah['estimasi_berangkat']
        ];
        $this->db->insert('surat_rekom_paspor', $data);

        // Unduh file
        header("Content-Disposition: attachment; filename=$file_name");
        header("Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document");
        readfile($file_name);
        unlink($file_name); // Hapus file setelah diunduh
        exit();
	}
    function download_rekom_paspor($primary_key,$travel = 1){
       /*
	   "id"	"nama_travel"	"keterangan"	"tanggal_berdiri"
"1"	"An Namiroh"	""	"0000-00-00"
"2"	"Rihlah"	""	"0000-00-00"
"3"	"Antrav"	""	"0000-00-00"
"4"	"Tajalli"	""	"0000-00-00"
"5"	"Belum terdata"	""	"0000-00-00"
	   */ 
    	   // Ambil data jamaah dari tabel data_jamaah
            $jamaah = $this->db->query('select * from data_jamaah where id_jamaah = '.$primary_key)->row_array();
            //var_dump(jamaah);
    //$output  = $this->db->last_query();//
            // Get the contents of the output buffer and clean it
           // $output = ob_get_clean();
            
            // Ambil nomor urut dari tabel surat_rekom
            $nomor_urut = $this->db->select_max('id')->get('surat_rekom_paspor')->row()->id + 1; // Menghitung id surat dan menambahkan 1
            $bulan_romawi = ['I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII'][date('n') - 1];
            $tahun = date('Y');
            $surat_nomor = "$nomor_urut/AN-NAMIROH/SR/$bulan_romawi/$tahun";
            
       // Siapkan data untuk mengganti placeholder
       
       $tanggal =  $jamaah['tgl_lahir'];
    
        // Membuat objek DateTime dari string tanggal
        $date = new DateTime($tanggal);
        // Mendapatkan hari, bulan, dan tahun
        $hari = $date->format('d');
        $bulan = $date->format('n'); // Bulan dalam angka
        $tahun = $date->format('Y');
        
        // Array bulan dalam bahasa Indonesia
        $bulanIndo = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember'
        ];
        
        // Mengambil nama bulan
        $namaBulan = $bulanIndo[$bulan];
        
        // Menggabungkan menjadi format yang diinginkan
        
        $outpux = "$hari $namaBulan $tahun";
        
        
        $tgl_dibuat = date_create(date('Y-m-d'));
        $hari = date_format($tgl_dibuat, 'd');
        $bulan = date_format($tgl_dibuat, 'n'); // Bulan dalam angka
        $tahun = date_format($tgl_dibuat, 'Y');
        
        $namaBulan = $bulanIndo[$bulan];
        $tanggal_dibuat =  "$hari $namaBulan $tahun";
        
        
        // Mengubah format tanggal
        $kode_imigrasi =  $jamaah['imigrasi'];
        //$imigrasi = $this->db->select('nama_imigrasi')->get('ref_imigrasi')->where('id',$kode_imigrasi)->row()->nama_imigrasi;
        $imigrasi = $this->db->select('nama_imigrasi')
                       ->get_where('ref_imigrasi', array('id' => $kode_imigrasi))
                       ->row()
                       ->nama_imigrasi;
        $data = [
            '5191/AN-NAMIROH/SR/IX/2025' => $surat_nomor,
            '33nama33' => $jamaah['nama_jamaah']." ".$jamaah['nama_tambahan'],
            '33tempat_lahir33' => $jamaah['tempat_lahir'],
            '33tanggallahir33' =>$outpux,
            '33alamat33' => $jamaah['alamat_jamaah'],
            '{{tanggal_dibuat}}' => date('Y-m-d'),
            '33imigrasi33'=>$imigrasi,
            '33tanggal33'=>$tanggal_dibuat
        ];
        
            $template = $this->load->view('dokumen/rekom_paspor', '', true);
        foreach ($data as $placeholder => $value) {
            $template = str_replace($placeholder, $value, $template);
        }
        // Simpan template sebagai file .docx
        $file_name = "rekomendasi_$nomor_urut.xml";
        file_put_contents($file_name, $template);

        // Simpan informasi surat ke tabel surat_rekom
        $data = [
            'nomor_urut' => $surat_nomor,
            'user_id' => $this->session->userdata('id_admin'), // Menggunakan id_jamaah sebagai user_id,
            'jamaah_id' => $primary_key,
            'nama_jamaah' => $jamaah['nama_jamaah'].' '.$jamaah['nama_tambahan'],
            'tempat_lahir' => $jamaah['tempat_lahir'],
            'tanggal_lahir' =>$outpux,
            'alamat' => $jamaah['alamat_jamaah'],
            'tanggal_dibuat' => date('Y-m-d'),
            'file_name' => $file_name,
            'imigrasi' => $kode_imigrasi,
            'estimasi_berangkat'=> $jamaah['estimasi_berangkat']
        ];
        $this->db->insert('surat_rekom_paspor', $data);

        // Unduh file
        header("Content-Disposition: attachment; filename=$file_name");
        header("Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document");
        readfile($file_name);
        unlink($file_name); // Hapus file setelah diunduh
        exit();
	}
	
	   public function download_saved_rekom_paspor($id) {
        // Ambil informasi surat dari tabel surat_rekom
        $surat = $this->db->where('id', $id)->get('surat_rekom')->row_array();
        
        if ($surat) {
            $file_name = $surat['file_name'];

            // Periksa apakah file ada
            if (file_exists($file_name)) {
                // Simpan ID admin yang mengunduh
                $downloaded_by = $this->session->userdata('id_admin');
                $this->db->where('id', $id);
                $this->db->update('surat_rekom', ['downloaded_by' => $downloaded_by]);

                // Unduh file
                header("Content-Disposition: attachment; filename=$file_name");
                header("Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document");
                readfile($file_name);
            } else {
                echo "File tidak ditemukan.";
            }
        } else {
            echo "Data surat tidak ditemukan.";
        }
    }
	function jamaah_p()
	{


		$this->crud->set_table('data_jamaah_paket');
		$this->crud->set_subject('Pilih Paket Umroh')->unset_edit()->unset_delete()->unset_read()->columns('estimasi_keberangkatan', 'Program');
		$this->crud->set_theme('datatables')->unset_read();
		$crud->unique_fields(array('no_ktp'));
		//$this->crud->set_relation('hotel','data_hotel','nama');
		$this->crud->set_relation('Penerbangan', 'data_maskapai', 'nama');

		$this->show();
	}
	/*
		 var $paket = array();
	 var $transaksi_paket = array();
	 */

	 public function jamaahUUID($action = null, $uuid = null)
	{
		// ❌ JANGAN ADA GROCERY CRUD DI SINI

		if ($action !== 'edit' || empty($uuid)) {
			show_404();
		}

		// Cari jamaah berdasarkan UUID
		$jamaah = $this->db
			->get_where('data_jamaah', ['random_uuid' => $uuid])
			->row();

		if (!$jamaah) {
			show_404();
		}

		// Jika form disubmit
		if ($this->input->post()) {
			$data = [
				'nama_jamaah' => $this->input->post('nama_jamaah', true),
				'no_tlp'      => $this->input->post('no_tlp', true),
				'alamat_jamaah' => $this->input->post('alamat_jamaah', true),
			];

			$this->db->where('id_jamaah', $jamaah->id_jamaah);
			$this->db->update('data_jamaah', $data);

			redirect(current_url());
		}

		// Tampilkan view PUBLIC
		$this->load->view('ci_simplicity/jamaah_edit', [
			'jamaah' => $jamaah
		]);
		
	}

	public function jamaahUUIDOld($paket = 0)
    {
        // ------------------------------------------------------------------------
        // [MODIFIKASI 1] INTERCEPTOR: Cek jika URL Edit menggunakan UUID
        // ------------------------------------------------------------------------
        $state = $this->crud->getState();
        $segment_id = $this->uri->segment(4); // Segmen ke-4 biasanya ID/UUID

        if ($state == 'edit' && !empty($segment_id) && !is_numeric($segment_id)) {
            // Jika segmen BUKAN angka (berarti UUID), cari ID aslinya
            $cek_uuid = $this->db->get_where('data_jamaah', ['random_uuid' => $segment_id])->row();
            
            if ($cek_uuid) {
                // Redirect ke URL standar Grocery CRUD (pakai ID angka)
                redirect(site_url('masterjamaahlink/jamaahUUID/edit/' . $cek_uuid->id_jamaah));
            } else {
                show_404(); // UUID tidak ditemukan
            }
        }
		$this->crud->unset_list();
        $this->crud->unset_back_to_list();

		$cek_id_jamaah = $this->db->get_where('data_jamaah', ['id_jamaah' => $segment_id])->row();
		if($cek_id_jamaah == NULL){
			 show_404();
			return;
		}
        // ------------------------------------------------------------------------

        $level = $this->session->userdata('level');
        if ($level == 4)
            redirect('masterjamaahlink/jamaah_p');

        // revisi 26 maret 2024, no ktp dan passport unik
        $this->crud->set_table('data_jamaah')->unique_fields(array('no_ktp', 'passport'));
        $nama = $this->input->post('s', true);

        if (isset($nama) && $nama != null)
            $this->crud->like('nama_jamaah', $nama);
            
        $this->crud->set_subject('Data Jamaah Umroh')
            ->set_top('Data Jamaah Umroh')->set_theme('datatables')->unset_delete();
            
        $this->crud->required_fields('city');
        $this->crud->unset_texteditor('keterangan', 'full_text');
        $this->crud->unset_texteditor('alamat_jamaah', 'full_text');
        
        $this->crud->fields('title', 
		'nama_jamaah', 'location_prov','location_city','location_disct','location_village',
		'tgl_lahir', 'tempat_lahir',
		'alamat_jamaah', 'imigrasi',
		'keterangan', 
		'no_ktp', 'no_tlp', 'agen', 'hp_jamaah', 
		'passport', 'issued', 'expired', 'office', 
		'nama_di_vaksin', 'jenis_vaksin', 'tgl_vaksin_1',
		'jenis_vaksin_2', 'tgl_vaksin_2', 'jenis_vaksin_3',
		'tgl_vaksin_3', 'jenis_vaksin_4', 'tgl_vaksin_4',
		'foto', 'kartukeluarga', 'ktp', 'surat_nikah','is_agen');

		$this->crud->unset_texteditor('alamat_jamaah')->set_relation('imigrasi','ref_imigrasi','nama_imigrasi');
		$this->crud->display_as('tempat_lahir', 'Tampat lahir');
		$this->crud->display_as('imigrasi', 'imigrasi');

        $this->crud->callback_edit_field('agen', array($this, '_callback_disable_agen'));

		$this->crud->callback_after_update(array($this, '_reset_uuid_on_save'));//untuk reset uuid

        $this->crud->unset_read()->columns('nama_jamaah', 'paket', 'tgl_lahir', 'no_ktp', 'agen', 'hp_jamaah', 'alamat_jamaah', 'user_id','action_link');
        
        $this->crud->set_rules('no_ktp', 'Nomor KTP', 'trim|required');
        $this->crud->set_rules('no_tlp', 'No Telepon', 'trim|required');
        $this->crud->set_rules('nama_jamaah', 'Nama Jamaah', 'max_length[100]');

        // [MODIFIKASI 2] Callback Kolom Link
        $this->crud->display_as('action_link', 'Link Form');
        $this->crud->callback_column('action_link', array($this, '_callback_tombol_copy'));

        // Display As Fields...
        $this->crud->display_as('title', 'Sebutan');
        $this->crud->display_as('nama_jamaah', 'Nama Lengkap');
        $this->crud->display_as('tgl_lahir', 'Tanggal Lahir');
        $this->crud->display_as('alamat_jamaah', 'Alamat Jamaah');
        $this->crud->display_as('no_ktp', 'No KTP');
        $this->crud->display_as('no_tlp', 'No Telepon');
        $this->crud->display_as('hp_jamaah', 'No HP');
        $this->crud->display_as('passport', 'No Passport');
        $this->crud->display_as('issued', 'Tanggal Pengeluaran');
        $this->crud->display_as('expired', 'Tanggal Habis Berlaku');
        $this->crud->display_as('foto', 'Foto Diri');
        $this->crud->display_as('kartukeluarga', 'Kartu Keluarga');
        $this->crud->display_as('ktp', 'KTP');
        $this->crud->display_as('surat_nikah', 'Surat Nikah');
        $this->crud->display_as('nama_di_vaksin', 'Nama di Sertifikat Vaksin');
        $this->crud->display_as('jenis_vaksin', 'Jenis Vaksin 1');
        $this->crud->display_as('jenis_vaksin_2', 'Jenis Vaksin 2');
        $this->crud->display_as('jenis_vaksin_3', 'Jenis Vaksin 3');
        $this->crud->display_as('jenis_vaksin_4', 'Jenis Vaksin 4');
        $this->crud->display_as('tgl_vaksin_1', 'Tgl Vaksin 1');
        $this->crud->display_as('tgl_vaksin_2', 'Tgl Vaksin 2');
        $this->crud->display_as('tgl_vaksin_3', 'Tgl Vaksin 3');
        $this->crud->display_as('tgl_vaksin_4', 'Tgl Vaksin 4');
        $this->crud->display_as('user_id', 'Admin');

		$this->crud->display_as('location_prov', 'Provinsi');
        $this->crud->display_as('location_city', 'Kota/Kabupaten');
        $this->crud->display_as('location_disct', 'Kecamatan');
        $this->crud->display_as('location_village', 'Kelurahan/Desa');


		$this->crud->callback_field('location_prov', array($this, '_cb_provinsi'));
        $this->crud->callback_field('location_city', array($this, '_cb_kota'));
        $this->crud->callback_field('location_disct', array($this, '_cb_kecamatan'));
        $this->crud->callback_field('location_village', array($this, '_cb_kelurahan'));

        $this->crud->set_relation('agen', 'data_jamaah', '{nama_jamaah}', array('is_agen' => '1'))
            ->callback_column('paket', array($this, '_callback_paket'))
            ->callback_column('user_id', array($this, 'user_id_callbackUUID')); // Pastikan fungsi ini ada di controller Anda

        // Query Paket...
        $query = $this->db->query("SELECT id,CONCAT( UPPER(estimasi_keberangkatan),'-',Program,'-',CAST(FORMAT(harga,2,'de_DE') AS CHAR CHARACTER SET utf8)) AS detail FROM data_jamaah_paket");
        foreach ($query->result() as $row) {
            $this->paket[$row->id] = $row->detail;
        }

        // Query Transaksi...
        $query = $this->db->get("transaksi_paket");
        foreach ($query->result_array() as $row) {
             if (isset($this->transaksi_paket[$row['jamaah']])) 
                $this->transaksi_paket[$row['jamaah']] .= isset($this->paket[$row['paket_umroh']]) ? "<a href='" . site_url('transaksi_op/pembayaran/' . $row['paket_umroh'] . '/' . $row['jamaah']) . "'data-toggle='tooltip' title='Klik untuk pembayaran' target='_blank'>" . $this->paket[$row['paket_umroh']] . "</a>" .(count($row) > 0 ? '<br/><br/>' : '') : '';
            else
                $this->transaksi_paket[$row['jamaah']] = isset($this->paket[$row['paket_umroh']]) ? "<a href='" . site_url('transaksi_op/pembayaran/' . $row['paket_umroh'] . '/' . $row['jamaah']) . "'data-toggle='tooltip' title='Klik untuk pembayaran' target='_blank'>" . $this->paket[$row['paket_umroh']] . "</a>" .(count($row) > 0 ? '<br/><br/>' : '') : '';
        }

        $this->crud->set_field_upload('foto', 'assets/uploads/foto');
        $this->crud->set_field_upload('kartukeluarga', 'assets/uploads/kk');
        $this->crud->set_field_upload('ktp', 'assets/uploads/ktp');
        $this->crud->set_field_upload('surat_nikah', 'assets/uploads/nikah');

        $this->crud->callback_before_upload(array($this,'_callback_before_upload'));

        // [MODIFIKASI 3] Panggil callback Insert
        $this->crud->callback_after_insert(array($this, 'jamaah_cb_uuid'));
        // $this->crud->callback_after_update(array($this, 'jamaah_callback_after_insert_update')); // Opsional jika update juga mau dihitung umur

        $query = $this->db->get('jenis_transaksi');
        foreach ($query->result() as $row) {
            $this->t[$row->id] = $row->nama_transaksi;
        }

        $this->crud->callback_insert(array($this,'_insert_user_id'));
        
        // $this->crud->callback_edit_field('no_ktp', function ($value, $primary_key) {
        //     return '
        //     <div class="form-input-box control-group" id="no_ktp_input_box">
        //         <input id="field-no_ktp" style="pointer-events:none;" class="form-control" value="'.$value.'" name="no_ktp" type="text">
        //     </div>
        //     ';
        // });

        $this->crud->order_by('id_jamaah', 'desc');

		$assets_select2 = '
          <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
          <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@1.5.2/dist/select2-bootstrap4.min.css">
        ';


		$js_script = '
            <script>
			// function copyToClipboard(text) {
			// 	var dummy = document.createElement("textarea");
			// 	document.body.appendChild(dummy);
			// 	dummy.value = text;
			// 	dummy.select();
			// 	document.execCommand("copy");
			// 	document.body.removeChild(dummy);
			// 	alert("Link berhasil disalin!");
			// }

            window.addEventListener("load", function() {
                // Inisialisasi Select2 pada field lokasi

				if (typeof jQuery === "undefined") {
                    console.error("Error: jQuery belum dimuat oleh sistem!");
                    return;
                }
                var $ = jQuery;
				var script = document.createElement("script");
				script.src = "https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js";
				script.onload = function() {
                    
                    // 1. Aktifkan Select2
                    $("#field-id_provinsi, #field-id_kota, #field-id_kecamatan, #field-id_kelurahan").select2({
                        width: "20%",
                        placeholder: "Pilih...",
                        theme: "bootstrap4"
                    });

                    // 2. Logic AJAX (Dimasukkan ke sini agar aman)
                    const baseUrlLocation = "'.base_url('location/').'"; 

                    // --- Event Listeners ---
                    
                    // Ganti Provinsi
                    $("#field-id_provinsi").change(function(){
                        let id_prov = $(this).val();
                        $("#field-id_kota").empty().append("<option value=\'\'>Loading...</option>").trigger("change");
                        $("#field-id_kecamatan").empty().trigger("change");
                        $("#field-id_kelurahan").empty().trigger("change");

                        if(id_prov){
                            $.ajax({
                                url: baseUrlLocation + "api_cities",
                                type: "POST",
                                data: {id_prov: id_prov},
                                dataType: "json",
                                success: function(data){
									console.log("sukses",data)
                                    let html = "<option value=\'\'>Pilih Kota</option>";
                                    $.each(data, function(key, value){
                                        html += "<option value=\'"+value.id+"\'>"+value.name+"</option>";
                                    });
                                    $("#field-id_kota").html(html);
                                },
								errors: function(err){
									console.log("error",err);
								}
                            });
                        }
                    });

                    // Ganti Kota
                    $("#field-id_kota").change(function(){
                        let id_city = $(this).val();
                        $("#field-id_kecamatan").empty().append("<option value=\'\'>Loading...</option>").trigger("change");
                        $("#field-id_kelurahan").empty().trigger("change");

                        if(id_city){
                            $.ajax({
                                url: baseUrlLocation + "api_districts",
                                type: "POST",
                                data: {id_city: id_city},
                                dataType: "json",
                                success: function(data){
									console.log("sukses kec",data);
                                    let html = "<option value=\'\'>Pilih Kecamatan</option>";
                                    $.each(data, function(key, value){
                                        html += "<option value=\'"+value.id+"\'>"+value.name+"</option>";
                                    });
                                    $("#field-id_kecamatan").html(html);
                                },
								errors: function(errors){
									console.log("sukses kec",errors);
								}
                            });
                        }
                    });

                    // Ganti Kecamatan
                    $("#field-id_kecamatan").change(function(){
                        let id_dist = $(this).val();
                        $("#field-id_kelurahan").empty().append("<option value=\'\'>Loading...</option>").trigger("change");

                        if(id_dist){
                            $.ajax({
                                url: baseUrlLocation + "api_villages",
                                type: "POST",
                                data: {id_district: id_dist},
                                dataType: "json",
                                success: function(data){
									console.log("sukses keluarhan",data)
                                    let html = "<option value=\'\'>Pilih Kelurahan</option>";
                                    $.each(data, function(key, value){
                                        html += "<option value=\'"+value.id+"\'>"+value.name+"</option>";
                                    });
                                    $("#field-id_kelurahan").html(html);
                                },
								errors: function(err){
									console.log("errors",err);
								}
                            });
                        }
                    });

                }; // End script.onload

                // Tempelkan script ke dalam dokumen
                document.head.appendChild(script);
				
            });

            function copyToClipboard(text) {
                var dummy = document.createElement("textarea");
                document.body.appendChild(dummy);
                dummy.value = text;
                dummy.select();
                document.execCommand("copy");
                document.body.removeChild(dummy);
                alert("Link berhasil disalin!");
            }
            </script>
        ';

        $this->output->append_output($assets_select2 . $js_script);
        $this->show();
    }


	public function _cb_provinsi($value = '', $primary_key = null)
    {
        $data = $this->Location_model->get_provinces();
        // ID harus sesuai dengan selector di JS
        $html = '<select id="field-id_provinsi" name="location_prov" class="form-control select2">';
        $html .= '<option value="">Pilih Provinsi</option>';
        foreach($data as $row){
            $selected = ($row->id == $value) ? 'selected' : '';
            $html .= '<option value="'.$row->id.'" '.$selected.'>'.$row->name.'</option>';
        }
        $html .= '</select>';
        return $html;
    }

  	 public function _cb_kota($value = '', $primary_key = null)
    {
        $html = '<select id="field-id_kota" name="location_city" class="form-control select2">';
        $html .= '<option value="">Pilih Kota</option>';
        
        if(!empty($value) && $primary_key){
            $jamaah = $this->db->get_where('data_jamaah', ['id_jamaah' => $primary_key])->row();
            // PERBAIKAN LOGIC: Ambil list kota berdasarkan ID PROVINSI, bukan ID Kota
            if($jamaah && $jamaah->location_prov){
                $cities = $this->Location_model->get_cities($jamaah->location_prov);
                foreach($cities as $row){
                    $selected = ($row->id == $value) ? 'selected' : '';
                    $html .= '<option value="'.$row->id.'" '.$selected.'>'.$row->name.'</option>';
                }
            }
        }
        $html .= '</select>';
        return $html;
    }

  	 public function _cb_kecamatan($value = '', $primary_key = null)
    {
        $html = '<select id="field-id_kecamatan" name="location_disct" class="form-control select2">';
        $html .= '<option value="">Pilih Kecamatan</option>';
        
        if(!empty($value) && $primary_key){
            $jamaah = $this->db->get_where('data_jamaah', ['id_jamaah' => $primary_key])->row();
            // PERBAIKAN LOGIC: Ambil list kecamatan berdasarkan ID KOTA
            if($jamaah && $jamaah->location_city){
                $dists = $this->Location_model->get_districts($jamaah->location_city);
                foreach($dists as $row){
                    $selected = ($row->id == $value) ? 'selected' : '';
                    $html .= '<option value="'.$row->id.'" '.$selected.'>'.$row->name.'</option>';
                }
            }
        }
        $html .= '</select>';
        return $html;
    }

  	 public function _cb_kelurahan($value = '', $primary_key = null)
    {
        $html = '<select id="field-id_kelurahan" name="location_village" class="form-control select2">';
        $html .= '<option value="">Pilih Kelurahan</option>';
        
        // Pastikan Primary Key ada (Mode Edit)
        if($primary_key){
            $jamaah = $this->db->get_where('data_jamaah', ['id_jamaah' => $primary_key])->row();
            
            // [PERBAIKAN LOGIC DISINI]
            // Kita harus cek apakah Kecamatan (location_disct) sudah terisi?
            // Lalu ambil daftar desa berdasarkan ID KECAMATAN tersebut.
            if($jamaah && !empty($jamaah->location_disct)){
                
                // Panggil model get_villages menggunakan ID KECAMATAN
                $vills = $this->Location_model->get_villages($jamaah->location_disct);
                
                foreach($vills as $row){
                    // Cek apakah ID row sama dengan value yang tersimpan di database
                    $selected = ($row->id == $value) ? 'selected' : '';
                    
                    $html .= '<option value="'.$row->id.'" '.$selected.'>'.$row->name.'</option>';
                }
            }
        }
        $html .= '</select>';
        return $html;
    }

	public function _reset_uuid_on_save($post_array, $primary_key)
    {
      	$this->db->where('id_jamaah', $primary_key); // Pastikan nama primary key sesuai tabel
		$this->db->update('data_jamaah', array('random_uuid' => NULL));
		return true;
    }

	public function _callback_disable_agen($value, $primary_key)
	{
		// Ambil nama agen berdasarkan ID yang tersimpan ($value)
		$nama_agen = '-';
		if(!empty($value)){
			$query = $this->db->get_where('data_jamaah', array('id_jamaah' => $value))->row(); // Sesuaikan tabel agen Anda
			if($query){
				$nama_agen = $query->nama_jamaah; // Sesuaikan field nama agen
			}
		}

		return '
			<input type="text" class="form-control" value="'.$nama_agen.'" disabled="disabled" />
			<input type="hidden" name="agen" value="'.$value.'" />
		';
	}

	public function jamaah_cb_uuid($post_array, $uuid){
		
		$jamaah = $this->db->from('data_jamaah')->select('tgl_lahir')->where(['random_uuid' => $uuid])->get()->row();
		$age = floor((time() - strtotime($jamaah->tgl_lahir)) / 31556926);
		$this->db->update('data_jamaah', ['age' => $age], ['random_uuid' => $uuid]);
	}
	
	function jamaah($paket = 0)
	{

		$level = $this->session->userdata('level');
		if ($level == 4)
			redirect('masterjamaahlink/jamaah_p');
		// revisi 26 maret 2024, no ktp dan passport unik
		$this->crud->set_table('data_jamaah')->unique_fields(array('no_ktp', 'passport'));
		$nama = $this->input->post('s', true);

		if (isset($nama) && $nama != null)
			$this->crud->like('nama_jamaah', $nama);
		$this->crud->set_subject('Data Jamaah Umroh')
		->set_top('Data Jamaah Umroh')->set_theme('datatables')->unset_delete();
		$this->crud->required_fields('city');
		$this->crud->unset_texteditor('keterangan', 'full_text');
		$this->crud->unset_texteditor('alamat_jamaah', 'full_text');
		$this->crud->fields('title', 'nama_jamaah', 'tgl_lahir', 'alamat_jamaah', 'keterangan', 'no_ktp', 'no_tlp', 'agen', 'hp_jamaah', 'passport', 'issued', 'expired', 'office', 'nama_di_vaksin', 'jenis_vaksin', 'tgl_vaksin_1', 'jenis_vaksin_2', 'tgl_vaksin_2', 'jenis_vaksin_3', 'tgl_vaksin_3', 'jenis_vaksin_4', 'tgl_vaksin_4', 'foto', 'kartukeluarga', 'ktp', 'surat_nikah','is_agen');
		$this->crud->unset_read()->columns('nama_jamaah', 'paket', 'tgl_lahir', 'no_ktp', 'agen', 'hp_jamaah', 'alamat_jamaah', 'user_id','action_link');
		$this->crud->set_rules('no_ktp', 'Nomor KTP', 'trim|required');
		$this->crud->set_rules('no_tlp', 'No Telepon', 'trim|required');
		$this->crud->set_rules('nama_jamaah', 'Nama Jamaah', 'max_length[100]');
		

		$this->crud->display_as('action_link', 'Link Form');
		$this->crud->callback_column('action_link', array($this, '_callback_tombol_copy'));
		$this->crud->display_as('title', 'Sebutan');
		$this->crud->display_as('nama_jamaah', 'Nama Lengkap');
		$this->crud->display_as('tgl_lahir', 'Tanggal Lahir');
		$this->crud->display_as('alamat_jamaah', 'Alamat Jamaah');
		$this->crud->display_as('no_ktp', 'No KTP');
		$this->crud->display_as('no_tlp', 'No Telepon');
		$this->crud->display_as('hp_jamaah', 'No HP');
		$this->crud->display_as('passport', 'No Passport');
		$this->crud->display_as('issued', 'Tanggal Pengeluaran');
		$this->crud->display_as('expired', 'Tanggal Habis Berlaku');
		$this->crud->display_as('foto', 'Foto Diri');
		$this->crud->display_as('kartukeluarga', 'Kartu Keluarga');
		$this->crud->display_as('ktp', 'KTP');
		$this->crud->display_as('surat_nikah', 'Surat Nikah');
		$this->crud->display_as('nama_di_vaksin', 'Nama di Sertifikat Vaksin');
		$this->crud->display_as('jenis_vaksin', 'Jenis Vaksin 1');
		$this->crud->display_as('jenis_vaksin_2', 'Jenis Vaksin 2');
		$this->crud->display_as('jenis_vaksin_3', 'Jenis Vaksin 3');
		$this->crud->display_as('jenis_vaksin_4', 'Jenis Vaksin 4');
		$this->crud->display_as('tgl_vaksin_1', 'Tgl Vaksin 1');
		$this->crud->display_as('tgl_vaksin_2', 'Tgl Vaksin 2');
		$this->crud->display_as('tgl_vaksin_3', 'Tgl Vaksin 3');
		$this->crud->display_as('tgl_vaksin_4', 'Tgl Vaksin 4');
		$this->crud->display_as('user_id', 'Admin');
		// $this->crud->set_relation_n_n('paket','transaksi_paket','data_jamaah_paket','jamaah','paket_umroh','{estimasi_keberangkatan}/{Program}-{data_jamaah_paket.harga}');
		$this->crud->set_relation('agen', 'data_jamaah', '{nama_jamaah}',array('is_agen' => '1'))
		// ->set_relation('user_id', 'admin', '{nama_admin}')
		->callback_column('paket', array($this, '_callback_paket'))
		// revisi 26 maret 2024
		->callback_column('user_id', array($this, 'user_id_callback'));
		// $query = $this->db->get("v_paket");
		$query = $this->db->query("SELECT id,CONCAT( UPPER(estimasi_keberangkatan),'-',Program,'-',CAST(FORMAT(harga,2,'de_DE') 
		      AS CHAR CHARACTER SET utf8)) AS detail FROM data_jamaah_paket");
		foreach ($query->result() as $row) {

			$this->paket[$row->id] = $row->detail;
		}
		$query = $this->db->get("transaksi_paket");
		foreach ($query->result_array() as $row) {

			//$link ="<a href='".site_url('transaksi_op/pembayaran/'.$row->paket_umroh)."'data-toggle='tooltip' title='Klik untuk pembayaran'>".$this->paket[$row->paket_umroh]."</a>";
			if (isset($this->transaksi_paket[$row['jamaah']])) //

				//$this->transaksi_paket[$row->jamaah] .= isset($this->paket[$row->paket_umroh])?'#'.$this->paket[$row->paket_umroh]:'';
				$this->transaksi_paket[$row['jamaah']] .= isset($this->paket[$row['paket_umroh']]) ? "<a href='" . site_url('transaksi_op/pembayaran/' . $row['paket_umroh'] . '/' . $row['jamaah']) . "'data-toggle='tooltip' title='Klik untuk pembayaran' target='_blank'>" . $this->paket[$row['paket_umroh']] . "</a>" .(count($row) > 0 ? '<br/><br/>' : '') : '';
			else
				//$this->transaksi_paket[$row->jamaah] = isset($this->paket[$row->paket_umroh])?'#'.$this->paket[$row->paket_umroh]:'';
				$this->transaksi_paket[$row['jamaah']] = isset($this->paket[$row['paket_umroh']]) ? "<a href='" . site_url('transaksi_op/pembayaran/' . $row['paket_umroh'] . '/' . $row['jamaah']) . "'data-toggle='tooltip' title='Klik untuk pembayaran' target='_blank'>" . $this->paket[$row['paket_umroh']] . "</a>" .(count($row) > 0 ? '<br/><br/>' : '') : '';
		}
		// $this->crud->set_relation('leader','data_jamaah_agen','{nama}-{id}');

		$this->crud->set_field_upload('foto', 'assets/uploads/foto');
		$this->crud->set_field_upload('kartukeluarga', 'assets/uploads/kk');
		$this->crud->set_field_upload('ktp', 'assets/uploads/ktp');
		$this->crud->set_field_upload('surat_nikah', 'assets/uploads/nikah');

		$this->crud->callback_before_upload(array($this,'_callback_before_upload'));

		$this->crud->callback_after_insert(array($this, 'jamaah_callback_after_insert_update'));
		$this->crud->callback_after_update(array($this, 'jamaah_callback_after_insert_update'));

		
		// $this->crud->set_rules('no_ktp','Nomor KTP / Paspor','trim|required|min_length[7]|max_length[17]');

		$query = $this->db->get('jenis_transaksi');
		foreach ($query->result() as $row) {

			$this->t[$row->id] = $row->nama_transaksi;
		}

		$this->crud->callback_insert(array($this,'_insert_user_id'));
		$this->crud->callback_edit_field('no_ktp', function ($value, $primary_key) {
            return '
			<div class="form-input-box control-group" id="no_ktp_input_box">
				<input id="field-no_ktp" style="pointer-events:none;" class="form-control" value="'.$value.'" name="no_ktp" type="text">
			</div>
			';
        });

		$this->crud->order_by('id_jamaah', 'desc');

		$js_script = '
			<script>
				function copyToClipboard(text) {
					// Membuat elemen textarea sementara
					var dummy = document.createElement("textarea");
					document.body.appendChild(dummy);
					dummy.value = text;
					dummy.select();
					
					// Eksekusi copy
					document.execCommand("copy");
					document.body.removeChild(dummy);
					
					// Notifikasi sukses (opsional, bisa diganti SweetAlert jika ada)
					alert("Link berhasil disalin!");
				}
			</script>';

		$this->output->append_output($js_script);
		$this->show();
	}

	public function _callback_tombol_copy($value, $row)
	{
		// Cek apakah kolom random_uuid di database memiliki nilai
		if (!empty($row->random_uuid)) {
			
			// Tentukan URL yang ingin disalin. 
			// Sesuaikan 'controller/method/' dengan link tujuan Anda sebenarnya.
			$link_tujuan = site_url('masterjamaahlink/jamaahUUID/edit/' . $row->random_uuid);
			return '<button type="button" class="btn btn-warning btn-xs" onclick="copyToClipboard(\''.$link_tujuan.'\')">
						<i class="fa fa-copy"></i>Link Edit Jamaah
					</button>';
		}

		// Jika random_uuid kosong, tidak menampilkan apa-apa
		return '';
	}

	// revisi 26 maret 2024, menampilkan tanggal dan user created_at
	public function user_id_callback($value, $row){
		$row = $this->db->select('created_at, nama_admin')
			->from('data_jamaah')
			->join('admin', 'user_id = id_admin')
			->where('id_jamaah', $row->id_jamaah)->get()->row();
		if(isset($row)) return (new DateTime($row->created_at))->format('d/m/Y H:i') . '/' . $row->nama_admin;
		return;
	}

	public function user_id_callbackUUID($value, $row){
		$row = $this->db->select('created_at, nama_admin')
			->from('data_jamaah')
			->join('admin', 'user_id = id_admin')
			->where('random_uuid', $row->random_uuid)->get()->row();
		if(isset($row)) return (new DateTime($row->created_at))->format('d/m/Y H:i') . '/' . $row->nama_admin;
		return;
	}

	public function _insert_user_id($post_array){
		$user_id = $this->session->userdata('id_admin');
		$post_array['random_uuid'] = NULL;
		$post_array['user_id'] = $user_id;
		return $this->db->insert('data_jamaah', $post_array);
	}

	public function jamaah_callback_after_insert_update($post_array, $primary_key){
		
		$jamaah = $this->db->from('data_jamaah')->select('tgl_lahir')->where(['id_jamaah' => $primary_key])->get()->row();
		$age = floor((time() - strtotime($jamaah->tgl_lahir)) / 31556926);
		$this->db->update('data_jamaah', ['age' => $age], ['id_jamaah' => $primary_key]);
	}

	public function _callback_before_upload($files_to_upload,$field_info){
		foreach($files_to_upload as $file){
			if( !($file['type'] == 'image/jpeg') && !($file['type'] == 'image/png')){
				return 'Hanya menerima jpg atau png';
			}

			if( ($file['size'] >= 2000000) ){
				return 'ukuran gambar maksimal 2MB';
			}
		}
		return true;
	}

	public function _callback_paket($value, $row)
	{
		$a = isset($this->transaksi_paket[$row->id_jamaah]) ? $this->transaksi_paket[$row->id_jamaah] : '';
		return $a;
	}
	public function _callback_transaksi($value, $row)
	{
		$id = $row->id_jamaah;
		$a = "<a href='" . site_url('transaksi/paket_jamaah/' . $id) . "'data-toggle='tooltip' title='Pembelian Paket Umroh Jamaah'>Transaksi</a>";
		// $a.=" | <a href='".site_url('transaksi/kredit_all/'.$id)."'data-toggle='tooltip' title='Setoran Jamaah'>Kredit</a> | <a href='".site_url('transaksi/histori/'.$id)."'data-toggle='tooltip' title='Histori Transaksi'>Histori</a>";

		return $a;
	}
	function _harga_rp($value, $row)
	{
		//return $this->main_model->get_kurs()*$row->harga_dolar;
		//echo "xxxx";
		//var_dump($row);
		if ($row->harga == null) return 0;
		return number_format((float)$row->harga);
		//setlocale(LC_MONETARY, 'id_ID');
		//return  money_format('%i', floatval($value)) . "\n";
		//return $value;
	}
	function paket()
	{
		$this->crud->set_table('data_jamaah_paket');
		$this->crud->callback_column('harga', array($this, '_harga_rp'));
		//	$this->crud->set_relation('hotel','data_hotel','nama');
		$this->crud->set_relation('hotel_makkah', 'data_hotel', 'nama');
		$this->crud->set_relation('hotel_madinah', 'data_hotel', 'nama');
		$this->crud->set_relation('Penerbangan', 'data_maskapai', 'nama');
		$this->crud->set_relation('travel', 'ref_travel', 'nama_travel');
		$this->crud->set_relation('paket_id', 'data_jamaah_paket', 'estimasi_keberangkatan');
		$this->crud->set_subject('Data Paket Umroh');
		    $this->crud->required_fields('travel','Program','Penerbangan','hotel_makkah','hotel_madinah','tanggal_keberangkatan');

		$this->crud
			->unset_read()->columns('travel','estimasi_keberangkatan', 'Program', 'Penerbangan', 'is_active', 'hotel_makkah', 'hotel_madinah', 'harga', 'tanggal_keberangkatan', 'pembimbing', 'total_seat', 'qty', 'detil')
			->unset_delete()
			->set_relation('pembimbing', 'data_jamaah_pembimbing', 'nama');
		$this->crud->fields('travel','estimasi_keberangkatan',
					'estimasi_tgl_keberangkatan', 
					'tanggal_keberangkatan', 
					'qty', 'program', 'Penerbangan', 
					'hotel_makkah', 'hotel_madinah',
					'detil', 'pembimbing', 'harga', 'total_seat',
					'paket_tunda', 'paket_id', 'is_active');
		
		$this->crud
		->field_type('is_active', 'true_false', array('Arsip', 'Aktif'))
		->field_type('paket_tunda', 'true_false', array('Tidak', 'Ya'));

		$this->crud->display_as('estimasi_keberangkatan', 'Nama Paket')
			->display_as('estimasi_tgl_keberangkatan', 'Estimasi Tgl Keberangkatan')
			->display_as('tanggal_keberangkatan', 'Tgl Keberangkatan')
			->display_as('hotel_makkah', 'Hotel Makkah')
			->display_as('hotel_madinah', 'Hotel Madinah')
			->display_as('detil', 'Detail')
			->display_as('total_seat', 'Total Seat')
			->display_as('paket_tunda', 'Paket Tunda')
			->display_as('paket_id', 'List Paket Aktif')
			->display_as('is_active', 'Keterangan');

		$this->show();
	}
	function paket_($arsip = '1')
	{

		$this->crud->set_table('data_jamaah_paket');
$this->crud->required_fields('travel','Program','Penerbangan','hotel_makkah','hotel_madinah','tanggal_keberangkatan');
		$this->crud->callback_column('harga', array($this, '_harga_rp'));
		//$this->crud->set_relation('hotel','data_hotel','nama');
		$this->crud->set_relation('hotel_makkah', 'data_hotel', 'nama');
		$this->crud->set_relation('hotel_madinah', 'data_hotel', 'nama');
		$this->crud->set_relation('Penerbangan', 'data_maskapai', 'nama');
		$this->crud->set_relation('paket_id', 'data_jamaah_paket', '{program} - {estimasi_keberangkatan}');
		$this->crud->set_subject('Data Paket Umroh');
		$this->crud->set_top('Data Paket Umroh');
		$this->crud->set_relation('travel', 'ref_travel', 'nama_travel');
		$this->crud->unset_read()->columns('travel','estimasi_keberangkatan', 'Program', 'Penerbangan', 'rute', 'hotel_makkah',  'hotel_madinah', 'harga', 'tanggal_keberangkatan', 'pembimbing', 'total_seat', 'qty', 'detil', 'paket_id')
		->fields('travel','estimasi_keberangkatan', 'tanggal_keberangkatan', 'qty', 'detil', 'Program', 'Penerbangan', 'hotel_makkah', 'hotel_madinah', 'pembimbing', 'harga', 'total_seat', 'paket_id', 'KET')->unset_delete()->set_relation('pembimbing', 'data_jamaah_pembimbing', 'nama');

		$this->crud->display_as('estimasi_keberangkatan', 'Estimasi Keberangkatan');
		$this->crud->display_as('tanggal_keberangkatan', 'Tanggal Keberangkatan');
		$this->crud->display_as('hotel_makkah', 'Hotel Makkah');
		$this->crud->display_as('hotel_madinah', 'Hotel Madinah');
		$this->crud->display_as('detil', 'Detail');
		$this->crud->display_as('total_seat', 'Total Seat');
		$this->crud->display_as('paket_id', 'Paket Tunda (Paket Pengganti)');
		$this->show();
	}
	function pembimbing()
	{
		$this->crud->set_table('data_jamaah_pembimbing');
		$this->crud->set_top('Data Pembimbing');
		$this->crud->set_subject('Data Pembimbing');
		$this->crud->unset_read()->columns('nama', 'alamat', 'pendidikan', 'sertifikat')->unset_delete();
		$this->crud->display_as('nama', 'Nama Pembimbing');
		$this->show();
	}
	function transaksi_pemasukan()
	{
		$this->crud->set_table('jenis_transaksi');
		$this->crud->set_subject('Jenis Transaksi');
		$this->crud->set_theme('datatables');
		$this->crud->set_subject('Jenis Transaksi')->unset_edit()->unset_delete();
		$this->show();
	}
	function transaksi_pengeluaran()
	{
		$this->crud->set_table('jenis_transaksi_pengeluaran');
		$this->crud->set_subject('Jenis Transaksi');
		$this->crud->set_theme('datatables');
		$this->crud->set_subject('Jenis Transaksi')->unset_edit()->unset_delete();
		$this->show();
	}
	function log()
	{
	}
}
// END Kelas Class

/* End of file kelas.php */
/* Location: ./system/application/controllers/kelas.php */