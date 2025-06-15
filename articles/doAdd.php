<?php // 新增文章的後端處理
require_once "./db.php";
require_once "./utilities.php";

try {

    if (!isset($_POST["title"]) || !isset($_POST["category_id"]) || !isset($_POST["content"])) {
        throw new Exception("請循正常管道進入本頁");
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
        if (!move_uploaded_file($_FILES['cover_image']['tmp_name'], $target)) {
            throw new Exception("圖片上傳失敗");
        }
        $cover_image = $filename;
    }

    $sql = "INSERT INTO articles (title, content, cover_image, category_id) VALUES (?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$title, $content, $cover_image, $category_id]);

    alertGoTo("新增文章成功", "index.php");

} catch (PDOException $e) {
    error_log("資料庫錯誤：" . $e->getMessage());
    alertAndBack("系統發生錯誤，請稍後再試");
} catch (Exception $e) {
    alertAndBack($e->getMessage());
}
?>