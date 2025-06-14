<?php

session_start();
if (!isset($_SESSION["user"])) {
  alertGoTo("請先登入系統", "../users/login.php");
}

  $cate_ary = ["會員", "商品", "優惠券", "文章"];
  //分頁名稱的陣列在此
?>

