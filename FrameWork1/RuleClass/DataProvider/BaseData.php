<?php

/**
 * 数据业务层基类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider
 * @author zhangchi
 */
class BaseData
{
    /**
     * 站点 数据表名
     */
    const TableName_Site = "cst_site";
    /**
     * 站点 数据表自增字段名
     */
    const TableId_Site = "SiteId";
    /**
     * 站点配置 数据表名
     */
    const TableName_SiteConfig = "cst_site_config";
    /**
     * 站点配置 数据表自增字段名
     */
    const TableId_SiteConfig = "SiteConfigId";

    /***********************************************************************/
    /***********************************************************************/
    /***********************************************************************/
    /**
     * 后台管理 栏目菜单 数据表名
     */
    const TableName_ManageMenuOfColumn = "cst_manage_menu_of_column";
    /**
     * 后台管理 栏目菜单 数据表自增字段名
     */
    const TableId_ManageMenuOfColumn = "ManageMenuOfColumnId";
    /**
     * 后台管理 会员管理菜单 数据表名
     */
    const TableName_ManageMenuOfUser = "cst_manage_menu_of_user";
    /**
     * 后台管理 会员管理菜单 数据表自增字段名
     */
    const TableId_ManageMenuOfUser = "ManageMenuOfUserId";
    /**
     * 管理分组 数据表名
     */
    const TableName_ManageUserGroup = "cst_manage_user_group";
    /**
     * 管理分组 数据表自增字段名
     */
    const TableId_ManageUserGroup = "ManageUserGroupId";
    /**
     * 管理操作日志 数据表名
     */
    const TableName_ManageUserLog = "cst_manage_user_log";
    /**
     * 管理操作日志 数据表自增字段名
     */
    const TableId_ManageUserLog = "ManageUserLogId";
    /**
     * 管理员 数据表名
     */
    const TableName_ManageUser = "cst_manage_user";
    /**
     * 管理员 数据表自增字段名
     */
    const TableId_ManageUser = "ManageUserId";
    /**
     * 管理权限 数据表名
     */
    const TableName_ManageUserAuthority = "cst_manage_user_authority";
    /**
     * 管理权限 数据表自增字段名
     */
    const TableId_ManageUserAuthority = "ManageUserAuthorityId";
    /***********************************************************************/
    /***********************************************************************/
    /***********************************************************************/
    /**
     * 频道 数据表名
     */
    const TableName_Channel = "cst_channel";
    /**
     * 频道 数据表自增字段名
     */
    const TableId_Channel = "ChannelId";
    /**
     * 频道模板 数据表名
     */
    const TableName_ChannelTemplate = "cst_channel_template";
    /**
     * 频道模板 数据表自增字段名
     */
    const TableId_ChannelTemplate = "ChannelTemplateId";
    /**
     * 新闻资讯 数据表名
     */
    const TableName_DocumentNews = "cst_document_news";
    /**
     * 新闻资讯 数据表自增字段名
     */
    const TableId_DocumentNews = "DocumentNewsId";
    const TableName_Forum = "cst_forum";
    const TableId_Forum = "ForumId";
    const TableName_ForumTopic = "cst_forum_topic";
    const TableId_ForumTopic = "ForumTopicId";
    /**
     * 会员 数据表名
     */
    const TableName_User = "cst_user";
    /**
     * 会员 数据表自增字段名
     */
    const TableId_User = "UserId";
    /**
     * 会员详细信息 数据表名
     */
    const TableName_UserInfo = "cst_user_info";
    /**
     * 会员角色 数据表名
     */
    const TableName_UserRole = "cst_user_role";


    /**
     * 会员相册 数据表名
     */
    const TableName_UserAlbum = "cst_user_album";
    /**
     * 会员相册 数据表自增字段名
     */
    const TableId_UserAlbum = "UserAlbumId";
    /**
     * 会员相册图片 数据表名
     */
    const TableName_UserAlbumPic = "cst_user_album_pic";
    /**
     * 会员相册图片 数据表自增字段名
     */
    const TableId_UserAlbumPic = "UserAlbumPicId";

    /**
     * 自定义表单 数据表名
     */
    const TableName_CustomForm = "cst_custom_form";
    /**
     * 自定义表单 数据表自增字段名
     */
    const TableId_CustomForm = "CustomFormId";
    /**
     * 自定义表单 存储内容 数据表名
     */
    const TableName_CustomFormContent = "cst_custom_form_content";
    /**
     * 自定义表单 存储内容 数据表自增字段名
     */
    const TableId_CustomFormContent = "CustomFormContentId";
    /**
     * 自定义表单 字段定义 数据表名
     */
    const TableName_CustomFormField = "cst_custom_form_field";
    /**
     * 自定义表单 字段定义 数据表自增字段名
     */
    const TableId_CustomFormField = "CustomFormFieldId";
    /**
     * 自定义表单 数据记录 数据表名
     */
    const TableName_CustomFormRecord = "cst_custom_form_record";
    /**
     * 自定义表单 数据记录 数据表自增字段名
     */
    const TableId_CustomFormRecord = "CustomFormRecordId";



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
        $sqlHasCount = "SELECT count(*) FROM information_schema.TABLES WHERE TABLE_NAME='$tableName'";

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