INSERT INTO page_akses (is_internal, link, `group`, menu, kategori, aktif, is_hidden)
SELECT '1', 'JamaahLinkShare/surat_izin', '2', 'Surat Izin', '2', '1', '0'
FROM DUAL
WHERE NOT EXISTS (
    SELECT 1 FROM page_akses 
    WHERE link = 'JamaahLinkShare/surat_izin' AND `group` = '2'
);

/*create table*/
CREATE TABLE IF NOT EXISTS suratIzin (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    nama_jamaah VARCHAR(255) NULL,
    name_instansi VARCHAR(255) NOT NULL,
    id_nama_jamaah BIGINT UNSIGNED NULL,
    jabatan VARCHAR(255) NULL,
    date_start DATE NOT NULL,
    date_end DATE NOT NULL,
    alamat TEXT NULL,
    travel ENUM('namiroh', 'tajalli', 'rihlah','antrav') NOT NULL,
    PRIMARY KEY (id),
    INDEX idx_id_nama_jamaah (id_nama_jamaah)

) ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_unicode_ci;


INSERT INTO page_akses (is_internal, link, `group`, menu, kategori, aktif, is_hidden)
SELECT '1', 'JamaahLinkShare/download_surat_izin', '2', 'Surat Izin', '2', '1', '1'
FROM DUAL
WHERE NOT EXISTS (
    SELECT 1 FROM page_akses 
    WHERE link = 'JamaahLinkShare/download_surat_izin' AND `group` = '2'
);