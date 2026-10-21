ALTER TABLE suratRekomVaksin ADD COLUMN nomor_surat VARCHAR(100) NULL, ADD UNIQUE KEY uq_nomor_surat (nomor_surat);

CREATE TABLE nomor_counter (
    kode    VARCHAR(20)  NOT NULL,
    tahun   SMALLINT     NOT NULL,
    last_no INT UNSIGNED NOT NULL DEFAULT 0,
    PRIMARY KEY (kode, tahun)
);