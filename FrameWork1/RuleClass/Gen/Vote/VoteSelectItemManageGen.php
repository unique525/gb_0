<?php

/**
 * 投票调查题目选项生成类
 * @category iCMS
 * @package  iCMS_Rules_Gen_Vote
 * @author   yanjiuyuan
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
            case "async_modify_state":
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
            case "pre_check_creating_from_channel":
                $result = self::PreCheckCreatingFromChannel();
                break;
            case "create_from_channel":
                $result = self::GenCreateFromChannel();
                break;
            case "create_from_txt":
                $result = self::GenCreateFromTxt();
                break;
            case "check_upload_txt":
                $result = self::CheckUploadTxt();
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
        $resultJavaScript = "";
        if ($manageUserId > 0) {
            parent::ReplaceFirst($tempContent);
            $tempContent = str_ireplace("{voteItemId}", strval($voteItemId), $tempContent);
            $tempContent = str_ireplace("{pageIndex}", strval($pageIndex), $tempContent);
            $voteSelectItemManageData = new VoteSelectItemManageData();
            parent::ReplaceWhenCreate($tempContent, $voteSelectItemManageData->GetFields());
            $patterns = '/\{s_(.*?)\}/';
            $tempContent = preg_replace($patterns, "", $tempContent);
            if (!empty($_POST)) {
                $httpPostData = $_POST;
                $voteSelectItemId = $voteSelectItemManageData->Create($httpPostData);

                //加入操作日志
                $operateContent = 'Create VoteSelectItem,POST FORM:' . implode('|', $_POST) . ';\r\nResult:VoteSelectItemId:' . $voteSelectItemId;
                self::CreateManageUserLog($operateContent);

                $tableId = $voteItemId;
                //处理题图1
                $fileElementName = "file_title_pic_1";
                $tableType = UploadFileData::UPLOAD_TABLE_TYPE_VOTE_SELECT_ITEM_TITLE_PIC_1;

                $uploadFile1 = new UploadFile();
                $uploadFileId1 = 0;
                $titlePic1Result = self::Upload(
                    $fileElementName,
                    $tableType,
                    $tableId,
                    $uploadFile1,
                    $uploadFileId1
                );

                if (intval($titlePic1Result) <= 0 && $uploadFileId1 <= 0) {
                    //上传出错或没有选择文件上传
                }
                else {
                    //修改题图
                    $voteSelectItemManageData->ModifyTitlePic1UploadFileId($voteSelectItemId, $uploadFileId1);
                }
                if ($voteSelectItemId > 0) {
                    //javascript 处理
                    $resultJavaScript = Control::GetJqueryMessage(Language::Load('vote', 1));
                    $jsCode = 'parent.location.href=parent.location.href';
                    Control::RunJavascript($jsCode);
                    $tempContent = "";
                }
                else {
                    Control::ShowMessage(Language::Load('vote', 2));
                }
            }
        }
        else {
            $tempContent = "";
        }
        parent::ReplaceEnd($tempContent);
        $tempContent = str_ireplace("{ResultJavascript}", $resultJavaScript, $tempContent);
        return $tempContent;
    }

    /**
     * 停用投票调查题目选项
     * @return mixed|string
     */
    private function AsyncModifyState()
    {
        //$result = -1;
        $voteSelectItemId = Control::GetRequest("vote_select_item_id", 0);
        $state = Control::GetRequest("state", 0);
        if ($voteSelectItemId > 0) {
            $voteSelectItemData = new VoteSelectItemManageData();
            $result = $voteSelectItemData->ModifyState($voteSelectItemId, $state);
            //加入操作日志
            $operateContent = 'ModifyState VoteSelectItem,Get FORM:' . implode('|', $_GET) . ';\r\nResult:voteSelectItemId:' . $voteSelectItemId;
            self::CreateManageUserLog($operateContent);
        }
        else {
            $result = -1;
        }
        return Control::GetRequest("jsonpcallback", "") . '({"result":"' . $result . '"})';
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
        $resultJavaScript = "";
        if ($voteSelectItemId > 0) {
            parent::ReplaceFirst($tempContent);
            $tempContent = str_ireplace("{PageIndex}", strval($pageIndex), $tempContent);
            $voteSelectItemManageData = new VoteSelectItemManageData();
            $arrList = $voteSelectItemManageData->GetOne($voteSelectItemId);
            Template::ReplaceOne($tempContent, $arrList, false, false);
            //替换掉{s XXX}的内容
            $patterns = '/\{s_(.*?)\}/';
            $tempContent = preg_replace($patterns, "", $tempContent);
            if (!empty($_POST)) {
                $httpPostData = $_POST;
                $result = $voteSelectItemManageData->Modify($httpPostData, $voteSelectItemId);
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
                    //题图操作
                    if (!empty($_FILES)) {

                        $tableId = $voteSelectItemId;

                        //title pic1
                        $fileElementName = "file_title_pic_1";
                        $tableType = UploadFileData::UPLOAD_TABLE_TYPE_VOTE_SELECT_ITEM_TITLE_PIC_1;

                        $uploadFile1 = new UploadFile();
                        $uploadFileId1 = 0;
                        $titlePic1Result = self::Upload(
                            $fileElementName,
                            $tableType,
                            $tableId,
                            $uploadFile1,
                            $uploadFileId1
                        );

                        if (intval($titlePic1Result) <= 0 && $uploadFileId1 <= 0) {
                            //上传出错或没有选择文件上传
                        }
                        else {
                            //删除原有题图
                            $oldUploadFileId1 = $voteSelectItemManageData->GetTitlePic1UploadFileId($voteSelectItemId, false);
                            parent::DeleteUploadFile($oldUploadFileId1);

                            //修改题图
                            $voteSelectItemManageData->ModifyTitlePic1UploadFileId($voteSelectItemId, $uploadFileId1);
                        }
                    }
                    //javascript 处理
                    $jsCode = 'parent.location.href=parent.location.href';
                    Control::RunJavascript($jsCode);
                    $tempContent = "";
                }
                else {
                    $resultJavaScript = Control::GetJqueryMessage(Language::Load('vote', 4));
                }
            }
        }
        parent::ReplaceEnd($tempContent);
        $tempContent = str_ireplace("{ResultJavascript}", $resultJavaScript, $tempContent);
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
                $pagerTemplate = Template::Load("pager/pager_style" . $styleNumber . ".html", "common");
                $isJs = FALSE;
                $navUrl = "/default.php?secu=manage&mod=vote_select_item&m=list&vote_item_id=$voteItemId&p={0}&ps=$pageSize";
                $jsFunctionName = "";
                $jsParamList = "";
                $pagerButton = Pager::ShowPageButton($pagerTemplate, $navUrl, $allCount, $pageSize, $pageIndex, $styleNumber, $isJs, $jsFunctionName, $jsParamList);
                Template::ReplaceList($templateContent, $arrList, $tagId);
                $templateContent = str_ireplace("{pager_button}", $pagerButton, $templateContent);
            }
            else {
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
        $arrList = $voteSelectItemManageData->GetListForAddCount($voteItemId, 0);
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
                }
                else {
                    Control::GoUrl($_SERVER["PHP_SELF"] . "?" . $_SERVER['QUERY_STRING']);
                }
            }
            else {
                Control::ShowMessage(Language::Load('vote', 10));
            }
        }
        Template::ReplaceList($tempContent, $arrList, $tagId);
        $tempContent = str_ireplace("{VoteId}", strval($voteId), $tempContent);
        $tempContent = str_ireplace("{VoteItemId}", strval($voteItemId), $tempContent);
        parent::ReplaceEnd($tempContent);
        return $tempContent;
    }


    public function PreCheckCreatingFromChannel()
    {
        $result = array();
        $channelId = Control::GetRequest("channel_id", 0);
        $itemCount = Control::GetRequest("item_count", 0);
        $state = Control::GetRequest("state", 30);
        if ($channelId > 0 && $itemCount > 0) {
            $channelManageData = new ChannelManageData();
            $siteManageData = new SiteManageData();
            $siteId = $channelManageData->GetSiteId($channelId, true);
            $siteUrl = $siteManageData->GetSiteUrl($siteId, true);
            $channelType = $channelManageData->GetChannelType($channelId, true);
            $result["ChannelType"] = $channelType;
            switch ($channelType) {
                case 1:
                    $documentNewsManageData = new DocumentNewsManageData();
                    $arrDocumentNews = $documentNewsManageData->GetListForVoteSelectItem($channelId, $itemCount, $state);
                    for ($i = 0; $i < count($arrDocumentNews); ++$i) {
                        $strPublishDate = str_ireplace('-', '', substr($arrDocumentNews[$i]["PublishDate"], 0, 10));
                        $arrDocumentNews[$i]["DocumentNewsUrl"] = $siteUrl . '/h/' . $channelId . '/' . $strPublishDate . '/' . $arrDocumentNews[$i]["DocumentNewsId"] . '.html';
                    }
                    if (count($arrDocumentNews) > 0) {
                        $result["list"] = $arrDocumentNews;
                    }
                    break;
                default:
                    $result["ChannelType"] = -100;//节点类型错误
                    break;
            }
        }

        return Control::GetRequest("jsonpcallback", "") . '(' . json_encode($result) . ')';
    }


    public function GenCreateFromChannel()
    {
        $result = -1;
        $resultJavaScript = "";
        $tempContent = Template::Load("vote/vote_select_item_create_from_channel.html", "common");
        $voteItemId = Control::GetRequest("vote_item_id", 0);
        if ($voteItemId > 0) {

            if (!empty($_POST)) {
                $channelId = Control::PostRequest("ChannelId", 0);
                //$itemCount=Control::PostRequest("ItemCount",0);
                $channelType = Control::PostRequest("ChannelType", 0);
                $error = "";
                if ($channelId > 0 && $channelType > 0) {
                    $json = $_POST["JSONArray"];
                    $json = str_ireplace('\\', '', $json);
                    $jsonArray = json_decode($json);
                    if (count($jsonArray) > 0) {
                        $voteSelectItemManageData = new VoteSelectItemManageData;
                        foreach ($jsonArray as $itemOne) {

                            $new = $voteSelectItemManageData->Create($itemOne);
                            if ($new < 0) {
                                $error .= $itemOne->f_VoteSelectItemTitle . "<br />";
                            }
                            if ($error == "") {
                                $result = 1;
                            }
                            else {
                                $result = -1;
                                $error = '以下内容导入失败:<br />' . $error;
                            }
                        }
                    }
                }
                if ($result > 0) {
                    //javascript 处理
                    $resultJavaScript = Control::GetJqueryMessage(Language::Load('vote', 1));
                    $jsCode = 'parent.location.href=parent.location.href';
                    Control::RunJavascript($jsCode);
                    $tempContent = "";
                }
                else {
                    Control::ShowMessage(Language::Load('vote', 2));
                }
            }
            $tempContent = str_ireplace("{VoteItemId}", strval($voteItemId), $tempContent);
        }
        else {
            $tempContent = "投票主题错误";
        }
        parent::ReplaceEnd($tempContent);
        $tempContent = str_ireplace("{ResultJavascript}", $resultJavaScript, $tempContent);
        return $tempContent;
    }

    /**从复制的TXT文本录入数据**/
    public function GenCreateFromTxt()
    {
        $result = -1;

        $tempContent = Template::Load("vote/vote_txt_source_input.html", "common");
        $voteItemId = Control::GetRequest("vote_item_id", 0);

        if ($voteItemId > 0) {
            $tempContent = str_ireplace("{VoteItemId}", strval($voteItemId), $tempContent);
            $result = $tempContent;
        }
        return $result;
    }

    /**
     * 处理json
     * 更新数据库
     **/
    public function CheckUploadTxt()
    {

        $resultStatus = -1;
        $resultMessage = '';

        $voteItemId = Control::GetRequest("vote_item_id", 0);
        $txt = Control::PostRequest("txt", "", false);   //停用防止xss,否则逗号会被删除


        $txt = html_entity_decode($txt);
        $txt = str_ireplace("\\", "", $txt);

        $arr_voteItem = json_decode($txt, true);

        if (is_array($arr_voteItem)) {

            $arr_dataProperty = array();
            foreach ($arr_voteItem as $arr_voteSelectItem) {

                $arr_voteSelectItem = $this->ChangeArrayKeyFromTxt($arr_voteSelectItem, $voteItemId);
                $arr_dataProperty[] = $arr_voteSelectItem;
            }

            $voteSelectItemManegeData = new VoteSelectItemManageData();

            $resultStatus = $voteSelectItemManegeData->RemoveDataBeforeImportFromTxt($voteItemId);


            if($resultStatus >= 0){

                $resultStatus = $voteSelectItemManegeData->CreateVoteItemBatch($arr_dataProperty);
            }


            if($resultStatus >= 0){
                $resultStatus = 1;
                $resultMessage = "上传成功";
            }
            else{
                $resultStatus = -1;
                $resultMessage = "写入失败";
            }

        }
        else {
            $resultStatus = -1;
            $resultMessage = '不能解析的数据格式';
        }

        $arr_result = array("result" => $resultStatus, "message" => $resultMessage);
        return Control::GetRequest("jsonpcallback", "") . '(' . json_encode($arr_result) . ')';
    }


    private function ChangeArrayKeyFromTxt($arr, $voteItemId){
        $arr_result["Type"]                = trim($arr[0]);
        $arr_result["VoteSelectItemTitle"] = trim($arr[1]);
        $arr_result["Author"]              = trim($arr[2]);
        $arr_result["Editor"]              = trim($arr[3]);
        $arr_result["PublishDate"]         = trim($arr[4]);
        $arr_result["PageNo"]              = trim($arr[5]);
        $arr_result["Email"]               = trim($arr[6]);
        $arr_result["VoteItemId"]          = $voteItemId;

        return $arr_result;
    }

}

?>
