INSERT INTO page_akses (is_internal, link, `group`, menu, kategori, aktif, is_hidden)
SELECT '1', 'SingleLinkShare/generate_jamaah', '2', 'Single Link Jamaah', '5', '1', '0'
FROM DUAL
WHERE NOT EXISTS (
    SELECT 1 FROM page_akses 
    WHERE link = 'SingleLinkShare/generate_jamaah' AND `group` = '2'
)
----hrd
INSERT INTO page_akses (is_internal, link, `group`, menu, kategori, aktif, is_hidden)
SELECT '1', 'SingleLinkShare/generate_jamaah', '9', 'Single Link Jamaah', '5', '1', '0'
FROM DUAL
WHERE NOT EXISTS (
    SELECT 1 FROM page_akses 
    WHERE link = 'SingleLinkShare/generate_jamaah' AND `group` = '9'
); -- <--- PERHATIKAN TITIK KOMA INI


