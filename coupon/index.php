<?php
include "../template_btn.php";
include "../vars.php";
require_once "./connect.php";
require_once "./coupon-b.php";
$cateNum = 2;
$pageTitle = "{$cate_ary[$cateNum]}列表";

// 查詢參數
$search = $_GET["search"] ?? "";
$searchType = $_GET["qType"] ?? "";
$typeFilter = $_GET["typeFilter"] ?? ""; // 類型篩選
$date1 = $_GET["date1"] ?? "";
$date2 = $_GET["date2"] ?? "";
$activeFilter = $_GET["activeFilter"] ?? "";

// 加入排序處理
$orderBy = $_GET["orderBy"] ?? "id";
$orderDir = strtoupper($_GET["orderDir"] ?? "DESC");
$allowedOrderFields = ["id", "type", "value", "min", "start_at", "expires_at"];
if (!in_array($orderBy, $allowedOrderFields))
    $orderBy = "id";
$orderDir = $orderDir === "ASC" ? "ASC" : "DESC";

// 組 WHERE 條件
$where = ["coupon.is_valid = 1"];
$values = [];

// 關鍵字搜尋（只限 code, desc, value, min）
$allowedSearchTypes = ["code", "desc", "value", "min"];
if ($search !== "" && in_array($searchType, $allowedSearchTypes)) {
    if (in_array($searchType, ["value", "min"]) && is_numeric($search)) {
        $where[] = "`$searchType` = :searchExact";
        $values["searchExact"] = $search;
    } else {
        $where[] = "`$searchType` LIKE :search";
        $values["search"] = "%$search%";
    }
}

// 類型篩選（1=百分比，2=固定金額）
if ($typeFilter !== "") {
    $where[] = "coupon.type = :typeFilter";
    $values["typeFilter"] = $typeFilter;
}

// 活動期間篩選
if ($date1 && $date2) {
    $where[] = "(start_at <= :endDate AND expires_at >= :startDate)";
    $values["startDate"] = $date1 . " 00:00:00";
    $values["endDate"] = $date2 . " 23:59:59";
}

// 啟用狀態篩選
if ($activeFilter !== "") {
    $where[] = "coupon.is_active = :activeFilter";
    $values["activeFilter"] = $activeFilter;
}

$whereSQL = implode(" AND ", $where);

// 切換啟用
if (isset($_GET["toggle_id"])) {
    $toggleId = $_GET["toggle_id"];
    $pdo->prepare("UPDATE coupon SET is_active = NOT is_active WHERE id = ?")
        ->execute([$toggleId]);
    header("Location: " . strtok($_SERVER["REQUEST_URI"], '?'));
    exit;
}

// 分頁
$perPage = 6;
$page = intval($_GET["page"] ?? 1);
$pageStart = ($page - 1) * $perPage;


// 動態設定排序方向文字
$ascLabel = "從小到大";
$descLabel = "從大到小";

switch ($orderBy) {
    case "start_at":
    case "expires_at":
        $ascLabel = "由晚到早";
        $descLabel = "由早到晚";
        break;
    case "value":
    case "min":
        $ascLabel = "最低優先";
        $descLabel = "最高優先";
        break;
    case "type":
        $ascLabel = "固定金額優先";
        $descLabel = "百分比優先";
        break;
}

// 撈資料
$sql = "SELECT * FROM coupon
        WHERE $whereSQL
        ORDER BY $orderBy $orderDir
        LIMIT $perPage OFFSET $pageStart";

$sqlAll = "SELECT id FROM coupon
           WHERE $whereSQL";


try {
    $stmt = $pdo->prepare($sql);
    $stmt->execute($values);
    $rows = $stmt->fetchAll();

    $stmtAll = $pdo->prepare($sqlAll);
    $stmtAll->execute($values);
    $totalCount = $stmtAll->rowCount();
} catch (PDOException $e) {
    echo "錯誤: " . $e->getMessage();
    exit;
}

$totalPage = ceil($totalCount / $perPage);

?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PC 周邊商品後台管理系統|<?= $pageTitle ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
        integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+TC:wght@100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/main.css">
    <style>
        /* （CSS 保持不變） */
        body {
            background-color: #DBE2EF;
        }

        .coupon-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 16px;
            padding: 20px;
            overflow-y: auto;
        }

        .btn-del {
            background: #fff;
            color: #3F72AF;
        }

        .btn-cha,
        .btn-del:hover {
            background: #3F72AF;
            color: #fff;
        }

        .pagination .page-item.active .page-link {
            background: hsl(213, 100.00%, 89.20%);
            border-color: #3F72AF;
        }

        .pagination .page-link {
            color: #3F72AF;
        }

        .coupon-card {
            position: relative;
            color: #fff;
        }

        .coupon-card::before {
            content: "";
            position: absolute;
            inset: 0;
            background: rgba(0, 0, 0, 0.4);
        }

        .coupon-card>div {
            position: relative;
            z-index: 1;
        }

        .btn-add {
            background: #3F72AF;
            color: #fff;
        }

        .btn-toggle-on {
            background: #ffd66e;
            color: #000;
        }

        .btn-toggle-off {
            background: #6c757d;
            color: #fff;
            border: 1px solid #545b62;
        }
    </style>
</head>

<body>
    <div class="dashboard">
        <?php include(__DIR__ . "/../template_sidebar.php"); ?>
        <div class="main-container">
            <?php include(__DIR__ . "/../template_header.php");?>
            <main>
                <div class="container my-4">
                    <h1 class="mb-4 coupon-list text-align:center">優惠券列表</h1>
                    <div class="my-2 d-flex">
                        <span class="me-auto">目前共 <?= $totalCount ?> 筆資料</span>
                        <a class="btn btn-add btn-sm" href="./add.php">新增優惠券</a>
                    </div>
                    <div class="d-flex justify-content-end mb-3">
                        <a href="indexList.php" class="btn btn-outline-secondary btn-sm">切換列表模式</a>
                    </div>

                    <!-- 搜尋表單 -->
                    <form class="row g-2 mb-3 justify-content-end">
                        <div class="col-auto">
                            <input type="text" name="search" value="<?= htmlspecialchars($search) ?>"
                                class="form-control form-control-sm" placeholder="搜尋關鍵字">
                        </div>
                        <div class="col-auto">
                            <select name="qType" class="form-select form-select-sm">
                                <option value="">所有欄位</option>
                                <option value="desc" <?= $searchType === "desc" ? "selected" : "" ?>>優惠券名字</option>
                                <option value="code" <?= $searchType === "code" ? "selected" : "" ?>>優惠碼</option>
                                <option value="value" <?= $searchType === "value" ? "selected" : "" ?>>折扣</option>
                                <option value="min" <?= $searchType === "min" ? "selected" : "" ?>>最低消費</option>
                            </select>
                        </div>
                        <div class="col-auto">
                            <select name="typeFilter" class="form-select form-select-sm">
                                <option value="">所有類型</option>
                                <option value="1" <?= $typeFilter === "1" ? "selected" : "" ?>>百分比</option>
                                <option value="0" <?= $typeFilter === "0" ? "selected" : "" ?>>固定金額</option>
                            </select>
                        </div>
                        <div class="col-auto">
                            <select name="activeFilter" class="form-select form-select-sm">
                                <option value="">全部狀態</option>
                                <option value="1" <?= $activeFilter === "1" ? "selected" : "" ?>>啟用中</option>
                                <option value="0" <?= $activeFilter === "0" ? "selected" : "" ?>>未啟用</option>
                            </select>
                        </div>
                        <div class="col-auto">
                            <input type="date" name="date1" id="date1" value="<?= $date1 ?>"
                                class="form-control form-control-sm">
                        </div>
                        <div class="col-auto">
                            <input type="date" name="date2" id="date2" value="<?= $date2 ?>"
                                class="form-control form-control-sm">
                        </div>
                        <div class="col-auto">
                            <button class="btn btn-sm btn-primary">搜尋</button>
                        </div>
                        <div class="col-auto">
                            <select name="orderBy" class="form-select form-select-sm" onchange="this.form.submit()">
                                <option value="id" <?= $orderBy === "id" ? "selected" : "" ?>>預設排序</option>
                                <option value="type" <?= $orderBy === "type" ? "selected" : "" ?>>類型</option>
                                <option value="value" <?= $orderBy === "value" ? "selected" : "" ?>>折扣</option>
                                <option value="min" <?= $orderBy === "min" ? "selected" : "" ?>>最低消費</option>
                                <option value="start_at" <?= $orderBy === "start_at" ? "selected" : "" ?>>起始時間</option>
                                <option value="expires_at" <?= $orderBy === "expires_at" ? "selected" : "" ?>>到期時間</option>
                            </select>
                        </div>
                        <div class="col-auto">
                            <select name="orderDir" class="form-select form-select-sm" onchange="this.form.submit()">
                                <option value="DESC" <?= $orderDir === "DESC" ? "selected" : "" ?>><?= $descLabel ?>
                                </option>
                                <option value="ASC" <?= $orderDir === "ASC" ? "selected" : "" ?>><?= $ascLabel ?></option>
                            </select>
                        </div>
                    </form>

                    <!-- 無資料顯示 -->
                    <?php if (empty($rows)): ?>
                        <div class="alert alert-info text-center">查無資料</div>
                        <a class="btn btn-add btn-sm" href="./index.php">回優惠券主頁</a>
                    <?php endif; ?>

                    <!-- 卡片顯示 -->
                    <div class="coupon-grid">
                        <?php foreach ($rows as $row): ?>
                            <?= renderCouponCard($row) ?>
                        <?php endforeach; ?>

                    </div>
                </div>
                <!-- 分頁 -->
<?php
$visiblePages = 5;
$half = floor($visiblePages / 2);

$startPage = max(1, $page - $half);
$endPage = min($totalPage, $startPage + $visiblePages - 1);

if ($endPage - $startPage + 1 < $visiblePages) {
    $startPage = max(1, $endPage - $visiblePages + 1);
}
?>

  <nav>
    <ul class="pagination pagination-sm justify-content-center">

      <!-- 上一頁箭頭 -->
      <li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>">
        <a class="page-link" href="?<?= http_build_query(array_merge($_GET, ['page' => $page - 1])) ?>">&laquo;</a>
      </li>

      <!-- 頁碼按鈕 -->
      <?php for ($i = $startPage; $i <= $endPage; $i++): ?>
        <li class="page-item <?= $page == $i ? 'active' : '' ?>">
          <a class="page-link" href="?<?= http_build_query(array_merge($_GET, ['page' => $i])) ?>"><?= $i ?></a>
        </li>
      <?php endfor; ?>

      <!-- 下一頁箭頭 -->
      <li class="page-item <?= $page >= $totalPage ? 'disabled' : '' ?>">
        <a class="page-link" href="?<?= http_build_query(array_merge($_GET, ['page' => $page + 1])) ?>">&raquo;</a>
      </li>

    </ul>
  </nav>

            </main>
        </div>

    </div>
    <script>
        // 限制日期範圍
        const d1 = document.getElementById('date1');
        const d2 = document.getElementById('date2');
        d1.addEventListener('change', () => { d2.min = d1.value; });
        d2.addEventListener('change', () => { d1.max = d2.value; });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-k6d4wzSIapyDyv1kpU366/PK5hCdSbCRGRCMv+eplOQJWyd1fbcAu9OCUj5zNLiq"
        crossorigin="anonymous"></script>
</body>

</html>