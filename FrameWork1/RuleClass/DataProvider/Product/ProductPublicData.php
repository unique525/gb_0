<?php
/**
 * 后台管理 产品 数据类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_Product
 * @author zhangchi
 */
class ProductPublicData extends BasePublicData {




    /**
     * 返回一行数据
     * @param int $productId 产品id
     * @return array|null 取得对应数组
     */
    public function GetOne($productId){
        $result = null;
        if($productId>0){
            $sql = "SELECT * FROM " . self::TableName_Product . " WHERE ProductId=:ProductId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("ProductId", $productId);
            $result = $this->dbOperator->GetArray($sql, $dataProperty);
        }
        return $result;
    }


    /**
     * 获取产品记录
     * @param int $channelId 频道Id
     * @param string $order 排序方式
     * @param int $topCount 显示的条数
     * @return array|null  列表数组
     */
    public function GetList($channelId, $order = "", $topCount = null)
    {
        $result = null;
        if ($topCount != null)
            $topCount = " limit " . $topCount;
        else $topCount = "";
        if ($channelId > 0) {
            switch ($order) {
                case "time_desc":
                    $order = " ORDER BY Createdate DESC";
                    break;
                case "time_asc":
                    $order = " ORDER BY Createdate ASC";
                    break;
                case "sale_desc":
                    $order = " ORDER BY SaleCount DESC";
                    break;
                case "sale_asc":
                    $order = " ORDER BY SaleCount ASC";
                    break;
                case "price_desc":
                    $order = " ORDER BY SalePrice DESC";
                    break;
                case "price_asc":
                    $order = " ORDER BY SalePrice ASC";
                    break;
                case "comment_asc":
                    $order = " ORDER BY Sort DESC,Createdate DESC";
                    break;
                case "comment_desc":
                    $order = " ORDER BY Sort DESC,Createdate DESC";
                    break;
                default:
                    $order = " ORDER BY Sort DESC,Createdate DESC";
                    break;
            }
            $sql = "SELECT *"
                . " FROM " . self::TableName_Product
                . " WHERE ChannelId=:ChannelId AND State<100"
                . $order
                . $topCount;
            $dataProperty = new DataProperty();
            $dataProperty->AddField("ChannelId", $channelId);
            $result = $this->dbOperator->GetArrayList($sql, $dataProperty);
        }
        return $result;
    }

    /**
     * 取得前台产品分页列表
     * @param int $channelId 频道id
     * @param int $pageBegin 起始记录行
     * @param int $pageSize 页大小
     * @param int $allCount 记录总数
     * @param string $searchKey 查询字符
     * @param int $searchType 查询下拉框的类别
     * @param string $order 排序方式
     * @return array 产品列表数据集
     */
    public function GetListForPager($channelId, $pageBegin, $pageSize, &$allCount, $searchKey = "", $searchType = 0, $order = "")
    {
        $result = null;
        if ($channelId > 0) {
            switch ($order) {
                case "time_down":
                    $order = " ORDER BY Createdate DESC";
                    break;
                case "time_up":
                    $order = " ORDER BY Createdate ASC";
                    break;
                case "sale_down":
                    $order = " ORDER BY SaleCount DESC";
                    break;
                case "sale_up":
                    $order = " ORDER BY SaleCount ASC";
                    break;
                case "price_down":
                    $order = " ORDER BY SalePrice DESC";
                    break;
                case "price_up":
                    $order = " ORDER BY SalePrice ASC";
                    break;
                case "comment_down":
                    $order = " ORDER BY Sort DESC,Createdate DESC";
                    break;
                case "comment_up":
                    $order = " ORDER BY Sort ASC,Createdate ASC";
                    break;
                default:
                    $order = " ORDER BY Sort DESC,Createdate DESC";
                    break;
            }
            $searchSql = "";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("ChannelId", $channelId);
            if (strlen($searchKey) > 0 && $searchKey != "undefined") {
                if ($searchType == 0) { //产品名称
                    $searchSql = " AND (ProductName like :SearchKey)";
                    $dataProperty->AddField("SearchKey", "%" . $searchKey . "%");
                } else if ($searchType == 1) { //简介
                    $searchSql = " AND (ProductIntro like :SearchKey)";
                    $dataProperty->AddField("SearchKey", "%" . $searchKey . "%");
                } else if ($searchType == 2) { //发布人
                    $searchSql = " AND (ManageUserName like :SearchKey)";
                    $dataProperty->AddField("SearchKey", "%" . $searchKey . "%");
                } else if ($searchType == 3) { //标签
                    $searchSql = " AND (ProductTag like :SearchKey)";
                    $dataProperty->AddField("SearchKey", "%" . $searchKey . "%");
                } else { //模糊
                    $searchSql = " AND (ProductName LIKE :SearchKey1
                                    OR ManageUserName LIKE :SearchKey2
                                    OR ProductIntro LIKE :SearchKey3
                                    OR ProductTag LIKE :SearchKey4)";
                    $dataProperty->AddField("SearchKey1", "%" . $searchKey . "%");
                    $dataProperty->AddField("SearchKey2", "%" . $searchKey . "%");
                    $dataProperty->AddField("SearchKey3", "%" . $searchKey . "%");
                    $dataProperty->AddField("SearchKey4", "%" . $searchKey . "%");
                }
            }
            $sql = "
            SELECT
            ProductId,ProductNumber,ProductName,SiteId,ChannelId,
            ProductShortName,ProductIntro,ProductTag,
            TitlePic1UploadFileId,TitlePic2UploadFileId,TitlePic3UploadFileId,TitlePic4UploadFileId,
            SalePrice,CreateDate,ManageUserId,ManageUserName,UserId,UserName,
            Sort,State,RecLevel,HitCount,RecCount,FavoriteCount,QuestionCount,IsHot,IsNew,
            SaleState,GetScore,SendPrice,SendPriceAdd,DirectUrl,MarketPrice,SaleCount,PublishDate
            FROM
            " . self::TableName_Product . "
            WHERE ChannelId=:ChannelId AND State<100 " . $searchSql . $order . " LIMIT ".  $pageBegin . "," . $pageSize . ";";

            $result = $this->dbOperator->GetArrayList($sql, $dataProperty);

            $sql = "SELECT count(*) FROM " . self::TableName_Product . "
                WHERE ChannelId=:ChannelId AND State<100 "  . " " . $searchSql . ";";
            $allCount = $this->dbOperator->GetInt($sql, $dataProperty);
        }

        return $result;
    }

    /**
     * 根据频道ID获取产品记录
     * @param int $channelId 频道Id
     * @param string $order 排序方式
     * @param int $topCount 显示的条数
     * @return array|null  列表数组
     */
    public function GetListByChannelId($channelId, $order = "", $topCount = null)
    {
        $result = null;
        if ($topCount != null)
            $topCount = " limit " . $topCount;
        else $topCount = "";
        if ($channelId > 0) {
            switch ($order) {
                default:
                    $order = " ORDER BY Sort DESC,Createdate DESC";
                    break;
            }
            $sql = "SELECT *"
                . " FROM " . self::TableName_Product
                . " WHERE ChannelId=:ChannelId AND State<100"
                . $order
                . $topCount;
            $dataProperty = new DataProperty();
            $dataProperty->AddField("ChannelId", $channelId);
            $result = $this->dbOperator->GetArrayList($sql, $dataProperty);
        }
        return $result;
    }

    /**
     * 根据推荐级别获取产品记录
     * @param string $order 排序方式
     * @param int $topCount 显示的条数
     * @return array|null  列表数组
     */
    public function GetListByRecLevel($order = "", $topCount = null)
    {
        $result = null;
        if ($topCount != null)
            $topCount = " limit " . $topCount;
        else $topCount = "";
        switch ($order) {
            default:
                $order = " ORDER BY RecLevel DESC";
                break;
        }
        $sql = "SELECT *"
            . " FROM " . self::TableName_Product
            . " WHERE State<100"
            . $order
            . $topCount;
        $dataProperty = new DataProperty();
        $result = $this->dbOperator->GetArrayList($sql, $dataProperty);
        return $result;
    }

    /**
     * 根据销售数量获取产品记录
     * @param string $order 排序方式
     * @param int $topCount 显示的条数
     * @return array|null  列表数组
     */
    public function GetListBySaleCount($order = "", $topCount = null)
    {
        $result = null;
        if ($topCount != null)
            $topCount = " limit " . $topCount;
        else $topCount = "";
        switch ($order) {
            default:
                $order = " ORDER BY SaleCount DESC";
                break;
        }
        $sql = "SELECT *"
            . " FROM " . self::TableName_Product
            . " WHERE State<100"
            . $order
            . $topCount;
        $dataProperty = new DataProperty();
        $result = $this->dbOperator->GetArrayList($sql, $dataProperty);
        return $result;
    }

    public function GetOneForUserFavorite($productId){
        $result = null;
        if($productId > 0){
            $sql = "SELECT ProductName,ChannelId,TitlePic1UploadFileId FROM ".self::TableName_Product." WHERE ProductId = :ProductId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("ProductId",$productId);
            $result = $this->dbOperator->GetArray($sql,$dataProperty);
        }
        return $result;
    }
}