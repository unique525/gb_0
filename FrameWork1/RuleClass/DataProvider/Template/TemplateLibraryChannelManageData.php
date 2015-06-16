<?php
/**
 * 模板库要自动创建的频道的数据处理类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_Template
 * @author 525
 */
class TemplateLibraryChannelManageData extends BaseManageData {

    /**
     * 新增频道
     * @param array $httpPostData $_post数组
     * @return int 新增id
     */
    public function Create($httpPostData) {
        $result=-1;
        $dataProperty = new DataProperty();
        $addFieldName = "";
        $addFieldValue = "";
        $preNumber = "";
        $addFieldNames = array();
        $addFieldValues = array();
        if(!empty($httpPostData)){
            $sql=parent::GetInsertSql($httpPostData, self::TableName_TemplateLibraryChannel, $dataProperty, $addFieldName, $addFieldValue, $preNumber, $addFieldNames, $addFieldValues);
            $result=$this->dbOperator->LastInsertId($sql,$dataProperty);
        }
        return $result;

    }
    
    /**
     * 修改
     * @param array $httpPostData $_post数组
     * @param int $templateLibraryChannelId
     * @return int 执行结果
     */
    public function Modify($httpPostData,$templateLibraryChannelId) {
        $dataProperty = new DataProperty();
        $sql = parent::GetUpdateSql($httpPostData, self::TableName_TemplateLibraryChannel, self::TableId_TemplateLibraryChannel, $templateLibraryChannelId, $dataProperty);
        $result = $this->dbOperator->Execute($sql, $dataProperty);
        return $result;
    }


    /**
     * 取得管理列表
     * @param int $templateLibraryId
     * @return array 数据集
     */
    public function GetChannelList($templateLibraryId){
        $result=null;
        if($templateLibraryId>0){
            $sql="SELECT channel.*,
            (SELECT count(*) FROM ".self::TableName_TemplateLibraryContent." content WHERE content.TemplateLibraryChannelId=channel.TemplateLibraryChannelId ) AS TemplateCount
            FROM ".self::TableName_TemplateLibraryChannel." channel
            WHERE channel.TemplateLibraryId = :TemplateLibraryId AND channel.State!=100 ;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("TemplateLibraryId", $templateLibraryId);
            $result = $this->dbOperator->GetArrayList($sql,$dataProperty);
        }
        return $result;
    }


    /**
     * 取得列表用来导入
     * @param int $templateLibraryId
     * @param int $rank
     * @return array 数据集
     */
    public function GetChannelListOfRank($templateLibraryId,$rank=0){
        $result=null;
        if($templateLibraryId>0){
            $sql = "SELECT * FROM ".self::TableName_TemplateLibraryChannel." WHERE TemplateLibraryId = :TemplateLibraryId AND Rank=:Rank AND State!=100";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("TemplateLibraryId", $templateLibraryId);
            $dataProperty->AddField("Rank", $rank);
            $result = $this->dbOperator->GetArrayList($sql,$dataProperty);
        }
        return $result;
    }

    /**
     * 取得列表 父频道id
     * @param int $templateLibraryId
     * @return array 数据集
     */
    public function GetParentTemplateLibraryChannelId($templateLibraryId){
        $result=-1;
        if($templateLibraryId>0){
            $sql = "SELECT ParentId FROM ".self::TableName_TemplateLibraryChannel." WHERE TemplateLibraryId = :TemplateLibraryId";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("TemplateLibraryId", $templateLibraryId);
            $result = $this->dbOperator->GetInt($sql,$dataProperty);
        }
        return $result;
    }


    /**
     * 添加节点
     * @param int $templateLibraryChannelId
     * @param int $siteId
     * @param int $documentChannelId
     * @param int $rank
     * @param string $createDate
     * @return int
     */
    
    public function InsertChannel($templateLibraryChannelId,$siteId,$documentChannelId,$rank,$createDate){
        $sql = "INSERT INTO ".self::TableName_Channel."
        (
            ChannelName,
            Icon,
            Sort,
            ChannelType,
            PublishType,
            PublishPath,
            ManageUserId,
            State,
            HasFTP,
            ShowChildList,
            OpenComment,
            ChannelBrowserTitle,
            ChannelBrowserKeywords,
            ChannelBrowserDescription,
            TitlePic1UploadFileId,
            TitlePic2UploadFileId,
            TitlePic3UploadFileId,
            IsCircle,
            IsShowIndex,
            SiteId,
            ParentId,
            Rank,
            CreateDate
        ) SELECT
            ChannelName,
            Icon,
            Sort,
            ChannelType,
            PublishType,
            PublishPath,
            ManageUserId,
            State,
            HasFTP,
            ShowChildList,
            OpenComment,
            ChannelBrowserTitle,
            ChannelBrowserKeywords,
            ChannelBrowserDescription,
            TitlePic1UploadFileId,
            TitlePic2UploadFileId,
            TitlePic3UploadFileId,
            IsCircle,
            IsShowIndex,
            :SiteId,
            :ParentId,
            :Rank,
            '$createDate'
        FROM ".self::TableName_TemplateLibraryChannel." WHERE TemplateLibraryChannelId = :TemplateLibraryChannelId";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("TemplateLibraryChannelId", $templateLibraryChannelId);
        $dataProperty->AddField("SiteId", $siteId);
        $dataProperty->AddField("ParentId", $documentChannelId);
        $dataProperty->AddField("Rank", $rank);
        $result = $this->dbOperator->LastInsertId($sql,$dataProperty);
        return $result;
    }
    
    /**
     * 获取模板库中的一个频道信息
     * @param int $templateLibraryChannelId 模板库的频道ID
     * @return Array 频道信息
     */
    public function GetOne($templateLibraryChannelId){
        $sql = "SELECT * FROM ".self::TableName_TemplateLibraryChannel." WHERE TemplateLibraryChannelId = :TemplateLibraryChannelId";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("TemplateLibraryChannelId", $templateLibraryChannelId);
        $result = $this->dbOperator->GetArray($sql,$dataProperty);
        return $result;
    }
    
    /**
     * 删除模板库的一个频道
     * @param int $templateLibraryChannelId
     * @return int 影响行数
     */
    public function Delete($templateLibraryChannelId){
        $sql = "UPDATE ".self::TableName_TemplateLibraryChannel." SET State=100 WHERE TemplateLibraryChannelId = :TemplateLibraryChannelId";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("TemplateLibraryChannelId", $templateLibraryChannelId);
        $result = $this->dbOperator->Execute($sql,$dataProperty);
        return $result;
    }

    /**
     * 取得模板库id
     * @param int $templateLibraryChannelId
     * @return int id
     */
    public function GetTemplateLibraryId($templateLibraryChannelId){
        $sql = "SELECT TemplateLibraryId FROM ".self::TableName_TemplateLibraryChannel." where TemplateLibraryChannelId = :TemplateLibraryChannelId";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("TemplateLibraryChannelId", $templateLibraryChannelId);
        $result = $this->dbOperator->GetInt($sql,$dataProperty);
        return $result;
    }

    /**
     * 获取模板库中的一个频道rank
     * @param int $templateLibraryChannelId 模板库的频道ID
     * @return int rank
     */
    public function GetRank($templateLibraryChannelId){
        $result=-1;
        if($templateLibraryChannelId>0){
            $sql = "SELECT Rank FROM ".self::TableName_TemplateLibraryChannel." WHERE TemplateLibraryChannelId = :TemplateLibraryChannelId";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("TemplateLibraryChannelId", $templateLibraryChannelId);
            $result = $this->dbOperator->GetInt($sql,$dataProperty);
        }
        return $result;
    }

    /**
     * 获取子节点id
     * @param $templateLibraryChannelId
     * @return array
     */
    public function GetChildrenId($templateLibraryChannelId){
        $result=null;
        if($templateLibraryChannelId>0){
            $sql = "SELECT TemplateLibraryChannelId FROM ".self::TableName_TemplateLibraryChannel." WHERE ParentId = :ParentId";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("ParentId", $templateLibraryChannelId);
            $result = $this->dbOperator->GetArray($sql,$dataProperty);
        }
        return $result;
    }

    /**
     * 取得字段数据集
     * @param string $tableName 表名
     * @return array 字段数据集
     */
    public function GetFields($tableName = self::TableName_TemplateLibraryChannel){
        return parent::GetFields(self::TableName_TemplateLibraryChannel);
    }

}

?>
