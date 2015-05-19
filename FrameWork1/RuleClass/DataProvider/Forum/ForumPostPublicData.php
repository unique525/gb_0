<?php
/**
 * 前台 论坛帖子 数据类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_Forum
 * @author xiao
 */
class ForumPostPublicData extends BasePublicData {

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
        $isOneSal,
        $addMoney,
        $addScore,
        $addCharm,
        $addExp,
        $showBoughtUser,
        $sort,
        $state,
        $uploadFiles
    ){
        echo($siteId);
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
            echo($sql);
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
            $dataProperty->AddField("IsOneSale", $isOneSal);
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

    public function Modify(
        $siteId,
        $forumTopicId,
        $isTopic,
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
        if(
            strlen($forumPostTitle)>0
        ){

            $dataProperty = new DataProperty();
            $dataProperty->AddField("SiteId", $siteId);
            $dataProperty->AddField("IsTopic", $isTopic);
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
            $fieldNames= "SiteId=:SiteId,IsTopic=:IsTopic,ForumPostTitle=:ForumPostTitle,ForumPostContent=:ForumPostContent,PostTime=:PostTime,ForumTopicAudit=:ForumTopicAudit,ForumTopicAccess=:ForumTopicAccess,AccessLimitNumber=:AccessLimitNumber,AccessLimitContent=:AccessLimitContent,ShowSign=:ShowSign,PostIp=:PostIp,IsOneSale=:IsOneSale,AddMoney=:AddMoney,AddScore=:AddScore,AddCharm=:AddCharm,AddExp=:AddExp,ShowBoughtUser=:ShowBoughtUser,Sort=:Sort,State=:State,UploadFiles=:UploadFiles";
            $sql = "UPDATE " . self::TableName_ForumPost . " SET " . $fieldNames ." WHERE forumTopicId =". $forumTopicId ."";
            $result = $this->dbOperator->Execute($sql, $dataProperty);
        }

        return $result;
    }

    /**
     * 取得一条信息
     * @param int $forumTopicId 帖子id
     * @return array 帖子信息数组
     */

    public function GetOne($forumTopicId)
    {
        $sql = "SELECT * FROM " . self::TableName_ForumPost . " WHERE " . self::TableId_ForumTopic. "=:" . self::TableId_ForumTopic . ";";
        $dataProperty = new DataProperty();
        $dataProperty->AddField(self::TableId_ForumTopic, $forumTopicId);
        $result = $this->dbOperator->GetArray($sql, $dataProperty);
        return $result;
    }
    /**
     * 取得列表信息
     * @param int $forumTopicId 帖子id
     * @param int $pageBegin
     * @param int $pageSize
     * @param int $allCount
     * @return array 帖子信息数组
     */

    public function GetListPager($forumTopicId, $pageBegin, $pageSize, &$allCount)
    {
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

        //统计总数
        $sql = "SELECT count(*)
                FROM " . self::TableName_ForumPost  . "
                WHERE ForumTopicId=:ForumTopicId;";

        $allCount = $this->dbOperator->GetInt($sql, $dataProperty);


        return $result;
    }
} 