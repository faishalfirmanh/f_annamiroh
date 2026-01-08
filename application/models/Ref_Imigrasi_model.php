<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ref_Imigrasi_model extends CI_Model {

    protected $table = 'ref_imigrasi';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Ambil semua data imigrasi
     */
    public function getAll()
    {
        return $this->db
            ->order_by('nama_imigrasi', 'ASC')
            ->get($this->table)
            ->result();
    }

    /**
     * Ambil 1 data imigrasi berdasarkan ID
     */
    public function getById($id)
    {
        if (empty($id)) {
            return null;
        }

        return $this->db
            ->where('id', (int)$id)
            ->get($this->table)
            ->row();
    }
}
