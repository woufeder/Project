<?php
require_once "./connect.php";
require_once "./utilities.php";
include "../template_btn.php";
include "../vars.php";

$cid = intval($_GET["cid"] ?? 0);
$mid = intval($_GET["mid"] ?? 0);
$branID = intval($_GET["brand_id"] ?? 0);

$cateMainSQL = "";
$cateSubSQL = "";
$brandSQL = "";
$values = [];

if ($mid != 0) {
	$cateMainSQL = "`category_main_id` = :mid AND ";
	$values["mid"] = $mid;
}
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
$pageTitle = "已下架{$cate_ary[$cateNum]}";

//分頁定番
$defaultPerPage = 10;
$allowedPerPage = [10, 25, 50];

$perPage = intval($_GET["per_page"] ?? $defaultPerPage);

if (!in_array($perPage, $allowedPerPage)) {
	$perPage = $defaultPerPage;
}

$page = intval($_GET["page"] ?? 1);
$pageStart = ($page - 1) * $perPage;

$queryParams["per_page"] = $perPage;

$sql = "SELECT 
            products.*,
            category_main.name AS category_main_name,
            category_sub.name AS category_sub_name,
            brands.name AS brand_name
        FROM products
        JOIN category_main ON products.category_main_id = category_main.id
        JOIN category_sub ON products.category_sub_id = category_sub.id
        JOIN brands ON products.brand_id = brands.id
        WHERE $cateMainSQL $cateSubSQL $brandSQL $searchSQL products.is_valid = 0 $orderSQL
        LIMIT $perPage OFFSET $pageStart";

$sqlAll = "SELECT * FROM `products` WHERE $cateMainSQL $cateSubSQL $brandSQL $searchSQL `is_valid`=0 $orderSQL ";
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
	<link rel="stylesheet" href="../css/main.css">
	<link rel="stylesheet" href="./css/index.css">
</head>

<body>
	<div class="dashboard">

		<?php include '../template_sidebar.php'; ?>

		<div class="main-container overflow-auto">

			<?php include '../template_header.php'; ?>
			<main>

				<div class="">
					<div class="">
						<div class="d-flex align-items-center mb-4 px-2">
							<div class="index-slecter d-flex gap-2">
								<div class="input-group input-group-sm ">
									<span class="input-group-text">母分類</span>
									<select class="form-select" name="mainCateID">
										<option value selected disabled>請選擇</option>
										<?php foreach ($rowsMain as $rowMain): ?>
											<option value="<?= $rowMain["id"] ?>"><?= $rowMain["name"] ?></option>
										<?php endforeach ?>
									</select>
								</div>
								<div class="input-group input-group-sm ">
									<span class="input-group-text">子分類</span>
									<select class="form-select" name="subCateID">
										<option value selected disabled>請選擇</option>
									</select>
								</div>
								<div class="input-group input-group-sm ">
									<span class="input-group-text">品牌</span>
									<select name="brand" class="form-select">
										<option value selected disabled>請選擇</option>
										<?php foreach ($rowsBrand as $rowBrand): ?>
											<option value="<?= $rowBrand["id"] ?>"><?= $rowBrand["name"] ?></option>
										<?php endforeach; ?>
									</select>
								</div>
								<div class="input-group input-group-sm ">
									<span class="input-group-text">品名</span>
									<input name="search" type="text" class="form-control" placeholder="請輸入關鍵字搜尋">
									<button class="btn btn-search" type="button">送出搜尋</button>
								</div>
							</div>
							<a class="btn btn-back ms-auto px-3" href="../products/index.php">
								<i class="fa-solid fa-backward"> 回到商品列表</i>
							</a>
						</div>

						<div class="list-area p-4">
							<div class="w-100 d-flex align-items-center mb-4">

								<ul class="pagination pagination-sm ">

									<?php
									$startPage = floor(($page - 1) / 10) * 10 + 1;
									$endPage = min($startPage + 9, $totalPage);

									if ($page > 1):
										$prevPage = $page - 1;
										$queryParams = $_GET;
										$queryParams["page"] = $prevPage;
										?>
										<li class="page-item">
											<a class="page-link" href="?<?= "?" . http_build_query($queryParams) ?>">
												<i class="fa-solid fa-caret-left"></i>
											</a>
										</li>
									<?php endif; ?>

									<?php

									for ($i = $startPage; $i <= $endPage; $i++):
										$queryParams = $_GET;
										$queryParams["page"] = $i;
										?>

										<li class="page-item <?= $page == $i ? "active" : "" ?>">
											<a class="page-link" href="?<?= "?" . http_build_query($queryParams) ?>"><?= $i ?></a>
										</li>
									<?php endfor; ?>

									<?php

									if ($page < $totalPage):
										$nextPage = $page + 1;
										$queryParams = $_GET;
										$queryParams["page"] = $nextPage;
										?>
										<li class="page-item">
											<a class="page-link" href="?<?= "?" . http_build_query($queryParams) ?>">
												<i class="fa-solid fa-caret-right"></i>
											</a>
										</li>
									<?php endif; ?>
								</ul>
								<div class="content-area d-flex ms-auto">
									<div class="my-2 d-flex flex-row align-items-center">
										<span class="info-count me-1">目前共有 <?= $totalCount ?>筆 資料</span>
										<form method="get">
											<div>
												<?php if (!empty($_GET['mid'])): ?>
													<input type="hidden" name="mid" value="<?= htmlspecialchars($_GET['mid']) ?>">
												<?php endif; ?>
												<?php if (!empty($_GET['cid'])): ?>
													<input type="hidden" name="cid" value="<?= htmlspecialchars($_GET['cid']) ?>">
												<?php endif; ?>
												<?php if (!empty($_GET['brand_id'])): ?>
													<input type="hidden" name="brand_id" value="<?= htmlspecialchars($_GET['brand_id']) ?>">
												<?php endif; ?>
												<?php if (!empty($_GET['order'])): ?>
													<input type="hidden" name="order" value="<?= htmlspecialchars($_GET['order']) ?>">
												<?php endif; ?>
												<?php $searcHidden = !empty($_GET['search']) ? $_GET['search'] : ($_GET['search-hidden'] ?? ''); ?>
												<input type="hidden" name="search-hidden" value="<?= htmlspecialchars($searcHidden) ?>">
												<select class="form-select form-select-sm select-perpage" name="per_page"
													onchange="this.form.submit()">
													<option value="10" <?= $perPage == 10 ? 'selected' : '' ?>>每頁顯示10筆</option>
													<option value="25" <?= $perPage == 25 ? 'selected' : '' ?>>每頁顯示25筆</option>
													<option value="50" <?= $perPage == 50 ? 'selected' : '' ?>>每頁顯示50筆</option>
												</select>
												<input type="hidden" name="page" value="1">
											</div>
										</form>
									</div>
								</div>
							</div>

							<div class="list">
								<div class="list-head px-2">
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
									<div class="list-row text-dark px-2">
										<div class="id">
											<p><?= $index + 1 + ($page - 1) * $perPage ?></p>
										</div>
										<!-- 圖片的row -->
										<div class="category_main">
											<p><?= $row["category_main_name"] ?></p>
										</div>
										<div class="category_sub">
											<p><?= $row["category_sub_name"] ?></p>
										</div>
										<div class="brand">
											<p><?= $row["brand_name"] ?></p>
										</div>
										<div class="name">
											<p><?= $row["name"] ?></p>
										</div>
										<div class="price">
											<p><?= $row["price"] ?>元</p>
										</div>
										<div class="control">
											<a class="btn btn-sm btn-look" href="./look2.php?id=<?= $row["id"] ?>">
												<?= $btnLook ?>
											</a>
											<a class="btn btn-sm btn-update" href="./update2.php?id=<?= $row["id"] ?>">
												<?= $btnUpdate ?>
											</a>
											<button class="btn btn-sm btn-return" data-id="<?= $row["id"] ?>">
												<i class="fa-solid fa-rotate-left"></i>
											</button>
										</div>
									</div>
								<?php endforeach ?>
							</div>
						</div>

					</div>

				</div>


			</main>
		</div>
	</div>

	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"
		integrity="sha384-k6d4wzSIapyDyv1kpU366/PK5hCdSbCRGRCMv+eplOQJWyd1fbcAu9OCUj5zNLiq"
		crossorigin="anonymous"></script>

	<script>
		const btnReturn = document.querySelectorAll(".btn-return");
		const btnSearch = document.querySelector(".btn-search");
		const mid = document.querySelector("select[name=mainCateID]");
		const cid = document.querySelector("select[name=subCateID]");
		const brandID = document.querySelector("select[name=brand]");
		const inputText = document.querySelector("input[name=search]");

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

		btnSearch.addEventListener("click", function () {
			const query = inputText.value;
			const midValue = mid.value || "";
			const cidValue = cid.value || "";
			const brandIDValue = brandID.value || "";
			const perPageValue = new URLSearchParams(window.location.search).get("per_page");

			if (cidValue != 0) {
				window.location.href = `./deleted.php?&cid=${cidValue}&brand_id=${brandIDValue}&search=${query}&per_page=${perPageValue}`;
				return;
			}
			if (midValue != 0) {
				window.location.href = `./deleted.php?&mid=${midValue}&brand_id=${brandIDValue}&search=${query}&per_page=${perPageValue}`;
				return;
			}
			window.location.href = `./deleted.php?mid=${midValue}&cid=${cidValue}&brand_id=${brandIDValue}&search=${query}&per_page=${perPageValue}`;

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