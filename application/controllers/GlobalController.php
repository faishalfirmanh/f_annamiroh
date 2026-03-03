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
    
   
   // public function approve_transaksi($id_pembayaran, $id_paket)
    //{
    //    $this->db->where('id', $id_pembayaran);
     //   $this->db->update('pembayaran_transaksi_paket', array('status_pembayaran' => 1));
        // 2. Set pesan sukses
     //   $this->session->set_flashdata('message', 'Pembayaran Berhasil di Approve!');

        // 3. Kembalikan ke halaman kredit sebelumnya
       // redirect('transaksi_op/kredit/' . $id_paket);
    //}
    //
public function approve_transaksi()
{
    // Ambil data dari POST
    $id_pembayaran = $this->input->post('id_pembayaran');
    $id_paket      = $this->input->post('id_paket');
    $keterangan    = $this->input->post('keterangan');

    if (!$id_pembayaran || !$keterangan) {
        redirect('transaksi_op/kredit/' . $id_paket);
        return;
    }

    // 1. Ambil keterangan lama jika ingin digabungkan (opsional)
    $old_data = $this->db->get_where('pembayaran_transaksi_paket', ['id' => $id_pembayaran])->row();
    $keterangan_baru = $old_data->keterangan . "-man " . $keterangan;

    // 2. Update status dan keterangan
    $this->db->where('id', $id_pembayaran);
    $this->db->update('pembayaran_transaksi_paket', array(
        'status_pembayaran' => 1,
        'keterangan'        => $keterangan_baru
    ));

    $this->session->set_flashdata('message', 'Pembayaran Berhasil di Approve dengan Catatan!');
    redirect('transaksi_op/kredit/' . $id_paket);
}

}
