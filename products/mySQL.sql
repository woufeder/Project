use my_project

CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category_main_id INT NOT NULL,
    category_sub_id INT NOT NULL,
    brand_id INT,
    name VARCHAR(100),
    modal VARCHAR(100),
    price INT NOT NULL DEFAULT 0,
    intro TEXT,
    spec TEXT,
    is_valid TINYINT NOT NULL DEFAULT 1,
    FOREIGN KEY (category_main_id) REFERENCES category_main (id),
    FOREIGN KEY (category_sub_id) REFERENCES category_sub (id),
    FOREIGN KEY (brand_id) REFERENCES brands (id)
);

CREATE TABLE category_main (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL
);

CREATE TABLE category_sub (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    main_id INT NOT NULL,
    FOREIGN KEY (main_id) REFERENCES category_main (id)
);
CREATE TABLE brands (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL
);

CREATE TABLE products_imgs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    file VARCHAR(255),
    product_id int,
    FOREIGN KEY (product_id) REFERENCES products (id)
);

CREATE TABLE products_intro_imgs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    file VARCHAR(255),
    product_id int,
    FOREIGN KEY (product_id) REFERENCES products (id)
);

INSERT INTO brands (name) VALUES
('A4tech 雙飛燕'),
('AIRPULSE'),
('AOC'),
('Arbiter Studio'),
('ASUS 華碩'),
('Avermedia圓剛'),
('AZIO'),
('BenQ'),
('CHERRY櫻桃'),
('CoolerMaster 酷碼'),
('CORSAIR海盜船'),
('Cougar'),
('Cryorig 快睿'),
('DIGIFAST迅華'),
('Dream Gamer逐夢者'),
('Ducky'),
('Edifier漫步者'),
('EIZO'),
('Elgato'),
('Endgame Gear'),
('EPOS'),
('Ergotron'),
('FBB'),
('FILCO'),
('Final'),
('Fractal Design'),
('Gateron佳達隆'),
('Gigabyte 技嘉'),
('Glorious'),
('GravaStar'),
('Helix Lab'),
('HyperX'),
('I-ROCKS 艾芮克'),
('JBL'),
('JONSBO 喬思伯'),
('Kailh凱華'),
('KBParadise'),
('Kelowna'),
('Keychron'),
('Keytok'),
('LianLi 聯力'),
('LINDY 林帝'),
('Mostly默思利'),
('Moyu.studio'),
('MSI GAMING 微星'),
('Noctua 貓頭鷹'),
('NZXT'),
('OTHER其他'),
('Padsmith'),
('Pulsar'),
('Razer 雷蛇'),
('ROYAL KLUDGE'),
('SAMSUNG 三星'),
('Sennheiser森海塞爾'),
('SHURE'),
('SilverStone 銀欣'),
('StarDust 星塵'),
('SteelSeries 賽睿'),
('SuperLux 舒伯樂'),
('Thermalright 利民'),
('Thermaltake 曜越'),
('Traitors背骨玩家'),
('TTC'),
('Vortex'),
('ZOWIE'),
('火炎森美');

INSERT INTO category_main (name) VALUES
('鍵盤｜鍵帽｜鍵盤周邊'),
('滑鼠｜鼠墊｜滑鼠周邊'),
('耳機｜喇叭｜音訊設備'),
('機殼｜電源｜散熱設備'),
('螢幕｜視訊｜相關設備');


INSERT INTO category_sub (name, main_id) VALUES
('機械式鍵盤', 1),
('薄膜式鍵盤', 1),
('類機械式鍵盤', 1),
('鍵盤手托｜手靠墊', 1),
('鍵帽', 1),
('軸體', 1),
('潤軸｜手工具', 1),
('組裝週邊│線材', 1),
('電競滑鼠', 2),
('文書滑鼠', 2),
('滑鼠墊', 2),
('耳機｜耳機麥克風', 3),
('喇叭｜音響', 3),
('麥克風', 3),
('音效卡｜擴大機', 3),
('傳輸線｜音源線│轉接頭', 3),
('機殼｜裸測架', 4),
('風扇｜散熱', 4),
('電源供應器', 4),
('螢幕顯示器', 5),
('螢幕架', 5),
('視訊設備│擷取盒', 5),
('影音線材｜轉接延長器', 5);



INSERT INTO products (category_main_id, category_sub_id,brand_id,name,modal,price,intro,spec) VALUES
(1,1,1,"這是一個測試用的商品名","want-to-sleep",8964,"做不完","老師不要罵我");



SELECT * FROM `products` WHERE `is_valid`=1

SELECT * FROM category_sub;

SELECT * FROM products

ALTER TABLE products ADD is_valid TINYINT NOT NULL DEFAULT 1 AFTER `spec`;

UPDATE products
SET is_valid=1
WHERE id BETWEEN 1 AND 396;

ALTER TABLE products
MODIFY COLUMN is_valid TINYINT NOT NULL DEFAULT 1;
