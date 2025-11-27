<?php

/**
 * Kelas Class
 *
 * @author	Awan Pribadi Basuki <awan_pribadi@yahoo.com>
 */
class Ticketing extends CI_Controller
{
	/**
	 * Constructor
	 */
	var $t = array();
	var $paket = array();
	var $transaksi_paket = array();
	var $crud = '';

	function __construct()
	{
		parent::__construct();
		if ($this->session->userdata('login') != TRUE) {
			redirect('login');
		}
		$this->load->database();
		$this->load->helper('url');
		$this->load->model('main_model', '', TRUE);
		$this->load->model('master_model', '', TRUE);
		$this->load->library('grocery_CRUD');
		$this->crud = new grocery_CRUD();
		$this->_init();
	}
	private function _init()
	{
		$this->output->set_template('admin');
		$ide = $this->session->userdata('level');
		$this->output->set_output_data('menu', $this->main_model->get_menu($ide));
		$this->load->js('assets/themes/default/js/jquery-1.9.1.min.js');
		
		$this->load->js('assets/themes/default/js/jquery-migrate-3.4.1.js');
		$this->load->js('assets/themes/default/hero_files/bootstrap-transition.js');
		$this->load->js('assets/themes/default/hero_files/bootstrap-collapse.js');
	}
	private function show($module  = '')
	{
		$this->crud->set_theme('twitter-bootstrap');
		$output = $this->crud->render();
		$this->load->view('ci_simplicity/admin', $output);
	}
	/**
	 * Memeriksa user state, jika dalam keadaan login akan menampilkan halaman kelas,
	 * jika tidak akan meredirect ke halaman login
	 */
	function index()
	{
		redirect('master/jamaah');
	}

        // Fungsi untuk mengelola pelanggan
    public function customer() {
        $this->crud->set_table('ticket_Customers')->columns();
        $this->crud->set_subject('Customer');

        // Menjadikan semua field kecuali primary key menjadi required
        $this->crud->required_fields('name', 'email', 'phone', 'address');
        // Mengatur kolom created_at dan updated_at
        $this->crud->field_type('ticket_created_at', 'hidden');
        $this->crud->field_type('ticket_updated_at', 'hidden')->unset_delete();
        $this->crud->unset_columns(array('ticket_created_at','ticket_updated_at'));

        // Menangani saat penyimpanan data
        $this->crud->callback_before_insert(array($this, 'before_insert_customers'));
        $this->crud->callback_before_update(array($this, 'before_update_customers'));
        // Callback untuk mencatat aktivitas
        $this->crud->callback_after_insert(array($this, 'log_activity_after_insert'));
        $this->crud->callback_after_update(array($this, 'log_activity_after_update'));
        $this->crud->callback_after_delete(array($this, 'log_activity_after_delete'));
        $this->show();
    }



    // Fungsi untuk mengelola tiket (2=kantor,1=luar)
    public function tickets($par=1) {
        $this->crud->set_table('ticket_Tickets');
        $this->crud->set_subject('Ticket')->unset_delete()->unset_edit();
        $this->crud->set_relation('ticket_customer_id', 'ticket_Customers', 'name');
$this->crud->unset_columns(array('ticket_created_at','ticket_updated_at'));
        // Menjadikan semua field kecuali primary key menjadi required
        $this->crud->required_fields('ticket_status');
        // Mengatur kolom created_at dan updated_at
        $this->crud->field_type('ticket_created_at', 'hidden')->set_relation('maskapai','data_maskapai','nama');
        $this->crud->field_type('ticket_updated_at', 'hidden');
        $this->crud->display_as('ticket_customer_id','Customer')->display_as('ticket_status','status');

        // Menangani saat penyimpanan data
        $this->crud->callback_before_insert(array($this, 'before_insert_tickets'));
        $this->crud->callback_before_update(array($this, 'before_update_tickets'));

        $this->show();
    }

    // Fungsi untuk mengelola permintaan
    public function requests() {
        $this->crud->set_table('ticket_Requests');
        $this->crud->set_subject('Request');
        $this->crud->set_relation('ticket_customer_id', 'ticket_Customers', 'name');

        // Menjadikan semua field kecuali primary key menjadi required
        $this->crud->required_fields('ticket_description', 'ticket_status');
        // Mengatur kolom created_at dan updated_at
        $this->crud->field_type('ticket_created_at', 'hidden');
        $this->crud->field_type('ticket_updated_at', 'hidden');

        // Menangani saat penyimpanan data
        $this->crud->callback_before_insert(array($this, 'before_insert_requests'));
        $this->crud->callback_before_update(array($this, 'before_update_requests'));

        $this->show();
    }

    // Fungsi untuk mengelola keluhan
    public function complaints() {
        $this->crud->set_table('ticket_Complaints');
        $this->crud->set_subject('Complaint');
        $this->crud->set_relation('ticket_customer_id', 'ticket_Customers', 'name');

        // Menjadikan semua field kecuali primary key menjadi required
        $this->crud->required_fields('ticket_description', 'ticket_status');
        // Mengatur kolom created_at dan updated_at
        $this->crud->field_type('ticket_created_at', 'hidden');
        $this->crud->field_type('ticket_updated_at', 'hidden');

        // Menangani saat penyimpanan data
        $this->crud->callback_before_insert(array($this, 'before_insert_complaints'));
        $this->crud->callback_before_update(array($this, 'before_update_complaints'));

        $this->show();
    }

    // Fungsi untuk mengelola antrian
    public function queue() {
        $this->crud->set_table('ticket_Queue');
        $this->crud->set_subject('Queue');
        $this->crud->set_relation('ticket_ticket_id', 'ticket_Tickets', 'ticket_ticket_id');

        // Menjadikan semua field kecuali primary key menjadi required
        $this->crud->required_fields('ticket_position');
        // Mengatur kolom created_at dan updated_at
        $this->crud->field_type('ticket_created_at', 'hidden');
        $this->crud->field_type('ticket_updated_at', 'hidden');

        // Menangani saat penyimpanan data
        $this->crud->callback_before_insert(array($this, 'before_insert_queue'));
        $this->crud->callback_before_update(array($this, 'before_update_queue'));

        $this->show();
    }

    // Fungsi untuk mengelola pengingat
    public function reminders() {
        $this->crud->set_table('ticket_Reminders');
        $this->crud->set_subject('Reminder');
         $this->crud->required_fields('ticket_customer_id')->columns('ticket_customer_id','ticket_task','ticket_reminder_time','ticket_status','ticket_description');
        $this->crud->set_relation('ticket_customer_id', 'ticket_Customers', 'name');
    	$ide = $this->session->userdata('id_admin');
    	$this->crud->field_type('user', 'hidden', $ide)->where('user', $ide);
        // Menjadikan semua field kecuali primary key menjadi required
        $this->crud->required_fields('ticket_task', 'ticket_reminder_time', 'ticket_status');
        // Mengatur kolom created_at dan updated_at
        $this->crud->field_type('ticket_created_at', 'hidden');
        $this->crud->field_type('ticket_updated_at', 'hidden');
 $this->crud->unset_texteditor('ticket_description');
        // Menangani saat penyimpanan data
        $this->crud->callback_before_insert(array($this, 'before_insert_reminders'));
        $this->crud->callback_before_update(array($this, 'before_update_reminders'));

        $this->show();
    }
	function log()
	{
	    
	}
	    // Callback sebelum insert untuk pelanggan
    public function before_insert_customers($post_array) {
        $post_array['ticket_created_at'] = date('Y-m-d H:i:s');
        $post_array['ticket_updated_at'] = date('Y-m-d H:i:s'); // Set initial value
        return $post_array;
    }

    // Callback sebelum update untuk pelanggan
    public function before_update_customers($post_array) {
        $post_array['ticket_updated_at'] = date('Y-m-d H:i:s'); // Update value
        return $post_array;
    }

    // Callback sebelum insert untuk pengguna
    public function before_insert_users($post_array) {
        $post_array['ticket_created_at'] = date('Y-m-d H:i:s');
        $post_array['ticket_updated_at'] = date('Y-m-d H:i:s'); // Set initial value
        return $post_array;
    }

    // Callback sebelum update untuk pengguna
    public function before_update_users($post_array) {
        $post_array['ticket_updated_at'] = date('Y-m-d H:i:s'); // Update value
        return $post_array;
    }

    // Callback sebelum insert untuk tiket
    public function before_insert_tickets($post_array) {
        $post_array['ticket_created_at'] = date('Y-m-d H:i:s');
        $post_array['ticket_updated_at'] = date('Y-m-d H:i:s'); // Set initial value
        return $post_array;
    }

    // Callback sebelum update untuk tiket
    public function before_update_tickets($post_array) {
        $post_array['ticket_updated_at'] = date('Y-m-d H:i:s'); // Update value
        return $post_array;
    }

    // Callback sebelum insert untuk permintaan
    public function before_insert_requests($post_array) {
        $post_array['ticket_created_at'] = date('Y-m-d H:i:s');
        $post_array['ticket_updated_at'] = date('Y-m-d H:i:s'); // Set initial value
        return $post_array;
    }

    // Callback sebelum update untuk permintaan
    public function before_update_requests($post_array) {
        $post_array['ticket_updated_at'] = date('Y-m-d H:i:s'); // Update value
        return $post_array;
    }

    // Callback sebelum insert untuk keluhan
    public function before_insert_complaints($post_array) {
        $post_array['ticket_created_at'] = date('Y-m-d H:i:s');
        $post_array['ticket_updated_at'] = date('Y-m-d H:i:s'); // Set initial value
        return $post_array;
    }

    // Callback sebelum update untuk keluhan
    public function before_update_complaints($post_array) {
        $post_array['ticket_updated_at'] = date('Y-m-d H:i:s'); // Update value
        return $post_array;
    }

    // Callback sebelum insert untuk antrian
    public function before_insert_queue($post_array) {
        $post_array['ticket_created_at'] = date('Y-m-d H:i:s');
        $post_array['ticket_updated_at'] = date('Y-m-d H:i:s'); // Set initial value
        return $post_array;
    }

    // Callback sebelum update untuk antrian
    public function before_update_queue($post_array) {
        $post_array['ticket_updated_at'] = date('Y-m-d H:i:s'); // Update value
        return $post_array;
    }

    // Callback sebelum insert untuk pengingat
    public function before_insert_reminders($post_array) {
        $post_array['ticket_created_at'] = date('Y-m-d H:i:s');
        $post_array['ticket_updated_at'] = date('Y-m-d H:i:s'); // Set initial value
        return $post_array;
    }

    // Callback sebelum update untuk pengingat
    public function before_update_reminders($post_array) {
        $post_array['ticket_updated_at'] = date('Y-m-d H:i:s'); // Update value
        return $post_array;
    }
    
    
       // Callback setelah insert untuk pelanggan
    public function log_activity_after_insert($post_array, $primary_key) {
        $activity = "Added customer with ID: " . $primary_key;
        $this->UserActivityLogModel->log_activity($this->session->userdata('user_id'), $activity);
        return true;
    }

    // Callback setelah update untuk pelanggan
    public function log_activity_after_update($post_array, $primary_key) {
        // Ambil data lama
        $old_data = $this->db->get_where('ticket_Customers', ['ticket_customer_id' => $primary_key])->row_array();
        $old_data_json = json_encode($old_data);

        // Aktivitas log
        $activity = "Updated customer with ID: " . $primary_key;
        $this->UserActivityLogModel->log_activity($this->session->userdata('user_id'), $activity, $old_data_json, json_encode($post_array));
        return true;
    }

    // Callback setelah delete untuk pelanggan
    public function log_activity_after_delete($primary_key) {
        // Ambil data yang dihapus
        $deleted_data = $this->db->get_where('ticket_Customers', ['ticket_customer_id' => $primary_key])->row_array();
        $deleted_data_json = json_encode($deleted_data);
        
        $activity = "Deleted customer with ID: " . $primary_key;
        $this->UserActivityLogModel->log_activity($this->session->userdata('user_id'), $activity, null, null, $deleted_data_json);
        return true;
    }

}
// END Kelas Class

/* End of file kelas.php */
/* Location: ./system/application/controllers/kelas.php */