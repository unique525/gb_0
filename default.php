<?php
$timeStart = GetMicroTime();
define('RELATIVE_PATH', '.');
define('PHYSICAL_PATH', str_ireplace('default.php','',realpath(__FILE__)));
define("CACHE_PATH", "cache");
include_once(RELATIVE_PATH . '/FrameWork1/start.php');
$timeEnd = GetMicroTime();
//echo "EXEC_TIME:".($timeEnd - $timeStart)."<br />";

function GetMicroTime() {
    $time = explode(" ", microtime());
    $time = $time[1] . ($time [0] * 1000);
    $time2 = explode(".", $time);
    $time = $time2[0];
    if(strlen($time) == 11){
        $time = $time.'000';
    }
    if(strlen($time) == 12){
        $time = $time.'00';
    }
    if(strlen($time) == 13){
        $time = $time.'0';
    }
    return floatval($time);
}
?>
