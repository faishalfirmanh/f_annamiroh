INSERT INTO page_akses (is_internal, link, `group`, menu, kategori, aktif)
SELECT 1, 'master/link_share_jamaah', 2, 'Link Share Jamaah', 2, 1
FROM DUAL
WHERE NOT EXISTS (
    SELECT link 
    FROM page_akses 
    WHERE link = 'master/link_share_jamaah'
);