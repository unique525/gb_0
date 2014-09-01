<?php

/**
 *
 * @author hongyi
 */
class ProductParamTypeOptionManageData extends BaseManageData
{
    /**
     * 取得字段数据集
     * @param string $tableName 表名
     * @return array 字段数据集
     */
    public function GetFields($tableName = self::TableName_ProductParamTypeOption){
        return parent::GetFields(self::TableName_ProductParamTypeOption);
    }

    /**
     * 新增产品参数类型选项
     * @param array $httpPostData $_POST数组
     * @param int $titlePicUploadFileId 题图附件id
     * @return int 新增的产品参数类型选项id
     */
    public function Create($httpPostData,$titlePicUploadFileId = 0)
    {
        $result = -1;
        $dataProperty = new DataProperty();
        if (!empty($httpPostData)) {
            $sql = parent::GetInsertSql(
                $httpPostData,
                self::TableName_ProductParamTypeOption,
                $dataProperty,
                "TitlePicUploadFileId",
                $titlePicUploadFileId
            );
            $result = $this->dbOperator->LastInsertId($sql, $dataProperty);
        }
        return $result;
    }
    
    /**
     * 修改产品参数类型选项
     * @param array $httpPostData $_POST数组
     * @param int $productParamTypeOptionId 产品参数类型选项id
     * @param int $titlePicUploadFileId 题图所在附件id
     * @return int 返回影响的行数
     */
    public function Modify($httpPostData, $productParamTypeOptionId,$titlePicUploadFileId = 0)
    {
        $result=-1;
        $dataProperty = new DataProperty();
        $addFieldName = "";
        $addFieldValue = "";
        if (intval($titlePicUploadFileId)>0) {
            $addFieldName = "TitlePic1UploadFileId";
            $addFieldValue = $titlePicUploadFileId;
        }
        if (!empty($httpPostData)) {
            $sql = parent::GetUpdateSql(
                $httpPostData,
                self::TableName_ProductParamTypeOption,
                self::TableId_ProductParamTypeOption,
                $productParamTypeOptionId,
                $dataProperty,
                $addFieldName,
                $addFieldValue
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
                . " WHERE ProductParamTypeId=:ProductParamTypeId AND State<100"
                . $order
                . $topCount;
            $dataProperty = new DataProperty();
            $dataProperty->AddField("ProductParamTypeId", $productParamTypeId);
            $result = $this->dbOperator->GetArrayList($sql, $dataProperty);
        }
        return $result;
    }


    /**
     * 获取选项分页列表
     * @param int $pageBegin   起始页码
     * @param int $pageSize    每页记录数
     * @param int $allCount    记录总数
     * @param int $productParamTypeId  产品参数类型Id
     * @param string $searchKey   查询字符
     * @return array  选项列表数组
     */
    public function GetListForPager($productParamTypeId, $pageBegin, $pageSize, &$allCount, $searchKey = "") {
        $result = null;
        if ($productParamTypeId < 0) {
            return $result;
        }
        $dataProperty = new DataProperty();
        $dataProperty->AddField("ProductParamTypeId", $productParamTypeId);
        $searchSql = "";
        if (strlen($searchKey) > 0 && $searchKey != "undefined") {
            $searchSql .= " AND (OptionName LIKE :searchKey1)";
            $dataProperty->AddField("searchKey1", "%" . $searchKey . "%");
        }

        $sql = "
        SELECT ProductParamTypeId,ProductParamTypeOptionId,OptionName,Sort,State
        FROM " . self::TableName_ProductParamTypeOption . "
        WHERE ProductParamTypeId=:ProductParamTypeId" . $searchSql . "
        ORDER BY Sort DESC,ProductParamTypeOptionId ASC
        LIMIT " . $pageBegin . "," . $pageSize . "";
        $result = $this->dbOperator->GetArrayList($sql, $dataProperty);
        $sql = "
        SELECT COUNT(*) FROM " . self::TableName_ProductParamTypeOption . "
        WHERE ProductParamTypeId=:ProductParamTypeId" . $searchSql;
        $allCount = $this->dbOperator->GetInt($sql, $dataProperty);
        return $result;
    }
}

?>