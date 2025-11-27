<?php
/**
 * Login Class
 *
 * @author	Awan Pribadi Basuki <awan_pribadi@yahoo.com>
 */
class Login extends CI_Controller {
	/**
	 * Constructor
	 */
	function __construct()
	{
		parent::__construct();
		$this->load->model('Login_model', '', TRUE);
	}
	
	/**
	 * Memeriksa user state, jika dalam keadaan login akan menampilkan halaman absen,
	 * jika tidak akan meload halaman login
	 */
	function index()
	{
		if ($this->session->userdata('login') == TRUE)
		{
			if($this->session->userdata('level')==6)//jamaah
			    redirect('usere/index');
			elseif($this->session->userdata('level')==5)//agen
			    redirect('usere/index');
			    elseif($this->session->userdata('level')==9)//hrd
			    redirect('hrd/index');
			elseif($this->session->userdata('level')==8)//IT
			    redirect('it/index');
			elseif($this->session->userdata('level')==10)//ticketing
			redirect('ticketing/customer');
			else
			    redirect('master/jamaah');
		}
		else
		{
			$this->load->view('login/login_view');
		}
	}
	
	/**
	 * Memproses login
	 */
	function process_login()
	{
		$this->form_validation->set_rules('username', 'Username', 'required|xss_clean');
		$this->form_validation->set_rules('password', 'Password', 'required|xss_clean');
		
		if ($this->form_validation->run() == TRUE)
		{
			$username = strtolower($this->input->post('username'));
			$password = md5($this->input->post('password'));
				// $password = ($this->input->post('password'));
			
			if ($this->Login_model->check_user($username, $password) == TRUE)
			{
				$admin = $this->Login_model->get_admin($username, $password);
				$data = array('id_admin' => $admin->id_admin, 'username' => $username, 'nama_admin' => $admin->nama_admin, 'level' => $admin->level, 'login' => TRUE,'fk'=>$admin->fk, 'is_namiroh' => $admin->is_namiroh);
				$this->session->set_userdata($data);
				session_start();
				$_SESSION['id_admin'] = $admin->id_admin;
				$_SESSION['nama_admin']=$admin->nama_admin;
			    if($admin->level == 6){
			        redirect('usere/index');
			    }
			    elseif($admin->level == 5)//agen
			    {
			        redirect('master/jamaah_p');
			    }
			    elseif($admin->level == 4)//leader
			    {
			        redirect('master/jamaah_p');
			    }
			    else{
			        redirect('transaksi/pembayaran');
			    }
				
			}
			else
			{
				$this->session->set_flashdata('message', 'Maaf, username dan atau password Anda salah');
				redirect('login/index');
			}
		}
		else
		{
			$this->load->view('login/login_view');
		}
	}
	
	/**
	 * Memproses logout
	 */
	function process_logout()
	{
		$this->session->sess_destroy();
		redirect('home', 'refresh');
	}
}
// END Login Class

/* End of file login.php */
/* Location: ./system/application/controllers/login.php */
