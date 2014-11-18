<?php
/**
 * Framework 包含所有类和接口文件
 * @category iCMS
 * @package iCMS_FrameWork1
 * @author zhangchi
 */
//include once rule class

$dir = RELATIVE_PATH . "/FrameWork1/SystemInc/config.inc.php";
if(file_exists($dir)){
    require $dir;
    if(!isset($incDomain['webapp']) || !isset($incDomain['manage'])){
        die('domain is null');
    }
    define('WEBAPP_DOMAIN', $incDomain['webapp']);
    define('MANAGE_DOMAIN', $incDomain['manage']);
    define('DATABASE_INFO', $incDatabaseInfo);
    define('SYSTEM_NAME', $incSystemName);
    define('LANGUAGE', $incLanguage);
    define('OPEN_MANAGE_USER_LOG', $incOpenManageUserLog);
    define('SECURITY_IP',$incSecurityIP);

}else{
    die("config file not found.");
}

$dir = RELATIVE_PATH . "/FrameWork1/RuleClass/DataBase";
$arrFiles = getFileNames($dir);
foreach ($arrFiles as $fileName) {
    include_once($fileName);
}

include_once(RELATIVE_PATH . "/FrameWork1/RuleClass/DataProvider/BaseData.php");
include_once(RELATIVE_PATH . "/FrameWork1/RuleClass/DataProvider/BasePublicData.php");
include_once(RELATIVE_PATH . "/FrameWork1/RuleClass/DataProvider/BaseClientData.php");
include_once(RELATIVE_PATH . "/FrameWork1/RuleClass/DataProvider/BaseManageData.php");
$dir = RELATIVE_PATH . "/FrameWork1/RuleClass/DataProvider";
$arrFiles = getFileNames($dir);
foreach ($arrFiles as $fileName) {
    include_once($fileName);
}

$dir = RELATIVE_PATH . "/FrameWork1/RuleClass/DataValueObject";
$arrFiles = getFileNames($dir);
foreach ($arrFiles as $fileName) {
    include_once($fileName);
}

include_once(RELATIVE_PATH . "/FrameWork1/RuleClass/Gen/BaseGen.php");
include_once(RELATIVE_PATH . "/FrameWork1/RuleClass/Gen/BasePublicGen.php");
include_once(RELATIVE_PATH . "/FrameWork1/RuleClass/Gen/BaseManageGen.php");
include_once(RELATIVE_PATH . "/FrameWork1/RuleClass/Gen/BaseClientGen.php");
include_once(RELATIVE_PATH . "/FrameWork1/RuleClass/Gen/IBasePublicGen.php");
include_once(RELATIVE_PATH . "/FrameWork1/RuleClass/Gen/IBaseManageGen.php");
include_once(RELATIVE_PATH . "/FrameWork1/RuleClass/Gen/IBaseClientGen.php");
include_once(RELATIVE_PATH . "/FrameWork1/RuleClass/Gen/Forum/ForumBasePublicGen.php");
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

$dir = RELATIVE_PATH . "/FrameWork1/RuleClass/Plugins";
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
 * getFileNames子方法
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
            if (is_dir($dir . '/' . $fileName)){
                _getFileNames($dir . '/' . $fileName, $arrFileNames);
            }else{
                $arrFileNames[] = $dir . '/' . $fileName;
            }
        }
    closedir($dh);
}

?>
