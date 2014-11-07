<?php

/**
 * 前台 频道 数据类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_Channel
 * @author zhangchi
 */
class ChannelPublicData extends BasePublicData {

    /**
     * 返回一行数据
     * @param int $channelId 频道id
     * @return array|null 取得对应数组
     */
    public function GetOne($channelId){
        $result = null;
        if($channelId>0){
            $sql = "SELECT * FROM " . self::TableName_Channel . " WHERE ChannelId=:ChannelId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("ChannelId", $channelId);
            $result = $this->dbOperator->GetArray($sql, $dataProperty);
        }
        return $result;
    }

    /**
     * 根据父id获取列表数据集
     * @param int $topCount 显示的条数
     * @param string $parentId 父id，可以是 id,id,id 的形式
     * @param string $order 排序方式
     * @return array|null 列表数据集
     */
    public function GetListByParentId($topCount, $parentId, $order){
        $result = null;
        if($parentId >0){
            switch($order){
                default:
                    $order = "ORDER BY Sort,Createdate,".self::TableId_Channel."";
                    break;
            }
            $sql = "SELECT
                        ChannelId,
                        ChannelName,
                        SiteId,
                        Icon,
                        Rank,
                        ParentId,
                        TitlePic1UploadFileId,
                        TitlePic2UploadFileId,
                        TitlePic3UploadFileId,
                        BrowserTitle,
                        BrowserDescription,
                        BrowserKeywords,
                        CreateDate,
                        Sort,
                        ChannelIntro,
                        ChildrenChannelId

                    FROM ".self::TableName_Channel."
                    WHERE

                        State<100
                        AND ParentId IN ($parentId)
                        AND IsCircle=1
                        $order
                        LIMIT $topCount;
                        ";

            $dataProperty = new DataProperty();
            //$dataProperty->AddField("ParentId", $parentId);
            $result = $this->dbOperator->GetArrayList($sql, $dataProperty);
        }
        return $result;
    }

    /**
     * 根据父id获取列表数据集
     * @param int $siteId 站点id
     * @param int $topCount 显示的条数
     * @param int $rank 级别
     * @param string $order 排序方式
     * @return array|null 列表数据集
     */
    public function GetListByRank($siteId, $topCount, $rank, $order){
        $result = null;
        if($siteId >0){
            switch($order){
                default:
                    $order = "ORDER BY Sort DESC,Createdate DESC,".self::TableId_Channel." DESC";
                    break;
            }
            $sql = "SELECT
                        ChannelId,
                        ChannelName,
                        SiteId,
                        Rank,
                        ParentId,
                        TitlePic1UploadFileId,
                        TitlePic2UploadFileId,
                        TitlePic3UploadFileId,
                        BrowserTitle,
                        BrowserDescription,
                        BrowserKeywords,
                        CreateDate,
                        Sort,
                        ChannelIntro

                    FROM ".self::TableName_Channel."
                    WHERE

                        State<100
                        AND Rank=:Rank
                        AND SiteId=:SiteId
                        AND IsCircle=1
                        $order
                        LIMIT $topCount;
                        ";

            echo $sql;
            $dataProperty = new DataProperty();
            $dataProperty->AddField("Rank", $rank);
            $dataProperty->AddField("SiteId", $siteId);
            $result = $this->dbOperator->GetArrayList($sql, $dataProperty);
        }
        return $result;
    }

}

?>
