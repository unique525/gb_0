<?php

/**
 * 前台 产品参数类型类别 数据类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_ProductParamTypeClass
 * @author hy
 */
class ProductParamTypeClassPublicData extends BasePublicData
{

    /**
     * 一个产品参数类别的信息
     * @param int $voteId 产品参数类别Id
     * @return array 产品参数类别一维数组
     */
    public function GetOne($voteId)
    {
        $result = null;
        if ($voteId < 0) {
            return $result;
        }
        $sql = "SELECT ProductParamTypeClassId,SiteId,ChannelId,ProductParamTypeClassName,State,CreateDate,Sort FROM " . self::TableName_ProductParamTypeClass . " WHERE ProductParamTypeClassId=:ProductParamTypeClassId;";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("ProductParamTypeClassId", $voteId);
        $result = $this->dbOperator->GetArray($sql, $dataProperty);
        return $result;
    }

    /**
     * 获取产品参数类别分页列表
     * @param int $siteId 站点Id
     * @param int $channelId 频道Id
     * @param int $pageBegin 起始页码
     * @param int $pageSize 每页记录数
     * @param int $allCount 记录总数
     * @param string $searchKey 查询字符
     * @return array  产品参数类别列表数组
     */
    public function GetListForPager($siteId, $channelId, $pageBegin, $pageSize, &$allCount, $searchKey = "")
    {
        $result = null;
        if ($siteId < 0 || $channelId < 0 || $pageBegin < 0 || $pageSize < 0) {
            return $result;
        }
        $dataProperty = new DataProperty();
        $dataProperty->AddField("SiteId", $siteId);
        $dataProperty->AddField("ChannelId", $channelId);
        $searchSql = "";
        if (strlen($searchKey) > 0 && $searchKey != "undefined") {
            $searchSql = " AND ProductParamTypeClassName like :searchKey1) AND";
            $dataProperty->AddField("searchKey1", "%" . $searchKey . "%");
        }
        $sql = "SELECT ProductParamTypeClassId,ProductParamTypeClassName,State,CreateDate,SiteId,ChannelId
                FROM " . self::TableName_ProductParamTypeClass . "
                WHERE SiteId=:SiteId AND ChannelId=:ChannelId" . $searchSql . "
                ORDER BY Sort DESC,ProductParamTypeClassId DESC LIMIT " . $pageBegin . "," . $pageSize . ";";
        $result = $this->dbOperator->GetArrayList($sql, $dataProperty);
        $sql = "
        SELECT COUNT(*) FROM " . self::TableName_ProductParamTypeClass . "
        WHERE SiteId=:SiteId AND ChannelId=:ChannelId". $searchSql . ";";
        $allCount = $this->dbOperator->GetInt($sql, $dataProperty);
        return $result;
    }

    /**
     * 获取产品参数类型类别记录
     * @param int $channelId 频道Id
     * @param string $order 排序方式
     * @param int $topCount 显示的条数
     * @return array|null  列表数组
     */
    public function GetList($channelId, $order = "", $topCount = null)
    {
        $result = null;
        if ($topCount != null)
            $topCount = " limit " . $topCount;
        else $topCount = "";
        if ($channelId > 0) {
            switch ($order) {
                default:
                    $order = " ORDER BY Sort DESC,Createdate DESC,CONVERT( ProductParamTypeClassName USING GBK ) COLLATE GBK_CHINESE_CI ASC";
                    break;
            }
            $sql = "SELECT *"
                . " FROM " . self::TableName_ProductParamTypeClass
                . " WHERE ChannelId=:ChannelId AND State<100"
                . $order
                . $topCount;
            $dataProperty = new DataProperty();
            $dataProperty->AddField("ChannelId", $channelId);
            $result = $this->dbOperator->GetArrayList($sql, $dataProperty);
        }
        return $result;
    }

}

?>