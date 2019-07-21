<?php

define('DB_HOST', 'localhost');
define('DB_USER', ' ');
define('DB_PASS', ' ');
define('DB_NAME', ' ');
define('ENCRYPTION_KEY', 'x93kc9algpw[q.c,');
define('WORKING_URL', 'http://'.$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']) . '/');

header('Access-Control-Allow-Origin: *');  

$db = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME) or die('Could not establish database connection');

?>