<?php

/**
 * 产品价格数据类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_ProductPrice
 * @author hy
 */
class ProductPriceManageData extends BaseManageData
{
    /**
     * 取得字段数据集
     * @param string $tableName 表名
     * @return array 字段数据集
     */
    public function GetFields($tableName = self::TableName_ProductPrice)
    {
        return parent::GetFields(self::TableName_ProductPrice);
    }

    /**
     * 新增产品价格
     * @param array $httpPostData $_post数组
     * @return int 产品价格Id
     */
    public function Create($httpPostData)
    {
        $dataProperty = new DataProperty();
        $sql = parent::GetInsertSql($httpPostData, self::TableName_ProductPrice, $dataProperty);
        $result = $this->dbOperator->LastInsertId($sql, $dataProperty);
        return $result;
    }


    /**
     * 新增产品价格
     * @param int $productId
     * @param float $productPriceValue
     * @param string $productPriceIntro
     * @param int $productCount
     * @param string $productUnit
     * @param string $remark
     * @param int $state
     * @param int $sort
     * @param int $manageUserId
     * @return int 产品价格Id
     */
    public function CreateForProductCreate(
        $productId,
        $productPriceValue,
        $productPriceIntro,
        $productCount,
        $productUnit,
        $remark,
        $state,
        $sort,
        $manageUserId
    )
    {

        $sql = "INSERT INTO
                " . self::TableName_ProductPrice . "
                (
                    ProductId,
                    ProductPriceValue,
                    ProductPriceIntro,
                    ProductCount,
                    ProductUnit,
                    Remark,
                    State,
                    Sort,
                    ManageUserId,
                    CreateDate
                )
                VALUES
                (
                    :ProductId,
                    :ProductPriceValue,
                    :ProductPriceIntro,
                    :ProductCount,
                    :ProductUnit,
                    :Remark,
                    :State,
                    :Sort,
                    :ManageUserId,
                    now()
                );

                ";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("ProductId", $productId);
        $dataProperty->AddField("ProductPriceValue", $productPriceValue);
        $dataProperty->AddField("ProductPriceIntro", $productPriceIntro);
        $dataProperty->AddField("ProductCount", $productCount);
        $dataProperty->AddField("ProductUnit", $productUnit);
        $dataProperty->AddField("Remark", $remark);
        $dataProperty->AddField("State", $state);
        $dataProperty->AddField("Sort", $sort);
        $dataProperty->AddField("ManageUserId", $manageUserId);

        $result = $this->dbOperator->LastInsertId($sql, $dataProperty);
        return $result;
    }

    /**
     * 异步修改状态
     * @param string $productPriceId 产品价格Id
     * @param string $state 状态
     * @return int 执行结果
     */
    public function ModifyState($productPriceId, $state)
    {
        $result = -1;
        if ($productPriceId < 0) {
            return $result;
        }
        $sql = "UPDATE " . self::TableName_ProductPrice . " SET State=:State WHERE ProductPriceId=:ProductPriceId;";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("ProductPriceId", $productPriceId);
        $dataProperty->AddField("State", $state);
        $result = $this->dbOperator->Execute($sql, $dataProperty);
        return $result;
    }

    /**
     * 修改产品价格
     * @param array $httpPostData $_post数组
     * @param int $productPriceId 产品价格Id
     * @return int 执行结果
     */
    public function Modify($httpPostData, $productPriceId)
    {
        $result = -1;
        if ($productPriceId < 0) {
            return $result;
        }
        $dataProperty = new DataProperty();
        $sql = parent::GetUpdateSql($httpPostData, self::TableName_ProductPrice, self::TableId_ProductPrice, $productPriceId, $dataProperty);
        $result = $this->dbOperator->Execute($sql, $dataProperty);
        return $result;
    }

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
     * @param int $productId 产品ID
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
                    $order = " ORDER BY Sort DESC,ProductPriceId ASC";
                    break;
            }
            $sql = "
            SELECT ProductPriceId,ProductId,ProductPriceValue,ProductPriceIntro,ProductCount,ProductUnit,Remark,Sort,State,CreateDate
            FROM " . self::TableName_ProductPrice . "
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

    /**
     * 增加库存数量
     * @param $productPriceId
     * @param $productCount
     * @return int
     */
    public function AddProductCount($productPriceId,$productCount){
        $result = -1;
        if($productPriceId>0){

            if(intval($productCount)<0){
                $productCount = 0;
            }

            $dataProperty = new DataProperty();
            $sql = "UPDATE " . self::TableName_ProductPrice . " SET
                        ProductCount = ProductCount+:ProductCount
                        WHERE ProductPriceId = :ProductPriceId
                        ;";
            $dataProperty->AddField("ProductCount", $productCount);
            $dataProperty->AddField("ProductPriceId", $productPriceId);
            $result = $this->dbOperator->Execute($sql, $dataProperty);

            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'product_price_data';
            $cacheFile = 'product_price_get_product_count.cache_' . $productPriceId . '';
            DataCache::Remove($cacheDir . DIRECTORY_SEPARATOR . $cacheFile);

        }
        return $result;
    }
}

?>