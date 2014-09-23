<?php

/**
 * 前台 论坛主题类型 数据类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_Forum
 * @author zhangchi
 */
class ForumTopicTypePublicData extends BasePublicData {

    /**
     * 返回某一站点下论坛主题类型列表
     * @param int $forumId 论坛id
     * @return array 论坛主题类型列表
     */
    public function GetList($forumId)
    {
        if ($forumId > 0) {
            $sql = "SELECT ForumTopicTypeId,ForumTopicTypeName
                        FROM " . self::TableName_ForumTopicType . "
                        WHERE ForumId=:ForumId
                        ORDER BY Sort DESC
                        ;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("ForumId", $forumId);
            $result = $this->dbOperator->GetArrayList($sql, $dataProperty);
            return $result;
        } else {
            return null;
        }
    }
} 