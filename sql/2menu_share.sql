INSERT INTO page_akses (is_internal, link, `group`, menu, kategori, aktif,is_hidden)
SELECT 1, 'masterjamaahlink/link_share_jamaah', 2, 'Link Share Jamaah', 2, 1,1
FROM DUAL
WHERE NOT EXISTS (
    SELECT link 
    FROM page_akses 
    WHERE link = 'masterjamaahlink/link_share_jamaah'
);


INSERT INTO page_akses (is_internal, link, `group`, menu, kategori, aktif,is_hidden)
SELECT 1, 'masterjamaahlink/jamaahUUID', 2, 'edit jamaah uuid', 2, 1,1
FROM DUAL
WHERE NOT EXISTS (
    SELECT link 
    FROM page_akses 
    WHERE link = 'masterjamaahlink/jamaahUUID'
);