# Articles 專案 CSS 檔案說明

本專案包含以下 CSS 檔案，每個檔案負責不同的樣式功能：

## 1. articles.css
主要的共用樣式檔案，包含：
- 基本頁面樣式（背景、字體等）
- 卡片元件樣式
- 表單元素樣式
- 按鈕樣式
- 編輯器相關樣式
- 響應式設計調整

影響範圍：
- 所有文章相關頁面
- 編輯器介面
- 表單元素
- 按鈕樣式

套用於以下 PHP 檔案：
- articles/index.php
- articles/article.php
- articles/edit.php
- articles/add.php
- articles/categories.php

## 2. article_modern.css
文章顯示相關的現代化樣式，包含：
- 文章內容排版
- 文章標題樣式
- 文章封面圖片樣式
- 文章資訊（分類、時間等）樣式

影響範圍：
- 文章列表頁面
- 單篇文章顯示頁面
- 文章編輯頁面

套用於以下 PHP 檔案：
- articles/index.php
- articles/article.php
- articles/edit.php
- articles/add.php

## 3. index.css
文章列表頁面的專用樣式，包含：
- 文章列表表格樣式
- 搜尋區塊樣式
- 分頁按鈕樣式
- 操作按鈕樣式

影響範圍：
- 文章列表頁面（index.php）
- 搜尋和篩選功能
- 分頁導航

套用於以下 PHP 檔案：
- articles/index.php
- articles/delete_list.php

## 4. editor.css
文章編輯器的專用樣式，包含：
- 編輯器工具列樣式
- 編輯區域樣式
- 編輯器按鈕樣式
- 編輯器功能相關樣式

影響範圍：
- 文章編輯頁面
- 文章新增頁面
- 編輯器工具列

套用於以下 PHP 檔案：
- articles/edit.php
- articles/add.php

## 5. categories_custom.css
文章分類相關的專用樣式，包含：
- 分類列表樣式
- 分類表單樣式
- 分類操作按鈕樣式

影響範圍：
- 分類管理頁面
- 分類新增/編輯表單
- 分類列表顯示

套用於以下 PHP 檔案：
- articles/categories.php

## 注意事項
1. 所有 CSS 檔案都遵循模組化設計原則
2. 修改樣式時請注意不要影響到其他頁面
3. 建議在修改前先確認樣式的影響範圍
4. 如需新增樣式，請優先考慮是否可加入現有檔案中
5. 如果新增的樣式較為獨立，可考慮創建新的 CSS 檔案

## 檔案相依性
- 所有頁面都會載入 articles.css（基礎樣式）
- 文章相關頁面會額外載入 article_modern.css
- 列表頁面會額外載入 index.css
- 編輯相關頁面會額外載入 editor.css
- 分類頁面會額外載入 categories_custom.css 