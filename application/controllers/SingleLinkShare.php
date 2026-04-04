<?php

/**
 * Kelas Class
 *
 * @author	Awan Pribadi Basuki <awan_pribadi@yahoo.com>
 */
class SingleLinkShare extends CI_Controller
{


    var $j = array();
    var $paketnya = array();
    var $group_rev = array();
    var $crud = null;
    var $paket = array();

    function __construct()
    {
        parent::__construct();
        if ($this->session->userdata('login') != TRUE) {
            redirect('login');
        }
        $this->load->database();
        $this->load->helper('url');
        $this->db->query("SET sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''))");
        $this->load->model('main_model', '', TRUE);
        $this->load->model('laporan_model', '', TRUE);
        $this->load->model('Single_link_share_jamaah_model');
        $this->load->library('grocery_CRUD');
        $this->load->library('session');
        $this->crud = new grocery_CRUD();
        $this->_init();

    }

    private function _init()
    {
        $this->output->set_template('admin');
        $ide = $this->session->userdata('level');
        $this->output->set_output_data('menu', $this->main_model->get_menu($ide));
        $this->load->js('assets/themes/default/js/jquery-1.9.1.min.js');
        $this->load->js('assets/themes/default/hero_files/bootstrap-transition.js');
        $this->load->js('assets/themes/default/hero_files/bootstrap-collapse.js');


        $d = $this->db->query("select id_jamaah, nama_jamaah,no_ktp from data_jamaah");
        foreach ($d->result() as $row) {
            $this->j[$row->id_jamaah] = $row->nama_jamaah . "-" . $row->no_ktp;
        }
        $query = $this->db->query("SELECT id,CONCAT(estimasi_keberangkatan,'-',Program,'-',CAST(FORMAT(harga,2,'de_DE') 
		      AS CHAR CHARACTER SET utf8)) AS detail FROM data_jamaah_paket");
        foreach ($query->result() as $row) {

            $this->paket[$row->id] = $row->detail;
        }
    }

    private function show($module = '')
    {
        $this->crud->set_theme('twitter-bootstrap');
        $output = $this->crud->render();

        // $output->meta_keywords = "Something 2";
        $this->load->view('ci_simplicity/admin', $output);
    }


    private function _get_uuid()
    {
        // Fungsi generate UUID v4 standar
        return sprintf(
            '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0x0fff) | 0x4000,
            mt_rand(0, 0x3fff) | 0x8000,
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff)
        );
    }

    public function formInputLinkShare()
    {
        $ide = $this->session->userdata('level');
        if ($ide == NULL) {
            echo "tidak ada akses";
            die();
        }
        if ($this->input->post('submit')) {
            $qty = (int) $this->input->post('jumlah_jamaah');
            $id_agen = $this->input->post('jenis_jamaah') == 'tipe_jamaah_agen' ? $this->input->post('agen') : NULL;
            $id_paket = $this->uri->segment(3);
            $master_paket = $this->db->get_where('data_jamaah_paket', array('id' => $id_paket))->row();

            $harga_paket = isset($master_paket->harga) ? $master_paket->harga : 0;
            if ($id_agen != NULL) {
                $data_agen = $this->db->get_where('data_jamaah', array('id_jamaah' => $id_agen))->row();
                $nama_agen_label = isset($data_agen->nama_jamaah) ? $data_agen->nama_jamaah : 'Agen Tidak Diketahui';
            }

            $this->db->trans_start();
            if ($qty > 0) {
                $data_batch = array();
                $cek_agent = $id_agen != NULL ? $id_agen : 0;

                for ($i = 1; $i <= $qty; $i++) {
                    $uuid = $this->_get_uuid();

                    $data_insert_jamaah = array(
                        'agen' => $this->input->post('agen'),
                        'title' => 'MR',
                        'no_tlp' => isset($data_agen) ? $data_agen->no_tlp : '0000',
                        'hp_jamaah' => '1111',
                        'nama_jamaah' => "input nama jamaah ",
                        'random_uuid' => $uuid, // UUID unik asli
                        'is_agen' => $cek_agent,//daftar dari agen =1, jika tidak = 0.
                        'created_at' => date('Y-m-d H:i:s'),
                        'user_id' => $this->session->userdata('id_admin'),
                        'status_generate' => 1,
                    );
                    $this->db->insert('data_jamaah', $data_insert_jamaah);

                    $id_jamaah_baru = $this->db->insert_id();
                    $data_transaksi_paket = array(
                        'jamaah' => $id_jamaah_baru,
                        'paket_umroh' => $id_paket, // ID 2580 masuk ke sini
                        'agen' => $cek_agent,
                        'harga' => $harga_paket,
                        'harga_normal' => $harga_paket,
                        'kekurangan' => $harga_paket,
                        'qty' => 1,
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

    public function coupon()
    {
        $this->load->database();


        $this->crud->set_table('coupons');
        $this->crud->set_subject('Data Kode Kupon');

        $this->crud->unset_add();
        $this->crud->unset_edit();
        $this->crud->unset_delete();


        $this->crud->display_as('code_coupon', 'Kode');
        $this->crud->set_theme('datatables');
        $this->show();

    }

    public function generate_jamaah()
    {
        $this->load->database();
        $id_admin = $this->session->userdata('id_admin');
        $level = $this->session->userdata('level');
        $cek_level_admin = $this->db->get_where('group_level', array('id' => $level))->row();

        $this->crud->set_table('single_link_share_jamaah');
        $this->crud->set_subject('Data Single Link Generate Jamaah');

        // PERBAIKAN: Ubah 'user_id' menjadi 'created_by' (sesuai struktur tabel Anda)
        if ($cek_level_admin->nama !== 'HRD') {
            $this->crud->where('created_by', $id_admin);
        }

        // 4. Labeling (Display As)
        $this->crud->display_as('action_link', 'Link Form');
        $this->crud->callback_column('action_link', array($this, '_callback_tombol_copy'));
        $this->crud->callback_column('paket_id', array($this, '_callback_paket_generate'));
        $this->crud->display_as('paket_id', 'Nama Paket');
        $this->crud->unset_columns('random_uuid');


        $this->crud->callback_column('agen_id', array($this, '_callback_agen_name'));
        $this->crud->display_as('agen_id', 'Nama Agen');

        $this->crud->callback_column('status', array($this, '__callback_status'));
        $this->crud->display_as('status', 'Status');

        $this->crud->display_as('qty_generate', 'Quantity Generate');
        $this->crud->display_as('qty_submit', 'Quantity Submit');

        $this->crud->callback_column('created_by', array($this, '_callback_user_id'));
        $this->crud->display_as('created_by', 'Dibuat Oleh');
        $this->crud->columns('paket_id', 'agen_id', 'qty_generate', 'qty_submit', 'status', 'created_by', 'action_link');
        // 5. Matikan fitur Add, Edit, Delete
        $this->crud->unset_add();
        $this->crud->unset_edit();
        $this->crud->unset_delete();

        // Set tema
        $this->crud->set_theme('datatables');

        $js_script = '
			<script>

				function copyToClipboard(text) {
					// Membuat elemen textarea sementara
					var dummy = document.createElement("textarea");
					document.body.appendChild(dummy);
					dummy.value = text;
					dummy.select();
					
					// Eksekusi copy
					document.execCommand("copy");
					document.body.removeChild(dummy);
					
					// Notifikasi sukses (opsional, bisa diganti SweetAlert jika ada)
					alert("Link berhasil disalin!");
				}
			</script>';

        //$this->output->append_output($js_script);

        $this->output->append_output($js_script);

        $this->show();
    }

    public function _callback_tombol_copy($value, $row)
    {
        // Cek apakah kolom random_uuid di database memiliki nilai
        if (!empty($row->random_uuid)) {
            $link_tujuan = site_url('JamaahLinkShare/formJamaahLink/' . $row->random_uuid);
            return '<button type="button" class="btn btn-warning btn-xs" onclick="copyToClipboard(\'' . $link_tujuan . '\')">
						<i class="fa fa-copy"></i>Link Edit Jamaah 
					</button>';
        }
        return '';
    }

    public function formJamaahLink($uuid = null)
    {

        $jamaah = $this->db
            ->get_where('data_jamaah', ['random_uuid' => $uuid])
            ->row();

        if (!$jamaah) {
            show_404();
        }
    }

    public function __callback_status($value, $row)
    {
        if ($value == 0) {
            return '<b style="color:red;">form belum submit sama sekali</b>';
        } else if ($value == 1) {
            return '<b style="color:orange;">form  disubmit  beberapa</b>';
        } else if ($value == 2) {
            return '<b style="color:green;">form disubmit semua</b>';
        } else {
            return 'sudah di submit semua';
        }
    }

    public function _callback_user_id($value, $row)
    {
        // PERBAIKAN: Gunakan variabel baru ($admin), bukan $row
        $admin = $this->db->select('id_admin, username, nama_admin')
            ->from('admin') // Note: Apakah benar tabel adminnya bernama ini?
            ->where('id_admin', $row->created_by)
            ->get()->row();

        // PERBAIKAN: Cek apakah data ada untuk mencegah error non-object
        return ($admin) ? $admin->username : 'Tidak diketahui';
    }

    public function _callback_agen_name($value, $row)
    {
        if ($row->agen_id > 0) {
            // PERBAIKAN: Gunakan variabel baru ($agen)
            $agen = $this->db->select('id_jamaah, nama_jamaah')
                ->from('data_jamaah')
                ->where('id_jamaah', $row->agen_id)
                ->get()->row();

            return ($agen) ? $agen->nama_jamaah : 'Agen Terhapus/Tidak Ditemukan';
        } else {
            return 'Jamaah Kantor';
        }
    }

    public function _callback_paket_generate($value, $row)
    {
        // PERBAIKAN: Gunakan variabel baru ($paket) dan asumsikan nama kolom typo
        $paket = $this->db->select('harga, estimasi_keberangkatan') // Ubah jika namanya memang estimasi_kebern
            ->from('data_jamaah_paket')
            ->where('id', $row->paket_id)
            ->get()->row();

        return ($paket) ? $paket->estimasi_keberangkatan : 'Paket Tidak Ditemukan';
    }


}
