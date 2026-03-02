<?php

/**
 * Kelas Class
 *
 * @author	Awan Pribadi Basuki <awan_pribadi@yahoo.com>
 */
class GlobalController extends CI_Controller
{

    function __construct()
    {
        parent::__construct(); 
        $this->load->database();
        $this->load->helper('url');
        // Jika flashdata digunakan, pastikan library session sudah terload
        $this->load->library('session');
    }
    
   
    public function approve_transaksi($id_pembayaran, $id_paket)
    {
        $this->db->where('id', $id_pembayaran);
        $this->db->update('pembayaran_transaksi_paket', array('status_pembayaran' => 1));
        // 2. Set pesan sukses
        $this->session->set_flashdata('message', 'Pembayaran Berhasil di Approve!');

        // 3. Kembalikan ke halaman kredit sebelumnya
        redirect('transaksi_op/kredit/' . $id_paket);
    }   

}
