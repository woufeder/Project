<?php
include "../template_btn.php";
require_once "./Utilities.php";
include "../vars.php";
require_once "./connect.php";

$cateNum = 2;
$pageTitle = "{$cate_ary[$cateNum]}列表";
$referer = $_SERVER["HTTP_REFERER"] ?? "";
$from = $_GET["from"] ?? "index";
$redirect_query = $_SERVER["QUERY_STRING"] ?? "";



if (strpos($referer, "indexList.php") !== false) {
    $from = "indexList";
} elseif (strpos($referer, "expiredList.php") !== false) {
    $from = "expiredList";
}

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
        body {
            background-color: #f0f4f8;
        }

        h1 {
            color: #3F72AF;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .card {
            background-color: #ffffff;
            border-radius: 15px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            padding: 30px;
        }

        label {
            font-weight: 500;
        }

        .btn-info {
            background-color: #89b9f4;
            border-color: #3F72AF;
        }

        .btn-info:hover {
            background-color: rgb(191, 220, 255);
            border-color: #5d89c2;
        }

        .btn-type {
            border: 2px solid #3F72AF;
            background-color: white;
            color: #3F72AF;
            padding: 8px 18px;
            border-radius: 8px;
            font-weight: 600;
            transition: 0.2s;
        }

        .btn-type:hover {
            background-color: rgb(63, 113, 175);
            color: white;
        }

        .btn-type.active {
            background-color: #3F72AF;
            color: white;
        }

        /* img {
            width: 400px;
            aspect-ratio: 4/3;
            object-fit: contain;
            object-position: 0 0;
        } */
    </style>
</head>

<body>
    <div class="dashboard">
        <?php include '../template_sidebar.php'; ?>
        <div class="main-container">
            <?php include '../template_header.php'; ?>
            <main>
                <div class="container mt-5" style="max-width: 600px;">
                    <h1 class="text-center">新增優惠券</h1>
                    <div class="card">
                        <form action="./doAdd.php" method="post" enctype="multipart/form-data">
                            <?php
                            $from = $_GET["from"] ?? "index";
                            $redirect_query = $_SERVER["QUERY_STRING"] ?? "";
                            ?>
                            <input type="hidden" name="from" value="<?= htmlspecialchars($from) ?>">
                            <input type="hidden" name="redirect_query" value="<?= htmlspecialchars($redirect_query) ?>">
                            <div class="mb-3">
                                <label for="code" class="form-label">優惠碼</label>
                                <input required name="code" type="text" class="form-control" id="code">
                            </div>

                            <div class="mb-3">
                                <label for="desc" class="form-label">敘述</label>
                                <input required name="desc" type="text" class="form-control" id="desc">
                            </div>

                            <div class="mb-3">
                                <label class="form-label d-block">類型</label>
                                <div class="type-buttons d-flex gap-3">
                                    <button type="button" class="btn-type" data-value="1">百分比折扣</button>
                                    <button type="button" class="btn-type" data-value="0">固定金額折扣</button>
                                </div>
                                <input type="hidden" name="type" id="type">
                            </div>

                            <div class="mb-3">
                                <label for="value" class="form-label">折扣數值</label>
                                <input required name="value" type="number" step="0.01" class="form-control" id="value">
                            </div>

                            <div class="mb-3">
                                <label for="min" class="form-label">最低消費</label>
                                <input required name="min" type="number" step="0.01" class="form-control" id="min">
                            </div>

                            <div class="row mb-3 align-items-end">
                                <div class="col">
                                    <label class="form-label">開始日期</label>
                                    <input type="date" name="start_at" value="<?= substr($row["start_at"], 0, 10) ?>"
                                        class="form-control" required>
                                </div>
                                <div class="col-auto">
                                    <span class="fw-bold">~</span>
                                </div>
                                <div class="col">
                                    <label class="form-label">結束日期</label>
                                    <input type="date" name="expires_at"
                                        value="<?= substr($row["expires_at"], 0, 10) ?>" class="form-control" required>
                                </div>
                            </div>

                            <!-- 是否啟用 -->
                            <div class="mb-3 form-check form-switch">
                                <input type="checkbox" class="form-check-input" id="is_active" name="is_active"
                                    value="1" checked>
                                <label class="form-check-label" for="is_active">啟用此優惠券</label>
                            </div>
                            <div class="text-end mt-3">
                                <button type="submit" class="btn btn-info">送出</button>

                                <a href="<?= $_SERVER["HTTP_REFERER"] ?? './index.php' ?>"
                                    class="btn btn-secondary">取消</a>
                            </div>
                        </form>
                    </div> <!-- end card -->
                </div> <!-- end container -->
            </main>
        </div>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const buttons = document.querySelectorAll('[data-value]');
            const hiddenInput = document.getElementById('type');
            const form = document.getElementById('couponForm');

            buttons.forEach(btn => {
                btn.addEventListener('click', () => {
                    hiddenInput.value = btn.dataset.value;
                    buttons.forEach(b => b.classList.remove('active'));
                    btn.classList.add('active');
                });
            });

            // 表單送出驗證 hidden input
            form.addEventListener('submit', (e) => {
                if (hiddenInput.value === "") {
                    e.preventDefault();
                    alert("請選擇類型！");
                }
            });
        });
    </script>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-k6d4wzSIapyDyv1kpU366/PK5hCdSbCRGRCMv+eplOQJWyd1fbcAu9OCUj5zNLiq"
        crossorigin="anonymous"></script>
</body>

</html>