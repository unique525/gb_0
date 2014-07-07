<?php

/**
 * 投票调查题目选项生成类
 * @category iCMS
 * @package iCMS_Rules_Gen_Vote
 * @author yanjiuyuan
 */
class VoteSelectItemManageGen extends BaseManageGen implements IBaseManageGen
{

    /**
     * 主生成方法(继承接口)
     * @return string 返回输出的HTML内容
     */
    function Gen()
    {
        $result = "";
        $method = Control::GetRequest("m", "");
        switch ($method) {
            case "create":
                $result = self::GenCreate();
                break;
            case "modify_state":
                $result = self::AsyncModifyState();
                break;
            case "modify":
                $result = self::GenModify();
                break;
            case "list":
                $result = self::GenList();
                break;
            case "add_count_ratio":
                $result = self::GenAddCountWithRatio();
                break;
        }

        $result = str_ireplace("{method}", $method, $result);
        return $result;
    }

    /**
     * 生成投票调查管理题目选项新增页面
     * @return mixed|string
     */
    private function GenCreate()
    {
        $manageUserId = Control::GetManageUserId();
        $tempContent = Template::Load("vote/vote_select_item_deal.html", "common");
        $voteItemId = Control::GetRequest("vote_item_id", 0);
        $pageIndex = Control::GetRequest("p", 0);
        if ($manageUserId > 0) {
            parent::ReplaceFirst($tempContent);
            $voteSelectItemManageData = new VoteselectItemManageData();
            if (!empty($_POST)) {
                //title_pic
                $fileElementName = "title_pic_upload";
                $uploadTableType = UploadFileManageData::UPLOAD_TABLE_TYPE_VOTE_SELECT_ITEM;
                $titlePic1UploadFileId = 0;
                $titlePicPath = self::Upload($fileElementName, $uploadTableType, 1, $titlePic1UploadFileId);
                $titlePicPath = str_ireplace("..", "", $titlePicPath);
                $httpPostData = $_POST;
                $voteSelectItemId = $voteSelectItemManageData->Create($httpPostData, $titlePicPath);
                //加入操作日志
                $operateContent = 'Create VoteSelectItem,POST FORM:' . implode('|', $_POST) . ';\r\nResult:documentNewsId:' . $voteSelectItemId;
                self::CreateManageUserLog($operateContent);

                if ($voteSelectItemId > 0) {
                    //javascript 处理
                    Control::ShowMessage(Language::Load('vote', 1));
                    $jsCode = 'parent.location.href=parent.location.href';
                    Control::RunJavascript($jsCode);
                } else {
                    Control::ShowMessage(Language::Load('vote', 2));
                }
                return "";
            }

            $tempContent = str_ireplace("{voteItemId}", strval($voteItemId), $tempContent);
            $tempContent = str_ireplace("{pageIndex}", strval($pageIndex), $tempContent);

            $fieldsOfVoteSelectItem = $voteSelectItemManageData->GetFields();
            parent::ReplaceWhenCreate($tempContent, $fieldsOfVoteSelectItem);

            $patterns = '/\{s_(.*?)\}/';
            $tempContent = preg_replace($patterns, "", $tempContent);
            parent::ReplaceEnd($tempContent);
        }
        return $tempContent;
    }

    /**
     * 停用投票调查题目选项
     * @return mixed|string
     */
    private function AsyncModifyState(){
        //$result = -1;
        $voteSelectItemId = Control::GetRequest("vote_select_item_id", 0);
        $state = Control::GetRequest("state",0);
        if ($voteSelectItemId > 0) {
            $voteSelectItemData = new VoteSelectItemManageData();
            $result = $voteSelectItemData->ModifyState($voteSelectItemId,$state);
            //加入操作日志
            $operateContent = 'ModifyState VoteSelectItem,Get FORM:' . implode('|', $_GET) . ';\r\nResult:voteSelectItemId:' . $voteSelectItemId;
            self::CreateManageUserLog($operateContent);
        } else {
            $result = -1;
        }
        return $_GET['jsonpcallback'] . '({"result":"'.$result.'"})';
    }

    /**
     * 生成投票调查题目选项修改页面
     * @return mixed|string
     */
    private function GenModify()
    {
        $tempContent = Template::Load("vote/vote_select_item_deal.html", "common");
        $voteId = Control::GetRequest("vote_id", 0);
        $voteItemId = Control::GetRequest("vote_item_id", 0);
        $voteSelectItemId = Control::GetRequest("vote_select_item_id", 0);
        $pageIndex = Control::GetRequest("p", 0);
        parent::ReplaceFirst($tempContent);
        $voteSelectItemManageData = new VoteSelectItemManageData();
        if ($voteSelectItemId > 0) {
            if (!empty($_POST)) {
                //title_pic
                $fileElementName = "title_pic_upload";
                $uploadTableType = UploadFileManageData::UPLOAD_TABLE_TYPE_VOTE_SELECT_ITEM;
                $titlePic1UploadFileId = 0;
                $titlePicPath = self::Upload($fileElementName, $uploadTableType, 1, $titlePic1UploadFileId);
                $titlePicPath = str_ireplace("..", "", $titlePicPath);
                $httpPostData = $_POST;
                $result = $voteSelectItemManageData->Modify($httpPostData, $voteSelectItemId, $titlePicPath);
                //计算投票调查中题目选项的总加票数
                $voteSelectItemManageData = new VoteSelectItemManageData();
                $itemAddCount = $voteSelectItemManageData->GetSum($voteItemId);
                //修改投票调查中题目的总加票数
                $voteItemManageData = new VoteItemManageData();
                $result = $voteItemManageData->ModifyAddCount($voteItemId, $itemAddCount);
                //计算投票调查题目总加票数
                $addCount = $voteItemManageData->GetSum($voteId);
                //修改投票调查总加票数
                $voteManageData = new VoteManageData();
                $result = $voteManageData->ModifyAddCount($voteId, $addCount);

                //加入操作日志
                $operateContent = 'Modify VoteSelectItem,POST FORM:' . implode('|', $_POST) . ';\r\nResult:voteSelectItemId:' . $voteSelectItemId;
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
            $arrList = $voteSelectItemManageData->GetOne($voteSelectItemId);
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
     * 投票调查题目选项管理列表页面
     * @return mixed|string
     */
    private function GenList()
    {
        $templateContent = Template::Load("vote/vote_select_item_list.html", "common");
        $voteItemId = Control::GetRequest("vote_item_id", 0);
        $pageSize = Control::GetRequest("ps", 20);
        $searchKey = Control::GetRequest("search_key", "");
        $searchKey = urldecode($searchKey);
        $pageIndex = Control::GetRequest("p", 1);

        if ($pageIndex > 0) {
            $pageBegin = ($pageIndex - 1) * $pageSize;
            $tagId = "vote_select_item_list";
            $allCount = 0;
            $voteSelectItemManageData = new VoteSelectItemManageData();
            $arrList = $voteSelectItemManageData->GetListForPager($voteItemId, $pageBegin, $pageSize, $allCount, $searchKey);
            if (count($arrList) > 0) {
                Template::ReplaceList($tempContent, $arrList, $tagId);
                $styleNumber = 1;
                $pagerTemplate = Template::Load("pager/pager_style".$styleNumber.".html","common");
                $isJs = FALSE;
                $navUrl = "/default.php?secu=manage&mod=vote_select_item&m=list&vote_item_id=$voteItemId&p={0}&ps=$pageSize";
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

    /**
     * 投票调查题目选项按比例加票管理列表页面
     * @return mixed|string
     */
    private function GenAddCountWithRatio()
    {
        $tempContent = Template::Load("vote/vote_select_item_add_count_ratio.html", "common");
        $voteId = Control::GetRequest("vote_id", 0);
        $voteItemId = Control::GetRequest("vote_item_id", 0);
        $tagId = "vote_select_item_list";
        $voteSelectItemManageData = new VoteSelectItemManageData();
        $arrList = $voteSelectItemManageData->GetSelect($voteItemId, 0);
        if (!empty($_POST)) {
            //逐个update
            for ($i = 1; $i <= count($arrList); $i++) {
                $selectItemAddCount = Control::PostRequest("add_count_" . $i, 0);
                $selectItemAddId = Control::PostRequest("id_" . $i, 0);
                $voteSelectItemManageData->ModifyAddCount($selectItemAddId, $selectItemAddCount);
            }
            //计算投票调查中题目的总投票数
            $itemAddCount = $voteSelectItemManageData->GetSum($voteItemId);
            $voteItemData = new VoteItemManageData();
            //同步投票调查中题目的总加票数
            $result = $voteItemData->ModifyAddCount($voteItemId, $itemAddCount);
            //计算投票调查的总投票数
            $addCount = $voteItemData->GetSum($voteId);
            $voteData = new VoteManageData();
            //同步投票调查的总加票数
            $result = $voteData->ModifyAddCount($voteId, $addCount);

            //加入操作日志
            $operateContent = 'AddCountWithRatio VoteSelectItem,POST FORM:' . implode('|', $_POST) . ';\r\nVoteItemId:' . $voteItemId;
            self::CreateManageUserLog($operateContent);

            if ($result > 0) {
                //javascript 处理
                $closeTab = Control::PostRequest("CloseTab", 0);
                if ($closeTab == 1) {
                    Control::ShowMessage(Language::Load('vote', 9));
                    Control::CloseTab();
                } else {
                    Control::GoUrl($_SERVER["PHP_SELF"]);
                }
            } else {
                Control::ShowMessage(Language::Load('vote', 10));
            }
        }
        Template::ReplaceList($tempContent, $arrList, $tagId);
        $tempContent = str_ireplace("{VoteId}", strval($voteId), $tempContent);
        $tempContent = str_ireplace("{VoteItemId}", strval($voteItemId), $tempContent);
        parent::ReplaceEnd($tempContent);
        return $tempContent;
    }

}

?>
