<?php
/**
 * 后台管理 产品参数类型 数据类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_Product
 * @author hy
 */
class ProductParamTypeManageData extends BaseManageData {
    /**
     * 新增产品参数类型
     * @param array $httpPostData $_POST数组
     * @param int $uploadFileId 附件id
     * @return int 新增的产品参数类型id
     */
    public function Create($httpPostData,$uploadFileId = 0){
        $result = -1;
        $dataProperty = new DataProperty();
        if (!empty($httpPostData)) {
            $sql = parent::GetInsertSql(
                $httpPostData,
                self::TableName_ProductParamType,
                $dataProperty,
                "uploadFileId",
                $uploadFileId
            );
            $result = $this->dbOperator->LastInsertId($sql, $dataProperty);
        }
        return $result;
    }

    /**
     * 修改产品参数类型
     * @param array $httpPostData $_POST数组
     * @param int $productParamTypeId 产品参数类型id
     * @param int $uploadFileId 附件id
     * @return int 返回影响的行数
     */
    public function Modify($httpPostData, $productParamTypeId,$uploadFileId = 0)
    {
        $result=-1;
        $dataProperty = new DataProperty();
        if (!empty($httpPostData)) {
            $sql = parent::GetUpdateSql(
                $httpPostData,
                self::TableName_ProductParamType,
                self::TableId_ProductParamType,
                $productParamTypeId,
                $dataProperty,
                "uploadFileId",
                $uploadFileId
            );
            $result = $this->dbOperator->Execute($sql, $dataProperty);
        }
        return $result;
    }


    /**
     * 修改产品参数类型状态
     * @param string $productParamTypeId 产品参数类型Id
     * @param string $state 状态
     * @return int 执行结果
     */
    public function ModifyState($productParamTypeId, $state)
    {
        $result = -1;
        if ($productParamTypeId > 0) {
            $sql = "update " . self::TableName_ProductParamType
                . " set state=:state"
                . " where " . self::TableId_ProductParamType . "=:".self::TableId_ProductParamType;
            $dataProperty = new DataProperty();
            $dataProperty->AddField(self::TableId_ProductParamType, $productParamTypeId);
            $dataProperty->AddField("state", $state);
            $result = $this->dbOperator->Execute($sql, $dataProperty);
        }
        return $result;
    }

    /**
     * 删除产品参数类型
     * @param int $productParamTypeId 产品参数类型id
     * @return int 返回影响的行数
     */
    public function Delete($productParamTypeId)
    {
        $result = -1;
        if ($productParamTypeId > 0) {
            $sql = "delete from " . self::TableName_ProductParamType
                . " where " . self::TableName_ProductParamType . "=:" . self::TableId_ProductParamType;
            $dataProperty = new DataProperty();
            $dataProperty->AddField(self::TableId_ProductParamType, $productParamTypeId);
            $result = $this->dbOperator->Execute($sql, $dataProperty);
        }
        return $result;
    }

    /**
     * 移动产品参数类型
     * @param int $productParamTypeId 产品参数类型id
     * @param int $parentId 产品参数类型父id
     * @return int 返回影响的行数
     */
    public function Drag($productParamTypeId, $parentId)
    {
        $result = -1;
        if ($productParamTypeId > 0 && $parentId > -1) {
            $sql = "update " . self::TableName_ProductParamType
                . " set parentId=:parentId"
                . " where " . self::TableName_ProductParamType . "=:" . self::TableId_ProductParamType;
            $dataProperty = new DataProperty();
            $dataProperty->AddField(self::TableId_ProductParamType, $productParamTypeId);
            $dataProperty->AddField("parentId", $parentId);
            $result = $this->dbOperator->Execute($sql, $dataProperty);
        }
        return $result;
    }

    /**
     * 一行产品参数记录
     * @param int $productParamTypeId 产品参数类型Id
     * @return array|null 取得对应数组
     */
    public function GetOne($productParamTypeId)
    {
        $result = null;
        if ($productParamTypeId > 0) {
            $sql = "select * from " . self::TableName_ProductParamType
                . " where " . self::TableId_ProductParamType . "=:" . self::TableId_ProductParamType;
            $dataProperty = new DataProperty();
            $dataProperty->AddField(self::TableId_ProductParamType, $productParamTypeId);
            $result = $this->dbOperator->GetArray($sql, $dataProperty);
        }
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
                . " WHERE ChannelId=:ChannelId"
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