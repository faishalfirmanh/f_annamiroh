INSERT INTO page_akses (is_internal, link, `group`, menu, kategori, aktif, is_hidden)
SELECT '1', 'master/kelurahan', '2', 'Master Kelurahan', '2', '1', '0'
FROM DUAL
WHERE NOT EXISTS (
    SELECT 1 FROM page_akses 
    WHERE link = 'master/kelurahan' AND `group` = '2'
);