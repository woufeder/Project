<?php
require_once "./db.php";
require_once "./utilities.php";
require_once "../vars.php";

// 檢查是否有傳入文章 id，否則導回
if (!isset($_GET['id'])) {
    alertAndBack("請循正常管道進入本頁");
    exit;
}
$id = intval($_GET['id']);

// 取得該文章資料，供表單預設值使用
$sql = "SELECT * FROM articles WHERE id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$id]);
$article = $stmt->fetch();
if (!$article) {
    alertGoTo("找不到該文章", "index.php");
    exit;
}
// 取得所有分類，供下拉選單使用
$sql = "SELECT * FROM categories";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$categories = $stmt->fetchAll();

$pageTitle = "編輯文章";
$cateNum = 3;
?>

<!doctype html>
<html lang="zh-Hant">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $pageTitle ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+TC:wght@100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/main.css">
    <link rel="stylesheet" href="../css/form.css">
    <link rel="stylesheet" href="./css/editor.css">
    <link rel="stylesheet" href="./css/index.css">
    <link rel="stylesheet" href="./css/article_modern.css">
    <link rel="stylesheet" href="./css/articles.css">
</head>

<body>
    <div class="dashboard">
        <?php include '../template_sidebar.php'; ?>
        <div class="main-container overflow-auto">
            <?php include '../template_header.php'; ?>
            <main>
                <div class="container-fluid px-3 mt-3">
                    <div class="modern-card">
                        <form action="doEdit.php" method="post" enctype="multipart/form-data" id="editForm">
                            <input type="hidden" name="id" value="<?= $article['id'] ?>">
                            <div class="mb-3">
                                <label for="title" class="form-label">標題</label>
                                <input type="text" class="form-control" id="title" name="title"
                                    value="<?= htmlspecialchars($article['title']) ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="category_id" class="form-label">分類</label>
                                <select class="form-select" id="category_id" name="category_id" required>
                                    <option value="" disabled>請選擇分類</option>
                                    <?php foreach ($categories as $cat): ?>
                                        <option value="<?= $cat['id'] ?>" <?= $cat['id'] == $article['category_id'] ? 'selected' : '' ?>><?= htmlspecialchars($cat['name']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="cover_image" class="form-label">封面圖片</label>
                                <?php if ($article['cover_image']): ?>
                                    <img src="./uploads/<?= htmlspecialchars($article['cover_image']) ?>" alt="封面"
                                        class="img-preview mb-2">
                                <?php endif; ?>
                                <input type="file" class="form-control" id="cover_image" name="cover_image">
                                <div class="form-text">如不更換可留空</div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">內容</label>
                                <div class="editor-toolbar">
                                    <select id="fontSizeSelect" onchange="setFontSize(this.value)" style="margin-right:4px;">
                                        <option value="">字體大小</option>
                                        <option value="1">小</option>
                                        <option value="3">標準</option>
                                        <option value="5">大</option>
                                        <option value="7">特大</option>
                                    </select>
                                    <button type="button" onclick="formatText('bold')"><i class="fas fa-bold"></i></button>
                                    <button type="button" onclick="formatText('italic')"><i class="fas fa-italic"></i></button>
                                    <button type="button" onclick="formatText('underline')"><i class="fas fa-underline"></i></button>
                                    <button type="button" onclick="formatText('strikethrough')"><i class="fas fa-strikethrough"></i></button>
                                    <button type="button" onclick="insertList('ul')"><i class="fas fa-list-ul"></i></button>
                                    <button type="button" onclick="insertList('ol')"><i class="fas fa-list-ol"></i></button>
                                    <button type="button" onclick="insertLink()"><i class="fas fa-link"></i></button>
                                    <button type="button" onclick="insertImage()"><i class="fas fa-image"></i></button>
                                </div>
                                <div id="editor" class="editor-content" contenteditable="true"><?= $article['content'] ?></div>
                                <input type="hidden" name="content" id="content">
                            </div>
                            <div class="mt-4 text-end d-flex gap-2 justify-content-end flex-wrap">
                                <button type="submit" class="btn btn-send" onclick="prepareContent()"><i class="fa-solid fa-save"></i> 儲存</button>
                                <a href="index.php" class="btn btn-cancel"><i class="fa-solid fa-xmark"></i> 取消</a>
                            </div>
                        </form>
                    </div>
                </div>
            </main>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function formatText(command) {
            document.execCommand(command, false, null);
            document.getElementById('editor').focus();
        }
        function setFontSize(size) {
            if(size) {
                document.execCommand('fontSize', false, size);
                document.getElementById('editor').focus();
            }
        }
        function insertList(type) {
            document.execCommand('insert' + type, false, null);
            document.getElementById('editor').focus();
        }
        function insertLink() {
            const url = prompt('請輸入連結網址：');
            if (url) {
                document.execCommand('createLink', false, url);
            }
            document.getElementById('editor').focus();
        }
        function insertImage() {
            const url = prompt('請輸入圖片網址：');
            if (url) {
                document.execCommand('insertImage', false, url);
            }
            document.getElementById('editor').focus();
        }
        function prepareContent() {
            document.getElementById('content').value = document.getElementById('editor').innerHTML;
        }
    </script>
</body>

</html>