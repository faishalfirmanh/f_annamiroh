<?php
class kbih extends CI_Controller {

	function __construct(){
		parent::__construct();	
		$this->load->model('Jamaah_model', '', TRUE);
		$this->load->model('Kbih_model', '', TRUE);
	}
	var $title = 'kbih';
	var $limit = 10;
	
	function index(){
		if ($this->session->userdata('login') == TRUE){
			$this->data_kbih();		
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
		$data['nm_total'] = 'Saldo KBIH';
		$data['form_action'] = site_url('kbih/update_kredit_proses');
		$data['isi'] = 'kbih/kbih_form_kredit';
		//session
		$id_jamaah = $this->session->userdata('id_jamaah');
		//Data Jamaah
		$jamaah = $this->Jamaah_model->get_jamaah_by_id($id_jamaah);
		$rjamaah = $jamaah->row_array();
		//
		$data['default']['no_porsi'] = $rjamaah['no_porsi'];
		$data['default']['nama_jamaah'] = $rjamaah['nama_jamaah'];
		$data['default']['status'] = $rjamaah['status'];
		//Total 
		$total = $this->Kbih_model->total($id_jamaah);
		$row = $total->row_array(); 
		$data['default']['total'] = $this->Kbih_model->uang($row['total']);
		
		$this->form_validation->set_rules('tgl_bayar', 'Tgl Bayar', 'required|min_length[10]|max_length[10]');
		$this->form_validation->set_rules('nominal', 'Nominal', 'required|min_length[2]|max_length[20]');
		//cek form
		if ($this->form_validation->run() == TRUE){
			$nominal = str_replace(",","",$this->input->post('nominal'));
			$jam = date('H:i:s');
			
			$tgl_bayar = $this->input->post('tgl_bayar').' '.$jam;
		
			$kbih = array('id_kbih' => $this->input->post('id_kbih'),
			'tgl_bayar' => $tgl_bayar, 
			'nominal' => $nominal);
			
			$this->Kbih_model->update_kredit($this->input->post('id_kbih'), $kbih);
			
			$this->session->set_flashdata('message', 'Data KBIH berhasil disimpan!');
			redirect('kbih');
		}
		else{
			$data['default']['id_kbih'] = $this->input->post('id_kbih');
			$data['default']['tgl_bayar'] = $this->input->post('tgl_bayar');
			$data['default']['nominal'] = $this->input->post('nominal');
			$this->load->view('template', $data);
		}		
	}
	
	function update_kredit($id){
		$data['title'] = $this->title;
		$data['menu_kiri'] = 'transaksi/transaksi_left';
		$data['h2_title'] = 'Transaksi > Edit Data KBIH';
		$data['main_view'] = 'transaksi_template';
		$data['data_jamaah'] = 'jamaah/jamaah_data_view';
		$data['nm_total'] = 'Saldo KBIH';
		$data['form_action'] = site_url('kbih/update_kredit_proses');
		$data['isi'] = 'kbih/kbih_form_kredit';
		$data['nm_total'] = 'Saldo KBIH';
		//session
		//session
		$id_jamaah = $this->session->userdata('id_jamaah');
		//Data Jamaah
		$jamaah = $this->Jamaah_model->get_jamaah_by_id($id_jamaah);
		$rjamaah = $jamaah->row_array();
		//
		$data['default']['no_porsi'] = $rjamaah['no_porsi'];
		$data['default']['nama_jamaah'] = $rjamaah['nama_jamaah'];
		$data['default']['status'] = $rjamaah['status'];
		//Total 
		$total = $this->Kbih_model->total($id_jamaah);
		$row = $total->row_array(); 
		$data['default']['total'] = $this->Kbih_model->uang($row['total']);
		
		//session unt. menyimpan id
		//$this->session->set_userdata('id_pembayaran', $pembayaran->id_pembayaran);
		
		// Data untuk mengisi fild form
		$pembayaran = $this->Kbih_model->get_kbih_by_id($id);
		$data['default']['id_kbih'] = $pembayaran->id_kbih;
		$data['default']['id_jamaah'] = $pembayaran->id_jamaah;
		$data['default']['tgl_bayar'] = substr($pembayaran->tgl_bayar, 0, 10);
		$data['default']['nominal'] = number_format($pembayaran->nominal,0, ',',',');
				
		$this->load->view('template', $data);
	}
	
	function delete($id){
		$this->Kbih_model->del_kbih($id);
		$this->session->set_flashdata('message', 'data berhasil dihapus');
		redirect('kbih');
	}
	
	function add(){
		$data['title'] = $this->title;
		$data['h2_title'] = 'Transaksi > Data KBIH';
		$data['menu_kiri'] = 'transaksi/transaksi_left';
		$data['main_view'] = 'transaksi_template';
		$data['data_jamaah'] = 'jamaah/jamaah_data_view';
		$data['nm_total'] = 'Saldo KBIH';
		$data['form_action'] = site_url('kbih/add_proses');
		$data['isi'] = 'kbih/kbih_form_kredit';
		
		//session
		$id_jamaah = $this->session->userdata('id_jamaah');
		//Data Jamaah
		$jamaah = $this->Jamaah_model->get_jamaah_by_id($id_jamaah);
		$rjamaah = $jamaah->row_array();
		//
		$data['default']['no_porsi'] = $rjamaah['no_porsi'];
		$data['default']['nama_jamaah'] = $rjamaah['nama_jamaah'];
		$data['default']['status'] = $rjamaah['status'];
		//Total 
		$total = $this->Kbih_model->total($id_jamaah);
		$row = $total->row_array(); 
		$data['default']['total'] = $this->Kbih_model->uang($row['total']);
				
		$this->load->view('template', $data);
	}
	
	function add_proses(){
		$data['title'] = $this->title;
		$data['h2_title'] = 'Transaksi > Data KBIH';
		$data['menu_kiri'] = 'transaksi/transaksi_left';
		$data['main_view'] = 'transaksi_template';
		$data['data_jamaah'] = 'jamaah/jamaah_data_view';
		$data['nm_total'] = 'Saldo KBIH';
		$data['form_action'] = site_url('kbih/add_proses');
		$data['isi'] = 'kbih/kbih_form_kredit';
		//session
		$id_jamaah = $this->session->userdata('id_jamaah');
		//Data Jamaah
		$jamaah = $this->Jamaah_model->get_jamaah_by_id($id_jamaah);
		$rjamaah = $jamaah->row_array();
		//
		$data['default']['no_porsi'] = $rjamaah['no_porsi'];
		$data['default']['nama_jamaah'] = $rjamaah['nama_jamaah'];
		$data['default']['status'] = $rjamaah['status'];
		//Total 
		$total = $this->Kbih_model->total($id_jamaah);
		$row = $total->row_array(); 
		$data['default']['total'] = $this->Kbih_model->uang($row['total']);
		//
		$this->form_validation->set_rules('tgl_bayar', 'Tgl Bayar', 'required|min_length[10]|max_length[10]');
		$this->form_validation->set_rules('nominal', 'Nominal', 'required|min_length[2]|max_length[20]');
		//cek form
		if ($this->form_validation->run() == TRUE){
			$nominal = str_replace(",","",$this->input->post('nominal'));
			$kbih = array(
			'id_admin' => $this->session->userdata('id_admin'),
			'id_jamaah' => $id_jamaah,
			'tgl_bayar' => $this->input->post('tgl_bayar'), 
			'nominal' => $nominal);
			
			$this->Kbih_model->insert($kbih);
			
			$this->session->set_flashdata('message', 'Data jamaah berhasil disimpan!');
			redirect('kbih');
		}
		else{
			$data['default']['tgl_bayar'] = $this->input->post('tgl_bayar');
			$data['default']['nominal'] = $this->input->post('nominal');
		}	
		
		$this->load->view('template', $data);
		
	}
	
	function data_kbih($offset = 0){
		$data['title'] = $this->title;
		$data['menu_kiri'] = 'transaksi/transaksi_left';
		$data['h2_title'] = 'Transaksi > Data KBIH';
		$data['main_view'] = 'transaksi_template';
		$data['data_jamaah'] = 'jamaah/jamaah_data_view';
		$data['nm_total'] = 'Saldo KBIH';
		$data['isi'] = 'kbih/kbih_view';
		$data['nm_total'] = 'Saldo KBIH';
		
		//session
		$id_jamaah = $this->session->userdata('id_jamaah');
		//Data Jamaah
		$jamaah = $this->Jamaah_model->get_jamaah_by_id($id_jamaah);
		$rjamaah = $jamaah->row_array();
		//
		$data['default']['no_porsi'] = $rjamaah['no_porsi'];
		$data['default']['nama_jamaah'] = $rjamaah['nama_jamaah'];
		$data['default']['status'] = $rjamaah['status'];
		//Total 
		$total = $this->Kbih_model->total($id_jamaah);
		$row = $total->row_array(); 
		$data['default']['total'] = $this->Kbih_model->uang($row['total']);
		
		//Data Kbih
		$uri_segment = 3;
		$offset = $this->uri->segment($uri_segment);
		
		$kbihs = $this->Kbih_model->get_last_ten_kbih($id_jamaah, $this->limit, $offset)->result();
		$num_rows = $this->Kbih_model->count_all_num_rows($id_jamaah);
		$data['default']['num'] = $num_rows;
			//$data['message'] = 'ada transaksi!';
			$config['base_url'] = site_url('kbih/data_kbih');
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
			$this->table->set_heading('No', 'Tgl', 'Nominal', 'Teller', 'Kwitansi', 'Action');
			
			$i = 0 + $offset;
			foreach ($kbihs as $kbih){
				$this->table->add_row($kbih->id_kbih,
				$kbih->tgl_bayar, 
				'<div align="right">'. $this->Kbih_model->uang($kbih->nominal).'</div>', 
				$kbih->nama_admin,
				anchor ('kbih/data_kbih/'.$offset,'print',array('class' => 'print','onclick'=>"MM_openBrWindow('".base_url()."pdf/kwitansi.php?t=kbih&id=".$kbih->id_kbih."','','scrollbars=yes,width=900,height=600');")),
				anchor ('kbih/update_kredit/'.$kbih->id_kbih,'edit',array('class' => 'update')).' '.
				anchor('kbih/delete/'.$kbih->id_kbih,'hapus',array('class'=> 'delete','onclick'=>"return confirm('Anda yakin akan menghapus data ini?')")));
			}
			
				$data['table'] = $this->table->generate();
		
		//
		$this->load->view('template', $data);
		
	}
	
}
/* End of file welcome.php */
/* Location: ./system/application/controllers/welcome.php */