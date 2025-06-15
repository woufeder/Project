<?php
// php -S localhost:8888

require_once "./connect.php";
require_once "./utilities.php";
include "../template_btn.php";
include "../vars.php";

//分頁
$perPage = 15;
$page = intval($_GET["page"] ?? 1);
// 每頁起始點第?筆
$pageStart = ($page - 1) * $perPage;

$date1 = $_GET["date1"] ?? "";
$date2 = $_GET["date2"] ?? "";
$searchType = $_GET["searchType"] ?? "";
$search = $_GET["search"] ?? "";

// 排序設定，只能是 id
$orderBy = $_GET["orderBy"] ?? "id";
// 限制 order  asc 或 desc，預設 asc
$order = strtolower($_GET["order"] ?? "asc") === "desc" ? "desc" : "asc";

$where = "WHERE is_valid = 1";
$params = [];

// 關鍵字條件: 使用者有選欄位 + 有輸入關鍵字
if ($searchType && $search) {
    $where .= " AND $searchType LIKE :search";
    $params[':search'] = "%$search%";
}

// 日期條件
if ($date1 && $date2) {
    $where .= " AND create_at BETWEEN :date1 AND :date2";
    // 日期 + 時間 以符合 create_at 欄位的格式
    $params[':date1'] = $date1 . " 00:00:00";
    $params[':date2'] = $date2 . " 23:59:59";
} elseif ($date1) {
    $where .= " AND create_at >= :date1";
    $params[':date1'] = $date1 . " 00:00:00";
} elseif ($date2) {
    $where .= " AND create_at <= :date2";
    $params[':date2'] = $date2 . " 23:59:59";
}

// 每頁顯示有效資料
$sql = "SELECT * FROM users $where ORDER BY $orderBy $order LIMIT $perPage OFFSET $pageStart";
// 計算總筆數
$sqlAll = "SELECT * FROM users $where";

try {
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $stmtAll = $pdo->prepare($sqlAll);
    $stmtAll->execute($params);
    $rowsAll = $stmtAll->fetchAll(PDO::FETCH_ASSOC);
    $totalCount = count($rowsAll);
} catch (PDOException $e) {
    echo "錯誤: " . $e->getMessage();
    exit;
}

// 產生排序連結跟圖示
$nextOrder = $order === "asc" ? "desc" : "asc";
$icon = $order === "asc"
    ? '<i class="fa-solid fa-sort-up ms-1"></i>'
    : '<i class="fa-solid fa-sort-down ms-1"></i>';

$baseQuery = $_GET;
// 固定只排序id
$baseQuery["orderBy"] = "id";
$baseQuery["order"] = $nextOrder;
// 排序時跳回第一頁
$baseQuery["page"] = 1;
$sortLink = "?" . http_build_query($baseQuery);

//頁碼
$totalPage = ceil($totalCount / $perPage);

// 計算要顯示的頁碼範圍 (一次5個)
$maxPagesToShow = 5;
// 往前多移動2頁，讓目前頁面在中間
// 取1和 $page - 2 之間較大的值，確保頁碼不小於 1
$startPage = max(1, $page - 2);
// 取 $totalPage 和 $startPage + $maxPagesToShow - 1 之間較小的值，確保不超過最後一頁
$endPage = min($totalPage, $startPage + $maxPagesToShow - 1);

// 若顯示範圍的頁碼數少於5個，會往前補更多的頁碼進來，同時起始頁碼不會小於1
if ($endPage - $startPage + 1 < $maxPagesToShow) {
    $startPage = max(1, $endPage - $maxPagesToShow + 1);
}

$cateNum = 0;
$pageTitle = "{$cate_ary[$cateNum]}列表";
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
    <link rel="stylesheet" href="./css/main.css">
    <link rel="stylesheet" href="./css/index.css">
</head>

<body>
    <div class="dashboard">
        <?php include '../template_sidebar.php'; ?>
        <div class="main-container">
            <?php include '../template_header.php'; ?>
            <main>
                <div class="container-fluid">
                    <h6 class="primary">&laquo; 總共 <?= $totalCount ?> 位會員 &raquo;</h6>

                    <div class="d-flex justify-content-between align-items-center mt-4">
                        <ul class="pagination">
                            <?php
                            // 首頁
                            // $_GET 超全域變數，抓網址上所有的查詢參數，內容是一個關聯式陣列
                            $queryParams = $_GET;
                            $queryParams["page"] = 1;
                            // http_build_query 把關聯陣列，轉換成 URL 查詢字串
                            $firstPageLink = "?" . http_build_query($queryParams);
                            ?>
                            <li class="page-item <?= $page === 1 ? 'disabled' : '' ?>">
                                <a class="page-link" href="<?= $firstPageLink ?>">首頁</a>
                            </li>

                            <?php
                            // 上一頁
                            $prevPage = max(1, $page - 1);
                            $queryParams["page"] = $prevPage;
                            $prevPageLink = "?" . http_build_query($queryParams);
                            ?>
                            <li class="page-item <?= $page === 1 ? 'disabled' : '' ?>">
                                <a class="page-link" href="<?= $prevPageLink ?>" aria-label="上一頁">
                                    <span aria-hidden="true">&laquo;</span>
                                </a>
                            </li>

                            <?php
                            // 只顯示3個頁碼，範圍 startPage ~ endPage
                            for ($i = $startPage; $i <= $endPage; $i++):
                                $queryParams["page"] = $i;
                                $link = "?" . http_build_query($queryParams);
                                ?>
                                <li class="page-item <?= $page == $i ? "active" : "" ?>">
                                    <a class="page-link" href="<?= $link ?>"><?= $i ?></a>
                                </li>
                            <?php endfor; ?>

                            <!-- 下一頁 -->
                            <?php
                            $isLastPage = $page >= $totalPage;
                            $nextPage = min($totalPage, $page + 1);
                            $queryParams["page"] = $nextPage;
                            $nextPageLink = "?" . http_build_query($queryParams);
                            ?>
                            <li class="page-item <?= $isLastPage ? 'disabled' : '' ?>">
                                <a class="page-link" href="<?= $nextPageLink ?>" aria-label="下一頁">
                                    <span aria-hidden="true">&raquo;</span>
                                </a>
                            </li>

                            <!-- 末頁 -->
                            <?php
                            $queryParams["page"] = $totalPage;
                            $lastPageLink = "?" . http_build_query($queryParams);
                            ?>
                            <li class="page-item <?= $isLastPage ? 'disabled' : '' ?>">
                                <a class="page-link" href="<?= $isLastPage ? '#' : $lastPageLink ?>">末頁</a>
                            </li>
                        </ul>
                        <a class="btn btn-b btn-add" href="./add.php"><i class="fa-solid fa-plus"></i></a>
                    </div>

                    <!-- 表單裡的資料 變成網址上的參數 -->
                    <form method="get" id="search-form" class="d-flex justify-content-end mb-3">
                        <div class="input-group">
                            <label for="searchType1" class="input-group-text">開始日期</label>
                            <!-- 表單的 name 作為鍵名將對應的值送到後端，可用 value $_GET["date1"] 將對應的參數值取出 -->
                            <input name="date1" id="searchType1" type="date" class="form-control"
                                value="<?= $_GET["date1"] ?? "" ?>" data-default-name="date1">
                            <label for="searchType2" class="input-group-text">結束日期</label>
                            <input name="date2" id="searchType2" type="date" class="form-control"
                                value="<?= $_GET["date2"] ?? "" ?>" data-default-name="date2">

                            <select name="searchType" class="form-select" data-default-name="searchType">
                                <option value="">欄位</option>
                                <option value="name" <?= ($_GET["searchType"] ?? "") == "name" ? "selected" : "" ?>>姓名
                                </option>
                                <option value="account" <?= ($_GET["searchType"] ?? "") == "account" ? "selected" : "" ?>>
                                    帳號</option>
                                <option value="email" <?= ($_GET["searchType"] ?? "") == "email" ? "selected" : "" ?>>信箱
                                </option>
                            </select>

                            <input name="search" type="text" class="form-control" id="keyword" placeholder="請輸入關鍵字"
                                value="<?= $_GET["search"] ?? "" ?>" data-default-name="search">
                            <button class="btn btn-b" type="submit"><i
                                    class="fa-solid fa-magnifying-glass"></i></button>
                        </div>
                        <button type="button" class="btn btn-outline-b ms-3" id="btn-reset"><i
                                class="fa-solid fa-ban"></i></button>
                    </form>


                    <div class="bg-white rounded-3 overflow-hidden shadow-sm p-2">
                        <!-- 標題列 -->
                        <div class="d-flex py-3 px-2 header text-white rounded-3 fw-bold">
                            <div class="id">
                                <span class="me-1">#</span>
                                <a href="<?= $sortLink ?>" class="text-decoration-none text-white">
                                    <?= $icon ?>
                                </a>
                            </div>
                            <div class="img">頭像</div>
                            <div class="name">姓名</div>
                            <div class="account">帳號</div>
                            <div class="email">信箱</div>
                            <div class="time">加入時間</div>
                            <div class="edit">操作</div>
                        </div>

                        <!-- 資料列 -->
                        <?php foreach ($rows as $index => $row): ?>
                            <div
                                class="d-flex align-items-center py-3 px-2 <?= $index === 0 ? '' : 'border-top' ?> table-row">
                                <div class="id">
                                    <?php
                                    if ($order === "asc") {
                                        echo $index + 1 + ($page - 1) * $perPage;
                                    } else {
                                        echo $totalCount - (($page - 1) * $perPage + $index);
                                    }
                                    ?>
                                </div>
                                <div class="img">
                                    <div class="img-thumbnail">
                                        <?php if (!empty($row["img"]) && file_exists("./imgs/" . $row["img"])): ?>
                                            <img src="./imgs/<?= $row["img"] ?>" alt="<?= $row["img"] ?>">
                                        <?php else: ?>
                                            <!-- 甚麼都不放，顯示灰色背景 -->
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="name"><?= $row["name"] ?></div>
                                <div class="account"><?= $row["account"] ?></div>
                                <div class="email"><?= $row["email"] ?></div>
                                <div class="time"><?= $row["create_at"] ?></div>
                                <div class="edit">
                                    <a class="btn btn-sm btn-b" href="./view.php?id=<?= $row["id"] ?>"><i
                                            class="fas fa-eye"></i></a>
                                    <a class="btn btn-sm btn-y" href="./update.php?id=<?= $row["id"] ?>"><i
                                            class="fas fa-pen"></i></a>
                                    <button class="btn btn-sm btn-del btn-d" data-id="<?= $row["id"] ?>"><i
                                            class="fas fa-trash"></i></button>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <ul class="pagination mt-4 d-flex justify-content-center">
                        <?php
                        // 首頁
                        // $_GET 超全域變數，抓網址上所有的查詢參數，內容是一個關聯式陣列
                        $queryParams = $_GET;
                        $queryParams["page"] = 1;
                        // http_build_query 把關聯陣列，轉換成 URL 查詢字串
                        $firstPageLink = "?" . http_build_query($queryParams);
                        ?>
                        <li class="page-item <?= $page === 1 ? 'disabled' : '' ?>">
                            <a class="page-link" href="<?= $firstPageLink ?>">首頁</a>
                        </li>

                        <?php
                        // 上一頁
                        $prevPage = max(1, $page - 1);
                        $queryParams["page"] = $prevPage;
                        $prevPageLink = "?" . http_build_query($queryParams);
                        ?>
                        <li class="page-item <?= $page === 1 ? 'disabled' : '' ?>">
                            <a class="page-link" href="<?= $prevPageLink ?>" aria-label="上一頁">
                                <span aria-hidden="true">&laquo;</span>
                            </a>
                        </li>

                        <?php
                        // 只顯示3個頁碼，範圍 startPage ~ endPage
                        for ($i = $startPage; $i <= $endPage; $i++):
                            $queryParams["page"] = $i;
                            $link = "?" . http_build_query($queryParams);
                            ?>
                            <li class="page-item <?= $page == $i ? "active" : "" ?>">
                                <a class="page-link" href="<?= $link ?>"><?= $i ?></a>
                            </li>
                        <?php endfor; ?>

                        <!-- 下一頁 -->
                        <?php
                        $isLastPage = $page >= $totalPage;
                        $nextPage = min($totalPage, $page + 1);
                        $queryParams["page"] = $nextPage;
                        $nextPageLink = "?" . http_build_query($queryParams);
                        ?>
                        <li class="page-item <?= $isLastPage ? 'disabled' : '' ?>">
                            <a class="page-link" href="<?= $nextPageLink ?>" aria-label="下一頁">
                                <span aria-hidden="true">&raquo;</span>
                            </a>
                        </li>

                        <!-- 末頁 -->
                        <?php
                        $queryParams["page"] = $totalPage;
                        $lastPageLink = "?" . http_build_query($queryParams);
                        ?>
                        <li class="page-item <?= $isLastPage ? 'disabled' : '' ?>">
                            <a class="page-link" href="<?= $isLastPage ? '#' : $lastPageLink ?>">末頁</a>
                        </li>
                    </ul>
                </div>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-k6d4wzSIapyDyv1kpU366/PK5hCdSbCRGRCMv+eplOQJWyd1fbcAu9OCUj5zNLiq" crossorigin="anonymous">
        </script>
    <!-- 刪除功能 -->
    <script>
        const btnDels = document.querySelectorAll(".btn-del");
        btnDels.forEach((btn) => {
            btn.addEventListener("click", doConfirm);
        });

        function doConfirm(e) {
            const btn = e.target;
            if (window.confirm("是否確定要停權該會員?")) {
                window.location.href = `./doDelete.php?id=${btn.dataset.id}`;
            }
        }

        const form = document.querySelector('#search-form');

        form.addEventListener('submit', function (e) {
            // 抓取 input 或 select 裡有設定 name 屬性的輸入欄位
            const inputs = form.querySelectorAll('input[name], select[name]');
            inputs.forEach(input => {
                // 如果欄位內容去掉前後空白後是空的，移除 name 屬性，不送出這個欄位
                if (!input.value.trim()) {
                    input.removeAttribute('name');
                }
            });

            // setTimeout 先讓表單送出後，再把 name 屬性還原
            setTimeout(() => {
                inputs.forEach(input => {
                    // 沒有 name 屬性的欄位
                    if (!input.hasAttribute('name')) {
                        // 從 HTML 裡的 data-default-name 這個自訂屬性(瀏覽器不會執行，但 JS 可讀取使用)拿該欄位原本存的「正確的 name」
                        const defaultName = input.getAttribute('data-default-name');
                        if (defaultName) {
                            // setAttribute('屬性名稱', '屬性值')
                            input.setAttribute('name', defaultName);
                        }
                    }
                });
            }, 10);
        });

        document.querySelector('#btn-reset').addEventListener('click', function () {
            window.location.href = './index.php';
        });

        // 關鍵字未輸入判斷
        form.addEventListener("submit", function (e) {
            const keyword = document.querySelector("#keyword").value.trim();
            const field = document.querySelector(".form-select").value;

            if (keyword !== "" && field === "") {
                e.preventDefault();
                alert("請先選擇欄位");
            }
        });
    </script>
</body>

</html>