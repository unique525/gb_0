<?php
/**
 * Framework 包含所有类和接口文件
 * @category iCMS
 * @package iCMS_FrameWork1
 * @author zhangchi
 */
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

/****************  TOOLS   ******************/
$dir = RELATIVE_PATH . "/FrameWork1/RuleClass/Tools";
$arrFileNames = array();
$arrFiles = getFileNames($dir, $arrFileNames);
foreach ($arrFiles as $fileName) {
    include_once($fileName);

}


/****************  PLUGINS ******************/
include_once(RELATIVE_PATH . "/FrameWork1/RuleClass/Plugins/PHPExcel.php");

include_once(RELATIVE_PATH . "/FrameWork1/RuleClass/Plugins/Alipay/Alipay.php");

include_once(RELATIVE_PATH . "/FrameWork1/RuleClass/Plugins/Alipay/lib/alipay_core.function.php");
include_once(RELATIVE_PATH . "/FrameWork1/RuleClass/Plugins/Alipay/lib/alipay_md5.function.php");
include_once(RELATIVE_PATH . "/FrameWork1/RuleClass/Plugins/Alipay/lib/alipay_notify.class.php");
include_once(RELATIVE_PATH . "/FrameWork1/RuleClass/Plugins/Alipay/lib/alipay_submit.class.php");

include_once(RELATIVE_PATH . "/FrameWork1/RuleClass/Plugins/WeiXin/WxApi.php");
include_once(RELATIVE_PATH . "/FrameWork1/RuleClass/Plugins/WeiXin/WxJsSDK.php");
include_once(RELATIVE_PATH . "/FrameWork1/RuleClass/Plugins/GifEncoder.php");

/****************  TOOLS   ******************/
$dir = RELATIVE_PATH . "/FrameWork1/RuleClass/DataValueObject";
$arrFileNames = array();
$arrFiles = getFileNames($dir, $arrFileNames);
foreach ($arrFiles as $fileName) {
    include_once($fileName);

}

include_once(RELATIVE_PATH . "/FrameWork1/RuleClass/DataBase/MemoryCache.php");

$cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'system_data';
$cacheFile = 'php_files';

$arrPhpFiles = array();
$cacheArray = getCacheWithArray($cacheDir, $cacheFile);
if ($cacheArray === false) {

    $dir = RELATIVE_PATH . "/FrameWork1/RuleClass/DataBase";
    $arrPhpFiles = _genPhpFilePath($dir, $arrPhpFiles);
    $dir = RELATIVE_PATH . "/FrameWork1/RuleClass/DataProvider";
    $arrPhpFiles = _genPhpFilePath($dir, $arrPhpFiles);
    $dir = RELATIVE_PATH . "/FrameWork1/RuleClass/Gen";
    $arrPhpFiles = _genPhpFilePath($dir, $arrPhpFiles);

    addCache($cacheDir, $cacheFile, $arrPhpFiles, 360000);
} else {
    $arrPhpFiles = $cacheArray;
}
global $arrPhpFiles;

function  __autoload($className) {
    global $arrPhpFiles;


    if ($arrPhpFiles != null && array_key_exists($className, $arrPhpFiles)){


        $filePath = $arrPhpFiles[$className];

        if (is_readable($filePath)) {
            include_once($filePath);
        }

    }




}
//include once rule class


/**
$dir = RELATIVE_PATH . "/FrameWork1/RuleClass/DataBase";
$arrFiles = getFileNames($dir, $arrFileNames);
foreach ($arrFiles as $fileName) {
    include_once($fileName);
}

include_once(RELATIVE_PATH . "/FrameWork1/RuleClass/DataProvider/BaseData.php");
include_once(RELATIVE_PATH . "/FrameWork1/RuleClass/DataProvider/BasePublicData.php");
include_once(RELATIVE_PATH . "/FrameWork1/RuleClass/DataProvider/BaseClientData.php");
include_once(RELATIVE_PATH . "/FrameWork1/RuleClass/DataProvider/BaseManageData.php");
$dir = RELATIVE_PATH . "/FrameWork1/RuleClass/DataProvider";
$arrFiles = getFileNames($dir, $arrFileNames);
foreach ($arrFiles as $fileName) {
    include_once($fileName);
}

$dir = RELATIVE_PATH . "/FrameWork1/RuleClass/DataValueObject";
$arrFiles = getFileNames($dir, $arrFileNames);
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
$arrFiles = getFileNames($dir, $arrFileNames);
foreach ($arrFiles as $fileName) {
    include_once($fileName);
}

$dir = RELATIVE_PATH . "/FrameWork1/RuleClass/Tools";
$arrFiles = getFileNames($dir, $arrFileNames);
foreach ($arrFiles as $fileName) {
    include_once($fileName);
}
*/



//$dir = RELATIVE_PATH . "/FrameWork1/RuleClass/Plugins";
//$arrFiles = getFileNames($dir, $arrFileNames);
//foreach ($arrFiles as $fileName) {
    //include_once($fileName);
//}

/**
 * 输入文件夹地址，迭代循环返回此文件夹下所有文件名的列表数组
 * @param string $dir
 * @param array $arrFileNames
 * @return array 
 */
function getFileNames($dir, &$arrFileNames) {
    $arrFilePath = array();
    _getFileNames($dir, $arrFilePath, $arrFileNames);
    return $arrFilePath;
}

/**
 * getFileNames子方法
 * @param string $dir
 * @param array $arrFilePath
 * @param array $arrFileNames 
 */
function _getFileNames($dir, &$arrFilePath, &$arrFileNames) {
    if (!isset($arrFilePath)) { //没有设置数组时，初始化一下
        $arrFilePath = array();
    }
    $dh = opendir($dir);
    while ($fileName = readdir($dh))
        if ($fileName != "." && $fileName != "..") {
            if (is_dir($dir . '/' . $fileName)){
                _getFileNames($dir . '/' . $fileName, $arrFilePath, $arrFileNames);
            }else{

                if(
                    _getExtension($fileName) == "php"
                ){
                    $arrFileNames[] = str_ireplace(".php","",$fileName);
                    $arrFilePath[] = $dir . '/' . $fileName;
                }
            }
        }
    closedir($dh);
}

/**
 *
 * @param $file
 * @return string
 */
function _getExtension($file)
{
    return substr(strrchr($file, '.'), 1);
}

/**
 * 生成所有PHP文件的存储位置数组
 * @param string $dir
 * @param array $arrPhpFiles
 * @return array
 */
function _genPhpFilePath($dir, $arrPhpFiles){



    $arrFilePath = getFileNames($dir, $arrFileNames);

    if(count($arrFilePath) == count($arrFileNames)){
        for ($i = 0; $i<count($arrFilePath); $i++) {
            //print_r($fileName.'<br/>');

            $arrPhpFiles[$arrFileNames[$i]] = $arrFilePath[$i];

        }
    }

    return $arrPhpFiles;


}


function addCache($cacheDir, $cacheKey, $cacheValue, $expiration){

    if (class_exists("Memcached")){ //
        $mem = MemoryCache::getInstance();

        if (_checkCacheServerStats($mem->getStats())){
            $result = $mem->add($cacheKey,$cacheValue,$expiration);
            //$resultCode = $mem->getResultCode();
            //echo 'add '.$resultCode;
            if ($result == false){
                //替换
                $mem -> replace($cacheKey, $cacheValue);
                //$resultCode = $mem->getResultCode();
                //echo 'replace '.$resultCode;
            }
        }
        else{
            //使用文件缓存
            if(is_array($cacheValue)){
                DataCache::SetWithArray($cacheDir, $cacheKey, $cacheValue);
            }else{
                DataCache::Set($cacheDir, $cacheKey, $cacheValue);
            }
        }

    }else{
        //错误,使用文件缓存
        if(is_array($cacheValue)){
            DataCache::SetWithArray($cacheDir, $cacheKey, $cacheValue);
        }else{
            DataCache::Set($cacheDir, $cacheKey, $cacheValue);
        }
    }
}


function getCacheWithArray($cacheDir, $cacheKey){

    if(class_exists("memcached")){
        $mem = MemoryCache::getInstance();
        if (_checkCacheServerStats($mem->getStats())){
            $mem->get($cacheKey);
            if ($mem->getResultCode() == Memcached::RES_NOTFOUND){
                $result = false;
            }else{
                $result = $mem->get($cacheKey);
            }
        }else{
            $result = DataCache::GetWithArray($cacheDir . DIRECTORY_SEPARATOR . $cacheKey);

        }

    }else{

        $result = DataCache::GetWithArray($cacheDir . DIRECTORY_SEPARATOR . $cacheKey);

    }

    return $result;

}

function _checkCacheServerStats($arrStats){
    if (empty($arrStats)){
        return false;
    }else{
        return true;
    }
}

?>
