<?php
class Pemb extends CI_Controller {

	function __construct(){
		parent::__construct();	
		$this->load->model('Jamaah_model', '', TRUE);
		$this->load->model('Fungsi_model', '', TRUE);
		$this->load->model('Pemb_model', '', TRUE);
		$this->load->model('Onh_model', '', TRUE);

	}
	var $title = 'ONH';
	var $limit = 24;
	var $judul=array(1=>'Pendaftaran',2=>'Paket Umroh',3=>'Pembayaran Paspor',4=>'Vaksin',5=>'Lain-lain');
	
	function index(){
		if ($this->session->userdata('login') == TRUE){
			$this->get_last_ten_onh();		
		}
		else{
			$this->load->view('login/login_view');
		}
	}
	
	function del_debet($id){
		if ($this->session->userdata('login') != TRUE) redirect('pemb/index.php');
		$this->Onh_model->del_onh($id);
		$this->session->set_flashdata('message', 'data berhasil dihapus');
		redirect('pemb/debet');
	}
	
	function update_debet_proses(){
		if ($this->session->userdata('login') != TRUE) redirect('pemb/index.php');
		$data['title'] = $this->title;
		$data['menu_kiri'] = 'transaksi/transaksi_left';
		$data['h2_title'] = 'Transaksi > Debet ONH > Edit Data';
		$data['main_view'] = 'transaksi_template';
		$data['data_jamaah'] = 'jamaah/jamaah_data_view';
		$data['form_action'] = site_url('pemb/update_debet_proses');
		$data['isi'] = 'pemb/onh_form_debet';
		//session
		$id_jamaah = $this->session->userdata('id_jamaah');
		//Data Jamaah
		$jamaah = $this->Jamaah_model->get_jamaah_by_id($id_jamaah);
		$rjamaah = $jamaah->row_array();
		//
		$data['default']['no_porsi'] = $rjamaah['no_porsi'];
		$data['default']['nama_jamaah'] = $rjamaah['nama_jamaah'];
		
		$this->form_validation->set_rules('tgl_debet', 'Tgl Debet', 'required|min_length[10]|max_length[10]');
		$this->form_validation->set_rules('nominal', 'Nominal', 'required|min_length[2]|max_length[20]');
		//cek form
		if ($this->form_validation->run() == TRUE){
			$nominal = str_replace(",","",$this->input->post('nominal'));
			$jam = date('H:i:s');
			
			$tgl_bayar = $this->input->post('tgl_debet').' '.$jam;
		
			$onh = array('id_pembayaran' => $this->input->post('id_pembayaran'),
			'tgl_bayar' => $tgl_bayar, 
			'debet' => $nominal);
			
			$this->Onh_model->update_kredit($this->input->post('id_pembayaran'), $onh);
			
			$this->session->set_flashdata('message', 'Data debet ONH berhasil disimpan!');
			redirect('pemb/debet');
		}
		else{
			$data['default']['id_pembayaran'] = $this->input->post('id_pembayaran');
			$data['default']['tgl_debet'] = $this->input->post('tgl_debet');
			$data['default']['nominal'] = $this->input->post('nominal');
			$this->load->view('template', $data);
		}		
	}
	
	function update_debet($id){
		if ($this->session->userdata('login') != TRUE) redirect('pemb/index.php');
		$data['title'] = $this->title;
		$data['menu_kiri'] = 'transaksi/transaksi_left';
		$data['h2_title'] = 'Transaksi > Debet ONH > Edit Data';
		$data['main_view'] = 'transaksi_template';
		$data['data_jamaah'] = 'jamaah/jamaah_data_view';
		$data['form_action'] = site_url('pemb/update_debet_proses');
		$data['isi'] = 'pemb/onh_form_debet';
		
		//session
		$id_jamaah = $this->session->userdata('id_jamaah');
		//Data Jamaah
		$jamaah = $this->Jamaah_model->get_jamaah_by_id($id_jamaah);
		$rjamaah = $jamaah->row_array();
		//
		$data['default']['no_porsi'] = $rjamaah['no_porsi'];
		$data['default']['nama_jamaah'] = $rjamaah['nama_jamaah'];
		
		//Data Pembayaran
		// Cari data
		$pembayaran = $this->Onh_model->get_pembayaran_by_id($id);
		
		//session unt. menyimpan id
		//$this->session->set_userdata('id_pembayaran', $pembayaran->id_pembayaran);
		
		// Data untuk mengisi fild form
		$data['default']['id_pembayaran'] = $pembayaran->id_pembayaran;
		$data['default']['id_jamaah'] = $pembayaran->id_jamaah;
		$data['default']['tgl_debet'] = substr($pembayaran->tgl_bayar, 0, 10);
		$data['default']['nominal'] = number_format($pembayaran->debet,0, ',',',');
				
		$this->load->view('template', $data);
	}
	
	function debet_proses(){
		if ($this->session->userdata('login') != TRUE) redirect('pemb/index.php');
		$data['title'] = $this->title;
		$data['menu_kiri'] = 'transaksi/transaksi_left';
		$data['h2_title'] = 'Transaksi > Debet ONH > Tambah Debet';
		$data['main_view'] = 'transaksi_template';
		$data['data_jamaah'] = 'jamaah/jamaah_data_view';
		$data['nm_total'] = 'Saldo';
		$data['isi'] = 'pemb/onh_form_debet';
		$data['form_action'] = site_url('pemb/debet_proses');
		//session
		$id_jamaah = $this->session->userdata('id_jamaah');
		//Data Jamaah
		$jamaah = $this->Jamaah_model->get_jamaah_by_id($id_jamaah);
		$rjamaah = $jamaah->row_array();
		//
		$data['default']['no_porsi'] = $rjamaah['no_porsi'];
		$data['default']['nama_jamaah'] = $rjamaah['nama_jamaah'];
		
		//Saldo
		$total = $this->Onh_model->total($id_jamaah);
		$row = $total->row_array(); 
		$data['default']['total'] = $this->Fungsi_model->uang($row['total']);
		//
		
		$this->form_validation->set_rules('tgl_debet', 'Tgl Debet', 'required|min_length[10]|max_length[10]');
		$this->form_validation->set_rules('nominal', 'Nominal', 'required|min_length[2]|max_length[20]');
		//cek form
		if ($this->form_validation->run() == TRUE){
			$nominal = str_replace(",","",$this->input->post('nominal'));
			$jam = date('H:i:s');
			
			$tgl_bayar = $this->input->post('tgl_debet');
			
			if ($row['total'] > $nominal){//cek saldo
				$onh = array('id_admin' => $this->session->userdata('id_admin'),
				'id_jamaah' => $this->session->userdata('id_jamaah'),
				'id_th' => $this->input->post('id_th'),
				'tgl_bayar' => $tgl_bayar, 
				'debet' => $nominal);
				$this->Onh_model->add($onh);
				$this->session->set_flashdata('message', 'Data debet ONH berhasil disimpan!');
			}
			else{
				$this->session->set_flashdata('message', 'Transaksi Gagal! Pengambilan melebihi saldo');
			}
			redirect('pemb/debet');
		}
		
		$this->load->view('template', $data);
		
	}
	
	function add_debet(){
		if ($this->session->userdata('login') != TRUE) redirect('pemb/index.php');
		$data['title'] = $this->title;
		$data['menu_kiri'] = 'transaksi/transaksi_left';
		$data['h2_title'] = 'Transaksi > Debet ONH > Tambah Data';
		$data['main_view'] = 'transaksi_template';
		$data['data_jamaah'] = 'jamaah/jamaah_data_view';
		$data['nm_total'] = 'Saldo';
		$data['form_action'] = site_url('pemb/debet_proses');
		$data['isi'] = 'pemb/onh_form_debet';
		//session
		$id_jamaah = $this->session->userdata('id_jamaah');
		//Data Jamaah
		$jamaah = $this->Jamaah_model->get_jamaah_by_id($id_jamaah);
		$rjamaah = $jamaah->row_array();
		//
		$data['default']['no_porsi'] = $rjamaah['no_porsi'];
		$data['default']['nama_jamaah'] = $rjamaah['nama_jamaah'];
		//Saldo
		$total = $this->Onh_model->total($id_jamaah);
		$row = $total->row_array(); 
		$data['default']['total'] = $this->Fungsi_model->uang($row['total']);
		//		
		$this->load->view('template', $data);
	}
	
	function debet(){
		if ($this->session->userdata('login') != TRUE) redirect('pemb/index.php');
		$data['title'] = $this->title;
		$data['menu_kiri'] = 'transaksi/transaksi_left';
		$data['h2_title'] = 'Transaksi > Debet ONH';
		$data['main_view'] = 'transaksi_template';
		$data['data_jamaah'] = 'jamaah/jamaah_data_view';
		$data['isi'] = 'pemb/onh_debet_view';
		$data['nm_total'] = 'Total Debet';
		//session
		$id_jamaah = $this->session->userdata('id_jamaah');
		//Data Jamaah
		$jamaah = $this->Jamaah_model->get_jamaah_by_id($id_jamaah);
		$rjamaah = $jamaah->row_array();
		//
		$data['default']['no_porsi'] = $rjamaah['no_porsi'];
		$data['default']['nama_jamaah'] = $rjamaah['nama_jamaah'];
		//Total 
		$total = $this->Onh_model->total_debet($id_jamaah);
		$row = $total->row_array(); 
		$data['default']['total'] = $this->Fungsi_model->uang($row['debet']);
		
		//Data Kbih
		$uri_segment = 3;
		$offset = $this->uri->segment($uri_segment);
		 
		
		$onhs = $this->Onh_model->debet($id_jamaah, $this->limit, $offset)->result();
		$num_rows = $this->Onh_model->count_debet($id_jamaah);
		
		if ($num_rows > 0){
			$data['default']['num'] = $num_rows;
			//$data['message'] = 'ada transaksi!';
			$config['base_url'] = site_url('pemb/debet');
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
			$this->table->set_heading('Tgl', 'Nominal', 'Teller', 'Kwitansi', 'Action');
			
			$i = 0 + $offset;
			foreach ($onhs as $onh){
				$this->table->add_row($onh->tgl_bayar, '<div align="right">'. $this->Fungsi_model->uang($onh->debet).'</div>',
				$onh->nama_admin,
				anchor ('pemb/debet/'.$offset,'print',array('class' => 'print','onclick'=>"MM_openBrWindow('".base_url()."pdf/kwitansi.php?t=debet_onh&id=".$onh->id_pembayaran."','','scrollbars=yes,width=900,height=600');")), 
				anchor ('pemb/update_debet/'.$onh->id_pembayaran,'edit',array('class' => 'update')).' '.
				anchor('pemb/del_debet/'.$onh->id_pembayaran,'hapus',array('class'=> 'delete','onclick'=>"return confirm('Anda yakin akan menghapus data ini?')")));
			}
			
				$data['table'] = $this->table->generate();
		}
		else{
			$data['message'] = 'Tidak ada transaksi!';
		}
		//
		$data['link'] = array('link_add' => anchor('pemb/add_debet/','tambah data', array('class' => 'add')));
		$this->load->view('template', $data);
	}
	
	function del_kredit($id){
		if ($this->session->userdata('login') != TRUE) redirect('pemb/index.php');
		$this->Onh_model->del_onh($id);
		$this->session->set_flashdata('message', 'data berhasil dihapus');
		redirect('pemb/kredit');
	}
	
	function update_kredit_proses(){
		if ($this->session->userdata('login') != TRUE) redirect('pemb/index.php');
		$data['title'] = $this->title;
		$data['menu_kiri'] = 'transaksi/transaksi_left';
		$data['h2_title'] = 'Transaksi > Kredit ONH > Edit Data';
		$data['main_view'] = 'transaksi_template';
		$data['data_jamaah'] = 'jamaah/jamaah_data_view';
		$data['form_action'] = site_url('pemb/update_kredit_proses');
		$data['isi'] = 'pemb/pemb_form_adm_view';
		//session
		$id_jamaah = $this->session->userdata('id_jamaah');
		//Data Jamaah
		$jamaah = $this->Jamaah_model->get_jamaah_by_id($id_jamaah);
		$rjamaah = $jamaah->row_array();
		//
		$data['default']['no_porsi'] = $rjamaah['no_porsi'];
		$data['default']['nama_jamaah'] = $rjamaah['nama_jamaah'];
		
		$this->form_validation->set_rules('tgl_kredit', 'Tgl Kredit', 'required|min_length[10]|max_length[10]');
		$this->form_validation->set_rules('nominal', 'Nominal', 'required|min_length[2]|max_length[20]');
		//cek form
		if ($this->form_validation->run() == TRUE){
			$nominal = str_replace(",","",$this->input->post('nominal'));
			$jam = date('H:i:s');
			
			$tgl_bayar = $this->input->post('tgl_kredit').' '.$jam;
		
			$onh = array('id_pembayaran' => $this->input->post('id_pembayaran'),
			'tgl_bayar' => $tgl_bayar, 
			'nominal' => $nominal);
			
			$this->Onh_model->update_kredit($this->input->post('id_pembayaran'), $onh);
			
			$this->session->set_flashdata('message', 'Data ONH berhasil disimpan!');
			redirect('pemb/kredit');
		}
		else{
			$data['default']['id_pembayaran'] = $this->input->post('id_pembayaran');
			$data['default']['tgl_kredit'] = $this->input->post('tgl_kredit');
			$data['default']['nominal'] = $this->input->post('nominal');
			$this->load->view('template', $data);
		}		
	}
	
	function update_kredit($id){
		if ($this->session->userdata('login') != TRUE) redirect('pemb/index.php');
		$data['title'] = $this->title;
		$data['menu_kiri'] = 'transaksi/transaksi_left';
		$data['h2_title'] = 'Transaksi > Kredit ONH > Edit Data';
		$data['main_view'] = 'transaksi_template';
		$data['data_jamaah'] = 'jamaah/jamaah_data_view';
		$data['form_action'] = site_url('pemb/update_kredit_proses');
		$data['isi'] = 'pemb/pemb_form_adm_view';
		
		//session
		$id_jamaah = $this->session->userdata('id_jamaah');
		//Data Jamaah
		$jamaah = $this->Jamaah_model->get_jamaah_by_id($id_jamaah);
		$rjamaah = $jamaah->row_array();
		//
		$data['default']['no_porsi'] = $rjamaah['no_porsi'];
		$data['default']['nama_jamaah'] = $rjamaah['nama_jamaah'];
		
		//Data Pembayaran
		// Cari data
		$pembayaran = $this->Onh_model->get_pembayaran_by_id($id);
		
		//session unt. menyimpan id
		//$this->session->set_userdata('id_pembayaran', $pembayaran->id_pembayaran);
		
		// Data untuk mengisi fild form
		$data['default']['id_pembayaran'] = $pembayaran->id_pembayaran;
		$data['default']['id_jamaah'] = $pembayaran->id_jamaah;
		$data['default']['tgl_kredit'] = substr($pembayaran->tgl_bayar, 0, 10);
		$data['default']['nominal'] = number_format($pembayaran->nominal,0, ',',',');
				
		$this->load->view('template', $data);
	}
	
	function kredit($offset = 0){
		if ($this->session->userdata('login') != TRUE) redirect('pemb/index.php');
		$data['title'] = $this->title;
		$data['menu_kiri'] = 'transaksi/transaksi_left';
		$data['h2_title'] = 'Transaksi > Kredit ONH';
		$data['main_view'] = 'transaksi_template';
		$data['data_jamaah'] = 'jamaah/jamaah_data_view';
		$data['isi'] = 'pemb/pemb_form_adm_view';
		$data['nm_total'] = 'Total Kredit';
		//
		//session
		$id_jamaah = $this->session->userdata('id_jamaah');
		//Data Jamaah
		$jamaah = $this->Jamaah_model->get_jamaah_by_id($id_jamaah);
		$rjamaah = $jamaah->row_array();
		//
		$data['default']['no_porsi'] = $rjamaah['no_porsi'];
		$data['default']['nama_jamaah'] = $rjamaah['nama_jamaah'];
		//Total 
		$total = $this->Pemb_model->total_kredit($id_jamaah);
		$row = $total->row_array(); 
		$data['default']['total'] = $this->Fungsi_model->uang($row['kredit']);
		
		//Data Kbih
		$uri_segment = 3;
		$offset = $this->uri->segment($uri_segment);
		
		$onhs = $this->Pemb_model->kredit($id_jamaah, $this->limit, $offset)->result();
		$num_rows = $this->Pemb_model->count_kredit($id_jamaah);
		
		if ($num_rows > 0){
			$data['default']['num'] = $num_rows;
			//$data['message'] = 'ada transaksi!';
			$config['base_url'] = site_url('pemb/kredit');
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
			$this->table->set_heading('Tgl', 'Nominal', 'Teller', 'Kwitansi', 'Action');
			
			$i = 0 + $offset;
			foreach ($onhs as $onh){
				$this->table->add_row($onh->tgl_bayar, '<div align="right">'. $this->Fungsi_model->uang($onh->nominal).'</div>',
				$onh->nama_admin,
				anchor ('pemb/kredit/'.$offset.'#','print',array('class' => 'print','onclick'=>"MM_openBrWindow('".base_url()."pdf/kwitansi.php?t=onh&id=".$onh->id_pembayaran."','','scrollbars=yes,width=900,height=600');")), 
				anchor ('pemb/update_kredit/'.$onh->id_pembayaran,'edit',array('class' => 'update')).' '.
				anchor('pemb/del_kredit/'.$onh->id_pembayaran,'hapus',array('class'=> 'delete','onclick'=>"return confirm('Anda yakin akan menghapus data ini?')")));
			}
			
				$data['table'] = $this->table->generate();
		}
		else{
			$data['message'] = 'Tidak ada transaksi!';
		}
		//
		$data['link'] = array('link_add' => anchor('pemb/add_kredit/','tambah data', array('class' => 'add')));
		$this->load->view('template_umroh', $data);
		
	}
	function umroh_kredit($modul=1,$offset = 0){
		if ($this->session->userdata('login') != TRUE) redirect('pemb/index.php');
		$data['title'] = $this->title;
		$judule = $this->judul[$modul];
		$data['title'] = "Transaksi $judule";
		$data['menu_kiri'] = 'transaksi/transaksi_left_umroh';
		$data['h2_title'] = "Transaksi Umroh > Kredit $judule";
		$data['main_view'] = 'transaksi_template';
		$data['data_jamaah'] = 'jamaah/jamaah_data_view_umroh';
		$data['isi'] = 'pemb/onh_kredit_view';
		$data['nm_total'] = 'Total Kredit';

		$id_jamaah = $this->session->userdata('id_jamaah');
		$jamaah = $this->Jamaah_model->get_jamaah_by_id($id_jamaah);
		$rjamaah = $jamaah->row_array();
		//
		$data['default']['nama_jamaah'] = $rjamaah['nama_jamaah'];
		//Total total_kredit_umroh
		
		
		$total = $this->Onh_model->total_kredit_umroh($id_jamaah,$modul);
		
		$row = $total->row_array(); 
		$data['default']['total'] = $this->Fungsi_model->uang($row['kredit']);
		
		//Data Kbih
		$uri_segment = 4;
		$offset = $this->uri->segment($uri_segment);
		
		$onhs = $this->Onh_model->kredit_umroh($id_jamaah, $this->limit, $offset,$modul)->result();
		$num_rows = $this->Onh_model->count_kredit($id_jamaah);
		
		if ($num_rows > 0){
			$data['default']['num'] = $num_rows;
			//$data['message'] = 'ada transaksi!';
			$config['base_url'] = site_url('pemb/kredit');
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
			$this->table->set_heading('Tgl', 'Nominal', 'Teller', 'Kwitansi', 'Action');
			
			$i = 0 + $offset;
			foreach ($onhs as $onh){
				$this->table->add_row($onh->tgl_bayar, '<div align="right">'. $this->Fungsi_model->uang($onh->nominal).'</div>',
				$onh->nama_admin,
				anchor ('pemb/kredit/'.$offset.'#','print',array('class' => 'print','onclick'=>"MM_openBrWindow('".base_url()."pdf/kwitansi.php?t=onh&id=".$onh->id_pembayaran."','','scrollbars=yes,width=900,height=600');")), 
				anchor ('pemb/update_kredit/'.$onh->id_pembayaran,'edit',array('class' => 'update')).' '.
				anchor('pemb/del_kredit/'.$onh->id_pembayaran,'hapus',array('class'=> 'delete','onclick'=>"return confirm('Anda yakin akan menghapus data ini?')")));
			}
			
				$data['table'] = $this->table->generate();
		}
		else{
			$data['message'] = 'Tidak ada transaksi!';
		}
		//
		$data['link'] = array('link_add' => anchor("pemb/add_kredit_umroh/$modul",'Tambah data', array('class' => 'add')));
		$this->load->view('template_umroh', $data);
		
	}
	
	function add_kredit(){
		if ($this->session->userdata('login') != TRUE) redirect('pemb/index.php');
		$data['title'] = $this->title;
		$data['menu_kiri'] = 'transaksi/transaksi_left';
		$data['h2_title'] = 'Transaksi > Kredit ONH > Tambah Data';
		$data['main_view'] = 'transaksi_template';
		$data['data_jamaah'] = 'jamaah/jamaah_data_view';
		$data['form_action'] = site_url('pemb/kredit_proses');
		$data['isi'] = 'pemb/pemb_form_adm_view';
		
		//session
		$id_jamaah = $this->session->userdata('id_jamaah');
		//Data Jamaah
		$jamaah = $this->Jamaah_model->get_jamaah_by_id($id_jamaah);
		$rjamaah = $jamaah->row_array();
		//
		$data['default']['no_porsi'] = $rjamaah['no_porsi'];
		$data['default']['nama_jamaah'] = $rjamaah['nama_jamaah'];
		//Angsuran ke
		$pembayaran = $this->Pemb_model->get_last_pembayaran($id_jamaah);
		if ($pembayaran->num_rows()<1){
			$angsuran = 0;
		}
		else{
			$row = $pembayaran->row_array(); 
			$angsuran = $row['angsuran_ke'];
		}
		$data['default']['angsuran_ke'] = $angsuran + 1;
				
		$this->load->view('template', $data);
	}
	function add_kredit_umroh($modul=1){
		if ($this->session->userdata('login') != TRUE) redirect('pemb/index.php');
		$data['title'] = $this->title;
		$data['menu_kiri'] = 'transaksi/transaksi_left_umroh';
		$data['h2_title'] = 'Transaksi > Kredit ONH > Tambah Data';
		$data['main_view'] = 'transaksi_template';
		$data['data_jamaah'] = 'jamaah/jamaah_data_view_umroh';
		$data['form_action'] = site_url("pemb/kredit_proses_umroh/$modul");
		$data['isi'] = 'pemb/pemb_form_adm_view_umroh';
		
		//session
		$id_jamaah = $this->session->userdata('id_jamaah');
		//Data Jamaah
		$jamaah = $this->Jamaah_model->get_jamaah_by_id($id_jamaah);
		$rjamaah = $jamaah->row_array();
		//
		$data['default']['no_porsi'] = $rjamaah['no_porsi'];
		$data['default']['nama_jamaah'] = $rjamaah['nama_jamaah'];
		//Angsuran ke
		$pembayaran = $this->Onh_model->get_last_pembayaran($id_jamaah);
		if ($pembayaran->num_rows()<1){
			$angsuran = 0;
		}
		else{
			$row = $pembayaran->row_array(); 
			$angsuran = $row['angsuran_ke'];
		}
		$data['default']['angsuran_ke'] = $angsuran + 1;
				
		$this->load->view('template_umroh', $data);
	}
	
	function kredit_proses(){
		if ($this->session->userdata('login') != TRUE) redirect('pemb/index.php');
		$data['title'] = $this->title;
		$data['menu_kiri'] = 'transaksi/transaksi_left';
		$data['h2_title'] = 'Transaksi > Kredit ONH > Tambah Data';
		$data['main_view'] = 'transaksi_template';
		$data['data_jamaah'] = 'jamaah/jamaah_data_view';
		$data['form_action'] = site_url('pemb/kredit_proses');
		$data['isi'] = 'pemb/pemb_form_adm_view';
		
		
		//session
		$id_jamaah = $this->session->userdata('id_jamaah');
		//Data Jamaah
		$jamaah = $this->Jamaah_model->get_jamaah_by_id($id_jamaah);
		$rjamaah = $jamaah->row_array();
		//
		$data['default']['no_porsi'] = $rjamaah['no_porsi'];
		$data['default']['nama_jamaah'] = $rjamaah['nama_jamaah'];
		//
		$this->form_validation->set_rules('tgl_kredit', 'Tgl Kredit', 'required|min_length[10]|max_length[10]');
		$this->form_validation->set_rules('nominal', 'Nominal', 'required|min_length[2]|max_length[20]');
		//cek form
		if ($this->form_validation->run() == TRUE){
			$nominal = str_replace(",","",$this->input->post('nominal'));
			$jam = date('H:i:s');
			
			$tgl_bayar = $this->input->post('tgl_kredit');
		
			$onh = array(
			'id_admin' => $this->session->userdata('id_admin'),
			'id_jamaah' => $id_jamaah,
			'angsuran_ke' => $this->input->post('angsuran_ke'),
			'tgl_bayar' => $tgl_bayar,
			'nominal' => $nominal);
			
			$this->Pemb_model->add($onh);
			
			$this->session->set_flashdata('message', 'Data kredit ONH berhasil disimpan!');
			redirect('pemb/kredit');
		}
		
		$this->load->view('template', $data);
		
	}
	function kredit_proses_umroh($modul = 1){
		if ($this->session->userdata('login') != TRUE) redirect('pemb/index.php');
		$data['title'] = $this->title;
		$data['menu_kiri'] = 'transaksi/transaksi_left_umroh';
		$data['h2_title'] = 'Transaksi > Kredit ONH > Tambah Data';
		$data['main_view'] = 'transaksi_template';
		$data['data_jamaah'] = 'jamaah/jamaah_data_view';
		$data['form_action'] = site_url('pemb/kredit_proses_umroh/'.$modul);
		$data['isi'] = 'pemb/pemb_form_adm_view_umroh';
		
		
		//session
		$id_jamaah = $this->session->userdata('id_jamaah');
		//Data Jamaah
		$jamaah = $this->Jamaah_model->get_jamaah_by_id($id_jamaah);
		$rjamaah = $jamaah->row_array();
		//
		$data['default']['no_porsi'] = $rjamaah['no_porsi'];
		$data['default']['nama_jamaah'] = $rjamaah['nama_jamaah'];
		//
		$this->form_validation->set_rules('tgl_kredit', 'Tgl Kredit', 'required|min_length[10]|max_length[10]');
		$this->form_validation->set_rules('nominal', 'Nominal', 'required|min_length[2]|max_length[20]');
		//cek form
		if ($this->form_validation->run() == TRUE){
			$nominal = str_replace(",","",$this->input->post('nominal'));
			$jam = date('H:i:s');
			
			$tgl_bayar = $this->input->post('tgl_kredit');
		
			$onh = array(
			'id_admin' => $this->session->userdata('id_admin'),
			'id_jamaah' => $id_jamaah,
			'angsuran_ke' => $this->input->post('angsuran_ke'),
			'tgl_bayar' => $tgl_bayar,
			'jenis'=>$modul,
			'nominal' => $nominal);
			
			$this->Onh_model->add_umroh($onh);
			
			$this->session->set_flashdata('message', 'Data kredit berhasil disimpan!');
			redirect("pemb/umroh_kredit/$modul");
		}
		
		$this->load->view('template_umroh', $data);
		
	}
	
	function get_last_ten_onh($offset = 0){
		if ($this->session->userdata('login') != TRUE) redirect('pemb/index.php');
		$data['title'] = $this->title;
		$data['menu_kiri'] = 'transaksi/transaksi_left';
		$data['h2_title'] = 'Transaksi > Data ONH';
		$data['main_view'] = 'transaksi_template';
		$data['data_jamaah'] = 'jamaah/jamaah_data_view';
		$data['isi'] = 'pemb/onh_view';
		$data['nm_total'] = 'Saldo';
		
		//session
		$id_jamaah = $this->session->userdata('id_jamaah');
		//Data Jamaah
		$jamaah = $this->Jamaah_model->get_jamaah_by_id($id_jamaah);
		$rjamaah = $jamaah->row_array();
		//
		$data['default']['no_porsi'] = $rjamaah['no_porsi'];
		$data['default']['nama_jamaah'] = $rjamaah['nama_jamaah'];
		//Total 
		$total = $this->Onh_model->total($id_jamaah);
		$row = $total->row_array(); 
		$data['default']['total'] = $this->Fungsi_model->uang($row['total']);
		
		//Data Onh
		$uri_segment = 3;
		$offset = $this->uri->segment($uri_segment);
		
		$onhs = $this->Onh_model->get_last_ten_onh($id_jamaah, $this->limit, $offset)->result();
		$num_rows = $this->Onh_model->count_all_num_rows($id_jamaah);
		
		if ($num_rows > 0){
			$data['default']['num'] = $num_rows;
			//$data['message'] = 'ada transaksi!';
			$config['base_url'] = site_url('pemb/get_last_ten_onh');
			$config['total_rows'] = $num_rows;
			$config['per_page']	= $this->limit;
			$config['uri_segment'] = $uri_segment;
			$this->pagination->initialize($config);//.
			$data['pagination'] = $this->pagination->create_links();
			$data['open_form'] = '<form target=_blank method=post action='.base_url().'pdf/print_buku_onh.php> 
			<input type=hidden name=id_jamaah value='.$id_jamaah.'>';
			$tmpl = array('table_open' => '<table class="table" border="1">', 
			'row_alt_start' => '<tr class="zebra">', 
			'row_alt_end' => '</tr>',
			'table_close' => '</table>');
			
			$this->table->set_template($tmpl);
			$this->table->set_empty("&nbsp;");
			$this->table->set_heading('No','Tgl', 'Debet', 'Kredit', 'Teller', '<input type=submit value=print>');
			
			$i = 0 + $offset;
			$saldo = $row['total'];
			$no = $num_rows - $offset;
			foreach ($onhs as $onh){
				$this->table->add_row('<div align="right">'.$onh->id_pembayaran.'</div>', $onh->tgl_bayar, 
				'<div align="right">'.$this->Fungsi_model->uang($onh->debet).'</div>', 
				'<div align="right">'. $this->Fungsi_model->uang($onh->nominal).'</div>', 
				$onh->nama_admin,
				'<div align="center"><input type=checkbox name=id[] value='.$onh->id_pembayaran.'/></div>');
			$no--;
			//$saldo = ($saldo + $onh->debet) - $onh->nominal;
			}
			
				$data['table'] = $this->table->generate();
				$data['close_form'] ='</form>'; 
		}
		else{
			$data['message'] = 'Tidak ada transaksi!';
		}
		//
		$this->load->view('template', $data);
		
	}
	
}
/* End of file welcome.php */
/* Location: ./system/application/controllers/welcome.php */