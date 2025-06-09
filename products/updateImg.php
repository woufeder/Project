<?php
function updateImages($inputName, $productID, $prefix = "", $table, $fileColumn = "file", $productColumn = "product_id")
{
    global $pdo;

    // ✅ 如果沒有上傳新檔案，就什麼都不做（保留舊圖）
    if (!isset($_FILES[$inputName]["name"]) || count(array_filter($_FILES[$inputName]["name"])) === 0) {
        return;
    }

    // 1. 刪掉舊資料庫圖片記錄 & 圖片檔案
    $sql = "SELECT `$fileColumn` FROM `$table` WHERE `$productColumn` = :product_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([":product_id" => $productID]);
    $oldFiles = $stmt->fetchAll(PDO::FETCH_COLUMN);

    foreach ($oldFiles as $file) {
        $filePath = "./uploads/" . $file;
        if (file_exists($filePath)) {
            unlink($filePath);
        }
    }

    $sql = "DELETE FROM `$table` WHERE `$productColumn` = :product_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([":product_id" => $productID]);

    // 2. 上傳新圖片 & 寫入資料庫
    if (!isset($_FILES[$inputName]))
        return;

    $countFiles = count($_FILES[$inputName]["name"]);
    $timestamp = time();
    $folderPath = "./uploads/{$productID}/";

    if (!is_dir($folderPath)) {
        mkdir($folderPath, 0777, true);
    }

    for ($i = 0; $i < $countFiles; $i++) {
        if ($_FILES[$inputName]["error"][$i] === 0) {
            $ext = pathinfo($_FILES[$inputName]["name"][$i], PATHINFO_EXTENSION);
            $newFileName = "{$prefix}{$timestamp}-{$i}.{$ext}";
            $filePath = $folderPath . $newFileName;

            if (move_uploaded_file($_FILES[$inputName]["tmp_name"][$i], $filePath)) {
                $sql = "INSERT INTO `$table` (`$fileColumn`, `$productColumn`) VALUES (:file, :product_id)";
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