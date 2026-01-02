<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Location_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    // Ambil Semua Provinsi
    public function get_provinces()
    {
        $this->db->order_by('name', 'ASC');
        return $this->db->get('location_provinces')->result();
    }

    // Ambil Kota berdasarkan ID Provinsi
    public function get_cities($prov_id)
    {
        $this->db->where('id_prov', $prov_id); // Sesuai struktur tabel Anda
        $this->db->order_by('name', 'ASC');
        return $this->db->get('location_city')->result();
    }

    // Ambil Kecamatan berdasarkan ID Kota/Kabupaten
    public function get_districts($city_id)
    {
        $this->db->where('kabupaten_id', $city_id); // Sesuai struktur tabel Anda
        $this->db->order_by('name', 'ASC');
        return $this->db->get('location_districts')->result();
    }

    // Ambil Desa berdasarkan ID Kecamatan
    public function get_villages($dist_id)
    {
        $this->db->where('id_kecamatan', $dist_id); // Sesuai struktur tabel Anda
        $this->db->order_by('name', 'ASC');
        return $this->db->get('location_villages')->result();
    }
}