<?php
/**
 * 客户端 产品参数 数据类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_Product
 * @author hy
 */
class ProductParamClientData extends BasePublicData {
    /**
     * 获取产品参数数组通用
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
                    $order = " ORDER BY t2.ProductParamTypeClassId ASC,t.ProductParamId ASC";
                    break;
            }
            $sql = "
            SELECT t.ProductParamId,t.ProductId,t.ProductParamTypeId,t.Remark,t.ShortStringValue,
            t.LongStringValue,t.MaxStringValue,t.FloatValue,t.MoneyValue,t.UrlValue,t.ProductParamTypeOptionId,
            t1.ParamTypeName,t1.ParamValueType,
            t2.ProductParamTypeClassId,t2.ProductParamTypeClassName
            FROM " . self::TableName_ProductParam . " t INNER JOIN " . self::TableName_ProductParamType . " t1
            ON t.ProductId=:ProductId AND t.ProductParamTypeId=t1.ProductParamTypeId
            INNER JOIN " . self::TableName_ProductParamTypeClass . " t2
            ON t1.ProductParamTypeClassId=t2.ProductParamTypeClassId"
                . $order
                . $topCount;
            $dataProperty = new DataProperty();
            $dataProperty->AddField("ProductId", $productId);
            $result = $this->dbOperator->GetArrayList($sql, $dataProperty);
        }
        return $result;
    }
} 