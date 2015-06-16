<?php
/**
 * 模板库模板内容的数据处理类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_Template
 * @author 525
 */
class TemplateLibraryContentManageData extends BaseManageData {

    /**
     * 新增频道模板
     * @param array $httpPostData $_POST数组
     * @param int $templateLibraryChannelId 频道id
     * @param int $templateLibraryId 模板库id
     * @param int $siteId 站点id
     * @return int 新增的频道模板id
     */
    public function Create($httpPostData,$templateLibraryChannelId,$templateLibraryId,$siteId)
    {
        $result = -1;
        $dataProperty = new DataProperty();
        if (!empty($httpPostData)) {
            if($templateLibraryChannelId<0){
                $templateLibraryChannelId=0;
            }

            $addFieldName = "";
            $addFieldValue = "";
            $preNumber = "";
            $addFieldNames = array(
                "TemplateLibraryChannelId","TemplateLibraryId","SiteId","CreateDate");
            $addFieldValues = array($templateLibraryChannelId,$templateLibraryId,$siteId,date("Y-m-d H:i:s", time()));

            $sql = parent::GetInsertSql(
                $httpPostData,
                self::TableName_TemplateLibraryContent,
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
     * @param array $httpPostData $_post数组
     * @param int $templateLibraryContentId
     * @return int 执行结果
     */
    public function Modify($httpPostData,$templateLibraryContentId) {
        $dataProperty = new DataProperty();
        $sql = parent::GetUpdateSql($httpPostData, self::TableName_TemplateLibraryContent, self::TableId_TemplateLibraryContent, $templateLibraryContentId, $dataProperty);
        $result = $this->dbOperator->Execute($sql, $dataProperty);
        return $result;
    }


    /**
     * 获取模板库id
     * @param int $templateLibraryContentId
     * @return int 模板库id
     */
    public function GetTemplateLibraryId($templateLibraryContentId) {
        $result=-1;
        if($templateLibraryContentId>0){
            $dataProperty = new DataProperty();
            $sql = "SELECT TemplateLibraryId FROM " . self::TableName_TemplateLibraryContent . " WHERE TemplateLibraryContentId=:TemplateLibraryContentId";
            $dataProperty->AddField("TemplateLibraryContentId", $templateLibraryContentId);
            $result = $this->dbOperator->GetInt($sql, $dataProperty);
        }
        return $result;
    }


    /**
     * 取得模板的附件
     * @param int $templateLibraryContentId 频道模板id
     * @return string 频道模板的附件
     */
    public function GetAttachment($templateLibraryContentId) {
        $result = null;
        if($templateLibraryContentId>0){
            $sql = "SELECT
                    Attachment
                FROM
                    " . self::TableName_TemplateLibraryContent . "
                WHERE TemplateLibraryContentId=:TemplateLibraryContentId";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("TemplateLibraryContentId", $templateLibraryContentId);
            $result = $this->dbOperator->GetLob($sql, $dataProperty);

        }
        return $result;
    }

    
    /**
     * 更新附件
     * @param <type> $templateLibraryContentId
     * @param <type> $attachment
     * @return int 执行结果
     */
    public function ModifyAttachment($templateLibraryContentId, $attachment) {
        $sql = "UPDATE " . self::TableName_TemplateLibraryContent . " SET Attachment=:Attachment WHERE TemplateLibraryContentId=:TemplateLibraryContentId";
        $dataProperty = new DataProperty();
        //$dataProperty->AddField("attachment", $attachment, PDO::PARAM_LOB);
        $dataProperty->AddField("Attachment", $attachment);
        $dataProperty->AddField("TemplateLibraryContentId", $templateLibraryContentId);
        $result = $this->dbOperator->Execute($sql, $dataProperty);
        return $result;
    }



    /**
     * 删除附件
     * @param int $templateLibraryContentId 模板id
     * @return int 操作影响的行数
     */
    public function DeleteAttachment($templateLibraryContentId){
        $result = -1;
        if($templateLibraryContentId>0){
            $sql = "UPDATE " . self::TableName_ChannelTemplate . "
                    SET Attachment = null
                    WHERE TemplateLibraryContentId=:TemplateLibraryContentId
                    ;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("TemplateLibraryContentId", $templateLibraryContentId);
            $result = $this->dbOperator->Execute($sql, $dataProperty);
        }
        return $result;
    }


    /**
     *通过template library id获取模板信息
     * @param int $templateLibraryId
     * @return array 数据集
     */
    public function GetTemplateContentByTemplateLibraryId($templateLibraryId){
        $sql = "SELECT TemplateLibraryId,TemplateLibraryContentId,TemplateName,TemplateContent,TemplateTypeId,TemplatePublishFileName,State,CreateDate,AttachmentName,ClosePublish FROM "
            .self::TableName_TemplateLibraryContent." WHERE TemplateLibraryId = :TemplateLibraryId";

        $dataProperty = new DataProperty();
        $dataProperty->AddField("TemplateLibraryId", $templateLibraryId);
        $result = $this->dbOperator->GetArrayList($sql,$dataProperty);
        return $result;
    }

    /**
     * @param $templateLibraryId
     * @param $templateLibraryChannelId 0:根节点  -1:根节点+所有子节点
     * @return array
     */

    public function GetList($templateLibraryId,$templateLibraryChannelId){
        $result=array();
        if($templateLibraryId>0){
            $dataProperty = new DataProperty();
            $selectChannel="";
            if($templateLibraryChannelId>=0){
                $selectChannel=" AND content.TemplateLibraryChannelId=:TemplateLibraryChannelId ";
                $dataProperty->AddField("TemplateLibraryChannelId", $templateLibraryChannelId);
            }
            $sql = "SELECT content.*,LENGTH(content.Attachment) AS AttachmentLength, channel.ChannelName FROM " . self::TableName_TemplateLibraryContent . " content
            LEFT OUTER JOIN " . self::TableName_TemplateLibraryChannel . " channel ON content.TemplateLibraryChannelId=channel.TemplateLibraryChannelId
         WHERE content.TemplateLibraryId=:TemplateLibraryId $selectChannel ORDER BY content.TemplateLibraryChannelId, content.CreateDate DESC";

            $dataProperty->AddField("TemplateLibraryId", $templateLibraryId);
            $result = $this->dbOperator->GetArrayList($sql, $dataProperty);
        }
        return $result;
    }


    /**
     * 获取模板数据集 （导入到频道）
     * @param int $templateLibraryId
     * @return array 数据集
     */

    public function GetListForImport($templateLibraryId){
        $sql = "SELECT `TemplateName`, `TemplateContent`, `TemplateContentForMobile`, `TemplateContentForPad`, `TemplateContentForTV`, `PublishFileName`, `State`, `Remark`, `TemplateLibraryId`, `Attachment`, `AttachmentName`, `PublishType`, `TemplateType`, `TemplateTag`, `IsAddVisitCode`,`TemplateLibraryChannelId`,`TemplateLibraryId`
FROM " . self::TableName_TemplateLibraryContent . " WHERE TemplateLibraryId=:TemplateLibraryId AND State<100" ;

        $dataProperty = new DataProperty();
        $dataProperty->AddField("TemplateLibraryId", $templateLibraryId);
        $result = $this->dbOperator->GetArrayList($sql, $dataProperty);
        return $result;
    }

    /**
     * 
     * @param int $templateLibraryContentId
     * @return array
     */
    public function GetOne($templateLibraryContentId){
        $sql = "SELECT * FROM ".self::TableName_TemplateLibraryContent." WHERE TemplateLibraryContentId = :TemplateLibraryContentId";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("TemplateLibraryContentId", $templateLibraryContentId);
        $result = $this->dbOperator->GetArray($sql, $dataProperty);
        return $result;
    }







    /**
     * 
     * @param int $templateLibraryContentId
     * @param int $state
     * @return int
     */
    
    public function ModifyState($templateLibraryContentId,$state){
        $sql = "UPDATE ".self::TableName_TemplateLibraryContent." SET State = :State WHERE TemplateLibraryContentId = :TemplateLibraryContentId";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("TemplateLibraryContentId", $templateLibraryContentId);
        $dataProperty->AddField("State", $state);
        $result = $this->dbOperator->Execute($sql,$dataProperty);
        return $result;
    } 
    /**
     * 
     * @param int $templateLibraryContentId
     * @return int
     */
    
    public function Delete($templateLibraryContentId){
        $sql = "DELETE FROM ".self::TableName_TemplateLibraryContent." where TemplateLibraryContentId = :TemplateLibraryContentId";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("TemplateLibraryContentId", $templateLibraryContentId);
        $result = $this->dbOperator->Execute($sql,$dataProperty);
        return $result;
    }

    /**
     * 随节点一起移动到回收站
     * @param $templateLibraryId
     * @param $templateLibraryChannelId
     * @return int
     */
    public function MoveToBinWithChannel($templateLibraryId,$templateLibraryChannelId){
        $result=-1;
        if($templateLibraryChannelId>=0&&$templateLibraryId>0){
            $sql = "UPDATE ".self::TableName_TemplateLibraryContent." SET State=100 where TemplateLibraryId = :TemplateLibraryId AND TemplateLibraryChannelId=:TemplateLibraryChannelId";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("TemplateLibraryId", $templateLibraryId);
            $dataProperty->AddField("TemplateLibraryChannelId", $templateLibraryChannelId);
            $result = $this->dbOperator->Execute($sql,$dataProperty);
        }
        return $result;
    }
    /**
     * 取得生成附件目录的名称，根据id
     * @param int $templateLibraryContentId
     * @return string
     */
    public function GetAttachmentNameByID($templateLibraryContentId) {
        $sql = "SELECT AttachmentName FROM " . self::TableName_TemplateLibraryContent . " WHERE TemplateLibraryContentId=:TemplateLibraryContentId";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("TemplateLibraryContentId", $templateLibraryContentId);
        $result = $this->dbOperator->GetString($sql, $dataProperty);
        return $result;
    }
    /**
     * 
     * @param int $templateLibraryContentId
     * @return int
     */
    
    public function GetTemplateLibraryContentByTemplateLibraryContentId($templateLibraryContentId){
        $sql = "SELECT TemplateContent FROM ".self::TableName_TemplateLibraryContent." where TemplateLibraryContentId = :TemplateLibraryContentId";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("TemplateLibraryContentId", $templateLibraryContentId);
        $result = $this->dbOperator->GetString($sql,$dataProperty);
        return $result;
    }

    /**
     * 获取所属模板库频道
     * @param $templateLibraryContentId
     * @return int
     */
    public function GetTemplateLibraryChannelId($templateLibraryContentId){
        $result=0;
        if($templateLibraryContentId>0){
            $sql = "SELECT TemplateLibraryChannelId FROM " . self::TableName_TemplateLibraryContent . "
         WHERE TemplateLibraryContentId=:TemplateLibraryContentId; ";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("TemplateLibraryContentId", $templateLibraryContentId);
            $result = $this->dbOperator->GetInt($sql, $dataProperty);
        }
        return $result;
    }


    /**
     * 取得字段数据集
     * @param string $tableName 表名
     * @return array 字段数据集
     */
    public function GetFields($tableName = self::TableName_TemplateLibraryContent){
        return parent::GetFields(self::TableName_TemplateLibraryContent);
    }

}

?>
