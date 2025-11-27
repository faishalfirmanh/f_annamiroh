<?php
/**
 * Kelas Class
 *
 * @author	Awan Pribadi Basuki <awan_pribadi@yahoo.com>
 */
class Web extends CI_Controller {
	/**
	 * Constructor
	 */
	 var $t = array();
	 var $crud='';
	function __construct()
	{
		parent::__construct();
		if ($this->session->userdata('login') != TRUE)
		{
			redirect('login');
		}
		$this->load->database();
		$this->load->helper('url');
		$this->load->model('main_model', '', TRUE);
		$this->load->library('grocery_CRUD');
		$this->crud = new grocery_CRUD();
		$this->_init();
	}
	private function _init()
	{
		$this->output->set_template('admin');
		$ide= $this->session->userdata('level');
		$this->output->set_output_data('menu',$this->main_model->get_menu($ide));
		$this->load->js('assets/themes/default/js/jquery-1.9.1.min.js');
		$this->load->js('assets/themes/default/hero_files/bootstrap-transition.js');
		$this->load->js('assets/themes/default/hero_files/bootstrap-collapse.js');
	}
	private function show($module  = ''){
		$this->crud->set_theme('twitter-bootstrap')->unset_export();
		$output = $this->crud->render();
		$this->load->view('ci_simplicity/admin',$output);
	}
	/**
	 * Memeriksa user state, jika dalam keadaan login akan menampilkan halaman kelas,
	 * jika tidak akan meredirect ke halaman login
	 */
	function index()
	{
		redirect('master/jamaah');
	}

	function tipe_post(){
		$this->crud->set_table('web_tipe_pos');
		$this->crud->set_subject('Data Tipe Tulisan')->callback_column('tipe',array($this,'__pilih_tipe'));
		$this->show();
		
	}
	public function __pilih_tipe($value, $row)
    {
        return "<a href='".site_url('web/post/'.$row->id)."'>$value</a>";
    }
    //create view pilihan_menu as select nama, group_level.id as id_group, kategori,group_level_menu.id as id_kategori from group_level_menu,group_level
	function get($table,$id,$id_val,$kolom){
		$query = $this->db->get_where($table, array($id => $id_val));
		foreach ($query->result() as $row)
		{
				return $row->$kolom;
		}
		return null;
	}
	
	function post($tipe=0){
	    if($tipe == 0 ){
	        $this->crud->set_table('web_tipe_pos')->unset_add()->unset_delete()->unset_edit()->unset_read()->columns('tipe');
    		$this->crud->set_subject('Pilih Group')->callback_column('tipe',array($this,'__pilih_tipe'));
    		$this->show();
	    }else{
			if($tipe==1)
				$this->crud->display_as('judul','Jamaah');
	       $d = $this->get('web_tipe_pos','id',$tipe,'tipe');
            $this->crud->set_table('web_konten')->unset_read()->columns('judul','isi','file','waktu_tayang','tampil')->where(array('kategori'=>$tipe))->field_type('kategori', 'hidden', $tipe);
    		$this->crud
			->set_subject("Data Konten $d")
			->set_top("Data Konten $d")
			->unset_texteditor('judul','full_text')->unset_texteditor('isi','full_text')->display_as('file','File / Foto')->set_relation('tampil','web_status_post','status');
    		$this->crud->set_field_upload('file','assets/uploads/front');

    		$this->show();
	        
	    }
		
		
	}
	

	
}
// END Kelas Class

/* End of file kelas.php */
/* Location: ./system/application/controllers/kelas.php */