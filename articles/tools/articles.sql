-- 建立分類表
CREATE TABLE categories (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL
) 

-- 建立文章表
CREATE TABLE articles (
  id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(255) NOT NULL,
  content TEXT,
  cover_image VARCHAR(255),
  category_id INT NOT NULL,
  views INT DEFAULT 0,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT NULL,
  FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE RESTRICT
) 

-- 建立文章圖片表
CREATE TABLE article_images (
  id INT AUTO_INCREMENT PRIMARY KEY,
  article_id INT NOT NULL,
  image_url VARCHAR(255),
  description VARCHAR(255),
  FOREIGN KEY (article_id) REFERENCES articles(id) ON DELETE CASCADE
) 

describe articles;
describe categories;
describe article_images;    

-- 插入文章分類資料
INSERT INTO categories (name) VALUES
  ('組裝與改造類'),
  ('鍵帽與外觀類'),
  ('軸體與手感類'),
  ('配件與升級類'),
  ('使用與應用類'),
  ('評測與開箱類'),
  ('潮流與專題類');

ALTER TABLE articles ADD COLUMN is_deleted TINYINT(1) NOT NULL DEFAULT 0;

describe articles;

