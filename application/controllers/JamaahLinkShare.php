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
        $this->load->model('single_link_share_jamaah_model', '', TRUE);
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

                $parent_uuid_ =  $this->_get_uuid();
                $parent_single_link_date = [
                    'paket_id'=> $id_paket,
                    'agen_id' =>$cek_agent,
                    'random_uuid'=>$parent_uuid_,
                    'qty_generate'=>$qty,
                    'status'=>0,//0 masih pertama di generate, 1 jamaah submit min 1 data ,2 sudah submit semua
                    'qty_submit'=>0,//qty yang sudah disubmit,
                    'created_by'=>$this->session->userdata('id_admin'),
                ];
                $save_single = $this->single_link_share_jamaah_model->insert_link($parent_single_link_date);

                for ($i = 1; $i <= $qty; $i++) {
                    $uuid = $this->_get_uuid();
                   
                    $data_insert_jamaah = array(
                        'agen'          => $this->input->post('agen'),
                        'title' => 'MR',
                        'no_tlp' => isset($data_agen) ? $data_agen->no_tlp : '0000',
                        'hp_jamaah'=> '1111',
                        'nama_jamaah'   => "input nama jamaah ",
                        'random_uuid'   => $uuid, // UUID unik asli
                        'is_agen'       => $cek_agent,//daftar dari agen =1, jika tidak = 0.
                        'created_at'    => date('Y-m-d H:i:s'),
                        'user_id'       => $this->session->userdata('id_admin'),
                        'status_generate' => 1,//1 awal, 2//sudah di submit
                        'child_id_single_link'=>$save_single
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
        $this->db->where('dj.user_id', $this->session->userdata('id_admin'));
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
        $ktp_file = $jamaah->ktp;

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
            $this->session->set_flashdata('error_id', $jamaah->id_jamaah);
            if($this->input->post('form_multi_jamaah') == 1){
                $parent_id_ =  $this->single_link_share_jamaah_model->get_single_data('id',$jamaah->child_id_single_link);
                $error_message = 'No KTP <strong>' . htmlspecialchars($input_no_ktp) . '</strong> sudah terdaftar atas nama: <strong>' . htmlspecialchars($existing_ktp->nama_jamaah) . '</strong>.';
                $this->session->set_flashdata('error_edit', $error_message);
                redirect('JamaahLinkShare/formJamaahLink/' . $parent_id_->random_uuid);
                return;
            }else{
                redirect('JamaahLinkShare/jamaahUUID/'.$uuid);
            }
           
        }

       if (!$this->input->post('nama_jamaah')) {
            show_error('Nama jamaah wajib diisi');
        }


        $input_no_ktp = $this->input->post('no_ktp');
        $existing_ktp = $this->db->where('no_ktp', $input_no_ktp)
                                 ->get('data_jamaah')
                                 ->row();

       
        if ($existing_ktp) {
            if( $this->input->post('form_multi_jamaah') == 1){
                $parent_id_ =  $this->single_link_share_jamaah_model->get_single_data('id',$jamaah->child_id_single_link);
                if($jamaah->no_ktp != $existing_ktp->no_ktp){
                        $error_message = 'No KTP <strong>' . htmlspecialchars($input_no_ktp) . '</strong> sudah terdaftar atas nama: <strong>' . htmlspecialchars($existing_ktp->nama_jamaah) . '</strong>.';
                        $this->session->set_flashdata('error_edit', $error_message);
                        redirect('JamaahLinkShare/formJamaahLink/' . $parent_id_->random_uuid);
                        return;
                }
            }else{
                $error_message = 'No KTP <strong>' . htmlspecialchars($input_no_ktp) . '</strong> sudah terdaftar atas nama: <strong>' . htmlspecialchars($existing_ktp->nama_jamaah) . '</strong>.';
                $this->session->set_flashdata('error_edit', $error_message);
                redirect('JamaahLinkShare/jamaahUUID/' . $uuid);
                return; // Hentikan eksekusi
            }
        }

        $base64Data = $this->input->post('ktp_compressed');
    
        if (!empty($_FILES['ktp']['name'])) {
            $uploadPath = FCPATH . 'assets/uploads/ktp/';
             if (!is_dir($uploadPath)) mkdir($uploadPath, 0777, true);

            $ext = pathinfo($_FILES['ktp']['name'], PATHINFO_EXTENSION);
            $allowed = array('jpg', 'jpeg', 'png', 'JPG', 'JPEG', 'PNG');

            // Validasi Ekstensi Manual
            if (!in_array($ext, $allowed)) {
                $this->session->set_flashdata('error_edit', 'Format file tidak diizinkan. Gunakan JPG atau PNG.');
                redirect($_SERVER['HTTP_REFERER']);
                return;
            }

            $config['upload_path']   = $uploadPath;
            $config['allowed_types'] = '*'; // Bypass pengecekan mimes.php
            $config['max_size']      = 5120; // 5MB
            $config['file_name']     = 'ktp_' . $jamaah->id_jamaah . '_' . time() . '.' . $ext;
          
            $this->load->library('upload');
            $this->upload->initialize($config);

            if ($this->upload->do_upload('ktp')) {
                $uploadData = $this->upload->data();
                $ktp_file = $uploadData['file_name'];
                
                // Hapus file lama
                if (!empty($jamaah->ktp) && file_exists('./assets/uploads/ktp/' . $jamaah->ktp)) {
                    @unlink('./assets/uploads/ktp/' . $jamaah->ktp);
                }
            } else {
                $this->session->set_flashdata('error_edit', $this->upload->display_errors());
                redirect($_SERVER['HTTP_REFERER']);
                return;
            }
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
           'status_generate'=> 2,

        ];

        $this->db->where('id_jamaah', $jamaah->id_jamaah);
        $saved = $this->db->update('data_jamaah', $data);

        if ($this->input->post('form_multi_jamaah') == 1) {
            // 1. Hitung total jamaah yang SUDAH submit (status_generate > 1)
            $total_sudah_submit = $this->db
                ->where('child_id_single_link', $jamaah->child_id_single_link)
                ->where('status_generate >', 1)
                ->count_all_results('data_jamaah');
            // 2. Ambil data master link untuk perbandingan qty
            $link_master = $this->db
                ->get_where('single_link_share_jamaah', ['id' => $jamaah->child_id_single_link])
                ->row();

            if ($link_master) {
                $new_status = ($link_master->qty_generate == $total_sudah_submit) ? 2 : 1;

                // 4. Update tabel master link
                $this->db->where('id', $jamaah->child_id_single_link);
                $this->db->update('single_link_share_jamaah', [
                    'qty_submit' => $total_sudah_submit,
                    'status'     => $new_status,
                ]);
            }
        }

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


    public function formJamaahLink($uuid = null)
    {
        if (!$uuid) {
            show_404();
        }
        $cek_single = $this->single_link_share_jamaah_model->get_single_data('random_uuid',$uuid);
      
        if (empty($cek_single)) {
            show_404();
        }

        $data['users'] = $this->db
            ->get_where('data_jamaah', ['child_id_single_link' => $cek_single->id])
          ->result();
        
        if (empty($data['users'])) {
            show_404();
        }

        $this->load->view('ci_simplicity/jamaah_edit_single_link', $data);
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
