<?php

/**
 * 后台管理 产品 数据类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_Product
 * @author zhangchi
 */
class ProductManageData extends BaseManageData
{

    /**
     * 取得字段数据集
     * @param string $tableName 表名
     * @return array 字段数据集
     */
    public function GetFields($tableName = self::TableName_Product)
    {
        return parent::GetFields(self::TableName_Product);
    }

    /**
     * 新增产品
     * @param array $httpPostData $_POST数组
     * @param int $manageUserId 后台管理员id
     * @return int 新增的产品id
     */
    public function Create($httpPostData, $manageUserId)
    {
        $result = -1;
        $dataProperty = new DataProperty();
        $addFieldName = "";
        $addFieldValue = "";
        $preNumber = "";
        $addFieldNames = array("CreateDate","ManageUserId");
        $addFieldValues = array(date("Y-m-d H:i:s", time()),$manageUserId);
        if (!empty($httpPostData)) {
            $sql = parent::GetInsertSql(
                $httpPostData,
                self::TableName_Product,
                $dataProperty,
                $addFieldName,
                $addFieldValue,
                $preNumber,
                $addFieldNames,
                $addFieldValues
            );
            $result = $this->dbOperator->LastInsertId($sql, $dataProperty);
        }
        return $result;
    }

    /**
     * 修改产品
     * @param array $httpPostData $_POST数组
     * @param int $productId 产品id
     * @return int 返回影响的行数
     */
    public function Modify($httpPostData, $productId)
    {
        $result = -1;
        $dataProperty = new DataProperty();
        $addFieldName = "";
        $addFieldValue = "";
        $preNumber = "";
        $addFieldNames = array();
        $addFieldValues = array();
        if (!empty($httpPostData)) {
            $sql = parent::GetUpdateSql(
                $httpPostData,
                self::TableName_Product,
                self::TableId_Product,
                $productId,
                $dataProperty,
                $addFieldName,
                $addFieldValue,
                $preNumber,
                $addFieldNames,
                $addFieldValues
            );
            $result = $this->dbOperator->Execute($sql, $dataProperty);
        }
        return $result;
    }


    /**
     * 修改产品题图的上传文件id
     * @param int $productId 产品id
     * @param int $titlePic1UploadFileId 题图1上传文件id
     * @param int $titlePic2UploadFileId 题图2上传文件id
     * @param int $titlePic3UploadFileId 题图3上传文件id
     * @param int $titlePic4UploadFileId 题图3上传文件id
     * @return int 操作结果
     */
    public function ModifyTitlePic($productId, $titlePic1UploadFileId, $titlePic2UploadFileId, $titlePic3UploadFileId, $titlePic4UploadFileId)
    {
        $result = -1;
        if ($productId > 0) {
            $dataProperty = new DataProperty();
            $sql = "UPDATE " . self::TableName_Product . " SET
                    TitlePic1UploadFileId = :TitlePic1UploadFileId,
                    TitlePic2UploadFileId = :TitlePic2UploadFileId,
                    TitlePic3UploadFileId = :TitlePic3UploadFileId,
                    TitlePic4UploadFileId = :TitlePic4UploadFileId

                    WHERE ProductId = :ProductId

                    ;";
            $dataProperty->AddField("TitlePic1UploadFileId", $titlePic1UploadFileId);
            $dataProperty->AddField("TitlePic2UploadFileId", $titlePic2UploadFileId);
            $dataProperty->AddField("TitlePic3UploadFileId", $titlePic3UploadFileId);
            $dataProperty->AddField("TitlePic4UploadFileId", $titlePic4UploadFileId);
            $dataProperty->AddField("ProductId", $productId);
            $result = $this->dbOperator->Execute($sql, $dataProperty);
        }

        return $result;
    }


    /**
     * 修改题图1的上传文件id
     * @param int $productId 产品id
     * @param int $titlePic1UploadFileId 题图1上传文件id
     * @return int 操作结果
     */
    public function ModifyTitlePic1UploadFileId($productId, $titlePic1UploadFileId)
    {
        $result = -1;
        if ($productId > 0) {
            $dataProperty = new DataProperty();
            $sql = "UPDATE " . self::TableName_Product . " SET
                    TitlePic1UploadFileId = :TitlePic1UploadFileId

                    WHERE ProductId = :ProductId
                    ;";
            $dataProperty->AddField("TitlePic1UploadFileId", $titlePic1UploadFileId);
            $dataProperty->AddField("ProductId", $productId);
            $result = $this->dbOperator->Execute($sql, $dataProperty);
        }

        return $result;
    }


    /**
     * 修改题图2的上传文件id
     * @param int $productId 产品id
     * @param int $titlePic2UploadFileId 题图2上传文件id
     * @return int 操作结果
     */
    public function ModifyTitlePic2UploadFileId($productId, $titlePic2UploadFileId)
    {
        $result = -1;
        if ($productId > 0) {
            $dataProperty = new DataProperty();
            $sql = "UPDATE " . self::TableName_Product . " SET
                    TitlePic2UploadFileId = :TitlePic2UploadFileId

                    WHERE ProductId = :ProductId
                    ;";
            $dataProperty->AddField("TitlePic2UploadFileId", $titlePic2UploadFileId);
            $dataProperty->AddField("ProductId", $productId);
            $result = $this->dbOperator->Execute($sql, $dataProperty);
        }

        return $result;
    }


    /**
     * 修改题图3的上传文件id
     * @param int $productId 产品id
     * @param int $titlePic3UploadFileId 题图3上传文件id
     * @return int 操作结果
     */
    public function ModifyTitlePic3UploadFileId($productId, $titlePic3UploadFileId)
    {
        $result = -1;
        if ($productId > 0) {
            $dataProperty = new DataProperty();
            $sql = "UPDATE " . self::TableName_Product . " SET
                    TitlePic3UploadFileId = :TitlePic3UploadFileId

                    WHERE ProductId = :ProductId
                    ;";
            $dataProperty->AddField("TitlePic3UploadFileId", $titlePic3UploadFileId);
            $dataProperty->AddField("ProductId", $productId);
            $result = $this->dbOperator->Execute($sql, $dataProperty);
        }

        return $result;
    }


    /**
     * 修改题图4的上传文件id
     * @param int $productId 产品id
     * @param int $titlePic4UploadFileId 题图4上传文件id
     * @return int 操作结果
     */
    public function ModifyTitlePic4UploadFileId($productId, $titlePic4UploadFileId)
    {
        $result = -1;
        if ($productId > 0) {
            $dataProperty = new DataProperty();
            $sql = "UPDATE " . self::TableName_Product . " SET
                    TitlePic4UploadFileId = :TitlePic4UploadFileId

                    WHERE ProductId = :ProductId
                    ;";
            $dataProperty->AddField("TitlePic4UploadFileId", $titlePic4UploadFileId);
            $dataProperty->AddField("ProductId", $productId);
            $result = $this->dbOperator->Execute($sql, $dataProperty);
        }

        return $result;
    }


    /**
     * 修改排序
     * @param int $sort 大于0向上移动，否则向下移动
     * @param int $productId 产品id
     * @return int 返回结果
     */
    public function ModifySort($sort, $productId) {
        $result = 0;
        if ($productId > 0) {

            $channelId = $this->GetChannelId($productId, false);

            $currentSort = $this->GetSort($productId, false);

            if ($sort > 0) { //向上移动
                $dataProperty = new DataProperty();
                $sql = "SELECT
                            Sort
                        FROM " . self::TableName_Product . "

                        WHERE
                            ChannelId=:ChannelId
                        AND ProductId<>:ProductId
                        AND sort>=:CurrentSort

                        ORDER BY Sort DESC LIMIT 1;
                        ";
                $dataProperty->AddField("ChannelId", $channelId);
                $dataProperty->AddField("ProductId", $productId);
                $dataProperty->AddField("CurrentSort", $currentSort);
                $newSort = $this->dbOperator->GetInt($sql, $dataProperty);
            } else{//向下移动
                $dataProperty = new DataProperty();
                $sql = "SELECT
                            Sort
                        FROM " . self::TableName_Product . "

                        WHERE ChannelId=:ChannelId
                        AND ProductId<>:ProductId
                        AND Sort<=:CurrentSort

                        ORDER BY Sort LIMIT 1;
                        ";
                $dataProperty->AddField("ChannelId", $channelId);
                $dataProperty->AddField("ProductId", $productId);
                $dataProperty->AddField("CurrentSort", $currentSort);
                $newSort = $this->dbOperator->GetInt($sql, $dataProperty);
            }

            if ($newSort < 0) {
                $newSort = 0;
            }

            $newSort = $newSort + $sort;


            //2011.12.8 zc 排序号禁止负数
            if ($newSort < 0) {
                $newSort = 0;
            }

            $dataProperty = new DataProperty();
            $sql = "UPDATE " . self::TableName_Product . "
                    SET `Sort`=:NewSort
                    WHERE ProductId=:ProductId;";
            $dataProperty->AddField("NewSort", $newSort);
            $dataProperty->AddField("ProductId", $productId);
            $result = $this->dbOperator->Execute($sql, $dataProperty);
        }
        return $result;
    }


    /**
     * 拖动排序
     * @param array $arrProductId 待处理的数组
     * @return int 操作结果
     */
    public function ModifySortForDrag($arrProductId)
    {
        if (count($arrProductId) > 1) { //大于1条时排序才有意义
            $strId = join(',', $arrProductId);
            $strId = Format::FormatSql($strId);
            $sql = "SELECT max(Sort) FROM " . self::TableName_Product . " WHERE ProductId IN ($strId);";
            $maxSort = $this->dbOperator->GetInt($sql, null);
            $arrSql = array();
            for ($i = 0; $i < count($arrProductId); $i++) {
                $newSort = $maxSort - $i;
                if ($newSort < 0) {
                    $newSort = 0;
                }
                $newSort = intval($newSort);
                $productId = intval($arrProductId[$i]);
                $sql = "UPDATE " . self::TableName_Product . " SET Sort=$newSort WHERE ProductId=$productId;";
                $arrSql[] = $sql;
            }
            return $this->dbOperator->ExecuteBatch($arrSql, null);
        }else{
            return -1;
        }
    }

    /**
     * 新增文档时修改排序号到当前频道的最大排序
     * @param int $channelId 频道id
     * @param int $productId 产品id
     * @return int 影响的记录行数
     */
    public function ModifySortWhenCreate($channelId, $productId) {
        $result = -1;
        if($channelId >0 && $productId>0){
            $dataProperty = new DataProperty();
            $sql = "SELECT max(Sort) FROM " . self::TableName_Product . " WHERE ChannelId=:ChannelId;";
            $dataProperty->AddField("ChannelId", $channelId);
            $maxSort = $this->dbOperator->GetInt($sql, $dataProperty);
            $newSort = $maxSort + 1;
            $dataProperty = new DataProperty();
            $dataProperty->AddField("Sort", $newSort);
            $dataProperty->AddField("ProductId", $productId);
            $sql = "UPDATE " . self::TableName_Product . " SET Sort=:Sort WHERE ProductId=:ProductId;";
            $result = $this->dbOperator->Execute($sql, $dataProperty);
        }
        return $result;
    }


    /**
     * 取得所属频道id
     * @param int $productId 产品id
     * @param bool $withCache 是否从缓冲中取
     * @return int 所属频道id
     */
    public function GetChannelId($productId, $withCache)
    {
        $result = -1;
        if ($productId > 0) {
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'product_data';
            $cacheFile = 'product_get_channel_id.cache_' . $productId . '';
            $sql = "SELECT ChannelId FROM " . self::TableName_Product . " WHERE ProductId=:ProductId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("ProductId", $productId);
            $result = $this->GetInfoOfIntValue($sql, $dataProperty, $withCache, $cacheDir, $cacheFile);
        }
        return $result;
    }

    /**
     * 取得排序号
     * @param int $productId 产品id
     * @param bool $withCache 是否从缓冲中取
     * @return int 排序号
     */
    public function GetSort($productId, $withCache)
    {
        $result = -1;
        if ($productId > 0) {
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'product_data';
            $cacheFile = 'product_get_sort.cache_' . $productId . '';
            $sql = "SELECT Sort FROM " . self::TableName_Product . " WHERE ProductId=:ProductId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("ProductId", $productId);
            $result = $this->GetInfoOfIntValue($sql, $dataProperty, $withCache, $cacheDir, $cacheFile);
        }
        return $result;
    }

    /**
     * 取得题图1的上传文件id
     * @param int $productId 产品id
     * @param bool $withCache 是否从缓冲中取
     * @return int 题图1的上传文件id
     */
    public function GetTitlePic1UploadFileId($productId, $withCache)
    {
        $result = -1;
        if ($productId > 0) {
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'product_data';
            $cacheFile = 'product_get_title_pic_1_upload_file_id.cache_' . $productId . '';
            $sql = "SELECT TitlePic1UploadFileId FROM " . self::TableName_Product . " WHERE ProductId = :ProductId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("ProductId", $productId);
            $result = $this->GetInfoOfIntValue($sql, $dataProperty, $withCache, $cacheDir, $cacheFile);
        }
        return $result;
    }


    /**
     * 取得题图2的上传文件id
     * @param int $productId 产品id
     * @param bool $withCache 是否从缓冲中取
     * @return int 题图2的上传文件id
     */
    public function GetTitlePic2UploadFileId($productId, $withCache)
    {
        $result = -1;
        if ($productId > 0) {
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'product_data';
            $cacheFile = 'product_get_title_pic_2_upload_file_id.cache_' . $productId . '';
            $sql = "SELECT TitlePic2UploadFileId FROM " . self::TableName_Product . " WHERE ProductId = :ProductId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("ProductId", $productId);
            $result = $this->GetInfoOfIntValue($sql, $dataProperty, $withCache, $cacheDir, $cacheFile);
        }
        return $result;
    }


    /**
     * 取得题图3的上传文件id
     * @param int $productId 产品id
     * @param bool $withCache 是否从缓冲中取
     * @return int 题图3的上传文件id
     */
    public function GetTitlePic3UploadFileId($productId, $withCache)
    {
        $result = -1;
        if ($productId > 0) {
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'product_data';
            $cacheFile = 'product_get_title_pic_3_upload_file_id.cache_' . $productId . '';
            $sql = "SELECT TitlePic3UploadFileId FROM " . self::TableName_Product . " WHERE ProductId = :ProductId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("ProductId", $productId);
            $result = $this->GetInfoOfIntValue($sql, $dataProperty, $withCache, $cacheDir, $cacheFile);
        }
        return $result;
    }


    /**
     * 取得题图4的上传文件id
     * @param int $productId 产品id
     * @param bool $withCache 是否从缓冲中取
     * @return int 题图4的上传文件id
     */
    public function GetTitlePic4UploadFileId($productId, $withCache)
    {
        $result = -1;
        if ($productId > 0) {
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'product_data';
            $cacheFile = 'product_get_title_pic_4_upload_file_id.cache_' . $productId . '';
            $sql = "SELECT TitlePic4UploadFileId FROM " . self::TableName_Product . " WHERE ProductId = :ProductId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("ProductId", $productId);
            $result = $this->GetInfoOfIntValue($sql, $dataProperty, $withCache, $cacheDir, $cacheFile);
        }
        return $result;
    }


    /**
     * 取得产品所属站点id
     * @param int $productId 产品id
     * @param bool $withCache 是否从缓冲中取
     * @return int 站点id
     */
    public function GetSiteId($productId, $withCache)
    {
        $result = -1;
        if ($productId > 0) {
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'product_data';
            $cacheFile = 'product_get_site_id.cache_' . $productId . '';
            $sql = "SELECT SiteId FROM " . self::TableName_Product . " WHERE ProductId=:ProductId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("ProductId", $productId);
            $result = $this->GetInfoOfIntValue($sql, $dataProperty, $withCache, $cacheDir, $cacheFile);
        }
        return $result;
    }


    /**
     * 返回一行数据
     * @param int $productId 产品id
     * @return array|null 取得对应数组
     */
    public function GetOne($productId)
    {
        $result = null;
        if ($productId > 0) {
            $sql = "SELECT t1.*,t2.ProductBrandName
            FROM " . self::TableName_Product . " t1 LEFT OUTER JOIN " . self::TableName_ProductBrand ." t2
            ON t1.ProductBrandId=t2.ProductBrandId
            WHERE ProductId=:ProductId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("ProductId", $productId);
            $result = $this->dbOperator->GetArray($sql, $dataProperty);
        }
        return $result;
    }


    /**
     * 取得后台产品列表数据集
     * @param int $channelId 频道id
     * @param int $pageBegin 起始记录行
     * @param int $pageSize 页大小
     * @param int $allCount 记录总数
     * @param string $searchKey 查询字符
     * @param int $searchType 查询下拉框的类别
     * @param int $isSelf 是否只显示当前登录的管理员录入的产品
     * @param int $manageUserId 查显示某个后台管理员id
     * @param string $sort 排序方式（默认降序）
     * @param string $saleCount 按HIT排序方式（默认不按hit排）
     * @return array 产品列表数据集
     */
    public function GetList(
        $channelId,
        $pageBegin,
        $pageSize,
        &$allCount,
        $searchKey = "",
        $searchType = 0,
        $isSelf = 0,
        $manageUserId = 0,
        $sort = "down",
        $saleCount = ""

    )
    {
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
        if ($isSelf === 1 && $manageUserId > 0) {
            $conditionManageUserId = ' AND ManageUserId=' . intval($manageUserId);
        } else {
            $conditionManageUserId = "";
        }


        if ($sort == "up") {
            $sortMethod = "ASC";
        } else {
            $sortMethod = "DESC";
        }

        if ($saleCount == "up") {
            $saleCountSortMethod = "SaleCount ASC,";
        } elseif ($saleCount == "down") {
            $saleCountSortMethod = "SaleCount DESC,";
        } else {
            $saleCountSortMethod = "";
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
            WHERE ChannelId=:ChannelId " . $searchSql . " " . $conditionManageUserId . "
            ORDER BY $saleCountSortMethod Sort $sortMethod, CreateDate DESC LIMIT " . $pageBegin . "," . $pageSize . ";";

        $result = $this->dbOperator->GetArrayList($sql, $dataProperty);

        $sql = "SELECT count(*) FROM " . self::TableName_Product . "
                WHERE ChannelId=:ChannelId AND State<100 " . $conditionManageUserId . " " . $searchSql . ";";
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
        if ($channelId > 0) {
            switch ($order) {
                default:
                    $order = " ORDER BY t.Sort DESC,t.Createdate DESC";
                    break;
            }
            $sql = "SELECT t.*,t1.*"
                . " FROM " . self::TableName_Product ." t"
                . " LEFT OUTER JOIN " .self::TableName_UploadFile." t1 on t.TitlePic1UploadFileId=t1.UploadFileId"
                . " WHERE t.ChannelId IN (".$channelId.") "
                . $order
                . $topCount;
            $dataProperty = new DataProperty();
            //$dataProperty->AddField("ChannelId", $channelId);
            $result = $this->dbOperator->GetArrayList($sql, $dataProperty);
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
                . " WHERE t.ChannelId IN (".$channelId.") AND IsDiscount=1 AND t.State<100"
                . $order
                . $topCount;
            $dataProperty = new DataProperty();
            //$dataProperty->AddField("ChannelId", $channelId);
            $result = $this->dbOperator->GetArrayList($sql, $dataProperty);
        }
        return $result;
    }

    /**
     * 移动产品到另一个频道
     * @param int $targetSiteId 目的站点id
     * @param int $targetChannelId 目的节点id
     * @param array $arrayOfProductList 要移动的产品数组
     * @param int $manageUserId 操作人id
     * @param string $manageUserName 操作人用户名
     * @return int
     */
    public function Move($targetSiteId, $targetChannelId, $arrayOfProductList,$manageUserId,$manageUserName) {
        $result=-1;
        if($targetChannelId>0&&count($arrayOfProductList)>0){
            $sort=self::GetMaxSortOfChannel($targetChannelId);  //排序号处理
            if($sort<0){
                $sort=0;
            }

            foreach($arrayOfProductList as $value){
                $sort++;
                $sql = "UPDATE " . self::TableName_Product . "
                 SET `SiteId`=:SiteId,`ChannelId`=:ChannelId,`ManageUserId`=:ManageUserId,`ManageUserName`=:ManageUserName,Sort=" . $sort . "
                 WHERE ProductId =:ProductId;";
                $dataProperty = new DataProperty();
                $dataProperty->AddField("SiteId", $targetSiteId);
                $dataProperty->AddField("ChannelId", $targetChannelId);
                $dataProperty->AddField("ManageUserId", $manageUserId);
                $dataProperty->AddField("ManageUserName", $manageUserName);
                $dataProperty->AddField("ProductId", $value["ProductId"]);
                $result = $this->dbOperator->Execute($sql, $dataProperty);
            }
        }
        return $result;
    }

    /**
     * 取得频道下最大的排序号 (用于批量移动产品)
     * @param int $channelId id
     * @return int 对应排序号
     */
    public function GetMaxSortOfChannel($channelId)
    {
        $result = 0;
        if ($channelId > 0) {
            $sql = "select max(sort) as MaxSort from " . self::TableName_Product . " where ChannelId=:ChannelId";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("ChannelId", $channelId);
            $result = $this->dbOperator->GetInt($sql, $dataProperty);
        }
        return $result;
    }

    /**
     * 由id字符串返回对应id产品的数据集
     * @param string $productIdString
     * @return array|null 取得对应数组
     */
    public function GetListByIDString($productIdString)
    {
        $result = null;
        if ($productIdString !="") {
            $sql = "SELECT * FROM
            " . self::TableName_Product . "
            WHERE ProductId IN ($productIdString)
            ;";
            $dataProperty = new DataProperty();
            $result = $this->dbOperator->GetArrayList($sql, $dataProperty);
        }
        return $result;
    }
} 