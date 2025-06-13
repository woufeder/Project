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

$remove_img = isset($_POST["remove_img"]);

// 更新資料

$sql = "UPDATE coupon SET 
  code = ?, `desc` = ?, type = ?, value = ?, min = ?, 
  start_at = ?, expires_at = ?, updated_at = NOW()
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
    $id
  ]);
} catch (PDOException $e) {
  echo "錯誤: " . $e->getMessage();
  exit;
}

alertGoTo("更新成功", "./index.php");