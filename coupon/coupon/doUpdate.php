<?php
require_once "../connect.php";
require_once "../Utilities.php";

// 取得資料
$id = $_POST["id"];
$code = $_POST["code"];
$desc = $_POST["desc"];
$type = $_POST["type"];
$value = $_POST["value"];
$min = $_POST["min"];
$start_at = $_POST["start_at"] . " 00:00:00";
$expires_at = $_POST["expires_at"] . " 23:59:59";
$original_img_id = $_POST["original_img_id"];
$remove_img = isset($_POST["remove_img"]);

$new_img_id = $original_img_id;

$img_id = $_POST["img_id"] ?? null;

// 如有上傳新圖片
if (!empty($_FILES["img"]["name"])) {
  $targetDir = "../uploads/";
  $filename = time() . "_" . basename($_FILES["img"]["name"]);
  $targetFile = $targetDir . $filename;

  if (move_uploaded_file($_FILES["img"]["tmp_name"], $targetFile)) {
    // 新增圖片資料到 images 表
    $insertImageSQL = "INSERT INTO images (file_path) VALUES (?)";
    $stmt = $pdo->prepare($insertImageSQL);
    $stmt->execute(["uploads/" . $filename]);
    $new_img_id = $pdo->lastInsertId();
  }
} elseif ($remove_img) {
  $new_img_id = null;
} elseif (!empty($img_id)) {
  // 若選擇圖片下拉選單但沒有上傳圖或移除圖
  $new_img_id = $img_id;
}

// 更新資料

$sql = "UPDATE coupon SET 
  code = ?, `desc` = ?, type = ?, value = ?, min = ?, 
  start_at = ?, expires_at = ?, img_id = ?, updated_at = NOW()
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
    $new_img_id,
    $id
  ]);
} catch (PDOException $e) {
  echo "錯誤: " . $e->getMessage();
  exit;
}

alertGoTo("更新成功", "./index.php");