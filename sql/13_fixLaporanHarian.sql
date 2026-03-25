ALTER TABLE pembayaran_transaksi_paket 
ADD INDEX idx_deleted_transaksi (deleted, id_transaksi_paket);

ALTER TABLE transaksi_paket 
ADD INDEX idx_jamaah (jamaah);