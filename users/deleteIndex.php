<?php
// 圖片聚焦問題，如果沒有上傳圖片show 灰色圓塊

//更新程式碼

require_once "./connect.php";
require_once "./utilities.php";

//分頁
$perPage = 10;
$page = intval($_GET["page"] ?? 1);
// 每頁起始點第?筆
$pageStart = ($page - 1) * $perPage;

// 每頁顯示無效資料
$sql = "SELECT * FROM `users` WHERE `is_valid` = 0 LIMIT $perPage OFFSET $pageStart";
// 總共無效資料
$sqlAll = "SELECT * FROM `users` WHERE `is_valid` = 0";

try {
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $stmtAll = $pdo->prepare($sqlAll);
    $stmtAll->execute();
    // 算總筆數
    $rowsAll = $stmtAll->fetchAll(PDO::FETCH_ASSOC);
    $totalCount = count($rowsAll);
    //$totalCount = $stmtAll->rowCount();
} catch (PDOException $e) {
    echo "錯誤: {{$e->getMessage()}}";
    exit;
}

$totalPage = ceil($totalCount / $perPage);
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>會員管理</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">
    <style>
        :root {
            --color-bg: #ffffff;
            --color-surface: #F9F7F7;
            --color-border: #DBE2EF;

            --color-primary: #3F72AF;
            --color-primary-light: #5B8BD6;

            --color-accent: #E1B822;

            --color-text: #2c2c2c;
            --color-text-secondary: #64748b;
            --color-text-inverse: #1e293b;

            --box-shadow: rgba(63, 114, 175, 0.2);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background-color: var(--color-border);
        }

        .header {
            background-color: var(--color-primary);
        }

        .id {
            width: 50px;
        }

        .img {
            width: 80px;
        }

        .img-thumbnail {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background-color: rgb(255, 255, 255);
            display: flex;
            justify-content: center;
            align-items: center;
            overflow: hidden;
        }

        .img-thumbnail img {
            width: 150%;
            height: 150%;
            object-fit: cover;
        }

        .name {
            width: 200px;
        }

        .account {
            flex: 1;
        }

        .email {
            flex: 1;
        }

        .time {
            width: 200px;
        }

        .edit {
            width: 120px;
        }

        .table-row:hover {
            background-color: #f1f5f9;
            transition: background-color 0.2s;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>停權會員列表</h1>
        <div class="d-flex  align-items-center">
            <span>總共 <?= $totalCount ?> 位停權會員</span>
            <a class="btn btn-primary ms-auto" href="./index.php">會員列表</a>
        </div>

        <ul class="pagination">
            <?php for ($i = 1; $i <= $totalPage; $i++): ?>
                <li class="page-item <?= $page == $i ? "active" : "" ?>">
                    <?php $link = "?page={$i}"; ?>
                    <a class="page-link" href="<?= $link ?>"><?= $i ?></a>
                </li>
            <?php endfor; ?>
        </ul>

        <div class="bg-white rounded-3 overflow-hidden shadow-sm p-2">
            <!-- 標題列 -->
            <div class="d-flex py-3 px-2 header text-white rounded-3 fw-bold">
                <div class="id">#</div>
                <div class="img">頭像</div>
                <div class="name">姓名</div>
                <div class="account">帳號</div>
                <div class="email">信箱</div>
                <div class="time">加入時間</div>
                <div class="edit">操作</div>
            </div>

            <!-- 資料列 -->
            <?php foreach ($rows as $index => $row): ?>
                <div class="d-flex align-items-center py-3 px-2 <?= $index === 0 ? '' : 'border-top' ?> table-row">
                    <div class="id"><?= $index + 1 + ($page - 1) * $perPage ?></div>
                    <div class="img">
                        <div class="img-thumbnail">
                            <img src="./imgs/<?= $row["img"] ?>" alt="<?= $row["img"] ?>">
                        </div>
                    </div>
                    <div class="name"><?= $row["name"] ?></div>
                    <div class="account"><?= $row["account"] ?></div>
                    <div class="email"><?= $row["email"] ?></div>
                    <div class="time"><?= $row["create_at"] ?></div>
                    <div class="edit">
                        <a class="btn btn-warning btn-sm" href="./update.php?id=<?= $row["id"] ?>">修改</a>
                        <button class="btn btn-danger btn-sm btn-del" data-id="<?= $row["id"] ?>">解除</button>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <ul class="pagination mt-4 d-flex justify-content-center">
            <?php for ($i = 1; $i <= $totalPage; $i++): ?>
                <li class="page-item <?= $page == $i ? "active" : "" ?>">
                    <?php $link = "?page={$i}"; ?>
                    <a class="page-link" href="<?= $link ?>"><?= $i ?></a>
                </li>
            <?php endfor; ?>
        </ul>

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
            if (window.confirm("是否確定解除停權該會員?")) {
                window.location.href = `./undoDelete.php?id=${btn.dataset.id}`;
            }
        }
    </script>
</body>

</html>