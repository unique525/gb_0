<?php

/**
 * 前台论坛生成类 
 * @category iCMS
 * @package iCMS_Rules_Gen_Forum
 * @author zhangchi
 */
class ForumTopicGen extends BaseFrontGen implements IBaseFrontGen {

    /**
     * 引导方法
     * @return string 返回执行结果
     */
    public function GenFront() {
        $result = "";

        $action = Control::GetRequest("a", "");
        switch ($action) {
            case "list":
                $result = self::GenList();
                break;
            case "create":
                $result = self::GenCreate();
                break;
            default:
                $result = self::GenList();
                break;
        }

        return $result;
    }

    /**
     * 生成论坛主题列表页html
     * @return string 论坛主题列表页html
     */
    private function GenList() {
        $siteId = Control::GetRequest("siteid", 0);
        if ($siteId <= 0) {
            $siteId = parent::GetSiteIdBySubDomain();
        }
        $forumId = Control::GetRequest("forumid", 0);
        if ($forumId <= 0) {
            return;
        }

        $templateFileUrl = "forum/forum_topic_list.html";
        $templateName = "default";
        $templatePath = "front_template";
        $tempContent = Template::Load($templateFileUrl, $templateName, $templatePath);


        parent::ReplaceFirstForForum($tempContent);
        $siteConfigData = new SiteConfigData($siteId);
        $pageIndex = Control::GetRequest("p", 1);
        $pageSize = $siteConfigData->ForumTopicPageSize;
        if ($pageSize <= 0 || empty($pageSize)) {
            $pageSize = 25;
        }
        $pageBegin = ($pageIndex - 1) * $pageSize;
        $allCount = 0;
        $forumTopicData = new ForumTopicData();
        $arrForumTopicList = $forumTopicData->GetListPager($siteId, $forumId, $pageBegin, $pageSize, $allCount);
        Template::ReplaceList($tempContent, $arrForumTopicList, "forumTopicList");
        $styleNumber = 1;
        $pagerTemplate = Template::Load("../common/pager_style$styleNumber.html");
        $isJs = FALSE;

        $forumTopicListUrl = "default.php?mod=forumtopic"; //非静态化的地址

        $navUrl = "$forumTopicListUrl&forumid=$forumId&p={0}";
        $jsFunctionName = "";
        $jsParamList = "";
        $pagerButton = Pager::ShowPageButton($pagerTemplate, $navUrl, $allCount, $pageSize, $pageIndex, $styleNumber, $isJs, $jsFunctionName, $jsParamList);
        
        
        $tempContent = str_ireplace("{pagerbutton}", $pagerButton, $tempContent);
        $tempContent = str_ireplace("{forumid}", $forumId, $tempContent);



        parent::ReplaceEndForForum($tempContent);
        parent::ReplaceSiteConfig($siteId, $tempContent);
        return $tempContent;
    }

    private function GenCreate() {
        $siteId = Control::GetRequest("siteid", 0);
        if ($siteId <= 0) {
            $siteId = parent::GetSiteIdBySubDomain();
        }
        $forumId = Control::GetRequest("forumid", 0);
        if ($forumId <= 0) {
            return;
        }

        $templateFileUrl = "forum/forum_topic_create.html";
        $templateName = "default";
        $templatePath = "front_template";
        $tempContent = Template::Load($templateFileUrl, $templateName, $templatePath);

        parent::ReplaceFirstForForum($tempContent);
        parent::ReplaceEndForForum($tempContent);
        parent::ReplaceSiteConfig($siteId, $tempContent);
        return $tempContent;
    }

}

?>
