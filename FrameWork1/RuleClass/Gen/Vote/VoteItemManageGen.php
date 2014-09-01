<?php

/**
 * 投票调查题目管理生成类
 * @category iCMS
 * @package iCMS_Rules_Gen_Vote
 * @author yanjiuyuan
 */
class VoteItemManageGen extends BaseManageGen implements IBaseManageGen {

    /**
     * 主生成方法(继承接口)
     * @return string 返回输出的HTML内容
     */
    function Gen() {
        $result = "";
        $method = Control::GetRequest("m", "");
        switch ($method) {
            case "create":
                $result = self::GenCreate();
                break;
            case "async_modify_state":
                $result = self::AsyncModifyState();
                break;
            case "modify":
                $result = self::GenModify();
                break;
            case "list":
                $result = self::GenList();
                break;
        }

        $result = str_ireplace("{method}", $method, $result);
        return $result;
    }

    /**
     * 生成投票调查管理题目新增页面
     * @return mixed|string
     */
    private function GenCreate()
    {
        $manageUserId = Control::GetManageUserId();
        $voteId = Control::GetRequest("vote_id", 0);
        $pageIndex = Control::GetRequest("p", 0);
        $tempContent = Template::Load("vote/vote_item_deal.html", "common");
        if ($manageUserId > 0) {
            parent::ReplaceFirst($tempContent);
            $voteItemManageData = new VoteItemManageData();
            if (!empty($_POST)) {
                $httpPostData = $_POST;
                $voteItemId = $voteItemManageData->Create($httpPostData);

                //加入操作日志
                $operateContent = 'Create VoteItem,POST FORM:' . implode('|', $_POST) . ';\r\nResult:voteItemId:' . $voteItemId;
                self::CreateManageUserLog($operateContent);

                if ($voteItemId > 0) {
                    //javascript 处理
                    Control::ShowMessage(Language::Load('vote', 1));
                    $jsCode = 'parent.location.href=parent.location.href';
                    Control::RunJavascript($jsCode);
                } else {
                    Control::ShowMessage(Language::Load('vote', 2));
                }
                return "";
            }
            $tempContent = str_ireplace("{PageIndex}", $pageIndex, $tempContent);
            $tempContent = str_ireplace("{VoteId}", strval($voteId), $tempContent);

            $fieldsOfVoteItem = $voteItemManageData->GetFields();
            parent::ReplaceWhenCreate($tempContent, $fieldsOfVoteItem);

            $patterns = '/\{s_(.*?)\}/';
            $tempContent = preg_replace($patterns, "", $tempContent);
            parent::ReplaceEnd($tempContent);
        }
        return $tempContent;
    }

    /**
     * 停用投票调查题目
     * @return mixed|string
     */
    private function AsyncModifyState() {
        //$result = -1;
        $voteItemId = Control::GetRequest("vote_item_id", 0);
        $state = Control::GetRequest("state",0);
        if ($voteItemId > 0) {
            $voteItemData = new VoteItemManageData();
            $result = $voteItemData->ModifyState($voteItemId,$state);
            //加入操作日志
            $operateContent = 'ModifyState VoteItem,Get FORM:' . implode('|', $_GET) . ';\r\nResult:voteItemId:' . $voteItemId;
            self::CreateManageUserLog($operateContent);
        } else {
            $result = -1;
        }
        return Control::GetRequest("jsonpcallback","") . '({"result":"'.$result.'"})';
    }

    /**
     * 生成投票调查题目修改页面
     * @return mixed|string
     */
    private function GenModify() {
        $tempContent = Template::Load("vote/vote_item_deal.html", "common");
        $voteItemId = Control::GetRequest("vote_item_id", 0);
        $pageIndex = Control::GetRequest("p", 0);
        parent::ReplaceFirst($tempContent);
        $voteItemManageData = new VoteItemManageData();
        if ($voteItemId > 0) {
            if (!empty($_POST)) {
                $httpPostData = $_POST;
                $result = $voteItemManageData->Modify($httpPostData, $voteItemId);

                //加入操作日志
                $operateContent = 'Modify VoteItem,POST FORM:' . implode('|', $_POST) . ';\r\nVoteItemId:' . $voteItemId;
                self::CreateManageUserLog($operateContent);

                if ($result > 0) {
                    //javascript 处理
                    Control::ShowMessage(Language::Load('vote', 3));
                    $jsCode = 'parent.location.href=parent.location.href';
                    Control::RunJavascript($jsCode);
                } else {
                    Control::ShowMessage(Language::Load('vote', 4));
                }
            }
            $arrList = $voteItemManageData->GetOne($voteItemId);
            Template::ReplaceOne($tempContent, $arrList, false, false);
            $tempContent = str_ireplace("{PageIndex}", strval($pageIndex), $tempContent);
        }
        //替换掉{s XXX}的内容
        $patterns = '/\{s_(.*?)\}/';
        $tempContent = preg_replace($patterns, "", $tempContent);
        parent::ReplaceEnd($tempContent);
        return $tempContent;
    }

    /**
     * 投票调查题目管理列表页面
     * @return mixed|string
     */
    private function GenList() {
        $templateContent = Template::Load("vote/vote_item_list.html", "common");
        $voteId = Control::GetRequest("vote_id", 0);
        $pageSize = Control::GetRequest("ps", 20);
        $searchKey = Control::GetRequest("search_key", "");
        $searchKey = urldecode($searchKey);
        $pageIndex = Control::GetRequest("p", 1);

        if ($pageIndex > 0) {
            $pageBegin = ($pageIndex - 1) * $pageSize;
            $tagId = "vote_item_list";
            $allCount = 0;
            $voteItemManageData = new VoteItemManageData();
            $arrList = $voteItemManageData->GetListForPager($voteId, $pageBegin, $pageSize, $allCount, $searchKey);
            if (count($arrList) > 0) {
                Template::ReplaceList($tempContent, $arrList, $tagId);
                $styleNumber = 1;
                $pagerTemplate = Template::Load("pager/pager_style".$styleNumber.".html","common");
                $isJs = FALSE;
                $navUrl = "/default.php?secu=manage&mod=vote_item&m=list&vote_id=$voteId&p={0}&ps=$pageSize";
                $jsFunctionName = "";
                $jsParamList = "";
                $pagerButton = Pager::ShowPageButton($pagerTemplate, $navUrl, $allCount, $pageSize, $pageIndex, $styleNumber, $isJs, $jsFunctionName, $jsParamList);
                Template::ReplaceList($templateContent,$arrList,$tagId);
                $templateContent = str_ireplace("{pager_button}", $pagerButton, $templateContent);
            } else {
                Template::RemoveCustomTag($templateContent, $tagId);
                $templateContent = str_ireplace("{pager_button}", Language::Load("vote", 101), $templateContent);
            }
        }
        parent::ReplaceEnd($templateContent);
        return $templateContent;
    }

}

?>
