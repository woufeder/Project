<?php
require_once "./connect.php";
require_once "./Utilities.php";

// 取得資料
$id = $_POST["id"];
$code = $_POST["code"];
$desc = $_POST["desc"];
$type = $_POST["type"];
$value = $_POST["value"];
$min = $_POST["min"];
$start_at = $_POST["start_at"] . " 00:00:00";
$expires_at = $_POST["expires_at"] . " 23:59:59";
$is_active = isset($_POST["is_active"]) ? 1 : 0;

$remove_img = isset($_POST["remove_img"]);

// 更新資料

$sql = "UPDATE coupon SET
  code = ?,
  `desc` = ?,
  type = ?,
  value = ?,
  min = ?,
  start_at = ?,
  expires_at = ?,
  is_active = ?
WHERE id = ?";


try {
  $stmt = $pdo->prepare($sql);
  $stmt->execute([
    $code,
    $desc,
    $type,
    $value,
    $min,
    $start_at,
    $expires_at,
    $is_active,
    $id
  ]);
} catch (PDOException $e) {
  echo "錯誤: " . $e->getMessage();
  exit;
}

$referer = $_POST["referer"] ?? './index.php';
alertGoTo("更新成功", $referer);
exit;