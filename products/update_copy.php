<?php
require_once "./connect.php";
require_once "./utilities.php";
require_once "../vars.php";

$id = $_GET["id"];
$sql = "SELECT * FROM `products` WHERE `is_valid`=1 and  id=?";
$sqlMain = "SELECT * FROM `category_main`";
$sqlSub = "SELECT * FROM `category_sub`";
$sqlBrand = "SELECT * FROM `brands`";
$sqlImg = "SELECT * FROM products_imgs WHERE product_id = ?";
$sqlIntroImg = "SELECT * FROM products_intro_imgs WHERE product_id = ?";


try {
  $stmt = $pdo->prepare($sql);
  $stmt->execute([$id]);
  $row = $stmt->fetch(PDO::FETCH_ASSOC);

  $stmtMain = $pdo->prepare($sqlMain);
  $stmtMain->execute();
  $rowsMain = $stmtMain->fetchAll(PDO::FETCH_ASSOC);

  $stmtSub = $pdo->prepare($sqlSub);
  $stmtSub->execute();
  $rowsSub = $stmtSub->fetchAll(PDO::FETCH_ASSOC);

  $stmtBrand = $pdo->prepare($sqlBrand);
  $stmtBrand->execute();
  $rowsBrand = $stmtBrand->fetchAll(PDO::FETCH_ASSOC);

  $stmtImg = $pdo->prepare($sqlImg);
  $stmtImg->execute([$id]);
  $rowsImg = $stmtImg->fetchAll(PDO::FETCH_ASSOC);

  $stmtIntroImg = $pdo->prepare($sqlIntroImg);
  $stmtIntroImg->execute([$id]);
  $rowsIntroImg = $stmtIntroImg->fetchAll(PDO::FETCH_ASSOC);
  if (!$row) {
    alertGoTo("沒有這個商品", "./index.php");
  }
} catch (PDOException $e) {
  // alertAndBack("好像有東西不對勁");
  echo $e->getMessage();
  exit;
}


$cateNum = 1;
$pageTitle = "修改{$cate_ary[$cateNum]}";

?>

<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>PC 周邊商品後台管理系統|<?= $pageTitle ?></title>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.4/css/lightbox.min.css" rel="stylesheet">

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
    integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
    crossorigin="anonymous" referrerpolicy="no-referrer" />
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+TC:wght@100..900&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="./css/index.css">
</head>

<body class="bg-dark-subtle">
  <div class="container mt-3">
    <form action="./doUpdate.php" method="post" enctype="multipart/form-data">
      <h1><?= $pageTitle ?></h1>

      <input hidden type="text" name="id" value="<?= $row["id"] ?>">

      <div class="d-flex gap-2 mb-2">
        <div class="input-group ">
          <span class="input-group-text">主分類名稱</span>
          <select class="form-select" name="mainCateID">
            <?php foreach ($rowsMain as $rowMain): ?>
              <option value="<?= $rowMain["id"] ?>" <?= ($rowMain["id"] == $row["category_main_id"]) ? "selected" : "" ?>>
                <?= $rowMain["name"] ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="input-group ">
          <span class="input-group-text">次分類名稱</span>
          <select class="form-select" name="subCateID">
            <?php foreach ($rowsSub as $rowSub): ?>
              <option value="<?= $rowSub["id"] ?>" <?= ($rowSub["id"] == $row["category_sub_id"]) ? "selected" : "" ?>>
                <?= $rowSub["name"] ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="input-group mt-1">
          <span class="input-group-text">品牌名稱</span>
          <select name="brandID" class="form-select">
            <option value selected disabled>請選擇</option>
            <?php foreach ($rowsBrand as $rowBrand): ?>
              <option value="<?= $rowBrand["id"] ?>" <?= ($rowBrand["id"] == $row["brand_id"]) ? "selected" : "" ?>>
                <?= $rowBrand["name"] ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>
      </div>

      <!-- 商品圖片+上傳 -->
      <div class="d-flex h550px mb-2">
        <div class="w500px me-2">
          <div id="productCarousel" class="carousel slide pb-2">
            <div class="carousel-inner">

              <?php foreach ($rowsImg as $index => $rowImg): ?>
                <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
                  <a href="./uploads/<?= htmlspecialchars($rowImg["file"]) ?>" data-lightbox="product-carousel"
                    data-title="商品圖片 <?= $index + 1 ?>">
                    <img src="./uploads/<?= htmlspecialchars($rowImg["file"]) ?>" class="d-block w-100" alt="商品圖片">
                  </a>
                </div>
              <?php endforeach; ?>
            </div>

            <button class="carousel-control-prev" type="button" data-bs-target="#productCarousel" data-bs-slide="prev">
              <span class="carousel-control-prev-icon" aria-hidden="true"></span>
              <span class="visually-hidden">上一張</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#productCarousel" data-bs-slide="next">
              <span class="carousel-control-next-icon" aria-hidden="true"></span>
              <span class="visually-hidden">下一張</span>
            </button>

            <div class="carousel-indicators">
              <?php foreach ($rowsImg as $index => $img): ?>
                <button type="button" data-bs-target="#productCarousel" data-bs-slide-to="<?= $index ?>"
                  class="<?= $index === 0 ? 'active' : '' ?>" aria-current="<?= $index === 0 ? 'true' : 'false' ?>"
                  aria-label="Slide <?= $index + 1 ?>"></button>
              <?php endforeach; ?>
            </div>
          </div>

          <div class="input-group">
            <span class="input-group-text">上傳圖片</span>
            <input name="productImg[]" multiple type="file" class="form-control">
          </div>
        </div>

        <!-- 商品資訊區 -->
        <div class="d-flex flex-column justify-content-between flex-grow-1 gap-2 h550px">
          <div class="input-group">
            <span class="input-group-text">商品名稱</span>
            <input name="name" type="text" class="form-control" placeholder="商品名稱" value="<?= $row["name"] ?>">
          </div>
          <div class="input-group">
            <span class="input-group-text">商品型號</span>
            <input name="modal" type="text" class="form-control" placeholder="型號" value="<?= $row["modal"] ?>">
          </div>
          <div class="input-group">
            <span class="input-group-text">價格</span>
            <input name="price" type="text" class="form-control" placeholder="價格" value="<?= $row["price"] ?>">
          </div>
          <div class="input-group h400px">
            <span class="input-group-text">商品介紹</span>
            <textarea name="intro" class="form-control overflow-y-auto resize-none"
              aria-label="With textarea"><?= $row["intro"] ?></textarea>
          </div>
        </div>
      </div>

      <div class="d-flex gap-2">
        <div class="d-flex flex-column w500px">
          <div class="input-group mb-2">
            <span class="input-group-text">上傳商品介紹圖片</span>
            <input name="introImg[]" multiple type="file" class="form-control">
          </div>
          <div class="d-flex gap-2 w500px flex-wrap">
            <?php foreach ($rowsIntroImg as $index => $rowIntroImg): ?>
              <div>
                <a href="./uploads/<?= htmlspecialchars($rowIntroImg["file"]) ?>" data-lightbox="gallery"
                  data-title="商品介紹圖 <?= $index + 1 ?>">
                  <img src="./uploads/<?= htmlspecialchars($rowIntroImg["file"]) ?>" class="wh100px" alt="商品介紹圖片">
                </a>
              </div>
            <?php endforeach; ?>
          </div>
        </div>
        <div class="input-group mb-2 h400px">
          <span class="input-group-text">商品規格</span>
          <textarea name="spec" class="form-control overflow-y-auto resize-none"
            aria-label="With textarea"><?= $row["spec"] ?></textarea>
        </div>
      </div>

      <div class="mt-3 text-end">
        <button type="submit" class="btn btn-send">送出</button>
        <a class="btn btn-cancel" href="./index.php">取消修改</a>
      </div>
    </form>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-k6d4wzSIapyDyv1kpU366/PK5hCdSbCRGRCMv+eplOQJWyd1fbcAu9OCUj5zNLiq"
    crossorigin="anonymous"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.4/js/lightbox.min.js"></script>

  <script>
    let subs = <?php echo json_encode($rowsSub); ?>;
    const selectMain = document.querySelector("select[name=mainCateID]");
    const selectSub = document.querySelector("select[name=subCateID]");

    selectMain.addEventListener("change", function () {
      setSubMenu(this.value);
    });

    function setSubMenu(id) {
      const selectedSubID = <?= $row["category_sub_id"] ?>;
      const ary = subs.filter(sub => sub.main_id == id);
      selectSub.innerHTML = "<option value selected disabled>請選擇</option>";
      ary.forEach(sub => {
        const option = document.createElement("option");
        option.value = sub.id;
        option.innerHTML = sub.name;
        if (sub.id == selectedSubID) {
          option.selected = true;
        }
        selectSub.append(option);
      });
    }

    document.addEventListener("DOMContentLoaded", function () {
      setSubMenu(selectMain.value);
    });


  </script>
</body>

</html>