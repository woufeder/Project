<?php
require_once "./connect.php";
require_once "./utilities.php";

if (!isset($_POST["id"])) {
    alertGoTo("請從正常管道進入", "./index.php");
    exit;
}

$id = $_POST["id"];
$password = $_POST["password"];
$name = $_POST["name"];
$phone = $_POST["phone"];
$year = $_POST["year"];
$month = $_POST["month"];
$date = $_POST["date"];
$gender = $_POST["gender"];
$city = $_POST["city"];

$sql = "SELECT * FROM users WHERE id = :id";
try {
    $stmt = $pdo->prepare($sql);
    $stmt->execute([":id" => $id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "錯誤: {{$e->getMessage()}}";
    exit;
}

if (!$row) {
    exit("沒有該會員資料");
}

$set = [];
$values = [":id" => $id];


if (isset($_FILES["file"]) && $_FILES["file"]["error"] == 0) {
    $img = null;
    $timestamp = time();
    $ext = pathinfo($_FILES["file"]["name"], PATHINFO_EXTENSION);
    $newFileName = "{$timestamp}.{$ext}";
    $file = "./imgs/{$newFileName}";
    if (move_uploaded_file($_FILES["file"]["tmp_name"], $file)) {
        if ($newFileName !== $row["img"]) {
            $img = $newFileName;
            $set[] = "`img` = :img";
            $values[":img"] = $img;
        }
    }
}

if ($password !== "" && $password !== $row["password"]) {
    $set[] = "`password` = :password";
    $values[":password"] = $password;
}
if ($name !== "" && $name !== $row["name"]) {
    $set[] = "`name` = :name";
    $values[":name"] = $name;
}
if ($phone !== "" && $phone !== $row["phone"]) {
    $set[] = "`phone` = :phone";
    $values[":phone"] = $phone;
}
if ($year !== "" && strval($year) !== strval($row["year"])) {
    $set[] = "`year` = :year";
    $values[":year"] = $year;
}
if ($month !== "" && strval($month) !== strval($row["month"])) {
    $set[] = "`month` = :month";
    $values[":month"] = $month;
}
if ($date !== "" && strval($date) !== strval($row["date"])) {
    $set[] = "`date` = :date";
    $values[":date"] = $date;
}
if ($gender !== "" && strval($gender) !== strval($row["gender_id"])) {
    $set[] = "`gender_id` = :gender";
    $values[":gender"] = $gender;
}
if ($city !== "" && strval($city) !== strval($row["city_id"])) {
    $set[] = "`city_id` = :city";
    $values[":city"] = $city;
}

if (count($set) == 0) {
    alertAndBack("沒有修改任何欄位");
}

$sqlUpdate = "UPDATE `users` SET " . implode(",", $set) . " WHERE `id` = :id";

try {
    $stmtUpdate = $pdo->prepare($sqlUpdate);
    $stmtUpdate->execute($values);
} catch (PDOException $e) {
    echo "錯誤: {{$e->getMessage()}}";
    exit;
}

alertGoTo("會員資料修改成功", "./view.php?id={$id}");