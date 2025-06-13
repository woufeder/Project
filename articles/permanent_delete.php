<?php // 永久刪除文章處理
require_once "./tools/db.php";
require_once "./tools/utilities.php";

$pdo = getPDO();

if (!isset($_GET["id"])) {
    alertAndBack("請循正常管道進入本頁");
    exit;
}

$id = intval($_GET["id"]);

try {
    // 先查詢文章資料，以取得封面圖片
    $sql = "SELECT cover_image FROM articles WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id]);
    $article = $stmt->fetch();
    
    if (!$article) {
        alertGoTo("找不到該文章", "delete_list.php");
        exit;
    }
    
    // 刪除封面圖片檔案
    if ($article['cover_image'] && file_exists('./uploads/' . $article['cover_image'])) {
        unlink('./uploads/' . $article['cover_image']);
    }
    
    // 從資料庫中永久刪除文章
    $sql = "DELETE FROM articles WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id]);
    
    alertGoTo("文章已永久刪除", "delete_list.php");
} catch (PDOException $e) {
    alertGoTo("刪除失敗，請稍後再試", "delete_list.php");
}
?> 