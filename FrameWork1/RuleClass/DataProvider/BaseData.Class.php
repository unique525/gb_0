<?php

/**
 * 数据业务层基类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider
 * @author zhangchi
 */
class BaseData
{

    const TableName_Site = "cst_site";
    const TableId_Site = "SiteId";
    const TableName_SiteConfig = "cst_site_config";
    const TableId_SiteConfig = "SiteConfigId";
    const TableName_AdminLeftNav = "cst_admin_left_nav";
    const TableId_AdminLeftNav = "AdminLeftNavId";
    /**
     *
     */
    const TableName_ManageMenuOfUser = "cst_manage_menu_of_user";
    const TableId_ManageMenuOfUser = "ManageMenuOfUserId";
    const TableName_AdminUserGroup = "cst_admin_user_group";
    const TableId_AdminUserGroup = "AdminUserGroupId";
    const TableName_AdminUserLog = "cst_admin_user_log";
    const TableId_AdminUserLog = "AdminUserLogId";
    const TableName_AdminUser = "cst_admin_user";
    const TableId_AdminUser = "AdminUserId";
    const TableName_AdminPopedom = "cst_admin_popedom";
    const TableName_Channel = "cst_channel";
    const TableId_Channel = "ChannelId";
    const TableName_DocumentNews = "cst_document_news";
    const TableId_DocumentNews = "DocumentNewsId";
    const TableName_Forum = "cst_forum";
    const TableId_Forum = "ForumId";
    const TableName_ForumTopic = "cst_forum_topic";
    const TableId_ForumTopic = "ForumTopicId";

    /**
     * 数据库操作对象的实例
     * @var DbOperator 返回数据库操作对象
     */
    protected $dbOperator = null;

    function __construct()
    {
        $this->dbOperator = DbOperator::getInstance();
    }

    /**
     * 创建数据分表
     * @param string $sourceTableName 原始表
     * @return string 返回分表表名
     */
    protected function CreateAndGetTableName($sourceTableName)
    {
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
    protected function IsDataCached($cacheDir, $cacheFile)
    {
        if (strlen(DataCache::Get($cacheDir . DIRECTORY_SEPARATOR . $cacheFile)) <= 0) {
            return FALSE;
        } else {
            return TRUE;
        }
    }

    /**
     * 返回int型的信息值（某行某字段的值）
     * @param string $sql 要执行的SQL语句
     * @param DataProperty $dataProperty 数据库字段集合对象
     * @param boolean $withCache 是否从缓存中取
     * @param string $cacheDir 缓存文件夹
     * @param string $cacheFile 缓存文件名
     * @return int 返回查询结果
     */
    protected function GetInfoOfIntValue($sql, DataProperty $dataProperty, $withCache, $cacheDir, $cacheFile)
    {
        $result = -1;
        if (strlen($sql) > 0) {
            $result = intval(self::GetInfoOfStringValue($sql, $dataProperty, $withCache, $cacheDir, $cacheFile));
        }
        return $result;
    }

    /**
     * 返回string型的信息值（某行某字段的值）
     * @param string $sql 要执行的SQL语句
     * @param DataProperty $dataProperty 数据库字段集合对象
     * @param boolean $withCache 是否从缓存中取
     * @param string $cacheDir 缓存文件夹
     * @param string $cacheFile 缓存文件名
     * @return string 返回查询结果
     */
    protected function GetInfoOfStringValue($sql, DataProperty $dataProperty, $withCache, $cacheDir, $cacheFile)
    {
        $result = "";
        if (strlen($sql) > 0) {
            $cacheContent = DataCache::Get($cacheDir . DIRECTORY_SEPARATOR . $cacheFile);
            if (!$withCache) {
                $cacheContent = "";
            }
            if (strlen($cacheContent) <= 0) {
                $result = $this->dbOperator->GetInt($sql, $dataProperty);
            } else {
                $result = $cacheContent;
            }
        }
        return $result;
    }

}

?>
