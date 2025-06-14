<?php
require_once "./connect.php";
require_once "./utilities.php";

if (!isset($_GET["id"])) {
    alertGoTo("請從正常管道進入", "./index.php");
    exit;
}


$id = $_GET["id"];

$sql = "SELECT * FROM `users` WHERE `is_valid` = 1 AND `id` = ?";
$sqlGender = "SELECT * FROM gender";
$sqlCity = "SELECT * FROM city";

try {
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$row) {
        alertGoBack("沒有該會員");
    }

    $stmtGender = $pdo->prepare($sqlGender);
    $stmtGender->execute();
    $rowsGender = $stmtGender->fetchAll(PDO::FETCH_ASSOC);

    $stmtCity = $pdo->prepare($sqlCity);
    $stmtCity->execute();
    $rowsCity = $stmtCity->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "錯誤: {{$e->getMessage()}}";
    exit;
}
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>會員資料編輯</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
        integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+TC:wght@100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="main.css">
    <link rel="stylesheet" href="./btn.css">
    <style>
        :root {
            --color-bg: #ffffff;
            --color-surface: #F9F7F7;
            --color-border: #DBE2EF;

            --color-primary: #3F72AF;
            --color-primary-light: #5B8BD6;

            --color-accent: #E1B822;

            --color-text: #2c2c2c;
            --color-text-white: #64748b;
            --color-text-inverse: #1e293b;

            --box-shadow: rgba(63, 114, 175, 0.2);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html,
        body {
            height: 100%;
        }

        body {
            font-family: "Noto Sans TC", sans-serif;
            background: linear-gradient(to top right, rgb(141, 155, 179) 0%, var(--color-primary-light) 100%);
            background-repeat: no-repeat;
            overflow-x: hidden;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .container {
            background-color: var(--color-border);

        }

        .avatar-wrapper {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            background-color: rgb(186, 186, 187);
            display: flex;
            justify-content: center;
            align-items: center;
            overflow: hidden;
            margin: auto;
        }

        .avatar-wrapper img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
    </style>
</head>

<body>
    <div class="container w-50 p-4 rounded-4">
        <h1 class="border-bottom border-white pb-4 text-center">會員資料</h1>
        <div class="row mb-2">
            <div class="col-12">
                <div id="avatar-preview" class="avatar-wrapper my-2 mx-auto border border-white border-3">
                    <?php if (!empty($row["img"])): ?>
                        <img src="./imgs/<?= htmlspecialchars($row["img"]) ?>" alt="<?= $row["img"] ?>">
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="row g-5">
            <div class="col-6 mb-3">
                <label class="form-label">帳號</label>
                <div class="border-bottom border-white pb-2"><?= htmlspecialchars($row["account"]) ?></div>
            </div>
            <div class="col-6 mb-3">
                <label class="form-label">信箱</label>
                <div class="border-bottom border-white pb-2"><?= htmlspecialchars($row["email"]) ?></div>
            </div>
        </div>

        <div class="row g-5">
            <div class="col-6 mb-3">
                <label class="form-label">姓名</label>
                <div class="border-bottom border-white pb-2"><?= htmlspecialchars($row["name"]) ?></div>
            </div>
            <div class="col-6 mb-3">
                <label class="form-label">電話</label>
                <div class="border-bottom border-white pb-2"><?= htmlspecialchars($row["phone"]) ?></div>
            </div>
        </div>

        <div class="row g-5">

            <div class="col-6 mb-3">
                <label class="form-label">生日</label>
                <div class="row">
                    <div class="col-4">
                        <div class="border-bottom border-white pb-2"><?= htmlspecialchars($row["year"]) ?> 年</div>
                    </div>
                    <div class="col-4">
                        <div class="border-bottom border-white pb-2"><?= htmlspecialchars($row["month"]) ?> 月</div>
                    </div>
                    <div class="col-4">
                        <div class="border-bottom border-white pb-2"><?= htmlspecialchars($row["date"]) ?> 日</div>
                    </div>
                </div>
            </div>
            <div class="col-6 mb-3">
                <label class="form-label">性別</label>
                <div class="border-bottom border-white pb-2">
                    <?php
                    foreach ($rowsGender as $gender) {
                        if ($row["gender_id"] == $gender["id"]) {
                            echo htmlspecialchars($gender["name"]);
                            break;
                        }
                    }
                    ?>
                </div>
            </div>
        </div>

        <div class="row g-5">
            <div class="col-6 mb-3">
                <label class="form-label">縣市</label>
                <div class="border-bottom border-white pb-2">
                    <?php
                    foreach ($rowsCity as $city) {
                        if ($row["city_id"] == $city["id"]) {
                            echo htmlspecialchars($city["name"]);
                            break;
                        }
                    }
                    ?>
                </div>
            </div>
            <div class="col-6 mb-3">
                <label class="form-label">更新時間</label>
                <div class="border-bottom border-white pb-2"><?= htmlspecialchars($row["update_at"]) ?></div>
            </div>
        </div>

        <div class="text-center mt-4">
            <a class="btn btn-b" href="./index.php"><i class="fa-solid fa-rotate-left me-2"></i>返回會員列表</a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-k6d4wzSIapyDyv1kpU366/PK5hCdSbCRGRCMv+eplOQJWyd1fbcAu9OCUj5zNLiq" crossorigin="anonymous">
        </script>
</body>

</html>