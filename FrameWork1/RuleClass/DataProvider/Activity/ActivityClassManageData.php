<?php
/**
 * 后台管理 活动分类(小) 数据类
 *
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_Activity
 * @author 525
 */
class ActivityClassManageData extends BaseManageData{

    /**
     * 新增活动类型(小)
     * @param array $httpPostData $_post数组
     * @return int 新增活动分类(小)id
     */
        public function Create($httpPostData) {
            $result=-1;
            if (!empty($httpPostData)) {
            //    $className = $httpPostData["f_ActivityClassName"];
            //    $channelId = $httpPostData["f_ChannelId"];
            //    $activityType = $httpPostData["f_ActivityType"];
            //    $count = self::GetCount($className, $channelId, $activityType);
            //    if ($count > 0) {
            //        return $result;            //在同一频道,同一分类下已存在相同的分类名称
            //    } else {
                    $dataProperty = new DataProperty();
                    $sql = parent::GetInsertSql($httpPostData, self::TableName_ActivityClass, $dataProperty);
                    $dbOperator = DBOperator::getInstance();
                    $result = $dbOperator->LastInsertId($sql, $dataProperty);
            //    }
            }
            return $result;
    }
    /**
     * 修改
     * @param array $httpPostData $_post数组
     * @param string $activityClassId 活动类型id
     * @return int 执行结果
     */
    public function Modify($httpPostData,$activityClassId) {
        $dataProperty = new DataProperty();
        $sql = parent::GetUpdateSql($httpPostData, self::TableName_ActivityClass, self::TableId_ActivityClass, $activityClassId, $dataProperty);
        $dbOperator = DBOperator::getInstance();
        $result = $dbOperator->Execute($sql, $dataProperty);
        return $result;
    }

    /**
     * 获取活动类型分页列表
     * @param int $channelId 频道id
     * @param int $pageBegin 起始页码
     * @param int $pageSize 每页大小
     * @param int $allCount 总大小
     * @return array 活动类型数据集
     */
    public function GetListPager($channelId, $pageBegin, $pageSize, &$allCount) {
        $result=-1;
        if($channelId>0){
            $searchSql = "";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("ChannelId", $channelId);

            $sql = "
                SELECT
                *
                FROM
                " . self::TableName_ActivityClass . "
                WHERE ChannelId=:ChannelId " . $searchSql . " ORDER BY Sort DESC, CreateDate DESC LIMIT " . $pageBegin . "," . $pageSize . " ;";

            $result = $this->dbOperator->GetArrayList($sql, $dataProperty);
            $sqlCount = "SELECT count(*) FROM " . self::TableName_ActivityClass . " WHERE ChannelId=:ChannelId AND state<100 " . $searchSql . " ;";
            $allCount = $this->dbOperator->GetInt($sqlCount, $dataProperty);
        }
        return $result;
    }
    /**
     * 获取节点下所有活动类型列表
     * @param int $channelId 频道id
     * @param int $activityType 活动type
     * @return array 活动类型数据集
     */
    public function GetList($channelId,$activityType) {
        $result=-1;
        if($channelId>0){
            $searchSql = "";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("ChannelId", $channelId);
            $dataProperty->AddField("ActivityType", $activityType);

            $sql = "
                SELECT
                *
                FROM
                " . self::TableName_ActivityClass . "
                WHERE ChannelId=:ChannelId AND ActivityType=:ActivityType " . $searchSql . " ;";
            $result = $this->dbOperator->GetArrayList($sql, $dataProperty);
        }
        return $result;
    }
    /**
     * 根据$className, $channelId, $activityType统计activityClass条数
     * @param string $activityClassName  活动分类(小)名称
     * @param int $channelId  所在节点id
     * @param int $activityType  活动大类id
     * @return int
     */
    public function GetCount($activityClassName, $channelId, $activityType = 0) {
        $sql = "SELECT count(" . self::TableId_ActivityClass . ") FROM " . self::TableName_ActivityClass . " WHERE ChannelId=:ChannelId AND ActivityType=:ActivityType AND ActivityClassName=:ActivityClassName";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("ChannelId", $channelId);
        $dataProperty->AddField("ActivityType", $activityType);
        $dataProperty->AddField("ActivityClassName", $activityClassName);
        $result = $this->dbOperator->GetInt($sql, $dataProperty);
        return $result;
    }


}