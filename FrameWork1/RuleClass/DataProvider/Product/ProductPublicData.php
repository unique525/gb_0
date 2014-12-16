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
            $sql = "
            SELECT t.*,t1.*
            FROM
            " . self::TableName_Product . " t
            LEFT OUTER JOIN " .self::TableName_UploadFile." t1 on t.TitlePic1UploadFileId=t1.UploadFileId
            WHERE t.ProductId=:ProductId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("ProductId", $productId);
            $result = $this->dbOperator->GetArray($sql, $dataProperty);
        }
        return $result;
    }


    /**
     * 获取产品记录
     * @param int $channelId 频道Id,可以是 id,id,id 的形式
     * @param string $order 排序方式
     * @param int $topCount 显示的条数
     * @return array|null  列表数组
     */
    public function GetList($channelId, $order = "", $topCount = null)
    {
        $result = null;
        if ($topCount != null)
        {
            $topCount = " limit " . $topCount;
        }
        else {
            $topCount = "";
        }
        if (!empty($channelId)) {
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
            $sql = "SELECT t.*,t1.*"
                . " FROM " . self::TableName_Product ." t"
                . " LEFT OUTER JOIN " .self::TableName_UploadFile." t1 on t.TitlePic1UploadFileId=t1.UploadFileId"
                . " WHERE t.ChannelId IN (".$channelId.") AND t.State<100 AND SaleState<50"
                . $order
                . $topCount;
            $dataProperty = new DataProperty();
            $result = $this->dbOperator->GetArrayList($sql, $dataProperty);
        }
        return $result;
    }

    /**
     * 取得前台产品分页列表
     * @param int $channelId 频道Id,可以是 id,id,id 的形式
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
        if (!empty($channelId)) {
            switch ($order) {
                case "time_down":
                    $order = " ORDER BY t.Createdate DESC";
                    break;
                case "time_up":
                    $order = " ORDER BY t.Createdate ASC";
                    break;
                case "sale_down":
                    $order = " ORDER BY t.SaleCount DESC";
                    break;
                case "sale_up":
                    $order = " ORDER BY t.SaleCount ASC";
                    break;
                case "price_down":
                    $order = " ORDER BY t.SalePrice DESC";
                    break;
                case "price_up":
                    $order = " ORDER BY t.SalePrice ASC";
                    break;
                case "comment_down":
                    $order = " ORDER BY t.Sort DESC,t.Createdate DESC";
                    break;
                case "comment_up":
                    $order = " ORDER BY t.Sort ASC,t.Createdate ASC";
                    break;
                default:
                    $order = " ORDER BY t.Sort DESC,t.Createdate DESC";
                    break;
            }
            $searchSql = "";
            $dataProperty = new DataProperty();
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
            t.ProductId,t.ProductNumber,t.ProductName,t.SiteId,t.ChannelId,t.
            ProductShortName,t.ProductIntro,t.ProductTag,t.
            TitlePic1UploadFileId,t.TitlePic2UploadFileId,t.TitlePic3UploadFileId,t.TitlePic4UploadFileId,t.
            SalePrice,t.CreateDate,t.ManageUserId,t.ManageUserName,t.UserId,t.UserName,t.
            Sort,t.State,t.RecLevel,t.HitCount,t.RecCount,t.FavoriteCount,t.QuestionCount,t.IsHot,t.IsNew,t.
            SaleState,t.GetScore,t.SendPrice,t.SendPriceAdd,t.DirectUrl,t.MarketPrice,t.SaleCount,t.PublishDate,t.ProductCommentCount,
            t1.*
            FROM
            " . self::TableName_Product . " t
            LEFT OUTER JOIN " .self::TableName_UploadFile." t1 on t.TitlePic1UploadFileId=t1.UploadFileId
            WHERE  t.ChannelId IN (".$channelId.") AND t.State<100 AND SaleState<50 " . $searchSql . $order . " LIMIT ".  $pageBegin . "," . $pageSize . ";";

            $result = $this->dbOperator->GetArrayList($sql, $dataProperty);

            $sql = "SELECT count(*) FROM " . self::TableName_Product . "
                WHERE ChannelId IN (".$channelId.") AND State<100 AND SaleState<50 "  . " " . $searchSql . ";";
            $allCount = $this->dbOperator->GetInt($sql, $dataProperty);
        }

        return $result;
    }

    /**
     * 搜索取得前台产品分页列表
     * @param int $channelId 频道Id,可以是 id,id,id 的形式
     * @param int $pageBegin 起始记录行
     * @param int $pageSize 页大小
     * @param int $allCount 记录总数
     * @param string $searchKey 查询字符
     * @param int $searchType 查询下拉框的类别
     * @param string $order 排序方式
     * @return array 产品列表数据集
     */
    public function GetListForSearchPager($channelId, $pageBegin, $pageSize, &$allCount, $searchKey = "", $searchType = 0, $order = "")
    {
        $result = null;
            switch ($order) {
                case "time_down":
                    $order = " ORDER BY t.Createdate DESC";
                    break;
                case "time_up":
                    $order = " ORDER BY t.Createdate ASC";
                    break;
                case "sale_down":
                    $order = " ORDER BY t.SaleCount DESC";
                    break;
                case "sale_up":
                    $order = " ORDER BY t.SaleCount ASC";
                    break;
                case "price_down":
                    $order = " ORDER BY t.SalePrice DESC";
                    break;
                case "price_up":
                    $order = " ORDER BY t.SalePrice ASC";
                    break;
                case "comment_down":
                    $order = " ORDER BY t.Sort DESC,t.Createdate DESC";
                    break;
                case "comment_up":
                    $order = " ORDER BY t.Sort ASC,t.Createdate ASC";
                    break;
                default:
                    $order = " ORDER BY t.Sort DESC,t.Createdate DESC";
                    break;
            }
            $searchSql = "";
            $dataProperty = new DataProperty();
            if(!empty($channelId))
            {
                $searchSql .="t.ChannelId IN (".$channelId.")";
            }
            if (strlen($searchKey) > 0 && $searchKey != "undefined") {
                if ($searchType == 0) { //产品名称
                    $searchSql .= " AND (ProductName like :SearchKey)";
                    $dataProperty->AddField("SearchKey", "%" . $searchKey . "%");
                } else if ($searchType == 1) { //简介
                    $searchSql .= " AND (ProductIntro like :SearchKey)";
                    $dataProperty->AddField("SearchKey", "%" . $searchKey . "%");
                } else if ($searchType == 2) { //发布人
                    $searchSql .= " AND (ManageUserName like :SearchKey)";
                    $dataProperty->AddField("SearchKey", "%" . $searchKey . "%");
                } else if ($searchType == 3) { //标签
                    $searchSql .= " AND (ProductTag like :SearchKey)";
                    $dataProperty->AddField("SearchKey", "%" . $searchKey . "%");
                } else { //模糊
                    $searchSql .= " AND (ProductName LIKE :SearchKey1
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
            t.ProductId,t.ProductNumber,t.ProductName,t.SiteId,t.ChannelId,t.
            ProductShortName,t.ProductIntro,t.ProductTag,t.
            TitlePic1UploadFileId,t.TitlePic2UploadFileId,t.TitlePic3UploadFileId,t.TitlePic4UploadFileId,t.
            SalePrice,t.CreateDate,t.ManageUserId,t.ManageUserName,t.UserId,t.UserName,t.
            Sort,t.State,t.RecLevel,t.HitCount,t.RecCount,t.FavoriteCount,t.QuestionCount,t.IsHot,t.IsNew,t.
            SaleState,t.GetScore,t.SendPrice,t.SendPriceAdd,t.DirectUrl,t.MarketPrice,t.SaleCount,t.PublishDate,t.ProductCommentCount,
            t1.*
            FROM
            " . self::TableName_Product . " t
            LEFT OUTER JOIN " .self::TableName_UploadFile." t1 on t.TitlePic1UploadFileId=t1.UploadFileId
            WHERE t.State<100  AND t.SaleState<50" . $searchSql . $order . " LIMIT ".  $pageBegin . "," . $pageSize . ";";

            $result = $this->dbOperator->GetArrayList($sql, $dataProperty);

            $sql = "SELECT count(*) FROM " . self::TableName_Product . " t
                WHERE State<100  AND SaleState<50"  . " " . $searchSql . ";";
            $allCount = $this->dbOperator->GetInt($sql, $dataProperty);
        return $result;
    }



    /**
     * 根据频道ID获取产品记录
     * @param int $channelId 频道Id,可以是 id,id,id 的形式
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
        if (!empty($channelId)) {
            switch ($order) {
                default:
                    $order = " ORDER BY t.Sort DESC,t.Createdate DESC";
                    break;
            }
            $sql = "SELECT t.*,t1.*"
                . " FROM " . self::TableName_Product ." t"
                . " LEFT OUTER JOIN " .self::TableName_UploadFile." t1 on t.TitlePic1UploadFileId=t1.UploadFileId"
                . " WHERE t.ChannelId IN (".$channelId.") AND t.State<100 AND SaleState<50"
                . $order
                . $topCount;
            $dataProperty = new DataProperty();
            //$dataProperty->AddField("ChannelId", $channelId);
            $result = $this->dbOperator->GetArrayList($sql, $dataProperty);
        }
        return $result;
    }

    /**
     * 根据推荐级别获取产品记录
     * @param int $siteId 站点id
     * @param int $recLevel 推荐级别
     * @param string $order 排序方式
     * @param int $topCount 显示的条数
     * @return array|null  列表数组
     */
    public function GetListByRecLevel($siteId, $recLevel, $order = "", $topCount = null)
    {
        $result = null;
        if ($topCount != null)
            $topCount = " limit " . $topCount;
        else $topCount = "";
        switch ($order) {
            default:
                $order = " ORDER BY t.RecLevel DESC";
                break;
        }
        $sql = "SELECT *"
            . " FROM " . self::TableName_Product ." t"
            . " LEFT OUTER JOIN " .self::TableName_UploadFile." t1 on t.TitlePic1UploadFileId=t1.UploadFileId"
            . " WHERE t.RecLevel=:RecLevel AND t.SiteId=:SiteId AND t.State<100 AND SaleState<50"
            . $order
            . $topCount;
        $dataProperty = new DataProperty();
        $dataProperty->AddField("RecLevel", $recLevel);
        $dataProperty->AddField("SiteId", $siteId);
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
        if ($topCount != null){
            $topCount = " limit " . $topCount;
        }else {
            $topCount = "";
        }
        switch ($order) {
            default:
                $order = " ORDER BY t.SaleCount DESC";
                break;
        }
        $sql = "SELECT t.*,t1.*"
            . " FROM " . self::TableName_Product ." t"
            . " LEFT OUTER JOIN " .self::TableName_UploadFile." t1 on t.TitlePic1UploadFileId=t1.UploadFileId"
            . " WHERE t.State<100 AND SaleState<50"
            . $order
            . $topCount;
        $dataProperty = new DataProperty();
        $result = $this->dbOperator->GetArrayList($sql, $dataProperty);
        return $result;
    }

    /**
     * 根据频道ID获取量贩产品记录
     * @param int $channelId 频道Id,可以是 id,id,id 的形式
     * @param string $order 排序方式
     * @param int $topCount 显示的条数
     * @return array|null  列表数组
     */
    public function GetDiscountListByChannelId($channelId, $order = "", $topCount = null)
    {
        $result = null;
        if ($topCount != null)
            $topCount = " limit " . $topCount;
        else $topCount = "";
        if ($channelId > 0) {
            switch ($order) {
                default:
                    $order = " ORDER BY t.Sort DESC,t.Createdate DESC";
                    break;
            }
            $sql = "SELECT t.*,t1.*"
                . " FROM " . self::TableName_Product ." t"
                . " LEFT OUTER JOIN " .self::TableName_UploadFile." t1 on t.TitlePic1UploadFileId=t1.UploadFileId"
                . " WHERE t.ChannelId IN (".$channelId.") AND IsDiscount=1 AND t.State<100 AND SaleState<50"
                . $order
                . $topCount;
            $dataProperty = new DataProperty();
            //$dataProperty->AddField("ChannelId", $channelId);
            $result = $this->dbOperator->GetArrayList($sql, $dataProperty);
        }
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

    public function GetChannelIdByProductId($productId){
        $result = -1;
        if($productId > 0){
            $sql = "SELECT ChannelId FROM ".self::TableName_Product." WHERE ProductId = :ProductId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("ProductId",$productId);
            $result = $this->dbOperator->GetInt($sql,$dataProperty);
        }
        return $result;
    }

    /**
     * 判断商品是否超过下架时间，并对设置了自动下架的超时商品更新下架状态值为下架状态
     * @param int $productId 产品id
     * @return string 是否下架
     */
    public function ModifySaleStateIfOutAutoRemoveDate($productId)
    {
        $isOutDate = false; //默认为不下架
        if ($productId > 0) {
            $nowTime = date("Y-m-d H:i:s");
            $arrOne = self::GetOne($productId);
            $autoRemoveDate = $arrOne["ProductId"];
            $OpenAutoRemove = $arrOne["OpenAutoRemove"];
            $SaleState = $arrOne["SaleState"];
            if ($SaleState != "100") {
                if ($OpenAutoRemove == 1) {
                    if (!empty($autoRemoveDate) && (strtotime($nowTime) > strtotime($autoRemoveDate))) {
                        $dataProperty = new DataProperty();
                        $sql = "UPDATE " . self::TableName_Product . " SET
                        SaleState = :SaleState
                        WHERE ProductId = :ProductId
                        ;";
                        $dataProperty->AddField("SaleState", 100);
                        $dataProperty->AddField("ProductId", $productId);
                        $result = $this->dbOperator->Execute($sql, $dataProperty);
                        if ($result > 0) {
                            $isOutDate = true;
                        }
                    }
                }
            }
            else{
                $isOutDate = true;
            }
        }
        return $isOutDate;
    }

    /**
     * 取得产品的下架时间
     * @param int $productId 产品id
     * @return string 产品下架时间
     */
    public function GetAutoRemoveDate($productId)
    {
        $withCache=false;//不缓存
        $result = "";
        if ($productId > 0) {
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'product_data';
            $cacheFile = 'product_get_auto_remove_date.cache_' . $productId . '';
            $sql = "SELECT AutoRemoveDate FROM " . self::TableName_Product . " WHERE ProductId=:ProductId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("ProductId", $productId);
            $result = $this->GetInfoOfStringValue($sql, $dataProperty, $withCache, $cacheDir, $cacheFile);
        }

        return $result;
    }
}