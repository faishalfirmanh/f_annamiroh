<?php
/**
 * Kelas Class
 *
 * @author	Awan Pribadi Basuki <awan_pribadi@yahoo.com>
 */
class Umroh extends CI_Controller {
	/**
	 * Constructor
	 */
	function __construct()
	{
		parent::__construct();
		$this->load->model('Jamaah_model', '', TRUE);
		$this->load->model('Kbih_model', '', TRUE);
		$this->load->model('Bank_model', '', TRUE);
		$this->load->model('Status_model', '', TRUE);
		$this->load->model('Master_model', '', TRUE);
	}
	
	/**
	 * Inisialisasi variabel untuk $title(untuk id element <body>)
	 */
	var $title = 'jamaah';
	var $limit = 15;
	/**
	 * Memeriksa user state, jika dalam keadaan login akan menampilkan halaman kelas,
	 * jika tidak akan meredirect ke halaman login
	 */
	function index()
	{
		if ($this->session->userdata('login') == TRUE)
		{
			$this->get_jamaahnya();
		}
		else
		{
			redirect('login');
		}
	}
	function cet($id){
	    $location = "https://namiroh.ypp-alhidayah.com/pdf/kwitansi.php?t=allt&id=$id";

       header('Location: '.$location);
	}
	function cari_pembayaran(){
	    	if ($this->session->userdata('login') != TRUE)
			redirect('login');
		$this->load->library('grocery_CRUD');
		$crud = new grocery_CRUD();
		
	    $crud->set_theme('datatables');
		$crud->set_table('semua_data1')->unset_read()->columns('id_admin','nama','keterangan','tgl_bayar','nominal')->fields('id_admin','nama','keterangan','tgl_bayar');//
		$crud->set_relation('id_admin','admin','nama_admin')->display_as('id_admin','Teller');
		$crud->set_subject('Data Pembayaran')->unset_edit()->unset_delete()->unset_read()->unset_add();
		//$crud->add_action('Print', 'https://namiroh.ypp-alhidayah.com/assets/grocery_crud/themes/flexigrid/css/images/print.png', 'umroh/cet','your_css_class add_target_blank');

		//$this->db->join('admin', 'admin.id_admin=semua_data1.id_admin');
		$crud->callback_column('nominal',array($this,'_callback_webpage_url'));
		$output = $crud->render();
		$data['title'] = $this->title;
		$data['nama_menu'] = 'Menu';
		$data['menu_kiri'] = 'jamaah/jamaah_left';
		$data['h2_title'] = 'Data Jamaah';
		$data['main_view'] = 'transaksi_template';
		$data['isi'] = 'jamaah/jamaah_view';
		$data['output'] = $output;
		$this->load->view('template_new',$output);	
		
	}
	public function _callback_webpage_url($value, $row)
    {
        return "$value &nbsp;&nbsp;&nbsp;<a href='".site_url('umroh/cet/'.$row->id_semua_data)."' target='_blank'>Print</a>";
    }
	// cek apakah valid untuk update?
	function valid_no_porsi2()
	{
		// cek agar tidak ada nis ganda, khusus untuk proses update
		$current_no_porsi 	= $this->session->userdata('no_porsi');
		$new_no_porsi		= $this->input->post('no_porsi');
				
		if ($new_no_porsi === $current_no_porsi)
		{
			return TRUE;
		}
		else
		{
			if($this->Jamaah_model->valid_no_porsi($new_no_porsi) === TRUE) // cek database untuk entry yang sama memakai valid_entry()
			{
				$this->form_validation->set_message('valid_no_porsi2', "No Porsi $new_no_porsi sudah terdaftar");
				return FALSE;
			}
			else
			{
				return TRUE;
			}
		}
	}
	
	function valid_no_porsi($no_porsi){
		if ($this->Jamaah_model->valid_no_porsi($no_porsi) == TRUE){
			$this->form_validation->set_message('valid_no_porsi', "no porsi $no_porsi sudah terdaftar");
			return FALSE;
		}
		else{			
			return TRUE;
		}
	}

	function add(){
		$data['title'] = $this->title;
		$data['nama_menu'] = 'Menu';
		$data['menu_kiri'] = 'jamaah/jamaah_left';
		$data['h2_title'] = 'Data Jamaah';
		$data['main_view'] = 'transaksi_template';
		$data['isi'] = 'jamaah/jamaah_form';
		$data['form_action'] = site_url('jamaah/add_proses');
				
		$status = $this->Status_model->get_status()->result();
		foreach($status as $row){
			$data['options_status'][$row->id_status] = $row->status;
		}
		
		$kecamatan = $this->Master_model->get_kecamatan()->result();
		foreach($kecamatan as $row){
			$data['options_kecamatan'][$row->id_kecamatan] = $row->kecamatan;
		}
		
		$this->load->view('template', $data);
	}
	
	function add_proses2(){
		$data['title'] = $this->title;
		$data['nama_menu'] = 'Menu';
		$data['menu_kiri'] = 'jamaah/jamaah_left';
		$data['h2_title'] = 'Data Jamaah';
		$data['main_view'] = 'transaksi_template';
		$data['isi'] = 'jamaah/jamaah_form';
		$data['form_action'] = site_url('jamaah/add_proses2');
		
		$this->form_validation->set_rules('no_porsi', 'no porsi', 'required|numeric|callback_valid_no_porsi');
		$this->form_validation->set_rules('nama_jamaah', 'Nama Jamaah', 'required|min_length[2]|max_length[50]');
		$this->form_validation->set_rules('ortu', 'Nama Ortu', 'required|min_length[2]|max_length[50]');
		$this->form_validation->set_rules('id_kabupaten', 'Kabupaten', 'required');
		$this->form_validation->set_rules('id_kecamatan2', 'Kecamatan', 'required');
		
		if ($this->form_validation->run() == TRUE){
			$jamaah = array(
			'id_status' => $this->input->post('id_status'),
			'tahun' => $this->input->post('tahun'),
			'nama_jamaah' => $this->input->post('nama_jamaah'), 
			'nama_ortu' => $this->input->post('ortu'),
			'tgl_daftar' => $this->input->post('tgl_daftar'),
			'tgl_porsi' => $this->input->post('tgl_porsi'),
			'tgl_tempo' => $this->input->post('tgl_tempo'),
			'no_porsi' => $this->input->post('no_porsi'),
			'bank' => $this->input->post('bank'),
			'no_rekening' => $this->input->post('no_rekening'),
			'alamat_jamaah' => $this->input->post('alamat_jamaah'),
			'alamat_ktp' => $this->input->post('alamat_ktp'),
			'id_kecamatan' => $this->input->post('id_kecamatan2'),
			'no_tlp' => $this->input->post('tlp'),
			);
			
			$this->Jamaah_model->add($jamaah);
			
			$this->session->set_flashdata('message', 'Data jamaah berhasil disimpan!');
			redirect('jamaah');
		}
		
			$data['default']['id_status'] = $this->input->post('id_status');
			$data['default']['nama_jamaah'] = $this->input->post('nama_jamaah');
			$data['default']['nama_ortu'] = $this->input->post('ortu');
			$data['default']['tgl_daftar'] = $this->input->post('tgl_daftar');
			$data['default']['tgl_porsi'] = $this->input->post('tgl_porsi');
			$data['default']['tgl_tempo'] = $this->input->post('tgl_tempo');
			$data['default']['no_porsi'] = $this->input->post('no_porsi');
			$data['default']['bank'] = $this->input->post('bank');
			$data['default']['no_rekening'] = $this->input->post('no_rekening');
			$data['default']['alamat_jamaah'] = $this->input->post('alamat_jamaah');
			$data['default']['alamat_ktp'] = $this->input->post('alamat_ktp');
			$data['default']['id_kabupaten'] =  $this->input->post('id_kabupaten');
			$data['default']['id_kecamatan'] = $this->input->post('id_kecamatan2');
			$data['default']['tlp'] = $this->input->post('tlp');
			$data['default']['tahun'] = $this->input->post('tahun');
		
		
		$status = $this->Status_model->get_status()->result();
		foreach($status as $row){
			$data['options_status'][$row->id_status] = $row->status;
		}
		
		$kecamatan = $this->Master_model->get_kecamatan()->result();
		foreach($kecamatan as $row){
			$data['options_kecamatan'][$row->id_kecamatan] = $row->kecamatan;
		}
		
		$this->load->view('template', $data);
	}
	
	function add_proses(){
		$data['title'] = $this->title;
		$data['nama_menu'] = 'Menu';
		$data['menu_kiri'] = 'jamaah/jamaah_left';
		$data['h2_title'] = 'Data Jamaah';
		$data['main_view'] = 'transaksi_template';
		$data['isi'] = 'jamaah/jamaah_form';
		$data['form_action'] = site_url('jamaah/add_proses2');
		
		$this->form_validation->set_rules('no_porsi', 'no porsi', 'required|numeric|callback_valid_no_porsi');
		$this->form_validation->set_rules('nama_jamaah', 'Nama Jamaah', 'required|min_length[2]|max_length[50]');
		$this->form_validation->set_rules('ortu', 'Nama Ortu', 'required|min_length[2]|max_length[50]');
		$this->form_validation->set_rules('id_kabupaten', 'Kabupaten', 'required');
		$this->form_validation->set_rules('id_kecamatan', 'Kecamatan', 'required');
		
		if ($this->form_validation->run() == TRUE){
			$jamaah = array(
			'id_status' => $this->input->post('id_status'),
			'tahun' => $this->input->post('tahun'),
			'nama_jamaah' => $this->input->post('nama_jamaah'), 
			'nama_ortu' => $this->input->post('ortu'),
			'tgl_daftar' => $this->input->post('tgl_daftar'),
			'tgl_porsi' => $this->input->post('tgl_porsi'),
			'tgl_tempo' => $this->input->post('tgl_tempo'),
			'no_porsi' => $this->input->post('no_porsi'),
			'bank' => $this->input->post('bank'),
			'no_rekening' => $this->input->post('no_rekening'),
			'alamat_jamaah' => $this->input->post('alamat_jamaah'),
			'alamat_ktp' => $this->input->post('alamat_ktp'),
			'id_kecamatan' => $this->input->post('id_kecamatan'),
			'no_tlp' => $this->input->post('tlp'),
			);
			
			$this->Jamaah_model->add($jamaah);
			
			$this->session->set_flashdata('message', 'Data jamaah berhasil disimpan!');
			redirect('jamaah');
		}
		
			$data['default']['id_status'] = $this->input->post('id_status');
			$data['default']['nama_jamaah'] = $this->input->post('nama_jamaah');
			$data['default']['nama_ortu'] = $this->input->post('ortu');
			$data['default']['tgl_daftar'] = $this->input->post('tgl_daftar');
			$data['default']['tgl_porsi'] = $this->input->post('tgl_porsi');
			$data['default']['tgl_tempo'] = $this->input->post('tgl_tempo');
			$data['default']['no_porsi'] = $this->input->post('no_porsi');
			$data['default']['bank'] = $this->input->post('bank');
			$data['default']['no_rekening'] = $this->input->post('no_rekening');
			$data['default']['alamat_jamaah'] = $this->input->post('alamat_jamaah');
			$data['default']['alamat_ktp'] = $this->input->post('alamat_ktp');
			$data['default']['id_kabupaten'] =  $this->input->post('id_kabupaten');
			$data['default']['id_kecamatan'] = $this->input->post('id_kecamatan');
			$data['default']['tlp'] = $this->input->post('tlp');
			$data['default']['tahun'] = $this->input->post('tahun');
		
		
		$status = $this->Status_model->get_status()->result();
		foreach($status as $row){
			$data['options_status'][$row->id_status] = $row->status;
		}
		
		$kecamatan = $this->Master_model->get_kecamatan()->result();
		foreach($kecamatan as $row){
			$data['options_kecamatan'][$row->id_kecamatan] = $row->kecamatan;
		}
		
		$this->load->view('template', $data);
	}
	
	function update($id){
		$data['title'] = $this->title;
		$data['nama_menu'] = 'Menu';
		$data['menu_kiri'] = 'jamaah/jamaah_left';
		$data['h2_title'] = 'Data Jamaah';
		$data['main_view'] = 'transaksi_template';
		$data['isi'] = 'jamaah/jamaah_form';
		$data['form_action'] = site_url('jamaah/update_proses');
		
		//session
		$id_jamaah = ($id);
		//Data Jamaah
		$jamaah = $this->Jamaah_model->get_jamaah_by_id($id_jamaah);
		$rjamaah = $jamaah->row_array();
		// Data untuk mengisi fild form
		$data['default']['id_jamaah'] = $rjamaah['id_jamaah'];
		$data['default']['id_status'] = $rjamaah['id_status'];
		$data['default']['nama_jamaah'] = $rjamaah['nama_jamaah'];
		$data['default']['nama_ortu'] = $rjamaah['nama_ortu'];
		$data['default']['tgl_daftar'] = $rjamaah['tgl_daftar'];
		$data['default']['tgl_porsi'] = $rjamaah['tgl_porsi'];
		$data['default']['tgl_tempo'] = $rjamaah['tgl_tempo'];
		$data['default']['no_porsi'] = $rjamaah['no_porsi'];
		$data['default']['bank'] = $rjamaah['bank'];
		$data['default']['no_rekening'] = $rjamaah['no_rekening'];
		$data['default']['alamat_jamaah'] = $rjamaah['alamat_jamaah'];
		$data['default']['alamat_ktp'] = $rjamaah['alamat_ktp'];
		$data['default']['id_kabupaten'] = $rjamaah['id_kabupaten'];
		$data['default']['id_kecamatan'] = $rjamaah['id_kecamatan'];
		$data['default']['tlp'] = $rjamaah['no_tlp'];
		$data['default']['tahun'] = $rjamaah['tahun'];
		
		$status = $this->Status_model->get_status()->result();
		foreach($status as $row){
			$data['options_status'][$row->id_status] = $row->status;
		}
		
		$kecamatan = $this->Master_model->get_kecamatan()->result();
		foreach($kecamatan as $row){
			$data['options_kecamatan'][$row->id_kecamatan] = $row->kecamatan;
		}

		$this->load->view('template', $data);	
	}
	
	function update_proses(){
		$data['title'] = $this->title;
		$data['nama_menu'] = 'Menu';
		$data['menu_kiri'] = 'jamaah/jamaah_left';
		$data['h2_title'] = 'Data Jamaah';
		$data['main_view'] = 'transaksi_template';
		$data['isi'] = 'jamaah/jamaah_form';
		$data['form_action'] = site_url('jamaah/update_proses');
		
		$this->form_validation->set_rules('nama_jamaah', 'Nama Jamaah', 'required|min_length[2]|max_length[50]');
		$this->form_validation->set_rules('ortu', 'Nama Ortu', 'required|min_length[2]|max_length[50]');
		$this->form_validation->set_rules('id_kabupaten', 'Kabupaten', 'required');
		$this->form_validation->set_rules('id_kecamatan2', 'Kecamatan', 'required');
		
		if ($this->form_validation->run() == TRUE){
			$jamaah = array(
			'id_status' => $this->input->post('id_status'),
			'tahun' => $this->input->post('tahun'),
			'nama_jamaah' => $this->input->post('nama_jamaah'), 
			'nama_ortu' => $this->input->post('ortu'),
			'tgl_daftar' => $this->input->post('tgl_daftar'),
			'tgl_porsi' => $this->input->post('tgl_porsi'),
			'tgl_tempo' => $this->input->post('tgl_tempo'),
			'no_porsi' => $this->input->post('no_porsi'),
			'bank' => $this->input->post('bank'),
			'no_rekening' => $this->input->post('no_rekening'),
			'alamat_jamaah' => $this->input->post('alamat_jamaah'),
			'alamat_ktp' => $this->input->post('alamat_ktp'),
			'id_kecamatan' => $this->input->post('id_kecamatan2'),
			'no_tlp' => $this->input->post('tlp'),
			);
			
			$this->Jamaah_model->update($this->input->post('id_jamaah'), $jamaah);
			
			$this->session->set_flashdata('message', 'Data jamaah berhasil disimpan!');
			redirect('jamaah/jamaah');
		}
		else{
			
			// Data untuk mengisi fild form
			$data['default']['id_status'] = $this->input->post('id_status');
			$data['default']['nama_jamaah'] = $this->input->post('nama_jamaah');
			$data['default']['nama_ortu'] = $this->input->post('ortu');
			$data['default']['tgl_daftar'] = $this->input->post('tgl_daftar');
			$data['default']['tgl_porsi'] = $this->input->post('tgl_porsi');
			$data['default']['tgl_tempo'] = $this->input->post('tgl_tempo');
			$data['default']['no_porsi'] = $this->input->post('no_porsi');
			$data['default']['bank'] = $this->input->post('bank');
			$data['default']['no_rekening'] = $this->input->post('no_rekening');
			$data['default']['alamat_jamaah'] = $this->input->post('alamat_jamaah');
			$data['default']['alamat_ktp'] = $this->input->post('alamat_ktp');
			$data['default']['id_kabupaten'] =  $this->input->post('id_kabupaten');
			$data['default']['id_kecamatan2'] = $this->input->post('id_kecamatan2');
			$data['default']['tlp'] = $this->input->post('tlp');
			$data['default']['tahun'] = $this->input->post('tahun');
		
			
			$status = $this->Status_model->get_status()->result();
			foreach($status as $row){
				$data['options_status'][$row->id_status] = $row->status;
			}
			
			$kecamatan = $this->Master_model->get_kecamatan()->result();
			foreach($kecamatan as $row){
				$data['options_kecamatan'][$row->id_kecamatan] = $row->kecamatan;
			}
			
			$this->load->view('template', $data);
		}		
	}
	
	function delete($id){
		$this->Jamaah_model->delete($id);
		$this->session->set_flashdata('message', 'data berhasil dihapus');
		redirect('jamaah');
	}
	
	function cari_proses(){
		$data['title'] = $this->title;
		$data['h2_title'] = 'Halaman Depan';
		$data['main_view'] = 'admin_template';
		$data['isi'] = 'admin/admin_view';
		//
		$this->form_validation->set_rules('no_porsi', 'No Porsi', 'required');
		if ($this->form_validation->run() == TRUE){
			$porsi = $this->input->post('no_porsi');
			
			$cari_porsi = $this->Jamaah_model->cari_jamaah_by_porsi($porsi);
			if ($cari_porsi > 0){
				$jamaah = $this->Jamaah_model->get_jamaah_by_porsi($porsi);
				$data = array('id_jamaah' => $jamaah->id_jamaah);
				$this->session->set_userdata($data);
				redirect('transaksi');
			}
			else{
				$data['info'] = 'Data tidak ditemukan!';
			}
		
		}
		else{
			
		}
		$this->load->view('template', $data);
	}
	
	function get_last_ten_jamaah($offset = 0){
		$data['title'] = $this->title;
		$data['nama_menu'] = 'Menu';
		$data['menu_kiri'] = 'jamaah/jamaah_left';
		$data['h2_title'] = 'Data Jamaah';
		$data['main_view'] = 'transaksi_template';
		$data['isi'] = 'jamaah/jamaah_view';
		$uri_segment = 3;
		$offset = $this->uri->segment($uri_segment);
		
		$jamaahs = $this->Jamaah_model->get_last_ten_jamaah($this->limit, $offset)->result();
		$qr = $this->Jamaah_model->count_all_num_rows();
		$num = $qr->row_array();
		$num_rows = $num['id_jamaah'];
		
		if ($num_rows > 0){
			$config['base_url'] = site_url('jamaah/get_last_ten_jamaah');
			$config['total_rows'] = $num_rows;
			$config['per_page']	= $this->limit;
			$config['uri_segment'] = $uri_segment;
			$this->pagination->initialize($config);
			$data['pagination'] = $this->pagination->create_links();
			
			$tmpl = array('table_open' => '<table class="table" border="1">', 
			'row_alt_start' => '<tr class="zebra">', 
			'row_alt_end' => '</tr>');
			
			$this->table->set_template($tmpl);
			$this->table->set_empty("&nbsp;");
			$this->table->set_heading('No', 'Nama', 'No Porsi', 'Tahun', 'Status', 'Kecamatan', 'Alamat', 'Transaksi', 'Action');
			
			$i = 1 + $offset;
			foreach ($jamaahs as $jamaah){
				$this->table->add_row('<div align="right">'.$i++.'</div>', 
				$jamaah->nama_jamaah, 
				'<div align="right">'. $jamaah->no_porsi.'</div>', 
				$jamaah->tahun, 
				$jamaah->status,
				$jamaah->kecamatan,
				$jamaah->alamat_jamaah, 
				anchor('admin/goto_transaksi/'.$jamaah->id_jamaah,'Transaksi'),
				anchor ('jamaah/update/'.$jamaah->id_jamaah,'update',array('class' => 'update')).' '.
				anchor('jamaah/delete/'.$jamaah->id_jamaah,'hapus',array('class'=> 'delete','onclick'=>"return confirm('Anda yakin akan menghapus data ini?')")));
			}
			
				$data['table'] = $this->table->generate();
		}
		else{
			$data['message'] = 'Tidak ditemukan satupun data jamaah!';
		}
	// Load view
		
		$this->load->view('template', $data);
	} // end get_last_ten_jamaah
	function kontak(){
		if ($this->session->userdata('login') != TRUE)
			redirect('login');
		$this->load->library('grocery_CRUD');
		$crud = new grocery_CRUD();
		
		// $crud->set_theme('datatables');
		$crud->set_table('phonebook')->unset_read()->columns('nama', 'nohp');
		$crud->set_subject('Nomor Telepon');
		
		$output = $crud->render();
		$data['title'] = $this->title;
		$data['nama_menu'] = 'Menu';
		$data['menu_kiri'] = 'jamaah/jamaah_left';
		$data['h2_title'] = 'Data Jamaah';
		$data['main_view'] = 'transaksi_template';
		$data['isi'] = 'jamaah/jamaah_view';
		$data['output'] = $output;
		$this->load->view('template_new',$output);	
	
	}
	function get_jamaahnya(){
		if ($this->session->userdata('login') != TRUE)
			redirect('login');
		$this->load->library('grocery_CRUD');
		$crud = new grocery_CRUD();
		$crud->set_theme('datatables');
		// $crud->set_theme('twitter-bootstrap');
		// $crud->set_theme('datatables');
		$crud->set_table('data_jamaah_umroh');
		$crud->set_subject('Data Jamaah Umroh');
		$crud->required_fields('city');
		$crud->unset_read()->columns('nama_jamaah','no_porsi','tahun','id_status');
		$crud->display_as('kelengkapan','Kelengkapan Data');
		$crud->display_as('id_status','Talangan');
		$crud->display_as('id_kecamatan','Kecamatan');
		$crud->display_as('id_kabupaten','Kabupaten');
		$crud->set_relation('id_status','talangan','keterangan');
		$crud->set_relation('layanan','layanan','layanan');
		$crud->set_relation('kelengkapan','kelengkapan_data','kelengkapan');
		$crud->set_relation('id_kecamatan','kecamatan','kecamatan');
		$crud->set_relation('id_kabupaten','kabupaten','kabupaten');
		$crud->set_field_upload('foto','assets/uploads/foto');
		$crud->set_field_upload('porsi','assets/uploads/porsi');
		$crud->set_field_upload('spph','assets/uploads/spph');
		$crud->set_field_upload('kartukeluarga','assets/uploads/kk');
		$crud->set_field_upload('ktp','assets/uploads/ktp');
		$crud->set_field_upload('surat_nikah','assets/uploads/surat_nikah');
		$crud->set_field_upload('setoran_awal_bpih','assets/uploads/setoran_awal_bpih');
		
		 $crud->add_action('Transaksi', base_url('images/transaksi.jpg'), 'admin/goto_transaksi','ui-icon-plus');
		$output = $crud->render();
		$data['title'] = $this->title;
		$data['nama_menu'] = 'Menu';
		$data['menu_kiri'] = 'jamaah/jamaah_left';
		$data['h2_title'] = 'Data Jamaah';
		$data['main_view'] = 'transaksi_template';
		$data['isi'] = 'jamaah/jamaah_view';
		$data['output'] = $output;
		$this->load->view('template_new',$output);
	}
 	function transaksi_debet($modul=1,$id_jamaah=1){
		$judul=array(1=>'Pendaftaran',2=>'Paket Umroh',3=>'Pembayaran Paspor',4=>'Vaksin',5=>'Lain-lain');
		$judul = $judul[$modul];
		if ($this->session->userdata('login') != TRUE)
			redirect('login');
		$this->load->library('grocery_CRUD');
		$crud = new grocery_CRUD();
		$crud->set_theme('datatables');
		// $crud->set_theme('twitter-bootstrap');
		// $crud->set_theme('datatables');
		$crud->set_table('tb_pembayaran_umroh');
				$crud>where('id_jamaah = '.$id.' AND debet!=0');

		$crud->set_subject("Data Transaksi $judul")
		->set_top("Data Transaksi $judul");
		$crud->unset_read()->columns('nominal','debet');
		
		 $crud->add_action('Print', base_url('images/transaksi.jpg'), 'admin/goto_transaksi','ui-icon-plus');
		$output = $crud->render();
		$data['title'] = $this->title;
		$data['nama_menu'] = 'Menu';
		$data['menu_kiri'] = 'jamaah/jamaah_left';
		$data['h2_title'] = 'Data Jamaah';
		$data['main_view'] = 'transaksi_template';
		$data['isi'] = 'jamaah/jamaah_view';
		$data['output'] = $output;
		$this->load->view('template_new',$output);
	} 
	
}

/* Location: ./system/application/controllers/jamaah.php */