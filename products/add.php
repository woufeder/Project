<?php
require_once "./connect.php";
require_once "./utilities.php";
require_once "../vars.php";

$sqlMain = "SELECT * FROM `category_main`";
$sqlSub = "SELECT * FROM `category_sub`";
$sqlBrand = "SELECT * FROM `brands`";

try {
  $stmtMain = $pdo->prepare($sqlMain);
  $stmtMain->execute();
  $rowsMain = $stmtMain->fetchAll(PDO::FETCH_ASSOC);

  $stmtSub = $pdo->prepare($sqlSub);
  $stmtSub->execute();
  $rowsSub = $stmtSub->fetchAll(PDO::FETCH_ASSOC);

  $stmtBrand = $pdo->prepare($sqlBrand);
  $stmtBrand->execute();
  $rowsBrand = $stmtBrand->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
  alertAndBack("有東西出錯了");
  exit;
}

$cateNum = 1;
$pageTitle = "新增{$cate_ary[$cateNum]}";
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
  <link rel="stylesheet" href="./css/products.css">
</head>

<body>
  <div class="container px-3 mt-3">
    <div class="d-flex align-items-center">
      <h1><?= $pageTitle ?></h1>
      <a class="btn btn-add ms-auto " href="./add.php">建立類別</a>
    </div>
    <form action="./doAdd.php" method="post" enctype="multipart/form-data">
      <!-- <div class="input-group mb-2">
        <span class="input-group-text">品牌名稱</span>
        <input name="brand" type="text" class="form-control" placeholder="品牌名稱">
      </div> -->

      <div class="d-flex gap-2">
        <div class="input-group mb-2">
          <span class="input-group-text">主分類名稱</span>
          <select class="form-select" name="mainCateID">
            <option value selected disabled>請選擇</option>
            <?php foreach ($rowsMain as $rowMain): ?>
              <option value="<?= $rowMain["id"] ?>"><?= $rowMain["name"] ?></option>
            <?php endforeach ?>
          </select>
        </div>
        <div class="input-group mb-2">
          <span class="input-group-text">次分類名稱</span>
          <select class="form-select" name="subCateID">
            <option value selected disabled>請選擇</option>
          </select>
        </div>
        <div class="input-group mt-1 mb-2">
          <span class="input-group-text">品牌名稱</span>
          <select name="brand" class="form-select">
            <option value selected disabled>請選擇</option>
            <?php foreach ($rowsBrand as $rowBrand): ?>
              <option value="<?= $rowBrand["id"] ?>"><?= $rowBrand["name"] ?></option>
            <?php endforeach; ?>
          </select>
        </div>
      </div>

      <div class="input-group mb-2">
        <span class="input-group-text">商品名稱</span>
        <input name="name" type="text" class="form-control" placeholder="商品名稱">
      </div>
      <div class="input-group mb-2">
        <!-- 後續要是可以自己寫一張吧 -->
        <span class="input-group-text">上傳圖片</span>
        <input name="productImg[]" multiple type="file" class="form-control">
      </div>
      <div class="d-flex gap-2">
        <div class="input-group mb-2">
          <span class="input-group-text">商品型號</span>
          <input name="modal" type="text" class="form-control" placeholder="價格">
        </div>
        <div class="input-group mb-2">
          <span class="input-group-text">價格</span>
          <input name="price" type="text" class="form-control" placeholder="價格">
        </div>
      </div>
      <div class="input-group mb-2">
        <span class="input-group-text">商品介紹</span>
        <textarea name="intro" class="form-control" aria-label="With textarea"></textarea>
      </div>
      <div class="input-group mb-2">
        <span class="input-group-text">商品規格</span>
        <textarea name="spec" class="form-control" aria-label="With textarea"></textarea>
      </div>
      <div class="input-group mb-2">
        <span class="input-group-text">上傳商品介紹圖片</span>
        <input name="introImg[]" multiple type="file" class="form-control">
      </div>
      <div class="mt-3 text-end">
        <button type="submit" class="btn btn-send">送出</button>
        <a class="btn btn-cancel" href="./index.php">取消</a>
      </div>
    </form>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-k6d4wzSIapyDyv1kpU366/PK5hCdSbCRGRCMv+eplOQJWyd1fbcAu9OCUj5zNLiq"
    crossorigin="anonymous"></script>
  <script>
    let subs = [];
    subs = <?php echo json_encode($rowsSub); ?>;
    const selectMain = document.querySelector("select[name=mainCateID]");
    const selectSub = document.querySelector("select[name=subCateID]");
    selectMain.addEventListener("change", function () {
      setSubMenu(this.value)
    })

    function setSubMenu(id) {
      const ary = subs.filter(sub => sub.main_id == id);
      selectSub.innerHTML = "<option value selected disabled>請選擇</option>";
      ary.forEach(sub => {
        const option = document.createElement("option");
        option.value = sub.id;
        option.innerHTML = sub.name;
        selectSub.append(option);
      });
    }
  </script>
</body>

</html>