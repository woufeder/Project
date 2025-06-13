<?php
require_once "../connect.php";
require_once "../Utilities.php";

$id = $_GET["id"] ?? 0;

$sql = "SELECT coupon.*, images.file_path
        FROM coupon
        LEFT JOIN images ON coupon.img_id = images.id
        WHERE coupon.id = ?";

try {
  $stmt = $pdo->prepare($sql);
  $stmt->execute([$id]);
  $row = $stmt->fetch(PDO::FETCH_ASSOC);
  if (!$row) {
    alertGoTo("沒有這個優惠券", "./index.php");
    exit;
  }
} catch (PDOException $e) {
  echo "錯誤: " . $e->getMessage();
  exit;
}
?>

<!doctype html>
<html lang="zh-Hant">

<head>
  <meta charset="utf-8">
  <title>編輯優惠券</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #f0f4f8;
    }

    h1 {
      color: #3F72AF;
      font-weight: bold;
      margin-bottom: 20px;
    }

    .card {
      background-color: #ffffff;
      border-radius: 15px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
      padding: 30px;
    }

    label {
      font-weight: 500;
    }

    .btn-info {
      background-color: #89b9f4;
      border-color: #3F72AF;
    }

    .btn-info:hover {
      background-color: rgb(191, 220, 255);
      border-color: #5d89c2;
    }

    img {
      width: 100%;
      max-width: 400px;
      aspect-ratio: 4/3;
      object-fit: contain;
      display: none;
    }
  </style>
</head>

<body>
  <div class="container mt-5" style="max-width: 600px;">
    <h1 class="text-center">編輯優惠券</h1>
    <div class="card p-4">
      <form action="./doUpdate.php" method="post">
        <input type="hidden" name="id" value="<?= $row["id"] ?>">
        <input type="hidden" name="original_img_id" value="<?= $row["img_id"] ?>">

        <div class="mb-3">
          <label class="form-label">優惠碼</label>
          <input name="code" value="<?= $row["code"] ?>" class="form-control" required>
        </div>

        <div class="mb-3">
          <label class="form-label">敘述</label>
          <input name="desc" value="<?= $row["desc"] ?>" class="form-control" required>
        </div>

        <div class="mb-3">
          <label class="form-label">類型</label>
          <select name="type" class="form-select" required>
            <option value="">請選擇</option>
            <option value="1" <?= $row["type"] == "1" ? "selected" : "" ?>>百分比</option>
            <option value="2" <?= $row["type"] == "2" ? "selected" : "" ?>>固定金額</option>
          </select>
        </div>

        <div class="mb-3">
          <label class="form-label">折扣</label>
          <input type="number" name="value" value="<?= $row["value"] ?>" class="form-control" required>
        </div>

        <div class="mb-3">
          <label class="form-label">最低消費</label>
          <input type="number" name="min" value="<?= $row["min"] ?>" class="form-control" required>
        </div>

        <div class="mb-3">
          <label class="form-label">開始日期</label>
          <input type="date" name="start_at" value="<?= substr($row["start_at"], 0, 10) ?>" class="form-control"
            required>
        </div>

        <div class="mb-3">
          <label class="form-label">結束日期</label>
          <input type="date" name="expires_at" value="<?= substr($row["expires_at"], 0, 10) ?>" class="form-control"
            required>
        </div>

        <div class="mb-3">
          <label class="form-label">優惠券底圖</label>
          <select name="img_id" class="form-select" id="img_id">
            <option value="" disabled selected>請選擇圖片</option>
            <?php
            $stmtImg = $pdo->query("SELECT id, file_path FROM images ORDER BY id DESC");
            $bgImages = $stmtImg->fetchAll();
            foreach ($bgImages as $index => $img):
              ?>
              <option value="<?= $img["id"] ?>" data-path="<?= $img["file_path"] ?>" <?= ($row["img_id"] ?? "") == $img["id"] ? "selected" : "" ?>>
                圖片<?= $index + 1 ?>
              </option>
            <?php endforeach; ?>
          </select>
          <img id="preview" class="mt-2" src="">
        </div>

        <div class="text-end mt-3">
          <button type="submit" class="btn btn-info">送出</button>
          <a href="<?= $_SERVER["HTTP_REFERER"] ?? './index.php' ?>" class="btn btn-secondary">取消</a>
        </div>
      </form>
    </div>
  </div>

  <script>
    const select = document.getElementById("img_id");
    const preview = document.getElementById("preview");
    function updatePreview() {
      const selected = select.selectedOptions[0];
      const path = selected.dataset.path;
      if (path) {
        preview.style.display = "block";
        preview.src = `../${path}`;
      } else {
        preview.style.display = "none";
      }
    }

    select.addEventListener("change", updatePreview);
    window.addEventListener("DOMContentLoaded", updatePreview);
  </script>
</body>

</html>