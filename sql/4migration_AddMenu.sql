INSERT INTO page_akses (is_internal, link, `group`, menu, kategori, aktif, is_hidden)
SELECT '1', 'laporan/generate_jamaah', '2', 'Laporan Generate Jamaah', '5', '1', '0'
FROM DUAL
WHERE NOT EXISTS (
    SELECT link FROM page_akses WHERE link = 'laporan/generate_jamaah'
);