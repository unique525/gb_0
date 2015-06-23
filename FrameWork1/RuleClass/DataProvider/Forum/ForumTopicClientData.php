<?php

/**
 * 客户端 论坛主题 数据类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_Forum
 * @author zhangchi
 */
class ForumTopicClientData extends BaseClientData {

    /**
     * 分页列表数据集
     * @param int $forumId
     * @param int $pageBegin
     * @param int $pageSize
     * @return array
     */
    public function GetList($forumId, $pageBegin, $pageSize)
    {
        $searchSql = "";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("ForumId", $forumId);
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

            WHERE ft.ForumId=:ForumId  " . $searchSql . "
            ORDER BY ft.Sort DESC,ft.PostTime DESC
            LIMIT " . $pageBegin . "," . $pageSize . ";";
        $result = $this->dbOperator->GetArrayList($sql, $dataProperty);

        return $result;
    }
} 