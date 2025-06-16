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

// 撈出這筆資料目前的 is_valid 狀態
$sql_check = "SELECT is_valid FROM coupon WHERE id = ?";
$stmt_check = $pdo->prepare($sql_check);
$stmt_check->execute([$id]);
$current = $stmt_check->fetch();
$is_valid = $current["is_valid"];


$remove_img = isset($_POST["remove_img"]);

$from = $_POST["from"] ?? "";

$query = $_POST["redirect_query"] ?? "";
$query = preg_replace('/[\r\n]/', '', $query); // 避免 header 錯誤
$query = $query ? "&" . $query : "";

if ($is_active == 1 && strtotime($expires_at) < time()) {
  if ($from === "expiredList") {
    header("Location: ./expiredList.php?error=expired{$query}");
  } else {
    header("Location: ./update.php?id=" . $_POST["id"] . "&error=expired");
  }
  exit;
}

switch ($from) {
  case "index":
    alertGoTo("更新成功", "./index.php?$query");
    break;
  case "indexList":
    alertGoTo("更新成功", "./indexList.php?$query");
    break;
  case "expiredList":
    alertGoTo("更新成功", "./expiredList.php?$query");
    break;
  case "update":
    alertGoTo("更新成功", "./update.php?id=" . $_POST["id"] . $query);
    break;
  default:
    alertGoTo("更新成功", "./index.php");
    break;
}

// 更新資料

$sql = "UPDATE coupon SET
  code = ?,
  `desc` = ?,
  type = ?,
  value = ?,
  min = ?,
  start_at = ?,
  expires_at = ?,
  is_active = ?,
  is_valid = ?
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
    $is_valid,
    $id
  ]);

  $redirect = match ($from) {
    "index" => "index.php",
    "indexList" => "indexList.php",
    "expiredList" => "expiredList.php",
    default => "index.php"
  };
  header("Location: ./{$redirect}?" . $query);
  exit;

} catch (PDOException $e) {
  echo "錯誤: " . $e->getMessage();
  exit;
}