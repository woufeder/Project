<?php

require_once "./connect.php";
require_once "./utilities.php";
require_once "./updateImg.php";

if (!isset($_POST["id"])) {
  echo "請循正常管道進入本頁";
  exit;
}

$id = $_POST["id"];
$mainCateID = $_POST["mainCateID"];
$subCateID = $_POST["subCateID"];
$brandID = $_POST["brandID"];
$name = $_POST["name"];
$modal = $_POST["modal"];
$price = $_POST["price"];
$intro = $_POST["intro"];
$spec = $_POST["spec"];
$set = [];
$values = [":id" => $id];

if ($mainCateID !== "") {
  $set[] = "`category_main_id`=:mainCateID";
  $values[":mainCateID"] = $mainCateID;
}

if ($subCateID !== "") {
  $set[] = "`category_sub_id`=:subCateID";
  $values[":subCateID"] = $subCateID;
}

if ($brandID !== "") {
  $set[] = "`brand_id`=:brandID";
  $values[":brandID"] = $brandID;
}

if ($name !== "") {
  $set[] = "`name`=:name";
  $values[":name"] = $name;
}

if ($modal !== "") {
  $set[] = "`modal`=:modal";
  $values[":modal"] = $modal;
}

if ($price !== "") {
  $set[] = "`price` = :price";
  $values[":price"] = $price;
}

if ($intro !== "") {
  $set[] = "`intro` = :intro";
  $values[":intro"] = $intro;
}

if ($spec !== "") {
  $set[] = "`spec` = :spec";
  $values[":spec"] = $spec;
}

if (count($set) == 0) {
  alertAndBack("沒有修改任何欄位耶你送身體健康逆");
}

$sql = "UPDATE `products` SET " . implode(", ", $set) . " WHERE `id`= :id";

try {
  $stmt = $pdo->prepare($sql);
  $stmt->execute($values);

  updateImages("productImg", $id, "", "products_imgs");
  updateImages("introImg", $id, "intro_", "products_intro_imgs");


} catch (PDOException $e) {
  echo "系統錯誤，請恰管理人員<br>";
  exit;
}
alertGoTo("修改資料成功", "./update.php?id=$id");

?>