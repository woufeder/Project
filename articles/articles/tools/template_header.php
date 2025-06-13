<header class="d-flex justify-content-between align-items-center">
  <!-- <div class="d-flex justify-content-between align-items-center"> -->
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

  <div class="user-info d-flex align-items-center">
    <div class="adminphoto">
      <!-- 會員相片 -->
      <img src="https://randomuser.me/api/portraits/women/51.jpg" alt="">
    </div>
    <div class="adminname">
      <!-- 會員名稱 -->
      <h6>Billie Pierce</h6>
      <p>yellowpeacock117</p>
    </div>
  </div>
  <!-- </div> -->
</header>