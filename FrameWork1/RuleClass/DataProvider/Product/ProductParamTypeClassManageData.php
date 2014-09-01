<?php

/**
 * 后台管理 产品参数类型类别 数据类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_ProductParamTypeClass
 * @author hy
 */
class ProductParamTypeClassManageData extends BaseManageData
{
    /**
     * 取得字段数据集
     * @param string $tableName 表名
     * @return array 字段数据集
     */
    public function GetFields($tableName = self::TableName_ProductParamTypeClass){
        return parent::GetFields(self::TableName_ProductParamTypeClass);
    }

    /**
     * 新增产品参数类别
     * @param array $httpPostData $_post数组
     * @return int 投票调查Id
     */
    public function Create($httpPostData)
    {
        $dataProperty = new DataProperty();
        $sql = parent::GetInsertSql($httpPostData, self::TableName_ProductParamTypeClass, $dataProperty);
        $result = $this->dbOperator->LastInsertId($sql, $dataProperty);
        return $result;
    }

    /**
     * 异步修改状态
     * @param string $voteId 产品参数类别Id
     * @param string $state 状态
     * @return int 执行结果
     */
    public function ModifyState($voteId,$state)
    {
        $result = -1;
        if ($voteId < 0) {
            return $result;
        }
        $sql = "UPDATE " . self::TableName_ProductParamTypeClass . " SET State=:State WHERE ProductParamTypeClassId=:ProductParamTypeClassId;";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("ProductParamTypeClassId", $voteId);
        $dataProperty->AddField("State", $state);
        $result = $this->dbOperator->Execute($sql, $dataProperty);
        return $result;
    }

    /**
     * 修改产品参数类别
     * @param array $httpPostData $_post数组
     * @param int $voteId 产品参数类别Id
     * @return int 执行结果
     */
    public function Modify($httpPostData, $voteId)
    {
        $result = -1;
        if ($voteId < 0) {
            return $result;
        }
        $dataProperty = new DataProperty();
        $sql = parent::GetUpdateSql($httpPostData, self::TableName_ProductParamTypeClass, self::TableId_ProductParamTypeClass, $voteId, $dataProperty);
        $result = $this->dbOperator->Execute($sql, $dataProperty);
        return $result;
    }

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
    public function GetList($channelId, $order = "", $topCount = -1)
    {
        $result = null;
        if ($topCount != -1)
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