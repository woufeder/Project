<?php
include "../template_btn.php";
require_once "./Utilities.php";
include "../vars.php";
require_once "./connect.php";


// 自動將過期或被刪除的優惠券設為未啟用
$pdo->exec("UPDATE coupon SET is_valid = 0, is_active = 0 WHERE expires_at < NOW() AND is_valid = 1");


$cateNum = 2;
$pageTitle = "已失效優惠券列表";

// 查詢參數
$search = $_GET["search"] ?? "";
$searchType = $_GET["qType"] ?? "";
$typeFilter = $_GET["typeFilter"] ?? "";
$date1 = $_GET["date1"] ?? "";
$date2 = $_GET["date2"] ?? "";
$activeFilter = $_GET["activeFilter"] ?? "";
$orderBy = $_GET["orderBy"] ?? "id";
$orderDir = strtoupper($_GET["orderDir"] ?? "DESC");

$allowedOrderFields = ["id", "type", "value", "min", "start_at", "expires_at", "created_at", "updated_at", "is_active"];
if (!in_array($orderBy, $allowedOrderFields))
    $orderBy = "id";
if (!in_array($orderDir, ["ASC", "DESC"]))
    $orderDir = "DESC";

$where = ["coupon.is_valid = 0"];
$values = [];
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
if ($typeFilter !== "") {
    $where[] = "coupon.type = :typeFilter";
    $values["typeFilter"] = intval($typeFilter);
}
if ($date1 && $date2) {
    $where[] = "(start_at <= :endDate AND expires_at >= :startDate)";
    $values["startDate"] = $date1 . " 00:00:00";
    $values["endDate"] = $date2 . " 23:59:59";
}
if ($activeFilter !== "") {
    $where[] = "coupon.is_active = :activeFilter";
    $values["activeFilter"] = $activeFilter;
}

$whereSQL = implode(" AND ", $where);

if (isset($_GET["toggle_id"])) {
    $toggleId = $_GET["toggle_id"];

    // 只允許有效（is_valid = 1）且未過期的優惠券才能切換狀態
    $stmtCheck = $pdo->prepare("SELECT * FROM coupon WHERE id = ? AND is_valid = 1 AND expires_at > NOW()");
    $stmtCheck->execute([$toggleId]);
    $validRow = $stmtCheck->fetch();
    if ($validRow) {
        $pdo->prepare("UPDATE coupon SET is_active = NOT is_active WHERE id = ?")->execute([$toggleId]);
    }

    header("Location: " . strtok($_SERVER["REQUEST_URI"], '?'));
    exit;
}

if (isset($_GET["restore_id"])) {
    $restoreId = $_GET["restore_id"];

    // 取得該筆資料
    $sql = "SELECT expires_at FROM coupon WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$restoreId]);
    $row = $stmt->fetch();

    // 如果資料存在且已過期
    if ($row && strtotime($row["expires_at"]) < time()) {
        header("Location: ./expiredList.php?error=expiredActive");
        exit;
    }

    // 否則允許重新上架
    $stmt = $pdo->prepare("UPDATE coupon SET is_valid = 1, is_active = 1, updated_at = NOW() WHERE id = ?");
    $stmt->execute([$restoreId]);
    header("Location: ./expiredList.php");
    exit;
}


$perPage = 12;
$page = max(1, intval($_GET["page"] ?? 1));
$pageStart = ($page - 1) * $perPage;

$sql = "SELECT * FROM coupon WHERE $whereSQL ORDER BY $orderBy $orderDir LIMIT $perPage OFFSET $pageStart";
$sqlAll = "SELECT COUNT(*) FROM coupon WHERE $whereSQL";

try {
    $stmt = $pdo->prepare($sql);
    $stmt->execute($values);
    $rows = $stmt->fetchAll();

    $stmtAll = $pdo->prepare($sqlAll);
    $stmtAll->execute($values);
    $totalCount = $stmtAll->fetchColumn();

    $totalPage = ceil($totalCount / $perPage);
    $range = 2;
    $startPage = max(1, $page - $range);
    $endPage = min($totalPage, $page + $range);


    if ($endPage - $startPage < 4) {
        if ($startPage == 1) {
            $endPage = min($totalPage, $startPage + 4);
        } elseif ($endPage == $totalPage) {
            $startPage = max(1, $endPage - 4);
        }
    }

} catch (PDOException $e) {
    echo "錯誤: " . $e->getMessage();
    exit;
}

function sortIcon($field, $orderBy, $orderDir)
{
    if ($field !== $orderBy)
        return '<i class="fa-solid fa-sort"></i>';
    return $orderDir === 'ASC'
        ? '<i class="fa-solid fa-sort-up"></i>'
        : '<i class="fa-solid fa-sort-down"></i>';
}
?>


<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PC 周邊商品後台管理系統|失效優惠券列表</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
        integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+TC:wght@100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/main.css">
    <link rel="stylesheet" href="./indexList.css">

</head>

<body>
    <div class="dashboard">
        <?php include(__DIR__ . "/../template_sidebar.php"); ?>
        <div class="main-container">
            <?php include(__DIR__ . "/../template_header.php"); ?>
            <main>
                <div class="container my-4">
                    <div class="my-2 d-flex align-items-center">
                        <span class="text-white bg-primary px-3 py-1 rounded-pill shadow-sm">
                            <i class="fa-solid fa-list me-1"></i>
                            共 <?= $totalCount ?> 筆資料
                        </span>
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
                            <?php
                            // 把 $_GET 中的 orderBy 和 orderDir 移除
                            $cleanSortUrl = strtok($_SERVER["REQUEST_URI"], '?') . '?' . http_build_query(array_diff_key($_GET, ['orderBy' => '', 'orderDir' => '']));
                            ?>
                            <a href="<?= $cleanSortUrl ?>" class="btn btn-sm btn-outline-danger">
                                清除排序
                            </a>
                        </div>

                    </form>

                    <?php if (isset($_GET["error"]) && $_GET["error"] === "expired"): ?>
                        <div class="alert alert-warning mt-3" role="alert">
                            ⚠️ 已逾期的優惠券無法重新啟用，請先修改有效期限！
                        </div>
                    <?php endif; ?>
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
                            <div class="actions">

                                <a href="./update.php?id=<?= $row["id"] ?>" class="btn btn-sm btn-cha">修改</a>
                                <a href="?restore_id=<?= $row["id"] ?>" class="btn btn-sm btn-del">重新上架</a>

                            </div>
                        </div>
                    <?php endforeach; ?>

                    <nav>
                        <ul class="pagination pagination-sm justify-content-center">

                            <!-- 上一頁箭頭 -->
                            <li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>">
                                <a class="page-link"
                                    href="?<?= http_build_query(array_merge($_GET, ['page' => $page - 1])) ?>">&laquo;</a>
                            </li>

                            <!-- 頁碼按鈕 -->
                            <?php for ($i = $startPage; $i <= $endPage; $i++): ?>
                                <li class="page-item <?= $page == $i ? 'active' : '' ?>">
                                    <a class="page-link"
                                        href="?<?= http_build_query(array_merge($_GET, ['page' => $i])) ?>"><?= $i ?></a>
                                </li>
                            <?php endfor; ?>

                            <!-- 下一頁箭頭 -->
                            <li class="page-item <?= $page >= $totalPage ? 'disabled' : '' ?>">
                                <a class="page-link"
                                    href="?<?= http_build_query(array_merge($_GET, ['page' => $page + 1])) ?>">&raquo;</a>
                            </li>

                        </ul>
                    </nav>
                </div>

                <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"
                    crossorigin="anonymous"></script>
                <script>
                    document.addEventListener("click", function (e) {
                        if (e.target && e.target.classList.contains("btn-del")) {
                            if (confirm("確定要重新上架嗎?")) {
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
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-k6d4wzSIapyDyv1kpU366/PK5hCdSbCRGRCMv+eplOQJWyd1fbcAu9OCUj5zNLiq"
        crossorigin="anonymous"></script>
</body>

</html>