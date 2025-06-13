<?php // 回收桶（已刪除文章列表/還原）頁
require_once "./tools/db.php";
require_once "./tools/utilities.php";
require_once "./tools/vars.php";
$pdo = getPDO();

// 取得所有已刪除文章及分類，顯示於回收桶列表
$sql = "SELECT a.*, c.name AS category_name FROM articles a JOIN categories c ON a.category_id = c.id WHERE a.is_deleted = 1 ORDER BY a.created_at DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$articles = $stmt->fetchAll();

$cateNum = 3;
$pageTitle = "已下架文章";
$totalCount = count($articles);
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
</head>

<body>
  <div class="dashboard">
    <?php include './tools/template_sidebar.php'; ?>
    <div class="main-container overflow-auto">
      <?php include './tools/template_header.php'; ?>
      <main>
        <div class="container-fluid px-3 mt-3">
          <div class="modern-card d-flex align-items-center flex-wrap gap-2">
            <span class="fw-bold" style="color: #5A7EC5;">&gt;&gt; 目前共<?= $totalCount ?> 筆資料</span>
            <a class="btn btn-sm btn-secondary ms-auto" href="index.php"><i class="fa-solid fa-arrow-left"></i> 返回文章管理</a>
          </div>
          <div class="table-responsive modern-table">
            <table class="table table-hover align-middle bg-white mt-3 mb-0">
              <thead class="table-light">
                <tr>
                  <th scope="col">#</th>
                  <th scope="col">標題</th>
                  <th scope="col">分類</th>
                  <th scope="col">建立時間</th>
                  <th scope="col">更新時間</th>
                  <th scope="col">操作</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($articles as $idx => $article): ?>
                <tr>
                  <td><?= $idx + 1 ?></td>
                  <td><span class="fw-semibold text-dark"><i class="fa-regular fa-file-lines me-1 text-primary"></i><?= htmlspecialchars($article['title']) ?></span></td>
                  <td><?= htmlspecialchars($article['category_name']) ?></td>
                  <td><?= $article['created_at'] ?></td>
                  <td><?= $article['updated_at'] ? $article['updated_at'] : '-' ?></td>
                  <td>
                    <a href="undo_delete.php?id=<?= $article['id'] ?>" class="btn btn-sm btn-update me-1" onclick="return confirm('確定要還原這篇文章嗎？');"><i class="fa-solid fa-rotate-left"></i> 還原</a>
                    <a href="permanent_delete.php?id=<?= $article['id'] ?>" class="btn btn-sm btn-del" onclick="return confirm('警告：此操作將永久刪除文章，且無法復原！確定要永久刪除嗎？');"><i class="fa-solid fa-trash"></i> 永久刪除</a>
                  </td>
                </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        </div>
      </main>
    </div>
  </div>
</body>

</html>