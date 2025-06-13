<?php
session_start();
require_once "./connect.php";
require_once "./utilities.php";

if (!isset($_POST["account"])) {
    alertGoTo("請從正常管道進入", "./login.php");
    exit;
}

$account = $_POST["account"];
$password1 = $_POST["password1"];
$password2 = $_POST["password2"];

if ($account == "") {
    alertAndBack("請輸入帳號");
    exit;
}
if ($password1 == "") {
    alertAndBack("請輸入密碼");
    exit;
}
if ($password2 == "") {
    alertAndBack("請再次輸入密碼");
    exit;
}

$sql = "SELECT * FROM `users` WHERE `account` = ?";

try {
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$account]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "錯誤: {{$e->getMessage()}}";
    exit;
}

if (!$row) {
    alertAndBack("沒有該會員帳號");
} else {
    if ($password1 !== $password2) {
        alertAndBack("兩次輸入密碼不同");
        exit;
    }
    if (password_verify($password1, $row["password"])) {
        //登入後儲存使用者資料(物件型別)
        $_SESSION["user"] = [
            "id" => $row["id"],
            "name" => $row["name"],
            "account" => $row["account"],
            "img" => $row["img"]
        ];
        alertGoTo("登入成功", "./index.php");
    } else {
        alertAndBack("登入失敗");
    }
}