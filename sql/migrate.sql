ALTER TABLE data_jamaah_paket
ADD paket_id INT;

ALTER TABLE data_jamaah
ADD jenis_vaksin_2
ENUM('[Belum Vaksin]', 'Sinovac', 'Bio Farma', 'AstraZeneca', 'Sinopharm', 'Moderna', 'Pfizer', 'Sputnik V', 'Janssen', 'Convidecia', 'Zifivax', 'Covovax');

ALTER TABLE data_jamaah
ADD jenis_vaksin_3
ENUM('[Belum Vaksin]', 'Sinovac', 'Bio Farma', 'AstraZeneca', 'Sinopharm', 'Moderna', 'Pfizer', 'Sputnik V', 'Janssen', 'Convidecia', 'Zifivax', 'Covovax');

ALTER TABLE data_jamaah
ADD jenis_vaksin_4
ENUM('[Belum Vaksin]', 'Sinovac', 'Bio Farma', 'AstraZeneca', 'Sinopharm', 'Moderna', 'Pfizer', 'Sputnik V', 'Janssen', 'Convidecia', 'Zifivax', 'Covovax');

ALTER TABLE data_jamaah
ADD tgl_vaksin_3 DATE;

ALTER TABLE data_jamaah
ADD tgl_vaksin_4 DATE;

ALTER TABLE transaksi_paket
ADD tgl_deposit DATE;

ALTER TABLE transaksi_paket
ADD tgl_pelunasan DATE;

ALTER TABLE transaksi_paket
ADD permintaan_tambahan TEXT;

ALTER TABLE pembayaran_transaksi_paket
ADD receiver_debit VARCHAR(255);

INSERT INTO page_akses 
(`is_internal`, `link`, `group`, `kategori`, `aktif`, is_hidden) 
VALUES (1, 'transaksi_op/receiver_debit_update', 1, 1, 1, 1);


INSERT INTO page_akses 
(`is_internal`, `link`, `group`, `kategori`, `aktif`, is_hidden) 
VALUES (1, 'transaksi_op/receiver_debit_update', 7, 1, 1, 1);

ALTER TABLE data_jamaah_paket
ADD paket_tunda BOOLEAN;

ALTER TABLE data_jamaah_paket
ADD is_active BOOLEAN;

ALTER TABLE data_jamaah_paket
ADD estimasi_tgl_keberangkatan DATE;

INSERT INTO page_akses 
(`is_internal`, `link`, `group`, `kategori`, `aktif`) 
VALUES ('1', 'transaksi/pembayaran_invoice', '1', '1', '1');

INSERT INTO page_akses 
(`is_internal`, `link`, `group`, `kategori`, `aktif`) 
VALUES ('1', 'transaksi/pembayaran_invoice', '2', '1', '1');

INSERT INTO page_akses (`is_internal`, `link`, `group`, `kategori`, `aktif`) 
    VALUES ('1', 'transaksi_op/manifest_import', '2', '1', '1');
INSERT INTO page_akses (`is_internal`, `link`, `group`, `kategori`, `aktif`) 
    VALUES ('1', 'transaksi_op/manifest_import_do', '2', '1', '1');

ALTER TABLE pembayaran_transaksi_paket
ADD deleted BOOLEAN;

ALTER TABLE data_jamaah_paket
ADD deleted BOOLEAN;

ALTER TABLE data_jamaah
ADD user_id INT;

ALTER TABLE pembayaran_transaksi_paket
ADD deleted_at TIMESTAMP;
ALTER TABLE pembayaran_transaksi_paket
ADD deleted_by INT;

ALTER TABLE data_jamaah_paket
ADD deleted_at TIMESTAMP;
ALTER TABLE data_jamaah_paket
ADD deleted_by INT;

-- already run

INSERT INTO page_akses (is_internal, link, `group`, menu, kategori, aktif)
VALUES (1, 'transaksi_op/pembayaran', 1, 'Pembayaran Transaksi Umroh', 2, 1);
-- alreay run


-- new module

INSERT INTO page_akses (is_internal, link, `group`, menu, kategori, aktif)
VALUES (1, 'master/koper', 1, 'Koper', 2, 1);

INSERT INTO page_akses (is_internal, link, `group`, menu, kategori, aktif)
VALUES (1, 'master/koper', 2, 'Koper', 2, 1);

INSERT INTO page_akses (is_internal, link, `group`, menu, kategori, aktif)
VALUES (1, 'master/triple', 1, 'Triple', 2, 1);

INSERT INTO page_akses (is_internal, link, `group`, menu, kategori, aktif)
VALUES (1, 'master/triple', 2, 'Triple', 2, 1);

INSERT INTO page_akses (is_internal, link, `group`, menu, kategori, aktif)
VALUES (1, 'master/double', 1, 'Double', 2, 1);

INSERT INTO page_akses (is_internal, link, `group`, menu, kategori, aktif)
VALUES (1, 'master/double', 2, 'Double', 2, 1);

INSERT INTO page_akses (is_internal, link, `group`, menu, kategori, aktif)
VALUES (1, 'master/passport', 1, 'Passport', 2, 1);

INSERT INTO page_akses (is_internal, link, `group`, menu, kategori, aktif)
VALUES (1, 'master/passport', 2, 'Passport', 2, 1);

INSERT INTO page_akses (is_internal, link, `group`, menu, kategori, aktif)
VALUES (1, 'master/vaksin', 1, 'Vaksin', 2, 1);

INSERT INTO page_akses (is_internal, link, `group`, menu, kategori, aktif)
VALUES (1, 'master/vaksin', 2, 'Vaksin', 2, 1);

-- new module

-- create koper table
CREATE TABLE data_koper (
    id INT NOT NULL AUTO_INCREMENT,
    nama VARCHAR(255) NOT NULL,
    biaya DOUBLE NOT NULL,
    CONSTRAINT nama_UNIQUE UNIQUE (nama),
    PRIMARY KEY( id)
);
-- create koper table

-- create triple table
CREATE TABLE data_triple (
    id INT NOT NULL AUTO_INCREMENT,
    nama VARCHAR(255) NOT NULL,
    biaya DOUBLE NOT NULL,
    CONSTRAINT nama_UNIQUE UNIQUE (nama),
    PRIMARY KEY( id)
);
-- create triple table

-- create double table
CREATE TABLE data_double (
    id INT NOT NULL AUTO_INCREMENT,
    nama VARCHAR(255) NOT NULL,
    biaya DOUBLE NOT NULL,
    CONSTRAINT nama_UNIQUE UNIQUE (nama),
    PRIMARY KEY( id)
);
-- create double table

-- create vaksin table
CREATE TABLE data_vaksin (
    id INT NOT NULL AUTO_INCREMENT,
    nama VARCHAR(255) NOT NULL,
    biaya DOUBLE NOT NULL,
    CONSTRAINT nama_UNIQUE UNIQUE (nama),
    PRIMARY KEY( id)
);
-- create vaksin table


-- create passport table
CREATE TABLE data_passport (
    id INT NOT NULL AUTO_INCREMENT,
    nama VARCHAR(255) NOT NULL,
    biaya DOUBLE NOT NULL,
    CONSTRAINT nama_UNIQUE UNIQUE (nama),
    PRIMARY KEY( id)
);
-- create passport table


INSERT INTO page_akses (is_internal, link, `group`, menu, kategori, aktif)
VALUES (1, 'transaksi/pembayaran_kolektif', 1, 'Pembayaran Transaksi Umroh Kolektif', 1, 1);

INSERT INTO page_akses (is_internal, link, `group`, menu, kategori, aktif)
VALUES (1, 'transaksi/pembayaran_kolektif', 2, 'Pembayaran Transaksi Umroh Kolektif', 1, 1);

CREATE TABLE transaksi_kolektif (
    id INT NOT NULL AUTO_INCREMENT,
    no_invoice VARCHAR(255) NOT NULL,
    agen_id INT,
    jamaah_id INT NOT NULL,
    paket_id INT NOT NULL,
    jumlah_kamar_double_makkah INT NOT NULL DEFAULT 0,
    jumlah_kamar_triple_makkah INT NOT NULL DEFAULT 0,
    harga_tambahan_kamar_makkah INT DEFAULT 0,
    jumlah_kamar_double_madinah INT NOT NULL DEFAULT 0,
    jumlah_kamar_triple_madinah INT NOT NULL DEFAULT 0,
    harga_tambahan_kamar_madinah INT DEFAULT 0,
    biaya_tambahan_paspor INT DEFAULT 0,
    biaya_tambahan_vaksin INT DEFAULT 0,
    uang_muka INT DEFAULT 0,
    tanggal_deposit_minimum DATE NOT NULL,
    catatan TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY(id)
);

CREATE TABLE transaksi_kolektif_anak (
    id INT NOT NULL AUTO_INCREMENT,
    transaksi_kolektif_id INT, 
    jamaah_induk_id INT NOT NULL,
    jamaah_anak_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY(id)
);

INSERT INTO page_akses (is_internal, link, `group`, menu, kategori, aktif, is_hidden)
VALUES (1, 'transaksi/pembayaran_kolektif_anak', 1, 'Pembayaran Transaksi Umroh Kolektif Keluarga', 1, 1, 1);

INSERT INTO page_akses (is_internal, link, `group`, menu, kategori, aktif, is_hidden)
VALUES (1, 'transaksi/pembayaran_kolektif_anak', 2, 'Pembayaran Transaksi Umroh Kolektif Keluarga', 1, 1, 1);

INSERT INTO page_akses (is_internal, link, `group`, menu, kategori, aktif, is_hidden)
VALUES (1, 'transaksi/pembayaran_kolektif_invoice', 1, 'Pembayaran Transaksi Umroh Kolektif invoice', 1, 1, 1);

INSERT INTO page_akses (is_internal, link, `group`, menu, kategori, aktif, is_hidden)
VALUES (1, 'transaksi/pembayaran_kolektif_invoice', 2, 'Pembayaran Transaksi Umroh Kolektif Invoice', 1, 1, 1);

SET SQL_SAFE_UPDATES = 0;

UPDATE page_akses
SET is_hidden = 1
WHERE link = 'transaksi_op/receiver_debit_update';

UPDATE page_akses
SET is_hidden = 1
WHERE link = 'transaksi/pembayaran_invoice';

SET SQL_SAFE_UPDATES = 1;


CREATE TABLE transaksi_kolektif_kontrak (
    id INT NOT NULL AUTO_INCREMENT,
    transaksi_kolektif_id INT, 
    nama_kontrak VARCHAR(255) NOT NULL,
    nomor_kontrak VARCHAR(255) NOT NULL,
    tanggal_pencairan DATE NOT NULL,
    deskripsi TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT nama_UNIQUE UNIQUE (nomor_kontrak),
    PRIMARY KEY(id)
);

INSERT INTO page_akses (is_internal, link, `group`, menu, kategori, aktif, is_hidden)
VALUES (1, 'transaksi/pembayaran_kolektif_kontrak', 1, 'Pembayaran Transaksi Umroh Kolektif Kontrak', 1, 1, 1);

INSERT INTO page_akses (is_internal, link, `group`, menu, kategori, aktif, is_hidden)
VALUES (1, 'transaksi/pembayaran_kolektif_kontrak', 2, 'Pembayaran Transaksi Umroh Kolektif Kontrak', 1, 1, 1);

ALTER TABLE transaksi_kolektif
ADD biaya_lain INT UNSIGNED NOT NULL DEFAULT 0;

ALTER TABLE transaksi_kolektif_anak
ADD biaya_lain INT UNSIGNED NOT NULL DEFAULT 0;

ALTER TABLE transaksi_kolektif_anak
ADD biaya_tambahan_paspor INT UNSIGNED NOT NULL DEFAULT 0;

ALTER TABLE transaksi_kolektif_anak
ADD biaya_tambahan_vaksin INT UNSIGNED NOT NULL DEFAULT 0;

CREATE TABLE transaksi_kolektif_pembayaran (
    id INT NOT NULL AUTO_INCREMENT,
    no_invoice VARCHAR(255) NOT NULL,
    transaksi_kolektif_id INT, 
    tanda CHAR(1) NOT NULL DEFAULT '+',
    nominal INT UNSIGNED NOT NULL,
    metode ENUM('cash', 'tf') NOT NULL DEFAULT 'cash',
    tanggal_transfer DATETIME NOT NULL,
    jenis_transaksi_id INT NOT NULL,
    keterangan TEXT,
    user_id INT NOT NULL,
    penerima VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at DATETIME,
    deleted_by INT,
    PRIMARY KEY(id)
);

SET SQL_SAFE_UPDATES = 0;

UPDATE page_akses
SET link = 'transaksi/transaksi_kolektif'
WHERE link = 'transaksi/pembayaran_kolektif';

UPDATE page_akses
SET link = 'transaksi/transaksi_kolektif_invoice'
WHERE link = 'transaksi/pembayaran_kolektif_invoice';

UPDATE page_akses
SET link = 'transaksi/transaksi_kolektif_anak'
WHERE link = 'transaksi/pembayaran_kolektif_anak';

UPDATE page_akses
SET link = 'transaksi/transaksi_kolektif_kontrak'
WHERE link = 'transaksi/pembayaran_kolektif_kontrak';

SET SQL_SAFE_UPDATES = 1;

INSERT INTO page_akses (is_internal, link, `group`, menu, kategori, aktif, is_hidden)
VALUES (1, 'transaksi/transaksi_kolektif_pembayaran', 1, 'Pembayaran Transaksi Umroh Kolektif Debit/Kredit', 1, 1, 1);

INSERT INTO page_akses (is_internal, link, `group`, menu, kategori, aktif, is_hidden)
VALUES (1, 'transaksi/transaksi_kolektif_pembayaran', 2, 'Pembayaran Transaksi Umroh Kolektif Debit/Kredit', 1, 1, 1);

ALTER TABLE transaksi_kolektif
ADD deleted_at DATETIME;

ALTER TABLE transaksi_kolektif
ADD deleted_by INT;

ALTER TABLE transaksi_kolektif MODIFY jumlah_kamar_double_makkah INT UNSIGNED NOT NULL DEFAULT 0;
ALTER TABLE transaksi_kolektif MODIFY jumlah_kamar_triple_makkah INT UNSIGNED NOT NULL DEFAULT 0;
ALTER TABLE transaksi_kolektif MODIFY harga_tambahan_kamar_makkah INT UNSIGNED NOT NULL DEFAULT 0;
ALTER TABLE transaksi_kolektif MODIFY jumlah_kamar_double_madinah INT UNSIGNED NOT NULL DEFAULT 0;
ALTER TABLE transaksi_kolektif MODIFY jumlah_kamar_triple_madinah INT UNSIGNED NOT NULL DEFAULT 0;
ALTER TABLE transaksi_kolektif MODIFY harga_tambahan_kamar_madinah INT UNSIGNED NOT NULL DEFAULT 0;
ALTER TABLE transaksi_kolektif MODIFY biaya_tambahan_paspor INT UNSIGNED NOT NULL DEFAULT 0;
ALTER TABLE transaksi_kolektif MODIFY biaya_tambahan_vaksin INT UNSIGNED NOT NULL DEFAULT 0;
ALTER TABLE transaksi_kolektif MODIFY uang_muka INT UNSIGNED NOT NULL DEFAULT 0;

ALTER TABLE transaksi_kolektif
ADD diskon INT UNSIGNED NOT NULL DEFAULT 0;

ALTER TABLE transaksi_kolektif
ADD fee INT UNSIGNED NOT NULL DEFAULT 0;

INSERT INTO page_akses (is_internal, link, `group`, menu, kategori, aktif)
VALUES (1, 'transaksi/transaksi_kolektif_laporan_harian', 1, 'Laporan Harian Kolektif', 5, 1);

INSERT INTO page_akses (is_internal, link, `group`, menu, kategori, aktif)
VALUES (1, 'transaksi/transaksi_kolektif_laporan_harian', 2, 'Laporan Harian Kolektif', 5, 1);

ALTER TABLE transaksi_paket
ADD metode ENUM('cash', 'tf') DEFAULT NULL;

-- 16 maret 2024

ALTER TABLE transaksi_kolektif
DROP COLUMN jamaah_id;

ALTER TABLE transaksi_kolektif
ADD biaya_perlengkapan INT UNSIGNED;

ALTER TABLE transaksi_kolektif_anak
DROP COLUMN jamaah_induk_id;

ALTER TABLE transaksi_kolektif_anak
ADD biaya_perlengkapan INT UNSIGNED;

ALTER TABLE transaksi_kolektif_anak
ADD catatan TEXT;

ALTER TABLE transaksi_kolektif_kontrak
ADD nominal INT UNSIGNED;

ALTER TABLE transaksi_kolektif_kontrak
ADD catatan TEXT;

-- 21 maret 2024

ALTER TABLE transaksi_kolektif
ADD metode ENUM('cash', 'tf') DEFAULT NULL;

-- 26 maret 2024

ALTER TABLE transaksi_kolektif_pembayaran
ADD updated_by INT UNSIGNED DEFAULT NULL;

-- 31 maret 2024


SET SQL_SAFE_UPDATES = 0;

UPDATE page_akses
SET is_hidden = 1
WHERE link = 'master/koper';

UPDATE page_akses
SET is_hidden = 1
WHERE link = 'master/triple';

UPDATE page_akses
SET is_hidden = 1
WHERE link = 'master/double';

UPDATE page_akses
SET is_hidden = 1
WHERE link = 'master/passport';

UPDATE page_akses
SET is_hidden = 1
WHERE link = 'master/vaksin';

UPDATE page_akses
SET link = 'master/jamaah'
WHERE link = 'master/jamah';


UPDATE data_jamaah
SET tgl_lahir = '2035-01-01'
WHERE CAST(tgl_lahir AS CHAR(20)) = '0000-00-00';

UPDATE data_jamaah
SET tgl_vaksin_1 = '2035-01-01'
WHERE CAST(tgl_vaksin_1 AS CHAR(20)) = '0000-00-00';

UPDATE data_jamaah
SET tgl_vaksin_2 = '2035-01-01'
WHERE CAST(tgl_vaksin_2 AS CHAR(20)) = '0000-00-00';

UPDATE data_jamaah
SET tgl_vaksin_3 = '2035-01-01'
WHERE CAST(tgl_vaksin_3 AS CHAR(20)) = '0000-00-00';

UPDATE data_jamaah
SET tgl_vaksin_4 = '2035-01-01'
WHERE CAST(tgl_vaksin_4 AS CHAR(20)) = '0000-00-00';

UPDATE data_jamaah
SET issued = '2035-01-01'
WHERE CAST(issued AS CHAR(20)) = '0000-00-00';

UPDATE data_jamaah
SET expired = '2035-01-01'
WHERE CAST(expired AS CHAR(20)) = '0000-00-00';

ALTER TABLE data_jamaah MODIFY tgl_lahir DATE DEFAULT NULL;
ALTER TABLE data_jamaah MODIFY tgl_vaksin_1 DATE DEFAULT NULL;
ALTER TABLE data_jamaah MODIFY tgl_vaksin_2 DATE DEFAULT NULL;
ALTER TABLE data_jamaah MODIFY tgl_vaksin_3 DATE DEFAULT NULL;
ALTER TABLE data_jamaah MODIFY tgl_vaksin_4 DATE DEFAULT NULL;
ALTER TABLE data_jamaah MODIFY issued DATE DEFAULT NULL;
ALTER TABLE data_jamaah MODIFY expired DATE DEFAULT NULL;

UPDATE data_jamaah
SET tgl_lahir = NULL
WHERE CAST(tgl_lahir AS CHAR(20)) = '2035-01-01';

UPDATE data_jamaah
SET tgl_vaksin_1 = NULL
WHERE CAST(tgl_vaksin_1 AS CHAR(20)) = '2035-01-01';

UPDATE data_jamaah
SET tgl_vaksin_2 = NULL
WHERE CAST(tgl_vaksin_2 AS CHAR(20)) = '2035-01-01';

UPDATE data_jamaah
SET tgl_vaksin_3 = NULL
WHERE CAST(tgl_vaksin_3 AS CHAR(20)) = '2035-01-01';

UPDATE data_jamaah
SET tgl_vaksin_4 = NULL
WHERE CAST(tgl_vaksin_4 AS CHAR(20)) = '2035-01-01';

UPDATE data_jamaah
SET issued = NULL
WHERE CAST(issued AS CHAR(20)) = '2035-01-01';

UPDATE data_jamaah
SET expired = NULL
WHERE CAST(expired AS CHAR(20)) = '2035-01-01';


SET SQL_SAFE_UPDATES = 1;

ALTER TABLE data_jamaah
ADD created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP;

ALTER TABLE data_jamaah
ADD updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP;


-- 25-04-2024

ALTER TABLE transaksi_kolektif
DROP COLUMN jumlah_kamar_double_makkah;

ALTER TABLE transaksi_kolektif
DROP COLUMN jumlah_kamar_triple_makkah;

ALTER TABLE transaksi_kolektif
DROP COLUMN harga_tambahan_kamar_makkah;

ALTER TABLE transaksi_kolektif
DROP COLUMN jumlah_kamar_double_madinah;

ALTER TABLE transaksi_kolektif
DROP COLUMN jumlah_kamar_triple_madinah;

ALTER TABLE transaksi_kolektif
DROP COLUMN harga_tambahan_kamar_madinah;

ALTER TABLE transaksi_kolektif
ADD jumlah_upgrade_kamar_double INT;

ALTER TABLE transaksi_kolektif
ADD harga_upgrade_kamar_double INT;

ALTER TABLE transaksi_kolektif
ADD jumlah_upgrade_kamar_triple INT;

ALTER TABLE transaksi_kolektif
ADD harga_upgrade_kamar_triple INT;

ALTER TABLE transaksi_kolektif
ADD biaya_lain_alias VARCHAR(255);

ALTER TABLE transaksi_kolektif
ADD biaya_lain_2 INT UNSIGNED NOT NULL DEFAULT 0;

ALTER TABLE transaksi_kolektif
ADD biaya_lain_alias_2 VARCHAR(255);

ALTER TABLE transaksi_kolektif
DROP COLUMN uang_muka;

ALTER TABLE transaksi_kolektif
DROP COLUMN metode;

ALTER TABLE transaksi_kolektif
DROP COLUMN fee;

ALTER TABLE transaksi_kolektif
DROP COLUMN tanggal_deposit_minimum;


INSERT INTO page_akses (is_internal, link, `group`, menu, kategori, aktif, is_hidden)
VALUES (1, 'transaksi/transaksi_kolektif_rincian_invoice', 1, 'Cetak Rincian Invoice', 1, 1, 1);

INSERT INTO page_akses (is_internal, link, `group`, menu, kategori, aktif, is_hidden)
VALUES (1, 'transaksi/transaksi_kolektif_rincian_invoice', 2, 'Cetak Rincian Invoice', 1, 1, 1);

ALTER TABLE data_jamaah MODIFY nama_jamaah VARCHAR(100)  NOT NULL DEFAULT '';

-- 19 mei 2024

ALTER TABLE transaksi_kolektif
ADD updated_by INT;

ALTER TABLE transaksi_kolektif
ADD created_by INT;

ALTER TABLE transaksi_kolektif_pembayaran MODIFY tanggal_transfer DATETIME;

SET SQL_SAFE_UPDATES = 0;

UPDATE transaksi_kolektif_pembayaran
SET tanggal_transfer = NULL;

SET SQL_SAFE_UPDATES = 1;

ALTER TABLE transaksi_kolektif_pembayaran MODIFY tanggal_transfer DATE DEFAULT NULL;

ALTER TABLE transaksi_kolektif
ADD jumlah_jamaah INT UNSIGNED NOT NULL DEFAULT 0;



