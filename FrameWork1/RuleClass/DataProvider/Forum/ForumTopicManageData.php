<?php

/**
 * 后台管理 论坛主题 数据类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_Forum
 * @author zhangchi
 */
class ForumTopicManageData extends BaseManageData {

    /**
     *
     * @param int $forumId
     * @param int $limit
     * @return array
     */
    public function GetList($forumId, $limit) {
        $dataProperty = new DataProperty();
        $dataProperty->AddField("ForumId", $forumId);
        $sql = "
            SELECT *
            FROM
            " . self::TableName_ForumTopic . "

            WHERE ForumId=:ForumId
            ORDER BY PostTime DESC LIMIT $limit
            ;";
        $result = $this->dbOperator->GetArrayList($sql, $dataProperty);

        return $result;
    }
    /**
     * 获取帖子分页列表
     * @param int $siteId 站点id
     * @param int $forumId 论坛id
     * @param int $pageBegin 起始页码
     * @param int $pageSize 每页大小
     * @param int $allCount 总大小
     * @param string $searchKey 搜索关键字
     * @return array 数据集
     */
    public function GetListPager($siteId, $forumId, $pageBegin, $pageSize, &$allCount, $searchKey) {
        $result=-1;
        if($siteId>0){
            $searchSql = "";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("SiteId", $siteId);
            $dataProperty->AddField("ForumId", $forumId);
            if (strlen($searchKey) > 0 && $searchKey != "undefined") {
                $searchSql.=" AND ForumTopicTitle LIKE :SearchKey ";
                $dataProperty->AddField("SearchKey", "%" . $searchKey . "%");
            }

            $sql = "
                SELECT
                *
                FROM
                " . self::TableName_ForumTopic . "
                WHERE SiteId=:SiteId " . $searchSql . " AND ForumId=:ForumId LIMIT " . $pageBegin . "," . $pageSize . " ;";
            $result = $this->dbOperator->GetArrayList($sql, $dataProperty);
            $sql = "
                SELECT
                COUNT(*)
                FROM
                " . self::TableName_ForumTopic . "
                WHERE SiteId=:SiteId " . $searchSql . " AND ForumId=:ForumId;";
            $allCount = $this->dbOperator->GetInt($sql, $dataProperty);
        }

        return $result;
    }
    /**
     * 当前发布的主题数量
     * @param int $forumId
     * @return int
     */
    public function GetTopicCount($forumId){
        $dataProperty = new DataProperty();
        $dataProperty->AddField("ForumId", $forumId);
        $sql = "
            SELECT count(*)
            FROM " . self::TableName_ForumTopic . "
            WHERE ForumId=:ForumId
                AND State<".ForumTopicData::FORUM_TOPIC_STATE_REMOVED."
            ;";
        $result = $this->dbOperator->GetInt($sql, $dataProperty);
        return $result;
    }

    /**
     * 取得一条信息
     * @param int $forumTopicId 论坛主题id
     * @return array 论坛主题信息数组
     */
    public function GetOne($forumTopicId)
    {
        $sql = "SELECT * FROM " . self::TableName_ForumTopic . " ft,". self::TableName_ForumPost . " fp WHERE ft.ForumTopicId=:" . self::TableId_ForumTopic . " AND fp.ForumTopicId=:" . self::TableId_ForumTopic . " AND fp.IsTopic=1 AND ft.State<".ForumTopicData::FORUM_TOPIC_STATE_REMOVED.";";
        $dataProperty = new DataProperty();
        $dataProperty->AddField(self::TableId_ForumTopic, $forumTopicId);
        $result = $this->dbOperator->GetArray($sql, $dataProperty);
        return $result;
    }

    /**
     * 修改主题
     * @param int $forumTopicId 论坛主题id
     * @param string $forumTopicTitle 论坛主题标题
     * @return int  执行结果
     */
    public function Modify($forumTopicId,$forumTopicTitle)
    {
        $result = -1;
        if(strlen($forumTopicTitle)>0){
            $dataProperty = new DataProperty();

            $sql = "UPDATE " . self::TableName_ForumTopic . " SET ForumTopicTitle=:ForumTopicTitle WHERE forumTopicId=:ForumTopicId";

            $dataProperty->AddField("ForumTopicId", $forumTopicId);
            $dataProperty->AddField("ForumTopicTitle", $forumTopicTitle);

            $result = $this->dbOperator->Execute($sql, $dataProperty);
        }
        return $result;
    }

    /**
     * 修改主题状态
     * @param int $forumTopicId 论坛主题id
     * @param string $state 论坛主题状态
     * @return int  执行结果
     */
    public function ModifyState($forumTopicId,$state)
    {
        $result = -1;
        if($forumTopicId>0){
            $dataProperty = new DataProperty();

            $sql = "UPDATE " . self::TableName_ForumTopic . " SET State=:State WHERE ForumTopicId=:ForumTopicId";

            $dataProperty->AddField("ForumTopicId", $forumTopicId);
            $dataProperty->AddField("State", $state);

            $result = $this->dbOperator->Execute($sql, $dataProperty);
            if($result > 0){
                $dataProperty = new DataProperty();

                $sql = "UPDATE " . self::TableName_ForumPost . " SET State=:State WHERE ForumTopicId=:ForumTopicId";

                $dataProperty->AddField("ForumTopicId", $forumTopicId);
                $dataProperty->AddField("State", $state);
                $result = $this->dbOperator->Execute($sql, $dataProperty);
            }
        }
        return $result;
    }

    /**
     * 将帖子移到别的板块
     * @param $siteId          int      站点id
     * @param $forumId         int      接受移动的板块id
     * @param $forumTopicIdArr array    被移动的帖子的topicId数组
     */
    public function MoveTopicToOtherBlock($siteId, $forumId, $forumTopicIdArr)
    {
        $result = -1;

        if($siteId > 0 && $forumId > 0 && count($forumTopicIdArr) > 0){
            $dataProperty = new DataProperty();
            $dataProperty->AddField('ForumId', $forumId);
            $sql = 'UPDATE ' .self::TableName_ForumTopic .
                    ' SET ForumId=:ForumId '.
                    ' WHERE ';

            foreach($forumTopicIdArr as $item){
                $sql .= ' (ForumTopicId=' .$item. ' AND SiteId=' .$siteId.') OR';
            }

            $sql = substr($sql, 0, strlen($sql)-3);
            $sql = $sql. ';';

            $result = $this->dbOperator->Execute($sql, $dataProperty);
            if($result == true){
                $result = 1;
            }
        }

        return $result;

    }
} 