 <?php
require_once "./connect.php";
require_once "./utilities.php";
include "../vars.php";
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>新增會員</title>
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
    <link rel="stylesheet" href="./css/add.css">
</head>

<body>
    <div class="container w-50 p-4 rounded-4">
        <form action="./doAdd.php" method="post" enctype="multipart/form-data">
            <h1 class="border-bottom border-white pb-4 text-center">新增會員</h1>

            <div class="row">
                <div class="col-12">
                    <div id="avatar-preview" class="avatar-wrapper my-2 mx-auto border border-white border-3"></div>
                </div>
            </div>
            <div class="row">
                <div class="input-group w-50 mx-auto mb-3 col-12">
                    <input type="file" id="avatar-input" name="file" class="form-control" accept=".png,.jpg,.jpeg">
                </div>
            </div>

            <div class="row g-5">
                <div class="col-6 mb-3">
                    <label for="input-account" class="form-label">帳號</label>
                    <input type="text" class="form-control" id="input-account" name="account" placeholder="請輸入帳號"
                        required>
                </div>
                <div class="col-6 mb-3">
                    <label for="input-password" class="form-label">密碼</label>
                    <input type="password" class="form-control" id="input-password" name="password"
                        placeholder="請輸入密碼 (長度介於5字元至20字元之間)" minlength="5" maxlength="20"required>
                </div>
            </div>

            <div class="row g-5">
                <div class="col-6 mb-3">
                    <label for="input-name" class="form-label">姓名</label>
                    <input type="text" class="form-control" id="input-name" name="name" placeholder="請輸入姓名" required>
                </div>
                <div class="col-6 mb-3">
                    <label for="input-phone" class="form-label">手機號碼</label>
                    <input type="text" class="form-control" id="input-phone" name="phone"
                        placeholder="請輸入手機號碼 (0912345678)" pattern="^\d{10}$" title="手機號碼格式錯誤" maxlength="10" required>
                </div>
            </div>

            <div class="row g-5">
                <div class="col-6 mb-3">
                    <label for="input-email" class="form-label">信箱</label>
                    <input type="text" class="form-control" id="input-email" name="email" placeholder="請輸入信箱" required>
                </div>
                <div class="col-6 mb-3">
                    <label for="input-birthday" class="form-label">生日</label>
                    <div class="row">
                        <div class="col-4">
                            <input type="number" class="form-control" id="input-birthday" name="year" placeholder="西元年份" min="1900" max="2025" required>
                        </div>
                        <div class="col-4">
                            <input type="number" class="form-control" name="month" placeholder="月份" min="1" max="12" required>
                        </div>
                        <div class="col-4">
                            <input type="number" class="form-control" name="date" placeholder="日期" min="1" max="31" required>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-5">
                <div class="col-6 mb-3">
                    <label for="radio1" class="form-label d-block">性別</label>
                    <div class="d-flex align-items-center h-50">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="gender" id="radio1" value="1" required>
                            <label class="form-check-label" for="radio1">男性</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="gender" id="radio2" value="2">
                            <label class="form-check-label" for="radio2">女性</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="gender" id="radio3" value="3">
                            <label class="form-check-label" for="radio3">其他</label>
                        </div>
                    </div>
                </div>
                <div class="col-6 mb-3">
                    <label for="input-city" class="form-label">縣市</label>
                    <select id="input-city" class="form-select" name="city" required>
                        <option value="" selected disabled>請選擇</option>
                        <option value="1">台北市</option>
                        <option value="2">新北市</option>
                        <option value="3">桃園市</option>
                        <option value="4">台中市</option>
                        <option value="5">台南市</option>
                        <option value="6">高雄市</option>
                        <option value="7">基隆市</option>
                        <option value="8">新竹市</option>
                        <option value="9">嘉義市</option>
                        <option value="10">新竹縣</option>
                        <option value="11">苗栗縣</option>
                        <option value="12">彰化縣</option>
                        <option value="13">南投縣</option>
                        <option value="14">雲林縣</option>
                        <option value="15">嘉義縣</option>
                        <option value="16">屏東縣</option>
                        <option value="17">宜蘭縣</option>
                        <option value="18">花蓮縣</option>
                        <option value="19">台東縣</option>
                        <option value="20">澎湖縣</option>
                        <option value="21">金門縣</option>
                        <option value="22">連江縣</option>
                    </select>
                </div>
            </div>

            <div class="border-bottom border-white text-center mb-4"></div>

            <div class="text-center">
                <button type="submit" class="btn btn-b btn-send me-4"><i class="fa-solid fa-plus me-2"></i>新增</button>
                <a class="btn btn-d" href="./index.php"><i class="fa-regular fa-circle-xmark me-2"></i>取消</a>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-k6d4wzSIapyDyv1kpU366/PK5hCdSbCRGRCMv+eplOQJWyd1fbcAu9OCUj5zNLiq" crossorigin="anonymous">
        </script>
    <script>
        const input = document.querySelector("#avatar-input");
        const preview = document.querySelector("#avatar-preview");

        input.addEventListener('change', function () {
            const file = input.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    preview.innerHTML = `<img src="${e.target.result}" alt="avatar">`;
                };
                reader.readAsDataURL(file);
            }
        });
    </script>
</body>

</html>