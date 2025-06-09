<?php
require_once "./connect.php";
require_once "./utilities.php";
include "../vars.php";

$cid = intval($_GET["cid"] ?? 0);
$branID = intval($_GET["brand_id"] ?? 0);

$cateSubSQL = "";
$brandSQL = "";
$values = [];

if ($cid != 0) {
  $cateSubSQL = "`category_sub_id` = :cid AND ";
  $values["cid"] = $cid;
}

if ($branID != 0) {
  $brandSQL = "`brand_id` = :brand_id AND ";
  $values["brand_id"] = $branID;
}

$search = $_GET["search"] ?? "";
$searchSQL = "";
if ($search !== "") {
  $searchSQL = "products.name LIKE :search AND ";
  $values["search"] = "%$search%";
}


$sort = $_GET["sort"] ?? "";
$order = $_GET["order"] ?? "asc";

$orderSQL = "";
$allowedSortFields = ["price"];

if (in_array($sort, $allowedSortFields)) {
  $order = strtolower($order) === "desc" ? "desc" : "asc";
  $orderSQL = " ORDER BY products.$sort $order";
}

$queryParams = $_GET;
$queryParams["sort"] = "price";
$queryParams["order"] = ($sort == "price" && $order == "asc") ? "desc" : "asc";
$sortLink = "?" . http_build_query($queryParams);

$sortArrow = "fa-solid fa-sort";
if ($sort == "price" && $order == "asc") {
  $sortArrow = "fa-solid fa-sort-up";
} elseif ($sort == "price" && $order == "desc") {
  $sortArrow = "fa-solid fa-sort-down";
}
;

$cateNum = 1;
$pageTitle = "已下架{$cate_ary[$cateNum]}列表";

//分頁定番
$perPage = 25;
$page = intval($_GET["page"] ?? 1);
$pageStart = ($page - 1) * $perPage;

$sql = "SELECT 
            products.*,
            category_main.name AS category_main_name,
            category_sub.name AS category_sub_name,
            brands.name AS brand_name
        FROM products
        JOIN category_main ON products.category_main_id = category_main.id
        JOIN category_sub ON products.category_sub_id = category_sub.id
        JOIN brands ON products.brand_id = brands.id
        WHERE $cateSubSQL $brandSQL $searchSQL products.is_valid = 0 $orderSQL
        LIMIT $perPage OFFSET $pageStart";

$sqlAll = "SELECT * FROM `products` WHERE $cateSubSQL $brandSQL $searchSQL `is_valid`=0 $orderSQL ";
$sqlMain = "SELECT * FROM `category_main`";
$sqlSub = "SELECT * FROM `category_sub`";
$sqlBrand = "SELECT * FROM `brands`";

try {
  $stmt = $pdo->prepare($sql);
  $stmt->execute($values);
  $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

  $stmtAll = $pdo->prepare($sqlAll);
  $stmtAll->execute($values);
  $totalCount = $stmtAll->rowCount();

  $stmtMain = $pdo->prepare($sqlMain);
  $stmtMain->execute();
  $rowsMain = $stmtMain->fetchAll(PDO::FETCH_ASSOC);

  $stmtSub = $pdo->prepare($sqlSub);
  $stmtSub->execute();
  $rowsSub = $stmtSub->fetchAll(PDO::FETCH_ASSOC);

  $stmtBrand = $pdo->prepare($sqlBrand);
  $stmtBrand->execute();
  $rowsBrand = $stmtBrand->fetchAll();
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
    <div class="d-flex align-items-baseline">
      <i class="fa-solid fa-cube me-3 fs-2"></i>
      <h1><?= $pageTitle ?></h1>
    </div>
    <div class="my-2 d-flex">
      <span class="me-auto">>>目前共<?= $totalCount ?>筆資料</span>
    </div>

    <div class="mb-2">
      <div class="input-group mb-3">
        <span class="input-group-text">母分類</span>
        <select class="form-select" name="mainCateID">
          <option value selected disabled>請選擇</option>
          <?php foreach ($rowsMain as $rowMain): ?>
            <option value="<?= $rowMain["id"] ?>"><?= $rowMain["name"] ?></option>
          <?php endforeach ?>
        </select>
      </div>

      <div class="input-group mb-3">
        <span class="input-group-text">子分類</span>
        <select class="form-select" name="subCateID">
          <option value selected disabled>請選擇</option>
        </select>
      </div>

      <div class="input-group mb-3">
        <span class="input-group-text">品牌</span>
        <select name="brand" class="form-select">
          <option value selected disabled>請選擇</option>
          <?php foreach ($rowsBrand as $rowBrand): ?>
            <option value="<?= $rowBrand["id"] ?>"><?= $rowBrand["name"] ?></option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="input-group mb-3">
        <span class="input-group-text">品名</span>
        <input name="search" type="text" class="form-control" placeholder="請輸入關鍵字搜尋">
      </div>

      <div class="btn btn-primary btn-sm btn-search mb-2">送出搜尋</div>

      <div class="list px-3">
        <div class="id">#</div>
        <!-- <div class="img">圖片</div> -->
        <div class="category_main">母分類</div>
        <div class="category_sub">子分類</div>
        <div class="brand">品牌</div>
        <div class="name">品名</div>
        <div class="price">
          <a class="sorts" href="<?= $sortLink ?>">價格
            <i class="<?= $sortArrow ?>"></i>
          </a>
        </div>
        <div class="control">操作</div>
      </div>

      <?php foreach ($rows as $index => $row): ?>
        <div class="list bg-light text-dark px-3">
          <div class="id"><?= $index + 1 + ($page - 1) * $perPage ?></div>
          <!-- 圖片的row -->

          <div class="category_main"><?= $row["category_main_name"] ?></div>
          <div class="category_sub"><?= $row["category_sub_name"] ?></div>
          <div class="brand"><?= $row["brand_name"] ?></div>
          <div class="name"><?= $row["name"] ?></div>
          <div class="price"><?= $row["price"] ?></div>
          <div class="control">
            <button class="btn btn-sm btn-del" data-id="<?= $row["id"] ?>">重新上架</button>
            <a class="btn btn-sm btn-update" href="./update.php?id=<?= $row["id"] ?>">修改</a>
          </div>
        </div>
      <?php endforeach ?>

      <ul class="pagination pagination-sm justify-content-center pt-2">
        <?php for ($i = 1; $i <= $totalPage; $i++): ?>
          <li class="page-item <?= $page == $i ? "active" : "" ?>">
            <?php

            $link = "?page={$i}";
            // if ($cid > 0) $link.= "$cid={$cid}";
            ?>
            <a class="page-link" href="<?= $link ?>"><?= $i ?></a>
          </li>
        <?php endfor; ?>
      </ul>

    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"
      integrity="sha384-k6d4wzSIapyDyv1kpU366/PK5hCdSbCRGRCMv+eplOQJWyd1fbcAu9OCUj5zNLiq"
      crossorigin="anonymous"></script>

    <script>
      const btnDels = document.querySelectorAll(".btn-del");
      const btnSearch = document.querySelector(".btn-search");
      const cid = document.querySelector("select[name=subCateID]");
      const brandID = document.querySelector("select[name=brand]");
      const inputText = document.querySelector("input[name=search]");

      btnDels.forEach((btn) => {
        btn.addEventListener("click", doConfirm);
      });

      function doConfirm(e) {
        const btn = e.target;
        //通常window可省略
        if (window.confirm("確定要重新上架嗎？")) {
          window.location.href = `./doUndo.php?id=${btn.dataset.id}`
        }
      };

      btnSearch.addEventListener("click", function () {
        const query = inputText.value;
        const cidValue = cid.value || "";
        const brandIDValue = brandID.value || "";
        window.location.href = `./index.php?cid=${cidValue}&brand_id=${brandIDValue}&search=${query}`;
      });

    </script>
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