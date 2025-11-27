<?php
class Adm extends CI_Controller {

	function __construct(){
		parent::__construct();	
		$this->load->model('Fungsi_model', '', TRUE);
		$this->load->model('Jamaah_model', '', TRUE);
		$this->load->model('Adm_model', '', TRUE);
	}
	var $title = 'administrasi';
	var $limit = 10;
	
	function index(){
		if ($this->session->userdata('login') == TRUE){
			$this->get_last_ten_adm();		
		}
		else{
			$this->load->view('login/login_view');
		}
	}
	
	function update_kredit_proses(){
		$data['title'] = $this->title;
		$data['menu_kiri'] = 'transaksi/transaksi_left';
		$data['h2_title'] = 'Transaksi > Edit KBIH';
		$data['main_view'] = 'transaksi_template';
		$data['data_jamaah'] = 'jamaah/jamaah_data_view';
		$data['form_action'] = site_url('adm/update_kredit_proses');
		$data['isi'] = 'adm/adm_form_kredit';
		//session
		$id_jamaah = $this->session->userdata('id_jamaah');
		//Data Jamaah
		$qrJamaah = $this->Jamaah_model->get_jamaah_by_id($id_jamaah);
		$rJamaah = $qrJamaah->row_array();
		//
		$data['default']['id_jamaah'] = $rJamaah['id_jamaah'];
		$data['default']['no_porsi'] = $rJamaah['no_porsi'];
		$data['default']['nama_jamaah'] = $rJamaah['nama_jamaah'];
		$data['default']['status'] = $rJamaah['status'];
		
		$this->form_validation->set_rules('tgl_bayar', 'Tgl Bayar', 'required|min_length[10]|max_length[10]');
		$this->form_validation->set_rules('nominal', 'Nominal', 'required|min_length[2]|max_length[20]');
		$this->form_validation->set_rules('keterangan', 'Keterangan', 'required');
		//cek form
		if ($this->form_validation->run() == TRUE){
			$nominal = str_replace(",","",$this->input->post('nominal'));
			$jam = date('H:i:s');
			$keterangan = $this->input->post('keterangan');
			$tgl_bayar = $this->input->post('tgl_bayar').' '.$jam;
		
			$adm = array(
			'id_adm' => $this->input->post('id_adm'),
			'tgl_bayar' => $tgl_bayar, 
			'nominal' => $nominal,
			'keterangan' => $keterangan);
			
			$this->Adm_model->update_kredit($this->input->post('id_adm'), $adm);
			
			$this->session->set_flashdata('message', 'Data KBIH berhasil disimpan!');
			redirect('adm');
		}
		else{
			$data['default']['id_adm'] = $this->input->post('id_adm');
			$data['default']['tgl_bayar'] = $this->input->post('tgl_bayar');
			$data['default']['nominal'] = $this->input->post('nominal');
			$data['default']['keterangan'] = $this->input->post('keterangan');
			$this->load->view('template', $data);
		}		
	}
	
	function update_kredit($id){
		$data['title'] = $this->title;
		$data['menu_kiri'] = 'transaksi/transaksi_left';
		$data['h2_title'] = 'Transaksi > Edit Data KBIH';
		$data['main_view'] = 'transaksi_template';
		$data['data_jamaah'] = 'jamaah/jamaah_data_view';
		$data['form_action'] = site_url('adm/update_kredit_proses');
		$data['isi'] = 'adm/adm_form_kredit';
		$data['nm_total'] = 'Saldo KBIH';
		//session
		$id_jamaah = $this->session->userdata('id_jamaah');
		//Data Jamaah
		$qrJamaah = $this->Jamaah_model->get_jamaah_by_id($id_jamaah);
		$rJamaah = $qrJamaah->row_array();
		//
		$data['default']['id_jamaah'] = $rJamaah['id_jamaah'];
		$data['default']['no_porsi'] = $rJamaah['no_porsi'];
		$data['default']['nama_jamaah'] = $rJamaah['nama_jamaah'];
		$data['default']['status'] = $rJamaah['status'];
		
		//Data Pembayaran
		// Cari data
		$pembayaran = $this->Adm_model->get_adm_by_id($id);
		
		//session unt. menyimpan id
		//$this->session->set_userdata('id_pembayaran', $pembayaran->id_pembayaran);
		
		// Data untuk mengisi fild form
		$data['default']['id_adm'] = $pembayaran->id_adm;
		$data['default']['tgl_bayar'] = substr($pembayaran->tgl_bayar, 0, 10);
		$data['default']['nominal'] = number_format($pembayaran->nominal,0, ',',',');
		$data['default']['keterangan'] = $pembayaran->keterangan;
				
		$this->load->view('template', $data);
	}
	
	function delete($id){
		$this->Adm_model->del_adm($id);
		$this->session->set_flashdata('message', 'data berhasil dihapus');
		redirect('adm');
	}
	
	function get_last_ten_adm($offset = 0){
		$data['title'] = $this->title;
		$data['menu_kiri'] = 'transaksi/transaksi_left';
		$data['h2_title'] = 'Transaksi > Data Administrasi';
		$data['main_view'] = 'transaksi_template';
		$data['data_jamaah'] = 'jamaah/jamaah_data_view';
		$data['isi'] = 'adm/adm_view';
		$data['nm_total'] = 'Total Adm.';
		
		//session
		$id = $this->session->userdata('id_jamaah');
		//Data Jamaah
		$qrJamaah = $this->Jamaah_model->get_jamaah_by_id($id);
		$rJamaah = $qrJamaah->row_array();
		//
		$data['default']['id_jamaah'] = $rJamaah['id_jamaah'];
		$data['default']['no_porsi'] = $rJamaah['no_porsi'];
		$data['default']['nama_jamaah'] = $rJamaah['nama_jamaah'];
		$data['default']['status'] = $rJamaah['status'];
		//Total 
		$total = $this->Adm_model->total($id);
		$row = $total->row_array(); 
		$data['default']['total'] = $this->Fungsi_model->uang($row['total']);
		
		//Data Kbih
		$uri_segment = 3;
		$offset = $this->uri->segment($uri_segment);
		
		$adms = $this->Adm_model->get_last_ten_adm($id, $this->limit, $offset)->result();
		$num_rows = $this->Adm_model->count_all_num_rows($id);
		$data['default']['num'] = $num_rows;
			//$data['message'] = 'ada transaksi!';
			$config['base_url'] = site_url('adm/get_last_ten_adm');
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
			$this->table->set_heading('No', 'Tgl', 'Nominal', 'Keterangan', 'Teller', 'Kwitansi', 'Action');
			
			$i = 0 + $offset;
			foreach ($adms as $adm){
				$this->table->add_row($adm->id_adm,
				$adm->tgl_bayar, 
				'<div align="right">'. $this->Fungsi_model->uang($adm->nominal).'</div>', 
				$adm->keterangan,
				$adm->nama_admin,
				anchor ('adm/get_last_ten_adm/'.$offset,'print',array('class' => 'print','onclick'=>"MM_openBrWindow('".base_url()."pdf/kwitansi.php?t=adm&id=".$adm->id_adm."','','scrollbars=yes,width=900,height=600');")),
				anchor ('adm/update_kredit/'.$adm->id_adm,'edit',array('class' => 'update')).' '.
				anchor('adm/delete/'.$adm->id_adm,'hapus',array('class'=> 'delete','onclick'=>"return confirm('Anda yakin akan menghapus data ini?')")));
			}
			
				$data['table'] = $this->table->generate();
		
		//
		$this->load->view('template', $data);
		
	}
	
	function add_kredit(){
		$data['title'] = $this->title;
		$data['h2_title'] = 'Transaksi > Data Administrasi';
		$data['menu_kiri'] = 'transaksi/transaksi_left';
		$data['main_view'] = 'transaksi_template';
		$data['data_jamaah'] = 'jamaah/jamaah_data_view';
		$data['form_action'] = site_url('adm/add_kredit_proses');
		$data['isi'] = 'adm/adm_form_kredit';
		
		//session
		$id_jamaah = $this->session->userdata('id_jamaah');
		//Data Jamaah
		$jamaah = $this->Jamaah_model->get_jamaah_by_id($id_jamaah);
		$rjamaah = $jamaah->row_array();
		//
		$data['default']['no_porsi'] = $rjamaah['no_porsi'];
		$data['default']['nama_jamaah'] = $rjamaah['nama_jamaah'];
		//Total 
				
		$this->load->view('template', $data);
	}
	
	function add_kredit_proses(){
		$data['title'] = $this->title;
		$data['h2_title'] = 'Transaksi > Data KBIH';
		$data['menu_kiri'] = 'transaksi/transaksi_left';
		$data['main_view'] = 'transaksi_template';
		$data['data_jamaah'] = 'jamaah/jamaah_data_view';
		$data['form_action'] = site_url('adm/add_proses');
		$data['isi'] = 'adm/adm_form_kredit';
		//session
		$id_jamaah = $this->session->userdata('id_jamaah');
		//Data Jamaah
		$jamaah = $this->Jamaah_model->get_jamaah_by_id($id_jamaah);
		$rjamaah = $jamaah->row_array();
		//
		$data['default']['no_porsi'] = $rjamaah['no_porsi'];
		$data['default']['nama_jamaah'] = $rjamaah['nama_jamaah'];
		//
		$this->form_validation->set_rules('tgl_bayar', 'Tgl Bayar', 'required|min_length[10]|max_length[10]');
		$this->form_validation->set_rules('nominal', 'Nominal', 'required|min_length[2]|max_length[20]');
		$this->form_validation->set_rules('keterangan', 'Keterangan', 'required');
		//cek form
		if ($this->form_validation->run() == TRUE){
			//var input
			$nominal = str_replace(",","",$this->input->post('nominal'));
			$keterangan = $this->input->post('keterangan');
			
			//input ke db
			$adm = array('id_admin' => $this->session->userdata('id_admin'),
			'id_jamaah' => $id_jamaah,
			'tgl_bayar' => $this->input->post('tgl_bayar'), 
			'nominal' => $nominal,
			'keterangan' => $keterangan);
			
			$this->Adm_model->insert($adm);
			
			$this->session->set_flashdata('message', 'Data administrasi berhasil disimpan!');
			redirect('adm');
		}
		else{
			$data['default']['tgl_bayar'] = $this->input->post('tgl_bayar');
			$data['default']['nominal'] = $this->input->post('nominal');
			$data['default']['keterangan'] = $this->input->post('keterangan');
		}	
		
		$this->load->view('template', $data);
		
	}
	
}
/* End of file welcome.php */
/* Location: ./system/application/controllers/welcome.php */