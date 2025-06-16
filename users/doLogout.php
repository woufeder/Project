<?php
require_once "./utilities.php";
session_start();
session_destroy();
alertGoTo("已成功登出", "./login.php");