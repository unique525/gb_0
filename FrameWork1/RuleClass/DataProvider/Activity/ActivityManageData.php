<?php
/**
 * 后台管理 活动 数据类
 *
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_Activity
 * @author 525
 */
class ActivityManageData extends BaseManageData{

    /**
     * 新增活动
     * @param array $httpPostData $_post数组
     * @return int 新增活动id
     */
    public function Create($httpPostData) {
        $result=-1;
        if (!empty($httpPostData)) {
            print_r($httpPostData);
            $dataProperty = new DataProperty();
            $sql = parent::GetInsertSql($httpPostData,self::TableName_Activity, $dataProperty);
            $result = $this->dbOperator->LastInsertId($sql, $dataProperty);
            echo '<br>'.$sql;
            echo '<br>'.$result;die();
        }
        return $result;
    }
    /**
     * 修改
     * @param array $httpPostData $_post数组
     * @param string $activityId 活动id
     * @return int 执行结果
     */
    public function Modify($httpPostData,$activityId) {
        $dataProperty = new DataProperty();
        $sql = parent::GetUpdateSql($httpPostData, self::TableName_Activity, self::TableId_Activity, $activityId, $dataProperty);
        $dbOperator = DBOperator::getInstance();
        $result = $dbOperator->Execute($sql, $dataProperty);
        return $result;
    }
    /**
     * 获取活动分页列表
     * @param int $channelId 频道id
     * @param int $pageBegin 起始页码
     * @param int $pageSize 每页大小
     * @param int $allCount 总大小
     * @return array 活动数据集
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
                " . self::TableName_Activity . "
                WHERE ChannelId=:ChannelId " . $searchSql . " ORDER BY Sort DESC, CreateDate DESC LIMIT " . $pageBegin . "," . $pageSize . " ;";

            $result = $this->dbOperator->GetArrayList($sql, $dataProperty);
            $sqlCount = "SELECT count(*) FROM " . self::TableName_Activity . " WHERE ChannelId=:ChannelId AND state<100 " . $searchSql . " ;";
            $allCount = $this->dbOperator->GetInt($sqlCount, $dataProperty);
        }
        return $result;
    }

}
?>