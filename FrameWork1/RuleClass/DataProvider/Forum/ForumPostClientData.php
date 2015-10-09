<?php
/**
 * 客户端 论坛帖子 数据类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_Forum
 * @author zhangchi
 */
class ForumPostClientData extends BaseClientData {


    public function Create(
        $siteId,
        $forumId,
        $forumTopicId,
        $isTopic,
        $userId,
        $userName,
        $forumPostTitle,
        $forumPostContent,
        $postTime,
        $forumTopicAudit,
        $forumTopicAccess,
        $accessLimitNumber,
        $accessLimitContent,
        $showSign,
        $postIp,
        $isOneSale,
        $addMoney,
        $addScore,
        $addCharm,
        $addExp,
        $showBoughtUser,
        $sort,
        $state,
        $uploadFiles
    ){
        $result = -1;
        if($siteId>0 && $forumId>0 && $userId>0 && strlen($userName)>0){
            $sql = "INSERT INTO " . self::TableName_ForumPost . "
                    (
                    SiteId,
                    ForumId,
                    ForumTopicId,
                    IsTopic,
                    UserId,
                    UserName,
                    ForumPostTitle,
                    ForumPostContent,
                    PostTime,
                    ForumTopicAudit,
                    ForumTopicAccess,
                    AccessLimitNumber,
                    AccessLimitContent,
                    ShowSign,
                    PostIp,
                    IsOneSale,
                    AddMoney,
                    AddScore,
                    AddCharm,
                    AddExp,
                    ShowBoughtUser,
                    Sort,
                    State,
                    UploadFiles
                    )
                    VALUES
                    (
                    :SiteId,
                    :ForumId,
                    :ForumTopicId,
                    :IsTopic,
                    :UserId,
                    :UserName,
                    :ForumPostTitle,
                    :ForumPostContent,
                    :PostTime,
                    :ForumTopicAudit,
                    :ForumTopicAccess,
                    :AccessLimitNumber,
                    :AccessLimitContent,
                    :ShowSign,
                    :PostIp,
                    :IsOneSale,
                    :AddMoney,
                    :AddScore,
                    :AddCharm,
                    :AddExp,
                    :ShowBoughtUser,
                    :Sort,
                    :State,
                    :UploadFiles
                    );";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("SiteId", $siteId);
            $dataProperty->AddField("ForumId", $forumId);
            $dataProperty->AddField("ForumTopicId", $forumTopicId);
            $dataProperty->AddField("IsTopic", $isTopic);
            $dataProperty->AddField("UserId", $userId);
            $dataProperty->AddField("UserName", $userName);
            $dataProperty->AddField("ForumPostTitle", $forumPostTitle);
            $dataProperty->AddField("ForumPostContent", $forumPostContent);
            $dataProperty->AddField("PostTime", $postTime);
            $dataProperty->AddField("ForumTopicAudit", $forumTopicAudit);
            $dataProperty->AddField("ForumTopicAccess", $forumTopicAccess);
            $dataProperty->AddField("AccessLimitNumber", $accessLimitNumber);
            $dataProperty->AddField("AccessLimitContent", $accessLimitContent);
            $dataProperty->AddField("ShowSign", $showSign);
            $dataProperty->AddField("PostIp", $postIp);
            $dataProperty->AddField("IsOneSale", $isOneSale);
            $dataProperty->AddField("AddMoney", $addMoney);
            $dataProperty->AddField("AddScore", $addScore);
            $dataProperty->AddField("AddCharm", $addCharm);
            $dataProperty->AddField("AddExp", $addExp);
            $dataProperty->AddField("ShowBoughtUser", $showBoughtUser);
            $dataProperty->AddField("Sort", $sort);
            $dataProperty->AddField("State", $state);
            $dataProperty->AddField("UploadFiles", $uploadFiles);
            $result = $this->dbOperator->LastInsertId($sql, $dataProperty);


        }

        return $result;

    }

    /**
     * 取得一条信息
     * @param int $forumPostId 帖子id
     * @return array 帖子信息数组
     */
    public function GetOne($forumPostId)
    {
        $sql = "SELECT * FROM " . self::TableName_ForumPost . " WHERE " . self::TableId_ForumPost. "=:" . self::TableId_ForumPost . ";";
        $dataProperty = new DataProperty();
        $dataProperty->AddField(self::TableId_ForumPost, $forumPostId);
        $result = $this->dbOperator->GetArray($sql, $dataProperty);
        return $result;
    }


    /**
     * 取得列表信息
     * @param int $forumTopicId 帖子id
     * @param int $pageBegin
     * @param int $pageSize
     * @return array 帖子信息数组
     */
    public function GetList($forumTopicId, $pageBegin, $pageSize)
    {
        $result = null;
        if($forumTopicId>0){
            $sql = "SELECT fp.*,
                        ui.AvatarUploadFileId,
                        uf.UploadFilePath AS AvatarUploadFilePath,
                        uf.UploadFileMobilePath AS AvatarUploadFileMobilePath,
                        uf.UploadFilePadPath AS AvatarUploadFilePadPath
                FROM " . self::TableName_ForumPost . " fp

                INNER JOIN " .self::TableName_UserInfo." ui ON (ui.UserId=fp.UserId)

                LEFT OUTER JOIN " .self::TableName_UploadFile." uf ON (ui.AvatarUploadFileId=uf.UploadFileId)


                WHERE fp." . self::TableId_ForumTopic. "=:" . self::TableId_ForumTopic . " ORDER BY fp.IsTopic DESC, fp.PostTime

                LIMIT " .$pageBegin . "," . $pageSize . ";";
            $dataProperty = new DataProperty();
            $dataProperty->AddField(self::TableId_ForumTopic, $forumTopicId);
            $result = $this->dbOperator->GetArrayList($sql, $dataProperty);
        }
        return $result;
    }


    /**
     * 取得列表信息
     * @param int $userId 会员id
     * @param int $pageBegin
     * @param int $pageSize
     * @return array 帖子信息数组
     */
    public function GetListOfUser($userId, $pageBegin, $pageSize)
    {

        $searchSql = "";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("UserId", $userId);
        $sql = "
            SELECT
                        ft.*,
                        ui.AvatarUploadFileId,
                        uf.UploadFilePath AS AvatarUploadFilePath,
                        uf.UploadFileMobilePath AS AvatarUploadFileMobilePath,
                        uf.UploadFilePadPath AS AvatarUploadFilePadPath,

                        uf2.UploadFilePath AS ContentUploadFilePath1,
                        uf3.UploadFilePath AS ContentUploadFilePath2,
                        uf4.UploadFilePath AS ContentUploadFilePath3
            FROM
            " . self::TableName_ForumTopic . " ft
            INNER JOIN " . self::TableName_UserInfo . " ui ON (ui.UserId=ft.UserId)

            LEFT OUTER JOIN " . self::TableName_UploadFile . " uf ON (ui.AvatarUploadFileId=uf.UploadFileId)
            LEFT OUTER JOIN " . self::TableName_UploadFile . " uf2 ON (ft.ContentUploadFileId1=uf2.UploadFileId)
            LEFT OUTER JOIN " . self::TableName_UploadFile . " uf3 ON (ft.ContentUploadFileId2=uf3.UploadFileId)
            LEFT OUTER JOIN " . self::TableName_UploadFile . " uf4 ON (ft.ContentUploadFileId3=uf4.UploadFileId)

            WHERE ft.ForumTopicId IN (SELECT ForumTopicId FROM " . self::TableName_ForumPost . " WHERE UserId=:UserId) " . $searchSql . " AND ft.State<".ForumTopicData::FORUM_TOPIC_STATE_REMOVED."
            ORDER BY ft.Sort DESC,ft.PostTime DESC
            LIMIT " . $pageBegin . "," . $pageSize . ";";
        $result = $this->dbOperator->GetArrayList($sql, $dataProperty);

        return $result;

        /**
        $result = null;
        if($userId>0){
            $sql = "SELECT fp.*,
                        ui.AvatarUploadFileId,
                        uf.UploadFilePath AS AvatarUploadFilePath,
                        uf.UploadFileMobilePath AS AvatarUploadFileMobilePath,
                        uf.UploadFilePadPath AS AvatarUploadFilePadPath
                FROM " . self::TableName_ForumPost . " fp

                INNER JOIN " .self::TableName_UserInfo." ui ON (ui.UserId=fp.UserId)

                LEFT OUTER JOIN " .self::TableName_UploadFile." uf ON (ui.AvatarUploadFileId=uf.UploadFileId)

                WHERE fp.UserId=:UserId

                ORDER BY fp.IsTopic DESC, fp.PostTime

                LIMIT " .$pageBegin . "," . $pageSize . ";";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("UserId", $userId);
            $result = $this->dbOperator->GetArrayList($sql, $dataProperty);
        }
        return $result;
         */
    }
} 