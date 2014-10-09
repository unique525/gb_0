<?php

/**
 * 前台管理 产品价格 数据类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_Product
 * @author yin
 */
class ProductPricePublicData extends BasePublicData
{

    /**
     * 一个产品价格的信息
     * @param int $productPriceId 产品价格Id
     * @return array 产品价格一维数组
     */
    public function GetOne($productPriceId)
    {
        $result = null;
        if ($productPriceId < 0) {
            return $result;
        }
        $sql = "SELECT ProductPriceId,ProductId,ProductPriceValue,ProductPriceIntro,ProductCount,ProductUnit,Remark,Sort,State,CreateDate FROM " . self::TableName_ProductPrice . " WHERE ProductPriceId=:ProductPriceId;";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("ProductPriceId", $productPriceId);
        $result = $this->dbOperator->GetArray($sql, $dataProperty);
        return $result;
    }

    /**
     * 获取产品价格数组通用
     * @param int $productId   产品ID
     * @param string $order 排序方式
     * @param int $topCount 显示的条数
     * @return array  返回查询题目数组
     */
    public function GetList($productId, $order = "", $topCount = null)
    {
        $result = null;
        if ($topCount != null)
            $topCount = " limit " . $topCount;
        else $topCount = "";
        if ($productId > 0) {
            switch ($order) {
                default:
                    $order = " ORDER BY Sort DESC,ProductPriceId ASC";
                    break;
            }
            $sql = "
            SELECT ProductPriceId,ProductId,ProductPriceValue,ProductPriceIntro,ProductCount,ProductUnit,Remark,Sort,State,CreateDate
            FROM " . self::TableName_ProductPrice . "
            WHERE State=0 AND ProductId=:ProductId"
                . $order
                . $topCount;
            $dataProperty = new DataProperty();
            $dataProperty->AddField("ProductId", $productId);
            $result = $this->dbOperator->GetArrayList($sql, $dataProperty);
        }
        return $result;
    }

    /**
     * 获取产品价格分页列表
     * @param int $productId 产品Id
     * @param int $pageBegin 起始页码
     * @param int $pageSize 每页记录数
     * @param int $allCount 记录总数
     * @param string $searchKey 查询字符
     * @return array  产品价格列表数组
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
            $searchSql .= " AND (ProductPriceIntro like :searchKey1) AND";
            $dataProperty->AddField("searchKey1", "%" . $searchKey . "%");
        }

        $sql = "
        SELECT ProductPriceId,ProductId,ProductPriceValue,ProductPriceIntro,ProductCount,ProductUnit,Remark,Sort,State,CreateDate
        FROM " . self::TableName_ProductPrice . "
        WHERE ProductId=:ProductId" . $searchSql . "
        ORDER BY Sort DESC,ProductPriceId ASC LIMIT " . $pageBegin . "," . $pageSize . ";";
        $result = $this->dbOperator->GetArrayList($sql, $dataProperty);
        $sql = "
        SELECT COUNT(*) FROM " . self::TableName_ProductPrice . "
        WHERE ProductId=:ProductId" . $searchSql . ";";
        $allCount = $this->dbOperator->GetInt($sql, $dataProperty);
        return $result;
    }

} 