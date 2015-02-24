<?php
/**
 * 后台管理 产品品牌 数据类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_Product
 * @author hy
 */
class ProductBrandManageData extends BaseManageData {
    /**
     * 新增产品品牌
     * @param array $httpPostData $_POST数组
     * @return int 新增的产品品牌id
     */
    public function Create($httpPostData)
    {
        $dataProperty = new DataProperty();
        $sql = parent::GetInsertSql($httpPostData, self::TableName_ProductBrand, $dataProperty);
        $result = $this->dbOperator->LastInsertId($sql, $dataProperty);
        return $result;
    }

    /**
     * 修改产品品牌
     * @param array $httpPostData $_POST数组
     * @param int $productBrandId 产品品牌id
     * @return int 返回影响的行数
     */
    public function Modify($httpPostData, $productBrandId)
    {
        $result = -1;
        if ($productBrandId < 0) {
            return $result;
        }
        $dataProperty = new DataProperty();
        $sql = parent::GetUpdateSql($httpPostData, self::TableName_ProductBrand, self::TableId_ProductBrand, $productBrandId, $dataProperty);
        $result = $this->dbOperator->Execute($sql, $dataProperty);
        return $result;
    }


    /**
     * 修改产品品牌状态
     * @param string $productBrandId 产品品牌Id
     * @param string $state 状态
     * @return int 执行结果
     */
    public function ModifyState($productBrandId, $state)
    {
        $result = -1;
        if ($productBrandId > 0) {
            $sql = "update " . self::TableName_ProductBrand
                . " set state=:state"
                . " where " . self::TableId_ProductBrand . "=:".self::TableId_ProductBrand;
            $dataProperty = new DataProperty();
            $dataProperty->AddField(self::TableId_ProductBrand, $productBrandId);
            $dataProperty->AddField("state", $state);
            $result = $this->dbOperator->Execute($sql, $dataProperty);
        }
        return $result;
    }

    /**
     * 移动产品品牌
     * @param int $productBrandId 产品品牌id
     * @param int $parentId 产品品牌父id
     * @param int $rank rank值
     * @return int 返回影响的行数
     */
    public function Drag($productBrandId, $parentId, $rank)
    {
        $result = -1;
        if ($productBrandId > 0 && $parentId >= -1) {
            $sql = "update " . self::TableName_ProductBrand
                . " set ParentId=:ParentId,Rank=:Rank"
                . " where " . self::TableId_ProductBrand . "=:" . self::TableId_ProductBrand;
            $dataProperty = new DataProperty();
            $dataProperty->AddField(self::TableId_ProductBrand, $productBrandId);
            $dataProperty->AddField("ParentId", $parentId);
            $dataProperty->AddField("Rank", $rank);
            $result = $this->dbOperator->Execute($sql, $dataProperty);
        }
        return $result;
    }

    /**
     * 一行产品参数记录
     * @param int $productBrandId 产品品牌Id
     * @return array|null 取得对应数组
     */
    public function GetOne($productBrandId)
    {
        $result = null;
        if ($productBrandId > 0) {
            $sql = "select * from " . self::TableName_ProductBrand
                . " where " . self::TableId_ProductBrand . "=:" . self::TableId_ProductBrand;
            $dataProperty = new DataProperty();
            $dataProperty->AddField(self::TableId_ProductBrand, $productBrandId);
            $result = $this->dbOperator->GetArray($sql, $dataProperty);
        }
        return $result;
    }

    /**
     * 获取产品品牌记录
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
                    $order = " ORDER BY Sort DESC,Createdate DESC,CONVERT( ProductBrandName USING GBK ) COLLATE GBK_CHINESE_CI ASC";
                    break;
            }
            $sql = "SELECT *"
                . " FROM " . self::TableName_ProductBrand
                . " WHERE SiteId=:SiteId AND State<100"
                . $order
                . $topCount;
            $dataProperty = new DataProperty();
            $dataProperty->AddField("SiteId", $channelId);
            $result = $this->dbOperator->GetArrayList($sql, $dataProperty);
        }
        return $result;
    }

    /**
     * 修改产品品牌表上传文件id
     * @param int $productBrandId 产品品牌表id
     * @param int $PicUploadFileId 上传文件id
     * @return int 操作结果
     */
    public function ModifyLogoUploadFileId($productBrandId, $PicUploadFileId)
    {
        $result = -1;
        if($productBrandId>0){
            $dataProperty = new DataProperty();
            $sql = "UPDATE " . self::TableName_ProductBrand . " SET
                    LogoUploadFileId = :LogoUploadFileId
                    WHERE ProductBrandId = :ProductBrandId
                    ;";
            $dataProperty->AddField("LogoUploadFileId", $PicUploadFileId);
            $dataProperty->AddField("ProductBrandId", $productBrandId);
            $result = $this->dbOperator->Execute($sql, $dataProperty);
        }

        return $result;
    }

    /**
     * 取得图片的上传文件id
     * @param int $productBrandId 产品品牌id
     * @param bool $withCache 是否从缓冲中取
     * @return int 图片的上传文件id
     */
    public function GetLogoUploadFileId($productBrandId, $withCache)
    {
        $result = -1;
        if ($productBrandId > 0) {
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'product_brand_data';
            $cacheFile = 'product_brand_get_pic_upload_file_id.cache_' . $productBrandId . '';
            $sql = "SELECT LogoUploadFileId FROM " . self::TableName_ProductBrand . " WHERE ProductBrandId = :ProductBrandId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("ProductBrandId", $productBrandId);
            $result = $this->GetInfoOfIntValue($sql, $dataProperty, $withCache, $cacheDir, $cacheFile);
        }
        return $result;
    }

}
?>