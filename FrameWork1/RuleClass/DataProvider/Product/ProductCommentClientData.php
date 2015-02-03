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
     * @param $productId
     * @param string $order
     * @param null $topCount
     * @return array|null
     */
    public function GetListByProductId($productId, $order = "", $topCount = null)
    {
        $result = null;
        if ($topCount != null)
        {
            $topCount = " limit " . $topCount;
        }
        else {
            $topCount = "";
        }
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
            . $topCount;
            $dataProperty = new DataProperty();
            $dataProperty->AddField("ProductId", $productId);
            $result = $this->dbOperator->GetArrayList($sql, $dataProperty);
        }
        return $result;
    }

} 