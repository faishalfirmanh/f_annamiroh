INSERT INTO page_akses (is_internal, link, `group`, menu, kategori, aktif, is_hidden)
SELECT '1', 'transaksi_op/master_bank', '1', 'Master Bank', '2', '1', '0'
FROM DUAL
WHERE NOT EXISTS (
    SELECT 1 FROM page_akses 
    WHERE link = 'transaksi_op/master_bank' AND `group` = '1'
);


----
CREATE TABLE IF NOT EXISTS master_bank (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nama_bank VARCHAR(100) NOT NULL,
    is_active TINYINT NOT NULL DEFAULT 0,
    UNIQUE KEY (nama_bank)
) ENGINE=InnoDB;


ALTER TABLE  pembayaran_transaksi_paket
ADD bank_id INT NULL;

---tambah kolom bank id pada pembayaran transaksi paket

ALTER TABLE pembayaran_transaksi_paket
MODIFY tanggal VARCHAR(20) NULL,
MODIFY tanggal_transfer VARCHAR(20) NULL;

UPDATE pembayaran_transaksi_paket
SET tanggal = NULL
WHERE tanggal = '0000-00-00'
   OR tanggal = '';

UPDATE pembayaran_transaksi_paket
SET tanggal_transfer = NULL
WHERE tanggal_transfer = '0000-00-00'
   OR tanggal_transfer = '';

ALTER TABLE pembayaran_transaksi_paket
MODIFY tanggal DATE NULL,
MODIFY tanggal_transfer DATE NULL;

ALTER TABLE pembayaran_transaksi_paket
ADD COLUMN bank_id INT UNSIGNED NULL;