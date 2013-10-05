<?php

date_default_timezone_set('Asia/Shanghai'); //'Asia/Shanghai' 亚洲/上海
//include_once(ROOTPATH . "/Rules/Gen/BaseGen.php");
//include once
$dir = ROOTPATH . "/Rules/Tools";
$arrFiles = getFileNames($dir);
foreach ($arrFiles as $fileName) {
    include_once($fileName);
}


//include once
$dir = ROOTPATH . "/Rules/DataBase";
$arrFiles = getFileNames($dir);
foreach ($arrFiles as $fileName) {
    include_once($fileName);
}

include_once(ROOTPATH . "/Rules/DataProvider/BaseData.php");
include_once(ROOTPATH . "/Rules/DataProvider/CommonData.php");
//include once
$dir = ROOTPATH . "/Rules/DataProvider";
$arrFiles = getFileNames($dir);
foreach ($arrFiles as $fileName) {
    include_once($fileName);
}


include_once(ROOTPATH . "/Rules/Gen/BaseGen.php");
include_once(ROOTPATH . "/Rules/Gen/IBaseGen.php");
include_once(ROOTPATH . "/Rules/Gen/IBaseManageGen.Interface.php");
//include once
$dir = ROOTPATH . "/Rules/Gen";
$arrFiles = getFileNames($dir);
foreach ($arrFiles as $fileName) {
    include_once($fileName);
}

//xunsearch全文检索引擎
$xunfile = '/usr/local/xunsearch/sdk/php/lib/XS.php';
if (file_exists($xunfile)) {
    require_once $xunfile;
} else {
    
}

function get_html(IBaseGen $gen) {
    header("Content-type: text/html; charset=utf-8");
    return $gen->Gen();
}

/**
 * 输入文件夹地址，迭代循环返回此文件夹下所有文件名的列表数组
 * @param type $dir
 * @return array 
 */
function getFileNames($dir) {
    $_arr_filenames = array();
    _getFileNames($dir, $_arr_filenames);
    return $_arr_filenames;
}

/**
 * get_filenames子方法
 * @param type $dir
 * @param type $arr_filenames 
 */
function _getFileNames($dir, &$arr_filenames) {
    if (!isset($arr_filenames)) { //没有设置数组时，初始化一下
        $arr_filenames = array();
    }
    $dh = opendir($dir);
    while ($filename = readdir($dh))
        if ($filename != "." && $filename != "..") {
            if (is_file($dir . '/' . $filename)) {
                if (strtolower(_get_ex($filename)) == 'php') {
                    $arr_filenames[] = $dir . '/' . $filename;
                }
            }
            if (is_dir($dir . '/' . $filename)) {
                _getFileNames($dir . '/' . $filename, $arr_filenames);
            }
        }
    closedir($dh);
}

function _get_ex($filename) {
    $index = strrpos($filename, '.');
    $fileex = substr($filename, $index + 1);
    return $fileex;
}

?>
