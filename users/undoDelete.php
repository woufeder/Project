<?php
require_once "./connect.php";
require_once "./utilities.php";
include "../vars.php";

if (!isset($_GET["id"])) {
    alertGoTo("請從正常管道進入", "./index.php");
    exit;
}

$id = $_GET["id"];

$sql = "UPDATE `users` SET `is_valid` = 1 WHERE `id` = ?";
$values = [$id];

try {
    $stmt = $pdo->prepare($sql);
    $stmt->execute($values);

} catch (PDOException $e) {
    echo "錯誤: {{$e->getMessage()}}";
    exit;
}

alertGoTo("解除停權會員成功", "./deleteIndex.php");