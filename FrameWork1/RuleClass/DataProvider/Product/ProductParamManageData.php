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
                . " WHERE t.ProductId=:ProductId and State<100"
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

    /**
     * 根据产品参数类型Id获取产品参数条数
     * @param int $productParamTypeId 产品参数类型Id
     * @return int 产品参数条数
     */
    public function GetParamCount($productParamTypeId)
    {
        $result = -1;
        if ($productParamTypeId > 0) {
            $sql = "SELECT COUNT(*)"
                . " FROM " . self::TableName_ProductParam
                . " WHERE " . self::TableId_ProductParamType . "=:" . self::TableId_ProductParamType;
            $dataProperty = new DataProperty();
            $dataProperty->AddField("productParamTypeId", $productParamTypeId);
            $result = $this->dbOperator->GetInt($sql, $dataProperty);
        }
        return $result;
    }

    /**
     * 根据产品参数类型Id和产品参数类型选项Id获取产品参数条数
     * @param int $productParamTypeId 产品参数类型Id
     * @param int $productParamTypeOptionId 产品参数类型选项Id
     * @return int 产品参数条数
     */
    public function GetParamCountByOptionID($productParamTypeId, $productParamTypeOptionId)
    {
        $result = -1;
        if ($productParamTypeId > 0 && $productParamTypeOptionId>0) {
            $sql = "SELECT COUNT(*)"
                . " FROM " . self::TableName_ProductParam
                . " WHERE " . self::TableId_ProductParamType . "=:" . self::TableId_ProductParamType . " AND ShortStringValue=:ShortStringValue";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("productParamTypeId", $productParamTypeId);
            $dataProperty->AddField("ShortStringValue", $productParamTypeOptionId);
            $result = $this->dbOperator->GetInt($sql, $dataProperty);
        }
        return $result;
    }

    /**
     * 批量修改产品表中产品参数类型选项值
     * @param int $sourceId 产品参数类型源选项值
     * @param int $targetId 产品参数类型目标选项值
     * @param int $productParamTypeId 产品参数类型id
     * @return int 执行结果
     */
    public function UpdateShortStringValue($sourceId, $targetId, $productParamTypeId)
    {
        $result = -1;
        if ($sourceId > 0 && $targetId > 0 && $productParamTypeId > 0) {
            $sql = "UPDATE " . self::TableName_ProductParam
                . " set ShortStringValue=:targetId"
                . " WHERE ShortStringValue=:sourceId AND " . self::TableId_ProductParamType . "=:" . self::TableId_ProductParamType;
            $dataProperty = new DataProperty();
            $dataProperty->AddField("sourceId", $sourceId);
            $dataProperty->AddField("targetId", $targetId);
            $dataProperty->AddField(self::TableId_ProductParamType, $productParamTypeId);
            $result = $this->dbOperator->Execute($sql, $dataProperty);
        }
        return $result;
    }

}
?>