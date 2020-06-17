<?php
session_start();
$timezone = "Asia/Calcutta";
if(function_exists('date_default_timezone_set')) date_default_timezone_set($timezone);
//require_once 'DB.class.php';
require_once 'DB2.class.php';
//$db = new DB();
$db2 = new DB2();
?>