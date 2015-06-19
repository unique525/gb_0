<?php

/**
 * 客户端 论坛 数据类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_Forum
 * @author zhangchi
 */
class ForumClientData extends BaseClientData {

    /**
     * 根据版块等级取得版块列表
     * @param int $siteId 站点id
     * @param int $forumRank 版块等级
     * @return array|null 版块列表
     */
    public function GetListByForumRank($siteId, $forumRank) {
        $result = null;
        if($siteId>0 && $forumRank>=0){


            $sql = "
            SELECT f.*,
                        uf1.UploadFilePath AS ForumPic1UploadFilePath,

                        uf2.UploadFilePath AS ForumPic2UploadFilePath

            FROM
            " . self::TableName_Forum . " f
            LEFT OUTER JOIN " .self::TableName_UploadFile." uf1 ON (f.ForumPic1UploadFileId=uf1.UploadFileId)
            LEFT OUTER JOIN " .self::TableName_UploadFile." uf2 ON (f.ForumPic2UploadFileId=uf2.UploadFileId)

            WHERE f.State<".ForumData::STATE_REMOVED." AND f.ForumRank=:ForumRank AND f.SiteId=:SiteId ORDER BY f.Sort DESC;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("ForumRank", $forumRank);
            $dataProperty->AddField("SiteId", $siteId);

            $result = $this->dbOperator->GetArrayList($sql, $dataProperty);
        }


        return $result;
    }

} 