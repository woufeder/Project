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
    alertGoTo("沒有這個商品", "./index.php");
  }
} catch (PDOException $e) {
  // alertAndBack("好像有東西不對勁");
  echo $e->getMessage();
  exit;
}


$cateNum = 1;
$pageTitle = "檢視{$cate_ary[$cateNum]}";
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
  <link rel="stylesheet" href="../products/css/look.css">
</head>

<body>

  <div class="dashboard">

    <?php include '../template_sidebar.php'; ?>

    <div class="main-container overflow-auto">

      <?php include '../template_header.php'; ?>
      <main>

        <div class="container">

          <div class="d-flex align-items-center mb-4 px-2">
            <a class="btn btn-back " href="../products/index.php">
              <i class="fa-solid fa-backward"> 回到商品列表</i>
            </a>
            <a class="btn btn-sm btn-update ms-auto px-3" href="./update.php?id=<?= $row["id"] ?>">
              <i class="fas fa-pen"> 修改商品</i>
            </a>
          </div>


          <div class="list-area p-5">
            <input hidden type="text" name="id" value="<?= $row["id"] ?>">

            <!-- 左邊圖片 -->
            <div class="d-flex gap-5">
              <div class="d-flex flex-column align-items-center flex1">
                <div id="productCarousel" class="carousel slide w-100 pe-1 mb-3">
                  <div class="carousel-inner ratio ratio-4x3 bg-dark border border-dark-subtle">

                    <?php foreach ($rowsImg as $index => $rowImg): ?>
                      <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
                        <img src="./uploads/<?= htmlspecialchars($rowImg["file"]) ?>"
                          class="d-block w-100 h-100 object-fit-contain" alt="商品圖片">
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




              </div>

              <!-- 商品名和品牌 -->
              <div class="d-flex flex-column justify-content-evenly flex1">
                <div class="w-100">
                  <?php foreach ($rowsMain as $rowMain): ?>
                    <?php if ($rowMain["id"] == $row["category_main_id"]): ?>
                      <!-- 或換成 <h6> -->

                      <?php foreach ($rowsSub as $rowSub): ?>
                        <?php if ($rowSub["id"] == $row["category_sub_id"]): ?>
                          <h6><?= $rowMain["name"] ?> ＞ <?= $rowSub["name"] ?></h6>
                          <!-- 或換成 <h6> -->
                        <?php endif; ?>
                      <?php endforeach; ?>

                    <?php endif; ?>
                  <?php endforeach; ?>
                </div>

                <h4 class="p-name w-100"><?= $row["name"] ?></h4>

                <?php foreach ($rowsBrand as $rowBrand): ?>
                  <?php if ($rowBrand["id"] == $row["brand_id"]): ?>
                    <h6 class="p-brand w-100"><?= $rowBrand["name"] ?></h6>
                  <?php endif; ?>
                <?php endforeach; ?>

                <div class="p-modal d-flex flex-row align-items-center gap-5">
                  <h6 class="tit">商品型號</h6>
                  <p><?= $row["modal"] ?></p>
                </div>

                <div class="price">
                  <h4>售價 <?= $row["price"] ?> 元</h4>
                </div>
              </div>
            </div>


            <div class="">
              <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                  <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home"
                    type="button" role="tab" aria-controls="home" aria-selected="true">商品介紹</button>
                </li>
                <li class="nav-item" role="presentation">
                  <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile" type="button"
                    role="tab" aria-controls="profile" aria-selected="false">商品規格</button>
                </li>
              </ul>

              <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                  <div id="intro" class="mt-2 preserve-format"><?= htmlspecialchars($row["intro"]) ?></div>
                  <?php foreach ($rowsIntroImg as $index => $rowIntroImg): ?>
                    <div>
                      <img src="./uploads/<?= htmlspecialchars($rowIntroImg["file"]) ?>" class="wh100px mt-2"
                        alt="商品介紹圖片">
                    </div>
                  <?php endforeach; ?>
                </div>
                <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                  <div id="spec" class=" mt-2 preserve-format"><?= htmlspecialchars($row["spec"]) ?></div>

                </div>
              </div>
            </div>

          </div>
        </div>
    </div>
    <!-- <div id="productCarousel2" class="carousel slide w-100 pe-1">
                <div class="carousel-inner ratio ratio-16x9 bg-secondary">

                  <?php foreach ($rowsIntroImg as $index => $rowIntroImg): ?>
                    <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
                      <img src="./uploads/<?= htmlspecialchars($rowIntroImg["file"]) ?>"
                        class="d-block w-100 h-100 object-fit-contain" alt="商品介紹圖片">
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
              </div> -->
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

    const btnDels = document.querySelectorAll(".btn-del");

    btnDels.forEach((btn) => {
      btn.addEventListener("click", doConfirm);
    });

    function doConfirm(e) {
      const btn = e.currentTarget; // ✅ 改這裡
      //通常window可省略
      if (window.confirm("確定要下架商品嗎？")) {
        window.location.href = `./doDelete.php?id=${btn.dataset.id}`
      }
    };

  </script>
</body>

</html>