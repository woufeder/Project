<?php

function alertAndBack($msg=""){
  echo "<script>
    alert('$msg');
    window.history.back();
  </script>";}

function alertGoTo($msg="", $url="index.php"){
  echo "<script>
    alert('$msg');
    window.location.href = '$url';
  </script>";
}
function alertToIndex($msg=""){
  echo "<script>
    alert('$msg');
    window.location = './index.php';
  </script>";
}

// 有預設值的參數要往最後放
function timeoutGoBack($time=1000){
  echo "<script>
    setTimeout(()=>window.location = './index.php', $time);
  </script>";
}

function goBack(){
  echo "<button onclick='goBack()'>回上一頁</button>";
  echo "<script>
          function goBack(){
            window.history.back();
          }
        </script>";
}
?>