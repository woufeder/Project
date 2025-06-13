<?php
require_once "./connect.php";
require_once "./utilities.php";
include "../template_btn.php";
include "../vars.php";

$id = $_GET["id"];
$sql = "SELECT * FROM `products` WHERE `is_valid`= 0 and  id=?";
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
    alertGoTo("該商品已為上架狀態，請從商品列表修改", "./index.php");
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
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
    integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
    crossorigin="anonymous" referrerpolicy="no-referrer" />
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+TC:wght@100..900&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="../css/main.css">
  <link rel="stylesheet" href="../products/css/add_up.css">
</head>

<body>

  <div class="dashboard">

    <?php include '../template_sidebar.php'; ?>

    <div class="main-container overflow-auto">

      <?php include '../template_header.php'; ?>
      <main>

        <div class="d-flex align-items-center mb-4 px-2">
          <a class="btn btn-back " href="../products/index.php">
            <i class="fa-solid fa-backward"> 回到商品列表</i>
          </a>
          <a class="btn btn-sm btn-return ms-auto px-3" data-id="<?= $row["id"] ?>">
            <i class="fa-solid fa-rotate-left"> 重新上架商品</i>
          </a>
        </div>

        <div class="list-area  p-5">

          <form action="./doUpdate.php" method="post" enctype="multipart/form-data">
            <input hidden type="text" name="id" value="<?= $row["id"] ?>">

            <!-- 上面三條 -->
            <div class="d-flex gap-2 mb-2">
              <div class="input-group">
                <span class="input-group-text">主分類名稱</span>
                <select class="form-select" name="mainCateID">
                  <?php foreach ($rowsMain as $rowMain): ?>
                    <option value="<?= $rowMain["id"] ?>" <?= ($rowMain["id"] == $row["category_main_id"]) ? "selected" : "" ?>>
                      <?= $rowMain["name"] ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>
              <div class="input-group">
                <span class="input-group-text">次分類名稱</span>
                <select class="form-select" name="subCateID">
                  <?php foreach ($rowsSub as $rowSub): ?>
                    <option value="<?= $rowSub["id"] ?>" <?= ($rowSub["id"] == $row["category_sub_id"]) ? "selected" : "" ?>>
                      <?= $rowSub["name"] ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>
              <div class="input-group">
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

            <div class="d-flex">
              <!-- 左邊 -->
              <div class="d-flex flex-column align-items-center flex1">
                <div id="productCarousel" class="carousel slide w-100 pe-1 mb-3">
                  <div class="carousel-inner ratio ratio-16x9 bg-secondary">

                    <?php foreach ($rowsImg as $index => $rowImg): ?>
                      <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
                        <a href="./uploads/<?= htmlspecialchars($rowImg["file"]) ?>" data-lightbox="product-carousel"
                          data-title="商品圖片 <?= $index + 1 ?>">
                          <img src="./uploads/<?= htmlspecialchars($rowImg["file"]) ?>"
                            class="d-block w-100 h-100 object-fit-contain" alt="商品圖片">
                        </a>
                      </div>
                    <?php endforeach; ?>
                  </div>

                  <div class="carousel-indicators">
                    <?php foreach ($rowsImg as $index => $img): ?>
                      <button type="button" data-bs-target="#productCarousel" data-bs-slide-to="<?= $index ?>"
                        class="<?= $index === 0 ? 'active' : '' ?>" aria-current="<?= $index === 0 ? 'true' : 'false' ?>"
                        aria-label="Slide <?= $index + 1 ?>"></button>
                    <?php endforeach; ?>
                  </div>

                  <button class="carousel-control-prev" type="button" data-bs-target="#productCarousel"
                    data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">上一張</span>
                  </button>
                  <button class="carousel-control-next" type="button" data-bs-target="#productCarousel"
                    data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">下一張</span>
                  </button>
                </div>

                <div class="d-flex gap-2 w-100 ">
                  <div class="input-group mb-2 fakeupload1">
                    <span class="input-group-text" id="fakeupload1">
                      更新商品圖片
                    </span>
                    <input name="productImg[]" multiple type="file" class="form-control" hidden>
                  </div>

                  <div class="input-group mb-2 fakeupload2">
                    <span class="input-group-text" id="fakeupload2">
                      更新介紹圖片
                    </span>
                    <input name="introImg[]" multiple type="file" class="form-control" hidden>
                  </div>
                </div>

                <div id="productCarousel2" class="carousel slide w-100 pe-1">
                  <div class="carousel-inner ratio ratio-16x9 bg-secondary">

                    <?php foreach ($rowsIntroImg as $index => $rowIntroImg): ?>
                      <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
                        <a href="./uploads/<?= htmlspecialchars($rowIntroImg["file"]) ?>" data-lightbox="product-carousel"
                          data-title="商品介紹圖片 <?= $index + 1 ?>">
                          <img src="./uploads/<?= htmlspecialchars($rowIntroImg["file"]) ?>"
                            class="d-block w-100 h-100 object-fit-contain" alt="商品介紹圖片">
                        </a>
                      </div>
                    <?php endforeach; ?>
                  </div>

                  <div class="carousel-indicators">
                    <?php foreach ($rowsIntroImg as $index => $rowIntroImg): ?>
                      <button type="button" data-bs-target="#productCarousel2" data-bs-slide-to="<?= $index ?>"
                        class="<?= $index === 0 ? 'active' : '' ?>" aria-current="<?= $index === 0 ? 'true' : 'false' ?>"
                        aria-label="Slide <?= $index + 1 ?>"></button>
                    <?php endforeach; ?>
                  </div>

                  <button class="carousel-control-prev" type="button" data-bs-target="#productCarousel2"
                    data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">上一張</span>
                  </button>
                  <button class="carousel-control-next" type="button" data-bs-target="#productCarousel2"
                    data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">下一張</span>
                  </button>
                </div>


              </div>

              <div class="d-flex flex-column flex2 ps-1 ">
                <div class="input-group mb-2">
                  <span class="input-group-text">商品名稱</span>
                  <input name="name" type="text" class="form-control" placeholder="商品名稱" value="<?= $row["name"] ?>">
                </div>
                <div class="d-flex gap-2">
                  <div class="input-group mb-2">
                    <span class="input-group-text">商品型號</span>
                    <input name="modal" type="text" class="form-control" placeholder="型號" value="<?= $row["modal"] ?>">
                  </div>
                  <div class="input-group mb-2">
                    <span class="input-group-text">價格</span>
                    <input name="price" type="text" class="form-control" placeholder="價格" value="<?= $row["price"] ?>">
                  </div>
                </div>
                <div class="input-group mb-2 flex1">
                  <span class="intro input-group-text ">商品介紹</span>
                  <textarea name="intro" class="form-control overflow-y-auto resize-none"
                    aria-label="With textarea"><?= $row["intro"] ?></textarea>
                </div>
                <div class="input-group flex1">
                  <span class="spec input-group-text">商品規格</span>
                  <textarea name="spec" class="form-control overflow-y-auto resize-none"
                    aria-label="With textarea"><?= $row["spec"] ?></textarea>
                </div>
              </div>

            </div>

            <div class="mt-2 text-end">
              <button type="submit" class="btn btn-send">
                <i class="fas fa-check"></i>
                送出</button>
              <a class="btn btn-cancel" href="./index.php">
                <i class="fas fa-times"></i>
                取消</a>
            </div>
          </form>
        </div>

      </main>
    </div>
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

    document.querySelector('.fakeupload1').addEventListener('click', () => {
      document.querySelector('.fakeupload1 input[type="file"]').click();
    });

    document.querySelector('.fakeupload2').addEventListener('click', () => {
      document.querySelector('.fakeupload2 input[type="file"]').click();
    });

    const btnReturn = document.querySelectorAll(".btn-return");
    btnReturn.forEach((btn) => {
      btn.addEventListener("click", doConfirm);
    });

    function doConfirm(e) {
      const btn = e.currentTarget; // ✅ 改這裡
      //通常window可省略
      if (window.confirm("確定要重新上架商品嗎？")) {
        window.location.href = `./doUndo.php?id=${btn.dataset.id}`
      }
    };

  </script>
</body>

</html>