<?php

/**
 * 后台管理 频道模板 数据类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_Channel
 * @author zhangchi
 */
class ChannelTemplateManageData extends BaseManageData
{

    /**
     * 取得字段数据集
     * @param string $tableName 表名
     * @return array 字段数据集
     */
    public function GetFields($tableName = self::TableName_ChannelTemplate){
        return parent::GetFields(self::TableName_ChannelTemplate);
    }

    /**
     * 新增频道模板
     * @param array $httpPostData $_POST数组
     * @param int $manageUserId 后台管理员id
     * @param int $channelId 频道id
     * @param int $siteId 站点id
     * @return int 新增的频道模板id
     */
    public function Create($httpPostData,$manageUserId,$channelId,$siteId)
    {
        $result = -1;
        $dataProperty = new DataProperty();
        if (!empty($httpPostData)) {


            $addFieldName = "";
            $addFieldValue = "";
            $preNumber = "";
            $addFieldNames = array(
                "ManageUserId","ChannelId","SiteId","CreateDate");
            $addFieldValues = array($manageUserId,$channelId,$siteId,date("Y-m-d H:i:s", time()));

            $sql = parent::GetInsertSql(
                $httpPostData,
                self::TableName_ChannelTemplate,
                $dataProperty,
                $addFieldName,
                $addFieldValue,
                $preNumber,
                $addFieldNames,
                $addFieldValues
            );
            $result = $this->dbOperator->LastInsertId($sql, $dataProperty);
        }


        return $result;
    }


    /**
     * 修改
     * @param array $httpPostData $_POST数组
     * @param int $channelTemplateId 模板id
     * @return int 返回影响的行数
     */
    public function Modify($httpPostData, $channelTemplateId)
    {
        $result = -1;
        if($channelTemplateId>0){

            $dataProperty = new DataProperty();
            $addFieldName = "";
            $addFieldValue = "";
            $preNumber = "";
            $addFieldNames = array();
            $addFieldValues = array();
            if (!empty($httpPostData)) {
                $sql = parent::GetUpdateSql(
                    $httpPostData,
                    self::TableName_ChannelTemplate,
                    self::TableId_ChannelTemplate,
                    $channelTemplateId,
                    $dataProperty,
                    $addFieldName,
                    $addFieldValue,
                    $preNumber,
                    $addFieldNames,
                    $addFieldValues
                );
                $result = $this->dbOperator->Execute($sql, $dataProperty);
            }

        }

        return $result;
    }


    /**
     * 修改状态
     * @param int $channelTemplateId 模板id
     * @param int $state 状态
     * @return int 操作结果
     */
    public function ModifyState($channelTemplateId, $state)
    {
        $result = 0;
        if ($channelTemplateId > 0) {
            $dataProperty = new DataProperty();
            $sql = "UPDATE " . self::TableName_ChannelTemplate . "
                        SET `State`=:State WHERE ".self::TableId_ChannelTemplate."=:".self::TableId_ChannelTemplate.";";
            $dataProperty->AddField(self::TableId_ChannelTemplate, $channelTemplateId);
            $dataProperty->AddField("State", $state);
            $result = $this->dbOperator->Execute($sql, $dataProperty);
        }
        return $result;
    }

    /**
     * 修改模板附件
     * @param int $channelTemplateId 模板id
     * @param string $attachment 读入到字符串中的附件
     * @return int 修改结果
     */
    public function ModifyAttachment($channelTemplateId, $attachment) {

        $result = -1;

        if($channelTemplateId>0 && !empty($attachment)){
            $sql = "UPDATE
                        " . self::TableName_ChannelTemplate . "
                    SET
                        Attachment=:Attachment
                    WHERE
                        ChannelTemplateId=:ChannelTemplateId;";
            $dataProperty = new DataProperty();
            //$dataProperty->AddField("attachment", $attachment, PDO::PARAM_LOB);
            $dataProperty->AddField("Attachment", $attachment);
            $dataProperty->AddField("ChannelTemplateId", $channelTemplateId);
            $result = $this->dbOperator->Execute($sql, $dataProperty);
        }

        return $result;
    }

    /**
     * 删除附件
     * @param int $channelTemplateId 模板id
     * @return int 操作影响的行数
     */
    public function DeleteAttachment($channelTemplateId){
        $result = -1;
        if($channelTemplateId>0){
            $sql = "UPDATE " . self::TableName_ChannelTemplate . "
                    SET Attachment = null
                    WHERE ChannelTemplateId=:ChannelTemplateId
                    ;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("ChannelTemplateId", $channelTemplateId);
            $result = $this->dbOperator->Execute($sql, $dataProperty);
        }
        return $result;
    }


    /**
     * 返回一行数据
     * @param int $channelTemplateId 模板id
     * @return array|null 取得对应数组
     */
    public function GetOne($channelTemplateId){
        $result = null;
        if($channelTemplateId>0){
            $sql = "SELECT * FROM
                        " . self::TableName_ChannelTemplate . "
                    WHERE ChannelTemplateId=:ChannelTemplateId
                    ;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("ChannelTemplateId", $channelTemplateId);
            $result = $this->dbOperator->GetArray($sql, $dataProperty);
        }
        return $result;
    }


    /**
     * 取得频道模板的附件
     * @param int $channelTemplateId 频道模板id
     * @return string 频道模板的附件
     */
    public function GetAttachment($channelTemplateId) {
        $result = null;
        if($channelTemplateId>0){
            $sql = "SELECT
                    Attachment
                FROM
                    " . self::TableName_ChannelTemplate . "
                WHERE ChannelTemplateId=:ChannelTemplateId";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("ChannelTemplateId", $channelTemplateId);
            $result = $this->dbOperator->GetLob($sql, $dataProperty);

        }
        return $result;
    }

    /**
     * 取得频道模板的内容
     * @param int $channelTemplateId 频道模板id
     * @param bool $withCache 是否从缓冲中取
     * @return string 频道模板的内容
     */
    public function GetChannelTemplateContent($channelTemplateId, $withCache)
    {
        $result = "";
        if ($channelTemplateId > 0) {
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'channel_template_data';
            $cacheFile = 'channel_template_get_channel_template_content.cache_' . $channelTemplateId . '';
            $sql = "SELECT ChannelTemplateContent FROM " . self::TableName_ChannelTemplate . " WHERE ChannelTemplateId = :ChannelTemplateId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("ChannelTemplateId", $channelTemplateId);
            $result = $this->GetInfoOfStringValue($sql, $dataProperty, $withCache, $cacheDir, $cacheFile);
        }
        return $result;
    }

    /**
     * 取得频道模板发布方式
     * @param int $channelTemplateId 频道模板id
     * @param bool $withCache 是否从缓冲中取
     * @return int 频道模板的发布方式
     */
    public function GetPublishType($channelTemplateId, $withCache)
    {
        $result = -1;
        if ($channelTemplateId > 0) {
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'channel_template_data';
            $cacheFile = 'channel_template_get_publish_type.cache_' . $channelTemplateId . '';
            $sql = "SELECT PublishType FROM " . self::TableName_ChannelTemplate . " WHERE ChannelTemplateId = :ChannelTemplateId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("ChannelTemplateId", $channelTemplateId);
            $result = $this->GetInfoOfIntValue($sql, $dataProperty, $withCache, $cacheDir, $cacheFile);
        }
        return $result;
    }


    /**
     * 取得频道模板站点id
     * @param int $channelTemplateId 频道模板id
     * @param bool $withCache 是否从缓冲中取
     * @return int 频道模板的站点id
     */
    public function GetChannelId($channelTemplateId, $withCache)
    {
        $result = -1;
        if ($channelTemplateId > 0) {
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'channel_template_data';
            $cacheFile = 'channel_template_get_channel_id.cache_' . $channelTemplateId . '';
            $sql = "SELECT ChannelId FROM " . self::TableName_ChannelTemplate . " WHERE ChannelTemplateId = :ChannelTemplateId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("ChannelTemplateId", $channelTemplateId);
            $result = $this->GetInfoOfIntValue($sql, $dataProperty, $withCache, $cacheDir, $cacheFile);
        }
        return $result;
    }

    /**
     * 取得频道模板站点id
     * @param int $channelTemplateId 频道模板id
     * @param bool $withCache 是否从缓冲中取
     * @return int 频道模板的站点id
     */
    public function GetSiteId($channelTemplateId, $withCache)
    {
        $result = -1;
        if ($channelTemplateId > 0) {
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'channel_template_data';
            $cacheFile = 'channel_template_get_site_id.cache_' . $channelTemplateId . '';
            $sql = "SELECT SiteId FROM " . self::TableName_ChannelTemplate . " WHERE ChannelTemplateId = :ChannelTemplateId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("ChannelTemplateId", $channelTemplateId);
            $result = $this->GetInfoOfIntValue($sql, $dataProperty, $withCache, $cacheDir, $cacheFile);
        }
        return $result;
    }

    /**
     * 取得当前频道下所有非详细页发布模板
     * @param int $channelId 频道id
     * @return array|null 发布模板
     */
    public function GetListForPublish($channelId){
        $result = null;

        if($channelId>0){
            $sql = "SELECT * FROM " . self::TableName_ChannelTemplate . "
             WHERE ChannelId=:ChannelId
                AND
                   State<".ChannelTemplateData::STATE_REMOVED."
                AND
                   PublishType IN (
                            ".ChannelTemplateData::PUBLISH_TYPE_LINKAGE_ALL.",
                            ".ChannelTemplateData::PUBLISH_TYPE_LINKAGE_ONLY_SELF.",
                            ".ChannelTemplateData::PUBLISH_TYPE_LINKAGE_ONLY_TRIGGER.",
                            ".ChannelTemplateData::PUBLISH_TYPE_ONLY_SELF."
                            );";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("ChannelId", $channelId);
            $result = $this->dbOperator->GetArrayList($sql, $dataProperty);
        }
        return $result;
    }


    /**
     * 取得当前频道下所有模板，管理模板使用
     * @param int $channelId 频道id
     * @return array|null 发布模板
     */
    public function GetListForManage($channelId){
        $result = null;

        if($channelId>0){
            $sql = "SELECT
                        ct.*,
                        length(ct.Attachment) as AttachmentLength,
                        mu.ManageUserName
                    FROM
                        " . self::TableName_ChannelTemplate . " ct,
                        " . self::TableName_ManageUser." mu
                    WHERE ct.ChannelId=:ChannelId
                        AND ct.ManageUserId=mu.ManageUserId
                    ;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("ChannelId", $channelId);
            $result = $this->dbOperator->GetArrayList($sql, $dataProperty);
        }
        return $result;
    }

    /**
     * 取得当前频道下对应发布方式的模板
     * @param int $channelId 频道id
     * @param int $publishType 发布方式
     * @param int $channelTemplateType 模板类型 0普通 1动态
     * @return array|null 发布模板
     */
    public function GetListByPublishType(
        $channelId,
        $publishType,
        $channelTemplateType = ChannelTemplateData::CHANNEL_TEMPLATE_TYPE_NORMAL){

        $result = null;

        if($channelId>0){
            $sql = "SELECT * FROM " . self::TableName_ChannelTemplate . "
             WHERE ChannelId=:ChannelId
                AND
                   State<".ChannelTemplateData::STATE_REMOVED."
                AND
                   PublishType = :PublishType
                AND
                   ChannelTemplateType = :ChannelTemplateType
                   ;";

            $dataProperty = new DataProperty();
            $dataProperty->AddField("ChannelId", $channelId);
            $dataProperty->AddField("PublishType", $publishType);
            $dataProperty->AddField("ChannelTemplateType", $channelTemplateType);
            $result = $this->dbOperator->GetArrayList($sql, $dataProperty);
        }
        return $result;

    }

    /**
     * 删除
     * @param $channelTemplateId
     * @return int 结果
     */
    public function Delete($channelTemplateId){
        $result=-1;
        if($channelTemplateId>0){
            $sql="DELETE FROM " . self::TableName_ChannelTemplate . "
             WHERE ChannelTemplateId=:ChannelTemplateId ;";


            $dataProperty = new DataProperty();
            $dataProperty->AddField("ChannelTemplateId", $channelTemplateId);
            $result = $this->dbOperator->Execute($sql, $dataProperty);
        }
        return $result;
    }
} 