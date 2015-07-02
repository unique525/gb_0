<?php

/**
 * 后台管理 站点自定义页面 后台数据类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_Site
 * @author zhangchi
 */
class SiteContentManageData extends BaseManageData
{

    /**
     * 取得字段数据集
     * @param string $tableName 表名
     * @return array 字段数据集
     */
    public function GetFields($tableName = self::TableName_SiteContent)
    {
        return parent::GetFields(self::TableName_SiteContent);
    }

    /**
     * 新增站点
     * @param array $httpPostData $_POST数组
     * @param int $manageUserId 后台管理员id
     * @return int 新增的站点id
     */
    public function Create($httpPostData,$manageUserId)
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
                self::TableName_SiteContent,
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
     * 修改站点
     * @param array $httpPostData $_POST数组
     * @param int $siteId 站点id
     * @return int 返回影响的行数
     */
    public function Modify($httpPostData, $siteId)
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
                self::TableName_SiteContent,
                self::TableId_SiteContent,
                $siteId,
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
     * 修改状态
     * @param int $siteContentId 站点自定义页面id
     * @param int $state 状态
     * @return int 操作结果
     */
    public function ModifyState($siteContentId, $state)
    {
        $result = 0;
        if ($siteContentId > 0) {
            $dataProperty = new DataProperty();
            $sql = "UPDATE " . self::TableName_SiteContent . "

                    SET `State`=:State

                    WHERE " . self::TableId_SiteContent . "=:" . self::TableId_SiteContent . ";
                        ";
            $dataProperty->AddField(self::TableId_SiteContent, $siteContentId);
            $dataProperty->AddField("State", $state);
            $result = $this->dbOperator->Execute($sql, $dataProperty);
        }
        return $result;
    }


    /**
     * 修改发布时间和发布人,只有发布时间为空时才进行操作
     * @param int $siteContentId 自定义页面id
     * @param int $publishDate 发布时间
     * @param int $manageUserId 操作管理员id
     * @return int 操作结果
     */
    public function ModifyPublishDate($siteContentId, $publishDate, $manageUserId)
    {
        $result = 0;
        if ($siteContentId > 0) {
            $dataProperty = new DataProperty();
            $sql = "UPDATE " . self::TableName_SiteContent . "
                SET

                    PublishDate=:PublishDate,
                    PublishManageUserId=:PublishManageUserId

                WHERE
                        SiteContentId=:SiteContentId
                    AND PublishDate is NULL

                    ;";


            $dataProperty->AddField("SiteContentId", $siteContentId);
            $dataProperty->AddField("PublishDate", $publishDate);
            $dataProperty->AddField("PublishManageUserId", $manageUserId);
            $result = $this->dbOperator->Execute($sql, $dataProperty);
        }
        return $result;
    }

    /**
     * 返回一行数据
     * @param int $siteContentId 站点id
     * @return array|null 取得对应数组
     */
    public function GetOne($siteContentId){
        $result = null;
        if($siteContentId>0){
            $sql = "SELECT * FROM
                        " . self::TableName_SiteContent . "
                    WHERE SiteContentId=:SiteContentId
                    ;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("SiteContentId", $siteContentId);
            $result = $this->dbOperator->GetArray($sql, $dataProperty);
        }
        return $result;
    }


    /**
     * 取得自定义页面的发布时间
     * @param int $siteContentId 自定义页面id
     * @param bool $withCache 是否从缓冲中取
     * @return string 自定义页面的发布时间
     */
    public function GetPublishDate($siteContentId, $withCache)
    {
        $result = "";
        if ($siteContentId > 0) {
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'site_content_data';
            $cacheFile = 'site_content_get_publish_date.cache_' . $siteContentId . '';
            $sql = "SELECT PublishDate FROM " . self::TableName_SiteContent . " WHERE SiteContentId=:SiteContentId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("SiteContentId", $siteContentId);
            $result = $this->GetInfoOfStringValue($sql, $dataProperty, $withCache, $cacheDir, $cacheFile);
        }
        return $result;
    }


    /**
     * 根据后台管理员id返回此管理员可以管理的列表数据集
     * @param int $siteId 站点id
     * @param int $pageBegin 分页起始位置
     * @param int $pageSize 分页大小
     * @param int $allCount 记录总数（输出参数）
     * @param string $searchKey 查询关键字
     * @param int $searchType 查询字段类型
     * @return array 列表数据集
     */
    public function GetList($siteId, $pageBegin, $pageSize, &$allCount, $searchKey, $searchType)
    {
        $dataProperty = new DataProperty();
        $searchSql = "";

        //查询
        if (strlen($searchKey) > 0 && $searchKey != "undefined") {
            if ($searchType == 0) { //站点名称
                $searchSql = " AND (s.SiteContentTitle like :SearchKey)";
                $dataProperty->AddField("SearchKey", "%" . $searchKey . "%");
            }
        }
        $sql = "SELECT s.*,mu.ManageUserName

                    FROM " . self::TableName_SiteContent . " s," . self::TableName_ManageUser . " mu

                        WHERE s.State<100 AND s.ManageUserId=mu.ManageUserId AND SiteId=:SiteId $searchSql
                        ORDER BY s.Sort DESC
                        LIMIT " . $pageBegin . "," . $pageSize . ";";
        $sqlCount = "
                    SELECT Count(*)

                    FROM " . self::TableName_SiteContent . " s," . self::TableName_ManageUser . " mu

                    WHERE s.State<100 AND s.ManageUserId=mu.ManageUserId AND SiteId=:SiteId $searchSql;";

        $dataProperty->AddField("SiteId", $siteId);

        $result = $this->dbOperator->GetArrayList($sql, $dataProperty);
        $allCount = $this->dbOperator->GetInt($sqlCount, $dataProperty);
        return $result;
    }

    /**
     * 取得所属频道id
     * @param int $siteContentId 站点自定义页面id
     * @param bool $withCache 是否从缓冲中取
     * @return string 所属频道id
     */
    public function GetChannelId($siteContentId, $withCache)
    {
        $result = "";
        if ($siteContentId > 0) {
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'site_content_data';
            $cacheFile = 'site_content_get_channel_id.cache_' . $siteContentId . '';
            $sql = "SELECT ChannelId FROM " . self::TableName_SiteContent . " WHERE SiteContentId=:SiteContentId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("SiteContentId", $siteContentId);
            $result = $this->GetInfoOfIntValue($sql, $dataProperty, $withCache, $cacheDir, $cacheFile);
        }
        return $result;
    }

    /**
     * 取得所属站点id
     * @param int $siteContentId 站点自定义页面id
     * @param bool $withCache 是否从缓冲中取
     * @return string 所属站点id
     */
    public function GetSiteId($siteContentId, $withCache)
    {
        $result = "";
        if ($siteContentId > 0) {
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'site_content_data';
            $cacheFile = 'site_content_get_site_id.cache_' . $siteContentId . '';
            $sql = "SELECT SiteId FROM " . self::TableName_SiteContent . " WHERE SiteContentId=:SiteContentId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("SiteContentId", $siteContentId);
            $result = $this->GetInfoOfIntValue($sql, $dataProperty, $withCache, $cacheDir, $cacheFile);
        }
        return $result;
    }

    /**
     * 取得状态
     * @param int $siteContentId 站点自定义页面id
     * @param bool $withCache 是否从缓冲中取
     * @return string 状态
     */
    public function GetState($siteContentId, $withCache)
    {
        $result = "";
        if ($siteContentId > 0) {
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'site_content_data';
            $cacheFile = 'site_content_get_state.cache_' . $siteContentId . '';
            $sql = "SELECT State FROM " . self::TableName_SiteContent . " WHERE SiteContentId=:SiteContentId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("SiteContentId", $siteContentId);
            $result = $this->GetInfoOfIntValue($sql, $dataProperty, $withCache, $cacheDir, $cacheFile);
        }
        return $result;
    }


    /**
     * 取得站点自定义页面内容
     * @param int $siteContentId 站点自定义页面id
     * @param bool $withCache 是否从缓冲中取
     * @return string 站点自定义页面内容
     */
    public function GetSiteContentValue($siteContentId, $withCache)
    {
        $result = "";
        if ($siteContentId > 0) {
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'site_content_data';
            $cacheFile = 'site_content_get_site_content_value.cache_' . $siteContentId . '';
            $sql = "SELECT SiteContentValue FROM " . self::TableName_SiteContent . " WHERE SiteContentId=:SiteContentId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("SiteContentId", $siteContentId);
            $result = $this->GetInfoOfStringValue($sql, $dataProperty, $withCache, $cacheDir, $cacheFile);
        }
        return $result;
    }
} 