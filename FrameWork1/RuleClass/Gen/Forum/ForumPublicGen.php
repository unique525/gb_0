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



        $tagId = "forum_".$siteId;
        $tagName = Template::DEFAULT_TAG_NAME;
        $tableIdName = BaseData::TableId_Forum;
        $parentIdName = "ParentId";

        $arrRankThreeList = null;
        $thirdTableIdName = null;
        $thirdParentIdName = null;

        $childArrayFieldName = "LastPostInfo";
        $thirdArrayFieldName = "";

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
            $thirdParentIdName,
            $childArrayFieldName,
            $thirdArrayFieldName
        );

        $tempContent = str_ireplace("{forum_list}", $templateForumBoard, $tempContent);
        $tempContent = str_ireplace("{SiteId}", $siteId, $tempContent);

        parent::ReplaceTemplate($tempContent);

        parent::ReplaceEndForForum($tempContent);
        parent::ReplaceSiteConfig($siteId, $tempContent);

        /*******************过滤字符 begin********************** */
        $multiFilterContent = array();
        $multiFilterContent[0] = $tempContent;
        $useArea = 4; //过滤范围 4:评论
        $stop = FALSE; //是否停止执行
        $filterContent = null;
        $stopWord = parent::DoFilter($siteId, $useArea, $stop, $filterContent, $multiFilterContent);
        $tempContent = $multiFilterContent[0];
        /*******************过滤字符 end********************** */

        return $tempContent;
    }

}

?>
