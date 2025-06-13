<?php // 新增文章的後端處理
require_once "./tools/db.php";
require_once "./tools/utilities.php";

$pdo = getPDO();

if (!isset($_POST["title"]) || !isset($_POST["category_id"]) || !isset($_POST["content"])) {
    alertAndBack("請循正常管道進入本頁");
    exit;
}

$title = trim($_POST["title"]);
$category_id = intval($_POST["category_id"]);
$content = trim($_POST["content"]);
$cover_image = null;

// 處理封面圖片上傳
if (isset($_FILES['cover_image']) && $_FILES['cover_image']['error'] === UPLOAD_ERR_OK) {
    $ext = pathinfo($_FILES['cover_image']['name'], PATHINFO_EXTENSION);
    $filename = uniqid('cover_', true) . '.' . $ext;
    $target = './uploads/' . $filename;
    if (move_uploaded_file($_FILES['cover_image']['tmp_name'], $target)) {
        $cover_image = $filename;
    }
}

$sql = "INSERT INTO articles (title, content, cover_image, category_id) VALUES (?, ?, ?, ?)";
$stmt = $pdo->prepare($sql);
$stmt->execute([$title, $content, $cover_image, $category_id]);

alertGoTo("新增文章成功", "index.php");
?>