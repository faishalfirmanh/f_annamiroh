<?php
class Allt extends CI_Controller {

	function __construct(){
		parent::__construct();	
		$this->load->model('Fungsi_model', '', TRUE);
		$this->load->model('Jamaah_model', '', TRUE);
		$this->load->model('Allt_model', '', TRUE);
	}
	var $title = 'All Pembayaran';
	var $limit = 10;
	
	function index(){
		if ($this->session->userdata('login') == TRUE){
			$this->get_last_ten_allt();		
		}
		else{
			$this->load->view('login/login_view');
		}
	}
	function restore(){
		$query = $this->db->query("select * from semua_data1_log order by waktu");
		foreach ($query->result() as $row)
		{
			echo $row->waktu.' | ';
			switch ($row->aksi) {
			// case "Tambah":
				
				// $this->db->insert('semua_data1',(array)json_decode($row->data_baru));
				// echo 'data masuk<br>';
				// break;
			// case "Update":
				// $datanya = (array)json_decode($row->data_lama);
				// $this->db->where('id_semua_data',$datanya['id_semua_data']);
				// $databaru = (array)json_decode($row->data_baru);
				// unset($databaru['id']);
				// $this->db->update('semua_data1',$databaru);
				// echo 'data diupdate <br>';
				// break;
			// case "Delete":
				// $datanya = (array)json_decode($row->data_baru);
				// $this->db->delete('semua_data1', array('id_semua_data' => $datanya['id_semua_data'])); 
				// echo 'data dihapus<br>';
				// break;
			// ...
			default:
				// code to be executed if n is different from all labels;
		}
			//print_r((array)json_decode($row->data_baru));
			echo '<br>';
		}
	}
	function update_kredit_proses(){
		$data['title'] = $this->title;
		$data['menu_kiri'] = 'allt/allt_left';
		$data['h2_title'] = 'Semua Pembayaran';
		$data['main_view'] = 'transaksi_template';
		$data['isi'] = 'allt/allt_view';
		$data['form_action'] = site_url('allt/update_kredit_proses');
		$data['isi'] = 'allt/allt_form_kredit';
				
		$this->form_validation->set_rules('tgl_bayar', 'Tgl Bayar', 'required|min_length[10]|max_length[10]');
		$this->form_validation->set_rules('nominal', 'Nominal', 'required|min_length[2]|max_length[20]');
		$this->form_validation->set_rules('keterangan', 'Keterangan', 'required|min_length[2]');
		$this->form_validation->set_rules('nama', 'Nama Jamaah', 'required|min_length[2]|max_length[30]');
		$this->form_validation->set_rules('penyetor', 'Penyetor', 'required|min_length[2]|max_length[30]');
		//cek form
		if ($this->form_validation->run() == TRUE){
			//var input
			$nominal = str_replace(",","",$this->input->post('nominal'));
			$nama = $this->input->post('nama');
			$keterangan = $this->input->post('keterangan');
			$penyetor = $this->input->post('penyetor');
			
			//input ke db
			$allt = array(
			'id_admin' => $this->session->userdata('id_admin'),
			'tgl_bayar' => $this->input->post('tgl_bayar'), 
			'nominal' => $nominal,
			'nama' => $nama,
			'keterangan' => $keterangan,
			'penyetor' => $penyetor,
			'mata_uang'=> $this->input->post('mata_uang'));
			
			$this->Allt_model->update_kredit($this->input->post('id_all'), $allt);
			
			$this->session->set_flashdata('message', 'Data KBIH berhasil disimpan!');
			redirect('allt');
		}
		else{
			$data['default']['id_semua_data'] = $this->input->post('id_semua_data');
			$data['default']['tgl_bayar'] = $this->input->post('tgl_bayar');
			$data['default']['nominal'] = $this->input->post('nominal');
			$this->load->view('template', $data);
		}		
	}
	
	function update_kredit($id){
		$data['title'] = $this->title;
		$data['menu_kiri'] = 'allt/allt_left';
		$data['h2_title'] = 'Semua Pembayaran';
		$data['main_view'] = 'transaksi_template';
		$data['isi'] = 'allt/allt_view';
		$data['form_action'] = site_url('allt/update_kredit_proses');
		$data['isi'] = 'allt/allt_form_kredit';
				
		//Data Pembayaran
		// Cari data
		$pembayaran = $this->Allt_model->get_all_by_id($id);
		
		//session unt. menyimpan id
		//$this->session->set_userdata('id_pembayaran', $pembayaran->id_pembayaran);
		
		// Data untuk mengisi fild form
		$data['default']['id_all'] = $pembayaran->id_semua_data;
		$data['default']['tgl_bayar'] = substr($pembayaran->tgl_bayar, 0, 10);
		$data['default']['nama'] = $pembayaran->nama;
		$data['default']['nominal'] = number_format($pembayaran->nominal,0, ',',',');
		$data['default']['keterangan'] = $pembayaran->keterangan;
		$data['default']['penyetor'] = $pembayaran->penyetor;
		$data['default']['mata_uang'] = $pembayaran->mata_uang;
				
		$this->load->view('template', $data);
	}
	
	function delete($id){
		$this->Allt_model->del_allt($id);
		$this->session->set_flashdata('message', 'data berhasil dihapus');
		redirect('allt');
	}
	
	function add_kredit_proses(){
		$data['title'] = $this->title;
		$data['menu_kiri'] = 'allt/allt_left';
		$data['h2_title'] = 'Semua Pembayaran';
		$data['main_view'] = 'transaksi_template';
		$data['isi'] = 'allt/allt_view';
		$data['form_action'] = site_url('allt/add_kredit_proses');
		$data['isi'] = 'allt/allt_form_kredit';
		
		//
		$this->form_validation->set_rules('tgl_bayar', 'Tgl Bayar', 'required|min_length[10]|max_length[10]');
		$this->form_validation->set_rules('nominal', 'Nominal', 'required|min_length[2]|max_length[20]');
		$this->form_validation->set_rules('keterangan', 'Keterangan', 'required|min_length[2]');
		$this->form_validation->set_rules('nama', 'Nama Jamaah', 'required|min_length[2]|max_length[30]');
		$this->form_validation->set_rules('penyetor', 'Penyetor', 'required|min_length[2]|max_length[30]');
		//cek form
		if ($this->form_validation->run() == TRUE){
			//var input
			$nominal = str_replace(",","",$this->input->post('nominal'));
			$nama = $this->input->post('nama');
			$keterangan = $this->input->post('keterangan');
			$penyetor = $this->input->post('penyetor');
			
			//input ke db
			$allt = array(
			'id_admin' => $this->session->userdata('id_admin'),
			'tgl_bayar' => $this->input->post('tgl_bayar'), 
			'nominal' => $nominal,
			'nama' => $nama,
			'keterangan' => $keterangan,
			'penyetor' => $penyetor);
			
			$this->Allt_model->insert($allt);
			
			$this->session->set_flashdata('message', 'Data administrasi berhasil disimpan!');
			redirect('allt');
		}
		else{
			$data['default']['tgl_bayar'] = $this->input->post('tgl_bayar');
			$data['default']['nominal'] = $this->input->post('nominal');
		}	
		
		$this->load->view('template', $data);
		
	}
	
	function add_kredit(){
		$data['title'] = $this->title;
		$data['menu_kiri'] = 'allt/allt_left';
		$data['h2_title'] = 'Semua Pembayaran';
		$data['main_view'] = 'transaksi_template';
		$data['isi'] = 'allt/allt_view';
		$data['form_action'] = site_url('allt/add_kredit_proses');
		$data['isi'] = 'allt/allt_form_kredit';
				
		$this->load->view('template', $data);
	}
	
	function get_last_ten_allt($offset = 0){
		$data['title'] = $this->title;
		$data['menu_kiri'] = 'allt/allt_left';
		$data['h2_title'] = 'Semua Pembayaran';
		$data['main_view'] = 'transaksi_template';
		$data['isi'] = 'allt/allt_view';
				
		//Data Kbih
		$uri_segment = 3;
		$offset = $this->uri->segment($uri_segment);
		
		$allts = $this->Allt_model->get_last_ten_allt($this->limit, $offset)->result();
		$num_rows = $this->Allt_model->count_all_num_rows();
		$data['default']['num'] = $num_rows;
			//$data['message'] = 'ada transaksi!';
			$config['base_url'] = site_url('allt/get_last_ten_allt');
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
			$this->table->set_heading('No', 'Tgl', 'Nama Jamaah', 'Nominal', 'Keterangan', 'Penyetor', 'Teller', 'Kwitansi', 'Action');
			
			$i = 0 + $offset;
			foreach ($allts as $allt){
				$label = $allt->mata_uang ==0?"Rp":"$";
				$this->table->add_row($allt->id_semua_data,
				$allt->tgl_bayar, 
				$allt->nama,
				$label.'<div align="right">'. $this->Fungsi_model->uang($allt->nominal).'</div>', 
				$allt->keterangan,
				$allt->penyetor,
				$allt->nama_admin,
				anchor ('allt/get_last_ten_allt/'.$offset,'print',array('class' => 'print','onclick'=>"MM_openBrWindow('".base_url()."pdf/kwitansi.php?t=allt&id=".$allt->id_semua_data."','','scrollbars=yes,width=900,height=600');")),
				anchor ('allt/update_kredit/'.$allt->id_semua_data,'edit',array('class' => 'update')).' '.
				anchor('allt/delete/'.$allt->id_semua_data,'hapus',array('class'=> 'delete','onclick'=>"return confirm('Anda yakin akan menghapus data ini?')")));
			}
			
				$data['table'] = $this->table->generate();
		
		//
		$this->load->view('template', $data);
		
	}
	
}
/* End of file welcome.php */
/* Location: ./system/application/controllers/welcome.php */