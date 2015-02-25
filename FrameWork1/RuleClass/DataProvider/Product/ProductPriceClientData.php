<?php
/**
 * 客户端 产品价格 数据类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_Product
 * @author zhangchi
 */
class ProductPriceClientData extends BasePublicData {
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

        if ($topCount != null){
            $topCount = " limit " . $topCount;
        }

        else {
            $topCount = "";
        }
        if ($productId > 0) {
            switch ($order) {
                default:
                    $order = " ORDER BY Sort DESC,ProductPriceId ASC";
                    break;
            }
            $sql = "
            SELECT ProductPriceId,ProductId,ProductPriceValue,ProductPriceIntro,ProductCount,ProductUnit,Remark,Sort,State,CreateDate,ManageUserId
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
     * 修改库存数量
     * @param int $productPriceId
     * @param int $productCount
     * @return int
     */
    public function ModifyProductCount($productPriceId, $productCount){
        $result = -1;
        if($productPriceId>0){

            if(intval($productCount)<0){
                $productCount = 0;
            }

            $dataProperty = new DataProperty();
            $sql = "UPDATE " . self::TableName_ProductPrice . " SET
                        ProductCount = :ProductCount
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


    /**
     * 取得库存数量
     * @param int $productPriceId
     * @param bool $withCache
     * @return int
     */
    public function GetProductCount($productPriceId, $withCache)
    {
        $result = -1;
        if ($productPriceId > 0) {
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'product_price_data';
            $cacheFile = 'product_price_get_product_count.cache_' . $productPriceId . '';
            $sql = "SELECT ProductCount FROM " . self::TableName_ProductPrice . " WHERE ProductPriceId=:ProductPriceId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("ProductPriceId", $productPriceId);
            $result = $this->GetInfoOfIntValue($sql, $dataProperty, $withCache, $cacheDir, $cacheFile);
        }
        return $result;
    }

    /**
     * 取得价格
     * @param int $productPriceId
     * @param bool $withCache
     * @return float 价格
     */
    public function GetProductPriceValue($productPriceId, $withCache)
    {
        $result = -1;
        if ($productPriceId > 0) {
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'product_price_data';
            $cacheFile = 'product_price_get_product_price_value.cache_' . $productPriceId . '';
            $sql = "SELECT ProductPriceValue FROM " . self::TableName_ProductPrice . " WHERE ProductPriceId=:ProductPriceId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("ProductPriceId", $productPriceId);
            $result = $this->GetInfoOfFloatValue($sql, $dataProperty, $withCache, $cacheDir, $cacheFile);
        }
        return $result;
    }
} 