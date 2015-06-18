<?php
/**
 * 后台管理 试题分类 生成类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_Gen_Exam
 * @author zhangchi
 */
class ExamQuestionClassManageGen extends BaseManageGen implements IBaseManageGen {
    /**
     * 引导方法
     * @return string 返回执行结果
     */
    public function Gen()
    {
        $result = "";
        $method = Control::GetRequest("m", "");
        switch ($method) {
            case "create":
                $result = self::GenCreate();
                break;
            case "modify":
                $result = self::GenModify();
                break;
            case "remove_to_bin":
                $result = self::GenRemoveToBin();
                break;
            case "list":
                $result = self::GenList();
                break;
            case "async_publish":
                $result = self::AsyncPublish();
                break;
            case "async_modify_sort":
                $result = self::AsyncModifySort();
                break;
            case "async_modify_sort_by_drag":
                $result = self::AsyncModifySortByDrag();
                break;
            case "async_modify_state":
                $result = self::AsyncModifyState();
                break;
            case "copy":
                $result = self::GenDeal($method);
                break;
            case "move":
                $result = self::GenDeal($method);
                break;
        }
        $result = str_ireplace("{method}", $method, $result);
        return $result;
    }

    /**
     * 生成资讯管理列表页面
     */
    private function GenList()
    {
        $channelId = Control::GetRequest("channel_id", 0);
        if ($channelId <= 0) {
            return null;
        }
        $manageUserId = Control::GetManageUserId();
        $channelManageData = new ChannelManageData();
        $siteId = $channelManageData->GetSiteId($channelId, false);
        if ($siteId <= 0) {
            return null;
        }

        ///////////////判断是否有操作权限///////////////////
        $manageUserAuthorityManageData = new ManageUserAuthorityManageData();
        $canExplore = $manageUserAuthorityManageData->CanChannelExplore($siteId, $channelId, $manageUserId);
        if (!$canExplore) {
            die(Language::Load('channel', 4));
        }

        //load template
        $templateContent = Template::Load(
            "exam/exam_question_list.html",
            "common");

        parent::ReplaceFirst($templateContent);

        $pageSize = Control::GetRequest("ps", 20);
        $pageIndex = Control::GetRequest("p", 1);
        $searchKey = Control::GetRequest("search_key", "");
        $searchType = Control::GetRequest("search_type", -1);
        $searchKey = urldecode($searchKey);

        if (isset($searchKey) && strlen($searchKey) > 0) {
            $canSearch = $manageUserAuthorityManageData->CanChannelSearch($siteId, $channelId, $manageUserId);
            if (!$canSearch) {
                die(Language::Load('channel', 4));
            }
        }

        if ($pageIndex > 0 && $channelId > 0) {

            $templateContent = str_ireplace("{ChannelId}", $channelId, $templateContent);
            $templateContent = str_ireplace("{SiteId}", $siteId, $templateContent);

            $pageBegin = ($pageIndex - 1) * $pageSize;
            $tagId = "exam_question_list";
            $allCount = 0;
            $isSelf = Control::GetRequest("is_self", 0);
            $examQuestionManageData = new ExamQuestionManageData();
            $arrList = $examQuestionManageData->GetList(
                $channelId,
                $pageBegin,
                $pageSize,
                $allCount,
                $searchKey,
                $searchType,
                $isSelf,
                $manageUserId
            );
            if (count($arrList) > 0) {
                Template::ReplaceList($templateContent, $arrList, $tagId);

                $styleNumber = 1;
                $pagerTemplate = Template::Load("pager/pager_style$styleNumber.html", "common");
                $isJs = FALSE;
                $navUrl = "default.php?secu=manage&mod=exam_question&m=list&channel_id=$channelId&p={0}&ps=$pageSize&isself=$isSelf";
                $jsFunctionName = "";
                $jsParamList = "";
                $pagerButton = Pager::ShowPageButton($pagerTemplate, $navUrl, $allCount, $pageSize, $pageIndex, $styleNumber, $isJs, $jsFunctionName, $jsParamList);

                $templateContent = str_ireplace("{pager_button}", $pagerButton, $templateContent);

            } else {
                Template::RemoveCustomTag($templateContent, $tagId);
                $templateContent = str_ireplace("{pager_button}", Language::Load("document", 7), $templateContent);
            }
        }

        parent::ReplaceEnd($templateContent);
        return $templateContent;
    }

} 