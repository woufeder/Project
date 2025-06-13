<?php
require_once "../connect.php";

// 查詢參數
$search = $_GET["search"] ?? "";
$searchType = $_GET["qType"] ?? "";
$typeFilter = $_GET["typeFilter"] ?? ""; // 類型篩選
$date1 = $_GET["date1"] ?? "";
$date2 = $_GET["date2"] ?? "";
$activeFilter = $_GET["activeFilter"] ?? "";
$orderBy = $_GET["orderBy"] ?? "id";
$orderDir = strtoupper($_GET["orderDir"] ?? "DESC");

// 排序欄位與方向白名單
$allowedOrderFields = ["id", "type", "value", "min", "start_at", "expires_at", "created_at", "updated_at", "is_active"];
if (!in_array($orderBy, $allowedOrderFields))
    $orderBy = "id";
if (!in_array($orderDir, ["ASC", "DESC"]))
    $orderDir = "DESC";

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

// 類型篩選（1=百分比，0=固定金額）
if ($typeFilter !== "") {
    $where[] = "coupon.type = :typeFilter";
    $values["typeFilter"] = intval($typeFilter);
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
$perPage = 12;
$page = intval($_GET["page"] ?? 1);
$pageStart = ($page - 1) * $perPage;

// 撈資料
$sql = "SELECT coupon.*, images.file_path
        FROM coupon
        LEFT JOIN images ON coupon.img_id = images.id
        WHERE $whereSQL
        ORDER BY $orderBy $orderDir
        LIMIT $perPage OFFSET $pageStart";
$sqlAll = "SELECT coupon.id
           FROM coupon
           LEFT JOIN images ON coupon.img_id = images.id
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

function sortIcon($field, $orderBy, $orderDir)
{
    if ($field !== $orderBy)
        return '<i class="fa-solid fa-sort"></i>';
    return $orderDir === 'ASC'
        ? '<i class="fa-solid fa-sort-up"></i>'
        : '<i class="fa-solid fa-sort-down"></i>';
} ?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>優惠券列表</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet"
        crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"
        integrity="sha512-...略..." crossorigin="anonymous" referrerpolicy="no-referrer" />

    <style>
        body {
            background-color: #fff;
        }

        h1 {
            color: #3F72AF;
        }

        .msg {
            display: flex;
            align-items: center;
            padding: 10px;
            margin-bottom: 10px;
            background-color: #f8f9fa;
            border-radius: 5px;
            font-size: 0.9rem;
            flex-wrap: wrap;
            overflow-x: hidden;
        }

        .msg-header {
            display: flex;
            align-items: center;
            font-weight: bold;
            background-color: #3F72AF;
            color: white;
            padding: 10px;
            border-radius: 5px;
            font-size: 0.95rem;
        }

        .msg-header a i {
            margin-left: 5px;
        }

        /* 欄位寬度（編號、優惠碼、折扣、低消 窄一點） */
        .id,
        .msg-header .id {
            flex: 0 0 50px;
            text-align: center;
        }

        .code,
        .msg-header .code,
        .value,
        .msg-header .value,
        .min,
        .msg-header .min {
            flex: 0 0 70px;
            text-align: center;
        }

        .desc,
        .msg-header .desc {
            flex: 0 0 140px;
            padding: 0 5px;
            text-align: center;
        }

        .type,
        .msg-header .type {
            flex: 0 0 80px;
            text-align: center;
        }

        .start_at,
        .msg-header .start_at,
        .expires_at,
        .msg-header .expires_at,
        .create_at,
        .msg-header .create_at,
        .updated_at,
        .msg-header .updated_at {
            flex: 0 0 140px;
            text-align: center;
        }

        .is_active,
        .msg-header .is_active {
            flex: 0 0 100px;
            text-align: center;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .actions,
        .msg-header .actions {
            flex: 0 0 120px;
            display: flex;
            justify-content: center;
            gap: 6px;
        }

        .actions .btn,
        .is_active .btn {
            padding: 3px 8px;
            font-size: 0.85rem;
            white-space: nowrap;
        }

        .create_at,
        .msg-header .create_at,
        .updated_at,
        .msg-header .updated_at {
            flex: 0 0 140px;
            text-align: center;
            overflow-wrap: break-word;
            white-space: normal;
        }



        .btn {
            margin-right: 0;
        }

        .btn-add,
        .btn-cha,
        .btn-del {
            background-color: #3F72AF;
            color: white;
            transition: background-color 0.2s;
        }

        .btn-add:hover,
        .btn-cha:hover,
        .btn-del:hover {
            background-color: #89b9f4;
            color: white;
        }

        .btn-toggle-on {
            background-color: #ffd66e;
            color: black;
            transition: background-color 0.2s, box-shadow 0.2s;
        }

        .btn-toggle-on:hover {
            background-color: #ffd66e;
        }

        .btn-toggle-off {
            background-color: #6c757d;
            color: white;
            border: 1px solid #545b62;
            transition: background-color 0.2s, box-shadow 0.2s;
        }

        .btn-toggle-off:hover {
            background-color: #868e96;
        }

        .pagination .page-item.active .page-link {
            background-color: rgb(200, 225, 255);
            border-color: #3F72AF;
        }

        .pagination .page-link {
            color: #3F72AF;
        }

        .pagination .page-link:hover {
            background-color: #DBE2EF;
        }
    </style>
</head>

<body>
    <div class="container my-4">
        <h1 class="mb-4 coupon-list">優惠券列表</h1>
        <div class="my-2 d-flex">
            <span class="me-auto">目前共 <?= $totalCount ?> 筆資料</span>
            <a class="btn btn-add btn-sm" href="./add.php">新增優惠券</a>
        </div>
        <div class="d-flex justify-content-end mb-3">
            <a href="index.php" class="btn btn-outline-secondary btn-sm">切換卡片模式</a>
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
                <input type="date" name="date1" id="date1" value="<?= $date1 ?>" class="form-control form-control-sm">
            </div>
            <div class="col-auto">
                <input type="date" name="date2" id="date2" value="<?= $date2 ?>" class="form-control form-control-sm">
            </div>
            <div class="col-auto">
                <button class="btn btn-sm btn-primary">搜尋</button>
            </div>
            <div class="col-auto">
                <?php
                // 把 $_GET 中的 orderBy 和 orderDir 移除
                $cleanSortUrl = strtok($_SERVER["REQUEST_URI"], '?') . '?' . http_build_query(array_diff_key($_GET, ['orderBy' => '', 'orderDir' => '']));
                ?>
                <a href="<?= $cleanSortUrl ?>" class="btn btn-sm btn-outline-danger">
                    清除排序
                </a>
            </div>

        </form>

        <div class="msg msg-header mb-1">
            <div class="id">編號</div>
            <div class="desc">敘述</div>
            <div class="code">優惠碼</div>
            <div class="type">
                <a href="?<?= http_build_query(array_merge($_GET, ['orderBy' => 'type', 'orderDir' => ($orderBy === 'type' && $orderDir === 'ASC') ? 'DESC' : 'ASC'])) ?>"
                    class="text-white text-decoration-none">
                    類型 <?= sortIcon('type', $orderBy, $orderDir) ?>
                </a>
            </div>
            <div class="value">
                <a href="?<?= http_build_query(array_merge($_GET, ['orderBy' => 'value', 'orderDir' => ($orderBy === 'value' && $orderDir === 'ASC') ? 'DESC' : 'ASC'])) ?>"
                    class="text-white text-decoration-none">
                    折扣 <?= sortIcon('value', $orderBy, $orderDir) ?>
                </a>
            </div>
            <div class="min">
                <a href="?<?= http_build_query(array_merge($_GET, ['orderBy' => 'min', 'orderDir' => ($orderBy === 'min' && $orderDir === 'ASC') ? 'DESC' : 'ASC'])) ?>"
                    class="text-white text-decoration-none">
                    低消 <?= sortIcon('min', $orderBy, $orderDir) ?>
                </a>
            </div>

            <div class="start_at">
                <a href="?<?= http_build_query(array_merge($_GET, ['orderBy' => 'start_at', 'orderDir' => ($orderBy === 'start_at' && $orderDir === 'ASC') ? 'DESC' : 'ASC'])) ?>"
                    class="text-white text-decoration-none">
                    開始時間 <?= sortIcon('start_at', $orderBy, $orderDir) ?>
                </a>
            </div>

            <div class="expires_at">
                <a href="?<?= http_build_query(array_merge($_GET, ['orderBy' => 'expires_at', 'orderDir' => ($orderBy === 'expires_at' && $orderDir === 'ASC') ? 'DESC' : 'ASC'])) ?>"
                    class="text-white text-decoration-none">
                    結束時間 <?= sortIcon('expires_at', $orderBy, $orderDir) ?>
                </a>
            </div>

            <div class="create_at">
                <a href="?<?= http_build_query(array_merge($_GET, ['orderBy' => 'created_at', 'orderDir' => ($orderBy === 'created_at' && $orderDir === 'ASC') ? 'DESC' : 'ASC'])) ?>"
                    class="text-white text-decoration-none">
                    建立時間 <?= sortIcon('created_at', $orderBy, $orderDir) ?>
                </a>
            </div>

            <div class="updated_at">
                <a href="?<?= http_build_query(array_merge($_GET, ['orderBy' => 'updated_at', 'orderDir' => ($orderBy === 'updated_at' && $orderDir === 'ASC') ? 'DESC' : 'ASC'])) ?>"
                    class="text-white text-decoration-none">
                    更新時間 <?= sortIcon('updated_at', $orderBy, $orderDir) ?>
                </a>
            </div>
            <div class="is_active">
                <a href="?<?= http_build_query(array_merge($_GET, ['orderBy' => 'is_active', 'orderDir' => ($orderBy === 'is_active' && $orderDir === 'ASC') ? 'DESC' : 'ASC'])) ?>"
                    class="text-white text-decoration-none">
                    狀態 <?= sortIcon('is_active', $orderBy, $orderDir) ?>
                </a>
            </div>
            <div class="actions">操作</div>

        </div>

        <!-- 無資料顯示 -->
        <?php if (empty($rows)): ?>
            <div class="alert alert-info text-center">查無資料</div>
            <a class="btn btn-add btn-sm" href="./indexList.php">回優惠券主頁</a>
        <?php endif; ?>

        <?php foreach ($rows as $index => $row): ?>
            <div class="msg">
                <div class="id"><?= $index + 1 + $pageStart ?></div>
                <div class="desc"><?= $row["desc"] ?></div>
                <div class="code"><?= htmlspecialchars($row["code"]) ?></div>
                <div class="type"><?= $row["type"] == 1 ? "百分比" : "固定金額" ?></div>
                <div class="value"><?= $row["value"] ?></div>
                <div class="min"><?= $row["min"] ?></div>
                <div class="start_at"><?= date("Y-m-d", strtotime($row["start_at"])) ?></div>
                <div class="expires_at"><?= date("Y-m-d", strtotime($row["expires_at"])) ?></div>
                <div class="create_at">
                    <?= date("Y-m-d", strtotime($row["created_at"])) ?><br>
                    <?= date("H:i:s", strtotime($row["created_at"])) ?>
                </div>
                <div class="updated_at">
                    <?= date("Y-m-d", strtotime($row["updated_at"])) ?><br>
                    <?= date("H:i:s", strtotime($row["updated_at"])) ?>
                </div>
                <div class="is_active">
                    <a href="?toggle_id=<?= $row["id"] ?>"
                        class="btn btn-sm <?= $row["is_active"] ? 'btn-toggle-on' : 'btn-toggle-off' ?>">
                        <?= $row["is_active"] ? '啟用中' : '未啟用' ?>
                    </a>
                </div>
                <div class="actions">
                    <a href="./update.php?id=<?= $row["id"] ?>" class="btn btn-sm btn-cha">修改</a>
                    <a href="./doDelete.php?id=<?= $row["id"] ?>" class="btn btn-sm btn-del">刪除</a>
                </div>
            </div>
        <?php endforeach; ?>

        <nav>
            <ul class="pagination pagination-sm justify-content-center">
                <?php for ($i = 1; $i <= $totalPage; $i++): ?>
                    <li class="page-item <?= $page == $i ? "active" : "" ?>">
                        <a class="page-link" href="?<?= http_build_query(array_merge($_GET, ["page" => $i])) ?>">
                            <?= $i ?>
                        </a>
                    </li>
                <?php endfor; ?>
            </ul>
        </nav>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"
        crossorigin="anonymous"></script>
    <script>
        document.addEventListener("click", function (e) {
            if (e.target && e.target.classList.contains("btn-del")) {
                if (confirm("確定要刪除嗎?")) {
                    window.location.href = `./doDelete.php?id=${e.target.dataset.id}`;
                }
            }
        });
    </script>

    <script>
        const toggleBtn = document.getElementById("toggleViewBtn");
        const container = document.querySelector(".container");

        toggleBtn.addEventListener("click", () => {
            container.classList.toggle("list-view");
            if (container.classList.contains("list-view")) {
                toggleBtn.textContent = "切換卡片模式";
            } else {
                toggleBtn.textContent = "切換列表模式";
            }
        });
    </script>
</body>

</html>