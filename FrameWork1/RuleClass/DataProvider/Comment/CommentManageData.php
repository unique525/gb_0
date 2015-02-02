<?php
/**
 * Created by PhpStorm.
 * User: yin
 * Date: 14-12-5
 * Time: 上午11:15
 */

class CommentManageData extends BaseManageData{

    public function GetList($tableId,$tableType,$siteId,$pageBegin,$pageSize,&$allCount){
        $result = null;
        if($tableType > 0 && $tableId > 0 && $siteId > 0){
            $sql = "SELECT * FROM ".self::TableName_Comment."
                WHERE TableId = :TableId AND TableType = :TableType AND SiteId = :SiteId
                ORDER BY CreateDate DESC LIMIT ".$pageBegin.",".$pageSize.";";
            $sqlCount = "SELECT count(*) FROM ".self::TableName_Comment."
                WHERE TableId = :TableId AND TableType = :TableType AND SiteId = :SiteId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("TableId",$tableId);
            $dataProperty->AddField("TableType",$tableType);
            $dataProperty->AddField("SiteId",$siteId);

            $result = $this->dbOperator->GetArrayList($sql,$dataProperty);
            $allCount = $this->dbOperator->GetInt($sqlCount,$dataProperty);
        }
        return $result;

    }

    public function GetListForSite($tableType,$siteId,$pageBegin,$pageSize,&$allCount){
        $result = null;
        if($tableType > 0 && $siteId > 0){
            $sql = "SELECT * FROM ".self::TableName_Comment."
                WHERE TableType = :TableType AND SiteId = :SiteId
                ORDER BY CreateDate DESC LIMIT ".$pageBegin.",".$pageSize.";";
            $sqlCount = "SELECT count(*) FROM ".self::TableName_Comment."
                WHERE TableType = :TableType AND SiteId = :SiteId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("TableType",$tableType);
            $dataProperty->AddField("SiteId",$siteId);

            $result = $this->dbOperator->GetArrayList($sql,$dataProperty);
            $allCount = $this->dbOperator->GetInt($sqlCount,$dataProperty);
        }
        return $result;

    }

    public function GetListForChannel($tableType,$channelId,$pageBegin,$pageSize,&$allCount){
        $result = null;
        if($tableType > 0 && $channelId > 0){
            $sql = "SELECT * FROM ".self::TableName_Comment."
                WHERE TableType = :TableType AND ChannelId = :ChannelId
                ORDER BY CreateDate DESC LIMIT ".$pageBegin.",".$pageSize.";";
            $sqlCount = "SELECT count(*) FROM ".self::TableName_Comment."
                WHERE TableType = :TableType AND ChannelId = :ChannelId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("TableType",$tableType);
            $dataProperty->AddField("ChannelId",$channelId);

            $result = $this->dbOperator->GetArrayList($sql,$dataProperty);
            $allCount = $this->dbOperator->GetInt($sqlCount,$dataProperty);
        }
        return $result;

    }

    public function ModifyState($commentId,$state){
        $result = -1;
        if($commentId > 0 && $state > 0){
            $sql = "UPDATE ".self::TableName_Comment." SET State = :State WHERE CommentId = :CommentId";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("State",$state);
            $dataProperty->AddField("CommentId",$commentId);
            $result = $this->dbOperator->Execute($sql,$dataProperty);
        }
        return $result;
    }
}