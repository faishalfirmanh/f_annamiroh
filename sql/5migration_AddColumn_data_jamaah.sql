--- jalankan 1,1, ini dulu
SET SESSION sql_mode = 'NO_ENGINE_SUBSTITUTION';

--- baru ini
ALTER TABLE data_jamaah
ADD status_generate TINYINT(1) NULL DEFAULT 0;