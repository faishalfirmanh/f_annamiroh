<?php
/**
 * Login_model Class
 *
 * @author	Awan Pribadi Basuki <awan_pribadi@yahoo.com>
 */
class Fungsi_model extends CI_Model {
	/**
	 * Constructor
	 */
	function __construct()
	{
		parent::__construct();
	}
		
	function uang($uang){
	  $rp = "";
	  $digit = strlen($uang);
	  
	  while($digit > 3) {
		$rp = "." . substr($uang,-3) . $rp;
		$lebar = strlen($uang) - 3;
		$uang  = substr($uang,0,$lebar);
		$digit = strlen($uang);  
	  }
	  $rp = $uang . $rp . ",-";
	  return $rp;
	}
}
// END Login_model Class

/* End of file login_model.php */ 
/* Location: ./system/application/model/login_model.php */