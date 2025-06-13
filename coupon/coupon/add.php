<?php require_once "../connect.php"; ?>
<!doctype html>
<html lang="zh-Hant">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>新增優惠券</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet"
    crossorigin="anonymous">
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
      width: 400px;
      aspect-ratio: 4/3;
      object-fit: contain;
      object-position: 0 0;
    }
  </style>
</head>

<body>
  <div class="container mt-5" style="max-width: 600px;">
    <h1 class="text-center">新增優惠券</h1>
    <div class="card">
      <form action="./doAdd.php" method="post" enctype="multipart/form-data">
        <div class="mb-3">
          <label for="code" class="form-label">優惠碼</label>
          <input required name="code" type="text" class="form-control" id="code">
        </div>

        <div class="mb-3">
          <label for="desc" class="form-label">敘述</label>
          <input required name="desc" type="text" class="form-control" id="desc">
        </div>

        <div class="mb-3">
          <label for="type" class="form-label">類型</label>
          <select required name="type" id="type" class="form-select">
            <option value="" disabled selected>請選擇</option>
            <option value="1">百分比折扣</option>
            <option value="2">固定金額折扣</option>
          </select>
        </div>

        <div class="mb-3">
          <label for="value" class="form-label">折扣數值</label>
          <input required name="value" type="number" step="0.01" class="form-control" id="value">
        </div>

        <div class="mb-3">
          <label for="min" class="form-label">最低消費</label>
          <input required name="min" type="number" step="0.01" class="form-control" id="min">
        </div>

        <div class="mb-3">
          <label for="start_at" class="form-label">開始時間</label>
          <input required name="start_at" type="date" class="form-control" id="start_at">
        </div>

        <div class="mb-3">
          <label for="expires_at" class="form-label">結束時間</label>
          <input required name="expires_at" type="date" class="form-control" id="expires_at">
        </div>

        <div class="mb-2">
          <label class="form-label">優惠券底圖</label>
          <select name="img_id" class="form-select">
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
          <img id="preview" src="" style="display:none" class="mt-2">
        </div>

        <div class="text-end mt-3">
          <button type="submit" class="btn btn-info">送出</button>
          <a href="<?= $_SERVER["HTTP_REFERER"] ?? './index.php' ?>" class="btn btn-secondary">取消</a>
        </div>
      </form>
    </div> <!-- end card -->
  </div> <!-- end container -->
  </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"
    crossorigin="anonymous"></script>
  <script>
    const myImg = document.querySelector("select[name=img_id]");
    const img = document.getElementById("preview");
    myImg.addEventListener("change", e => {
      const selected = e.target.selectedOptions[0];
      const path = selected.dataset.path;
      if (path) {
        img.style.display = "block";
        img.src = `../${path}`;
      } else {
        img.style.display = "none";
      }
    });

    window.addEventListener("DOMContentLoaded", () => {
      const selected = myImg.selectedOptions[0];
      const path = selected.dataset.path;
      if (path) {
        img.style.display = "block";
        img.src = `../${path}`;
      }
    });
  </script>

</body>

</html>