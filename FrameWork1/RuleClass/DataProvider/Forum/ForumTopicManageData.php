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

} 