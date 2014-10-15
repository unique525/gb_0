<?php

/**
 * 会员浏览记录数据类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_UserExplore
 * @author hy
 */
class UserExploreManageData extends BaseManageData
{
    /**
     * 取得字段数据集
     * @param string $tableName 表名
     * @return array 字段数据集
     */
    public function GetFields($tableName = self::TableName_UserExplore){
        return parent::GetFields(self::TableName_UserExplore);
    }

    /**
     * 新增会员浏览记录
     * @param array $httpPostData $_post数组
     * @return int 会员浏览记录Id
     */
    public function Create($httpPostData)
    {
        $dataProperty = new DataProperty();
        $sql = parent::GetInsertSql($httpPostData, self::TableName_UserExplore, $dataProperty);
        $result = $this->dbOperator->LastInsertId($sql, $dataProperty);
        return $result;
    }

    /**
     * 异步修改状态
     * @param string $productPriceId 会员浏览记录Id
     * @param string $state 状态
     * @return int 执行结果
     */
    public function ModifyState($productPriceId,$state)
    {
        $result = -1;
        if ($productPriceId < 0) {
            return $result;
        }
        $sql = "UPDATE " . self::TableName_UserExplore . " SET State=:State WHERE UserExploreId=:UserExploreId;";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("UserExploreId", $productPriceId);
        $dataProperty->AddField("State", $state);
        $result = $this->dbOperator->Execute($sql, $dataProperty);
        return $result;
    }

    /**
     * 修改会员浏览记录
     * @param array $httpPostData $_post数组
     * @param int $productPriceId 会员浏览记录Id
     * @return int 执行结果
     */
    public function Modify($httpPostData, $productPriceId)
    {
        $result = -1;
        if ($productPriceId < 0) {
            return $result;
        }
        $dataProperty = new DataProperty();
        $sql = parent::GetUpdateSql($httpPostData, self::TableName_UserExplore, self::TableId_UserExplore, $productPriceId, $dataProperty);
        $result = $this->dbOperator->Execute($sql, $dataProperty);
        return $result;
    }

    /**
     * 一个会员浏览记录的信息
     * @param int $productPriceId 会员浏览记录Id
     * @return array 会员浏览记录一维数组
     */
    public function GetOne($productPriceId)
    {
        $result = null;
        if ($productPriceId < 0) {
            return $result;
        }
        $sql = "SELECT UserExploreId,TableType,TableId,CreateDate,ExploreUrl,ExploreDevice FROM " . self::TableName_UserExplore . " WHERE UserExploreId=:UserExploreId;";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("UserExploreId", $productPriceId);
        $result = $this->dbOperator->GetArray($sql, $dataProperty);
        return $result;
    }

    /**
     * 获取会员浏览记录数组通用
     * @param int $productId   产品ID
     * @param string $order 排序方式
     * @param int $topCount 显示的条数
     * @return array  返回查询题目数组
     */
    public function GetList($productId, $order = "", $topCount = -1)
    {
        $result = null;
        if ($topCount != -1)
            $topCount = " limit " . $topCount;
        else $topCount = "";
        if ($productId > 0) {
            switch ($order) {
                default:
                    $order = " ORDER BY Sort DESC,UserExploreId ASC";
                    break;
            }
            $sql = "
            SELECT UserExploreId,TableType,TableId,CreateDate,ExploreUrl,ExploreDevice
            FROM " . self::TableName_UserExplore . "
            WHERE ProductId=:ProductId"
                . $order
                . $topCount;
            $dataProperty = new DataProperty();
            $dataProperty->AddField("ProductId", $productId);
            $result = $this->dbOperator->GetArrayList($sql, $dataProperty);
        }
        return $result;
    }

    /**
     * 获取会员浏览记录分页列表
     * @param int $productId 产品Id
     * @param int $pageBegin 起始页码
     * @param int $pageSize 每页记录数
     * @param int $allCount 记录总数
     * @param string $searchKey 查询字符
     * @return array  会员浏览记录列表数组
     */
    public function GetListForPager($productId, $pageBegin, $pageSize, &$allCount, $searchKey = "")
    {
        $result = null;
        if ($productId < 0) {
            return $result;
        }
        $dataProperty = new DataProperty();
        $dataProperty->AddField("ProductId", $productId);
        $searchSql = "";
        if (strlen($searchKey) > 0 && $searchKey != "undefined") {
            $searchSql .= " AND (UserExploreIntro like :searchKey1) AND";
            $dataProperty->AddField("searchKey1", "%" . $searchKey . "%");
        }

        $sql = "
        SELECT UserExploreId,TableType,TableId,CreateDate,ExploreUrl,ExploreDevice
        FROM " . self::TableName_UserExplore . "
        WHERE ProductId=:ProductId" . $searchSql . "
        ORDER BY Sort DESC,UserExploreId ASC LIMIT " . $pageBegin . "," . $pageSize . ";";
        $result = $this->dbOperator->GetArrayList($sql, $dataProperty);
        $sql = "
        SELECT COUNT(*) FROM " . self::TableName_UserExplore . "
        WHERE ProductId=:ProductId" . $searchSql . ";";
        $allCount = $this->dbOperator->GetInt($sql, $dataProperty);
        return $result;
    }

}

?>