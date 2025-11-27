<?php
/**
 * Kelas Class
 *
 * @author	Awan Pribadi Basuki <awan_pribadi@yahoo.com>
 */
class Transaksi_Op extends CI_Controller {
	/**
	 * Constructor
	 */
	 var $t = array();
	 var $r = 0;
	 var $crud = null;
	 var $j = array();
	 var $l = '';
	function __construct()
	{
		parent::__construct();
		if ($this->session->userdata('login') != TRUE)
		{
			redirect('login');
		}
		$this->load->database();
        setlocale(LC_MONETARY, 'id_ID');     
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
    function jamaah($id_jamaah,$jenis_transaksi){
        $this->crud = new grocery_CRUD();
		$this->crud->set_table('data_jamaah_paket');
		$this->crud->set_subject('Data Paket Umroh');
		$this->crud->set_theme('twitter-bootstrap');
		$this->crud->set_relation('hotel','data_hotel','nama');
		$this->crud->set_relation('Penerbangan','data_maskapai','nama');
		$this->crud->set_subject('Data Paket Umroh');
	    $this->crud->columns('estimasi_keberangkatan','Program','Penerbangan','hotel','harga','sisa_kursi');
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
		//print_r($row);
		// $this->db->where('paket_umroh', $row->id);
		// $this->db->from('transaksi_paket');
		// $j=$this->db->count_all_results();
		$jumlae = isset($this->j[$row->id])?$this->j[$row->id]:0;
		return "<a href='".site_url('transaksi_op/pembayaran/'.$row->id)."'target='_blank'>$value</a> - <a href='".site_url('transaksi_op/manifest/'.$row->id)."'target='_blank'>$jumlae orang</a>";
    }
	function _harga_rp($value,$row){
		return $this->main_model->get_kurs()*$row->harga_dolar;
	}
	function harga_field_callback_1()
	{
	return '<input type="text" maxlength="50" value="000'.$this->r.'" name="harga" style="width:462px">';
	}	

    function _rupiah($value,$row){
        
		// return number_format((float)$value,0,'.',',');
		return number_format($value,0,",",".");
	} 
	function edit_callback($value,$row){
        $x = $row->jamaah;
		if($value =='')
			$value='?';
        		return "<a href='".site_url('master/jamaah/edit/'.$x)."' target='_blank'>$value</a>";
    }
	function edit_callback1($value,$row){
        $x = $row->jamaah;
        $value = $this->jamaahnya[$x][1];
		if($value =='')
			$value='?';
        		return "<a href='".site_url('master/jamaah/edit/'.$x)."' target='_blank'>$value</a>";
    }
	function edit_callback2($value,$row){
        $x = $row->jamaah;
        $value = $this->jamaahnya[$x][2];
		if($value =='')
			$value='?';
        		return "<a href='".site_url('master/jamaah/edit/'.$x)."' target='_blank'>$value</a>";
    }
    	function edit_callback3($value,$row){
        $x = $row->jamaah;
        $value = $this->jamaahnya[$x][3];
		if($value =='')
			$value='?';
        		return "<a href='".site_url('master/jamaah/edit/'.$x)."' target='_blank'>$value</a>";
    }
    	function edit_callback4($value,$row){
        $x = $row->jamaah;
        $value = $this->jamaahnya[$x][4];
		if($value =='')
			$value='?';
        		return "<a href='".site_url('master/jamaah/edit/'.$x)."' target='_blank'>$value</a>";
    }
    	function edit_callback5($value,$row){
        $x = $row->jamaah;
        $value = $this->jamaahnya[$x][5];
		if($value =='')
			$value='?';
        		return "<a href='".site_url('master/jamaah/edit/'.$x)."' target='_blank'>$value</a>";
    }
    	function edit_callback6($value,$row){
        $x = $row->jamaah;
        $value = $this->jamaahnya[$x][6];
		if($value =='')
			$value='?';
        		return "<a href='".site_url('master/jamaah/edit/'.$x)."' target='_blank'>$value</a>";
    }
    	function edit_callback7($value,$row){
        $x = $row->jamaah;
        $value = $this->jamaahnya[$x][7];
		if($value =='')
			$value='?';
        		return "<a href='".site_url('master/jamaah/edit/'.$x)."' target='_blank'>$value</a>";
    }
    	function edit_callback8($value,$row){
        $x = $row->jamaah;
        $value = $this->jamaahnya[$x][8];
		if($value =='')
			$value='?';
        		return "<a href='".site_url('master/jamaah/edit/'.$x)."' target='_blank'>$value</a>";
    }
    	function edit_callback9($value,$row){
        $x = $row->jamaah;
        $value = $this->jamaahnya[$x][9];
		if($value =='')
			$value='?';
        		return "<a href='".site_url('master/jamaah/edit/'.$x)."' target='_blank'>$value</a>";
    }
	function manifest($paket = 0){
	    $this->l = $paket;
	    $state = $this->crud->getState();
	    
	    
	        $s = $this->get('data_jamaah_paket','id',$paket,'estimasi_keberangkatan').'<br>';
            
    		$d= $this->db->query("select id_jamaah,nama_jamaah,title,place,age, passport,place,issued, passport,expired,issued,office from data_jamaah");
    		// print_r($d);
    		foreach($d->result() as $row){
    			$this->jamaahnya[$row->id_jamaah]=array($row->nama_jamaah,
    			$row->title,$row->age, $row->passport,$row->place,$row->issued,$row->expired,$row->office
    			);
    		}
    		// print_r($this->jamaahnya);
    		$r = $this->get('data_jamaah_paket','id',$paket,'harga');
    		$this->r = $r;
    		$s.='Rp.'.$this->main_model->uang($r);
    		$this->grocery_crud->callback_add_field('harga',array($this,'harga_field_callback_1'))->unset_edit();
    		$this->crud->set_table('transaksi_paket')->set_subject('Manifest Jamaah '.$s)->columns('jamaah','title','age', 'passport','place','issued', 'passport','expired','issued','office');
    		$this->crud->callback_column('title',array($this,'edit_callback1'));
    		$this->crud->callback_column('age',array($this,'edit_callback2'));
    		$this->crud->callback_column('passport',array($this,'edit_callback3'));
    		$this->crud->callback_column('place',array($this,'edit_callback4'));
    		$this->crud->callback_column('issued',array($this,'edit_callback5'));
    		$this->crud->callback_column('expired',array($this,'edit_callback6'));
    		$this->crud->callback_column('office',array($this,'edit_callback7'));
    		$this->crud->field_type('kode', 'readonly')->set_relation('jamaah','data_jamaah','{nama_jamaah}','nama_jamaah <> ""')->display_as('jamaah','Name');
    		
    		$this->crud->where(array('paket_umroh'=>$paket))->unset_add()->unset_edit()->unset_delete();
	    
		
		$this->crud->set_theme('twitter-bootstrap');
		$output = $this->crud->render();
		$this->load->view('ci_simplicity/admin',$output);
	}
	function pembayaran($paket=0,$jamaah =0){
		if($paket==0){
			$this->crud->where('KET','AKTIF');
			$this->crud->set_table('data_jamaah_paket')->columns('estimasi_keberangkatan','tanggal_keberangkatan','Program','harga')->fields('estimasi_keberangkatan','Program','harga','KET')->display_as('estimasi_keberangkatan','Pilih Paket')->unset_delete()->unset_add()->set_subject('Pilih Paket')->order_by('tanggal_keberangkatan','DESC');
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
			$s = $this->get('data_jamaah_paket','id',$paket,'estimasi_keberangkatan').'<br>';
            
            $d= $this->db->query("select id_jamaah,nama_jamaah from data_jamaah");
            // print_r($d);
            foreach($d->result() as $row){
                $this->jamaahnya[$row->id_jamaah]=$row->nama_jamaah;
            }
            // print_r($this->jamaahnya);
			$r = $this->get('data_jamaah_paket','id',$paket,'harga');
			$this->r = $r;
			$s.='Rp.'.$this->main_model->uang($r);
			$this->grocery_crud->callback_add_field('harga',array($this,'harga_field_callback_1'))->unset_edit();
			$this->crud->set_table('transaksi_paket')->set_subject('Pembelian paket umroh '.$s)->columns('jamaah','harga','kredit','kekurangan','debet','saldo','kode','agen');
			$this->crud->field_type('kode', 'readonly')->set_relation('jamaah','data_jamaah','{nama_jamaah}-{no_ktp}-{alamat_jamaah}-{no_tlp}','nama_jamaah <> ""');
            // $this->crud->callback_column('detil',array($this,'__jamaah'));

			// $this->crud->set_relation('paket_umroh','data_jamaah_paket','estimasi_keberangkatan');
			$this->crud->callback_column('harga',array($this,'_rupiah'));
			$this->crud->callback_column('saldo',array($this,'_rupiah'));
			$this->crud->callback_column('kekurangan',array($this,'_kekurangan'));
			$this->crud->callback_column('debet',array($this,'__debet'));
			$this->crud->callback_column('kredit',array($this,'__kredit'));
			$this->crud->set_relation('agen','data_jamaah_agen','nama');
			$this->crud->add_fields(array('jamaah','harga','paket_umroh','kekurangan','harga_normal','agen'));
			$this->crud->callback_before_insert(array($this,'_update_kekurangan'));
			$this->crud->field_type('harga_normal', 'hidden', $r);
			$this->crud->field_type('kekurangan', 'hidden', $r);
			$this->crud->field_type('paket_umroh', 'hidden', $paket)->edit_fields('jamaah','harga');
			$this->crud->data['-tes']='-';
			
			$this->crud->callback_after_insert(array($this, 'fix_code_after_insert'))->where(array('paket_umroh'=>$paket));
		}
		
	    $this->show();
	}
    function __jamaah($value,$row){
        $x = $this->jamaahnya[$value];
        		return "<a href='".site_url('master/jamaah/edit/'.$value)."' target='_blank'>$x</a>";

        
    }
	function arsip_pembayaran($paket=0,$jamaah =0){
		if($paket==0){
			$this->crud->where('KET','ARSIP');
			$this->crud->set_table('data_jamaah_paket')->columns('estimasi_keberangkatan','Program','harga')->fields('estimasi_keberangkatan','Program','harga','KET')->display_as('estimasi_keberangkatan','Pilih Paket')->unset_delete()->unset_add()->set_subject('Pilih Paket');
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
			$s = $this->get('data_jamaah_paket','id',$paket,'estimasi_keberangkatan').'<br>';
			$r = $this->get('data_jamaah_paket','id',$paket,'harga');
			// $kurs = $this->main_model->get_kurs();
			// $s.=$dolar.'<br>';
			// $r=ceil($kurs*$dolar/1000)*1000;
			$this->r = $r;
			$s.='Rp.'.$this->main_model->uang($r);
			$this->grocery_crud->callback_add_field('harga',array($this,'harga_field_callback_1'))->unset_edit()->unset_delete();
			$this->crud->set_table('transaksi_paket')->set_subject('Pembelian paket umroh '.$s)->columns('jamaah','harga','kredit','kekurangan','debet','saldo','kode','agen');
			$this->crud->field_type('kode', 'readonly');
			// $this->crud->set_relation('paket_umroh','data_jamaah_paket','estimasi_keberangkatan');callback_column('jamaah',array($this,'__jamaah'))->
			$this->crud->callback_column('kekurangan',array($this,'_kekurangan'));
			$this->crud->callback_column('debet',array($this,'__debet'));
			$this->crud->callback_column('kredit',array($this,'__kredit'));
			$this->crud->set_relation('agen','data_jamaah_agen','nama');
			$this->crud->add_fields(array('jamaah','harga','paket_umroh','kekurangan','harga_normal','agen'));
			$this->crud->callback_before_insert(array($this,'_update_kekurangan'));
			$this->crud->field_type('harga_normal', 'hidden', $r);
			$this->crud->field_type('kekurangan', 'hidden', $r);
			$this->crud->field_type('paket_umroh', 'hidden', $paket);
			$this->crud->data['-tes']='-';
			
			$this->crud->callback_after_insert(array($this, 'fix_code_after_insert'))->where(array('paket_umroh'=>$paket))->unset_edit()->unset_delete();
		}
		
	    $this->show();
	}
	
	public function _kekurangan($value, $row)
	{
		return "<a href='".site_url('transaksi_op/histori/'.$row->id)."' target='_blank'>".$this->_rupiah($value,$row)."</a>";
	}
	public function __debet($value, $row)
	{
		return "<a href='".site_url('transaksi_op/debet/'.$row->id)."'  target='_blank'>".$this->_rupiah($value,$row)."</a> <a href='".site_url('transaksi_op/debet/'.$row->id)."/add'  target='_blank'>+</a>";
	}
	public function __kredit($value, $row)
	{
		return "<a href='".site_url('transaksi_op/kredit/'.$row->id)."'  target='_blank'>".$this->_rupiah($value,$row)."</a> <a href='".site_url('transaksi_op/kredit/'.$row->id)."/add'  target='_blank'>+</a>";
	}
	public function __kuitansi_kredit($value, $row)
	{
		$value = $this->rp($value);
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
	        redirect('transaksi_op/pembayaran');
		$this->crud->set_table('pembayaran_transaksi_paket');
		$j = $this->get_row('transaksi_paket','id',$id);
		$jamaah = $this->get('data_jamaah','id_jamaah',$j->jamaah,'nama_jamaah');
		$ide= $this->session->userdata('id_admin');
		// $this->crud->callback_column('debet',array($this,'__kuitansi_kredit'));
		$p = $this->get_row('data_jamaah_paket','id',$j->paket_umroh);
		$paket = $p->estimasi_keberangkatan;
		$harga = $j->harga;
		list($debet,$kredit) = $this->get_sum($id,$harga);
		$kurang = $harga - $kredit;
		$saldo = $kredit - $debet;
		$this->crud->set_subject("Transaksi Debet $jamaah | Paket :$paket | Harga:$harga | Pembayaran:$kredit | Kekurangan: ".$kurang."<br>Transaksi Debet : $debet | saldo = $saldo");
		// $this->crud->set_subject("Debet Transaksi $jamaah<br>Paket :$paket<br>Harga:$harga <br>Kekurangan: ".$j->kekurangan);
		$this->crud->columns('jenis_transaksi','keterangan','tanggal','tanggal_transfer','debet','teller');
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
		$this->crud->fields('id_transaksi_paket','jenis_transaksi','tanggal','tanggal_transfer','debet','keterangan','teller')->unset_edit()->unset_delete();
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
	function rp($value){
		return number_format($value,0,",",".");

        // return number_format((float)$value,0,'.',',');
    }
    function kredit($id=0){
	    if($id==0) 
	        redirect('transaksi_op/pembayaran');
		$this->crud->set_table('pembayaran_transaksi_paket');
		$j = $this->get_row('transaksi_paket','id',$id);
		$jamaah = $this->get('data_jamaah','id_jamaah',$j->jamaah,'nama_jamaah');
		//
		$this->crud->callback_column('kredit',array($this,'__kuitansi_kredit'));
		$ide= $this->session->userdata('id_admin');
		$p = $this->get_row('data_jamaah_paket','id',$j->paket_umroh);
		$paket = $p->estimasi_keberangkatan;
		$harga = $j->harga;
		
		list($debet,$kredit) = $this->get_sum($id,$harga);
		$kurang = $harga - $kredit;
		// $this->crud->set_subject("Kredit Transaksi $jamaah<br>Paket :$paket<br>Harga:$harga <br>Pembayaran:$kredit<br>Kekurangan: ".$kurang."<br>Transaksi Debet : $debet");
        $kurang   = $this->rp($kurang);
        $harga   = $this->rp($harga);
        $debet   = $this->rp($debet);
		$this->crud->set_subject("Transaksi Kredit $jamaah | Paket :$paket | Harga:$harga | Pembayaran:$kredit | Kekurangan: ".$kurang."<br>Transaksi Debet : $debet");
		$this->crud->columns('jenis_transaksi','keterangan','tanggal','tanggal_transfer','kredit','teller');
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
		$this->crud->fields('id_transaksi_paket','jenis_transaksi','tanggal','tanggal_transfer','kredit','keterangan','teller')->unset_edit();
	    $this->show();
	}
	
	function histori($id=0){
		$this->crud->set_table('pembayaran_transaksi_paket');
		$j = $this->get_row('transaksi_paket','id',$id);
		$jamaah = $this->get('data_jamaah','id_jamaah',$j->jamaah,'nama_jamaah');
		
		$ide= $this->session->userdata('id_admin');
		$p = $this->get_row('data_jamaah_paket','id',$j->paket_umroh);
		$paket = $p->estimasi_keberangkatan;
		$harga = $j->harga;
		list($debet,$kredit) = $this->get_sum($id,$harga);
		$kurang = $harga - $kredit;
		$this->crud->set_subject("Transaksi Kredit $jamaah | Paket :$paket | Harga:$harga | Pembayaran:$kredit | Kekurangan: ".$kurang."<br>Transaksi Debet : $debet")->unset_add();
		$this->crud->columns('jenis_transaksi','keterangan','tanggal','debet','kredit','teller');
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
		$this->crud->set_table('note')->columns('note','tanggal');
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
		$this->crud->set_table('kurs')->columns('nilai','nilai_namiroh','tanggal','status');
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
			redirect('transaksi_op/update_kurs');
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
		$this->crud->set_table('kurs')->columns('nilai','nilai_namiroh','tanggal','status');
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