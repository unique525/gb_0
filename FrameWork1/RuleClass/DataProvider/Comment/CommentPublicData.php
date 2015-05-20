<?php

/**
 * 前台 频道 数据类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_Channel
 * @author zhangchi
 */
class CommentPublicData extends BasePublicData {

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
        if($siteId > 0  && !empty($content)  && $channelId > 0 && $tableId > 0 && $tableType > 0){
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

    public function GetList($tableId,$tableType,$siteId,$commentType,&$allCount,$pageBegin,$pageSize){
        $result = null;
        if($tableId > 0 && $tableType > 0 && $siteId > 0){
            $sql = "SELECT
                            c.CommentId,
                            c.UserId,
                            c.UserName,
                            c.AgreeCount,
                            c.DisagreeCount,
                            c.CreateDate,
                            c.Content,
                            c.GuestName,
                            c.GuestEmail,
                            c.SiteId,
                            c.ChannelId,
                            ui.NickName,
                            uf.UploadFileThumbPath2 AS Avatar
                          FROM "
                            . self::TableName_Comment . " c
                            LEFT JOIN ".self::TableName_UserInfo." ui on c.UserId = ui.UserId
                            LEFT JOIN ".self::TableName_UploadFile." uf ON ui.AvatarUploadFileId = uf.UploadFileId
                          WHERE (c.state=30 OR c.state=0) AND c.TableType=:TableType AND c.TableId=:TableId AND c.CommentType=:CommentType ORDER BY c.CreateDate DESC LIMIT " . $pageBegin . "," . $pageSize . ";";
            $sqlCount = "SELECT count(*) FROM " . self::TableName_Comment . " c LEFT JOIN ".self::TableName_UserInfo." ui ON c.UserId = ui.UserId "
                . " WHERE (c.State=30 OR c.state=0) AND c.TableType=:TableType AND c.TableId=:TableId AND c.CommentType=:CommentType ORDER BY c.CreateDate DESC;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("TableId", $tableId);
            $dataProperty->AddField("TableType", $tableType);
            $dataProperty->AddField("CommentType", $commentType);
            $result = $this->dbOperator->GetArrayList($sql, $dataProperty);
            $allCount = $this->dbOperator->GetInt($sqlCount, $dataProperty);
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

}

?>
