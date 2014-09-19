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
     * @return int 新增的产品id
     */
    public function Create($httpPostData)
    {
        $result = -1;
        $dataProperty = new DataProperty();
        $addFieldName = "";
        $addFieldValue = "";
        $preNumber = "";
        $addFieldNames = array();
        $addFieldValues = array();
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
            $sql = "SELECT * FROM " . self::TableName_Product . " WHERE ProductId=:ProductId;";
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
            WHERE ChannelId=:ChannelId AND State<100 " . $searchSql . " " . $conditionManageUserId . "
            ORDER BY $saleCountSortMethod Sort $sortMethod, CreateDate DESC LIMIT " . $pageBegin . "," . $pageSize . ";";

        $result = $this->dbOperator->GetArrayList($sql, $dataProperty);

        $sql = "SELECT count(*) FROM " . self::TableName_Product . "
                WHERE ChannelId=:ChannelId AND State<100 " . $conditionManageUserId . " " . $searchSql . ";";
        $allCount = $this->dbOperator->GetInt($sql, $dataProperty);

        return $result;
    }
} 