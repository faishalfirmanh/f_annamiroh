<?php
class Admin extends CI_Controller {

	function __construct(){
		parent::__construct();	
		$this->load->model('Admin_model', '', TRUE);
		$this->load->model('Laporan_model', '', TRUE);
	}
	var $title = 'admin';
	var $limit = 20;
	
	function index(){
		if ($this->session->userdata('login') == TRUE){
			$this->get_user();		
		}
		else{
			$this->load->view('login/login_view');
		}
	}
	
	function get_user(){
		if ($this->session->userdata('login') != TRUE) redirect('onh/index.php');
		$data['title'] = $this->title;
		$data['h2_title'] = 'Halaman Depan';
		$data['main_view'] = 'admin_template';
		$data['isi'] = 'admin/admin_view';
		$data['form_action'] = site_url('admin/proses_cari_jamaah');
		$data['kanan_view'] = 'admin/admin_kanan';
		$this->load->view('template', $data);
		
	}
	
	function goto_transaksi($id){
		if ($this->session->userdata('login') != TRUE) 		
			redirect('onh/index.php');
		$id_jamaah = array('id_jamaah' => $id);
		$this->session->set_userdata($id_jamaah);
		redirect('transaksi/get_jamaah/'.$id);
	}
	function goto_umroh($id){
		if ($this->session->userdata('login') != TRUE) 		
			redirect('onh/index.php');
		$id_jamaah = array('id_jamaah' => $id);
		$this->session->set_userdata($id_jamaah);
		redirect('transaksi/umroh/'.$id);
	}
	
	function proses_cari_jamaah(){
				
		if ($this->session->userdata('login') != TRUE) redirect('onh/index.php');
		
		$kec = $this->input->post('kecamatan');
		$kab = $this->input->post('kabupaten');
		$th = $this->input->post('th');
		
		$data = array('kecamatan' =>$kec, 'kabupaten'=>$kab, 'tahun'=>$th);
		
		$this->session->set_userdata($data);
		
		redirect('admin/result_jamaah');
		
	}
	
	function result_jamaah(){
		if ($this->session->userdata('login') != TRUE) redirect('onh/index.php');
		$data['title'] = $this->title;
		$data['h2_title'] = 'Halaman Depan';
		$data['main_view'] = 'admin_template';
		$data['isi'] = 'admin/result_jamaah_view';
		$data['form_action'] = site_url('admin/proses_cari_jamaah');
		$data['kanan_view'] = 'admin/admin_kanan';
		
		
		$kec = $this->session->userdata('kecamatan');
		$kab = $this->session->userdata('kabupaten');
		$th = $this->session->userdata('tahun');
		
		$data['kec'] = $kec;
		
		$data['kab'] = $kab;
		
		$uri_segment = 3;
		$offset = $this->uri->segment($uri_segment);
		
		$jamaahs = $this->Admin_model->cari_jamaah_by_lokasi($kec, $kab, $th, $this->limit, $offset)->result();
		$qr = $this->Admin_model->count_jamaah_by_lokasi($kec, $kab, $th);
		$num = $qr->row_array();
		
			$config['base_url'] = site_url('admin/result_jamaah');
			$config['total_rows'] = $num['id_jamaah'];
			$config['per_page']	= $this->limit;
			$config['uri_segment'] = $uri_segment;
			$this->pagination->initialize($config);
			$data['pagination'] = $this->pagination->create_links();
		
		$tmpl = array('table_open' => '<table class="table" border="1">', 
			'row_alt_start' => '<tr class="zebra">', 
			'row_alt_end' => '</tr>',
			'table_close' => '</table>');
			
			$this->table->set_template($tmpl);
			$this->table->set_empty("&nbsp;");
			$this->table->set_heading('No', 'Nama', 'No Porsi', 'Tahun', 'Alamat', 'Transaksi', 'Action');
			
			$i = 1;
			foreach ($jamaahs as $jamaah){
				$this->table->add_row('<div align="right">'.$i++.'</div>', 
				$jamaah->nama_jamaah, 
				'<div align="right">'. $jamaah->no_porsi.'</div>', 
				$jamaah->tahun, 
				$jamaah->alamat_jamaah, 
				anchor('admin/goto_transaksi/'.$jamaah->id_jamaah,'Transaksi'),
				anchor ('jamaah/update/'.$jamaah->id_jamaah,'update',array('class' => 'update')).' '.
				anchor('jamaah/delete/'.$jamaah->id_jamaah,'hapus',array('class'=> 'delete','onclick'=>"return confirm('Anda yakin akan menghapus data ini?')")));
			//$saldo = ($saldo + $onh->debet) - $onh->nominal;
			}
			
			$data['table'] = $this->table->generate();
		
			$this->load->view('template', $data);
		
	}
}
/* End of file welcome.php */
/* Location: ./system/application/controllers/welcome.php */