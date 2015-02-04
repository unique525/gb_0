<?php
/**
 * 客户端 产品评论 数据类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_Product
 * @author hy
 */
class ProductCommentClientData extends BaseClientData {


    /**
     * 根据产品id获取产品评论数据
     * @param int $productId 产品id
     * @param string $pageBegin 页码
     * @param string $pageSize 每页条数
     * @param string $allCount 总记录数
     * @param string $order 排序方式
     * @return array|null
     */
    public function GetListByProductId($productId, $pageBegin, $pageSize, &$allCount,$order = "")
    {
        $result = null;
        if (!empty($productId)) {
            switch ($order) {
                default:
                    $order = " ORDER BY Sort DESC,Createdate DESC";
                    break;
            }
            $sql = "
            SELECT ProductCommentId,ParentId,Rank,UserOrderProductId,ProductId,Subject,Content,
            UserId,UserName,CreateDate,Appraisal,ProductScore,SendScore,ServiceScore,
            SiteId,ChannelId,State,Sort
            FROM " . self::TableName_ProductComment . "
            WHERE ProductId=:ProductId"
            . $order
            . " LIMIT ".  $pageBegin . "," . $pageSize . "";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("ProductId", $productId);
            $result = $this->dbOperator->GetArrayList($sql, $dataProperty);

            $sqlCount = "SELECT count(*) FROM " . self::TableName_ProductComment . "
            WHERE ProductId=:ProductId";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("ProductId", $productId);
            $allCount = $this->dbOperator->GetInt($sqlCount, $dataProperty);
        }
        return $result;
    }

} 