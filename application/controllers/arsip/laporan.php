<?php
class Laporan extends CI_Controller {

	function __construct(){
		parent::__construct();	
		$this->load->model('Laporan_model', '', TRUE);
	}
	var $title = 'Laporan';
	
	function index(){ 
		if ($this->session->userdata('login') == TRUE){
			$this->get_laporan();		
		}
		else{
			$this->load->view('login/login_view');
		}
	}
	
	function get_laporan(){
		$data['title'] = $this->title;
		$data['nama_menu'] = 'Menu Laporan';
		$data['menu_kiri'] = 'laporan/laporan_left';
		$data['h2_title'] = 'Laporan';
		$data['main_view'] = 'transaksi_template';
		$data['isi'] = 'laporan/laporan_view';
		$data['petunjuk'] = '&lt;- Klik Jenis Laporan yang ada di sebelah kiri';
		
		$this->load->view('template', $data);
		
	}
	
	function lap_data_jamaah_proses(){
		$data['title'] = $this->title;
		$data['nama_menu'] = 'Menu Laporan';
		$data['menu_kiri'] = 'laporan/laporan_left';
		$data['h2_title'] = 'Laporan Data Jamaah';
		$data['main_view'] = 'transaksi_template';
		$data['data_jamaah'] = 'laporan/laporan_form_th';
		$data['form_action'] = site_url('laporan/lap_data_jamaah_proses');
		$data['isi'] = 'laporan/laporan_view';
		//$data['petunjuk'] = '<div align=center>Masukkan tgl</div>';
		$data['petunjuk'] = '<div align=center>Klik link di bawah ini</div>';
		$data['current_link'] = 'lap_data_jamaah';
		$data['isi'] = 'laporan/laporan_view';
		$data['nama_laporan'] = 'Laporan Data Jamaah';
					
		$th = $this->input->post('th');
			
		$data['lap_pdf'] = 'lap_data_jamaah.php?th='.$th.'';

		$this->load->view('template', $data);
	}
	
	function lap_data_jamaah(){
		$data['title'] = $this->title;
		$data['nama_menu'] = 'Menu Laporan';
		$data['menu_kiri'] = 'laporan/laporan_left';
		$data['h2_title'] = 'Laporan Data Jamaah';
		$data['main_view'] = 'transaksi_template';
		$data['data_jamaah'] = 'laporan/laporan_form_th';
		$data['form_action'] = site_url('laporan/lap_data_jamaah_proses');
		$data['isi'] = 'laporan/laporan_view';
		$data['petunjuk'] = '<div align=center>Masukkan tgl</div>';
			
		$this->load->view('template', $data);
	}
	
	function lap_harian_all_proses(){
		$data['title'] = $this->title;
		$data['nama_menu'] = 'Menu Laporan';
		$data['menu_kiri'] = 'laporan/laporan_left';
		$data['h2_title'] = 'Laporan Harian All Pembayaran';
		$data['main_view'] = 'transaksi_template';
		$data['data_jamaah'] = 'laporan/laporan_form_tgl';
		$data['form_action'] = site_url('laporan/lap_harian_all_proses');
		//$data['petunjuk'] = '<div align=center>Klik link di bawah ini</div>';
		$data['current_link'] = 'lap_harian_all';
		$data['isi'] = 'laporan/laporan_view';
		$data['nama_laporan'] = 'Laporan Harian All Pembayaran';
		
		
		
		$this->form_validation->set_rules('tgl_laporan', 'Tgl', 'required|min_length[10]|max_length[10]');
		if ($this->form_validation->run() == TRUE){
						
			$tgl = $this->input->post('tgl_laporan');
			
			$data['lap_pdf'] = 'lap_harian_all.php?tgl='.$tgl.'';
		}
		$this->load->view('template', $data);
	}
	
	function lap_harian_all(){
		$data['title'] = $this->title;
		$data['nama_menu'] = 'Menu Laporan';
		$data['menu_kiri'] = 'laporan/laporan_left';
		$data['h2_title'] = 'Laporan Harian All Pembayaran';
		$data['main_view'] = 'transaksi_template';
		$data['data_jamaah'] = 'laporan/laporan_form_th_2';
		$data['form_action'] = '';//site_url('laporan/lap_harian_all_proses');
		$data['isi'] = 'laporan/laporan_view';
		$data['petunjuk'] = '<div align=center>Pilih tahun / klik detil untuk laporan bulanan</div>';
			
		$this->load->view('template', $data);
		
	}
	
	function lap_saldo_jamaah_onh_th_proses(){
		$data['title'] = $this->title;
		$data['nama_menu'] = 'Menu Laporan';
		$data['menu_kiri'] = 'laporan/laporan_left';
		$data['h2_title'] = 'Laporan Saldo Jamaah ONH';
		$data['main_view'] = 'transaksi_template';
		$data['data_jamaah'] = 'laporan/laporan_form_th';
		$data['form_action'] = site_url('laporan/lap_saldo_jamaah_onh_th_proses');
		$data['isi'] = 'laporan/laporan_view';
		//$data['petunjuk'] = '<div align=center>Masukkan tgl</div>';
		$data['petunjuk'] = '<div align=center>Klik link di bawah ini</div>';
		$data['current_link'] = 'lap_saldo_jamaah_onh_th';
		$data['isi'] = 'laporan/laporan_view';
		$data['nama_laporan'] = 'Laporan Saldo Jamaah ONH';
					
		$th = $this->input->post('th');
			
		$data['lap_pdf'] = 'lap_saldo_jamaah_onh_th.php?th='.$th.'';

		$this->load->view('template', $data);
	}
	
	function lap_saldo_jamaah_onh_th(){
		$data['title'] = $this->title;
		$data['nama_menu'] = 'Menu Laporan';
		$data['menu_kiri'] = 'laporan/laporan_left';
		$data['h2_title'] = 'Laporan Saldo Jamaah ONH';
		$data['main_view'] = 'transaksi_template';
		$data['data_jamaah'] = 'laporan/laporan_form_th';
		$data['form_action'] = site_url('laporan/lap_saldo_jamaah_onh_th_proses');
		$data['isi'] = 'laporan/laporan_view';
		$data['petunjuk'] = '<div align=center>Masukkan tgl</div>';
		
		
		
		$this->load->view('template', $data);
		
	}
	
	function lap_harian_adm_proses(){
		$data['title'] = $this->title;
		$data['nama_menu'] = 'Menu Laporan';
		$data['menu_kiri'] = 'laporan/laporan_left';
		$data['h2_title'] = 'Laporan Harian Administrasi';
		$data['main_view'] = 'transaksi_template';
		$data['data_jamaah'] = 'laporan/laporan_form_tgl';
		$data['form_action'] = site_url('laporan/lap_harian_adm_proses');
		//$data['petunjuk'] = '<div align=center>Klik link di bawah ini</div>';
		$data['current_link'] = 'lap_harian_adm';
		$data['isi'] = 'laporan/laporan_view';
		$data['nama_laporan'] = 'Laporan Harian Administrasi';
		
		
		
		$this->form_validation->set_rules('tgl_laporan', 'Tgl', 'required|min_length[10]|max_length[10]');
		if ($this->form_validation->run() == TRUE){
						
			$tgl = $this->input->post('tgl_laporan');
			
			$data['lap_pdf'] = 'lap_harian_adm.php?tgl='.$tgl.'';
		}
		$this->load->view('template', $data);
	}
	
	function lap_harian_adm(){
		$data['title'] = $this->title;
		$data['nama_menu'] = 'Menu Laporan';
		$data['menu_kiri'] = 'laporan/laporan_left';
		$data['h2_title'] = 'Laporan Harian Administrasi';
		$data['main_view'] = 'transaksi_template';
		$data['data_jamaah'] = 'laporan/laporan_form_tgl';
		$data['form_action'] = site_url('laporan/lap_harian_adm_proses');
		$data['isi'] = 'laporan/laporan_view';
		$data['petunjuk'] = '<div align=center>Masukkan tgl</div>';
		$this->load->view('template', $data);
		
	}
	
	function lap_harian_kbih_proses(){
		$data['title'] = $this->title;
		$data['nama_menu'] = 'Menu Laporan';
		$data['menu_kiri'] = 'laporan/laporan_left';
		$data['h2_title'] = 'Laporan Harian KBIH';
		$data['main_view'] = 'transaksi_template';
		$data['data_jamaah'] = 'laporan/laporan_form_tgl';
		$data['form_action'] = site_url('laporan/lap_harian_kbih_proses');
		//$data['petunjuk'] = '<div align=center>Klik link di bawah ini</div>';
		$data['current_link'] = 'lap_harian_kbih';
		$data['isi'] = 'laporan/laporan_view';
		$data['nama_laporan'] = 'Laporan Harian KBIH';
		
		
		
		$this->form_validation->set_rules('tgl_laporan', 'Tgl', 'required|min_length[10]|max_length[10]');
		if ($this->form_validation->run() == TRUE){
						
			$tgl = $this->input->post('tgl_laporan');
			
			$data['lap_pdf'] = 'lap_harian_kbih.php?tgl='.$tgl.'';
		}
		$this->load->view('template', $data);
	}
	
	function lap_harian_kbih(){
		$data['title'] = $this->title;
		$data['nama_menu'] = 'Menu Laporan';
		$data['menu_kiri'] = 'laporan/laporan_left';
		$data['h2_title'] = 'Laporan Harian KBIH';
		$data['main_view'] = 'transaksi_template';
		$data['data_jamaah'] = 'laporan/laporan_form_tgl';
		$data['form_action'] = site_url('laporan/lap_harian_kbih_proses');
		$data['isi'] = 'laporan/laporan_view';
		$data['petunjuk'] = '<div align=center>Masukkan tgl</div>';
		$this->load->view('template', $data);
		
	}
	
	function lap_harian_onh_proses(){
		$data['title'] = $this->title;
		$data['nama_menu'] = 'Menu Laporan';
		$data['menu_kiri'] = 'laporan/laporan_left';
		$data['h2_title'] = 'Laporan Harian ONH';
		$data['main_view'] = 'transaksi_template';
		$data['data_jamaah'] = 'laporan/laporan_form_tgl';
		$data['form_action'] = site_url('laporan/lap_harian_onh_proses');
		$data['current_link'] = 'lap_harian_onh';
		$data['isi'] = 'laporan/laporan_view';
		$data['nama_laporan'] = 'Laporan Harian ONH';
		
		
		
		$this->form_validation->set_rules('tgl_laporan', 'Tgl', 'required|min_length[10]|max_length[10]');
		if ($this->form_validation->run() == TRUE){
						
			$tgl = $this->input->post('tgl_laporan');
			
			$data['lap_pdf'] = 'lap_harian_onh.php?tgl='.$tgl.'';
		}
		$this->load->view('template', $data);
	}
	
	function lap_harian_onh(){
		$data['title'] = $this->title;
		$data['nama_menu'] = 'Menu Laporan';
		$data['menu_kiri'] = 'laporan/laporan_left';
		$data['h2_title'] = 'Laporan Harian ONH';
		$data['main_view'] = 'transaksi_template';
		$data['data_jamaah'] = 'laporan/laporan_form_tgl';
		$data['form_action'] = site_url('laporan/lap_harian_onh_proses');
		$data['isi'] = 'laporan/laporan_view';
		$data['petunjuk'] = '<div align=center>Masukkan tgl</div>';
		$this->load->view('template', $data);
		
	}
	function lap_th_onh(){
		$data['title'] = $this->title;
		$data['nama_menu'] = 'Menu Laporan';
		$data['menu_kiri'] = 'laporan/laporan_left';
		$data['h2_title'] = 'Laporan ONH';
		$data['main_view'] = 'transaksi_template';
		$data['data_jamaah'] = 'laporan/laporan_form_th_1';
		$data['form_action'] ='';
		$data['isi'] = 'laporan/laporan_view';
		$data['petunjuk'] = '<div align=center>Pilih Tahun</div>';
		$this->load->view('template', $data);
	}	// function get_total_days($month, $year)	// {		// $days_in_month	= array(31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);		// if ($month < 1 OR $month > 12)		// {			// return 0;		// }		// if ($month == 2)		// {			// if ($year % 400 == 0 OR ($year % 4 == 0 AND $year % 100 != 0))			// {				// return 29;			// }		// }		// return $days_in_month[$month - 1];	// }	function lap_harian_all_day($bulan='',$tahun=''){		$list= ("Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Okt","Nop","Dec");		for($i=1;$i<=12;$i++){			if($list[$i-1]==$bulan)				$bulan = $i;		}		$days =31;//$this->get_total_days($bulan,$tahun);		for($i=1;$i<=$days;$i++){			echo "<a href='".base_url()."pdf/lap_harian_all.php?tgl=$i-$bulan-$tahun'>$i</a>&nbsp;&nbsp;&nbsp;";		}	}
	private function get_total_days($month, $year)
	{
		$days_in_month	= array(31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);

		if ($month < 1 OR $month > 12)
		{
			return 0;
		}

		// Is the year a leap year?
		if ($month == 2)
		{
			if ($year % 400 == 0 OR ($year % 4 == 0 AND $year % 100 != 0))
			{
				return 29;
			}
		}

		return $days_in_month[$month - 1];
	}
	function lap_harian_all_day($bulan,$tahun){
		$month_number = date("n",strtotime($bulan));
		for($i=1;$i<=$this->get_total_days($month_number,$tahun);$i++){
			echo '<a href="'.base_url().'pdf/lap_harian_all.php?tgl=';
			echo $tahun.'-'.$month_number.'-'.$i.'"'.$i."> $i</a> ";
		}
	}
	function lap_harial_all_year($tahun=''){
		$data['title'] = $this->title;
		$link = array();
		for($i=1;$i<=12;$i++){
			$link[$i]=array();
		}
		$prefs = array (
               'start_day'    => 'sunday',
               'month_type'   => 'short',
               'day_type'     => 'short'
             );
		$prefs['template'] = '

		   {table_open}<table border="0" cellpadding="0" cellspacing="0">{/table_open}

		   {heading_row_start}<tr>{/heading_row_start}

		   {heading_previous_cell}<th><a href="{previous_url}">&lt;&lt;</a></th>{/heading_previous_cell}
		   {heading_title_cell}<th colspan="{colspan}"><a href="'.base_url('pdf/lap_bulanan_all.php?p=').'{heading}" target="_blank">{heading}</a> <a href="'.base_url().'index.php/laporan/lap_harian_all_day/{heading}" target="popup" onclick="window.open(\''.base_url().'index.php/laporan/lap_harian_all_day/{heading} \',\'name\',\'width=800,height=600\')">Detil...</a></th>{/heading_title_cell}
		   {heading_next_cell}<th><a href="{next_url}">&gt;&gt;</a></th>{/heading_next_cell}

		   {heading_row_end}</tr>{/heading_row_end}

		   {week_row_start}<tr>{/week_row_start}
		   {week_day_cell}<td>{week_day}</td>{/week_day_cell}
		   {week_row_end}</tr>{/week_row_end}

		   {cal_row_start}<tr>{/cal_row_start}
		   {cal_cell_start}<td>{/cal_cell_start}

		   {cal_cell_content}<a href="'.base_url('pdf/laporan_harian/').'{content}{heading}{day}">{day}</a>{/cal_cell_content}
		   {cal_cell_content_today}<div class="highlight"><a href="{content}">{day}</a></div>{/cal_cell_content_today}

		   {cal_cell_no_content}<a href="'.base_url('pdf/laporan_harian?p=').'{heading}-{day}">{day}</a>{/cal_cell_no_content}
		   {cal_cell_no_content_today}<div class="highlight"><a href="pdf/laporan_{day}"></div></div>{/cal_cell_no_content_today}

		   {cal_cell_blank}&nbsp;{/cal_cell_blank}

		   {cal_cell_end}</td>{/cal_cell_end}
		   {cal_row_end}</tr>{/cal_row_end}

		   {table_close}</table>{/table_close}
		';
		$this->load->library('calendar', $prefs);
		$data['nama_menu'] = 'Menu Laporan';
		$data['menu_kiri'] = 'laporan/laporan_left';
		$data['h2_title'] = "Laporan ONH Tahun $tahun";
		$data['main_view'] = 'transaksi_template';
		$data['data_jamaah'] = $tahun==''?'laporan/laporan_form_th_1':'laporan/laporan_form_all';
		$data['form_action'] ='';
		$data['th'] = $tahun;
		$data['isi'] = 'laporan/laporan_view';
		$data['petunjuk'] =$tahun==''?'<div align=center>Pilih Tahun</div>':'<div align=center>Pilih Tanggal</div>';
		$this->load->view('template', $data);
	}	function lap_th_onh_all($tahun=''){
		$data['title'] = $this->title;
		$link = array();
		for($i=1;$i<=12;$i++){
			$link[$i]=array();
		}
		$prefs = array (
               'start_day'    => 'sunday',
               'month_type'   => 'short',
               'day_type'     => 'short'
             );
		$prefs['template'] = '

		   {table_open}<table border="0" cellpadding="0" cellspacing="0">{/table_open}

		   {heading_row_start}<tr>{/heading_row_start}

		   {heading_previous_cell}<th><a href="{previous_url}">&lt;&lt;</a></th>{/heading_previous_cell}
		   {heading_title_cell}<th colspan="{colspan}"><a href="'.base_url('pdf/lap_bulanan_onh.php?p=').'{heading}" target="_blank">{heading}</a></th>{/heading_title_cell}
		   {heading_next_cell}<th><a href="{next_url}">&gt;&gt;</a></th>{/heading_next_cell}

		   {heading_row_end}</tr>{/heading_row_end}

		   {week_row_start}<tr>{/week_row_start}
		   {week_day_cell}<td>{week_day}</td>{/week_day_cell}
		   {week_row_end}</tr>{/week_row_end}

		   {cal_row_start}<tr>{/cal_row_start}
		   {cal_cell_start}<td>{/cal_cell_start}

		   {cal_cell_content}<a href="'.base_url('pdf/laporan_harian/').'{content}{heading}{day}">{day}</a>{/cal_cell_content}
		   {cal_cell_content_today}<div class="highlight"><a href="{content}">{day}</a></div>{/cal_cell_content_today}

		   {cal_cell_no_content}<a href="'.base_url('pdf/laporan_harian?p=').'{heading}-{day}">{day}</a>{/cal_cell_no_content}
		   {cal_cell_no_content_today}<div class="highlight"><a href="pdf/laporan_{day}"></div></div>{/cal_cell_no_content_today}

		   {cal_cell_blank}&nbsp;{/cal_cell_blank}

		   {cal_cell_end}</td>{/cal_cell_end}
		   {cal_row_end}</tr>{/cal_row_end}

		   {table_close}</table>{/table_close}
		';
		$this->load->library('calendar', $prefs);
		$data['nama_menu'] = 'Menu Laporan';
		$data['menu_kiri'] = 'laporan/laporan_left';
		$data['h2_title'] = "Laporan ONH Tahun $tahun";
		$data['main_view'] = 'transaksi_template';
		$data['data_jamaah'] = $tahun==''?'laporan/laporan_form_th_1':'laporan/laporan_form_all';
		$data['form_action'] ='';
		$data['th'] = $tahun;
		$data['isi'] = 'laporan/laporan_view';
		$data['petunjuk'] =$tahun==''?'<div align=center>Pilih Tahun</div>':'<div align=center>Pilih Tanggal</div>';
		$this->load->view('template', $data);
	}
	
}
/* End of file welcome.php */
/* Location: ./system/application/controllers/welcome.php */