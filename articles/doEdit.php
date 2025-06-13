<?php // 編輯文章的後端處理

require_once "./tools/db.php";
require_once "./tools/utilities.php";

// 開啟錯誤報告
error_reporting(E_ALL);
ini_set('display_errors', 1);

$pdo = getPDO();

// 記錄接收到的資料
error_log("POST data: " . print_r($_POST, true));
error_log("FILES data: " . print_r($_FILES, true));

// 檢查是否為 POST 請求
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo "<script>alert('請使用正確的方式提交表單'); history.back();</script>";
    exit;
}

// 驗證必要欄位
if (!isset($_POST["id"]) || !isset($_POST["title"]) || !isset($_POST["category_id"]) || !isset($_POST["content"])) {
    echo "<script>alert('請填寫所有必要欄位'); history.back();</script>";
    exit;
}

$id = intval($_POST["id"]);
$title = trim($_POST["title"]);
$category_id = intval($_POST["category_id"]);
$content = trim($_POST["content"]);

// 記錄處理後的資料
error_log("Processed data - ID: $id, Title: $title, Category: $category_id, Content length: " . strlen($content));

// 驗證資料
if (empty($title) || empty($content) || $category_id <= 0) {
    echo "<script>alert('請填寫所有必要欄位'); history.back();</script>";
    exit;
}

try {
    // 先查詢原本的封面圖片
    $sql = "SELECT cover_image FROM articles WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id]);
    $article = $stmt->fetch();
    
    if (!$article) {
        echo "<script>alert('找不到該文章'); history.back();</script>";
        exit;
    }
    
    $cover_image = $article['cover_image'];

    // 處理封面圖片上傳
    if (isset($_FILES['cover_image']) && $_FILES['cover_image']['error'] === UPLOAD_ERR_OK) {
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
        if (!in_array($_FILES['cover_image']['type'], $allowed_types)) {
            echo "<script>alert('只允許上傳 JPG、PNG 或 GIF 格式的圖片'); history.back();</script>";
            exit;
        }

        $ext = pathinfo($_FILES['cover_image']['name'], PATHINFO_EXTENSION);
        $filename = uniqid('cover_', true) . '.' . $ext;
        $target = './uploads/' . $filename;
        
        if (move_uploaded_file($_FILES['cover_image']['tmp_name'], $target)) {
            // 如果成功上傳新圖片，刪除舊圖片
            if ($cover_image && file_exists('./uploads/' . $cover_image)) {
                unlink('./uploads/' . $cover_image);
            }
            $cover_image = $filename;
        }
    }

    // 更新文章
    $sql = "UPDATE articles SET title = ?, content = ?, cover_image = ?, category_id = ?, updated_at = NOW() WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $result = $stmt->execute([$title, $content, $cover_image, $category_id, $id]);

    if ($result) {
        echo "<script>
            alert('文章已成功更新！');
            window.location.href = 'index.php';
        </script>";
    } else {
        echo "<script>alert('更新失敗，請稍後再試'); history.back();</script>";
    }
} catch (PDOException $e) {
    echo "<script>alert('系統錯誤，請稍後再試'); history.back();</script>";
}
?>