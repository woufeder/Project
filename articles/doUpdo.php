<?php // 狀態切換（如上架/下架）處理
require_once "./tools/db.php";
require_once "./tools/utilities.php";

$pdo = getPDO();

if (!isset($_GET["id"])) {
  echo "請循正常管道進入本頁";
  exit;
}

$id = intval($_GET["id"]);

// 這裡假設 articles 表有 is_deleted 欄位，0=上架，1=下架
// 若要切換 is_deleted 狀態，可根據目前狀態反轉
$sql = "SELECT is_deleted FROM articles WHERE id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$id]);
$article = $stmt->fetch();

if (!$article) {
  alertGoTo("找不到該文章", "index.php");
  exit;
}

$new_status = $article['is_deleted'] ? 0 : 1;
$sql = "UPDATE articles SET is_deleted = ? WHERE id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$new_status, $id]);

$msg = $new_status ? "文章已下架" : "文章已上架";
alertGoTo($msg, "index.php");
?>