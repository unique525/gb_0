<?php

/**
 * 前台管理 产品评论 数据类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_User
 * @author yin
 */
class ProductCommentPublicData extends BasePublicData
{
    /**
     * @param $productId
     * @param $content
     * @param $userId
     * @param $userName
     * @param $siteId
     * @param $channelId
     * @param int $parentId
     * @param int $rank
     * @param string $subject
     * @param int $appraisal
     * @param int $productScore
     * @param int $sendScore
     * @param int $serviceScore
     * @param int $state
     * @param int $sort
     * @return int
     */
    public function Create(
        $productId,
        $content,
        $userId,
        $userName,
        $siteId,
        $channelId,
        $parentId = 0,
        $rank = 0,
        $subject = "",
        $appraisal = 0,
        $productScore = 0,
        $sendScore = 0,
        $serviceScore = 0,
        $state = 0,
        $sort = 0
    )
    {
        $result = -1;
        if ($productId > 0 && !empty($content) && $userId > 0 && !empty($userName) && $siteId > 0 && $channelId > 0) {
            $sql = "INSERT INTO " . self::TableName_ProductComment
                . " (ParentId,Rank,ProductId,Subject,Content,UserId,UserName,CreateDate,
                Appraisal,ProductScore,SendScore,ServiceScore,SiteId,ChannelId,State,Sort)
                 VALUES (
                 :ParentId,:Rank,:ProductId,:Subject,:Content,:UserId,:UserName,now(),
                 :Appraisal,:ProductScore,:SendScore,:ServiceScore,:SiteId,:ChannelId,:State,:Sort
                 );";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("ParentId", $parentId);
            $dataProperty->AddField("Rank", $rank);
            $dataProperty->AddField("ProductId", $productId);
            $dataProperty->AddField("Subject", $subject);
            $dataProperty->AddField("Content", $content);
            $dataProperty->AddField("UserId", $userId);
            $dataProperty->AddField("UserName", $userName);
            $dataProperty->AddField("Appraisal", $appraisal);
            $dataProperty->AddField("ProductScore", $productScore);
            $dataProperty->AddField("SendScore", $sendScore);
            $dataProperty->AddField("ServiceScore", $serviceScore);
            $dataProperty->AddField("SiteId", $siteId);
            $dataProperty->AddField("ChannelId", $channelId);
            $dataProperty->AddField("State", $state);
            $dataProperty->AddField("Sort", $sort);
            $result = $this->dbOperator->LastInsertId($sql, $dataProperty);
        }
        return $result;
    }


    public function GetListByParentId($parentId){
        $result = null;

        $sql = "
            SELECT
                ParentId,
                Subject,
                Content,
                UserId,
                UserName,
                ProductScore,
                SendScore,
                ServiceScore
            FROM " . self::TableName_ProductComment . "

            WHERE
                ParentId = :ParentId

            ORDER BY CreateDate DESC;

                ";





        $dataProperty = new DataProperty();
        $dataProperty->AddField("ParentId", $parentId);
        $result = $this->dbOperator->LastInsertId($sql, $dataProperty);


        return $result;
    }

    public function GetListOfStrParentIds($strParentIds){
        $result = null;
        if(!empty($strParentIds)){
        $sql = "
            SELECT
                ParentId,
                Subject,
                Content,
                UserId,
                UserName,
                ProductScore,
                SendScore,
                ServiceScore
            FROM " . self::TableName_ProductComment . "

            WHERE
                ParentId IN (".$strParentIds.")

            ORDER BY CreateDate DESC;

                ";
        $dataProperty = new DataProperty();
        $result = $this->dbOperator->GetArrayList($sql, $dataProperty);
        }
        return $result;
    }

    public function GetListOfChild(){

    }

    public function GetListOfParent($productId,&$allCount,$pageBegin,$pageSize){
        $result = null;
        if($productId > 0){
            $sql = "SELECT pc.*,ug.UserGroupName,uf.UploadFileCompressPath1 FROM "
                .self::TableName_ProductComment." pc,"
                .self::TableName_UserRole." ur,"
                .self::TableName_UserGroup." ug,"
                .self::TableName_UserInfo." ui LEFT JOIN "
                .self::TableName_UploadFile." uf ON ui.AvatarUploadFileId = uf.UploadFileId
                WHERE pc.ParentId = 0 AND pc.ProductId = :ProductId
                AND ui.UserId = pc.UserId
                AND pc.UserId = ur.UserId AND ur.UserGroupId = ug.UserGroupId
                LIMIT ".$pageBegin.",".$pageSize.";";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("ProductId",$productId);
            $result = $this->dbOperator->GetArrayList($sql,$dataProperty);

            $sqlCount = "SELECT count(*) FROM "
                .self::TableName_ProductComment." pc,"
                .self::TableName_UserRole." ur,"
                .self::TableName_UserGroup." ug,"
                .self::TableName_UserInfo." ui LEFT JOIN "
                .self::TableName_UploadFile." uf ON ui.AvatarUploadFileId = uf.UploadFileId
                WHERE pc.ParentId = 0 AND pc.ProductId = :ProductId
                AND ui.UserId = pc.UserId
                AND pc.UserId = ur.UserId AND ur.UserGroupId = ug.UserGroupId;";
            $allCount = $this->dbOperator->GetInt($sqlCount,$dataProperty);
        }
        return $result;
    }
}