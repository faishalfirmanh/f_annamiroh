<?php
/**
 * Kelas Class
 *
 * @author	Awan Pribadi Basuki <awan_pribadi@yahoo.com>
 */
class Master extends CI_Controller {
	/**
	 * Constructor
	 */
	 var $t = array();
	 var $paket = array();
	 var $transaksi_paket = array();
	 var $crud='';
	function __construct()
	{
		parent::__construct();
		if ($this->session->userdata('login') != TRUE)
		{
			redirect('login');
		}
		$this->load->database();
		$this->load->helper('url');
		$this->load->model('main_model', '', TRUE);
		$this->load->library('grocery_CRUD');
		$this->crud = new grocery_CRUD();
		$this->_init();
	}
	private function _init()
	{
		$this->output->set_template('admin');
		$ide= $this->session->userdata('level');
		$this->output->set_output_data('menu',$this->main_model->get_menu($ide));
		$this->load->js('assets/themes/default/js/jquery-1.9.1.min.js');
		$this->load->js('assets/themes/default/hero_files/bootstrap-transition.js');
		$this->load->js('assets/themes/default/hero_files/bootstrap-collapse.js');
	}
	private function show($module  = ''){
		$this->crud->set_theme('twitter-bootstrap')->unset_export();
		$output = $this->crud->render();
		$this->load->view('ci_simplicity/admin',$output);
	}
	/**
	 * Memeriksa user state, jika dalam keadaan login akan menampilkan halaman kelas,
	 * jika tidak akan meredirect ke halaman login
	 */
	function index()
	{
		redirect('master/jamaah');
	}

	function hotel(){
		$this->crud->set_table('data_hotel');
		$this->crud->set_subject('Data Hotel');
		$this->show();
		
	}
	function group_level(){
		$this->crud->set_table('group_level')->unset_add()->unset_delete()->unset_edit()->unset_read()->columns('id','nama','keterangan')->order_by('id');
		$this->crud->set_subject('Data Group');
		$this->show();
		
	}
	function group_level_kategori(){
		$this->crud->set_table('group_level_menu');
		$this->crud->set_subject('Data Kategori Menu');
		$this->show();
		
	}
	function perhitungan(){
		$this->crud->set_table('perhitungan');
		$this->crud->set_subject('Hitung Paket');
		$this->crud->unset_read()->columns('keberangkatan', 'jual','jumlah_pax', 'tiket', 'visa', 'perlengkapan', 'handling', 'operasional', 'baksis', 'bus', 'guide','lama_guide' ,'hotel_makkah', 'hotel_madinah','jumlah_per_kamar', 
	'jumlah_hari_makkah', 'jumlah_hari_madinah','jumlah_free', 'kurs');
		$this->crud->fields('keberangkatan', 'jumlah_pax', 'tiket', 'visa', 'perlengkapan', 'handling', 'operasional', 'baksis', 'bus', 'guide','lama_guide', 'hotel_makkah', 'hotel_madinah', 
	'jumlah_hari_makkah', 'jumlah_hari_madinah',
	'hotel_makkah1', 'hotel_madinah1', 
	'jumlah_hari_makkah1', 'jumlah_hari_madinah1','jumlah_free', 'jumlah_per_kamar','laba','kurs')->callback_column('jual',array($this,'_callback_jual'));
		$this->crud->display_as('jumlah_hari_madinah1','Jumlah Hari Madinah (Opsional)');
		// $this->crud->display_as('jumlah_per_kamar','Jumlah jamaah per kamar');
		$this->crud->display_as('jumlah_hari_makkah1','Jumlah Hari Makkah (Opsional)');
		$this->crud->display_as('hotel_makkah1','Hotel Makkah (Opsional)');
		$this->crud->display_as('hotel_madinah1','Hotel Madinah (Opsional)');
		$this->crud->callback_column('keberangkatan',array($this,'_callback_edit'));
		$this->crud->set_rules('jumlah_pax','Jumlah Pax','numeric');
		$this->crud->set_rules('tiket','Tiket','numeric');
		$this->crud->set_rules('visa','Visa','numeric');
		$this->crud->set_rules('perlengkapan','Perlengkapan','numeric');
		$this->crud->set_rules('handling','Handling','numeric');
		$this->crud->set_rules('operasional','Operasional','numeric');
		$this->crud->set_rules('baksis','Baksis','numeric');
		$this->crud->set_rules('bus','Bus','numeric');
		$this->crud->set_rules('guide','Guide','numeric');
		$this->crud->set_rules('lama_guide','Guide','numeric');
		$this->crud->set_rules('hotel_makkah','Hotel Makkah','numeric');
		$this->crud->set_rules('hotel_madinah','Hotel Madinah','numeric');
		$this->crud->set_rules('jumlah_free','Hotel Madinah','numeric');
		$this->crud->set_rules('laba','Hotel Madinah','numeric');
		$this->crud->set_rules('kurs','Hotel Madinah','numeric');
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
		return "<a href='".site_url('master/perhitungan/edit/'.$row->id)."' >$value</a>";
	}
	function _jumlah_per_kamar_callback()
	{
		return ' <input type="text" maxlength="2" value="4" name="jumlah_per_kamar">';
	}
	public function _callback_jual($value, $row)
	{
	  $total = $row->visa+$row->tiket+$row->perlengkapan;
	  $a = "B123=$total<br>";
	  //$a='';
	  if($row->jumlah_pax<1) 
		  return "<a href='".site_url('master/perhitungan/edit/'.$row->id)."' >Jumlahpax harus diset lebih dari 0</a>";
	  if($row->jumlah_per_kamar<1)
		  return "<a href='".site_url('master/perhitungan/edit/'.$row->id)."' >Jumlah jamaah per kamar harus lebih dari 0</a>";
	  $durasi = $row->jumlah_hari_makkah+$row->jumlah_hari_madinah;
	  $real = $row->handling+$row->operasional+$row->baksis/$row->jumlah_pax+$row->bus/$row->jumlah_pax+$row->guide*$row->lama_guide/$row->jumlah_pax+$row->hotel_makkah*$row->jumlah_hari_makkah/$row->jumlah_per_kamar+$row->hotel_madinah*$row->jumlah_hari_madinah/$row->jumlah_per_kamar
	  +$row->hotel_makkah1*$row->jumlah_hari_makkah1/$row->jumlah_per_kamar+$row->hotel_madinah1*$row->jumlah_hari_madinah1/$row->jumlah_per_kamar;
	  $a.="+SAR $real <br>";
	  $total = $real*$row->kurs + $total;
	  $free1 = $total*$row->jumlah_free/$row->jumlah_pax;
	  $total = $total+$free1+$row->laba;
	  $value= number_format((float)$total);
	  $t = $a.'Total='.$value;
	  return "<a href='".site_url('master/perhitungan/edit/'.$row->id)."' >$t</a>";
	}
	
	function _rupiah($value,$row){
		return number_format((float)$value);
	} 
	public function __pilih_group($value, $row)
    {
        return "<a href='".site_url('master/akses/'.$row->id_group.'/'.$row->id_kategori)."' target='_blank'>$value</a>";
    }
    function get($table,$id,$id_val,$kolom){
		$query = $this->db->get_where($table, array($id => $id_val));
		foreach ($query->result() as $row)
		{
				return $row->$kolom;
		}
		return null;
	}
    //create view pilihan_menu as select nama, group_level.id as id_group, kategori,group_level_menu.id as id_kategori from group_level_menu,group_level
	function akses($group = 0,$kategori = 0){
	    if($group == 0 || $kategori == 0){
	        $this->crud->set_table('pilihan_menu1')->unset_add()->unset_delete()->unset_edit()->unset_read()->columns('nama','kategori');
    		$this->crud->set_subject('Pilih Group')->callback_column('kategori',array($this,'__pilih_group'))->order_by('id_group');
    		$this->show();
	    }else{
	        $t = $this->get('group_level','id',$group,'nama');
	        $g = $this->get('group_level_menu','id',$kategori,'kategori');
            $this->crud->set_table('page_akses')->unset_read()->columns('link','menu','aktif')->field_type('kategori', 'hidden', $kategori)->field_type('group', 'hidden', $group)->where(array('kategori'=>$kategori,'group'=>$group));
            //
    		$this->crud->set_subject("Data Hak Akses $t <br> kategori $g");
			$this->crud->set_top("Data Hak Akses $t <br> kategori $g");
    		$this->show();
	    }
	}
	
	function maskapai(){
		$this->crud->set_table('data_maskapai');
		$this->crud->set_subject('Data Maskapai');
		$this->crud->set_theme('datatables');
		$this->show();
	}
	function rute(){
	    $crud = new grocery_CRUD();
		$this->crud->set_table('data_rute');
		$this->crud->set_subject('Data Rute')->set_relation('pesawat_berangkat','data_maskapai','nama')->set_relation('pesawat_pulang','data_maskapai','nama');
		
		$this->show();
		
	}
	function agen(){
		$crud = new grocery_CRUD();
		$this->crud->set_table('data_jamaah_agen');
		$this->crud->set_theme('datatables');
		$this->crud->set_subject('Data Agen Umroh')->display_as('id','Nomor Agen')->unset_read()->columns('id','nama','alamat','telepon','email','hp','keterangan','leader')->unset_delete()->where('data_jamaah_agen.pangkat',0);	
		$this->crud->set_relation('leader','data_jamaah_agen','{nama}-{id}',array('pangkat' => '1'))->field_type('pangkat','hidden',0);//0 agen, 1 leader
		$this->show();
	}
	public function _urle($value, $row)
    {
        return "<a href='".site_url('usere/agen_leader/'.$row->id)."'>$value</a>";
    }
	function agen_(){
	    $fk= $this->session->userdata('fk');
		$crud = new grocery_CRUD();
		$this->crud->set_table('data_jamaah_agen');
		$this->crud->set_theme('datatables');
		$this->crud->set_subject('Data Agen Umroh')->display_as('id','Nomor Agen')->unset_read()->columns('id','nama','alamat','telepon','email','hp','keterangan')->unset_delete()->where('data_jamaah_agen.pangkat',0)->where('leader',$fk);	
		$this->crud->field_type('pangkat','hidden',0)->callback_column('nama',array($this,'_urle'));;//0 agen, 1 leader
		$this->show();
	}
	function leader(){
		$crud = new grocery_CRUD();
		$this->crud->set_table('data_jamaah_agen')->unset_read()->columns('id','nama','alamat','telepon','email','hp','keterangan')->fields('nama','alamat','telepon','email','hp','keterangan','pangkat');
		$this->crud->set_theme('datatables');
		$this->crud->set_subject('Data Leader Umroh')->where('pangkat',1)->field_type('pangkat','hidden',1)->unset_delete();	
		$this->show();
	}
	function jamaah_p(){
	    
	    
	    $this->crud->set_table('data_jamaah_paket');
		$this->crud->set_subject('Pilih Paket Umroh')->unset_edit()->unset_delete()->unset_read()->columns('estimasi_keberangkatan','Program');
		$this->crud->set_theme('datatables')->unset_read();
		//$this->crud->set_relation('hotel','data_hotel','nama');
		$this->crud->set_relation('Penerbangan','data_maskapai','nama');

		$this->show();
	}
	/*
		 var $paket = array();
	 var $transaksi_paket = array();
	 */
	function jamaah($paket=0){
	    $level = $this->session->userdata('level');
	    if($level == 4) 
	        redirect('master/jamaah_p');
		$this->crud->set_table('data_jamaah');
		$nama = $this->input->post('s',true);

		if(isset($nama) && $nama != null)
		    $this->crud->like('nama_jamaah',$nama);
		$this->crud->set_subject('Data Jamaah Umroh')->set_theme('datatables')->unset_delete();
		$this->crud->required_fields('city');
		 $this->crud->unset_texteditor('keterangan','full_text');
		  $this->crud->unset_texteditor('alamat_jamaah','full_text');
		$this->crud->fields('nama_jamaah','tgl_lahir','alamat_jamaah','no_tlp','hp_jamaah','no_ktp','foto','kartukeluarga','ktp','surat_nikah','keterangan','agen');
		$this->crud->unset_read()->columns('nama_jamaah','paket','tgl_lahir','no_ktp','agen','hp_jamaah','alamat_jamaah');
		// $this->crud->set_relation_n_n('paket','transaksi_paket','data_jamaah_paket','jamaah','paket_umroh','{estimasi_keberangkatan}/{Program}-{data_jamaah_paket.harga}');
		$this->crud->set_relation('agen','data_jamaah_agen','{nama}-{id}')->callback_column('paket',array($this,'_callback_paket'));
		// $query = $this->db->get("v_paket");
		$query = $this->db->query("SELECT id,CONCAT(estimasi_keberangkatan,'-',Program,'-',CAST(FORMAT(harga,2,'de_DE') AS CHAR CHARACTER SET utf8)) AS detail FROM data_jamaah_paket");
        foreach ($query->result() as $row)
        {
                
                $this->paket[$row->id] = $row->detail;
        }
        $query = $this->db->get("transaksi_paket");
        foreach ($query->result() as $row)
        {
            if(isset($this->transaksi_paket[$row->jamaah]))
				$this->transaksi_paket[$row->jamaah] .= isset($this->paket[$row->paket_umroh])?'#'.$this->paket[$row->paket_umroh]:'';
			else
				$this->transaksi_paket[$row->jamaah] = isset($this->paket[$row->paket_umroh])?'#'.$this->paket[$row->paket_umroh]:'';
        }
		// $this->crud->set_relation('leader','data_jamaah_agen','{nama}-{id}');
		$this->crud->set_field_upload('foto','assets/uploads/foto');
		$this->crud->set_field_upload('kartukeluarga','assets/uploads/kk');
		$this->crud->set_field_upload('ktp','assets/uploads/ktp');
		$this->crud->set_field_upload('surat_nikah','assets/uploads/nikah');
	    $this->crud->set_rules('no_ktp','Nomor KTP / Paspor','trim|required|min_length[7]|max_length[17]');

        $query = $this->db->get('jenis_transaksi');
        foreach ($query->result() as $row)
        {
                
                $this->t[$row->id] = $row->nama_transaksi;
        }
		$this->show();
	}
	public function _callback_paket($value, $row)
    {
        $a = isset($this->transaksi_paket[$row->id_jamaah])?$this->transaksi_paket[$row->id_jamaah]:'';
		return $a;
    }	
	public function _callback_transaksi($value, $row)
    {
        $id = $row->id_jamaah;
        $a = "<a href='".site_url('transaksi/paket_jamaah/'.$id)."'data-toggle='tooltip' title='Pembelian Paket Umroh Jamaah'>Transaksi</a>";
        // $a.=" | <a href='".site_url('transaksi/kredit_all/'.$id)."'data-toggle='tooltip' title='Setoran Jamaah'>Kredit</a> | <a href='".site_url('transaksi/histori/'.$id)."'data-toggle='tooltip' title='Histori Transaksi'>Histori</a>";
        
		return $a;
    }
    function _harga_rp($value,$row){
		//return $this->main_model->get_kurs()*$row->harga_dolar;
		//echo "xxxx";
		//var_dump($row);
		if($row->harga == null) return 0;
		return number_format((float)$row->harga);
		//setlocale(LC_MONETARY, 'id_ID');
		//return  money_format('%i', floatval($value)) . "\n";
		//return $value;
	}
	function paket(){
		$this->crud->set_table('data_jamaah_paket');
			$this->crud->callback_column('harga',array($this,'_harga_rp'));
	//	$this->crud->set_relation('hotel','data_hotel','nama');
		$this->crud->set_relation('hotel_makkah','data_hotel','nama');
		$this->crud->set_relation('hotel_madinah','data_hotel','nama');
		$this->crud->set_relation('Penerbangan','data_maskapai','nama');
		$this->crud->set_subject('Data Paket Umroh');
	    $this->crud->unset_read()->columns('estimasi_keberangkatan','Program','Penerbangan','rute','hotel_makkah','hotel_madinah','harga','tanggal_keberangkatan','pembimbing','total_seat')->unset_delete()->set_relation('pembimbing','data_jamaah_pembimbing','nama');
		$this->show();
	}
	function paket_($arsip='1'){
		
		$this->crud->set_table('data_jamaah_paket');
				
		$this->crud->callback_column('harga',array($this,'_harga_rp'));
		//$this->crud->set_relation('hotel','data_hotel','nama');
		$this->crud->set_relation('hotel_makkah','data_hotel','nama');
		$this->crud->set_relation('hotel_madinah','data_hotel','nama');
		$this->crud->set_relation('Penerbangan','data_maskapai','nama');
		$this->crud->set_subject('Data Paket Umroh');
	    $this->crud->unset_read()->columns('estimasi_keberangkatan','tanggal_keberangkatan','Program','Penerbangan','pembimbing','hotel_makkah','hotel_madinah','harga','total_seat','KET')->fields('estimasi_keberangkatan','tanggal_keberangkatan','Program','Penerbangan','hotel_makkah','hotel_madinah','pembimbing','harga','total_seat','KET')->unset_delete()->set_relation('pembimbing','data_jamaah_pembimbing','nama');
		$this->show();
	}
	function pembimbing(){
		$this->crud->set_table('data_jamaah_pembimbing');
		$this->crud->set_subject('Data Pembimbing');
	    $this->crud->unset_read()->columns('nama','alamat','pendidikan','sertifikat')->unset_delete();
		$this->show();
	}
	function transaksi_pemasukan(){
		$this->crud->set_table('jenis_transaksi');
		$this->crud->set_subject('Jenis Transaksi');
		$this->crud->set_theme('datatables');
		$this->crud->set_subject('Jenis Transaksi')->unset_edit()->unset_delete();
	    $this->show();
	}
	function transaksi_pengeluaran(){
		$this->crud->set_table('jenis_transaksi_pengeluaran');
		$this->crud->set_subject('Jenis Transaksi');
		$this->crud->set_theme('datatables');
		$this->crud->set_subject('Jenis Transaksi')->unset_edit()->unset_delete();
	    $this->show();
	}
	function log(){
		
	}
	
}
// END Kelas Class

/* End of file kelas.php */
/* Location: ./system/application/controllers/kelas.php */