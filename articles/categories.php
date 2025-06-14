<?php // 文章分類管理頁
require_once "./db.php";
require_once "../vars.php"; 

// 新增分類
if (isset($_POST['add_name'])) {
    $name = trim($_POST['add_name']);
    if ($name !== '') {
        $stmt = $pdo->prepare("INSERT INTO categories (name) VALUES (?)");
        $stmt->execute([$name]);
        header("Location: categories.php");
        exit;
    }
}
// 編輯分類
if (isset($_POST['edit_id'], $_POST['edit_name'])) {
    $id = intval($_POST['edit_id']);
    $name = trim($_POST['edit_name']);
    if ($name !== '') {
        $stmt = $pdo->prepare("UPDATE categories SET name=? WHERE id=?");
        $stmt->execute([$name, $id]);
        header("Location: categories.php");
        exit;
    }
}
// 刪除分類
if (isset($_POST['delete_id'])) {
    $id = intval($_POST['delete_id']);
    $stmt = $pdo->prepare("DELETE FROM categories WHERE id=?");
    $stmt->execute([$id]);
    header("Location: categories.php");
    exit;
}
// 取得所有分類，顯示於列表
$stmt = $pdo->query("SELECT * FROM categories ORDER BY id ASC");
$categories = $stmt->fetchAll();
$pageTitle = "文章分類管理";
$cateNum = 3;
$totalCount = count($categories);
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
    <link rel="stylesheet" href="./css/index.css">
    <link rel="stylesheet" href="./css/article_modern.css">
    <link rel="stylesheet" href="./css/categories_custom.css">
</head>
<body>
<div class="dashboard">
  <?php include '../template_sidebar.php'; ?>
  <div class="main-container overflow-auto">
    <?php include '../template_header.php'; ?>
    <main>
      <div class="container-fluid px-3 mt-3">
        <div class="modern-card">
          <div class="d-flex align-items-center flex-wrap gap-2 mb-3">
            <span class="fw-bold" style="color: #5A7EC5;">&gt;&gt; 目前共<?= $totalCount ?> 筆分類</span>
            <a class="btn btn-sm btn-secondary ms-auto" href="index.php"><i class="fa-solid fa-arrow-left"></i> 返回文章管理</a>
          </div>
          <div class="table-responsive modern-table">
            <table class="table table-hover align-middle bg-white mb-0">
              <thead class="table-light">
                <tr>
                  <th style="width:60px;">#</th>
                  <th>分類名稱</th>
                  <th style="width:180px;">操作</th>
                </tr>
              </thead>
              <tbody>
                <!-- 新增分類 -->
                <tr>
                  <form method="post">
                    <td></td>
                    <td style="width:240px;">
                      <input type="text" class="form-control" name="add_name" placeholder="新增分類名稱" required style="max-width:220px;display:inline-block;">
                    </td>
                    <td>
                      <button class="btn btn-sm btn-add" type="submit" style="min-width:80px;"><i class="fa-solid fa-plus"></i> 新增</button>
                    </td>
                  </form>
                </tr>
                <!-- 分類列表 -->
                <?php foreach ($categories as $idx => $cat): ?>
                <tr>
                  <td><?= $idx + 1 ?></td>
                  <td>
                    <form method="post" class="d-inline">
                      <input type="hidden" name="edit_id" value="<?= $cat['id'] ?>">
                      <input type="text" class="form-control" name="edit_name" value="<?= htmlspecialchars($cat['name']) ?>" required style="max-width:220px;display:inline-block;">
                      <button class="btn btn-sm btn-update me-1" type="submit"><i class="fa-solid fa-pen"></i> 編輯</button>
                    </form>
                    <form method="post" class="d-inline">
                      <button class="btn btn-sm btn-del" type="submit" name="delete_id" value="<?= $cat['id'] ?>" onclick="return confirm('確定要刪除這個分類嗎？');"><i class="fa-solid fa-trash"></i> 刪除</button>
                    </form>
                  </td>
                </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </main>
  </div>
</div>
</body>
</html> 