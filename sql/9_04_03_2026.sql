

INSERT INTO page_akses (is_internal, link, `group`, menu, kategori, aktif, is_hidden)
SELECT '1', 'LaporanAgenController/laporan_agent_jamaah', '2', 'Laporan Agen Jamaah', '5', '1', '0'
FROM DUAL
WHERE NOT EXISTS (
    SELECT 1 FROM page_akses 
    WHERE link = 'LaporanAgenController/laporan_agent_jamaah' AND `group` = '2'
)




--hrd
INSERT INTO page_akses (is_internal, link, `group`, menu, kategori, aktif, is_hidden)
SELECT '1', 'LaporanAgenController/laporan_agent_jamaah', '9', 'Laporan Agen Jamaah', '5', '1', '0'
FROM DUAL
WHERE NOT EXISTS (
    SELECT 1 FROM page_akses 
    WHERE link = 'LaporanAgenController/laporan_agent_jamaah' AND `group` = '9'
)
