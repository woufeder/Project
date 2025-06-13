<?php

function alertAndBack($msg = "")
{
  echo "<script>
    alert('$msg');
    window.history.back();
  </script>";
}

function alertGoBack($msg = "")
{
  echo "<script>
    alert('$msg');
    window.location = './index.php';
  </script>";
}

function alertGoTo($msg = "", $url = "./index.php")
{
  echo "<script>
    alert('$msg');
    window.location = '$url';
  </script>";
}

function timeoutGoBack($time = 1000)
{
  echo "<script>
    setTimeout(()=>window.location = './index.php', $time);
  </script>";
}

function goBack()
{
  echo "<button onclick='goBack()'>回上一頁</button>";
  echo "<script>
          function goBack(){
            window.history.back();
          }
        </script>";
}
?>