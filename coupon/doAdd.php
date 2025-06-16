<?php
require_once "./connect.php";
require_once "./Utilities.php";

// 取得表單資料
$code = $_POST["code"];
$desc = $_POST["desc"];
$type = $_POST["type"] ?? null;
$value = $_POST["value"];
$min = $_POST["min"];
$start_at = $_POST["start_at"] . " 00:00:00";
$expires_at = $_POST["expires_at"] . " 23:59:59";
$is_active = isset($_POST["is_active"]) ? 1 : 0;

$from = $_POST["from"] ?? "";
$query = $_POST["redirect_query"] ?? "";
$query = preg_replace('/[\r\n]/', '', $query); // 避免換行造成 header 問題
$query = $query ? "&" . $query : "";

// 驗證必填欄位
if (empty($code) || empty($desc) || $type === null || empty($value) || empty($min) || empty($_POST["start_at"]) || empty($_POST["expires_at"])) {
  echo "請確認所有欄位皆已填寫";
  exit;
}

// 過期時間檢查（避免已過期還啟用）
if ($is_active == 1 && strtotime($expires_at) < time()) {
  if ($from === "expiredList") {
    header("Location: ./expiredList.php?error=expired");
  } else {
    header("Location: ./add.php?error=expired");
  }
  exit;
}

// 寫入資料庫
$sql = "INSERT INTO coupon
  (code, `desc`, type, value, min, start_at, expires_at, is_active)
  VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

try {
  $stmt = $pdo->prepare($sql);
  $stmt->execute([$code, $desc, $type, $value, $min, $start_at, $expires_at, $is_active]);

  // 決定導回頁面
  $redirect = match ($from) {
    "index" => "index.php",
    "indexList" => "indexList.php",
    "expiredList" => "expiredList.php",
    default => "index.php"
  };
  header("Location: ./{$redirect}?" . $query);
  exit;

} catch (PDOException $e) {
  echo "錯誤：" . $e->getMessage();
  exit;
}
