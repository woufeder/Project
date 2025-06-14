<?php
require_once "./Utilities.php";
function formatDiscount($type, $value)
{
  return $type == 1 ? $value . "% OFF" : "折 $" . $value . " 元";
}


?>

<!DOCTYPE html>
<html lang="zh-Hant">

<head>
  <meta charset="UTF-8" />
  <title>優惠券底圖</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
  <style>
    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
      font-family: 'Poppins', sans-serif;
    }

    /* body {
      background: #DBE2EF;
      min-height: 100vh;
      padding: 30px;
      display: flex;
      flex-direction: column;
      gap: 20px;
      align-items: center;
    } */

    .coupon {
      width: 320px;
      border-radius: 16px;
      overflow: hidden;
      border: 2px solid #dce3ec;
      position: relative;
      background: #fff;
    }

    .coupon::before,
    .coupon::after {
      content: "";
      position: absolute;
      width: 30px;
      height: 30px;
      background: #f1f3f8;
      border-radius: 50%;
      z-index: 10;
      top: 50%;
      transform: translateY(-50%);
      clip-path: circle(50% at center);
      border: 2px solid #dce3ec;
    }

    .coupon::before {
      left: -15px;
    }

    .coupon::after {
      right: -15px;
    }

    /* ✅ 使用 Grid 排版兩欄三列 */
    .coupon-top {
      display: grid;
      grid-template-columns: 130px 1fr;
      grid-template-rows: repeat(3, auto);
      background: #e8f0fc;
      padding: 16px 20px;
      column-gap: 12px;
      row-gap: 2px;
      align-items: center;
    }

    .coupon-left .badge-group {
      display: flex;
      justify-content: center;
      align-items: center;
      height: 40px;
      /* 可調整，讓它跟右邊按鈕一樣高 */
    }

    /* 左欄項目 */
    .coupon-left .discount,
    .coupon-left .min,
    .coupon-left .badge-group {
      color: #0d1b2a;
      align-items: center;
    }

    .discount {
      font-size: 26px;
      font-weight: 700;
      text-align: center;
    }

    .min {
      font-size: 18px;
      text-align: center;
    }

    .badge {
      background-color: #689bdb;
      color: #fff;
      border-radius: 999px;
      padding: 4px 10px;
      font-size: 17px !important;
      font-weight: 500;
      display: inline-block;
    }

    /* 右欄項目 */
    .coupon-center h1 {
      font-size: 25px;
      margin: 0;
      color: #0d1b2a;
    }

    .code-line {
      display: flex;
      align-items: center;
      gap: 8px;
    }

    .code-text {
      background: #e3f2fd;
      border: 1px solid rgb(113, 160, 199);
      padding: 5px 10px;
      border-radius: 6px;
      font-weight: 600;
      color: #1565c0;
      font-size: 14px;
    }

    .actions {
      display: flex;
      flex-direction: row;
      gap: 6px;
    }

    .btn {
      text-decoration: none;
      padding: 6px 12px;
      border-radius: 6px;
      font-size: 14px;
      font-weight: 500;
      display: inline-block;
      color: white;
    }

    .btn-sm {
      font-size: 13px;
      padding: 4px 10px;
    }

    .btn-cha {
      background-color: #f0ad4e;
    }

    .btn-del {
      background-color: #d9534f;
    }

    .coupon-bottom {
      background: #3F72AF;
      padding: 10px 20px;
      font-size: 16px;
      color: rgb(255, 255, 255);
      text-align: right;
    }
  </style>
</head>

<body>

  <?php
  function renderCouponCard($row)
  {
    ob_start();
    ?>
    <div class="coupon">
      <div class="coupon-top">
        <!-- 第1行 -->
        <div class="coupon-left discount">
          <?= formatDiscount($row["type"], $row["value"]) ?>
        </div>
        <div class="coupon-center">
          <h1><?= $row["desc"] ?></h1>
        </div>

        <!-- 第2行 -->
        <div class="coupon-left min">
          滿 <?= $row["min"] ?> 可用
        </div>
        <div class="coupon-center">
          <div class="code-line">
            <div>優惠碼</div>
            <span class="code-text"><?= $row["code"] ?></span>
          </div>
        </div>

        <!-- 第3行 -->
        <div class="coupon-left">
          <div class="badge-group">
            <span class="badge">折價券</span>
          </div>
        </div>
        <div class="coupon-center">
          <div class="actions">
            <a href="./update.php?id=<?= $row["id"] ?>" class="btn btn-sm btn-cha" title="修改">
              <i class="fas fa-pen"></i>
            </a>
            <a href="./doDelete.php?id=<?= $row["id"] ?>" class="btn btn-sm btn-del" title="刪除"
              onclick="return confirm('確定要刪除這筆資料嗎？');">
              <i class="fas fa-trash-alt"></i>
            </a>
          </div>
        </div>
      </div>

      <div class="coupon-bottom">
        效期：<?= substr($row["start_at"], 0, 10) ?> ~ <?= substr($row["expires_at"], 0, 10) ?>
      </div>
    </div>
    <?php
    return ob_get_clean();
  }
  ?>
</body>

</html>