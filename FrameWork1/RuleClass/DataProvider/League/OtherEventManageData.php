<?php
/**
 * Created by PhpStorm.
 * User: a525
 * Date: 16-4-22
 * Time: 上午11:43
 */
class OtherEventManageData extends BaseManageData
{


    /**
     * 新增
     * @param array $httpPostData $_post数组
     * @param int $manageUserId
     * @return int 新增id
     */
    public function Create($httpPostData,$manageUserId) {
        $result=-1;
        $dataProperty = new DataProperty();
        $addFieldName = "ManageUserId";
        $addFieldValue = $manageUserId;
        $preNumber = "";
        $addFieldNames = array();
        $addFieldValues = array();
        if (!empty($httpPostData)) {
            $sql = parent::GetInsertSql($httpPostData,self::TableName_OtherEvent, $dataProperty, $addFieldName, $addFieldValue, $preNumber, $addFieldNames, $addFieldValues);
            $result = $this->dbOperator->LastInsertId($sql, $dataProperty);
        }
        return $result;
    }



    /**
     * 修改
     * @param array $httpPostData $_POST数组
     * @param int $otherEventId id
     * @param int $manageUserId
     * @return int 返回影响的行数
     */
    public function Modify($httpPostData, $otherEventId,$manageUserId)
    {
        $result = -1;
        $dataProperty = new DataProperty();
        $addFieldName = "ManageUserId";
        $addFieldValue = $manageUserId;
        $preNumber = "";
        $addFieldNames = array();
        $addFieldValues = array();

        if (!empty($httpPostData)) {
            $sql = parent::GetUpdateSql($httpPostData, self::TableName_OtherEvent, self::TableId_OtherEvent, $otherEventId, $dataProperty, $addFieldName, $addFieldValue, $preNumber, $addFieldNames, $addFieldValues);
            $result = $this->dbOperator->Execute($sql, $dataProperty);
        }
        return $result;
    }



    /**
     * 返回一行数据
     * @param int $otherEventId id
     * @return array|null 取得对应数组
     */
    public function GetOne($otherEventId)
    {
        $result = null;
        if ($otherEventId > 0) {
            $sql = "SELECT "." * FROM " . self::TableName_OtherEvent . " WHERE OtherEventId=:OtherEventId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("OtherEventId", $otherEventId);
            $result = $this->dbOperator->GetArray($sql, $dataProperty);
        }
        return $result;
    }

    /**
     * 取得比赛id
     * @param int $otherEventId 频道id
     * @param bool $withCache 是否从缓冲中取
     * @return int 站点id
     */
    public function GetMatchId($otherEventId, $withCache)
    {
        $result = -1;
        if ($otherEventId > 0) {
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'member_change';
            $cacheFile = 'member_change_get_match_id.cache_' . $otherEventId . '';
            $sql = "SELECT "." MatchId FROM " . self::TableName_OtherEvent . " WHERE OtherEventId=:OtherEventId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("OtherEventId", $otherEventId);
            $result = $this->GetInfoOfIntValue($sql, $dataProperty, $withCache, $cacheDir, $cacheFile);
        }
        return $result;
    }


    /**
     * 取得字段数据集
     * @param string $tableName 表名
     * @return array 字段数据集
     */
    public function GetFields($tableName = self::TableName_OtherEvent){
        return parent::GetFields(self::TableName_OtherEvent);
    }




}