<?php // 文章列表、搜尋、分類、操作主頁
require_once "./db.php";
require_once "./utilities.php";
include "../vars.php";

// 取得所有分類，供下拉選單使用
$sql_cat = "SELECT * FROM categories ORDER BY id ASC";
$stmt_cat = $pdo->prepare($sql_cat);
$stmt_cat->execute();
$allCategories = $stmt_cat->fetchAll();

// 依據 GET 參數篩選分類
$filter_category_id = isset($_GET['category_id']) ? intval($_GET['category_id']) : 0;
$filter_sql = $filter_category_id ? " AND a.category_id = $filter_category_id" : "";

// 新增關鍵字搜尋功能
$search_keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : '';
$search_sql = '';
if ($search_keyword !== '') {
    $safe_keyword = str_replace(['%', '_'], ['\\%', '\\_'], $search_keyword); // 防止萬用字元注入
    $search_sql = " AND (a.title LIKE :kw OR a.content LIKE :kw)";
}

// 新增日期範圍篩選
$start_date = isset($_GET['start_date']) ? trim($_GET['start_date']) : '';
$end_date = isset($_GET['end_date']) ? trim($_GET['end_date']) : '';
$date_sql = '';
if ($start_date !== '') {
    $date_sql .= " AND a.updated_at >= :start_date";
}
if ($end_date !== '') {
    $date_sql .= " AND a.updated_at <= :end_date";
}

// 每頁10筆
$perPage = 10;
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$offset = ($page - 1) * $perPage;

// 查詢總筆數
$sql_count = "SELECT COUNT(*) FROM articles a WHERE a.is_deleted = 0 $filter_sql $search_sql $date_sql";
$stmt_count = $pdo->prepare($sql_count);
if ($search_keyword !== '') {
    $stmt_count->bindValue(':kw', "%$search_keyword%", PDO::PARAM_STR);
}
if ($start_date !== '') {
    $stmt_count->bindValue(':start_date', $start_date . ' 00:00:00', PDO::PARAM_STR);
}
if ($end_date !== '') {
    $stmt_count->bindValue(':end_date', $end_date . ' 23:59:59', PDO::PARAM_STR);
}
$stmt_count->execute();
$totalCount = $stmt_count->fetchColumn();
$totalPages = ceil($totalCount / $perPage);

// 最多只顯示10頁
$maxShowPages = 10;
$startPage = max(1, $page - floor($maxShowPages / 2));
$endPage = min($totalPages, $startPage + $maxShowPages - 1);
if ($endPage - $startPage + 1 < $maxShowPages) {
    $startPage = max(1, $endPage - $maxShowPages + 1);
}

// 查詢當前頁的文章資料
$sql = "SELECT a.*, c.name AS category_name FROM articles a JOIN categories c ON a.category_id = c.id WHERE a.is_deleted = 0 $filter_sql $search_sql $date_sql ORDER BY a.updated_at DESC, a.created_at DESC LIMIT :limit OFFSET :offset";
$stmt = $pdo->prepare($sql);
if ($search_keyword !== '') {
    $stmt->bindValue(':kw', "%$search_keyword%", PDO::PARAM_STR);
}
if ($start_date !== '') {
    $stmt->bindValue(':start_date', $start_date . ' 00:00:00', PDO::PARAM_STR);
}
if ($end_date !== '') {
    $stmt->bindValue(':end_date', $end_date . ' 23:59:59', PDO::PARAM_STR);
}
$stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$articles = $stmt->fetchAll();
$cateNum = 3;
$pageTitle = "文章列表";
?>
<!doctype html>
<html lang="zh-Hant">

<head>
    <meta charset="utf-8">
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
    <link rel="stylesheet" href="./css/articles.css">
    <style>
    </style>
</head>

<body>
    <div class="dashboard">
        <?php include '../template_sidebar.php'; ?>
        <div class="main-container overflow-auto">
            <?php include '../template_header.php'; ?>
            <main>
                <div class="container-fluid px-3 mt-3">
                    <div class="modern-card d-flex align-items-center flex-wrap gap-2">
                        <span class="fw-bold" style="color: #5A7EC5;">&gt;&gt; 目前共<?= $totalCount ?> 筆資料</span>
                        <form method="get" class="d-flex align-items-center gap-2 flex-wrap">
                            <select name="category_id" class="form-select form-select-sm" style="width:auto;display:inline-block;" onchange="this.form.submit()">
                                <option value="0">全部分類</option>
                                <?php foreach ($allCategories as $cat): ?>
                                    <option value="<?= $cat['id'] ?>" <?= $filter_category_id == $cat['id'] ? 'selected' : '' ?>><?= htmlspecialchars($cat['name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                            <div class="date-range-container">
                                <div class="d-flex align-items-center gap-2">
                                    <span class="fw-bold" style="color: #5A7EC5; white-space: nowrap;">更新時間：</span>
                                    <div class="d-flex align-items-center gap-2">
                                        <input type="date" name="start_date" class="form-control form-control-sm" value="<?= htmlspecialchars($start_date) ?>" placeholder="開始日期" onchange="this.form.submit()">
                                        <span class="text-muted">至</span>
                                        <input type="date" name="end_date" class="form-control form-control-sm" value="<?= htmlspecialchars($end_date) ?>" placeholder="結束日期" onchange="this.form.submit()">
                                    </div>
                                </div>
                            </div>
                            <input type="text" name="keyword" class="form-control form-control-sm" placeholder="搜尋標題或內容" value="<?= htmlspecialchars($search_keyword) ?>" style="width:180px;">
                            <button type="submit" class="btn btn-sm btn-info"><i class="fa fa-search"></i> 搜尋</button>
                        </form>
                        <a class="btn btn-sm btn-add ms-auto" href="add.php"><i class="fa-solid fa-plus"></i> 新增文章</a>
                        <a class="btn btn-sm btn-info ms-2" href="categories.php"><i class="fa-solid fa-list"></i> 文章分類</a>
                        <a class="btn btn-sm btn-secondary ms-2" href="delete_list.php"><i class="fa-solid fa-trash"></i> 我已刪除/下架的文章</a>
                    </div>
                    <div class="table-responsive modern-table">
                        <table class="table table-hover align-middle bg-white mt-3 mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">標題</th>
                                    <th scope="col">分類</th>
                                    <th scope="col">封面照片</th>
                                    <th scope="col">建立時間</th>
                                    <th scope="col">更新時間</th>
                                    <th scope="col">上架狀態</th>
                                    <th scope="col">操作</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($articles as $idx => $article): ?>
                                <tr>
                                    <td><?= $offset + $idx + 1 ?></td>
                                    <td><a href="article.php?id=<?= $article['id'] ?>" class="text-decoration-none fw-semibold text-dark"><i class="fa-regular fa-file-alt me-1" style="color: var(--color-primary);"></i><?= htmlspecialchars($article['title']) ?></a></td>
                                    <td><?= htmlspecialchars($article['category_name']) ?></td>
                                    <td>
                                      <?php if ($article['cover_image']): ?>
                                        <div class="cover-image-container">
                                          <img src="./uploads/<?= htmlspecialchars($article['cover_image']) ?>" alt="封面" style="max-width:120px;max-height:80px;border-radius:4px;object-fit:cover;box-shadow:0 2px 4px rgba(0,0,0,0.1);">
                                        </div>
                                      <?php else: ?>
                                        <span class="text-muted">無</span>
                                      <?php endif; ?>
                                    </td>
                                    <td><?= $article['created_at'] ?></td>
                                    <td><?= $article['updated_at'] ? $article['updated_at'] : '-' ?></td>
                                    <td>
                                      <?php if (isset($article['is_deleted']) && $article['is_deleted'] == 0): ?>
                                        <span class="badge bg-success">已上架</span>
                                      <?php else: ?>
                                        <span class="badge bg-secondary">未上架</span>
                                      <?php endif; ?>
                                    </td>
                                    <td>
                                        <a href="edit.php?id=<?= $article['id'] ?>" class="btn btn-sm btn-update me-1"><i class="fa-solid fa-pen-to-square"></i> 編輯</a>
                                        <a href="delete.php?id=<?= $article['id'] ?>" class="btn btn-sm btn-del" onclick="return confirm('確定要將這篇文章移至回收桶嗎？');"><i class="fa-solid fa-trash"></i> 刪除</a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <!-- 分頁按鈕 -->
                    <?php if ($totalPages > 1): ?>
                    <nav aria-label="Page navigation" class="mt-4">
                      <ul class="pagination justify-content-center">
                        <li class="page-item<?= $page <= 1 ? ' disabled' : '' ?>">
                          <a class="page-link" href="?<?= http_build_query(array_merge($_GET, ['page' => $page - 1])) ?>">上一頁</a>
                        </li>
                        <?php for ($i = $startPage; $i <= $endPage; $i++): ?>
                          <li class="page-item<?= $i == $page ? ' active' : '' ?>">
                            <a class="page-link" href="?<?= http_build_query(array_merge($_GET, ['page' => $i])) ?>"><?= $i ?></a>
                          </li>
                        <?php endfor; ?>
                        <li class="page-item<?= $page >= $totalPages ? ' disabled' : '' ?>">
                          <a class="page-link" href="?<?= http_build_query(array_merge($_GET, ['page' => $page + 1])) ?>">下一頁</a>
                        </li>
                      </ul>
                    </nav>
                    <?php endif; ?>
                </div>
            </main>
        </div>
    </div>
</body>

</html>