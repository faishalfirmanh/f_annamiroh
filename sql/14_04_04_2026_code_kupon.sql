CREATE TABLE coupons (
    id            INT AUTO_INCREMENT PRIMARY KEY,
    code_coupon   VARCHAR(100) UNIQUE NOT NULL,
    is_used       TINYINT(1) DEFAULT 0,
    created_at    TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at    TIMESTAMP NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


/* group -> user role , kategory -> menu parent*/
INSERT INTO page_akses (is_internal, link, `group`, menu, kategori, aktif, is_hidden)
SELECT '1', 'Coupon/list_coupon', '2', 'Daftar Voucher', '5', '1', '0'
FROM DUAL
WHERE NOT EXISTS (
    SELECT 1 FROM page_akses 
    WHERE link = 'Coupon/list_coupon' AND `group` = '2'
);
INSERT INTO page_akses (is_internal, link, `group`, menu, kategori, aktif, is_hidden)
SELECT '1', 'Coupon/form_input_code', '2', 'Input Kode Voucher', '5', '1', '0'
FROM DUAL
WHERE NOT EXISTS (
    SELECT 1 FROM page_akses 
    WHERE link = 'Coupon/form_input_code' AND `group` = '2'
);
/* 2 - it*/