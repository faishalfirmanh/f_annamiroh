

CREATE TABLE IF NOT EXISTS user_access_jamaah (
    id INT auto_increment primary KEY,
    username VARCHAR(20) NOT NULL,
    id_jamaah int not NULL,
    password VARCHAR(70) not NULL,
  password_show VARCHAR(70) not null,
    login_time DATETIME NULL,
    is_login TINYINT NOT NULL DEFAULT 0, 
    UNIQUE KEY (username)
) ENGINE=InnoDB;

----untuk generate akses
INSERT INTO page_akses (is_internal, link, `group`, menu, kategori, aktif, is_hidden)
SELECT '1', 'master/generate_akses', '2', 'generate akses', '5', '1', '1'
FROM DUAL
WHERE NOT EXISTS (
    SELECT 1 FROM page_akses 
    WHERE link = 'master/generate_akses' AND `group` = '2'
)

--lihat akses login_time
INSERT INTO page_akses (is_internal, link, `group`, menu, kategori, aktif, is_hidden)
SELECT '1', 'master/view_akses', '2', 'generate akses', '5', '1', '1'
FROM DUAL
WHERE NOT EXISTS (
    SELECT 1 FROM page_akses 
    WHERE link = 'master/view_akses' AND `group` = '2'
)
