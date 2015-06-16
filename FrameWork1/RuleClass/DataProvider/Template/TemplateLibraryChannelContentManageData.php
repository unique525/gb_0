<?php

/**
 * 模板库应用到频道的模板内容的数据处理类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_Template
 * @author 525
 */
class TemplateLibraryChannelContentManageData extends BaseManageData {

    /**
     * 新增频道模板
     * @param array $httpPostData $_POST数组
     * @return int 新增的频道模板id
     */
    public function Create($httpPostData)
    {
        $result = -1;
        $dataProperty = new DataProperty();
        if (!empty($httpPostData)) {


            $addFieldName = "";
            $addFieldValue = "";
            $preNumber = "";
            $addFieldNames = array();
            $addFieldValues = array();

            $sql = parent::GetInsertSql(
                $httpPostData,
                self::TableName_TemplateLibraryChannelContent,
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
     * 根据频道id返回对应的模板库中的所有启用的模板内容数据
     * @param int $channelId
     * @return array 数据集
     */
    public function GetList($channelId) {
        $sql = "SELECT *,LENGTH(Attachment) AS AttachmentLength FROM " . self::TableName_TemplateLibraryChannelContent . " WHERE State<100 AND ChannelId=:ChannelId;";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("ChannelId", $channelId);
        $result = $this->dbOperator->GetArrayList($sql, $dataProperty);
        return $result;
    }
    
    /**
     * 修改
     * @param <type> $httpPostData
     * @param <type> $templateLibraryChannelContentId
     * @return int
     */
    public function Modify($httpPostData,$templateLibraryChannelContentId) {
        $dataProperty = new DataProperty();
        $sql = parent::GetUpdateSql($httpPostData, self::TableName_TemplateLibraryChannelContent, self::TableId_TemplateLibraryChannelContent, $templateLibraryChannelContentId, $dataProperty);
        $result = $this->dbOperator->Execute($sql, $dataProperty);
        return $result;
    }

    /**
     * 取得发布频道时用的列表数据
     * @param int $channelId
     * @return array 数据集
     */
    public function GetListForPublish($channelId) {
        $result = null;

        if($channelId>0){
            $sql = "SELECT
            *,
            TemplateContent AS ChannelTemplateContent,
            TemplateContentForMobile AS ChannelTemplateContentForMobile,
            TemplateContentForPad AS ChannelTemplateContentForPad,
            TemplateContentForTV AS ChannelTemplateContentForTV,
            1 AS IsTemplateLibrary
             FROM " . self::TableName_TemplateLibraryChannelContent . "
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
     * 根据TemplateLibraryChannelContentId取得附件
     * @param int $templateLibraryChannelContentId
     * @return object 附件
     */
    public function GetAttachmentById($templateLibraryChannelContentId) {
        if ($templateLibraryChannelContentId > 0) {
            $sql = "SELECT Attachment FROM " . self::TableName_TemplateLibraryChannelContent . " WHERE TemplateLibraryChannelContentId=:TemplateLibraryChannelContentId";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("TemplateLibraryChannelContentId", $templateLibraryChannelContentId);
            $result = $this->dbOperator->GetLob($sql, $dataProperty);
            return $result;
        } else {
            return null;
        }
    }
    
    /**
     * 新建模板内容
     * @param int $templateLibraryId
     * @param int $templateLibraryChannelId
     * @param int $channelId
     * @param int $siteId
     * @param string $createDate
     * @return int
     */
    public function CreateTemplateChannelContent($templateLibraryId,$templateLibraryChannelId,$channelId,$siteId,$createDate){
        $sql ="INSERT INTO " . self::TableName_TemplateLibraryChannelContent . "
         (`TemplateName`, `TemplateContent`, `TemplateContentForMobile`, `TemplateContentForPad`, `TemplateContentForTV`, `PublishFileName`, `State`, `CreateDate`, `Remark`, `TemplateLibraryId`, `SiteId`, `Attachment`, `AttachmentName`, `PublishType`, `TemplateType`, `TemplateTag`, `IsAddVisitCode`, `ChannelId`)
    SELECT `TemplateName`, `TemplateContent`, `TemplateContentForMobile`, `TemplateContentForPad`, `TemplateContentForTV`, `PublishFileName`, `State`, '$createDate', `Remark`, `TemplateLibraryId`, $siteId, `Attachment`, `AttachmentName`, `PublishType`, `TemplateType`, `TemplateTag`, `IsAddVisitCode`, $channelId
FROM " . self::TableName_TemplateLibraryContent . " WHERE TemplateLibraryId=:TemplateLibraryId AND TemplateLibraryChannelId=:TemplateLibraryChannelId ;
";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("TemplateLibraryId", $templateLibraryId);
        $dataProperty->AddField("TemplateLibraryChannelId", $templateLibraryChannelId);
        $result = $this->dbOperator->LastInsertId($sql,$dataProperty);
        return $result;
    }

    /**
     * 取得模板内容来修改模板库的channel_id
     * @param int $templateLibraryId
     * @param int $channelId
     * @return array
     */
    public function GetContentForUpdate($templateLibraryId,$channelId){
        $result=null;
        if($templateLibraryId>0){
            $sql="SELECT TemplateLibraryChannelContentId,TemplateContent,TemplateContentForMobile,TemplateContentForPad,TemplateContentForTV
            FROM " . self::TableName_TemplateLibraryChannelContent . " WHERE TemplateLibraryId=:TemplateLibraryId AND ChannelId=:ChannelId";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("TemplateLibraryId", $templateLibraryId);
            $dataProperty->AddField("ChannelId", $channelId);
            $result = $this->dbOperator->GetArrayList($sql,$dataProperty);
        }
        return $result;
    }

    /**
     * 修改模板内容
     * @param $templateLibraryChannelContentId
     * @param $templateContent
     * @param $templateContentForMobile
     * @param $templateContentForPad
     * @param $templateContentForTV
     * @return array|null
     */
    public function UpdateTemplateContent($templateLibraryChannelContentId,$templateContent,$templateContentForMobile,$templateContentForPad,$templateContentForTV){
        $result=null;
        if($templateLibraryChannelContentId>0){
            $sql="UPDATE " . self::TableName_TemplateLibraryChannelContent . "
            SET
            TemplateContent=:TemplateContent,
            TemplateContentForMobile=:TemplateContentForMobile,
            TemplateContentForPad=:TemplateContentForPad,
            TemplateContentForTV=:TemplateContentForTV
            WHERE TemplateLibraryChannelContentId=:TemplateLibraryChannelContentId ";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("TemplateLibraryChannelContentId", $templateLibraryChannelContentId);
            $dataProperty->AddField("TemplateContent", $templateContent);
            $dataProperty->AddField("TemplateContentForMobile", $templateContentForMobile);
            $dataProperty->AddField("TemplateContentForPad", $templateContentForPad);
            $dataProperty->AddField("TemplateContentForTV", $templateContentForTV);
            $result = $this->dbOperator->Execute($sql,$dataProperty);
        }
        return $result;
    }

    
    /**
     * 取得列表，根据频道ID
     * @param int $channelId
     * @return array
     */
    public function GetListForView($channelId) {
        $result=null;
        if($channelId>0){
            $sql = "SELECT t.*,LENGTH(t.Attachment) as LengthOfAttach,tt.DocumentChannelTemplateTypeName FROM
            ".self::TableName_TemplateLibraryChannelContent." t
            LEFT JOIN ".self::TableName_ChannelTemplate."  tt on t.TemplateType = tt.ChannelTemplateType WHERE
            t.ChannelId=:ChannelId ORDER BY t.CreateDate DESC";

            $dataProperty = new DataProperty();
            $dataProperty->AddField("ChannelId", $channelId);
            $result = $this->dbOperator->GetArrayList($sql, $dataProperty);
        }
        return $result;
    }
    /**
     * 停用
     * @param int $channelId
     * @return int
     */
    
    public function RemoveTemplateChannelContent($channelId){
        $result=-1;
        if($channelId>0){
            $sql = "UPDATE ".self::TableName_TemplateLibraryChannelContent." set State = 100 where ChannelId = :ChannelId";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("ChannelId", $channelId);
            $result = $this->dbOperator->Execute($sql,$dataProperty);
        }
        return $result;
    }
    
    /**
     * 取得一条数据
     * @param int $templateChannelContentId
     * @return array
     */
    public function GetOne($templateChannelContentId){
        $result=null;
        if($templateChannelContentId>0){
            $sql = "SELECT * FROM " . self::TableName_TemplateLibraryChannelContent . " WHERE TemplateLibraryChannelContentId=:TemplateLibraryChannelContentId";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("TemplateLibraryChannelContentId", $templateChannelContentId);
            $result = $this->dbOperator->GetArray($sql, $dataProperty);
        }
        return $result;
    }
    
    /**
     * 更新附件
     * @param int $templateLibraryChannelContentId
     * @param string $attachment
     * @return int
     */
    public function ModifyAttachment($templateLibraryChannelContentId, $attachment) {
        $result=-1;
        if($templateLibraryChannelContentId>0){
            $sql = "UPDATE " . self::TableName_TemplateLibraryChannelContent . " SET Attachment=:Attachment where TemplateLibraryChannelContentId=:TemplateLibraryChannelContentId";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("Attachment", $attachment);
            $dataProperty->AddField("TemplateLibraryChannelContentId", $templateLibraryChannelContentId);
            $dbOperator = DBOperator::getInstance();
            $result = $dbOperator->Execute($sql, $dataProperty);
        }
        return $result;
    }
    /**
     * 修改状态
     * @param int $templateLibraryChannelContentId
     * @param int $state
     * @return int
     */
    
    public function ModifyState($templateLibraryChannelContentId,$state){
        $sql = "UPDATE ".self::TableName_TemplateLibraryChannelContent." SET State = :State WHERE TemplateLibraryChannelContentId = :TemplateLibraryChannelContentId";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("TemplateLibraryChannelContentId", $templateLibraryChannelContentId);
        $dataProperty->AddField("State", $state);
        $result = $this->dbOperator->Execute($sql,$dataProperty);
        return $result;
    }
    /**
     * 获取频道id
     * @param int $templateLibraryChannelContentId
     * @return int
     */

    public function GetChannelId($templateLibraryChannelContentId){
        $result=-1;
        if($templateLibraryChannelContentId>0){
            $sql = "SELECT ChannelId FROM ".self::TableName_TemplateLibraryChannelContent." WHERE TemplateLibraryChannelContentId = :TemplateLibraryChannelContentId";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("TemplateLibraryChannelContentId", $templateLibraryChannelContentId);
            $result = $this->dbOperator->GetInt($sql,$dataProperty);
        }
        return $result;
    }

    /**
     * 获取站点id
     * @param int $templateLibraryChannelContentId
     * @return int
     */

    public function GetSiteId($templateLibraryChannelContentId){
        $result=-1;
        if($templateLibraryChannelContentId>0){
            $sql = "SELECT SiteId FROM ".self::TableName_TemplateLibraryChannelContent." WHERE TemplateLibraryChannelContentId = :TemplateLibraryChannelContentId";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("TemplateLibraryChannelContentId", $templateLibraryChannelContentId);
            $result = $this->dbOperator->GetInt($sql,$dataProperty);
        }
        return $result;
    }

    /**
     * 物理删除
     * @param int $templateLibraryChannelContentId
     * @return int
     */

    public function Delete($templateLibraryChannelContentId){
        $result=-1;
        if($templateLibraryChannelContentId>0){
            $sql = "DELETE FROM ".self::TableName_TemplateLibraryChannelContent." WHERE TemplateLibraryChannelContentId = :TemplateLibraryChannelContentId";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("TemplateLibraryChannelContentId", $templateLibraryChannelContentId);
            $result = $this->dbOperator->Execute($sql,$dataProperty);
        }
        return $result;
    }


    /**
     * 删除附件
     * @param int $templateLibraryChannelContentId 模板id
     * @return int 操作影响的行数
     */
    public function DeleteAttachment($templateLibraryChannelContentId){
        $result = -1;
        if($templateLibraryChannelContentId>0){
            $sql = "UPDATE " . self::TableName_TemplateLibraryChannelContent . "
                    SET Attachment = null
                    WHERE TemplateLibraryChannelContentId=:TemplateLibraryChannelContentId
                    ;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("TemplateLibraryChannelContentId", $templateLibraryChannelContentId);
            $result = $this->dbOperator->Execute($sql, $dataProperty);
        }
        return $result;
    }

}

?>
