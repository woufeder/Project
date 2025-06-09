<header class="d-flex justify-content-between algin-items-center">
  <!-- <div class="d-flex justify-content-between algin-items-center"> -->
  <div class="header-left d-flex flex-column">
    <div class="page-title">
      <h2><?= $pageTitle ?></h2>
    </div>
    <div class="breadcrumb">
      <?php
      $now = $_GET["name"] ?? $pageTitle;
      $breadcrumb_items = [
        "{$cate_ary[$cateNum]}管理" => "",
        $now => null
      ];
      include "template_breadcrumb.php";
      ?>
    </div>
  </div>
  <div class="content-area px-5 d-flex justify-content-between align-items-center ">
    <div class="my-2 d-flex flex-column align-items-end">
      <span class="info-count mb-1 text-white">目前共有 <?= $totalCount ?>筆 資料</span>
      <form method="get">
        <div>
          <input type="hidden" name="mid" value="<?= htmlspecialchars($_GET['mid'] ?? '') ?>">
          <input type="hidden" name="cid" value="<?= htmlspecialchars($_GET['cid'] ?? '') ?>">
          <input type="hidden" name="brand_id" value="<?= htmlspecialchars($_GET['brand_id'] ?? '') ?>">
          <input type="hidden" name="order" value="<?= htmlspecialchars($_GET['order'] ?? '') ?>">
          <input type="hidden" name="search" value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
          <input type="hidden" name="page" value="1">

          <select class="form-select form-select-sm" name="per_page" onchange="this.form.submit()">
            <option value="10" <?= $perPage == 10 ? 'selected' : '' ?>>每頁顯示10筆</option>
            <option value="25" <?= $perPage == 25 ? 'selected' : '' ?>>每頁顯示25筆</option>
            <option value="50" <?= $perPage == 50 ? 'selected' : '' ?>>每頁顯示50筆</option>
          </select>

        </div>
      </form>
    </div>
  </div>
  <!-- </div> -->
</header>