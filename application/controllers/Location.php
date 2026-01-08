<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Location extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Location_model');
        $this->load->model('Ref_Imigrasi_model','imigrasi');
        $this->load->helper('url');
    }

    // Halaman Utama View
    public function index()
    {
        $this->load->view('location_view');
    }

    // API: Get Data Provinsi
    public function api_provinces()
    {
        $data = $this->Location_model->get_provinces();
        echo json_encode($data);
    }
    
     public function api_imigrasi()
    {
        $data = $this->imigrasi->getAll();
        echo json_encode($data);
    }
    
    public function api_imigrasiById()
    {
         $idIm = $this->input->post('id');
        $data = $this->imigrasi->getById($idIm);
        echo json_encode($data);
    }

    // API: Get Data Kota (POST id_prov)
    public function api_cities()
    {
        $id_prov = $this->input->post('id_prov');
        $data = $this->Location_model->get_cities($id_prov);
        echo json_encode($data);
    }

    // API: Get Data Kecamatan (POST id_city)
    public function api_districts()
    {
        $id_city = $this->input->post('id_city');
        $data = $this->Location_model->get_districts($id_city);
        echo json_encode($data);
    }

    // API: Get Data Desa (POST id_district)
    public function api_villages()
    {
        $id_district = $this->input->post('id_district');
        $data = $this->Location_model->get_villages($id_district);
        echo json_encode($data);
    }
}