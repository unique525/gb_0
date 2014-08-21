<?php
/**
 * 后台管理 产品参数 数据类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_Product
 * @author hy
 */
class ProductParamManageData extends BaseManageData {

    /**
     * 获取产品参数记录
     * @param int $productId 产品Id
     * @param string $order 排序方式
     * @param int $topCount 显示的条数
     * @return array|null  列表数组
     */
    public function GetList($productId,$order,$topCount=-1)
    {
        $result = null;
        if ($topCount != -1)
            $topCount = " limit " . $topCount;
        else $topCount = "";
        if ($productId > 0) {
            switch ($order) {
                default:
                    $order = " ORDER BY t.ProductID DESC,t.ProductParamTypeID DESC";
                    break;
            }
            $sql = "SELECT t.*"
                . " FROM " . self::TableName_ProductParam ." t"
                . " LEFT OUTER JOIN" . self::TableName_ProductParamType ." t1"
                . " ON t.".self::TableId_ProductParamType."=t1.".self::TableId_ProductParamType
                . " WHERE t.ProductId=:ProductId AND t.State<100 AND t1.State<100"
                . $order
                . $topCount;
            $dataProperty = new DataProperty();
            $dataProperty->AddField("ProductId", $productId);
            $result = $this->dbOperator->GetArrayList($sql, $dataProperty);
        }
        return $result;
    }

    /**
     * 根据产品Id和产品参数类型父Id获取产品参数记录
     * @param int $productId 产品Id
     * @param int $parentId 产品参数类型parentId
     * @param string $order 排序方式
     * @param int $topCount 显示的条数
     * @return array|null  列表数组
     */
    public function GetListByParentId($productId, $parentId=-1, $order = "", $topCount=-1)
    {
        $result = null;
        if ($topCount != -1)
            $topCount = " limit " . $topCount;
        else $topCount = "";
        $dataProperty = new DataProperty();
        $searchSql = "";
        if ($parentId != -1) {
            $searchSql = $searchSql . " AND t1.ParentId =:ParentId";
            $dataProperty->AddField("ParentId", $parentId);
        }
        if ($productId > 0) {
            $sql = "SELECT t.*,t1." . self::TableId_ProductParamType . ",t1.ParamTypeName"
                . " FROM " . self::TableName_ProductParam . " t"
                . " LEFT OUTER JOIN" . self::TableName_ProductParamType . " t1"
                . " ON t." . self::TableId_ProductParamType . "=t1." . self::TableId_ProductParamType
                . " WHERE t.ProductId=:ProductId AND t.State<100 and t1.State<100"
                . $searchSql
                . $order
                . $topCount;
            $dataProperty->AddField("productId", $productId);
            $result = $this->dbOperator->GetArrayList($sql, $dataProperty);
        }
        return $result;
    }

}
?>