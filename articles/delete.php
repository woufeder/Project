<?php // 軟刪除（移至回收桶）處理
require_once "./tools/db.php";
require_once "./tools/utilities.php";

$pdo = getPDO();

// 將文章軟刪除（is_deleted 設為 1）
if (!isset($_GET["id"])) {
    alertAndBack("請循正常管道進入本頁");
    exit;
}
$id = intval($_GET["id"]);

$sql = "UPDATE articles SET is_deleted = 1 WHERE id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$id]);

alertGoTo("刪除成功", "index.php");
?>