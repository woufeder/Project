<?php
function uploadImages($inputName, $productID, $prefix = "", $table = "") {
  global $pdo;

  $countFiles = count($_FILES[$inputName]["name"]);
  $timestamp = time();
  $folderPath = "./uploads/{$productID}/";

  if (!is_dir($folderPath) && !mkdir($folderPath, 0777, true)) {
    alertAndBack("資料夾建立失敗");
    exit;
  }

  for ($i = 0; $i < $countFiles; $i++) {
    if ($_FILES[$inputName]["error"][$i] === 0) {
      $ext = pathinfo($_FILES[$inputName]["name"][$i], PATHINFO_EXTENSION);
      $newFileName = "{$prefix}{$timestamp}-{$i}.{$ext}";
      $filePath = $folderPath . $newFileName;

      if (move_uploaded_file($_FILES[$inputName]["tmp_name"][$i], $filePath)) {
        // ✅ 使用反引號保護資料表名稱
        $sql = "INSERT INTO `$table` (`file`, `product_id`) VALUES (:file, :product_id)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
          ":file" => "{$productID}/{$newFileName}",
          ":product_id" => $productID
        ]);
      }
    }
  }
}
?>

