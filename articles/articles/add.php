<?php
require_once "./tools/db.php";
require_once "./tools/utilities.php";
require_once "./tools/vars.php";
$pdo = getPDO();    

// 取得所有分類，供下拉選單使用
$sql = "SELECT * FROM categories";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$categories = $stmt->fetchAll();

$pageTitle = "新增文章";
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
    <link rel="stylesheet" href="./css/main.css">
    <link rel="stylesheet" href="./css/form.css">
    <link rel="stylesheet" href="./css/index.css">
    <link rel="stylesheet" href="./css/article_modern.css">
    <style>
        .editor-toolbar {
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            border-bottom: none;
            border-radius: 4px 4px 0 0;
            padding: 8px;
        }
        .editor-toolbar button {
            background: white;
            border: 1px solid #dee2e6;
            border-radius: 4px;
            padding: 4px 8px;
            margin-right: 4px;
            cursor: pointer;
        }
        .editor-toolbar button:hover {
            background: #e9ecef;
        }
        .editor-content {
            border: 1px solid #dee2e6;
            border-radius: 0 0 4px 4px;
            min-height: 300px;
            padding: 12px;
            background: white;
        }
        .editor-content:focus {
            outline: none;
            border-color: #86b7fe;
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
        }
    </style>
</head>

<body>
    <div class="dashboard">
        <?php include './tools/template_sidebar.php'; ?>
        <div class="main-container overflow-auto">
            <?php include './tools/template_header.php'; ?>
            <main>
                <div class="container-fluid px-3 mt-3">
                    <div class="modern-card">
                        <form action="doAdd.php" method="post" enctype="multipart/form-data" id="addForm">
                            <div class="mb-3">
                                <label for="title" class="form-label">標題</label>
                                <input type="text" class="form-control" id="title" name="title" required>
                            </div>
                            <div class="mb-3">
                                <label for="category_id" class="form-label">分類</label>
                                <select class="form-select" id="category_id" name="category_id" required>
                                    <option value="" disabled selected>請選擇分類</option>
                                    <?php foreach ($categories as $cat): ?>
                                        <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="cover_image" class="form-label">封面圖片</label>
                                <input type="file" class="form-control" id="cover_image" name="cover_image">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">內容</label>
                                <div class="editor-toolbar">
                                    <button type="button" onclick="formatText('bold')"><i class="fas fa-bold"></i></button>
                                    <button type="button" onclick="formatText('italic')"><i class="fas fa-italic"></i></button>
                                    <button type="button" onclick="formatText('underline')"><i class="fas fa-underline"></i></button>
                                    <button type="button" onclick="formatText('strikethrough')"><i class="fas fa-strikethrough"></i></button>
                                    <button type="button" onclick="insertList('ul')"><i class="fas fa-list-ul"></i></button>
                                    <button type="button" onclick="insertList('ol')"><i class="fas fa-list-ol"></i></button>
                                    <button type="button" onclick="insertLink()"><i class="fas fa-link"></i></button>
                                    <button type="button" onclick="insertImage()"><i class="fas fa-image"></i></button>
                                </div>
                                <div id="editor" class="editor-content" contenteditable="true"></div>
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