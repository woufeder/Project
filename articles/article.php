<?php // 單篇文章詳細內容頁
require_once "./db.php";
require_once "../vars.php";
require_once "./utilities.php";
$pageTitle = "文章內容";
$cateNum = 3;

// 檢查是否有傳入文章 id，否則顯示錯誤
if (!isset($_GET['id'])) {
    alertGoTo("找不到文章", "index.php");
    exit;
}
$id = intval($_GET['id']);
// 取得該文章資料及分類名稱，僅顯示未刪除的文章
$sql = "SELECT a.*, c.name AS category_name FROM articles a JOIN categories c ON a.category_id = c.id WHERE a.id = ? AND a.is_deleted = 0";
$stmt = $pdo->prepare($sql);
$stmt->execute([$id]);
$article = $stmt->fetch();
if (!$article) {
    alertGoTo("找不到文章", "index.php");
    exit;
}
?>
<!doctype html>
<html lang="zh-Hant">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $pageTitle ?> - 文章內容</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../css/form.css">
    <link rel="stylesheet" href="../css/main.css">
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
                <div class="row">
                    <?php if ($article['cover_image']): ?>
                    <div class="col-md-4">
                        <div class="modern-card p-4 mb-3">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <h5 class="mb-0" style="color: #5A7EC5;">文章封面</h5>
                            </div>
                            <div class="cover-image-container">
                                <img src="./uploads/<?= htmlspecialchars($article['cover_image']) ?>" alt="封面" class="img-fluid">
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <div class="<?= $article['cover_image'] ? 'col-md-8' : 'col-12' ?>">
                        <div class="modern-card p-4">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <h2 class="mb-0"><?= htmlspecialchars($article['title']) ?></h2>
                            </div>
                            <div class="mb-4 text-muted">
                                <i class="bi bi-folder me-2"></i><?= htmlspecialchars($article['category_name']) ?>
                                <span class="mx-3">|</span>
                                <i class="bi bi-clock me-2"></i><?= $article['created_at'] ?>
                            </div>
                            <div class="article-content">
                                <?= nl2br($article['content']) ?>
                            </div>
                            <div class="mt-4">
                                <a href="index.php" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> 返回列表</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>
</body>
</html> 