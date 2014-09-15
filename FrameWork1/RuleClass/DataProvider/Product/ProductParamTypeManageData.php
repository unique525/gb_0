<?php
/**
 * 后台管理 产品参数类型 数据类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_Vote
 * @author hy
 */
class ProductParamTypeManageData extends BaseManageData
{
    /**
     * 取得字段数据集
     * @param string $tableName 表名
     * @return array 字段数据集
     */
    public function GetFields($tableName = self::TableName_ProductParamType){
        return parent::GetFields(self::TableName_ProductParamType);
    }

    /**
     * 新增产品参数类型
     * @param array $httpPostData $_post数组
     * @return int 返回产品参数类型Id
     */
    public function Create($httpPostData) {
        $dataProperty = new DataProperty();
        $sql = parent::GetInsertSql($httpPostData, self::TableName_ProductParamType, $dataProperty);
        $result = $this->dbOperator->LastInsertId($sql, $dataProperty);
        return $result;
    }

    /**
     * 修改产品参数类型
     * @param array $httpPostData $_post数组
     * @param int $productParamTypeId  产品参数类型Id
     * @return int 执行结果
     */
    public function Modify($httpPostData,$productParamTypeId) {
        $dataProperty = new DataProperty();
        $sql = parent::GetUpdateSql($httpPostData, self::TableName_ProductParamType, self::TableId_ProductParamType, $productParamTypeId, $dataProperty);
        $result = $this->dbOperator->Execute($sql, $dataProperty);
        return $result;
    }

    /**
     * 异步修改状态
     * @param string $productParamTypeId 产品参数类型Id
     * @param string $state 状态
     * @return int 执行结果
     */
    public function ModifyState($productParamTypeId,$state) {
        $result = -1;
        if ($productParamTypeId < 0) {
            return $result;
        }
        $sql = "UPDATE " . self::TableName_ProductParamType . " SET State=:State WHERE ProductParamTypeId=:ProductParamTypeId";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("ProductParamTypeId", $productParamTypeId);
        $dataProperty->AddField("State", $state);
        $result = $this->dbOperator->Execute($sql, $dataProperty);
        return $result;
    }

    /**
     * 获取一个产品参数类型的数据
     * @param int $productParamTypeId  产品参数类型Id
     * @return array 产品参数类型一维数组
     */
    public function GetOne($productParamTypeId) {
        $sql = "
        SELECT ProductParamTypeId,ProductParamTypeClassId,ParamTypeName,ParamValueType,Sort,State,CreateDate
        FROM
        " . self::TableName_ProductParamType .
            " WHERE ProductParamTypeId=:ProductParamTypeId";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("ProductParamTypeId", $productParamTypeId);
        $result = $this->dbOperator->GetArray($sql, $dataProperty);
        return $result;
    }

    /**
     * 获取产品参数类型分页列表
     * @param int $pageBegin   起始页码
     * @param int $pageSize    每页记录数
     * @param int $allCount    记录总数
     * @param int $productParamTypeClassId  产品参数类型类别Id
     * @param string $searchKey   查询字符
     * @return array  产品参数类型列表数组
     */
    public function GetListForPager($productParamTypeClassId, $pageBegin, $pageSize, &$allCount, $searchKey = "") {
        $result = null;
        if ($productParamTypeClassId < 0) {
            return $result;
        }
        $dataProperty = new DataProperty();
        $dataProperty->AddField("ProductParamTypeClassId", $productParamTypeClassId);
        $searchSql = "";
        if (strlen($searchKey) > 0 && $searchKey != "undefined") {
            $searchSql .= " AND (ParamTypeName like :searchKey1)";
            $dataProperty->AddField("searchKey1", "%" . $searchKey . "%");
        }
        $sql = "
        SELECT ProductParamTypeId,ProductParamTypeClassId,ParamTypeName,ParamValueType,Sort,State,CreateDate
        FROM " . self::TableName_ProductParamType ."
        WHERE ProductParamTypeClassId=:ProductParamTypeClassId" . $searchSql . "
        ORDER BY Sort DESC,ProductParamTypeId ASC LIMIT " . $pageBegin . "," . $pageSize . "";
        $result = $this->dbOperator->GetArrayList($sql, $dataProperty);
        $sql = "
        SELECT COUNT(*) FROM " . self::TableName_ProductParamType . "
        WHERE ProductParamTypeClassId=:ProductParamTypeClassId" . $searchSql;
        $allCount = $this->dbOperator->GetInt($sql, $dataProperty);
        return $result;
    }

    /**
     * 获取产品参数类型记录
     * @param int $productParamTypeClassId 产品参数类型ID
     * @param string $order 排序方式
     * @param int $topCount 显示的条数
     * @return array|null  列表数组
     */
    public function GetList($productParamTypeClassId, $order = "", $topCount = -1)
    {
        $result = null;
        if ($topCount != -1)
            $topCount = " limit " . $topCount;
        else $topCount = "";
        if ($productParamTypeClassId > 0) {
            switch ($order) {
                default:
                    $order = " ORDER BY Sort DESC,Createdate ASC";
                    break;
            }
            $sql = "SELECT *"
                . " FROM " . self::TableName_ProductParamType
                . " WHERE ProductParamTypeClassId=:ProductParamTypeClassId AND State<100"
                . $order
                . $topCount;
            $dataProperty = new DataProperty();
            $dataProperty->AddField("ProductParamTypeClassId", $productParamTypeClassId);
            $result = $this->dbOperator->GetArrayList($sql, $dataProperty);
        }
        return $result;
    }

    /**
     * 获取产品对应参数类型并且带参数值记录
     * @param int $productParamTypeClassId 产品参数类型ID
     * @param int $productId 产品ID
     * @param string $order 排序方式
     * @param int $topCount 显示的条数
     * @return array|null  列表数组
     */
    public function GetListWithValue($productParamTypeClassId, $productId, $order = "", $topCount = -1)
    {
        $result = null;
        if ($topCount != -1)
            $topCount = " limit " . $topCount;
        else $topCount = "";
        if ($productParamTypeClassId > 0&&$productId > 0) {
            switch ($order) {
                default:
                    $order = " ORDER BY t.Sort DESC,t.Createdate ASC";
                    break;
            }
            $sql = "SELECT t.ParamTypeName,t.ParamValueType,t1.*"
                . " FROM " . self::TableName_ProductParamType . " t LEFT OUTER JOIN ". self::TableName_ProductParam ." t1"
                . " ON t1.ProductId=:ProductId AND t.ProductParamTypeId=t1.ProductParamTypeId"
                . " WHERE t.ProductParamTypeClassId=:ProductParamTypeClassId AND t.State<100"
                . $order
                . $topCount;
            $dataProperty = new DataProperty();
            $dataProperty->AddField("ProductId", $productId);
            $dataProperty->AddField("ProductParamTypeClassId", $productParamTypeClassId);
            $result = $this->dbOperator->GetArrayList($sql, $dataProperty);
        }
        return $result;
    }

}

?>