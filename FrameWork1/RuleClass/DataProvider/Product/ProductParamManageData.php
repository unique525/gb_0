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
    public function GetList($productId, $order = "", $topCount = -1)
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
                . " LEFT OUTER JOIN " . self::TableName_ProductParamType ." t1"
                . " ON t.".self::TableId_ProductParamType."=t1.".self::TableId_ProductParamType
                . " WHERE t.ProductId=:ProductId AND t1.State<100"
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
                . " WHERE t.ProductId=:ProductId AND t1.State<100"
                . $searchSql
                . $order
                . $topCount;
            $dataProperty->AddField("ProductId", $productId);
            $result = $this->dbOperator->GetArrayList($sql, $dataProperty);
        }
        return $result;
    }



    /**
     * 产品参数新增
     * @param array $httpPostData $_POST数组
     * @param string $productId 产品ID
     * @return string 执行结果
     */
    public function CreateProductParam($httpPostData,$productId) {
        $result=-1;
        $arrDataProperty = array();
        if (!empty($httpPostData)) {
            $arrSql = self::GetInsertSqlForProductParam(
                $httpPostData,
                self::TableName_ProductParam,
                $productId,
                $arrDataProperty
            );
            $result = $this->dbOperator->ExecuteBatch($arrSql, $arrDataProperty);
        }
        return $result;
    }

    /**
     * 根据POST和附加字段生成产品参数表的INSERT SQL
     * @param array $httpPostData $_POST数组
     * @param string $tableName 表名
     * @param string $productId 产品ID
     * @param array $arrDataProperty 数据对象数组
     * @param array $arrAddField 附加字段键值对数组
     * @return string SQL数组
     */
    public function GetInsertSqlForProductParam($httpPostData, $tableName, $productId, &$arrDataProperty, $arrAddField = null)
    {
        if (!empty($httpPostData)) {
            $arrSql = array();
            $arrDataProperty = array();
            foreach ($httpPostData as $key => $value) {
                $fieldNames = "";
                $fieldValues = "";
                $dataProperty = new DataProperty();
                if (strpos($key, "ppi_") === 0 || strpos($key, "pps_") === 0 || strpos($key, "ppr_") === 0) {
                    $arrControlName = explode('_', $key);
                    $controlType = $arrControlName[0];
                    $keyName = $arrControlName[1];
                    $productParamTypeId = $arrControlName[2];
                    //使用服务器时间校准CreateDate
                    if (strtolower($keyName) == 'createdate') {
                        if (strpos($value, '0000-00-00') >= 0 || empty($value)) {
                            $value = date("Y-m-d H:i:s", time());
                        }
                    }
                    if ($controlType === "ppi") { //text TextArea 类字段
                        $fieldNames = $fieldNames . ",`" . $keyName . "`";
                        $fieldValues = $fieldValues . ",:" . $keyName;
                        $dataProperty->AddField($keyName, stripslashes($value));
                    } else if ($controlType === "pps") { //下拉框类型字段
                        $fieldNames = $fieldNames . ",`" . $keyName . "`";
                        $fieldValues = $fieldValues . ",:" . $keyName;
                        $dataProperty->AddField($keyName, stripslashes($value));
                        //产品参数类型选项Id字段插入SQL
                        $productParamTypeOptionIdFieldName = "ProductParamTypeOptionId";
                        $productParamTypeOptionId = self::GetProductParamTypeOptionId($productParamTypeId, $value);
                        if ($productParamTypeOptionId > 0) {
                            $fieldNames = $fieldNames . ",`" . $productParamTypeOptionIdFieldName . "`";
                            $fieldValues = $fieldValues . ",:" . $productParamTypeOptionIdFieldName;
                            $dataProperty->AddField($productParamTypeOptionIdFieldName, stripslashes($productParamTypeOptionId));
                        }
                    } else if ($controlType === "ppr") { //radio checkbox类字段
                        $fieldNames = $fieldNames . ",`" . $keyName . "`";
                        $fieldValues = $fieldValues . ",:" . $keyName;
                        if ($value === "on") {
                            $dataProperty->AddField($keyName, "1");
                        } else {
                            $dataProperty->AddField($keyName, "0");
                        }
                    }
                    //产品ID字段插入SQL
                    $productIdFieldName = "ProductID";
                    if (strlen($productId) > 0) {
                        $fieldNames = $fieldNames . "," . $productIdFieldName;
                        $fieldValues = $fieldValues . ",:" . $productIdFieldName;
                        $dataProperty->AddField($productIdFieldName, stripslashes($productId));
                    }
                    //产品参数类型字段插入SQL
                    $productParamTypeFieldName = "ProductParamTypeId";
                    if (strlen($productParamTypeId) > 0) {
                        $fieldNames = $fieldNames . "," . $productParamTypeFieldName;
                        $fieldValues = $fieldValues . ",:" . $productParamTypeFieldName;
                        $dataProperty->AddField($productParamTypeFieldName, stripslashes($productParamTypeId));
                    }
                    //附加字段键值对数组插入SQL
                    if (count($arrAddField) > 0) {
                        foreach ($arrAddField as $addKey => $addValue) {
                            if (strlen($addKey) > 0 && strlen($addValue) > 0) {
                                $fieldNames = $fieldNames . "," . $addKey;
                                $fieldValues = $fieldValues . ",:" . $addKey;
                                $dataProperty->AddField($addKey, stripslashes($addValue));
                            }
                        }
                    }

                    //去掉字符串开头多余的逗号
                    if (strpos($fieldNames, ",") === 0) {
                        $fieldNames = substr($fieldNames, 1);
                    }
                    if (strpos($fieldValues, ",") === 0) {
                        $fieldValues = substr($fieldValues, 1);
                    }
                    $sql = "INSERT INTO " . $tableName . " (" . $fieldNames . ") VALUES (" . $fieldValues . ");";
                    //推入数组
                    $arrSql[] = $sql;
                    $arrDataProperty[] = $dataProperty;
                }
            }
            return $arrSql;
        } else {
            return null;
        }
    }

    /**
     * 产品参数修改
     * @param array $httpPostData $_POST数组
     * @param string $productId 产品ID
     * @return string 执行结果
     */
    public function ModifyProductParam($httpPostData,$productId) {
        $result=-1;
        $arrDataProperty = array();
        if (!empty($httpPostData)) {
            $arrSql = self::GetInsertSqlForProductParam(
                $httpPostData,
                self::TableName_ProductParam,
                $productId,
                $arrDataProperty
            );
            //先删除产品id对应所有参数再重新新增实现修改
            $sqlDelete = "DELETE FROM ".self::TableName_ProductParam
                ." WHERE ProductId=:ProductId";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("ProductId", $productId);
            array_unshift($arrSql, $sqlDelete);
            array_unshift($arrDataProperty, $dataProperty);
            $result = $this->dbOperator->ExecuteBatch($arrSql, $arrDataProperty);
        }
        return $result;
    }

    /**
     * 根据参数类型ID和参数值获取产品参数选项ID
     * @param int $productParamTypeId 产品参数类型id
     * @param string $OptionName 产品参数值
     * @return string 产品参数选项ID
     */
    public function GetProductParamTypeOptionId($productParamTypeId, $OptionName)
    {
        $sql = "SELECT ProductParamTypeOptionId"
            . " FROM " . self::TableName_ProductParamTypeOption . " t"
            . " WHERE ProductParamTypeId=:ProductParamTypeId AND OptionName=:OptionName";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("ProductParamTypeId", $productParamTypeId);
        $dataProperty->AddField("OptionName", $OptionName);
        $result = $this->dbOperator->GetInt($sql, $dataProperty);
        return $result;
    }

}
?>