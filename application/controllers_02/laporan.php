<?php
/**
 * Kelas Class
 *
 * @author	Moch Yasin
 */
class Laporan extends CI_Controller {
	/**
	 * Constructor
	 */
	 var $j = array();
	 var $paketnya = array();
     var $group_rev = array();
    var $crud = null;
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
		$this->load->model('laporan_model', '', TRUE);
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
		
		
		$d= $this->db->query("select id_jamaah, nama_jamaah from data_jamaah");
		foreach($d->result() as $row){
		    $this->j[$row->id_jamaah] = $row->nama_jamaah;
		}
        
	}
	private function show($module  = ''){
		$this->crud->set_theme('tanggal')->unset_export();
		$output = $this->crud->render();
		$this->load->view('ci_simplicity/admin',$output);
	}
	function index()
	{
		redirect('master/jamaah');
	}
/*


*/
	function unique_field_name($field_name) {
	    return 's'.substr(md5($field_name),0,8); //This s is because is better for a string to begin with a letter and not with a number
    }
    function bulanan($tahun = 0,$bulan = 0){
        if($tahun == 0 && $bulan == 0 ){
            $this->crud->set_table('v_rekap_bulanan');
            $this->crud->set_primary_key('id');
            $this->crud->set_subject('Data Transaksi Bulanan')->unset_add()->unset_edit()->unset_delete();
            // $this->crud->set_relation('jenis_transaksi','jenis_transaksi_pengeluaran','nama_transaksi')->columns('tahun','bulan','jenis_transaksi','debet','kredit');
            $this->crud->set_relation('bulan','bulan','bulan');
            //print_r($this->j);
               
            $this->crud->callback_column('bulan',array($this,'_callback_bulanan'));

             $this->crud->callback_column($this->unique_field_name('bulan'),array($this,'_callback_bulanan'));
        }else{
            $this->crud->set_table('pembayaran_transaksi_paket');
            $this->crud->set_subject('Data Transaksi Harian')->unset_add()->unset_edit()->unset_delete()->where('year(tanggal)',$tahun)->where('month(tanggal)',$bulan);
            $this->crud->set_relation('teller','admin','nama')->set_relation('jenis_transaksi','jenis_transaksi_pengeluaran','nama_transaksi')->set_relation('id_transaksi_paket','transaksi_paket','{jamaah}-{kode}')->columns('id','id_transaksi_paket','tanggal','tanggal_transfer','debet','kredit','jenis_transaksi','keterangan','teller')->display_as('id','Nomor Kuitansi')->display_as('id_transaksi_paket','Jamaah / Kode Booking');
            // print_r($this->j);
            $this->crud->callback_column($this->unique_field_name('id_transaksi_paket'),array($this,'_jamaah'));;
		
        }
        $this->crud->callback_column('debet',array($this,'_rupiah'));
        $this->crud->callback_column('kredit',array($this,'_rupiah')); 
        
		$this->show();
    }

    function tahunan($tahun = 0,$bulan = 0){
        //tahun	bulan	jenis_transaksi	debet	kredit
        //select tahun,jenis_transaksi,sum(debet) as debet, sum(kredit) as kredit from v_rekap_bulanan group by tahun, jenis_transaksi 
        $this->crud->set_table('v_rekap_tahunan');
        $this->crud->set_primary_key('tahun');
		$this->crud->set_subject('Data Transaksi Tahunan')->unset_add()->unset_edit()->unset_delete();
		// $this->crud->set_relation('jenis_transaksi','jenis_transaksi_pengeluaran','nama_transaksi')->columns('tahun','debet','kredit');
		//print_r($this->j);
        $this->crud->callback_column('debet',array($this,'_rupiah'));
        $this->crud->callback_column('kredit',array($this,'_rupiah'));
		$this->show();
    }
   
    function _rupiah($value,$row){
		return 'Rp. '.number_format((float)$value);
        
        
        //return 9;//$this->main_model->get_kurs()*$row->harga_dolar;
	} 
    function _harga_rp($value,$row){
		if($row->harga == null) return 0;
		return number_format((float)$row->harga);
        
        
        //return 9;//$this->main_model->get_kurs()*$row->harga_dolar;
	}
    public function _callback_webpage_url($value, $row)
    {
		$jumlae = isset($this->j[$row->id])?$this->j[$row->id]:0;
		return "<a href='".site_url('transaksi/pembayaran/'.$row->id)." target=\"_blank\"'>$value-$jumlae orang</a>";
    }
    public function _callback_bulanan($value, $row)
    {
        return "<a href='".site_url('laporan/bulanan/'.$row->tahun.'/'.$row->bulan)."' target=\"_blank\">$value</a>";
    }
    public function _callback_pemasukan($value, $row)
    {
		/*
        tabel: pembayaran_transaksi_paket(id_transaksi_paket , debet, saldo
        tabel:transaksi_paket: id, paket_umroh. 
        get all id_from transaksi paket., lalu dari pembayaran transaksi paket, disum.
        */
        if(isset($this->group_rev[$row->id])) 
            return "<a href='".site_url('transaksi/pembayaran/'.$row->id)."' target='_blank'>".
        number_format((float)$this->group_rev[$row->id])."</a>";
        else return 0;
        
    }
    
    function keberangkatan($paket=0,$jamaah =0){
        if($paket==0){
            $this->group_rev = $this->laporan_model->get_pendapatan_keberangkatan();
			$this->crud->set_table('data_jamaah_paket')->columns('estimasi_keberangkatan','Program','harga','pemasukan')->unset_edit()->display_as('estimasi_keberangkatan','Pilih Paket')->unset_delete()->unset_add()->set_subject('Pilih Paket');
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
			$this->crud->callback_column('pemasukan',array($this,'_callback_pemasukan'));
			$this->crud->callback_column('harga',array($this,'_harga_rp'));
            
                        

		}
		elseif($jamaah==0){
			$s = $this->get('data_jamaah_paket','id',$paket,'estimasi_keberangkatan').'<br>$';
			// $dolar = $this->get('data_jamaah_paket','id',$paket,'harga_dolar');
			// $kurs = $this->main_model->get_kurs();
			// $s.=$dolar.'<br>';
			// $r=ceil($kurs*$dolar/1000)*1000;
			// $this->r = $r;
			// $s.='Rp.'.$this->main_model->uang($r);
            
			$this->grocery_crud->callback_add_field('harga',array($this,'harga_field_callback_1'));
			$this->crud->set_table('transaksi_paket')->set_subject('Pembelian paket umroh '.$s)->set_relation('jamaah','data_jamaah','{nama_jamaah}-{no_ktp}','nama_jamaah <> ""')->columns('jamaah','harga','kredit','kekurangan','debet','saldo','kode','agen');
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
			
			$this->crud->callback_after_insert(array($this, 'fix_code_after_insert'))->where(array('paket_umroh'=>$paket));
		}
		
	    $this->show();
    }
	function harian(){
		$this->crud->set_table('pembayaran_transaksi_paket');
		$this->crud->set_subject('Data Transaksi Harian')->unset_add()->unset_edit()->unset_delete();
		$this->crud->set_relation('teller','admin','nama')->set_relation('jenis_transaksi','jenis_transaksi_pengeluaran','nama_transaksi')->set_relation('id_transaksi_paket','transaksi_paket','{jamaah}-{kode}')->columns('id','id_transaksi_paket','tanggal','tanggal_transfer','debet','kredit','jenis_transaksi','keterangan','teller')->display_as('id','Nomor Kuitansi')->display_as('id_transaksi_paket','Jamaah / Kode Booking / Paket Umroh');
		//print_r($this->j);
		$this->crud->callback_column($this->unique_field_name('id_transaksi_paket'),array($this,'_jamaah'));;
		$query = $this->db->query("SELECT CONCAT (estimasi_keberangkatan,'-',Program) AS paket,a.id FROM transaksi_paket a 
			LEFT JOIN data_jamaah_paket b
			ON a.paket_umroh = b.id");
		foreach ($query->result_array() as $row)
		{
				$this->paketnya[$row['id']] = $row['paket'];
		}
		
		/*
		data_jamaah_paket -->
			transaksi_paket --> 
				pembayaran_transaksi_paket
		*/
		// print_r($this->paketnya);
		$this->show();
		
	}

    function _jamaah($value,$row){
		
        if($value){
            $d= (explode("-",$value));
			// echo "Jamaah: <br>".$d[1];
			// print_r($row);
            if(isset($d[0]) && isset($d[1]) && isset($this->j[$d[0]])){
				$x = base_convert($d[1],36,10);
				$paket = $this->paketnya[$x];
				if(isset($paket))
					return $this->j[$d[0]].'/'.$d[1].'/'.$paket;
				return $this->j[$d[0]].'/'.$d[1].'/-';
			}
        	
        }
        return "-";
        
	}
	function log(){
		
	}
	
}
// END Kelas Class

/* End of file kelas.php */
/* Location: ./system/application/controllers/kelas.php */