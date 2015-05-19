<?php

/**
 * 后台管理 论坛帖子 数据类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_Forum
 * @author zhangchi
 */
class ForumPostManageData extends BaseManageData {

    /**
     * 当前发布的主题数量
     * @param int $forumId
     * @return int
     */
    public function GetNewCount($forumId){
        $dataProperty = new DataProperty();
        $dataProperty->AddField("ForumId", $forumId);
        $sql = "
            SELECT count(*)
            FROM
            " . self::TableName_ForumPost . "
            WHERE ForumId=:ForumId AND PostTime>=current_date()
            ;";
        $result = $this->dbOperator->GetInt($sql, $dataProperty);

        return $result;
    }

    /**
     * 当前发布的主题数量
     * @param int $forumId
     * @return int
     */
    public function GetPostCount($forumId){
        $dataProperty = new DataProperty();
        $dataProperty->AddField("ForumId", $forumId);
        $sql = "
            SELECT count(*)
            FROM " . self::TableName_ForumPost . "
            WHERE ForumId=:ForumId
                AND State<".ForumPostData::FORUM_POST_STATE_REMOVED."
                AND IsTopic = 0
            ;";
        $result = $this->dbOperator->GetInt($sql, $dataProperty);

        return $result;
    }
} 