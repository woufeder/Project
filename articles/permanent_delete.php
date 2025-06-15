<?php // 永久刪除文章處理
require_once "./db.php";
require_once "./utilities.php";

try {

    if (!isset($_GET["id"])) {
        throw new Exception("請循正常管道進入本頁");
    }

    $id = intval($_GET["id"]);

    // 先查詢文章資料，以取得封面圖片
    $sql = "SELECT cover_image FROM articles WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id]);
    $article = $stmt->fetch();
    
    if (!$article) {
        throw new Exception("找不到該文章");
    }
    
    // 刪除封面圖片檔案
    if ($article['cover_image'] && file_exists('./uploads/' . $article['cover_image'])) {
        if (!unlink('./uploads/' . $article['cover_image'])) {
            throw new Exception("刪除圖片檔案失敗");
        }
    }
    
    // 從資料庫中永久刪除文章
    $sql = "DELETE FROM articles WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $result = $stmt->execute([$id]);

    if (!$result) {
        throw new Exception("刪除文章失敗");
    }
    
    alertGoTo("文章已永久刪除", "delete_list.php");

} catch (PDOException $e) {
    error_log("資料庫錯誤：" . $e->getMessage());
    alertAndBack("系統錯誤，請稍後再試");
} catch (Exception $e) {
    alertAndBack($e->getMessage());
}
?> 