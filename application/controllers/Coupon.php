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
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        if ($update) {
            echo json_encode([
                'status' => 'success',
                'code' => $code,
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
        $this->db->select('code_coupon, is_used, created_at, updated_at');
        $this->db->from('coupons');

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
                'updated_at' => $row->updated_at
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

        if ($jumlah < 1 || $jumlah > 500) {
            echo json_encode(['status' => 'error', 'message' => 'Jumlah harus antara 1 sampai 500']);
            return;
        }

        $result = $this->Coupon_model->generate_coupons_batch($jumlah);

        echo json_encode($result);
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

        $this->load->library('MyFpdf');

        $pdf = new MyFpdf('P', 'mm', 'A4');
        $pdf->AddPage();

        $pdf->SetFont('Arial', 'B', 16);
        $pdf->Cell(0, 10, 'DAFTAR KUPON DISKON', 0, 1, 'C');
        $pdf->Ln(15);

        $i = 0;
        foreach ($coupons as $coupon) {
            if ($i % 3 == 0 && $i != 0) {
                $pdf->AddPage();
            }

            $pdf->SetFont('Arial', 'B', 14);
            $pdf->Cell(0, 10, 'KUPON DISKON', 0, 1, 'C');

            $pdf->SetFont('Arial', 'B', 24);
            $pdf->Cell(0, 20, strtoupper($coupon->code_coupon), 1, 1, 'C');

            $pdf->SetFont('Arial', '', 12);
            $pdf->Cell(0, 10, 'Gunakan kode ini untuk mendapatkan diskon', 0, 1, 'C');
            $pdf->Cell(0, 10, 'Berlaku sekali pakai', 0, 1, 'C');
            $pdf->Cell(0, 10, 'Dibuat: ' . date('d M Y H:i', strtotime($coupon->created_at)), 0, 1, 'C');

            $pdf->Ln(25);
            $i++;
        }

        $filename = 'Kupon_' . date('YmdHis') . '.pdf';
        $pdf->Output('D', $filename);
        exit;
    }
}