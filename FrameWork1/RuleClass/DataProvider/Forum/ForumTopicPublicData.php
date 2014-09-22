<?php

/**
 * 前台 论坛主题 数据类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_Forum
 * @author zhangchi
 */
class ForumTopicPublicData extends BasePublicData {

    /**
     * 新增主题
     * @param int $siteId 站点id
     * @param int $forumId 论坛id
     * @param string $forumTopicTitle 主题标题
     * @param int $forumTopicTypeId 论坛主题类型，默认为0，无类型
     * @param string $forumTopicTypeName 论坛主题类型名称，默认为空（冗余字段）
     * @param int $forumTopicAudit 主题审核（授权）方式
     * @param int $forumTopicAccess 主题访问方式
     * @param string $postTime 创建时间
     * @param int $userId 会员id
     * @param string $userName 会员帐号（冗余字段）
     * @param int $forumTopicMood 心情图标
     * @param int $forumTopicAttach 附加图标
     * @param string $titleBold 标题加粗
     * @param string $titleColor 标题颜色
     * @param string $titleBgImage 标题背景图
     * @return int
     */
    public function Create(
        $siteId,
        $forumId,
        $forumTopicTitle,
        $forumTopicTypeId,
        $forumTopicTypeName,
        $forumTopicAudit,
        $forumTopicAccess,
        $postTime,
        $userId,
        $userName,
        $forumTopicMood,
        $forumTopicAttach,
        $titleBold,
        $titleColor,
        $titleBgImage
    )
    {
        $result = -1;
        if(
            $siteId>0
            && $forumId>0
            && strlen($forumTopicTitle)>0
            && $userId>0
            && strlen($userName)>0
        ){
            $sql = "INSERT INTO " . self::TableName_ForumTopic . "
                    (
                    ForumTopicTitle,
                    ForumTopicTypeId,
                    ForumTopicTypeName,
                    ForumTopicAudit,
                    ForumTopicAccess,
                    ForumId,
                    SiteId,
                    PostTime,
                    UserId,
                    UserName,
                    ForumTopicMood,
                    ForumTopicAttach,
                    TitleBold,
                    TitleColor,
                    TitleBgImage
                    )
                    VALUES
                    (
                    :ForumTopicTitle,
                    :ForumTopicTypeId,
                    :ForumTopicTypeName,
                    :ForumTopicAudit,
                    :ForumTopicAccess,
                    :ForumId,
                    :SiteId,
                    :PostTime,
                    :UserId,
                    :UserName,
                    :ForumTopicMood,
                    :ForumTopicAttach,
                    :TitleBold,
                    :TitleColor,
                    :TitleBgImage
                    );";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("ForumTopicTitle", $forumTopicTitle);
            $dataProperty->AddField("ForumTopicTypeId", $forumTopicTypeId);
            $dataProperty->AddField("ForumTopicTypeName", $forumTopicTypeName);
            $dataProperty->AddField("ForumTopicAudit", $forumTopicAudit);
            $dataProperty->AddField("ForumTopicAccess", $forumTopicAccess);
            $dataProperty->AddField("ForumId", $forumId);
            $dataProperty->AddField("SiteId", $siteId);
            $dataProperty->AddField("PostTime", $postTime);
            $dataProperty->AddField("UserId", $userId);
            $dataProperty->AddField("UserName", $userName);
            $dataProperty->AddField("ForumTopicMood", $forumTopicMood);
            $dataProperty->AddField("ForumTopicAttach", $forumTopicAttach);
            $dataProperty->AddField("TitleBold", $titleBold);
            $dataProperty->AddField("TitleColor", $titleColor);
            $dataProperty->AddField("TitleBgImage", $titleBgImage);
            $result = $this->dbOperator->LastInsertId($sql, $dataProperty);
        }
        return $result;
    }
} 