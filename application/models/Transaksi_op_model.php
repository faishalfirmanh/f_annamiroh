<?php
class Transaksi_op_model extends CI_Model {
    
    function __construct(){
        parent::__construct();
    }

    function get_by_no_ktp($no_ktp = []){
        $data = $this->db->select('nama_jamaah, title, passport, expired, issued, office, tgl_lahir, no_tlp, hp_jamaah, alamat_jamaah, no_ktp')->from('data_jamaah')
        ->where_in('no_ktp', $no_ktp)->get()->result();
        return $data;
    }

    function add($data){
        $data = $this->db->insert_batch('data_jamaah', $data);
        return $data;
    }

}