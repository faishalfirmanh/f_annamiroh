<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Location_model extends CI_Model
{

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

    public function get_last_id()
    {
        $row = $this->db->select_max('id')->get('location_villages')->row();
        return ($row && $row->id) ? $row->id + 1 : 1;
    }

    public function get_location_path($id_kecamatan)
    {
        $this->db->select('location_districts.id as id_kecamatan,
                        location_districts.kabupaten_id as id_kota,
                        location_city.id_prov as id_provinsi');
        $this->db->from('location_districts');
        $this->db->join('location_city', 'location_city.id = location_districts.kabupaten_id');
        $this->db->where('location_districts.id', $id_kecamatan);
        return $this->db->get()->row();
    }
}