<?php
/**
 * 客户端 产品 数据类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_Product
 * @author zhangchi
 */
class ProductClientData extends BaseClientData {


    /**
     * 返回一行数据
     * @param int $productId 产品id
     * @param bool $withCache 是否使用缓存
     * @return array|null 取得对应数组
     */
    public function GetOne($productId, $withCache = FALSE){
        $result = null;
        if($productId>0){
            $sql = "
            SELECT p.*,
                        uf1.UploadFilePath AS TitlePic1UploadFilePath,
                        uf1.UploadFileMobilePath AS TitlePic1UploadFileMobilePath,
                        uf1.UploadFilePadPath AS TitlePic1UploadFilePadPath,
                        uf1.UploadFileThumbPath1 AS TitlePic1UploadFileThumbPath1,
                        uf1.UploadFileThumbPath2 AS TitlePic1UploadFileThumbPath2,
                        uf1.UploadFileThumbPath3 AS TitlePic1UploadFileThumbPath3,
                        uf1.UploadFileWatermarkPath1 AS TitlePic1UploadFileWatermarkPath1,
                        uf1.UploadFileWatermarkPath2 AS TitlePic1UploadFileWatermarkPath2,
                        uf1.UploadFileCompressPath1 AS TitlePic1UploadFileCompressPath1,
                        uf1.UploadFileCompressPath2 AS TitlePic1UploadFileCompressPath2,


                        uf2.UploadFilePath AS TitlePic2UploadFilePath,
                        uf2.UploadFileMobilePath AS TitlePic2UploadFileMobilePath,
                        uf2.UploadFilePadPath AS TitlePic2UploadFilePadPath,
                        uf2.UploadFileThumbPath1 AS TitlePic2UploadFileThumbPath1,
                        uf2.UploadFileThumbPath2 AS TitlePic2UploadFileThumbPath2,
                        uf2.UploadFileThumbPath3 AS TitlePic2UploadFileThumbPath3,
                        uf2.UploadFileWatermarkPath1 AS TitlePic2UploadFileWatermarkPath1,
                        uf2.UploadFileWatermarkPath2 AS TitlePic2UploadFileWatermarkPath2,
                        uf2.UploadFileCompressPath1 AS TitlePic2UploadFileCompressPath1,
                        uf2.UploadFileCompressPath2 AS TitlePic2UploadFileCompressPath2,


                        uf3.UploadFilePath AS TitlePic3UploadFilePath,
                        uf3.UploadFileMobilePath AS TitlePic3UploadFileMobilePath,
                        uf3.UploadFilePadPath AS TitlePic3UploadFilePadPath,
                        uf3.UploadFileThumbPath1 AS TitlePic3UploadFileThumbPath1,
                        uf3.UploadFileThumbPath2 AS TitlePic3UploadFileThumbPath2,
                        uf3.UploadFileThumbPath3 AS TitlePic3UploadFileThumbPath3,
                        uf3.UploadFileWatermarkPath1 AS TitlePic3UploadFileWatermarkPath1,
                        uf3.UploadFileWatermarkPath2 AS TitlePic3UploadFileWatermarkPath2,
                        uf3.UploadFileCompressPath1 AS TitlePic3UploadFileCompressPath1,
                        uf3.UploadFileCompressPath2 AS TitlePic3UploadFileCompressPath2,


                        uf4.UploadFilePath AS TitlePic4UploadFilePath,
                        uf4.UploadFileMobilePath AS TitlePic4UploadFileMobilePath,
                        uf4.UploadFilePadPath AS TitlePic4UploadFilePadPath,
                        uf4.UploadFileThumbPath1 AS TitlePic4UploadFileThumbPath1,
                        uf4.UploadFileThumbPath2 AS TitlePic4UploadFileThumbPath2,
                        uf4.UploadFileThumbPath3 AS TitlePic4UploadFileThumbPath3,
                        uf4.UploadFileWatermarkPath1 AS TitlePic4UploadFileWatermarkPath1,
                        uf4.UploadFileWatermarkPath2 AS TitlePic4UploadFileWatermarkPath2,
                        uf4.UploadFileCompressPath1 AS TitlePic4UploadFileCompressPath1,
                        uf4.UploadFileCompressPath2 AS TitlePic4UploadFileCompressPath2
            FROM
            " . self::TableName_Product . " p
            LEFT OUTER JOIN " .self::TableName_UploadFile." uf1 ON (p.TitlePic1UploadFileId=uf1.UploadFileId)
            LEFT OUTER JOIN " .self::TableName_UploadFile." uf2 ON (p.TitlePic2UploadFileId=uf2.UploadFileId)
            LEFT OUTER JOIN " .self::TableName_UploadFile." uf3 ON (p.TitlePic3UploadFileId=uf3.UploadFileId)
            LEFT OUTER JOIN " .self::TableName_UploadFile." uf4 ON (p.TitlePic4UploadFileId=uf4.UploadFileId)
            WHERE p.ProductId=:ProductId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("ProductId", $productId);
            $result = $this->dbOperator->GetArray($sql, $dataProperty);

        }
        return $result;
    }

    /**
     * 返回对应频道的产品列表
     * @param int $channelId 频道id
     * @param string $order 排序方式
     * @param int $pageBegin
     * @param int $pageSize
     * @return array|null 对应频道的产品列表
     */
    public function GetListOfChannelId(
        $channelId,
        $order,
        $pageBegin,
        $pageSize
    )
    {
        $result = null;


        if (!empty($channelId)) {
            $order = Format::FormatSql($order);
            switch ($order) {
                case "time_desc":
                    $order = " ORDER BY CreateDate DESC";
                    break;
                case "time_asc":
                    $order = " ORDER BY CreateDate ASC";
                    break;
                case "sale_desc":
                    $order = " ORDER BY SaleCount DESC";
                    break;
                case "sale_asc":
                    $order = " ORDER BY SaleCount ASC";
                    break;
                case "price_desc":
                    $order = " ORDER BY CONVERT(SalePrice,DECIMAL) DESC";
                    break;
                case "price_asc":
                    $order = " ORDER BY CONVERT(SalePrice,DECIMAL) ASC";
                    break;
                case "comment_asc":
                    $order = " ORDER BY Sort DESC,CreateDate DESC";
                    break;
                case "comment_desc":
                    $order = " ORDER BY Sort DESC,CreateDate DESC";
                    break;
                default:
                    $order = " ORDER BY Sort DESC,CreateDate DESC";
                    break;
            }
            $sql = "SELECT

                    t.*,

                    t1.UploadFilePath AS TitlePic1UploadFilePath,
                    t1.UploadFileMobilePath AS TitlePic1UploadFileMobilePath


                    FROM " . self::TableName_Product ." t
                    LEFT OUTER JOIN " .self::TableName_UploadFile." t1 on t.TitlePic1UploadFileId=t1.UploadFileId
                    WHERE t.ChannelId IN (".$channelId.") AND t.State<100"
                . $order
                . " LIMIT " . $pageBegin . "," . $pageSize . ";";
            $dataProperty = new DataProperty();
            $result = $this->dbOperator->GetArrayList($sql, $dataProperty);
        }
        return $result;
    }

    /**
     * 按搜索条件取得前台产品分页列表
     * @param int $channelId 频道Id,可以是 id,id,id 的形式
     * @param int $pageBegin 起始记录行
     * @param int $pageSize 页大小
     * @param int $allCount 记录总数
     * @param string $searchKey 查询字符
     * @param int $searchType 查询下拉框的类别
     * @param string $order 排序方式
     * @return array 产品列表数据集
     */
    public function GetListForSearch($channelId, $pageBegin, $pageSize, &$allCount, $searchKey = "", $searchType = 0, $order = "")
    {
        $result = null;
        if (!empty($channelId)) {
            switch ($order) {
                case "time_desc":
                    $order = " ORDER BY CreateDate DESC";
                    break;
                case "time_asc":
                    $order = " ORDER BY CreateDate ASC";
                    break;
                case "sale_desc":
                    $order = " ORDER BY SaleCount DESC";
                    break;
                case "sale_asc":
                    $order = " ORDER BY SaleCount ASC";
                    break;
                case "price_desc":
                    $order = " ORDER BY CONVERT(SalePrice,DECIMAL) DESC";
                    break;
                case "price_asc":
                    $order = " ORDER BY CONVERT(SalePrice,DECIMAL) ASC";
                    break;
                case "comment_asc":
                    $order = " ORDER BY Sort DESC,CreateDate DESC";
                    break;
                case "comment_desc":
                    $order = " ORDER BY Sort DESC,CreateDate DESC";
                    break;
                default:
                    $order = " ORDER BY Sort DESC,CreateDate DESC";
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
            t1.UploadFilePath AS TitlePic1UploadFilePath,
            t1.UploadFileMobilePath AS TitlePic1UploadFileMobilePath
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
     * 根据频道ID获取量贩产品记录
     * @param int $channelId 频道Id,可以是 id,id,id 的形式
     * @param string $order 排序方式
     * @param int $topCount 显示的条数
     * @return array|null  列表数组
     */
    public function GetDiscountListByChannelId($channelId, $order = "", $topCount = null)
    {
        $result = null;
        if ($topCount != null){
            $topCount = " LIMIT " . $topCount;
        }else{
            $topCount = "";
        }
        if (strlen($channelId) > 0) {
            $channelId = Format::FormatSql($channelId);
            switch ($order) {
                default:
                    $order = " ORDER BY p.Sort DESC,p.Createdate DESC";
                    break;
            }
            $sql = "SELECT

                        p.*,
                        uf1.UploadFilePath AS TitlePic1UploadFilePath,
                        uf1.UploadFileMobilePath AS TitlePic1UploadFileMobilePath,
                        uf1.UploadFilePadPath AS TitlePic1UploadFilePadPath,
                        uf1.UploadFileThumbPath1 AS TitlePic1UploadFileThumbPath1,
                        uf1.UploadFileThumbPath2 AS TitlePic1UploadFileThumbPath2,
                        uf1.UploadFileThumbPath3 AS TitlePic1UploadFileThumbPath3,
                        uf1.UploadFileWatermarkPath1 AS TitlePic1UploadFileWatermarkPath1,
                        uf1.UploadFileWatermarkPath2 AS TitlePic1UploadFileWatermarkPath2,
                        uf1.UploadFileCompressPath1 AS TitlePic1UploadFileCompressPath1,
                        uf1.UploadFileCompressPath2 AS TitlePic1UploadFileCompressPath2,


                        uf2.UploadFilePath AS TitlePic2UploadFilePath,
                        uf2.UploadFileMobilePath AS TitlePic2UploadFileMobilePath,
                        uf2.UploadFilePadPath AS TitlePic2UploadFilePadPath,
                        uf2.UploadFileThumbPath1 AS TitlePic2UploadFileThumbPath1,
                        uf2.UploadFileThumbPath2 AS TitlePic2UploadFileThumbPath2,
                        uf2.UploadFileThumbPath3 AS TitlePic2UploadFileThumbPath3,
                        uf2.UploadFileWatermarkPath1 AS TitlePic2UploadFileWatermarkPath1,
                        uf2.UploadFileWatermarkPath2 AS TitlePic2UploadFileWatermarkPath2,
                        uf2.UploadFileCompressPath1 AS TitlePic2UploadFileCompressPath1,
                        uf2.UploadFileCompressPath2 AS TitlePic2UploadFileCompressPath2,


                        uf3.UploadFilePath AS TitlePic3UploadFilePath,
                        uf3.UploadFileMobilePath AS TitlePic3UploadFileMobilePath,
                        uf3.UploadFilePadPath AS TitlePic3UploadFilePadPath,
                        uf3.UploadFileThumbPath1 AS TitlePic3UploadFileThumbPath1,
                        uf3.UploadFileThumbPath2 AS TitlePic3UploadFileThumbPath2,
                        uf3.UploadFileThumbPath3 AS TitlePic3UploadFileThumbPath3,
                        uf3.UploadFileWatermarkPath1 AS TitlePic3UploadFileWatermarkPath1,
                        uf3.UploadFileWatermarkPath2 AS TitlePic3UploadFileWatermarkPath2,
                        uf3.UploadFileCompressPath1 AS TitlePic3UploadFileCompressPath1,
                        uf3.UploadFileCompressPath2 AS TitlePic3UploadFileCompressPath2,


                        uf4.UploadFilePath AS TitlePic4UploadFilePath,
                        uf4.UploadFileMobilePath AS TitlePic4UploadFileMobilePath,
                        uf4.UploadFilePadPath AS TitlePic4UploadFilePadPath,
                        uf4.UploadFileThumbPath1 AS TitlePic4UploadFileThumbPath1,
                        uf4.UploadFileThumbPath2 AS TitlePic4UploadFileThumbPath2,
                        uf4.UploadFileThumbPath3 AS TitlePic4UploadFileThumbPath3,
                        uf4.UploadFileWatermarkPath1 AS TitlePic4UploadFileWatermarkPath1,
                        uf4.UploadFileWatermarkPath2 AS TitlePic4UploadFileWatermarkPath2,
                        uf4.UploadFileCompressPath1 AS TitlePic4UploadFileCompressPath1,
                        uf4.UploadFileCompressPath2 AS TitlePic4UploadFileCompressPath2


                    FROM " . self::TableName_Product ." p
                    LEFT OUTER JOIN " .self::TableName_UploadFile." uf1 on p.TitlePic1UploadFileId=uf1.UploadFileId
                    LEFT OUTER JOIN " .self::TableName_UploadFile." uf2 on p.TitlePic2UploadFileId=uf2.UploadFileId
                    LEFT OUTER JOIN " .self::TableName_UploadFile." uf3 on p.TitlePic3UploadFileId=uf3.UploadFileId
                    LEFT OUTER JOIN " .self::TableName_UploadFile." uf4 on p.TitlePic4UploadFileId=uf4.UploadFileId
                    WHERE p.ChannelId IN (".$channelId.") AND p.IsDiscount=1 AND p.State<100"
                    . $order
                    . $topCount;

            $dataProperty = new DataProperty();
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
            $topCount = " LIMIT " . $topCount;
        else $topCount = "";
        switch ($order) {
            default:
                $order = " ORDER BY p.Sort DESC,p.CreateDate DESC,p.RecLevel DESC";
                break;
        }
        $sql = "SELECT
                p.*,
                uf1.UploadFilePath AS TitlePic1UploadFilePath,
                uf1.UploadFileMobilePath AS TitlePic1UploadFileMobilePath,
                uf1.UploadFilePadPath AS TitlePic1UploadFilePadPath,
                uf1.UploadFileThumbPath1 AS TitlePic1UploadFileThumbPath1,
                uf1.UploadFileThumbPath2 AS TitlePic1UploadFileThumbPath2,
                uf1.UploadFileThumbPath3 AS TitlePic1UploadFileThumbPath3,
                uf1.UploadFileWatermarkPath1 AS TitlePic1UploadFileWatermarkPath1,
                uf1.UploadFileWatermarkPath2 AS TitlePic1UploadFileWatermarkPath2,
                uf1.UploadFileCompressPath1 AS TitlePic1UploadFileCompressPath1,
                uf1.UploadFileCompressPath2 AS TitlePic1UploadFileCompressPath2,


                uf2.UploadFilePath AS TitlePic2UploadFilePath,
                uf2.UploadFileMobilePath AS TitlePic2UploadFileMobilePath,
                uf2.UploadFilePadPath AS TitlePic1Up2oadFilePadPath,
                uf2.UploadFileThumbPath1 AS TitlePic2UploadFileThumbPath1,
                uf2.UploadFileThumbPath2 AS TitlePic2UploadFileThumbPath2,
                uf2.UploadFileThumbPath3 AS TitlePic2UploadFileThumbPath3,
                uf2.UploadFileWatermarkPath1 AS TitlePic2UploadFileWatermarkPath1,
                uf2.UploadFileWatermarkPath2 AS TitlePic2UploadFileWatermarkPath2,
                uf2.UploadFileCompressPath1 AS TitlePic2UploadFileCompressPath1,
                uf2.UploadFileCompressPath2 AS TitlePic2UploadFileCompressPath2,


                uf3.UploadFilePath AS TitlePic3UploadFilePath,
                uf3.UploadFileMobilePath AS TitlePic3UploadFileMobilePath,
                uf3.UploadFilePadPath AS TitlePic3UploadFilePadPath,
                uf3.UploadFileThumbPath1 AS TitlePic3UploadFileThumbPath1,
                uf3.UploadFileThumbPath2 AS TitlePic3UploadFileThumbPath2,
                uf3.UploadFileThumbPath3 AS TitlePic3UploadFileThumbPath3,
                uf3.UploadFileWatermarkPath1 AS TitlePic3UploadFileWatermarkPath1,
                uf3.UploadFileWatermarkPath2 AS TitlePic3UploadFileWatermarkPath2,
                uf3.UploadFileCompressPath1 AS TitlePic3UploadFileCompressPath1,
                uf3.UploadFileCompressPath2 AS TitlePic3UploadFileCompressPath2,


                uf4.UploadFilePath AS TitlePic4UploadFilePath,
                uf4.UploadFileMobilePath AS TitlePic4UploadFileMobilePath,
                uf4.UploadFilePadPath AS TitlePic4UploadFilePadPath,
                uf4.UploadFileThumbPath1 AS TitlePic4UploadFileThumbPath1,
                uf4.UploadFileThumbPath2 AS TitlePic4UploadFileThumbPath2,
                uf4.UploadFileThumbPath3 AS TitlePic4UploadFileThumbPath3,
                uf4.UploadFileWatermarkPath1 AS TitlePic4UploadFileWatermarkPath1,
                uf4.UploadFileWatermarkPath2 AS TitlePic4UploadFileWatermarkPath2,
                uf4.UploadFileCompressPath1 AS TitlePic4UploadFileCompressPath1,
                uf4.UploadFileCompressPath2 AS TitlePic4UploadFileCompressPath2


                FROM " . self::TableName_Product ." p
                LEFT OUTER JOIN " .self::TableName_UploadFile." uf1 on p.TitlePic1UploadFileId=uf1.UploadFileId
                LEFT OUTER JOIN " .self::TableName_UploadFile." uf2 on p.TitlePic2UploadFileId=uf2.UploadFileId
                LEFT OUTER JOIN " .self::TableName_UploadFile." uf3 on p.TitlePic3UploadFileId=uf3.UploadFileId
                LEFT OUTER JOIN " .self::TableName_UploadFile." uf4 on p.TitlePic4UploadFileId=uf4.UploadFileId
                WHERE p.RecLevel=:RecLevel AND p.SiteId=:SiteId AND p.State<100"
            . $order
            . $topCount;
        $dataProperty = new DataProperty();
        $dataProperty->AddField("RecLevel", $recLevel);
        $dataProperty->AddField("SiteId", $siteId);
        $result = $this->dbOperator->GetArrayList($sql, $dataProperty);
        return $result;
    }

    /**
     * 取得产品的发货费用
     * @param int $productId 产品id
     * @param bool $withCache 是否缓存
     * @return float 发货费用
     */
    public function GetSendPrice($productId, $withCache)
    {
        $result = 0;
        if ($productId > 0) {
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'product_data';
            $cacheFile = 'product_get_send_price.cache_' . $productId . '';
            $sql = "SELECT SendPrice FROM " . self::TableName_Product . " WHERE ProductId=:ProductId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("ProductId", $productId);
            $result = $this->GetInfoOfFloatValue($sql, $dataProperty, $withCache, $cacheDir, $cacheFile);
        }

        return $result;
    }
    /**
     * 取得产品的发货续重费用（暂定：商品超过1个时的费用）
     * @param int $productId 产品id
     * @param bool $withCache 是否缓存
     * @return float 发货续重费用（暂定：商品超过1个时的费用）
     */
    public function GetSendPriceAdd($productId, $withCache)
    {
        $result = 0;
        if ($productId > 0) {
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'product_data';
            $cacheFile = 'product_get_send_price_add.cache_' . $productId . '';
            $sql = "SELECT SendPriceAdd FROM " . self::TableName_Product . " WHERE ProductId=:ProductId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("ProductId", $productId);
            $result = $this->GetInfoOfFloatValue($sql, $dataProperty, $withCache, $cacheDir, $cacheFile);
        }

        return $result;
    }
} 