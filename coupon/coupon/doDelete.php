<?php
require_once "../connect.php";
require_once "../Utilities.php";

// 驗證 id
if (!isset($_GET["id"]) || empty($_GET["id"])) {
  alertAndBack("請提供正確的 ID");
  exit;
}

$id = $_GET["id"];

// 軟刪除：將 is_valid 設為 0
$sql = "UPDATE `coupon` SET `is_valid` = 0 WHERE `id` = ?";

try {
  $stmt = $pdo->prepare($sql);
  $stmt->execute([$id]);
} catch (PDOException $e) {
  echo "錯誤: " . $e->getMessage();
  exit;
}

$referrer = $_SERVER["HTTP_REFERER"] ?? "./index.php";
alertGoTo("優惠券已成功刪除", $referrer);
