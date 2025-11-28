
DROP VIEW IF EXISTS view_riwayat_transaksi_lengkap;

CREATE VIEW view_riwayat_transaksi_lengkap AS
SELECT 
    ptp.id_transaksi_paket, 
    ptp.tanggal, 
    ptp.tanggal_transfer, 
    ptp.debet, 
    ptp.kredit, 
    ptp.saldo, 
    ptp.keterangan, 
    jt.nama_transaksi, 
    dj.nama_jamaah,
    CONCAT(djp.estimasi_keberangkatan, ' ', djp.Program) AS nama_paket,
    dj.id_jamaah AS jamaah_id_filter 
FROM 
    pembayaran_transaksi_paket ptp 
JOIN 
    transaksi_paket tp ON ptp.id_transaksi_paket = tp.id 
LEFT JOIN 
    jenis_transaksi jt ON ptp.jenis_transaksi = jt.id 
JOIN 
    data_jamaah dj ON tp.jamaah = dj.id_jamaah 
JOIN 
    data_jamaah_paket djp ON tp.paket_umroh = djp.id;