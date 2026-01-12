<?php

/**
 * Kelas Class
 *
 * @author	Awan Pribadi Basuki <awan_pribadi@yahoo.com>
 */
class JamaahLinkShare extends CI_Controller
{


    function __construct()
    {
         parent::__construct();

        // 2. Load Library Wajib
        $this->load->database();
        $this->load->helper('url');
        $this->load->library('session');
        $this->load->model('Location_model');
        $this->load->model('master_model', '', TRUE);
    }
    
    public function hallo()
    {
        var_dump(33333);
    }


 private function _get_uuid()
    {
        // Fungsi generate UUID v4 standar
        return sprintf(
            '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            mt_rand(0, 0xffff), mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0x0fff) | 0x4000,
            mt_rand(0, 0x3fff) | 0x8000,
            mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
        );
    }
    
     public function formInputLinkShare()
    {
        // Cek jika form disubmit
          $ide = $this->session->userdata('level');
          if($ide == NULL){
              echo "tidak ada akses";
              die();
          }
        if ($this->input->post('submit')) {
            $qty = (int)$this->input->post('jumlah_jamaah');
            $id_agen = $this->input->post('jenis_jamaah') =='tipe_jamaah_agen' ? $this->input->post('agen') : NULL;
            $id_paket = $this->uri->segment(3);
            $master_paket = $this->db->get_where('data_jamaah_paket', array('id' => $id_paket))->row();

   		$harga_paket = isset($master_paket->harga) ? $master_paket->harga : 0;
        if($id_agen != NULL){
            $data_agen = $this->db->get_where('data_jamaah', array('id_jamaah' => $id_agen))->row();
	    	$nama_agen_label = isset($data_agen->nama_jamaah) ? $data_agen->nama_jamaah : 'Agen Tidak Diketahui';
        }
		
		$this->db->trans_start();
            if ($qty > 0 ) {
                $data_batch = array();
                $cek_agent = $id_agen != NULL ? $id_agen : 0;

                for ($i = 1; $i <= $qty; $i++) {
                    $uuid = $this->_get_uuid();
                   
                    $data_insert_jamaah = array(
                        'agen'          => $this->input->post('agen'),
                        'title' => 'MR',
                        'no_tlp' => isset($data_agen) ? $data_agen->no_tlp : '0000',
                        'hp_jamaah'=> '1111',
                        'nama_jamaah'   => "Jamaah Baru Dummy",
                        'random_uuid'   => $uuid, // UUID unik asli
                        'is_agen'       => $cek_agent,//daftar dari agen =1, jika tidak = 0.
                        'created_at'    => date('Y-m-d H:i:s')
                    );
                    $this->db->insert('data_jamaah', $data_insert_jamaah);
                    
                    	$id_jamaah_baru = $this->db->insert_id();
            			$data_transaksi_paket = array(
            				'jamaah'      => $id_jamaah_baru,
            				'paket_umroh' => $id_paket, // ID 2580 masuk ke sini
            				'agen'        => $cek_agent,
            				'harga'       => $harga_paket,
            				'harga_normal'=> $harga_paket,
            				'kekurangan'  => $harga_paket,
            				'qty'         => 1,
            			);
            			$this->db->insert('transaksi_paket', $data_transaksi_paket);
                }

                // Insert banyak data sekaligus lebih cepat daripada satu-satu
              	$this->db->trans_complete();

                $this->session->set_flashdata('success', "Berhasil men-generate $qty data jamaah.");
                redirect('master/jamaah');
            }
        }

        // Ambil data agen untuk dropdown (is_agen = 1)
        $data['list_agen'] = $this->db->get_where('data_jamaah', ['is_agen' => '1'])->result();
        
        // Ambil 10 data terbaru untuk ditampilkan di tabel bawah form
        $this->db->select('dj.*, a.nama_jamaah as nama_agen');
        $this->db->from('data_jamaah dj');
        $this->db->join('data_jamaah a', 'a.id_jamaah = dj.agen', 'left');
        $this->db->order_by('dj.id_jamaah', 'desc'); // urut terbaru
        $this->db->limit(10);                        // ambil 10 data
        $data['latest_jamaah'] = $this->db->get()->result();

        $this->load->view('ci_simplicity/admin_manual_generate', $data);
    }
    
    
    public function submitEditData($uuid = null){
        $ktp_file = null;


        $jamaah = $this->db
        ->get_where('data_jamaah', ['random_uuid' => $uuid])
        ->row();
        if (!$jamaah) show_404();

        // Validasi form
        $this->form_validation->set_rules('nama_jamaah', 'Nama Jamaah', 'required');
        $this->form_validation->set_rules('no_ktp', 'No KTP', 'required|numeric');
        $this->form_validation->set_rules('location_prov', 'Provinsi', 'required');
        $this->form_validation->set_rules('location_city', 'Kota', 'required');
        $this->form_validation->set_rules('location_disct', 'Kecamatan', 'required');
        $this->form_validation->set_rules('location_village', 'Kelurahan', 'required');

        if ($this->form_validation->run() == FALSE) {
            // Gagal validasi → kembali ke edit page
            $this->session->set_flashdata('error_edit', validation_errors());
            redirect('JamaahLinkShare/jamaahUUID/'.$uuid);
        }

       if (!$this->input->post('nama_jamaah')) {
            show_error('Nama jamaah wajib diisi');
        }

        if ($this->input->post('ktp_compressed')) {
            $base64 = $this->input->post('ktp_compressed');
            $base64 = str_replace('data:image/jpeg;base64,', '', $base64);
            $base64 = str_replace(' ', '+', $base64);

            $imageData = base64_decode($base64);

            if (strlen($imageData) > 1024 * 1024) {
                show_error('Ukuran foto KTP melebihi 1 MB setelah kompres');
            }

            $uploadPath = FCPATH . 'assets/uploads/ktp/';
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }

            $fileName = 'ktp_' . $jamaah->id_jamaah . '_' . time() . '.jpg';
            file_put_contents($uploadPath . $fileName, $imageData);

            $ktp_file = $fileName;
        }
        $data = [
            'location_prov'     => $this->input->post('location_prov'),
            'location_city'     => $this->input->post('location_city'),
            'location_disct'    => $this->input->post('location_disct'),
            'location_village'  => $this->input->post('location_village'),
            'imigrasi'          => $this->input->post('imigrasi'),
            'tempat_lahir'      => $this->input->post('tempat_lahir'),
            'title'             => $this->input->post('title'),
            'nama_jamaah'       => $this->input->post('nama_jamaah'),
            'tgl_lahir'         => $this->input->post('tgl_lahir'),
            'alamat_jamaah'     => $this->input->post('alamat_jamaah'),
            'no_ktp'            => $this->input->post('no_ktp'),
            'no_tlp'            => $this->input->post('no_tlp'),
            'passport'          => $this->input->post('passport'),
            'ktp'               => $ktp_file,
            // 'agen'              => $this->input->post('agen'),
            'updated_at'        => date('Y-m-d H:i:s'),
           'random_uuid' => NULL,

        ];

        $this->db->where('id_jamaah', $jamaah->id_jamaah);
        $saved = $this->db->update('data_jamaah', $data);
        $notif_alert = '';
        if($saved){
                $notif_alert = '<div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>Sukses!</strong> Data berhasil diupdate.
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>';
        }else{
            $notif_alert = '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <strong>Gagal!</strong>Gagal save data jamaah.
                                <button type="button" class="close" data-dismiss="alert">&times;</button>
                            </div>';
        
        } 

        $this->load->model('Jamaah_model');

        

       
        $data_jamaah_view = [
            'nama_jamaah'=> $data['nama_jamaah'],
            'jenis_jamaah'=> $jamaah->agen > 0 ? 'jamaah agen '.$this->Jamaah_model->get_by_id($jamaah->agen)->nama_jamaah : ' Jamaah kantor ',
            'nama_paket'=>  $this->Jamaah_model->get_nama_estimasi_keberangkatan($jamaah->id_jamaah)->estimasi_keberangkatan      
        ];

        
        $this->load->view('no_login_page', ['notif_alert'=> $notif_alert, 'data_saved'=>$data_jamaah_view]);
    }
    
     public function jamaahUUID($uuid = null)
    {
    // UUID wajib ada

    if (!$uuid) {
        show_404();
    }

    // Ambil data jamaah
    $jamaah = $this->db
        ->get_where('data_jamaah', ['random_uuid' => $uuid])
        ->row();
        
    if (!$jamaah) {
        show_404();
    }

    // Jika submit form (EDIT
     $this->load->view('ci_simplicity/jamaah_edit', [
         'jamaah' => $jamaah
        ]);

    // Load VIEW PUBLIC (tanpa template admin)
  
}

}
