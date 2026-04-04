<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Coupon_model extends CI_Model
{

	private $table = 'coupons';

	public function generate_coupons_batch($jumlah = 10)
	{
		if ($jumlah < 1 || $jumlah > 1000) {
			return ['success' => false, 'message' => 'Jumlah harus antara 1 - 1000'];
		}

		$this->db->trans_start();

		$coupons = [];
		for ($i = 0; $i < $jumlah; $i++) {
			$code = $this->generate_unique_code();

			$data = [
				'code_coupon' => strtoupper($code),
				'is_used' => 0,
				'created_at' => date('Y-m-d H:i:s')
			];

			$this->db->insert($this->table, $data);
			$coupons[] = $code;
		}

		$this->db->trans_complete();

		if ($this->db->trans_status() === FALSE) {
			return ['success' => false, 'message' => 'Gagal menyimpan coupon'];
		}

		return [
			'success' => true,
			'total' => count($coupons),
			'coupons' => $coupons
		];
	}

	// Generate kode unik (3 huruf + 3 angka)
	private function generate_unique_code()
	{
		$letters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$numbers = '0123456789';
		$code = '';

		// 3 huruf
		for ($i = 0; $i < 3; $i++) {
			$code .= $letters[rand(0, 25)];
		}
		// 3 angka
		for ($i = 0; $i < 3; $i++) {
			$code .= $numbers[rand(0, 9)];
		}

		// Cek apakah sudah ada di database, kalau ada generate ulang
		while ($this->is_code_exists($code)) {
			$code = $this->generate_unique_code(); // recursive call
		}

		return $code;
	}

	private function is_code_exists($code)
	{
		$this->db->where('code_coupon', strtoupper($code));
		return $this->db->get($this->table)->num_rows() > 0;
	}
}