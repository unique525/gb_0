<?php

/**
 * 客户端 频道 数据类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_Comment
 * @author zhangchi
 */
class CommentClientData extends BaseClientData {

    public function Create(
        $siteId,
        $subject,
        $content,
        $channelId,
        $tableId,
        $tableType,
        $userId,
        $userName,
        $guestName,
        $guestEmail,
        $state,
        $commentType,
        $sourceUrl
    ){
        $result = -1;

        if($siteId > 0  && strlen($content)>0  && $tableId > 0 && $tableType > 0){
            $sql = "
                INSERT INTO " . self::TableName_Comment . " (
                    TableId,
                    TableType,
                    SiteId,
                    ChannelId,
                    Subject,
                    Content,
                    UserId,
                    UserName,
                    GuestName,
                    CreateDate,
                    GuestEmail,
                    State,
                    CommentType,
                    SourceUrl
                ) VALUES (
                    :TableId,
                    :TableType,
                    :SiteId,
                    :ChannelId,
                    :Subject,
                    :Content,
                    :UserId,
                    :UserName,
                    :GuestName,
                    now(),
                    :GuestEmail,
                    :State,
                    :CommentType,
                    :SourceUrl
                );";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("TableId", $tableId);
            $dataProperty->AddField("TableType", $tableType);
            $dataProperty->AddField("SiteId", $siteId);
            $dataProperty->AddField("ChannelId", $channelId);
            $dataProperty->AddField("Subject", $subject);
            $dataProperty->AddField("Content", $content);
            $dataProperty->AddField("UserId", $userId);
            $dataProperty->AddField("UserName", $userName);
            $dataProperty->AddField("GuestName", $guestName);
            $dataProperty->AddField("GuestEmail", $guestEmail);
            $dataProperty->AddField("State", $state);
            $dataProperty->AddField("CommentType", $commentType);
            $dataProperty->AddField("SourceUrl", $sourceUrl);
            $result = $this->dbOperator->LastInsertId($sql, $dataProperty);

        }
        return $result;
    }

    public function GetList($tableId,$tableType,$commentType,$pageBegin,$pageSize){
        $result = null;
        if($tableId > 0 && $tableType > 0){
            $sql = "SELECT
                            c.*,
                            ui.NickName,
                            uf.UploadFileThumbPath2 AS Avatar
                          FROM "
                . self::TableName_Comment . " c
                            LEFT JOIN ".self::TableName_UserInfo." ui on c.UserId = ui.UserId
                            LEFT JOIN ".self::TableName_UploadFile." uf ON ui.AvatarUploadFileId = uf.UploadFileId
                          WHERE (c.state=".CommentData::COMMENT_STATE_CHECKED." OR c.state=".CommentData::COMMENT_STATE_UN_CHECK.") AND c.TableType=:TableType AND c.TableId=:TableId AND c.CommentType=:CommentType ORDER BY c.CreateDate DESC LIMIT " . $pageBegin . "," . $pageSize . ";";
            $dataProperty = new DataProperty();

            $dataProperty->AddField("TableId", $tableId);
            $dataProperty->AddField("TableType", $tableType);
            $dataProperty->AddField("CommentType", $commentType);
            $result = $this->dbOperator->GetArrayList($sql, $dataProperty);
        }
        return $result;
    }

    public function GetListOfUser($userId,$pageBegin,$pageSize){
        $result = null;
        if($userId > 0){
            $sql = "SELECT
                            c.*,
                            ui.NickName,
                            uf.UploadFileThumbPath2 AS Avatar
                          FROM "
                . self::TableName_Comment . " c
                            LEFT JOIN ".self::TableName_UserInfo." ui on c.UserId = ui.UserId
                            LEFT JOIN ".self::TableName_UploadFile." uf ON ui.AvatarUploadFileId = uf.UploadFileId
                          WHERE (c.State=".CommentData::COMMENT_STATE_CHECKED."
                            OR c.State=".CommentData::COMMENT_STATE_UN_CHECK.") AND c.UserId=:UserId
                                ORDER BY c.CreateDate DESC LIMIT " . $pageBegin . "," . $pageSize . ";";
            $dataProperty = new DataProperty();

            $dataProperty->AddField("UserId", $userId);
            $result = $this->dbOperator->GetArrayList($sql, $dataProperty);
        }
        return $result;
    }


    public function Reply(
        $parentId,
        $rank,
        $tableId,
        $tableType,
        $subject,
        $content,
        $userId,
        $userName,
        $guestName,
        $agreeCount,
        $disagreeCount,
        $guestEmail,
        $state,
        $commentType,
        $siteId,
        $channelId,
        $sourceUrl
    )
    {
        $result = -1;

        if($siteId > 0  && !empty($content)  && $channelId > 0 && $tableId > 0 && $tableType > 0){
            $sql = "
                INSERT INTO " . self::TableName_Comment . " (
                    ParentId,
                    Rank,
                    TableId,
                    TableType,
                    Subject,
                    Content,
                    UserId,
                    UserName,
                    GuestName,
                    CreateDate,
                    AgreeCount,
                    DisagreeCount,
                    GuestEmail,
                    State,
                    CommentType,
                    SiteId,
                    ChannelId,
                    SourceUrl
                ) VALUES (
                    :ParentId,
                    :Rank,
                    :TableId,
                    :TableType,
                    :Subject,
                    :Content,
                    :UserId,
                    :UserName,
                    :GuestName,
                    now(),
                    :AgreeCount,
                    :DisagreeCount,
                    :GuestEmail,
                    :State,
                    :CommentType,
                    :SiteId,
                    :ChannelId,
                    :SourceUrl
                );";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("AgreeCount", $agreeCount);
            $dataProperty->AddField("DisagreeCount", $disagreeCount);
            $dataProperty->AddField("ParentId", $parentId);
            $dataProperty->AddField("Rank", $rank);
            $dataProperty->AddField("TableId", $tableId);
            $dataProperty->AddField("TableType", $tableType);
            $dataProperty->AddField("SiteId", $siteId);
            $dataProperty->AddField("ChannelId", $channelId);
            $dataProperty->AddField("Subject", $subject);
            $dataProperty->AddField("Content", $content);
            $dataProperty->AddField("UserId", $userId);
            $dataProperty->AddField("UserName", $userName);
            $dataProperty->AddField("GuestName", $guestName);
            $dataProperty->AddField("GuestEmail", $guestEmail);
            $dataProperty->AddField("State", $state);
            $dataProperty->AddField("CommentType", $commentType);
            $dataProperty->AddField("SourceUrl", $sourceUrl);
            $result = $this->dbOperator->LastInsertId($sql, $dataProperty);
        }
        return $result;
    }

    /**
     * 获取一个信息
     * @param int $commentId 订单Id
     * @return array|null 数组
     */
    public function GetOne($commentId){
        $result = null;
        if($commentId > 0){
            $sql = "SELECT * FROM "
                .self::TableName_Comment."
                 WHERE CommentId = :CommentId; ";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("CommentId",$commentId);
            $result = $this->dbOperator->GetArray($sql,$dataProperty);
        }
        return $result;
    }

}