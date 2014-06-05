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
            case "remove_to_bin":
                $result = self::GenRemoveToBin();
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
        $tempContent = Template::Load("vote/vote_item_deal.html");
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
                    $closeTab = Control::PostRequest("CloseTab", 0);
                    if ($closeTab == 1) {
                        Control::CloseTab();
                    } else {
                        Control::GoUrl($_SERVER["PHP_SELF"]);
                    }
                } else {
                    Control::ShowMessage(Language::Load('vote', 2));
                }
            }
            $fieldsOfVoteItem = $voteItemManageData->GetFields();
            parent::ReplaceWhenCreate($tempContent, $fieldsOfVoteItem);
            $tempContent = str_ireplace("{Sort}", "0", $tempContent);
            $tempContent = str_ireplace("{VoteId}", strval($manageUserId), $tempContent);

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
    private function GenRemoveToBin() {
        /**
        $uId = Control::GetRequest("adminuserid", 0);
        $voteId = Control::GetRequest("voteid", 0);
        $voteItemId = Control::GetRequest("voteitemid", 0);

        if ($voteItemId > 0) {
            $voteItemData = new VoteItemManageData();
            $result = $voteItemData->RemoveBin($voteItemId);
            //加入操作log
            $operateContent = "AdminUser：RemoveBin id ：" . $uId . "；userid：" . Control::GetAdminUserID() . "；username；" . Control::GetAdminUserName() . "；result：" . $result;
            $adminuserLogData = new AdminUserLogData();
            $adminuserLogData->Insert($operateContent);

            if ($result > 0) {
                Control::ShowMessage(Language::Load('vote', 5));
                Control::GoUrl(ROOTPATH . '/vote/index.php?a=voteitemmanage&m=list&voteid=' . $voteId . '&height=&width=&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=true');
                return "";
            } else {
                Control::ShowMessage(Language::Load('vote', 6));
                Control::GoUrl(ROOTPATH . '/vote/index.php?a=voteitemmanage&m=edit&voteid=' . $voteId . '&height=&width=&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=true');
                return "";
            }
        } else {
            Control::ShowMessage(Language::Load('vote', 3));
            $jsCode = 'javascript:history.go(-1);';
            Control::RunJS($jsCode);
            return "";
        }**/
    }

    /**
     * 生成投票调查题目修改页面
     * @return mixed|string
     */
    private function GenModify() {
        $tempContent = Template::Load("vote/vote_item_deal.html");
        $voteItemId = Control::GetRequest("vote_item_id", 0);
        $pageIndex = Control::GetRequest("p", 0);
        parent::ReplaceFirst($tempContent);
        $voteItemManageData = new VoteItemManageData();
        if ($voteItemId > 0) {
            if (!empty($_POST)) {
                $httpPostData = $_POST;
                $result = $voteItemManageData->Modify($httpPostData, $voteItemId);

                //加入操作日志
                $operateContent = 'Modify VoteItem,POST FORM:' . implode('|', $_POST) . ';\r\nResult:voteId:' . $voteItemId;
                self::CreateManageUserLog($operateContent);

                if ($result > 0) {
                    //javascript 处理
                    $closeTab = Control::PostRequest("CloseTab", 0);
                    if ($closeTab == 1) {
                        Control::ShowMessage(Language::Load('vote', 3));
                        Control::CloseTab();
                    } else {
                        Control::GoUrl($_SERVER["PHP_SELF"]);
                    }
                } else {
                    Control::ShowMessage(Language::Load('vote', 4));
                }
            }
            $arrList = $voteItemManageData->GetOne($voteItemId);
            Template::ReplaceOne($tempContent, $arrList, 1);
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
        $tempContent = Template::Load("vote/vote_item_list.html");
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
            $arrList = $voteItemManageData->GetListForPager($pageBegin, $pageSize, $allCount, $voteId, $searchKey);

            if (count($arrList) > 0) {
                Template::ReplaceList($tempContent, $arrList, $tagId);
                /**
                $pagerTemplate = Template::Load("pager.html");
                $isJs = false;
                $jsFunctionName = "";
                $jsParamList = "";
                $pagerUrl = "/vote/index.php?a=voteitemmanage&m=list&voteid=" . $voteId . "&state=" . $state . "&searchkey=" . $searchKey . "&ps=" . $pageSize . "&p={0}";
                $pagerButton = Pager::ShowPageButton($pagerTemplate, $pagerUrl, $allCount, $pageSize, $pageIndex, $isJs, $jsFunctionName, $jsParamList);

                $replaceArr = array(
                    "{voteid}" => $voteId,
                    "{pageindex}" => $pageIndex,
                    "{pagerbutton}" => $pagerButton
                );
                $tempContent = strtr($tempContent, $replaceArr);
                parent::ReplaceEnd($tempContent);
                $result = $tempContent;
                **/
            } else {
                Template::RemoveCustomTag($tempContent, $tagId);
                $tempContent = str_ireplace("{pager_button}", Language::Load("vote", 101), $tempContent);
            }
        }
        parent::ReplaceEnd($tempContent);
        return $tempContent;
    }

}

?>
