<?php

require_once 'inc/global.inc.php';

$res = $db2->getQuery("SHOW TABLES");
print_r($res);
?>