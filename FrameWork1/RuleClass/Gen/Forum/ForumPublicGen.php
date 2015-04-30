<?php

/**
 * 前台 论坛 生成类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_Gen_Forum
 * @author zhangchi
 */
class ForumPublicGen extends ForumBasePublicGen implements IBasePublicGen {

    /**
     * 引导方法
     * @return string 返回执行结果
     */
    public function GenPublic() {

        $action = Control::GetRequest("a", "");
        switch ($action) {
            default:
                $result = self::GenDefault();
                break;
        }

        return $result;
    }

    /**
     * 生成论坛首页
     * @return string 论坛首页HTML
     */
    private function GenDefault() {
        $siteId = parent::GetSiteIdByDomain();
        $templateFileUrl = "forum/forum_default.html";
        $templateName = "default";
        $templatePath = "front_template";
        $tempContent = Template::Load($templateFileUrl, $templateName, $templatePath);

        parent::ReplaceFirstForForum($tempContent);


        /******************  顶部推荐栏  ********************** */
        $templateForumRecTopicFileUrl = "forum/forum_rec_1.html";
        $templateForumRecTopic = Template::Load($templateForumRecTopicFileUrl, $templateName, $templatePath);
        $tempContent = str_ireplace("{forum_rec_1}", $templateForumRecTopic, $tempContent);

        /******************  版块栏  ***********************/
        $templateForumBoardFileUrl = "forum/forum_default_list.html";
        $templateForumBoard = Template::Load($templateForumBoardFileUrl, $templateName, $templatePath);
        $templateForumBoard = str_ireplace("{SiteId}", $siteId, $templateForumBoard);

        $forumPublicData = new ForumPublicData();
        $forumRank = 0;
        $arrRankOneList = $forumPublicData->GetListByForumRank($siteId, $forumRank);

        $forumRank = 1;
        $arrRankTwoList = $forumPublicData->GetListByForumRank($siteId, $forumRank);
        //print_r($arrRankTwoList);

        $tagId = "forum_".$siteId;
        $tagName = Template::DEFAULT_TAG_NAME;
        $tableIdName = BaseData::TableId_Forum;
        $parentIdName = "ParentId";

        $arrRankThreeList = null;
        $thirdTableIdName = null;
        $thirdParentIdName = null;


        Template::ReplaceList(
            $templateForumBoard,
            $arrRankOneList,
            $tagId,
            $tagName,
            $arrRankTwoList,
            $tableIdName,
            $parentIdName,
            $arrRankThreeList,
            $thirdTableIdName,
            $thirdParentIdName
        );

        $tempContent = str_ireplace("{forum_list}", $templateForumBoard, $tempContent);
        parent::ReplaceEndForForum($tempContent);
        parent::ReplaceSiteConfig($siteId, $tempContent);
        return $tempContent;
    }

}

?>
