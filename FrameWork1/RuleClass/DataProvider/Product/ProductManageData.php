<?php
/**
 * 后台管理 产品 数据类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_Product
 * @author zhangchi
 */
class ProductManageData extends BaseManageData {

    /**
     * 取得字段数据集
     * @param string $tableName 表名
     * @return array 字段数据集
     */
    public function GetFields($tableName = self::TableName_Product){
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
     * 取得后台产品列表数据集
     * @param int $channelId 频道id
     * @param int $pageBegin 起始记录行
     * @param int $pageSize 页大小
     * @param int $allCount 记录总数
     * @param string $searchKey 查询字符
     * @param int $searchType 查询下拉框的类别
     * @param int $isSelf 是否只显示当前登录的管理员录入的产品
     * @param int $manageUserId 后台管理员id
     * @return array 产品列表数据集
     */
    public function GetList($channelId, $pageBegin, $pageSize, &$allCount, $searchKey = "", $searchType = 0, $isSelf = 0, $manageUserId = 0)
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
            ORDER BY Sort DESC, CreateDate DESC LIMIT " . $pageBegin . "," . $pageSize . ";";

        $result = $this->dbOperator->GetArrayList($sql, $dataProperty);

        $sql = "SELECT count(*) FROM " . self::TableName_Product . "
                WHERE ChannelId=:ChannelId AND State<100 " . $conditionManageUserId . " " . $searchSql . ";";
        $allCount = $this->dbOperator->GetInt($sql, $dataProperty);

        return $result;
    }
} 