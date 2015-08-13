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
            case "async_modify_state":
                $result = self::AsyncModifyState();
                break;
        }
        $result = str_ireplace("{method}", $method, $result);
        return $result;
    }

    /**
     * 新增
     * @return string 模板内容页面
     */
    private function GenCreate()
    {

        $templateContent = "";
        $siteId = Control::GetRequest("site_id", 0);
        $channelId = Control::GetRequest("channel_id", 0);
        $rank = Control::GetRequest("rank", 0);
        $parentId = Control::GetRequest("parent_id", 0);

        if ($siteId > 0 && $channelId>0 && $rank >= 0) {
            $templateContent = Template::Load("exam/exam_question_class_deal.html", "common");
            $resultJavaScript = "";
            parent::ReplaceFirst($templateContent);
            $parentName = "无";
            $examQuestionClassManageData = new ExamQuestionClassManageData();
            if ($rank > 0) {

                if ($parentId > 0) {
                    $parentName = $examQuestionClassManageData->GetExamQuestionClassName($parentId, false);

                } else {
                    $parentId = 0;
                }

            }

            $templateContent = str_ireplace("{ParentName}", $parentName, $templateContent);
            $templateContent = str_ireplace("{SiteId}", $siteId, $templateContent);
            $templateContent = str_ireplace("{ChannelId}", $channelId, $templateContent);

            $templateContent = str_ireplace("{Rank}", $rank, $templateContent);
            $templateContent = str_ireplace("{ParentId}", $parentId, $templateContent);
            $templateContent = str_ireplace("{Sort}", "0", $templateContent);

            if (!empty($_POST)) {
                $httpPostData = $_POST;

                $examQuestionClassId = $examQuestionClassManageData->Create($httpPostData);

                //加入操作日志
                $operateContent = 'Create ExamQuestionClass,POST FORM:'
                    . implode('|', $_POST) . ';\r\nResult:' . $examQuestionClassId;
                self::CreateManageUserLog($operateContent);

                if ($examQuestionClassId > 0) {

                    //javascript 处理

                    $closeTab = Control::PostRequest("CloseTab", 0);
                    if ($closeTab == 1) {
                        //$resultJavaScript .= Control::GetCloseTab();
                        Control::GoUrl("/default.php?secu=manage&mod=exam_question_class&m=list&site_id=$siteId&channel_id=$channelId");
                    } else {
                        Control::GoUrl($_SERVER["PHP_SELF"]."?".$_SERVER['QUERY_STRING']);
                    }
                } else {
                    $resultJavaScript = Control::GetJqueryMessage(Language::Load('forum', 2));
                }
            }

            $fields = $examQuestionClassManageData->GetFields();
            parent::ReplaceWhenCreate($templateContent, $fields);

            $patterns = '/\{s_(.*?)\}/';
            $templateContent = preg_replace($patterns, "", $templateContent);
            parent::ReplaceEnd($templateContent);

            $templateContent = str_ireplace("{ResultJavaScript}", $resultJavaScript, $templateContent);
        }


        return $templateContent;
    }


    private function GenModify(){
        $templateContent = "";
        $examQuestionClassId = Control::GetRequest("exam_question_class_id", 0);
        $siteId = Control::GetRequest("site_id", 0);
        $channelId = Control::GetRequest("channel_id", 0);

        if ($siteId>0 && $channelId>0 && $examQuestionClassId >= 0) {
            $templateContent = Template::Load("exam/exam_question_class_deal.html", "common");
            $resultJavaScript = "";
            parent::ReplaceFirst($templateContent);


            $examQuestionClassManageData = new ExamQuestionClassManageData();


            if (!empty($_POST)) {
                $httpPostData = $_POST;

                $result = $examQuestionClassManageData->Modify($httpPostData, $examQuestionClassId);

                //加入操作日志
                $operateContent = 'Modify ExamQuestionClass,POST FORM:' .
                    implode('|', $_POST) . ';\r\nResult:' . $result;
                self::CreateManageUserLog($operateContent);

                if ($result > 0) {

                    //删除缓冲

                    parent::DelAllCache();

                    //javascript 处理

                    $closeTab = Control::PostRequest("CloseTab", 0);
                    if ($closeTab == 1) {
                        //$resultJavaScript .= Control::GetCloseTab();
                        Control::GoUrl("/default.php?secu=manage&mod=forum&m=list&site_id=$siteId&channel_id=$channelId");
                    } else {
                        Control::GoUrl($_SERVER["PHP_SELF"]."?".$_SERVER['QUERY_STRING']);
                    }
                } else {
                    $resultJavaScript = Control::GetJqueryMessage(Language::Load('forum', 4));
                }
            }



            $arrOne = $examQuestionClassManageData->GetOne($examQuestionClassId);
            Template::ReplaceOne($templateContent, $arrOne);
            $parentId = intval($arrOne["ParentId"]);
            if($parentId>0){
                $parentName = $examQuestionClassManageData->GetExamQuestionClassName($parentId, false);
                $templateContent = str_ireplace("{ParentName}", $parentName, $templateContent);
            }else{
                $templateContent = str_ireplace("{ParentName}", "无", $templateContent);
            }


            $templateContent = str_ireplace("{SiteId}", $siteId, $templateContent);
            $templateContent = str_ireplace("{ChannelId}", $channelId, $templateContent);
            $templateContent = str_ireplace("{ExamQuestionClassId}", $examQuestionClassId, $templateContent);
            $templateContent = str_ireplace("{Sort}", "0", $templateContent);

            $patterns = '/\{s_(.*?)\}/';
            $templateContent = preg_replace($patterns, "", $templateContent);
            parent::ReplaceEnd($templateContent);

            $templateContent = str_ireplace("{ResultJavaScript}", $resultJavaScript, $templateContent);

        }


        return $templateContent;
    }


    /**
     * 生成列表页面
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

        $templateContent = "";

        if($channelId>0){

            //load template
            $templateContent = Template::Load(
                "exam/exam_question_class_list.html",
                "common");

            parent::ReplaceFirst($templateContent);

            $examQuestionClassManageData = new ExamQuestionClassManageData();
            $rank = 0;
            $arrRankOneList = $examQuestionClassManageData->GetListByRank($channelId, $rank);
            $rank = 1;
            $arrRankTwoList = $examQuestionClassManageData->GetListByRank($channelId, $rank);
            $rank = 2;
            $arrRankThreeList = $examQuestionClassManageData->GetListByRank($channelId, $rank);
            $tagId = "exam_question_class_list";

            if(count($arrRankOneList)>0){

                Template::ReplaceList(
                    $templateContent,
                    $arrRankOneList,
                    $tagId,
                    Template::DEFAULT_TAG_NAME,
                    $arrRankTwoList,
                    "ExamQuestionClassId",
                    "ParentId",
                    $arrRankThreeList,
                    "ExamQuestionClassId",
                    "ParentId"
                );
                $templateContent = str_ireplace("{pager_button}", "", $templateContent);
            }else{
                Template::RemoveCustomTag($templateContent, $tagId);
                $templateContent = str_ireplace("{pager_button}", Language::Load("document", 7), $templateContent);

            }

            $templateContent = str_ireplace("{SiteId}", $siteId, $templateContent);
            $templateContent = str_ireplace("{ChannelId}", $channelId, $templateContent);


            parent::ReplaceEnd($templateContent);



        }

        return $templateContent;
    }

} 