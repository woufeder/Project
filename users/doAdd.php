<?php
//為甚麼會有密碼加密阿QQ
require_once "./connect.php";
require_once "./utilities.php";

if (!isset($_POST["account"])) {
    alertGoTo("請從正常管道進入", "./index.php");
    exit;
}

//確認是否有空白欄位
$account = $_POST["account"];
$password = $_POST["password"];
$name = $_POST["name"];
$phone = $_POST["phone"];
$email = $_POST["email"];
$year = $_POST["year"];
$month = $_POST["month"];
$date = $_POST["date"];
$gender = $_POST["gender"];
$city = $_POST["city"];

if ($account == "") {
    alertAndBack("請輸入帳號");
    exit;
}
;
if ($password == "") {
    alertAndBack("請輸入密碼");
    exit;
}
;
$passwordLength = strlen($password);
if ($passwordLength < 5 || $passwordLength > 20) {
    alertAndBack("密碼不得少於5字元或多於20字元");
    exit;
}
;
if ($name == "") {
    alertAndBack("請輸入姓名");
    exit;
}
;
if ($phone == "") {
    alertAndBack("請輸入電話");
    exit;
}
;
if ($email == "") {
    alertAndBack("請輸入信箱");
    exit;
}
;
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    alertAndBack("信箱格式錯誤");
    exit;
}
;
if ($year == "") {
    alertAndBack("請輸入生日西元年份");
    exit;
}
;
if ($month == "") {
    alertAndBack("請輸入生日月份");
    exit;
}
;
if ($date == "") {
    alertAndBack("請輸入生日日期");
    exit;
}
;
if ($gender == "") {
    alertAndBack("請選擇性別");
    exit;
}
;
if ($city == "") {
    alertAndBack("請選擇縣市");
    exit;
}
;


//加密
$password = password_hash($password, PASSWORD_BCRYPT);

//預設圖片欄位為 null
$img = null;
if (isset($_FILES["file"]) && $_FILES["file"]["error"] == 0) {
    $timestamp = time();
    $ext = pathinfo($_FILES["file"]["name"], PATHINFO_EXTENSION);
    $newFileName = "{$timestamp}.{$ext}";
    $file = "./imgs/{$newFileName}";
    if (move_uploaded_file($_FILES["file"]["tmp_name"], $file)) {
        $img = $newFileName;
    }
}

$sql = "INSERT INTO `users` (`name`, `account`, `password`, `gender_id`, `email`, `phone`, `city_id`, `year`, `month`, `date`, `img`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?);";
$values = [$name, $account, $password, $gender, $email, $phone, $city, $year, $month, $date, $img];

$sqlAccount = "SELECT COUNT(*) as count FROM users WHERE `account` = ?;";

$sqlEmail = "SELECT COUNT(*) as count FROM users WHERE `email` = ?;";

try {
    $sqlAccount = $pdo->prepare($sqlAccount);
    $sqlAccount->execute([$account]);
    $count = $sqlAccount->fetchColumn();
    if ($count > 0) {
        alertAndBack("帳號已註冊過");
        exit;
    }

    $stmtEmail = $pdo->prepare($sqlEmail);
    $stmtEmail->execute([$email]);
    $count = $stmtEmail->fetchColumn();
    if ($count > 0) {
        alertAndBack("信箱已註冊過");
        exit;
    }

    $stmt = $pdo->prepare($sql);
    $stmt->execute($values);
} catch (PDOException $e) {
    echo "錯誤: {{$e->getMessage()}}";
    exit;
}

alertGoTo("新增會員成功", "./index.php");