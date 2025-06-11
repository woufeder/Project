<?php
// part 1
require_once "./connect.php";
require_once "./utilities.php";
require_once "./uploadImgs.php";
require_once "./connect.php";
require_once "./utilities.php";
include "../template_btn.php";
include "../vars.php";


if (!isset($_POST["name"]) || !isset($_POST["mainCateID"]) || !isset($_POST["subCateID"])) {
  echo "請循正常管道進入本頁";
  exit;
}

$mainCateID = $_POST["mainCateID"];
$subCateID = $_POST["subCateID"];
$brand = $_POST["brand"];
$name = $_POST["name"];
$modal = $_POST["modal"];
$price = $_POST["price"];
$intro = $_POST["intro"];
$spec = $_POST["spec"];

// 檢查 part 1
if ($brand == "") {
  alertAndBack("請輸入品牌");
  exit;
}

if (empty($name)) {
  alertAndBack("請輸入商品名稱");
  exit;
}

$sql = "INSERT INTO `products` 
  (`category_main_id`, `category_sub_id`, `brand_id`,`name`,`modal`,`price`,`intro`,`spec`) VALUES 
  (:category_main_id, :category_sub_id, :brand_id,:name,:modal,:price,:intro,:spec);";

$values = [
  ":category_main_id" => $mainCateID,
  ":category_sub_id" => $subCateID,
  ":brand_id" => $brand,
  ":name" => $name,
  ":modal" => $modal,
  ":price" => $price,
  ":intro" => $intro,
  ":spec" => $spec
];

try {
  $stmt = $pdo->prepare($sql);
  $stmt->execute($values);

  $productID = $pdo->lastInsertId();
  uploadImages("productImg", $productID,"","products_imgs");
  uploadImages("introImg", $productID, "intro_", "products_intro_imgs");

} catch (PDOException $e) {
  echo $e->getMessage();
  exit;
}

alertGoTo("新增商品成功", "./index.php");
?>