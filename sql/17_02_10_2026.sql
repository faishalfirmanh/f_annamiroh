INSERT INTO page_akses (is_internal, link, `group`, menu, kategori, aktif, is_hidden)
SELECT '1', 'JamaahLinkShare/suratVaksin', '2', 'Surat Vaksin', '2', '1', '0'
FROM DUAL
WHERE NOT EXISTS (
    SELECT 1 FROM page_akses 
    WHERE link = 'JamaahLinkShare/suratVaksin' AND `group` = '2'
);

INSERT INTO page_akses (is_internal, link, `group`, menu, kategori, aktif, is_hidden)
SELECT '1', 'JamaahLinkShare/downloadSuratVaksin', '2', 'Surat Vaksin', '2', '1', '1'
FROM DUAL
WHERE NOT EXISTS (
    SELECT 1 FROM page_akses 
    WHERE link = 'JamaahLinkShare/downloadSuratVaksin' AND `group` = '2'
);

/*create table*/
CREATE TABLE IF NOT EXISTS suratRekomVaksin (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    nama_jamaah VARCHAR(255) NULL,
    id_nama_jamaah BIGINT UNSIGNED NULL,
    no_passport_or_nik VARCHAR(255) NOT NULL,
    tanggal_keberangkatan DATE NOT NULL,
    alamat TEXT NULL,
    travel ENUM('namiroh', 'tajalli', 'rihlah','antrav') NOT NULL,
    PRIMARY KEY (id),
    INDEX idx_id_nama_jamaah_vaksin (id_nama_jamaah)
) ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_unicode_ci;


