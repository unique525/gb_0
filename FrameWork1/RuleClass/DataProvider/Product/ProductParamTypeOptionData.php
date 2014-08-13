<?php

/**
 *
 * @author hongyi
 */
class ProductParamTypeOptionData extends BaseManageData
{
    /**
     * 新增产品参数类型选项
     * @param array $httpPostData $_POST数组
     * @param int $uploadFileId 附件id
     * @return int 新增的产品参数类型选项id
     */
    public function Create($httpPostData,$uploadFileId = 0)
    {
        $result = -1;
        $dataProperty = new DataProperty();
        if (!empty($httpPostData)) {
            $sql = parent::GetInsertSql(
                $httpPostData,
                self::TableName_ProductParamTypeOption,
                $dataProperty,
                "uploadFileId",
                $uploadFileId
            );
            $result = $this->dbOperator->LastInsertId($sql, $dataProperty);
        }
        return $result;
    }
    
    /**
     * 修改产品参数类型选项
     * @param array $httpPostData $_POST数组
     * @param int $productParamTypeOptionId 产品参数类型选项id
     * @param int $uploadFileId 附件id
     * @return int 返回影响的行数
     */
    public function Modify($httpPostData, $productParamTypeOptionId,$uploadFileId = 0)
    {
        $result=-1;
        $dataProperty = new DataProperty();
        if (!empty($httpPostData)) {
            $sql = parent::GetUpdateSql(
                $httpPostData,
                self::TableName_ProductParamTypeOption,
                self::TableId_ProductParamTypeOption,
                $productParamTypeOptionId,
                $dataProperty,
                "uploadFileId",
                $uploadFileId
            );
            $result = $this->dbOperator->Execute($sql, $dataProperty);
        }
        return $result;
    }

    /**
     * 修改产品参数类型选项状态
     * @param string $productParamTypeOptionId 产品参数类型选项Id
     * @param string $state 状态
     * @return int 执行结果
     */
    public function ModifyState($productParamTypeOptionId, $state)
    {
        $result = -1;
        if ($productParamTypeOptionId > 0) {
            $sql = "update " . self::TableName_ProductParamTypeOption
                . " set state=:state"
                . " where " . self::TableId_ProductParamTypeOption . "=:".self::TableId_ProductParamTypeOption;
            $dataProperty = new DataProperty();
            $dataProperty->AddField(self::TableId_ProductParamTypeOption, $productParamTypeOptionId);
            $dataProperty->AddField("state", $state);
            $result = $this->dbOperator->Execute($sql, $dataProperty);
        }
        return $result;
    }

    /**
     * 删除产品参数类型选项
     * @param int $productParamTypeOptionId 产品参数类型选项id
     * @return int 返回影响的行数
     */
    public function Delete($productParamTypeOptionId)
    {
        $result = -1;
        if ($productParamTypeOptionId > 0) {
            $sql = "delete from " . self::TableName_ProductParamTypeOption
                . " where " . self::TableName_ProductParamTypeOption . "=:" . self::TableId_ProductParamTypeOption;
            $dataProperty = new DataProperty();
            $dataProperty->AddField(self::TableId_ProductParamTypeOption, $productParamTypeOptionId);
            $result = $this->dbOperator->Execute($sql, $dataProperty);
        }
        return $result;
    }

    /**
     * 移动产品参数类型选项
     * @param int $productParamTypeOptionId 产品参数类型选项id
     * @param int $parentId 产品参数类型选项父id
     * @return int 返回影响的行数
     */
    public function Drag($productParamTypeOptionId, $parentId)
    {
        $result = -1;
        if ($productParamTypeOptionId > 0 && $parentId > -1) {
            $sql = "update " . self::TableName_ProductParamTypeOption
                . " set parentId=:parentId"
                . " where " . self::TableName_ProductParamTypeOption . "=:" . self::TableId_ProductParamTypeOption;
            $dataProperty = new DataProperty();
            $dataProperty->AddField(self::TableId_ProductParamTypeOption, $productParamTypeOptionId);
            $dataProperty->AddField("parentId", $parentId);
            $result = $this->dbOperator->Execute($sql, $dataProperty);
        }
        return $result;
    }

    /**
     * 一行产品参数类型选项记录
     * @param int $productParamTypeOptionId 产品参数类型选项Id
     * @return array|null 取得对应数组
     */
    public function GetOne($productParamTypeOptionId)
    {
        $result = null;
        if ($productParamTypeOptionId > 0) {
            $sql = "select * from " . self::TableName_ProductParamTypeOption
                . " where " . self::TableId_ProductParamTypeOption . "=:" . self::TableId_ProductParamTypeOption;
            $dataProperty = new DataProperty();
            $dataProperty->AddField(self::TableId_ProductParamTypeOption, $productParamTypeOptionId);
            $result = $this->dbOperator->GetArray($sql, $dataProperty);
        }
        return $result;
    }

    /**
     * 获取产品参数类型选项记录
     * @param int $productParamTypeId 产品参数类型Id
     * @param string $order 排序方式
     * @param int $topCount 显示的条数
     * @return array|null  列表数组
     */
    public function GetList($productParamTypeId, $order = "", $topCount = -1)
    {
        $result = null;
        if ($topCount != -1)
            $topCount = " limit " . $topCount;
        else $topCount = "";
        if ($productParamTypeId > 0) {
            switch ($order) {
                default:
                    $order = " ORDER BY Sort DESC,CONVERT( OptionName USING GBK ) COLLATE GBK_CHINESE_CI ASC";
                    break;
            }
            $sql = "SELECT *"
                . " FROM " . self::TableName_ProductParamTypeOption
                . " WHERE ProductParamTypeId=:ProductParamTypeId"
                . $order
                . $topCount;
            $dataProperty = new DataProperty();
            $dataProperty->AddField("ProductParamTypeId", $productParamTypeId);
            $result = $this->dbOperator->GetArrayList($sql, $dataProperty);
        }
        return $result;
    }
}

?>