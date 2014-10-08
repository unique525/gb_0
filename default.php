<?php
define('RELATIVE_PATH', '.');
define('PHYSICAL_PATH', str_ireplace('default.php','',realpath(__FILE__)));
define("CACHE_PATH", "cache");
include_once(RELATIVE_PATH . '/FrameWork1/start.php');
?>
