<?php
/**
 * Framework 包含所有类和接口文件
 * @category iCMS
 * @package iCMS_FrameWork1
 * @author zhangchi
 */
//include once rule class

$dir = RELATIVE_PATH . "/FrameWork1/RuleClass/DataBase";
$arrFiles = getFileNames($dir);
foreach ($arrFiles as $fileName) {
    include_once($fileName);
}

include_once(RELATIVE_PATH . "/FrameWork1/RuleClass/DataProvider/BaseData.Class.php");
include_once(RELATIVE_PATH . "/FrameWork1/RuleClass/DataProvider/BasePublicData.Class.php");
include_once(RELATIVE_PATH . "/FrameWork1/RuleClass/DataProvider/BaseOfManageData.Class.php");
$dir = RELATIVE_PATH . "/FrameWork1/RuleClass/DataProvider";
$arrFiles = getFileNames($dir);
foreach ($arrFiles as $fileName) {
    include_once($fileName);
}

include_once(RELATIVE_PATH . "/FrameWork1/RuleClass/Gen/BaseGen.Class.php");
include_once(RELATIVE_PATH . "/FrameWork1/RuleClass/Gen/BasePublicGen.Class.php");
include_once(RELATIVE_PATH . "/FrameWork1/RuleClass/Gen/BaseOfManageGen.Class.php");
include_once(RELATIVE_PATH . "/FrameWork1/RuleClass/Gen/IBasePublicGen.Interface.php");
include_once(RELATIVE_PATH . "/FrameWork1/RuleClass/Gen/IBaseManageGen.Interface.php");
//include once rule class
$dir = RELATIVE_PATH . "/FrameWork1/RuleClass/Gen";
$arrFiles = getFileNames($dir);
foreach ($arrFiles as $fileName) {
    include_once($fileName);
}

$dir = RELATIVE_PATH . "/FrameWork1/RuleClass/Tools";
$arrFiles = getFileNames($dir);
foreach ($arrFiles as $fileName) {
    include_once($fileName);
}

/**
 * 输入文件夹地址，迭代循环返回此文件夹下所有文件名的列表数组
 * @param string $dir
 * @return array 
 */
function getFileNames($dir) {
    $arrFileNames = array();
    _getFileNames($dir, $arrFileNames);
    return $arrFileNames;
}

/**
 * get_filenames子方法
 * @param string $dir
 * @param array $arrFileNames 
 */
function _getFileNames($dir, &$arrFileNames) {
    if (!isset($arrFileNames)) { //没有设置数组时，初始化一下
        $arrFileNames = array();
    }
    $dh = opendir($dir);
    while ($fileName = readdir($dh))
        if ($fileName != "." && $fileName != "..") {
            if (is_dir($dir . '/' . $fileName))
                _getFileNames($dir . '/' . $fileName, $arrFileNames);
            else
                $arrFileNames[] = $dir . '/' . $fileName;
        }
    closedir($dh);
}

?>
