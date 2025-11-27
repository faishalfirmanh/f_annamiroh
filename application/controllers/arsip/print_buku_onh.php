<?php
class Print_buku_onh extends CI_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->model('Print_buku_onh_model');
		$this->load->helper('url');
	}
	public function index() {
		$data['member'] = $this->Print_buku_onh_model->alldata();
		$this->load->view('pdf_jamaah_view', $data);
	}

	function topdf () {
		$this->load->library('cezpdf');
		$this->load->helper('pdf');
		//prep_pdf();
		$data['member']= $this->pdf_jamaah_model->alldata();
		$titlecolumn = array(
							'id_jamaah' => 'id_jamaah',
							'nama_jamaah' => 'nama_jamaah'
		);
		$this->cezpdf->ezTable($data['member'], $titlecolumn,'Data Jamaah');
		$this->cezpdf->ezStream();
	} 
}
?>