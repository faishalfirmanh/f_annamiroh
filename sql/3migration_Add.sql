-- 1. Matikan mode ketat
SET sql_mode = '';

-- 2. Jalankan perintah tambah kolom Anda
ALTER TABLE data_jamaah
ADD COLUMN random_uuid VARCHAR(36) NULL,
ADD COLUMN is_updated TINYINT(1) DEFAULT 0,
ADD COLUMN location_prov BIGINT NULL,
ADD COLUMN location_city BIGINT NULL,
ADD COLUMN location_disct BIGINT NULL,
ADD COLUMN location_village BIGINT NULL;

-- 3. (Opsional) Kembalikan mode ketat (atau biarkan saja sampai restart server)
SET sql_mode = 'NO_ENGINE_SUBSTITUTION,STRICT_TRANS_TABLES';