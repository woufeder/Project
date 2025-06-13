<?php
require_once "./connect.php";
require_once "./utilities.php";
include "../template_btn.php";
include "../vars.php";

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
          <a class="btn btn-back ms-auto px-3" href="../products/index.php">
            <i class="fa-solid fa-backward"> 回到商品列表</i>
          </a>
        </div>

        <div class="list-area  p-5">

          <form action="./doAdd.php" method="post" enctype="multipart/form-data">

            <!-- 上面三條 -->
            <div class="d-flex gap-2 mb-2">
              <div class="input-group">
                <span class="input-group-text">主分類名稱</span>
                <select class="form-select" name="mainCateID">
                  <option value selected disabled>請選擇</option>
                  <?php foreach ($rowsMain as $rowMain): ?>
                    <option value="<?= $rowMain["id"] ?>"><?= $rowMain["name"] ?></option>
                  <?php endforeach ?>
                </select>
              </div>
              <div class="input-group">
                <span class="input-group-text">次分類名稱</span>
                <select class="form-select" name="subCateID">
                  <option value selected disabled>請選擇</option>
                </select>
              </div>
              <div class="input-group">
                <span class="input-group-text">品牌名稱</span>
                <select name="brand" class="form-select">
                  <option value selected disabled>請選擇</option>
                  <?php foreach ($rowsBrand as $rowBrand): ?>
                    <option value="<?= $rowBrand["id"] ?>"><?= $rowBrand["name"] ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>
            <div class="d-flex">
              <!-- 左邊 -->
              <div class="d-flex flex-column align-items-center flex1">
                <div id="preview1" class="carousel slide w-100 pe-1 mb-3" data-bs-ride="carousel">
                  <div class="carousel-inner ratio ratio-16x9 bg-secondary" id="preview-img">
                  </div>
                  <div class="carousel-indicators" id="preview-indicators"></div>
                  <button class="carousel-control-prev" type="button" data-bs-target="#preview1" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon"></span>
                  </button>
                  <button class="carousel-control-next" type="button" data-bs-target="#preview1" data-bs-slide="next">
                    <span class="carousel-control-next-icon"></span>
                  </button>
                </div>

                <div class="d-flex gap-2 w-100 ">
                  <div class="input-group mb-2 fakeupload1">
                    <span class="input-group-text" id="fakeupload1">
                      點擊上傳商品圖片
                    </span>
                    <input name="productImg[]" multiple type="file" id="upload-img" class="form-control" hidden>
                  </div>

                  <div class="input-group mb-2">
                    <span class="input-group-text " id="fakeupload2">
                      點擊上傳介紹圖片
                    </span>
                    <input name="introImg[]" multiple type="file" id="upload-introimg" class="form-control" hidden>
                  </div>
                </div>

                <div id="preview2" class="carousel slide w-100 pe-1" data-bs-ride="carousel">
                  <div class="carousel-inner ratio ratio-16x9 bg-secondary" id="preview-intro">
                  </div>
                  <div class="carousel-indicators" id="preview-intro-indicators"></div>
                  <button class="carousel-control-prev" type="button" data-bs-target="#preview2" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon"></span>
                  </button>
                  <button class="carousel-control-next" type="button" data-bs-target="#preview2" data-bs-slide="next">
                    <span class="carousel-control-next-icon"></span>
                  </button>
                </div>

              </div>

              <div class="d-flex flex-column flex2 ps-1 ">
                <div class="input-group mb-2">
                  <span class="input-group-text">商品名稱</span>
                  <input name="name" type="text" class="form-control" placeholder="商品名稱">
                </div>
                <div class="d-flex gap-2">
                  <div class="input-group mb-2">
                    <span class="input-group-text">商品型號</span>
                    <input name="modal" type="text" class="form-control" placeholder="請填寫商品型號">
                  </div>
                  <div class="input-group mb-2">
                    <span class="input-group-text">價格</span>
                    <input name="price" type="text" class="form-control" placeholder="請填寫價格">
                  </div>
                </div>
                <div class="input-group mb-2 flex1">
                  <span class="intro input-group-text h-">商品介紹</span>
                  <textarea name="intro" class="form-control resize-none" aria-label="With textarea"></textarea>
                </div>
                <div class="input-group flex1">
                  <span class="spec input-group-text">商品規格</span>
                  <textarea name="spec" class="form-control resize-none" aria-label="With textarea"></textarea>
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

    const uploadImg = document.getElementById('upload-img');
    const uploadIntro = document.getElementById('upload-introimg');
    const fakeUpload1 = document.querySelector("#fakeupload1");
    const fakeUpload2 = document.querySelector("#fakeupload2");
    const previewImg = document.querySelector("#preview-img");
    const previewIntro = document.querySelector("#preview-intro");

    fakeUpload1.addEventListener('click', () => {
      uploadImg.click();
    });
    fakeUpload2.addEventListener('click', () => {
      uploadIntro.click();
    });

    uploadImg.addEventListener('change', () => {
      const count = uploadImg.files.length;

      if (count > 0) {
        fakeUpload1.textContent = `已選擇 ${count} 張圖片`;
      }

      previewImg.innerHTML = ''; // 清空預覽
      const files = uploadImg.files;

      // ⭐ 清空指標
      const indicatorContainer = document.getElementById('preview-indicators');
      indicatorContainer.innerHTML = '';

      Array.from(files).forEach((file, index) => {
        // 加入圖片 carousel item
        const reader = new FileReader();
        reader.onload = function (e) {
          const carouselItem = document.createElement('div');
          carouselItem.className = 'carousel-item' + (index === 0 ? ' active' : '');
          carouselItem.innerHTML = `<img src="${e.target.result}" class="d-block w-100 h-100 object-fit-contain" alt="預覽圖片">`;
          previewImg.appendChild(carouselItem);
        };
        reader.readAsDataURL(file);

        // ⭐ 同步新增 indicator
        const button = document.createElement('button');
        button.type = 'button';
        button.setAttribute('data-bs-target', '#preview1');
        button.setAttribute('data-bs-slide-to', index);
        button.setAttribute('aria-label', `Slide ${index + 1}`);
        if (index === 0) {
          button.classList.add('active');
          button.setAttribute('aria-current', 'true');
        }
        indicatorContainer.appendChild(button);
      });
    });


uploadIntro.addEventListener('change', () => {
  const count = uploadIntro.files.length;

  if (count > 0) {
    fakeUpload2.textContent = `已選擇 ${count} 張圖片`;
  }

  previewIntro.innerHTML = ''; // 清空預覽
  const files = uploadIntro.files;

  // ⭐ 清空指標
  const indicatorContainer = document.getElementById('preview-intro-indicators');
  indicatorContainer.innerHTML = '';

  Array.from(files).forEach((file, index) => {
    // 加入圖片 carousel item
    const reader = new FileReader();
    reader.onload = function (e) {
      const carouselItem = document.createElement('div');
      carouselItem.className = 'carousel-item' + (index === 0 ? ' active' : '');
      carouselItem.innerHTML = `<img src="${e.target.result}" class="d-block w-100 h-100 object-fit-contain" alt="預覽圖片">`;
      previewIntro.appendChild(carouselItem);
    };
    reader.readAsDataURL(file);

    // ⭐ 同步新增 indicator
    const button = document.createElement('button');
    button.type = 'button';
    button.setAttribute('data-bs-target', '#preview2');
    button.setAttribute('data-bs-slide-to', index);
    button.setAttribute('aria-label', `Slide ${index + 1}`);
    if (index === 0) {
      button.classList.add('active');
      button.setAttribute('aria-current', 'true');
    }
    indicatorContainer.appendChild(button);
  });
});


  </script>

  </script>
</body>

</html>