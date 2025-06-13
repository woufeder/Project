<?php
require_once "./connect.php";
require_once "./Utilities.php";

// 取得資料
$code = $_POST["code"];
$desc = $_POST["desc"];
$type = $_POST["type"];
$value = $_POST["value"];
$min = $_POST["min"];
$start_at = $_POST["start_at"] . " 00:00:00";
$expires_at = $_POST["expires_at"] . " 23:59:59";

// 1. 資料驗證
if (empty($code)) {
  alertAndBack("請輸入優惠碼");
  exit;
}
if (empty($desc)) {
  alertAndBack("請輸入敘述");
  exit;
}
if (empty($type)) {
  alertAndBack("請輸入類型");
  exit;
}
if (empty($value)) {
  alertAndBack("請輸入折扣");
  exit;
}
if (empty($min)) {
  alertAndBack("請輸入最低消費");
  exit;
}
if (empty($_POST["start_at"])) {
  alertAndBack("請輸入開始時間");
  exit;
}
if (empty($_POST["expires_at"])) {
  alertAndBack("請輸入結束時間");
  exit;
}

// 2. 檢查優惠碼是否重複
$sqlCheck = "SELECT COUNT(*) FROM coupon WHERE code = ?";
try {
  $stmtCheck = $pdo->prepare($sqlCheck);
  $stmtCheck->execute([$code]);
  $count = $stmtCheck->fetchColumn();
  if ($count > 0) {
    alertAndBack("此優惠碼已經使用過");
    exit;
  }
} catch (PDOException $e) {
  echo "查詢錯誤: " . $e->getMessage();
  exit;
}

// 3. 寫入資料
$sql = "INSERT INTO coupon 
(code, `desc`, type, value, min, start_at, expires_at)
VALUES (?, ?, ?, ?, ?, ?, ?)";


try {
  $stmt = $pdo->prepare($sql);
  $stmt->execute([$code, $desc, $type, $value, $min, $start_at, $expires_at]);
} catch (PDOException $e) {
  echo "新增失敗 " . $e->getMessage();
  exit;
}

// 4. 導回
$referrer = $_POST["ref"] ?? "./index.php";
alertGoTo("新增優惠券成功", $referrer);
