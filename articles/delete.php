<?php // 軟刪除（移至回收桶）處理
require_once "./db.php";
require_once "./utilities.php";

try {

    if (!isset($_GET["id"])) {
        throw new Exception("請循正常管道進入本頁");
    }

    $id = intval($_GET["id"]);

    $sql = "UPDATE articles SET is_deleted = 1 WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $result = $stmt->execute([$id]);

    if (!$result) {
        throw new Exception("刪除失敗，請稍後再試");
    }

    alertGoTo("刪除成功", "index.php");

} catch (PDOException $e) {
    error_log("資料庫錯誤：" . $e->getMessage());
    alertAndBack("系統錯誤，請稍後再試");
} catch (Exception $e) {
    alertAndBack($e->getMessage());
}
?>