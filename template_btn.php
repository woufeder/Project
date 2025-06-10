<?php
// 搜尋按鈕(目前沒有使用)
$btnSearch = '<i class="fa-solid fa-magnifying-glass"></i>';

// 增加按鈕(目前沒有使用)
$btnAdd = '<i class="fa-solid fa-plus"></i>';

// 檢視按鈕
$btnLook = '<i class="fas fa-eye"></i>';

// 修改按鈕
$btnUpdate = '<i class="fas fa-pen"></i>';

// 刪除按鈕
$btnDel = '<i class="fas fa-trash"></i>';

/*
！注意事項！
！按鈕套用css時class需要統一！
！要是不放心就把範例複製貼上，包準樣式是對的！
－－－－－－－－－－－－－－－－－－
送出搜尋=> .btn-search
<button class="btn btn-search" type="button">
<?=$btnSearch?>
</button>

增加資料=> .btn-add
(也可以改新增會員啥的，隨喜)
<a class="btn btn-sm btn-add" href="./add.php">
<?=$btnAdd?>
</a>

檢視資料(眼睛圖示)=> .btn-look
(這個沒時間可不做，為當條資料的檢視頁面，類似前台的頁面呈現)
<button class="btn btn-sm btn-look">
<?=$btnLook?>
</button>

修改資料(筆圖示)=> .btn-update
<a class="btn btn-sm btn-update" href="#">
<?=$btnUpdate?>
</a>
(寫button或a都可以，套用bootstrap時，只要class有掛btn，就會有和btn一樣的效果，看個人喜歡)

刪除資料(垃圾桶圖示)=> .btn-del
<button class="btn btn-sm btn-del">">
<?=$btnDel?>
</button>

*/