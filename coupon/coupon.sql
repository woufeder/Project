use my_project;

CREATE table coupon (
    id int PRIMARY KEY AUTO_INCREMENT,
    -- img_id INT,
    code VARCHAR(10),
    `desc` VARCHAR(100),
    type TINYINT,
    value INT,
    min INT,
    start_at DATETIME,
    expires_at DATETIME,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    -- FOREIGN KEY (img_id) REFERENCES images (id)
    -- ON DELETE RESTRICT
);

DROP TABLE `coupon`;

-- SET FOREIGN_KEY_CHECKS = 1;

-- CREATE TABLE images (
--     id INT PRIMARY KEY AUTO_INCREMENT,
--     file_path VARCHAR(255) NOT NULL
-- );

SELECT * FROM coupon;

ALTER TABLE coupon
ADD COLUMN is_valid TINYINT NOT NULL DEFAULT 1,
ADD COLUMN is_active TINYINT NOT NULL DEFAULT 1;