<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Coupon extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Coupon_model');
        $this->load->helper(['form', 'url']);
        $this->load->library(['form_validation']);
        if ($this->session->userdata('login') != TRUE) {
            redirect('login');
        }
    }

    // ====================== HALAMAN LIST COUPON ======================
    public function list_coupon()
    {
        $data['title'] = "Daftar Coupon";
        $data['nama_level'] = strtolower(trim($this->session->userdata('nama_level')));
        $this->load->view('coupon/coupon_list', $data);
    }


    public function form_input_code()
    {
        $data['title'] = "Input Kode Voucher";
        $this->load->view('coupon/form_coupon', $data);
    }

    public function used_coupon()
    {
        $code = trim($this->input->post('code_voucher'));

        if (empty($code)) {
            echo json_encode(['status' => 'error', 'message' => 'Kode voucher tidak boleh kosong']);
            return;
        }

        // Cek apakah kode ada
        $this->db->where('code_coupon', $code);
        $query = $this->db->get('coupons');

        if ($query->num_rows() === 0) {
            // Kode tidak ditemukan
            echo json_encode([
                'status' => 'not_found',
                'code' => $code
            ]);
            return;
        }

        $row = $query->row();

        if ($row->is_used == 1) {
            // Sudah digunakan
            echo json_encode([
                'status' => 'already_used',
                'code' => $code
            ]);
            return;
        }

        // Kode ada dan belum digunakan → update menjadi digunakan
        $this->db->where('code_coupon', $code);
        $update = $this->db->update('coupons', [
            'is_used' => 1,
            'updated_at' => date('Y-m-d H:i:s'),
            'updated_by' => $this->session->userdata('id_admin'),
        ]);



        if ($update) {
            echo json_encode([
                'status' => 'success',
                'code' => $code,
                'nominal_voucher' => number_format($row->nominal_vocher, 0, ',', '.'),
                'updated_at' => date('d M Y H:i:s')
            ]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Gagal update voucher']);
        }
    }

    // ====================== AJAX DATA FOR DATATABLES ======================
    public function get_coupons_ajax()
    {
        $is_used = $this->input->post('is_used');
        $search = trim($this->input->post('search')['value'] ?? '');

        // ========================================
        // 1. Hitung recordsFiltered (dengan filter)
        // ========================================
        $this->db->reset_query();
        $this->db->from('coupons');

        if ($is_used !== '' && $is_used !== null) {
            $this->db->where('is_used', $is_used);
        }

        if ($search !== '') {
            $this->db->like('code_coupon', $search);
        }

        $recordsFiltered = $this->db->count_all_results();   // tidak pakai parameter table

        // ========================================
        // 2. Ambil data untuk tabel
        // ========================================
        $this->db->reset_query();
        //$this->db->select('code_coupon, is_used, created_at, updated_at,created_by, updated_by, nominal_vocher');

        $this->db->select('
            coupons.code_coupon,
            coupons.is_used,
            coupons.created_at,
            coupons.updated_at,
            coupons.created_by,
            coupons.updated_by,
            coupons.nominal_vocher,
            c.nama_admin     AS created_by_name,   
            u.nama_admin     AS updated_by_name  
        ');


        $this->db->from('coupons');
        $this->db->join('admin as c', 'c.id_admin = coupons.created_by', 'left');
        $this->db->join('admin as u', 'u.id_admin = coupons.updated_by', 'left');

        if ($is_used !== '' && $is_used !== null) {
            $this->db->where('is_used', $is_used);
        }

        if ($search !== '') {
            $this->db->like('code_coupon', $search);
        }

        $this->db->order_by('created_at', 'DESC');
        $this->db->limit(
            (int) $this->input->post('length'),
            (int) $this->input->post('start')
        );

        $query = $this->db->get();

        $data = [];
        foreach ($query->result() as $row) {
            $status = ($row->is_used == 0)
                ? '<span class="badge bg-success">Belum Digunakan</span>'
                : '<span class="badge bg-danger">Sudah Digunakan</span>';

            $data[] = [
                'checkbox' => '<input type="checkbox" class="coupon-checkbox" value="' . htmlspecialchars($row->code_coupon) . '">',
                'code_coupon' => '<strong class="coupon-code">' . htmlspecialchars($row->code_coupon) . '</strong>',
                'status' => $status,
                'created_at' => date('d M Y H:i', strtotime($row->created_at)),
                'updated_at' => $row->updated_at ? date('d M Y H:i', strtotime($row->updated_at)) : '-',

                // Kolom baru dari JOIN tabel admin
                'created_by' => $row->created_by_name ?? '-',
                'updated_by' => $row->updated_by_name ?? '-',
                'nominal_vocher' => number_format($row->nominal_vocher, 0, ',', '.')
            ];
        }

        // recordsTotal = total semua coupon tanpa filter
        $recordsTotal = $this->db->count_all('coupons');

        $output = [
            "draw" => intval($this->input->post('draw')),
            "recordsTotal" => $recordsTotal,
            "recordsFiltered" => $recordsFiltered,
            "data" => $data
        ];

        echo json_encode($output);
    }

    // ====================== GENERATE COUPON (AJAX) ======================
    public function generate()
    {
        $jumlah = (int) $this->input->post('jumlah');
        $nominal = (int) $this->input->post('nominal');

        if ($jumlah < 1 || $jumlah > 500) {
            echo json_encode(['status' => 'error', 'message' => 'Jumlah harus antara 1 sampai 500']);
            return;
        }

        $result = $this->Coupon_model->generate_coupons_batch($jumlah, $nominal);

        echo json_encode($result);
    }

    public function download_excel()
    {

        $selected = $this->input->post('selected_coupons');

        if (empty($selected) || !is_array($selected)) {
            echo "Pilih minimal 1 coupon";
            return;
        }

        $this->db->where_in('code_coupon', $selected);
        $coupons = $this->db->get('coupons')->result();

        if (empty($coupons)) {
            echo "Coupon tidak ditemukan";
            return;
        }

        // ====================== HEADER EXCEL ======================
        header("Content-type: application/vnd-ms-excel");
        header("Content-Disposition: attachment; filename=Klaim_Fee_Referral_" . date('YmdHis') . ".xls");
        header("Pragma: no-cache");
        header("Expires: 0");

        echo '<table border="1" cellpadding="5" cellspacing="0" style="font-family:Arial">';
        echo '<tr style="background:#4CAF50;color:white;font-weight:bold;text-align:center">';
        echo '<th>No</th>';
        echo '<th>Kode Coupon</th>';
        echo '<th>Nominal Voucher</th>';
        echo '</tr>';

        $no = 1;
        foreach ($coupons as $row) {
            echo '<tr>';
            echo '<td style="text-align:center">' . $no++ . '</td>';
            echo '<td>' . strtoupper($row->code_coupon) . '</td>';
            echo '<td style="text-align:right">Rp ' . number_format($row->nominal_vocher, 0, ',', '.') . '</td>';
            echo '</tr>';
        }
        echo '</table>';
        exit;
    }

    // ====================== DOWNLOAD PDF ======================
    public function download_pdf()
    {
        $selected = $this->input->post('selected_coupons');
        if (empty($selected) || !is_array($selected)) {
            echo json_encode(['status' => 'error', 'message' => 'Pilih minimal 1 coupon']);
            return;
        }

        $this->db->where_in('code_coupon', $selected);
        $coupons = $this->db->get('coupons')->result();

        if (empty($coupons)) {
            echo json_encode(['status' => 'error', 'message' => 'Coupon tidak ditemukan']);
            return;
        }

        $this->load->library('MyFpdf');
        $pdf = new MyFpdf('P', 'mm', 'A4');
        $pdf->SetAutoPageBreak(false);
        $pdf->SetMargins(0, 0, 0);

        // ====================== PATH TEMPLATE GAMBAR ======================
        $template_path = FCPATH . 'assets/referal.jpeg';

        if (!file_exists($template_path)) {
            die('Template gambar tidak ditemukan di: ' . $template_path . '<br><br>Periksa path dan nama file!');
        }

        $voucher_width = 190;
        $voucher_height = 93;
        $margin_top = 8;

        foreach ($coupons as $i => $coupon) {

            if ($i % 3 === 0) {
                $pdf->AddPage();
            }

            $y = $margin_top + ($i % 3) * $voucher_height;

            // ====================== TEMPEL GAMBAR TEMPLATE ======================
            $pdf->Image($template_path, 10, $y, $voucher_width, $voucher_height);

            // ====================== CODE COUPON PERTAMA (SUDAH SESUAI) ======================
            $pdf->SetFont('Arial', 'B', 30);           // ukuran font pertama
            $pdf->SetTextColor(0, 0, 0);               // warna hitam
            $pdf->SetXY(36, $y + 75);                  // koordinat pertama
            $pdf->Cell(72, 12, strtoupper($coupon->code_coupon), 0, 1, 'C');

            // ====================== CODE COUPON KEDUA (BARU DITAMBAHKAN) ======================
            // ←←← UBAH DI SINI sesuai kebutuhan kamu
            $pdf->SetFont('Arial', 'B', 18);           // ← Ubah ukuran font kedua
            $pdf->SetTextColor(0, 0, 0);               // ← Ubah warna jika perlu
            $pdf->SetXY(5, $y + 75);                 // ← Ubah koordinat X dan Y kedua
            $pdf->Cell(55, 12, strtoupper($coupon->code_coupon), 0, 1, 'C');

            //nominal

            // ====================== nominal PERTAMA (SUDAH SESUAI) ======================
            $pdf->SetFont('Arial', 'B', 40);           // ukuran font pertama
            $pdf->SetTextColor(0, 0, 0);               // warna hitam
            $pdf->SetXY(80, $y + 50);                  // koordinat pertama
            $pdf->Cell(72, 12, "Rp. " . number_format($coupon->nominal_vocher, 0, ',', '.'), 0, 1, 'C');

            //


            $pdf->SetFont('Arial', 'B', 13);
            $pdf->SetTextColor(0, 0, 0);
            $pdf->SetXY(14, $y + 60);                    // ← Ubah X jadi 5 (bisa dicoba 3 atau 8)
            $pdf->Cell(55, 12, "Rp. " . number_format(1200000, 0, ',', '.'), 0, 1, 'L');
            // Tambahkan code_coupon ketiga atau teks lain di sini jika diperlukan
        }

        // Output PDF
        $filename = 'Klaim_Fee_Referral_' . date('YmdHis') . '.pdf';
        $pdf->Output('D', $filename);
        exit;
    }
}
