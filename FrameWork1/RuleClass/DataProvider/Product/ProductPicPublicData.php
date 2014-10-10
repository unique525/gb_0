<?php

/**
 * 产品图片数据类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_ProductPic
 * @author hy
 */
class ProductPicPublicData extends BasePublicData
{

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
        SELECT ProductPicId,ProductId,UploadFileId,ProductPicTag,Sort,State,CreateDate
        FROM " . self::TableName_ProductPic . " t1
        LEFT OUTER JOIN " .self::TableName_UploadFile." t2 on t1.
        WHERE ProductPicId=:ProductPicId;";
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
                    $order = " ORDER BY Sort DESC,ProductId ASC";
                    break;
            }
            $sql = "
            SELECT ProductPicId,ProductId,UploadFileId,ProductPicTag,Sort,State,CreateDate
            FROM " . self::TableName_ProductPic . "
            WHERE State=0 AND ProductId=:ProductId"
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
            $searchSql .= " AND (ProductPicTag=:ProductPicTag)";
            $dataProperty->AddField("ProductPicTag", $tag);
        }
        if (strlen($searchKey) > 0 && $searchKey != "undefined") {
            $searchSql .= " AND (ProductPicIntro like :searchKey1)";
            $dataProperty->AddField("searchKey1", "%" . $searchKey . "%");
        }

        $sql = "
        SELECT ProductPicId,ProductId,UploadFileId,ProductPicTag,Sort,State,CreateDate
        FROM " . self::TableName_ProductPic . "
        WHERE ProductId=:ProductId" . $searchSql . "
        ORDER BY Sort DESC,ProductPicId DESC LIMIT " . $pageBegin . "," . $pageSize . ";";
        $result = $this->dbOperator->GetArrayList($sql, $dataProperty);
        $sql = "
        SELECT COUNT(*) FROM " . self::TableName_ProductPic . "
        WHERE ProductId=:ProductId" . $searchSql . ";";
        $allCount = $this->dbOperator->GetInt($sql, $dataProperty);
        return $result;
    }

}

?>