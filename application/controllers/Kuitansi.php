<?php
/**
 * Kelas Class
 *
 * @author	Awan Pribadi Basuki <awan_pribadi@yahoo.com>
 */
class Kuitansi extends CI_Controller {
	/**
	 * Constructor
	 */
	 var $t = array();
	 var $r = 0;
	 var $crud = null;
	 var $j = array();
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
		// $this->load->library('grocery_CRUD');
		// $this->_init();
	}

	/**
	 * Memeriksa user state, jika dalam keadaan login akan menampilkan halaman kelas,
	 * jika tidak akan meredirect ke halaman login
	 */
	function index()
	{
		redirect('master/jamaah');
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

	function _harga_rp($value,$row){
		return $this->main_model->get_kurs()*$row->harga_dolar;
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
		//echo "kredit = $kredit debet = $debet";
		$kredit = floatval($kredit);
		$debet = floatval($debet);
		$harga = floatval($harga);
		$saldo = $kredit - $debet;
		$this->db->update('transaksi_paket',array('kekurangan'=>($harga - $kredit),'debet'=>$debet,'kredit'=>$kredit,'saldo'=>$saldo),array('id'=>$id_transaksi_paket));
		//echo $this->db->last_query();
		// $this->db->update('transaksi_paket',array('kode'=>base_convert($primary_key,10,36)),array('id'=>$primary_key));
		return array($debet,$kredit);
	}
	function kredit($id_kuitansi){
		$this->load->helper('date');
		$j = $this->get_row('pembayaran_transaksi_paket','id',$id_kuitansi);
        $untuk = $this->get('jenis_transaksi','id',$j->jenis_transaksi,'nama_transaksi') 
            ??  $this->get('jenis_transaksi_pengeluaran','id',$j->jenis_transaksi,'nama_transaksi');
        //$un2 =         var_dump($untuk);
        //die();
        $teksLower = strtolower($j->keterangan);
        $keterangan = str_replace("-man", "", $teksLower);

		//$keterangan = $j->keterangan;
		$teller = $this->get('admin','id_admin',$j->teller,'nama_admin');
		$k = $this->get_row('transaksi_paket','id',$j->id_transaksi_paket);
		$jamaah = $this->get('data_jamaah','id_jamaah',$k->jamaah,'nama_jamaah');
		$p = $this->get_row('data_jamaah_paket','id',$k->paket_umroh);
		$paket = $p->estimasi_keberangkatan;
		$harga = $p->harga;
		list($debet,$kredit) = $this->get_sum($j->id_transaksi_paket,$harga);
		$this->load->view('kuitansi/main',array('keterangan'=>$keterangan,'jamaah'=>$jamaah,'paket'=>$paket,'jumlah'=>$j->kredit,'banyaknya'=>$this->main_model->uang($j->kredit),'untuk'=>$untuk,'no'=>$j->id,'tanggal'=>$j->tanggal,'teller'=>$teller));
	}



function debit($id_kuitansi)
{
    $this->load->helper('date'); // bisa di-autoload kalau sering dipakai

    $sql = "SELECT 
                ptp.id,
                ptp.debet,
                ptp.kredit,
                ptp.keterangan,
                ptp.tanggal,
                ptp.receiver_debit,
                jtp.nama_transaksi AS untuk,
                adm.nama_admin AS teller,
                COALESCE(dj.nama_jamaah, dj2.nama_jamaah) AS nama,           -- ← hanya kolom yang tidak NULL
                djp.estimasi_keberangkatan AS paket,
                djp.harga
            FROM pembayaran_transaksi_paket ptp
            JOIN jenis_transaksi_pengeluaran jtp ON ptp.jenis_transaksi = jtp.id
            JOIN admin adm ON ptp.teller = adm.id_admin
            JOIN transaksi_paket tp ON ptp.id_transaksi_paket = tp.id
            LEFT JOIN data_jamaah dj ON tp.jamaah = dj.id_jamaah
            LEFT JOIN data_jamaah dj2 ON tp.agen = dj2.id_jamaah
            JOIN data_jamaah_paket djp ON tp.paket_umroh = djp.id
            WHERE ptp.id = ?";

    $j = $this->db->query($sql, array($id_kuitansi))->row();

    //var_dump($j);
    //die();
    // Kalau data tidak ditemukan
    if (empty($j)) {
        show_404(); // atau return error sesuai kebutuhan
    }

    // get_sum tetap dipanggil (kalau masih dibutuhkan)
    list($debet, $kredit) = $this->get_sum($j->id, $j->harga);

    $this->load->view('kuitansi/debit', [
        'keterangan'   => $j->keterangan,
        'jamaah'       => $j->nama,                    // ← tetap kompatibel dengan view lama
        'paket'        => $j->paket,
        'jumlah'       => $j->debet,
        'banyaknya'    => $this->main_model->uang($j->kredit),
        'untuk'        => $j->untuk,
        'no'           => $j->id,
        'tanggal'      => $j->tanggal,
        'custom_query' => (object) [                   // ← custom_query tetap ada
            'nama'                  => $j->nama,       // gunakan ini di view
            'estimasi_keberangkatan'=> $j->paket,
            'debet'                 => $j->debet
        ],
        'teller'       => $j->teller,
        'receiver'     => $j->receiver_debit,
        'receiver_dinamis' => $j->nama
    ]);
}


    //function debit lama
	function debitOld($id_kuitansi){
		$this->load->helper('date');
		$j = $this->get_row('pembayaran_transaksi_paket','id',$id_kuitansi);
		$untuk = $this->get('jenis_transaksi_pengeluaran','id',$j->jenis_transaksi,'nama_transaksi');
		$keterangan = $j->keterangan;
        $teller = $this->get('admin','id_admin',$j->teller,'nama_admin');
		$k = $this->get_row('transaksi_paket','id',$j->id_transaksi_paket);
		$jamaah = $this->get('data_jamaah','id_jamaah',$k->jamaah,'nama_jamaah');
		$p = $this->get_row('data_jamaah_paket','id',$k->paket_umroh);
		$paket = $p->estimasi_keberangkatan;
        $harga = $p->harga;

        $sql = "SELECT ptp.id, ptp.debet, dj.nama_jamaah AS 'jamaah', 
                   dj2.nama_jamaah AS 'agen', djp.estimasi_keberangkatan  
            FROM pembayaran_transaksi_paket ptp 
            JOIN transaksi_paket tp ON ptp.id_transaksi_paket = tp.id 
            JOIN data_jamaah_paket djp ON tp.paket_umroh = djp.id 
            LEFT JOIN data_jamaah dj ON tp.jamaah = dj.id_jamaah 
            LEFT JOIN data_jamaah dj2 ON tp.agen = dj2.id_jamaah 
            WHERE ptp.id = ?";

        $query_custom = $this->db->query($sql, array($id_kuitansi))->row();

		list($debet,$kredit) = $this->get_sum($j->id_transaksi_paket,$harga);
		// author: irul, jumah is debet
        $this->load->view('kuitansi/debit',array('keterangan'=>$keterangan,
            'jamaah'=>$jamaah,
            'paket'=>$paket,
            'jumlah'=>$j->debet,
            'banyaknya'=>$this->main_model->uang($j->kredit),
            'untuk'=>$untuk,
            'no'=>$j->id,
            'tanggal'=>$j->tanggal,
            'custom_query'=>$query_custom,
            'teller'=>$teller, 'receiver' => $j->receiver_debit));
	}	
    //	
}
