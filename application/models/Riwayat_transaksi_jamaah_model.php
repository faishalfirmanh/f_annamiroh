<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Riwayat_transaksi_jamaah_model extends CI_Model
{

    /**
     * Mengambil data transaksi paket secara detail untuk satu jamaah.
     * @param string $id_jamaah ID Jamaah sebagai parameter filtering.
     * @return array Hasil query dalam bentuk array objek.
     */
    public function get_data_riwayat($id_jamaah)
    {
        $sql = "SELECT 
                    ptp.id_transaksi_paket, 
                    ptp.tanggal, 
                    ptp.tanggal_transfer, 
                    ptp.debet, 
                    ptp.kredit, 
                    ptp.saldo, 
                    ptp.keterangan, 
                    jt.nama_transaksi, 
                    dj.nama_jamaah,
                    CONCAT(djp.estimasi_keberangkatan, ' ', djp.Program) AS nama_paket 
                FROM 
                    pembayaran_transaksi_paket ptp 
                JOIN 
                    transaksi_paket tp ON ptp.id_transaksi_paket = tp.id 
                LEFT JOIN 
                    jenis_transaksi jt ON ptp.jenis_transaksi = jt.id 
                JOIN 
                    data_jamaah dj ON tp.jamaah = dj.id_jamaah 
                JOIN 
                    data_jamaah_paket djp ON tp.paket_umroh = djp.id 
                WHERE 
                    dj.id_jamaah = ? 
                ORDER BY 
                    ptp.tanggal_transfer DESC";

        // Menggunakan Query Binding (?) untuk keamanan (SQL Injection protection)
        $query = $this->db->query($sql, array($id_jamaah));

        return $query->result_array(); // Grocery CRUD biasanya lebih suka array
    }
}