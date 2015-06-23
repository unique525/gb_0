<?php
/**
 * 客户端 论坛帖子 数据类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_Forum
 * @author zhangchi
 */
class ForumPostClientData extends BaseClientData {

    /**
     * 取得列表信息
     * @param int $forumTopicId 帖子id
     * @param int $pageBegin
     * @param int $pageSize
     * @return array 帖子信息数组
     */
    public function GetList($forumTopicId, $pageBegin, $pageSize)
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


        return $result;
    }
} 