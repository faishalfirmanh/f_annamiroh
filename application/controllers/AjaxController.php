<?php

/**
 * Kelas Class
 *
 * @author	Moch Yasin
 */
class AjaxController extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->helper('url');
        $this->load->library('session');
        $this->load->driver('cache', [
            'adapter' => 'file',
            'backup' => 'file'
        ]);
        $this->load->model('Location_model');
        $this->load->js('assets/themes/default/js/jquery-1.9.1.min.js');
        $this->load->js('assets/themes/default/hero_files/bootstrap-transition.js');
        $this->load->js('assets/themes/default/hero_files/bootstrap-collapse.js');
    }


    public function get_kota()
    {
        $id_prov = $this->input->post('id_prov');
        $data = $this->Location_model->get_cities($id_prov);
        echo json_encode($data);
    }

    public function get_kecamatan()
    {
        $id_kota = $this->input->post('id_kota');
        $data = $this->Location_model->get_districts($id_kota);
        echo json_encode($data);
    }

    public function harian()
    {
        // ==================== PARAMETER PAGINATION ====================
        if ($this->session->userdata('username') == NULL) {
            redirect('/home'); // Sesuaikan dengan fungsi dashboard Anda
        }
        $per_page = (int) $this->input->get('per_page', TRUE) ?: 50;   // default 50
        $page = (int) $this->input->get('page', TRUE) ?: 1;
        $offset = ($page - 1) * $per_page;

        $search = trim($this->input->get('search', TRUE)); // keyword pencarian

        // ==================== QUERY UTAMA ====================
        $this->db->select('
            pembayaran_transaksi_paket.id,
            pembayaran_transaksi_paket.id_transaksi_paket,
            pembayaran_transaksi_paket.tanggal,
            pembayaran_transaksi_paket.tanggal_transfer,
            pembayaran_transaksi_paket.debet,
            pembayaran_transaksi_paket.kredit,
            pembayaran_transaksi_paket.jenis_transaksi,
            pembayaran_transaksi_paket.keterangan,
            pembayaran_transaksi_paket.teller,
            data_jamaah.nama_jamaah,
            pembayaran_transaksi_paket.deleted,
            pembayaran_transaksi_paket.deleted_by,
            pembayaran_transaksi_paket.deleted_at,
            transaksi_paket.jamaah,
            transaksi_paket.kode,
            admin.nama as nama_teller,
            data_jamaah_paket.estimasi_keberangkatan,
            jenis_transaksi_pengeluaran.nama_transaksi as nama_jenis
        ');

        $this->db->from('pembayaran_transaksi_paket');
        //$this->db->join('transaksi_paket', 'pembayaran_transaksi_paket.id_transaksi_paket = transaksi_paket.id', 'left');
        $this->db->join('transaksi_paket', 'pembayaran_transaksi_paket.id_transaksi_paket = transaksi_paket.id', 'left');
        $this->db->join('data_jamaah', 'transaksi_paket.jamaah = data_jamaah.id_jamaah', 'left');//
        $this->db->join('data_jamaah_paket', 'transaksi_paket.paket_umroh = data_jamaah_paket.id', 'left');
        $this->db->join('admin', 'pembayaran_transaksi_paket.teller = admin.id_admin', 'left');
        $this->db->join('jenis_transaksi_pengeluaran', 'pembayaran_transaksi_paket.jenis_transaksi = jenis_transaksi_pengeluaran.id', 'left');

        // $this->db->where('pembayaran_transaksi_paket.deleted', NULL);

        // ==================== CUSTOM SEARCH ====================
        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('pembayaran_transaksi_paket.keterangan', $search);
            // $this->db->or_like('transaksi_paket.kode', $search);
            // $this->db->or_like('pembayaran_transaksi_paket.tanggal', $search);
            // $this->db->or_like('pembayaran_transaksi_paket.tanggal_transfer', $search);
            // $this->db->or_like('data_jamaah_paket.estimasi_keberangkatan', $search);
            $this->db->group_end();
        }


        // Clone query untuk menghitung total record
        $total_query = clone $this->db;
        $total = $this->db->count_all_results('', FALSE);

        // Ambil data dengan limit & offset
        $this->db->order_by('pembayaran_transaksi_paket.id', 'DESC');
        $this->db->limit($per_page, $offset);
        $query = $this->db->get();

        $data = [];
        foreach ($query->result_array() as $row) {

            $data[] = [
                'id' => $row['id'],
                'nomor_kuitansi' => $row['id'],
                'jamaah_nik_paket' => $row['nama_jamaah'] . ' - ' . $row['estimasi_keberangkatan'],
                'tanggal' => $row['tanggal'] ? date('d-m-Y', strtotime($row['tanggal'])) : '',
                'tanggal_transfer' => $row['tanggal_transfer'] ? date('d-m-Y', strtotime($row['tanggal_transfer'])) : '',
                'debet' => (float) $row['debet'],
                'kredit' => (float) $row['kredit'],
                'jenis_transaksi' => $row['nama_jenis'],
                'keterangan' => $row['keterangan'],
                'teller' => $row['nama_teller'],
                // 'metode'              => $row['metode'],
                'histori' => $row['deleted_by'] ? 'deleted: ' . $row['deleted_at'] . ' | ' . $row['nama_teller'] : ''
            ];
        }

        // ==================== SUMMARY (Footer) ====================
        $summary = $this->getHarianSummary();   // method cache di bawah

        // ==================== RESPONSE JSON ====================
        $response = [
            'status' => 'success',
            'message' => 'Data berhasil diambil',
            'data' => $data,
            'recordsTotal' => $total,
            'recordsFiltered' => $total,
            'pagination' => [
                'total' => $total,
                'per_page' => $per_page,
                'current_page' => $page,
                'last_page' => ceil($total / $per_page),
                'from' => $offset + 1,
                'to' => min($offset + $per_page, $total)
            ],
            'summary' => $summary
        ];

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response));
    }


    public function viewHarian()
    {

        if ($this->session->userdata('username') == NULL) {
            redirect('/home'); // Sesuaikan dengan fungsi dashboard Anda
        }
        $this->load->view('laporan/harian');
    }


    private function getHarianSummary()
    {
        $cacheKey = 'harian_summary';

        if ($cached = $this->cache->get($cacheKey)) {
            return $cached;
        }

        // $jamaah_count = $this->db
        // 	->from('pembayaran_transaksi_paket')
        // 	->join('transaksi_paket', 'pembayaran_transaksi_paket.id_transaksi_paket = transaksi_paket.id')
        // 	->group_by('jamaah')
        // 	->where('pembayaran_transaksi_paket.deleted', null)
        // 	->get()->num_rows(); 

        // var_dump($jamaah_count);
        // die();
        $sum = $this->db
            ->select('
                COUNT(DISTINCT transaksi_paket.qty) as jamaah_count,
                SUM(debet) as debit_sum,
                SUM(kredit) as kredit_sum
            ')
            ->from('transaksi_paket')
            ->get()
            ->row_array();

        $result = [
            'jamaah_count' => number_format($sum['jamaah_count'] ?? 0, 0, ',', '.'),
            'debit_sum' => number_format($sum['debit_sum'] ?? 0, 2, ',', '.'),
            'kredit_sum' => number_format($sum['kredit_sum'] ?? 0, 2, ',', '.'),
            'tag' => 'laporan_harian'
        ];

        $this->cache->save($cacheKey, $result, 600);
        return $result;
    }

    function packageSeatCalculation()
    {
        $subquery = "(
                  SELECT SUM(qty) 
                  FROM transaksi_paket tp 
                  JOIN data_jamaah dj ON tp.jamaah = dj.id_jamaah 
                  WHERE tp.paket_umroh = p.id 
                  AND LOWER(dj.nama_jamaah) != 'jamaah baru dummy')";
        $this->db->select("
					p.id, 
					p.travel, 
					p.estimasi_keberangkatan, 
					p.jumlah_pendaftar, 
					p.qty, 
					p.total_seat, 
					p.Program,
					p.tanggal_keberangkatan, 
					p.estimasi_tgl_keberangkatan, 
					p.ket,
					$subquery as totalPendaftarReal
				", FALSE);

        $this->db->from('data_jamaah_paket p');
        $this->db->where('p.tanggal_keberangkatan > CURRENT_DATE()', NULL, FALSE);
        // $this->db->where("DATEDIFF(p.tanggal_keberangkatan, CURRENT_DATE()) <", 25);
        $this->db->where('p.tanggal_keberangkatan <= DATE_ADD(CURRENT_DATE(), INTERVAL 25 DAY)', NULL, FALSE);
        $this->db->having('totalPendaftarReal < p.qty');
        $this->db->order_by('p.id', 'DESC');
        $query = $this->db->get();

        if (!$query) {
            $error = $this->db->error();
            $response = [
                'status' => false,
                'message' => 'DB Error: ' . $error['message']
            ];
            // Set header 500 agar masuk ke block 'error' di ajax
            $this->output->set_status_header(500);
        } else {
            $response = [
                'status' => true,
                'total' => $query->num_rows(),
                'data' => $query->result()
            ];
        }
        ob_end_clean();
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response));
    }
}
// END Kelas Class

/* End of file kelas.php */
/* Location: ./system/application/controllers/kelas.php */