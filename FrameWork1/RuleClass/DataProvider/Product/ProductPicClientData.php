<?php
/**
 * 客户端 产品图片 数据类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_Product
 * @author hy
 */
class ProductPicClientData extends BaseClientData {



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
        $sql = "
        SELECT ProductPicId,ProductId,UploadFileId,ProductPicTag,Sort,State,CreateDate,t1.*
        FROM " . self::TableName_ProductPic . " t
        LEFT OUTER JOIN " .self::TableName_UploadFile." t1 on t.UploadFileId=t1.UploadFileId
        WHERE t.ProductPicId=:ProductPicId;";
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
    public function GetList($productId, $order = "", $topCount = null)
    {
        $result = null;
        if ($topCount != null)
            $topCount = " limit " . $topCount;
        else $topCount = "";
        if ($productId > 0) {
            switch ($order) {
                default:
                    $order = " ORDER BY t.Sort DESC,t.ProductId ASC";
                    break;
            }
            $sql = "
            SELECT

                t.ProductPicId,
                t.ProductId,
                t.UploadFileId,
                t.ProductPicTag,
                t.Sort,
                t.State,
                t.CreateDate,
                t1.*

            FROM " . self::TableName_ProductPic . " t
            LEFT OUTER JOIN " .self::TableName_UploadFile." t1 on t.UploadFileId=t1.UploadFileId
            WHERE t.State<100 AND t.ProductId=:ProductId"
                . $order
                . $topCount;
            $dataProperty = new DataProperty();
            $dataProperty->AddField("ProductId", $productId);
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
        SELECT t.ProductPicId,t.ProductId,t.UploadFileId,t.ProductPicTag,t.Sort,t.State,t.CreateDate
        FROM " . self::TableName_ProductPic . " t
        LEFT OUTER JOIN " .self::TableName_UploadFile." t1 on t.UploadFileId=t1.UploadFileId
        WHERE t.State=0 AND ProductId=:ProductId" . $searchSql . "
        ORDER BY t.Sort DESC,t.ProductPicId DESC LIMIT " . $pageBegin . "," . $pageSize . ";";
        $result = $this->dbOperator->GetArrayList($sql, $dataProperty);
        $sql = "
        SELECT COUNT(*) FROM " . self::TableName_ProductPic . "
        WHERE t.State=0 AND ProductId=:ProductId" . $searchSql . ";";
        $allCount = $this->dbOperator->GetInt($sql, $dataProperty);
        return $result;
    }
} 