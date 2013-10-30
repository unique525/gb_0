<?php

/**
 * 数据业务层基类 
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider
 * @author zhangchi
 */
class BaseData {
    
    /**
     * 数据库操作对象的实例
     * @var DbOperator 返回数据库操作对象 
     */
    protected $dbOperator = null;
    function __construct() {
        $this->dbOperator = DbOperator::getInstance();
    }
    
    /**
     * 创建数据分表
     * @param type $sourceTableName 原始表
     * @return string 返回分表表名
     */
    protected function CreateAndGetTableName($sourceTableName) {
        $nowYear = date('Y');
        $nowMonth = date('m');

        $tableName = $sourceTableName . "_" . $nowYear . $nowMonth;
        $dbOperator = DBOperator::getInstance();
        $sqlHasCount = "SELECT COUNT(*) FROM information_schema.TABLES WHERE TABLE_NAME='$tableName'";
        
        $hasCount = $dbOperator->ReturnInt($sqlHasCount, null);
       
        if ($hasCount <= 0) {
            $sql = "CREATE TABLE if not exists $tableName LIKE $sourceTableName;";
            $dbOperator->Execute($sql, null);
        }

        return $tableName;
    }
    
    /**
     * 判断指定路径的数据是否缓存
     * @param string $cacheDir 缓存目录
     * @param string $cacheFile 缓存文件
     * @return boolean 是否缓存
     */
    protected function IsDataCached($cacheDir,$cacheFile){
        if(strlen(DataCache::Get($cacheDir . DIRECTORY_SEPARATOR . $cacheFile)) <= 0){
            return FALSE;
        }else{
            return TRUE;
        }                
    }
}

?>
