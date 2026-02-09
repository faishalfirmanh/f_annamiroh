-- Query 1 (INSERT)
INSERT INTO page_akses (is_internal, link, `group`, menu, kategori, aktif, is_hidden)
SELECT '1', 'laporan/generate_jamaah', '2', 'Laporan Generate Jamaah', '5', '1', '0'
FROM DUAL
WHERE NOT EXISTS (
    SELECT 1 FROM page_akses 
    WHERE link = 'laporan/generate_jamaah' AND `group` = '2'
)
UNION ALL
SELECT '1', 'laporan/generate_jamaah', '9', 'Laporan Generate Jamaah', '5', '1', '0'
FROM DUAL
WHERE NOT EXISTS (
    SELECT 1 FROM page_akses 
    WHERE link = 'laporan/generate_jamaah' AND `group` = '9'
); -- <--- PERHATIKAN TITIK KOMA INI

