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
     * 取得一条信息
     * @param int $forumPostId 论坛回帖id
     * @return array 论坛回帖信息数组
     */
    public function GetOne($forumPostId)
    {
        $sql = "SELECT * FROM " . self::TableName_ForumPost ." WHERE ForumPostId=:ForumPostId;";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("ForumPostId", $forumPostId);
        $result = $this->dbOperator->GetArray($sql, $dataProperty);
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
    /**
     * 获取回帖分页列表
     * @param int $forumTopicId 主题ID
     * @param int $pageBegin 起始页码
     * @param int $pageSize 每页大小
     * @param int $allCount 总大小
     * @param string $searchKey 搜索关键字
     * @return array 数据集
     */
    public function GetListPager($forumTopicId, $pageBegin, $pageSize, &$allCount, $searchKey) {
        $result=-1;
        if($forumTopicId>0){
            $searchSql = "";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("ForumTopicId", $forumTopicId);
            if (strlen($searchKey) > 0 && $searchKey != "undefined") {
                $searchSql.=" AND ForumPostTitle LIKE :SearchKey ";
                $dataProperty->AddField("SearchKey", "%" . $searchKey . "%");
            }

            $sql = "
                SELECT
                *
                FROM
                " . self::TableName_ForumPost . "
                WHERE ForumTopicId=:ForumTopicId " . $searchSql . " AND IsTopic=0  LIMIT " . $pageBegin . "," . $pageSize . " ;";
            $result = $this->dbOperator->GetArrayList($sql, $dataProperty);

            $sql = "
                SELECT
                COUNT(*)
                FROM
                " . self::TableName_ForumPost . "
                WHERE ForumTopicId=:ForumTopicId " . $searchSql . " AND IsTopic=0;";
            $allCount = $this->dbOperator->GetInt($sql, $dataProperty);
        }

        return $result;
    }

    /**
     * 修改主题Content
     * @param int $forumTopicId
     * @param int $forumPostId
     * @param string $forumPostContent
     * @param string $forumPostTitle
     * @return int  执行结果
     */
    public function Modify($forumTopicId=0,$forumPostContent,$forumPostTitle="",$forumPostId=0){
        $result = -1;
        if($forumPostId=0){
            $dataProperty = new DataProperty();

            $sql = "UPDATE " . self::TableName_ForumPost . " SET ForumPostContent=:ForumPostContent  WHERE forumTopicId=:ForumTopicId AND IsTopic=1";

            $dataProperty->AddField("ForumTopicId", $forumTopicId);
            $dataProperty->AddField("ForumPostContent", $forumPostContent);

            $result = $this->dbOperator->Execute($sql, $dataProperty);
        }else{
            $dataProperty = new DataProperty();
            $sql = "UPDATE " . self::TableName_ForumPost . " SET ForumPostContent=:ForumPostContent,ForumPostTitle=:ForumPostTitle WHERE ForumPostId=:ForumPostId";

            $dataProperty->AddField("ForumPostId", $forumPostId);
            $dataProperty->AddField("ForumPostTitle", $forumPostTitle);
            $dataProperty->AddField("ForumPostContent", $forumPostContent);

            $result = $this->dbOperator->Execute($sql, $dataProperty);
        }
        return $result;
    }
} 