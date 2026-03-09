<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Master_bank_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    private $table = 'master_bank';

    public function insert_link($data)
    {
        $this->db->insert($this->table,$data);
        return $this->db->insert_id();
    }

    public function get_single_data($column,$value)
    {
        $this->db->where($column, $value);
        $query = $this->db->get($this->table);
        return $query->row(); 
    }

    
	 public function getBankActive()
    {
		$this->db->where('is_active', '1');
        $query = $this->db->get($this->table);
        return $query->result();
    }   
    
}