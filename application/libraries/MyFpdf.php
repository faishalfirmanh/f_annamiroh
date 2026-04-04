<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . 'libraries/FPDF/fpdf.php';

class MyFpdf extends FPDF
{
    public function __construct()
    {
        parent::__construct();
    }
}