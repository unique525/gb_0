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
        SELECT ProductParamTypeId,ProductParamTypeClassId,ParamTypeName,Sort,State,CreateDate
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
        $dataProperty = new DataProperty();
        $searchSql = "WHERE";
        if ($productParamTypeClassId > 0) {
            $searchSql .= " ProductParamTypeClassId=:ProductParamTypeClassId AND";
            $dataProperty->AddField("ProductParamTypeClassId", $productParamTypeClassId);
        }
        if (strlen($searchKey) > 0 && $searchKey != "undefined") {
            $searchSql .= " (ParamTypeName like :searchKey1) AND";
            $dataProperty->AddField("searchKey1", "%" . $searchKey . "%");
        }
        if (strlen($searchSql) > 5)
            $searchSql = substr($searchSql, 0, strlen($searchSql) - 3);
        else
            $searchSql = "";
        $sql = "
        SELECT ProductParamTypeId,ProductParamTypeClassId,ParamTypeName,Sort,State,CreateDate
        FROM " . self::TableName_ProductParamType . " " . $searchSql . "
        ORDER BY Sort DESC,ProductParamTypeId ASC LIMIT " . $pageBegin . "," . $pageSize . "";
        $result = $this->dbOperator->GetArrayList($sql, $dataProperty);
        $sql = "SELECT COUNT(*) FROM " . self::TableName_ProductParamType . " " . $searchSql;
        $allCount = $this->dbOperator->GetInt($sql, $dataProperty);
        return $result;
    }

    /**
     * 获取产品参数类型记录
     * @param int $channelId 频道Id
     * @param string $order 排序方式
     * @param int $topCount 显示的条数
     * @return array|null  列表数组
     */
    public function GetList($channelId, $order = "", $topCount = -1)
    {
        $result = null;
        if ($topCount != -1)
            $topCount = " limit " . $topCount;
        else $topCount = "";
        if ($channelId > 0) {
            switch ($order) {
                default:
                    $order = " ORDER BY Sort DESC,Createdate DESC,CONVERT( ParamTypeName USING GBK ) COLLATE GBK_CHINESE_CI ASC";
                    break;
            }
            $sql = "SELECT *"
                . " FROM " . self::TableName_ProductParamType
                . " WHERE ChannelId=:ChannelId AND State<100"
                . $order
                . $topCount;
            $dataProperty = new DataProperty();
            $dataProperty->AddField("ChannelId", $channelId);
            $result = $this->dbOperator->GetArrayList($sql, $dataProperty);
        }
        return $result;
    }

}

?>