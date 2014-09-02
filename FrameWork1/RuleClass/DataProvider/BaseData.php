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
    /**
     * 站点自定义页面 数据表名
     */
    const TableName_SiteContent = "cst_site_content";
    /**
     * 站点自定义页面 数据表自增字段名
     */
    const TableId_SiteContent = "SiteContentId";
    /**
     * FTP 数据表名
     */
    const TableName_Ftp = "cst_ftp";
    /**
     * FTP 数据表自增字段名
     */
    const TableId_Ftp = "FtpId";
    /**
     * 发布日志 数据表名
     */
    const TableName_PublishLog = "cst_publish_log";
    /**
     * 发布日志 数据表自增字段名
     */
    const TableId_PublishLog = "PublishLogId";


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
    /**
     * 活动 数据表名
     */
    const TableName_Activity = "cst_activity";
    /**
     * 活动 数据表自增字段名
     */
    const TableId_Activity = "ActivityId";
    /**
     * 活动相册 数据表名
     */
    const TableName_ActivityAlbum = "cst_activity_album";
    /**
     * 活动相册 数据表自增字段名
     */
    const TableId_ActivityAlbum = "ActivityAlbumId";
    /**
     * 活动会员 数据表名
     */
    const TableName_ActivityUser = "cst_activity_user";
    /**
     * 活动会员 数据表自增字段名
     */
    const TableId_ActivityUser = "ActivityUserId";


    /**
     * 快捷内容录入 数据表名
     */
    const TableName_DocumentPreContent = "cst_document_pre_content";
    /**
     * 快捷内容录入 数据表自增字段名
     */
    const TableId_DocumentPreContent = "DocumentPreContentId";
    /**
     * 论坛版块 数据表名
     */
    const TableName_Forum = "cst_forum";
    /**
     * 论坛版块 数据表自增字段名
     */
    const TableId_Forum = "ForumId";
    /**
     * 论坛主题 数据表名
     */
    const TableName_ForumTopic = "cst_forum_topic";
    /**
     * 论坛主题 数据表自增字段名
     */
    const TableId_ForumTopic = "ForumTopicId";
    /**
     * 论坛帖子 数据表名
     */
    const TableName_ForumPost = "cst_forum_post";
    /**
     * 论坛帖子 数据表自增字段名
     */
    const TableId_ForumPost = "ForumPostId";



    /**
     * 会员 数据表名
     */
    const TableName_User = "cst_user";
    /**
     * 会员 数据表自增字段名
     */
    const TableId_User = "UserId";

    /**
     * 会员组 数据表名
     */
    const TableName_UserGroup = "cst_user_group";
    /**
     * 会员组 数据表自增字段名
     */
    const TableId_UserGroup = "UserGroupId";
    /**
     * 会员详细信息 数据表名
     */
    const TableName_UserInfo = "cst_user_info";
    /**
     * 会员详细信息 数据表自增字段名
     */
    const TableId_UserInfo = "UserId";
    /**
     * 会员订单信息 数据表名
     */
    const TableName_UserOrder = "cst_user_order";
    /**
     * 会员订单信息 数据表自增字段名
     */
    const TableId_UserOrder = "UserOrderId";
    /**
     * 会员订单产品信息 数据表名
     */
    const TableName_UserOrderProduct = "cst_user_order_product";
    /**
     * 会员订单产品信息 数据表自增字段名
     */
    const TableId_UserOrderProduct = "UserOrderProductId";
    /**
     * 会员订单产品信息 数据表名
     */
    const TableName_UserOrderPay= "cst_user_order_pay";
    /**
     * 会员订单产品信息 数据表自增字段名
     */
    const TableId_UserOrderPay = "UserOrderPayId";
    /**
     * 会员角色 数据表名
     */
    const TableName_UserRole = "cst_user_role";
    /**
     * 会员等级定义 数据表名
     */
    const TableName_UserLevel = "cst_user_level";
    /**
     * 会员等级定义 数据表自增字段名
     */
    const TableId_UserLevel = "UserLevelId";
    /**
     * 会员在对应站点的等级 数据表名
     */
    const TableName_UserSiteLevel = "cst_user_site_level";


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
     * 会员相册分类 数据表名
     */
    const TableName_UserAlbumType = "cst_user_album_type";
    /**
     * 会员相册分类 数据表自增字段名
     */
    const TableId_UserAlbumType = "UserAlbumTypeId";
    /**
     * 会员相册分类 数据表名
     */
    const TableName_UserReceiveInfo = "cst_user_receive_info";
    /**
     * 会员相册分类 数据表自增字段名
     */
    const TableId_UserReceiveInfo = "UserReceiveInfoId";

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
     * 投票调查 数据表名
     */
    const TableName_Vote = "cst_vote";
    /**
     * 投票调查 数据表自增字段名
     */
    const TableId_Vote = "VoteId";
    /**
     * 投票调查 题目 数据表名
     */
    const TableName_VoteItem = "cst_vote_item";
    /**
     * 投票调查 题目 数据表自增字段名
     */
    const TableId_VoteItem = "VoteItemId";
    /**
     * 投票调查 题目选项 数据表名
     */
    const TableName_VoteSelectItem = "cst_vote_select_item";
    /**
     * 投票调查 题目选项 数据表自增字段名
     */
    const TableId_VoteSelectItem = "VoteSelectItemId";
    /**
     * 投票调查 投票记录 数据表名
     */
    const TableName_VoteRecord = "cst_vote_record";
    /**
     * 投票调查 投票记录 数据表自增字段名
     */
    const TableId_VoteRecord = "VoteRecordId";
    /**
     * 投票调查 投票记录明细 数据表名
     */
    const TableName_VoteRecordDetail = "cst_vote_record_detail";
    /**
     * 投票调查 投票记录明细 数据表自增字段名
     */
    const TableId_VoteRecordDetail = "VoteRecordDetailId";

    /**
     * 通用来源 数据表名
     */
    const TableName_SourceCommon = "cst_source_common";
    /**
     * 通用来源 数据表自增字段名
     */
    const TableId_SourceCommon = "SourceCommonId";
    /**
     * 上传文件 数据表名
     */
    const TableName_UploadFile = "cst_upload_file";
    /**
     * 上传文件 数据表自增字段名
     */
    const TableId_UploadFile = "UploadFileId";

    /**
     * 产品 数据表名
     */
    const TableName_Product = "cst_product";
    /**
     * 产品 数据表自增字段名
     */
    const TableId_Product = "ProductId";
    /**
     * 产品参数 数据表名
     */
    const TableName_ProductParam = "cst_product_param";
    /**
     * 产品参数 数据表自增字段名
     */
    const TableId_ProductParam = "ProductParamId";
    /**
     * 产品参数类别 数据表名
     */
    const TableName_ProductParamTypeClass = "cst_product_param_type_class";
    /**
     * 产品参数类别 数据表自增字段名
     */
    const TableId_ProductParamTypeClass = "ProductParamTypeClassId";
    /**
     * 产品参数类型 数据表名
     */
    const TableName_ProductParamType = "cst_product_param_type";
    /**
     * 产品参数类型 数据表自增字段名
     */
    const TableId_ProductParamType = "ProductParamTypeId";
    /**
     * 产品参数类型选项 数据表名
     */
    const TableName_ProductParamTypeOption = "cst_product_param_type_option";
    /**
     * 产品参数类型选项 数据表自增字段名
     */
    const TableId_ProductParamTypeOption = "ProductParamTypeOptionId";
    /**
     * 产品价格 数据表名
     */
    const TableName_ProductPrice = "cst_product_price";
    /**
     * 产品价格 数据表自增字段名
     */
    const TableId_ProductPrice = "ProductPriceId";


    /**
     * 调试 数据表名
     */
    const TableName_DebugLog = "cst_debug_log";

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
        $sqlHasCount = "SELECT count(*) FROM information_schema.TABLES WHERE TABLE_NAME='$tableName'";

        $hasCount = $this->dbOperator->GetInt($sqlHasCount, null);

        if ($hasCount <= 0) {
            $sql = "CREATE TABLE if not exists $tableName LIKE $sourceTableName;";
            $this->dbOperator->Execute($sql, null);
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
            if($withCache){
                $cacheContent = DataCache::Get($cacheDir . DIRECTORY_SEPARATOR . $cacheFile);
                if (strlen($cacheContent) <= 0) {
                    $result = $this->dbOperator->GetString($sql, $dataProperty);
                } else {
                    $result = $cacheContent;
                }
            }else{
                $result = $this->dbOperator->GetString($sql, $dataProperty);
            }
        }
        return $result;
    }

}

?>
