CREATE table IF NOT EXISTS single_link_share_jamaah (
    id INT AUTO_INCREMENT PRIMARY KEY,
    paket_id int NOT NULL,
    agen_id int not null default 0,
    random_uuid VARCHAR(100) NOT NULL,
    qty_generate int not null,
    status smallint not null default 0,
    qty_submit int not null default 0,
    created_by int  NULL
);

SET SESSION sql_mode = 'NO_ENGINE_SUBSTITUTION';

--- baru ini
ALTER TABLE data_jamaah
ADD child_id_single_link int NULL DEFAULT NULL;