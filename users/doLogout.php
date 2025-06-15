<?php
require_once "./utilities.php";
include "../vars.php";
session_destroy();
alertGoTo("已成功登出", "./login.php");