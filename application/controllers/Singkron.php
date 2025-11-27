<?php
/**
 * Kelas Class
 *
 * @author	Awan Pribadi Basuki <awan_pribadi@yahoo.com>
 */
class Singkron extends CI_Controller {
	/**
	 * Constructor
	 */
	function __construct()
	{
		parent::__construct();
	}
	function index(){
		if(!set_time_limit(7200)) echo 'time limit minimal.';;
		$semua = array('adm','adm_log','all_log_table','bank','bank2','data_jamaah','data_jamaah_log','data_log_log','kabupaten','kabupaten_log','kbih','kbih_log','kecamatan','kecamatan_log','kelengkapan_data','layanan','perubahan','phonebook','semua_data','semua_data1','semua_data1_log','setoran','status_jamaah','talangan','tb_pembayaran','tb_pembayaran_log','th_berangkat');
		echo '<div id="progress" style="width:300px;border:1px solid #ccc;"></div>
<!-- Progress information -->
<div id="information" style="width"></div>';
		$i=1;
		$total = count($semua)*2;
		foreach($semua as $a){
			$this->proses($a);
			$percent = intval($i/$total * 100)."%";
			// if ( !PHP_SAPI === 'cli' ){
			echo '<script language="javascript">
				document.getElementById("progress").innerHTML="<div style=\"width:'.$percent.';background-color:#ddd;\">&nbsp;</div>";
				document.getElementById("information").innerHTML="'.$i++.' dari '.(int)($total/2).' tabel sudah diupload.";
				</script>';
				// This is for the buffer achieve the minimum size in order to flush data
				echo str_repeat(' ',1024*64);
				// Send output to browser immediately
				flush();
				// Sleep one second so we can see the delay
				sleep(1);
			// }
		}
		foreach($semua as $a){
			$this->proses_file($a);
			$percent = intval($i/$total * 100)."%";
			// if ( !PHP_SAPI === 'cli' ){
			echo '<script language="javascript">
				document.getElementById("progress").innerHTML="<div style=\"width:'.$percent.';background-color:#ddd;\">&nbsp;</div>";
				document.getElementById("information").innerHTML="'.$i++.' dari '.$total.' tabel sudah disingkronkan.";
				</script>';
			// This is for the buffer achieve the minimum size in order to flush data
			echo str_repeat(' ',1024*64);
			// Send output to browser immediately
			flush();
			// Sleep one second so we can see the delay
			sleep(1);
			// }
		}
	}
	function proses_file($tabel){
		$a =  file_get_contents("http://ypp-alhidayah.com/namiroh/index.php/admin/singkron/$tabel");
		echo $a;
		return $a;
	}
	function proses($tabel){
		$filename = __DIR__."/$tabel.txt";
		// if (file_exists($filename)) {
			// echo "The file $filename exists";
		// } else {
			// echo "The file $filename does not exist";
		// }
	
	
		$settingannya = array(
                'tables'      => array($tabel),  // Array of tables to backup.
                'ignore'      => array(),           // List of tables to omit from the backup
                'format'      => 'txt',             // gzip, zip, txt
                'filename'    => 'backup_'.date('y-m-d').'.sql',    // File name - NEEDED ONLY WITH ZIP FILES
                'add_drop'    => TRUE,              // Whether to add DROP TABLE statements to backup file
                'add_insert'  => TRUE,              // Whether to add INSERT data to backup file
                'newline'     => "\n"               // Newline character used in backup file
              );
		$this->load->dbutil();

		// Backup your entire database and assign it to a variable
		$backup =& $this->dbutil->backup($settingannya); 
		$file = fopen(dirname ( __FILE__ )."/$tabel.txt","w");
		fwrite($file,$backup);
		fclose($file);
		
		$file = $filename;
		// set up basic connection
		$conn_id = ftp_connect('ftp.ypp-alhidayah.com');

		// login with username and password
		$login_result = ftp_login($conn_id, 'u2314635', '!lsc6mOfB&');

		// upload a file
		if (ftp_put($conn_id, "/public_ftp/data/$tabel.sql", $file, FTP_ASCII)) {
			echo "successfully uploaded $file\n";
		} else {
			echo "There was a problem while uploading $file\n";
		}

		// close the connection
		ftp_close($conn_id);
		flush();
//////////////////////////////////////////////////////
		// $this->load->library('ftp');
		// $config['hostname'] = 'ftp.ypp-alhidayah.com';
		// $config['username'] = 'u2314635';
		// $config['password'] = '!lsc6mOfB&';
		// $config['debug']	= TRUE;

		// $this->ftp->connect($config);
		// echo  dirname ( __FILE__ );
		// $this->ftp->upload($filename , '/public_html/namoroh/tes.html', 'auto', 0775);

		// $this->ftp->close();
	}
	
	
}
// END Kelas Class

/* Location: ./system/application/controllers/singkron.php */