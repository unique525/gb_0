<?php
/**
 * 前台管理 产品评论 数据类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_User
 * @author yin
 */
class ProductCommentPublicData extends BasePublicData{
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
    ) {
        $result = -1;
        if($productId > 0 && !empty($content) && $userId > 0 && !empty($userName) && $siteId > 0 && $channelId > 0){
            $sql = "INSERT INTO ".self::TableName_ProductComment
                ." (ParentId,Rank,ProductId,Subject,Content,UserId,UserName,CreateDate,
                Appraisal,ProductScore,SendScore,ServiceScore,SiteId,ChannelId,State,Sort)
                 VALUES (
                 :ParentId,:Rank,:ProductId,:Subject,:Content,:UserId,:UserName,now(),
                 :Appraisal,:ProductScore,:SendScore,:ServiceScore,:SiteId,:ChannelId,:State,:Sort
                 );";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("ParentId",$parentId);
            $dataProperty->AddField("Rank",$rank);
            $dataProperty->AddField("ProductId",$productId);
            $dataProperty->AddField("Subject",$subject);
            $dataProperty->AddField("Content",$content);
            $dataProperty->AddField("UserId",$userId);
            $dataProperty->AddField("UserName",$userName);
            $dataProperty->AddField("Appraisal",$appraisal);
            $dataProperty->AddField("ProductScore",$productScore);
            $dataProperty->AddField("SendScore",$sendScore);
            $dataProperty->AddField("ServiceScore",$serviceScore);
            $dataProperty->AddField("SiteId",$siteId);
            $dataProperty->AddField("ChannelId",$channelId);
            $dataProperty->AddField("State",$state);
            $dataProperty->AddField("Sort",$sort);
            $result = $this->dbOperator->LastInsertId($sql,$dataProperty);
        }
        return $result;
    }
}