<?php

/**
 * 后台管理 电子版 数据类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_Newspaper
 * @author zhangchi
 */
class NewspaperManageData extends BaseManageData
{
    /**
     * 取得字段数据集
     * @param string $tableName 表名
     * @return array 字段数据集
     */
    public function GetFields($tableName = self::TableName_Newspaper)
    {
        return parent::GetFields(self::TableName_Newspaper);
    }

    /**
     * 新增
     * @param array $httpPostData $_POST数组
     * @return int 新增的id
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
                self::TableName_Newspaper,
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
     * 修改
     * @param array $httpPostData $_POST数组
     * @param int $newspaperId id
     * @return int 返回影响的行数
     */
    public function Modify($httpPostData, $newspaperId)
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
                self::TableName_Newspaper,
                self::TableId_Newspaper,
                $newspaperId,
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
     * @param int $newspaperId id
     * @param int $state 状态
     * @return int 操作结果
     */
    public function ModifyState($newspaperId, $state)
    {
        $result = 0;
        if ($newspaperId > 0) {
            $dataProperty = new DataProperty();
            $sql = "UPDATE " . self::TableName_Newspaper . " SET `State`=:State WHERE " . self::TableId_Newspaper . "=:" . self::TableId_Newspaper . ";";
            $dataProperty->AddField(self::TableId_Newspaper, $newspaperId);
            $dataProperty->AddField("State", $state);
            $result = $this->dbOperator->Execute($sql, $dataProperty);
        }
        return $result;
    }

    /**
     * 根据后台管理员id返回此管理员可以管理的站点列表数据集
     * @param int $channelId 频道id
     * @param int $pageBegin 分页起始位置
     * @param int $pageSize 分页大小
     * @param int $allCount 记录总数（输出参数）
     * @param string $searchKey 查询关键字
     * @param int $searchType 查询字段类型
     * @return array 站点列表数据集
     */
    public function GetList($channelId, $pageBegin, $pageSize, &$allCount, $searchKey, $searchType)
    {
        $dataProperty = new DataProperty();
        $searchSql = "";

        //查询
        if (strlen($searchKey) > 0 && $searchKey != "undefined") {
            if ($searchType == 0) { //名称
                $searchSql = " AND (NewspaperTitle like :SearchKey)";
                $dataProperty->AddField("SearchKey", "%" . $searchKey . "%");
            } else { //名称
                $searchSql = " AND (NewspaperTitle like :SearchKey)";
                $dataProperty->AddField("SearchKey", "%" . $searchKey . "%");
            }
        }
        $sql = "SELECT * FROM " . self::TableName_Newspaper . "
                        WHERE
                            ChannelId = :ChannelId
                        ORDER BY PublishDate DESC
                        LIMIT " . $pageBegin . "," . $pageSize . ";";
        $sqlCount = "SELECT Count(*) FROM " . self::TableName_Newspaper . "
                        WHERE
                            ChannelId = :ChannelId
                        $searchSql;";


        $dataProperty->AddField("ChannelId", $channelId);

        $result = $this->dbOperator->GetArrayList($sql, $dataProperty);
        $allCount = $this->dbOperator->GetInt($sqlCount, $dataProperty);
        return $result;
    }

    /**
     * 取得状态
     * @param int $newspaperId id
     * @param bool $withCache 是否从缓冲中取
     * @return string 状态
     */
    public function GetState($newspaperId, $withCache)
    {
        $result = "";
        if ($newspaperId > 0) {
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'newspaper_data';
            $cacheFile = 'newspaper_get_state.cache_' . $newspaperId . '';
            $sql = "SELECT State FROM " . self::TableName_Newspaper . " WHERE NewspaperId=:NewspaperId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("NewspaperId", $newspaperId);
            $result = $this->GetInfoOfIntValue($sql, $dataProperty, $withCache, $cacheDir, $cacheFile);
        }
        return $result;
    }


    /**
     * 取得频道id
     * @param int $newspaperId id
     * @param bool $withCache 是否从缓冲中取
     * @return string 状态
     */
    public function GetChannelId($newspaperId, $withCache)
    {
        $result = "";
        if ($newspaperId > 0) {
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'newspaper_data';
            $cacheFile = 'newspaper_get_channel_id.cache_' . $newspaperId . '';
            $sql = "SELECT ChannelId FROM " . self::TableName_Newspaper . " WHERE NewspaperId=:NewspaperId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("NewspaperId", $newspaperId);
            $result = $this->GetInfoOfIntValue($sql, $dataProperty, $withCache, $cacheDir, $cacheFile);
        }
        return $result;
    }

    /**
     * 返回一行数据
     * @param int $newspaperId id
     * @return array|null 取得对应数组
     */
    public function GetOne($newspaperId)
    {
        $result = null;
        if ($newspaperId > 0) {
            $sql = "SELECT * FROM
                        " . self::TableName_Newspaper . "
                    WHERE NewspaperId=:NewspaperId
                    ;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("NewspaperId", $newspaperId);
            $result = $this->dbOperator->GetArray($sql, $dataProperty);
        }
        return $result;
    }

}