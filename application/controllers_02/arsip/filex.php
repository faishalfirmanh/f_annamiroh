<?php
class Filex extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->helper('url');
	}
		 
	public function index()
	{
		$this->load->view('welcome_message');
	}
		 
	public function elfinder()
	{
		require_once './assets/elfinder/php/elFinderConnector.class.php';
		require_once './assets/elfinder/php/elFinder.class.php';
		require_once './assets/elfinder/php/elFinderVolumeDriver.class.php';
		require_once './assets/elfinder/php/elFinderVolumeLocalFileSystem.class.php';
		 
		$conn = new elFinderConnector(new elFinder(array(
			'roots'=>array(
				array(
					'driver'=>'LocalFileSystem',
					'path'=>'./myfile',
					'URL'=>base_url('myfile').'/',
				)
			)
		)));
		$conn->run();
	}
		
}
/* End of file welcome.php */
/* Location: ./system/application/controllers/welcome.php */