<?php

/**
 * 产品图片数据类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_ProductPic
 * @author hy
 */
class ProductPicManageData extends BaseManageData
{
    /**
     * 取得字段数据集
     * @param string $tableName 表名
     * @return array 字段数据集
     */
    public function GetFields($tableName = self::TableName_ProductPic){
        return parent::GetFields(self::TableName_ProductPic);
    }

    /**
     * 新增产品图片
     * @param array $httpPostData $_post数组
     * @return int 产品图片Id
     */
    public function Create($httpPostData)
    {
        $dataProperty = new DataProperty();
        $sql = parent::GetInsertSql($httpPostData, self::TableName_ProductPic, $dataProperty);
        $result = $this->dbOperator->LastInsertId($sql, $dataProperty);
        return $result;
    }

    /**
     * 异步修改状态
     * @param string $productPriceId 产品图片Id
     * @param string $state 状态
     * @return int 执行结果
     */
    public function ModifyState($productPriceId,$state)
    {
        $result = -1;
        if ($productPriceId < 0) {
            return $result;
        }
        $sql = "UPDATE " . self::TableName_ProductPic . " SET State=:State WHERE ProductPicId=:ProductPicId;";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("ProductPicId", $productPriceId);
        $dataProperty->AddField("State", $state);
        $result = $this->dbOperator->Execute($sql, $dataProperty);
        return $result;
    }

    /**
     * 修改产品图片
     * @param array $httpPostData $_post数组
     * @param int $productPriceId 产品图片Id
     * @return int 执行结果
     */
    public function Modify($httpPostData, $productPriceId)
    {
        $result = -1;
        if ($productPriceId < 0) {
            return $result;
        }
        $dataProperty = new DataProperty();
        $sql = parent::GetUpdateSql($httpPostData, self::TableName_ProductPic, self::TableId_ProductPic, $productPriceId, $dataProperty);
        $result = $this->dbOperator->Execute($sql, $dataProperty);
        return $result;
    }

    /**
     * 一个产品图片的信息
     * @param int $productPriceId 产品图片Id
     * @return array 产品图片一维数组
     */
    public function GetOne($productPriceId)
    {
        $result = null;
        if ($productPriceId < 0) {
            return $result;
        }
        $sql = "SELECT ProductPicId,ProductId,UploadFileId,ProductPicTag,Sort,State,CreateDate FROM " . self::TableName_ProductPic . " WHERE ProductPicId=:ProductPicId;";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("ProductPicId", $productPriceId);
        $result = $this->dbOperator->GetArray($sql, $dataProperty);
        return $result;
    }

    /**
     * 获取产品图片数组通用
     * @param int $productId   产品ID
     * @param string $order 排序方式
     * @param int $topCount 显示的条数
     * @return array  返回查询题目数组
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
                    $order = " ORDER BY t.Sort DESC,t.ProductId ASC";
                    break;
            }
            $sql = "
            SELECT t.ProductPicId,t.ProductId,t.UploadFileId,t.ProductPicTag,t.Sort,t.State,t.CreateDate,t1.*
            FROM " . self::TableName_ProductPic . " t
            LEFT OUTER JOIN " .self::TableName_UploadFile." t1 on t.UploadFileId=t1.UploadFileId
            WHERE t.ProductId=:ProductId"
            . $order
            . $topCount;
            $dataProperty = new DataProperty();
            $dataProperty->AddField("ProductId",$productId);
            $result = $this->dbOperator->GetArrayList($sql, $dataProperty);
        }
        return $result;
    }

    /**
     * 获取产品图片分页列表
     * @param int $productId 产品Id
     * @param int $pageBegin 起始页码
     * @param int $pageSize 每页记录数
     * @param int $allCount 记录总数
     * @param string $tag 图片类别
     * @param string $searchKey 查询字符
     * @return array  产品图片列表数组
     */
    public function GetListForPager($productId, $pageBegin, $pageSize, &$allCount, $tag = "", $searchKey = "")
    {
        $result = null;
        if ($productId < 0) {
            return $result;
        }
        $dataProperty = new DataProperty();
        $dataProperty->AddField("ProductId", $productId);
        $searchSql = "";
        if (strlen($tag) > 0 && $tag != "undefined") {
            $searchSql .= " AND (t.ProductPicTag=:ProductPicTag)";
            $dataProperty->AddField("ProductPicTag", $tag);
        }
        if (strlen($searchKey) > 0 && $searchKey != "undefined") {
            $searchSql .= " AND (t.ProductPicIntro like :searchKey1)";
            $dataProperty->AddField("searchKey1", "%" . $searchKey . "%");
        }

        $sql = "
        SELECT t.ProductPicId,t.ProductId,t.UploadFileId,t.ProductPicTag,t.Sort,t.State,t.CreateDate,t1.*
        FROM " . self::TableName_ProductPic . " t
        LEFT OUTER JOIN " .self::TableName_UploadFile." t1 on t.UploadFileId=t1.UploadFileId
        WHERE t.ProductId=:ProductId" . $searchSql . "
        ORDER BY t.Sort DESC,t.ProductPicId DESC LIMIT " . $pageBegin . "," . $pageSize . ";";
        $result = $this->dbOperator->GetArrayList($sql, $dataProperty);
        $sql = "
        SELECT COUNT(*) FROM " . self::TableName_ProductPic . "
        WHERE ProductId=:ProductId" . $searchSql . ";";
        $allCount = $this->dbOperator->GetInt($sql, $dataProperty);
        return $result;
    }

    /**
     * 修改产品图片表上传文件id
     * @param int $productPicId 产品图片表id
     * @param int $PicUploadFileId 上传文件id
     * @return int 操作结果
     */
    public function ModifyUploadFileId($productPicId, $PicUploadFileId)
    {
        $result = -1;
        if($productPicId>0){
            $dataProperty = new DataProperty();
            $sql = "UPDATE " . self::TableName_ProductPic . " SET
                    UploadFileId = :UploadFileId
                    WHERE ProductPicId = :ProductPicId
                    ;";
            $dataProperty->AddField("UploadFileId", $PicUploadFileId);
            $dataProperty->AddField("ProductPicId", $productPicId);
            $result = $this->dbOperator->Execute($sql, $dataProperty);
        }

        return $result;
    }

    /**
     * 取得图片的上传文件id
     * @param int $productPicId 产品id
     * @param bool $withCache 是否从缓冲中取
     * @return int 图片的上传文件id
     */
    public function GetUploadFileId($productPicId, $withCache)
    {
        $result = -1;
        if ($productPicId > 0) {
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'product_pic_data';
            $cacheFile = 'product_pic_get_pic_upload_file_id.cache_' . $productPicId . '';
            $sql = "SELECT UploadFileId FROM " . self::TableName_ProductPic . " WHERE ProductPicId = :ProductPicId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("ProductPicId", $productPicId);
            $result = $this->GetInfoOfIntValue($sql, $dataProperty, $withCache, $cacheDir, $cacheFile);
        }
        return $result;
    }

    /**
     * 获取产品图片类别数组
     * @param int $productId   产品ID
     * @param string $order 排序方式
     * @param int $topCount 显示的条数
     * @return array  返回查询题目数组
     */
    public function GetPicTagList($productId, $order = "", $topCount = -1)
    {
        $result = null;
        if ($topCount != -1)
            $topCount = " limit " . $topCount;
        else $topCount = "";
        if ($productId > 0) {
            switch ($order) {
                default:
                    $order = "  ORDER BY CONVERT( ProductPicTag USING GBK ) COLLATE GBK_CHINESE_CI ASC";
                    break;
            }
            $sql = "
            SELECT ProductPicTag
            FROM " . self::TableName_ProductPic . "
            WHERE ProductId=:ProductId AND State=0
            GROUP BY ProductPicTag"
                . $order
                . $topCount;
            $dataProperty = new DataProperty();
            $dataProperty->AddField("ProductId", $productId);
            $result = $this->dbOperator->GetArrayList($sql, $dataProperty);
        }
        return $result;
    }

}

?>