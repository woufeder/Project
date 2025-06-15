<?php // 還原已刪除文章處理
require_once "./db.php";
require_once "./utilities.php";


// 還原已刪除的文章（is_deleted 設為 0）
if (!isset($_GET["id"])) {
    alertAndBack("請循正常管道進入本頁");
    exit;
}
$id = intval($_GET["id"]);

$sql = "UPDATE articles SET is_deleted = 0 WHERE id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$id]);

alertGoTo("還原成功", "delete_list.php"); 