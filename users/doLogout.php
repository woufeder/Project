<?php
session_start();
session_destroy();
require_once "./utilities.php";
alertGoTo("已成功登出", "./login.php");