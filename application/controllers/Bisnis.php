<?php
/**
 *  
 *
 * @author	Moch Yasin
 */
class Bisnis extends CI_Controller {
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
		if ($this->session->userdata('login') != TRUE)
		{
			redirect('login');
		}
		$this->load->database();
		$this->load->helper('url');
		$this->load->model('main_model', '', TRUE);
		$this->load->library('grocery_CRUD');
		$this->_init();
	}
	
	private function _init()
	{
		$this->output->set_template('admin');
        $this->crud = new Grocery_CRUD();
		$ide= $this->session->userdata('level');
		$this->output->set_output_data('menu',$this->main_model->get_menu($ide));
		$this->crud->set_language("indonesian");
		$this->load->js('assets/themes/default/js/jquery-1.9.1.min.js');
		$this->load->js('assets/themes/default/js/jquery-migrate-3.4.1.js');
	
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
	private function show(){
		$this->crud->set_theme('twitter-bootstrap')->unset_export();
		$output = $this->crud->render();
		// $kurs = $this->main_model->get_kurs();
		// $this->load->section('sidebar', 'ci_simplicity/kurs',array('kurs'=>$kurs));
		$this->load->view('ci_simplicity/admin',$output);
	}
    function utama($id_jamaah,$jenis_transaksi){
        $this->crud = new grocery_CRUD();
		$this->crud->set_table('bisnis');
		$this->crud->set_subject('Data Bisnis');
		$this->crud->set_theme('twitter-bootstrap');
		$this->crud->set_relation('hotel_makkah','data_hotel','nama');
		$this->crud->set_relation('hotel_madinah','data_hotel','nama');
		$this->crud->set_relation('Penerbangan','data_maskapai','nama');
		$this->crud->set_subject('Data Bisnis');
	    $this->crud->unset_read()->columns('bisnis','kebutuhan');
	    $this->show();
    }
	function get_row($table,$id,$id_val){
		$query = $this->db->get_where($table, array($id => $id_val));
		foreach ($query->result() as $row)
		{
				return $row;
		}
		return null;
	}
	function get($table,$id,$id_val,$kolom){
		$query = $this->db->get_where($table, array($id => $id_val));
		foreach ($query->result() as $row)
		{
				return $row->$kolom;
		}
		return null;
	}
	public function _callback_webpage_url($value, $row)
    {
		$jumlae = isset($this->j[$row->id])?$this->j[$row->id]:0;
		
		$sisa = $row->total_seat-$jumlae;
		if(isset($sisa)){
			if($sisa > 0)
				return "<a href='".site_url('transaksi/pembayaran/'.$row->id)."'>$value-$jumlae orang sisa $sisa</a>";
		}
		return "<a href='".site_url('transaksi/pembayaran/'.$row->id)."'>$value-$jumlae orang seat PENUH</a>";
    }
	public function _peserta_paket($value, $row)
    {
		$jumlae = isset($this->j[$row->id])?$this->j[$row->id]:0;
		$sisa = $row->total_seat-$jumlae;
		if(isset($sisa)){
			if($sisa > 0)
				return "$value-terisi $jumlae orang, sisa $sisa";
		}
		return "$value-terisi $jumlae orang, seat PENUH";
    }
	function _harga_rp($value,$row){
		return $this->main_model->get_kurs()*$row->harga_dolar;
	}
	function harga_field_callback_1()
	{
	return '<input type="text" maxlength="50" value="000'.$this->r.'" name="harga" style="width:462px">';
	}	
    function pakete(){
        $this->crud->set_table('data_jamaah_paket')->unset_read()->columns('estimasi_keberangkatan','Program','tanggal_keberangkatan','total_seat','harga','Penerbangan','rute')->unset_edit()->display_as('estimasi_keberangkatan','Paket')->unset_delete()->unset_add()->set_subject('Paket')->where('KET','AKTIF')->where('TAMPIL','YA');
			$this->crud->set_relation('Penerbangan','data_maskapai','nama');

			$this->db->select('paket_umroh');
			$query = $this->db->get('transaksi_paket');
			foreach ($query->result() as $row)
			{
				if(isset($this->j[$row->paket_umroh]))
					$this->j[$row->paket_umroh]++;
				else
					$this->j[$row->paket_umroh]=1;
			}
			$this->crud->callback_column('estimasi_keberangkatan',array($this,'_peserta_paket'));
			// $this->crud->callback_column('harga',array($this,'_harga_rp'));
			 $this->show();
    }
	function pembayaran($paket=0,$jamaah =0){
	   
		if($paket==0){
			$this->crud->set_table('data_jamaah_paket')->unset_read()->columns('estimasi_keberangkatan','total_seat','tanggal_keberangkatan','Program','harga','harga_dolar')->unset_edit()->display_as('estimasi_keberangkatan','Pilih Paket')->unset_delete()->unset_add()->set_subject('Pilih Paket')->where('KET','AKTIF')->where('TAMPIL','YA');
			$this->db->select('paket_umroh');
			$query = $this->db->get('transaksi_paket');
			foreach ($query->result() as $row)
			{
				if(isset($this->j[$row->paket_umroh]))
					$this->j[$row->paket_umroh]++;
					
				else
					$this->j[$row->paket_umroh]=1;
			}
			$this->crud->callback_column('estimasi_keberangkatan',array($this,'_callback_webpage_url'));
			$this->crud->callback_column('harga',array($this,'_harga_rp'));
		}
		elseif($jamaah==0){
			$s = $this->get('data_jamaah_paket','id',$paket,'estimasi_keberangkatan').'<br>$';
			$dolar = $this->get('data_jamaah_paket','id',$paket,'harga_dolar');
			$kurs = $this->main_model->get_kurs();
			$s.=$dolar.'<br>';
			$r=ceil($kurs*$dolar/1000)*1000;
			$this->r = $r;
			$s.='Rp.'.$this->main_model->uang($r);
			$this->grocery_crud->callback_add_field('harga',array($this,'harga_field_callback_1'));
			$this->crud->set_table('transaksi_paket')->set_subject('Pembelian paket umroh '.$s)->set_relation('jamaah','data_jamaah','{nama_jamaah}-{no_ktp}','nama_jamaah <> ""')->unset_read()->columns('jamaah','harga','kredit','kekurangan','debet','saldo','kode','agen');
			$this->crud->field_type('kode', 'readonly');
			// $this->crud->set_relation('paket_umroh','data_jamaah_paket','estimasi_keberangkatan');
			$this->crud->callback_column('kekurangan',array($this,'_kekurangan'));
			$this->crud->callback_column('debet',array($this,'__debet'));
			$this->crud->callback_column('kredit',array($this,'__kredit'));
			$this->crud->set_relation('agen','data_jamaah_agen','{nama}/{id}');
			$this->crud->add_fields(array('jamaah','harga','paket_umroh','kekurangan','harga_normal','agen'));
			$this->crud->callback_before_insert(array($this,'_update_kekurangan'));
			$this->crud->field_type('harga_normal', 'hidden', $r);
			$this->crud->field_type('kekurangan', 'hidden', $r);
			$this->crud->field_type('paket_umroh', 'hidden', $paket);
			$this->crud->data['-tes']='-';
			$this->crud->set_top('Pembelian paket umroh '.$s);
			
			$this->crud->callback_after_insert(array($this, 'fix_code_after_insert'))->where(array('paket_umroh'=>$paket));
		}
		
	    $this->show();
	}
	
	public function _kekurangan($value, $row)
	{
		return "<a href='".site_url('transaksi/histori/'.$row->id)."' target='_blank'>$value</a>";
	}
	public function __debet($value, $row)
	{
		return "<a href='".site_url('transaksi/debet/'.$row->id)."'  target='_blank'>$value</a> <a href='".site_url('transaksi/debet/'.$row->id)."/add'  target='_blank'>+</a>";
	}
	public function __kredit($value, $row)
	{
		return "<a href='".site_url('transaksi/kredit/'.$row->id)."'  target='_blank'>$value</a> <a href='".site_url('transaksi/kredit/'.$row->id)."/add'  target='_blank'>+</a>";
	}
	public function __kuitansi_kredit($value, $row)
	{
		return "<a href='".site_url('kuitansi/kredit/'.$row->id)."'  target='_blank'>$value</a>";
	}
	function _update_kekurangan($post_array) {
		
		$post_array['kekurangan'] = $post_array['harga'];
		 
		return $post_array;
	} 
	function fix_code_after_insert($post_array,$primary_key)
	{
		$this->db->update('transaksi_paket',array('kode'=>base_convert($primary_key,10,36)),array('id'=>$primary_key));
		 // echo $this->db->last_query();
		return true;
	}
	/*
	
	*/
	function debet($id=0){
	    if($id==0) 
	        redirect('transaksi/pembayaran');
		$this->crud->set_table('pembayaran_transaksi_paket');
		$j = $this->get_row('transaksi_paket','id',$id);
		$jamaah = $this->get('data_jamaah','id_jamaah',$j->jamaah,'nama_jamaah');
		$ide= $this->session->userdata('id_admin');
		// $this->crud->callback_column('debet',array($this,'__kuitansi_kredit'));
		$p = $this->get_row('data_jamaah_paket','id',$j->paket_umroh);
		$paket = $p->estimasi_keberangkatan;
		$harga = $p->harga;
		list($debet,$kredit) = $this->get_sum($id,$harga);
		$kurang = $harga - $kredit;
		$saldo = $kredit - $debet;
		$this->crud->set_subject("Transaksi Debet $jamaah | Paket :$paket | Harga:$harga | Pembayaran:$kredit | Kekurangan: ".$kurang."<br>Transaksi Debet : $debet | saldo = $saldo");
		// $this->crud->set_subject("Debet Transaksi $jamaah<br>Paket :$paket<br>Harga:$harga <br>Kekurangan: ".$j->kekurangan);
		$this->crud->unset_read()->columns('jenis_transaksi','keterangan','tanggal','tanggal_transfer','debet','teller');
		$this->crud->field_type('teller', 'hidden', $ide)
			->field_type('id_transaksi_paket', 'hidden', $id)
			->where('id_transaksi_paket',$id)
			->where('debet > 0')
			->set_relation('jenis_transaksi','jenis_transaksi_pengeluaran','nama_transaksi')
			->unset_texteditor('keterangan');
		$state= $this->crud->getState();
		// echo "state=$state";
		if($state == 'ajax_list'){
			$this->crud->set_relation('teller','admin','nama');
		}
		$this->crud->set_top("Transaksi Debet $jamaah | Paket :$paket | Harga:$harga | Pembayaran:$kredit | Kekurangan: ".$kurang."<br>Transaksi Debet : $debet | saldo = $saldo");
		$this->crud->fields('id_transaksi_paket','jenis_transaksi','tanggal','tanggal_transfer','debet','keterangan','teller');
	    $this->show();
	}
	
	private function get_sum($id_transaksi_paket,$harga){

		$d= $this->db->query("select kredit,debet from pembayaran_transaksi_paket where  id_transaksi_paket = $id_transaksi_paket");
		$debet = 0;
		$kredit = 0;
		foreach($d->result() as $row){
			$debet += $row->debet;
			$kredit += $row->kredit;
		}
		// return array('debet'=>$debet,'kredit'=>$kredit);
		$saldo = $kredit - $debet;
		$this->db->update('transaksi_paket',array('kekurangan'=>($harga - $kredit),'debet'=>$debet,'kredit'=>$kredit,'saldo'=>$saldo),array('id'=>$id_transaksi_paket));
		// $this->db->update('transaksi_paket',array('kode'=>base_convert($primary_key,10,36)),array('id'=>$primary_key));
		return array($debet,$kredit);
	}
	function kredit($id=0){
	    if($id==0) 
	        redirect('transaksi/pembayaran');
		$this->crud->set_table('pembayaran_transaksi_paket');
		$j = $this->get_row('transaksi_paket','id',$id);
		$jamaah = $this->get('data_jamaah','id_jamaah',$j->jamaah,'nama_jamaah');
		//
		$this->crud->callback_column('kredit',array($this,'__kuitansi_kredit'));
		$ide= $this->session->userdata('id_admin');
		$p = $this->get_row('data_jamaah_paket','id',$j->paket_umroh);
		$paket = $p->estimasi_keberangkatan;
		$harga = $p->harga;
		list($debet,$kredit) = $this->get_sum($id,$harga);
		$kurang = $harga - $kredit;
		// $this->crud->set_subject("Kredit Transaksi $jamaah<br>Paket :$paket<br>Harga:$harga <br>Pembayaran:$kredit<br>Kekurangan: ".$kurang."<br>Transaksi Debet : $debet");
		$this->crud->set_subject("Transaksi Kredit $jamaah | Paket :$paket | Harga:$harga | Pembayaran:$kredit | Kekurangan: ".$kurang."<br>Transaksi Debet : $debet");
		$this->crud->set_top("Transaksi Kredit $jamaah | Paket :$paket | Harga:$harga | Pembayaran:$kredit | Kekurangan: ".$kurang."<br>Transaksi Debet : $debet");
		$this->crud->unset_read()->columns('jenis_transaksi','keterangan','tanggal','tanggal_transfer','kredit','teller');
		$this->crud->field_type('teller', 'hidden', $ide)
			->field_type('id_transaksi_paket', 'hidden', $id)
			->where('id_transaksi_paket',$id)
			->where('kredit >',0)
			->set_relation('jenis_transaksi','jenis_transaksi_pengeluaran','nama_transaksi')
			->unset_texteditor('keterangan');
		$state= $this->crud->getState();
		// echo "state=$state";
		if($state == 'ajax_list'){
			$this->crud->set_relation('teller','admin','nama');
		}
		$this->crud->fields('id_transaksi_paket','jenis_transaksi','tanggal','tanggal_transfer','kredit','keterangan','teller');
	    $this->show();
	}
	
	function histori($id=0){
		$this->crud->set_table('pembayaran_transaksi_paket');
		$j = $this->get_row('transaksi_paket','id',$id);
		$jamaah = $this->get('data_jamaah','id_jamaah',$j->jamaah,'nama_jamaah');
		
		$ide= $this->session->userdata('id_admin');
		$p = $this->get_row('data_jamaah_paket','id',$j->paket_umroh);
		$paket = $p->estimasi_keberangkatan;
		$harga = $p->harga;
		list($debet,$kredit) = $this->get_sum($id,$harga);
		$kurang = $harga - $kredit;
		$this->crud->set_subject("Transaksi Kredit $jamaah | Paket :$paket | Harga:$harga | Pembayaran:$kredit | Kekurangan: ".$kurang."<br>Transaksi Debet : $debet")->unset_add();
		$this->crud->set_top("Transaksi Kredit $jamaah | Paket :$paket | Harga:$harga | Pembayaran:$kredit | Kekurangan: ".$kurang."<br>Transaksi Debet : $debet");
		$this->crud->unset_read()->columns('jenis_transaksi','keterangan','tanggal','debet','kredit','teller');
		$this->crud->field_type('teller', 'hidden', $ide)
			->field_type('id_transaksi_paket', 'hidden', $id)
			->where('id_transaksi_paket',$id)
			->set_relation('jenis_transaksi','jenis_transaksi_pengeluaran','nama_transaksi')
			->unset_texteditor('keterangan');
		$state= $this->crud->getState();
		// echo "state=$state";
		if($state == 'ajax_list'){
			$this->crud->set_relation('teller','admin','nama');
		}
		$this->crud->unset_edit()->unset_delete();
	    $this->show();
	}
	function note($id_jamaah=0){
        $this->crud = new grocery_CRUD();
		$this->crud->set_table('note')->unset_read()->columns('note','tanggal');
		$ide= $this->session->userdata('id_admin');
		$this->crud->field_type('user', 'hidden', $ide);
		$this->crud->set_theme('twitter-bootstrap')->where('user',$ide);		
	    $this->show();
    }

	private function fungsiCurl($url){
		 $data = curl_init();
		 curl_setopt($data, CURLOPT_RETURNTRANSFER, 1);
		 curl_setopt($data, CURLOPT_URL, $url);
			 curl_setopt($data, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-GB; rv:1.8.1.6) Gecko/20070725 Firefox/2.0.0.6");
		 $hasil = curl_exec($data);
		 curl_close($data);
		 return $hasil;
	}
	function update_kurs($update=0){

		$this->crud = new grocery_CRUD();
		$this->crud->set_table('kurs')->unset_read()->columns('nilai','nilai_namiroh','tanggal','status');
		$ide= $this->session->userdata('id_admin');
		$this->crud->set_relation('status','status_aktif','keterangan')->order_by('tanggal','desc')->display_as('tanggal','Waktu update');
		//get from bank
		$url = $this->fungsiCurl('http://www.bankmandiri.co.id/resource/kurs.asp');
		$pecah = explode('<table class="tbl-view" cellpadding="0" cellspacing="0" border="0" width="100%">', $url);
		$pecah2 = explode ('</table>',$pecah[1]);
		$pecah3 = explode ('<th>&nbsp;</th>', $pecah2[0]);
		//echo( $pecah3[2]);
		$pecah4 = explode ('<td>&nbsp;&nbsp;</td>',$pecah3[2]);
		$kurs =str_replace('<td align="right">',"",$pecah4[29]);
		$kurs =str_replace('</td>',"",$kurs);
		$kurs =str_replace('.',"",$kurs);
		// echo "k=$kurs<br>";
		$kurs = (int)$kurs;
		if($update==1){
			//set all to inactive
			//insert new
			$this->db->update('kurs',array('status'=>2));
			$timezone = 7;
			$data = array(
			   'tanggal' => gmdate("Y-m-d H:i:s", time() + 3600*($timezone)) ,
			   'nilai' => $kurs ,
			   'status'=>1,
			   'nilai_namiroh' => $kurs+50
			);

			$this->db->insert('kurs', $data); 
			redirect('transaksi/update_kurs');
		}
		$kurs +=50;
		$this->crud->set_theme('twitter-bootstrap')->unset_export();
		$output = $this->crud->render();
		// echo "kurs = $kurs";
		$this->load->section('sidebar', 'ci_simplicity/kurs_online',array('kurs'=>$kurs,'kursnamiroh'=>$this->main_model->get_kurs()));
		$this->load->view('ci_simplicity/admin',$output);				//activate it
	}
	function kurs(){
        $this->crud = new grocery_CRUD();
		$this->crud->set_table('kurs')->unset_read()->columns('nilai','nilai_namiroh','tanggal','status');
		$ide= $this->session->userdata('id_admin');
		$this->crud->set_relation('status','status_aktif','keterangan')->order_by('tanggal','desc')->display_as('tanggal','Waktu update');
	    $this->show();
    }
	function log(){
		
	}
	
}
// END Kelas Class

/* End of file kelas.php */
/* Location: ./system/application/controllers/kelas.php */