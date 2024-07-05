<?php
define("ROOT_PATH", dirname(__FILE__));

require_once('inc/functions.php');

require_once 'layout/header.php';

$file = getCurrentFilePath();

if (file_exists($file)) {
    require_once $file;
}

require_once 'layout/footer.php';
