<?php
/**
 * 后台管理 频道 数据类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_Comment
 * @author zhangchi
 */
class CommentManageData extends BaseManageData{

    public function GetList($tableId,$tableType,$siteId,$pageBegin,$pageSize,&$allCount){
        $result = null;
        if($tableType > 0 && $tableId > 0 && $siteId > 0){
            $sql = "SELECT
                        c.*,u.*,ui.*
                    FROM ".self::TableName_Comment." c

                        LEFT OUTER JOIN " .self::TableName_User." u on c.UserId=u.UserId
                        LEFT OUTER JOIN " .self::TableName_UserInfo." ui on c.UserId=ui.UserId


                WHERE c.TableId = :TableId AND c.TableType = :TableType AND c.SiteId = :SiteId
                ORDER BY c.CreateDate DESC LIMIT ".$pageBegin.",".$pageSize.";";
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



    public function GetListForSite($tableType,$siteId,$pageBegin,$pageSize,&$allCount,$rank){
        $result = null;
        if($tableType > 0 && $siteId > 0){
            $sql = "SELECT c.*,u.UserName,u.UserMobile,u.UserEmail,ui.RealName,ui.NickName
                    FROM ".self::TableName_Comment." c
                        LEFT OUTER JOIN " .self::TableName_User." u on c.UserId=u.UserId
                        LEFT OUTER JOIN " .self::TableName_UserInfo." ui on c.UserId=ui.UserId


                WHERE c.TableType = :TableType AND c.SiteId = :SiteId AND Rank=:Rank
                ORDER BY c.CreateDate DESC LIMIT ".$pageBegin.",".$pageSize.";";

            $sqlCount = "SELECT count(*) FROM ".self::TableName_Comment."
                WHERE TableType = :TableType AND SiteId = :SiteId AND Rank=:Rank;";

            $dataProperty = new DataProperty();
            $dataProperty->AddField("TableType",$tableType);
            $dataProperty->AddField("SiteId",$siteId);
            $dataProperty->AddField("Rank",$rank);
            $result = $this->dbOperator->GetArrayList($sql,$dataProperty);
            $allCount = $this->dbOperator->GetInt($sqlCount,$dataProperty);
        }
        return $result;

    }

    /**
     * @param $parentIds (1,32,4)
     * @return array|null
     */
    public function GetListOfChild($parentIds){
        $result = null;
        if(strlen($parentIds)>0){
            $sql = "SELECT c.*,u.UserName,u.UserMobile,u.UserEmail,ui.RealName,ui.NickName
                    FROM ".self::TableName_Comment." c
                        LEFT OUTER JOIN " .self::TableName_User." u on c.UserId=u.UserId
                        LEFT OUTER JOIN " .self::TableName_UserInfo." ui on c.UserId=ui.UserId


                WHERE c.ParentId IN ($parentIds)
                ORDER BY c.CreateDate DESC;";

            $result = $this->dbOperator->GetArrayList($sql,null);
        }
        return $result;

    }

    public function GetListForChannel($tableType,$channelId,$pageBegin,$pageSize,&$allCount){
        $result = null;
        if($tableType > 0 && $channelId > 0){
            $sql = "SELECT c.*,u.*,ui.*
                        FROM ".self::TableName_Comment." c

                        LEFT OUTER JOIN " .self::TableName_User." u on c.UserId=u.UserId
                        LEFT OUTER JOIN " .self::TableName_UserInfo." ui on c.UserId=ui.UserId


                WHERE c.TableType = :TableType AND c.ChannelId = :ChannelId
                ORDER BY c.CreateDate DESC LIMIT ".$pageBegin.",".$pageSize.";";
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

    public function GetListByCommentRank($siteId, $commentRank) {
        $result = null;
        if($siteId>0 && $commentRank>=0){
            $sql = "SELECT
                    *
            FROM " . self::TableName_Comment . "
            WHERE
            Rank=:CommentRank
            AND SiteId=:SiteId
            ORDER BY createDate DESC;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("CommentRank", $commentRank);
            $dataProperty->AddField("SiteId", $siteId);
            $result = $this->dbOperator->GetArrayList($sql, $dataProperty);
        }


        return $result;
    }
    public function GetOne($parentId)
    {
        if($parentId > 0){
            $sql = "SELECT * FROM " . self::TableName_Comment . " WHERE " . self::TableId_Comment. "=:ParentId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("ParentId", $parentId);
            $result = $this->dbOperator->GetArray($sql, $dataProperty);
            return $result;
        }
    }
}