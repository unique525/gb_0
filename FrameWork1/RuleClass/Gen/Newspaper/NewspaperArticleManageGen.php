<?php
/**
 * 后台管理 电子报文章 生成类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_Gen_Newspaper
 * @author zhangchi
 */
class NewspaperArticleManageGen extends BaseManageGen {
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
            case "copy":
                $result = self::GenCopyToDocChannel($method);
                break;
            case "async_modify_sort_by_drag":
                $result = self::AsyncModifySortByDrag();
                break;
            case "async_modify_state":
                $result = self::AsyncModifyState();
                break;
        }
        $result = str_ireplace("{method}", $method, $result);
        return $result;
    }


    /**
     * 编辑
     * @return string 模板内容页面
     */
    private function GenModify()
    {
        $tempContent = Template::Load("newspaper/newspaper_article_deal.html", "common");
        $newspaperArticleId = Control::GetRequest("newspaper_article_id", 0);
        $resultJavaScript = "";
        $manageUserId = Control::GetManageUserId();

        if ($newspaperArticleId > 0 && $manageUserId > 0) {

            parent::ReplaceFirst($tempContent);

            $newspaperArticleManageData = new NewspaperArticleManageData();

            //加载原有数据
            $arrOne = $newspaperArticleManageData->GetOne($newspaperArticleId);
            Template::ReplaceOne($tempContent, $arrOne);


            if (!empty($_POST)) {
                $httpPostData = $_POST;

                $result = $newspaperArticleManageData->Modify($httpPostData, $newspaperArticleId);
                //加入操作日志
                $operateContent = 'Modify Newspaper Article,POST FORM:' . implode('|', $_POST) . ';\r\nResult:result:' . $result;
                self::CreateManageUserLog($operateContent);

                if ($result > 0) {
                    //删除缓冲
                    DataCache::RemoveDir(CACHE_PATH . '/newspaper_article_data');
                    $closeTab = Control::PostRequest("CloseTab", 0);
                    if ($closeTab == 1) {
                        $resultJavaScript .= Control::GetCloseTab();
                    } else {
                        Control::GoUrl($_SERVER["PHP_SELF"] . "?" . $_SERVER['QUERY_STRING']);
                    }
                } else {
                    $resultJavaScript .= Control::GetJqueryMessage(Language::Load('newspaper', 4)); //编辑失败！
                }
            }

            $patterns = '/\{s_(.*?)\}/';
            $tempContent = preg_replace($patterns, "", $tempContent);
        }
        parent::ReplaceEnd($tempContent);
        $tempContent = str_ireplace("{ResultJavascript}", $resultJavaScript, $tempContent);
        return $tempContent;
    }


    /**
     * 修改文档状态 状态值定义在Data类中
     * @return string 返回Jsonp修改结果
     */
    private function AsyncModifyState()
    {
        $result = -1;
        $newspaperArticleId = Control::GetRequest("newspaper_article_id", 0);
        $state = Control::GetRequest("state", -1);
        $manageUserId = Control::GetManageUserId();

        if ($newspaperArticleId > 0 && $state >= 0 && $manageUserId > 0) {

            $newspaperArticleManageData = new NewspaperArticleManageData();
            $newspaperPageManageData = new NewspaperPageManageData();
            $newspaperManageData = new NewspaperManageData();
            //判断权限

            /**********************************************************************
             ******************************判断是否有操作权限**********************
             **********************************************************************/
            $newspaperPageId = $newspaperArticleManageData->GetNewspaperPageId($newspaperArticleId, true);
            $newspaperId = $newspaperPageManageData->GetNewspaperId($newspaperPageId, true);
            $manageUserAuthorityManageData = new ManageUserAuthorityManageData();
            $channelId = $newspaperManageData->GetChannelId($newspaperId, true);
            $can = $manageUserAuthorityManageData->CanModify(0, $channelId, $manageUserId);
            if (!$can) {
                $result = -10;
            } else {
                $result = $newspaperPageManageData->ModifyState($newspaperArticleId, $state);
                //加入操作日志
                $operateContent = 'Modify State Newspaper Article,GET PARAM:' . implode('|', $_GET) . ';\r\nResult:' . $result;
                self::CreateManageUserLog($operateContent);
            }
        }
        return Control::GetRequest("jsonpcallback", "") . '({"result":' . $result . '})';
    }


    private function GenList(){
        $manageUserId = Control::GetManageUserId();

        ///////////////判断是否有操作权限///////////////////
        //$manageUserAuthorityManageData = new ManageUserAuthorityManageData();
        //$canExplore = $manageUserAuthorityManageData->CanExplore($siteId, $channelId, $manageUserId);
        //if (!$canExplore) {
        //    return ;
        //}

        //load template
        $tempContent = Template::Load("newspaper/newspaper_article_list.html", "common");

        parent::ReplaceFirst($tempContent);

        ////////////////////////////////////////////////////
        ///////////////输出权限到页面///////////////////
        ////////////////////////////////////////////////////

        $pageSize = Control::GetRequest("ps", 40);
        $pageIndex = Control::GetRequest("p", 1);
        $searchKey = Control::GetRequest("search_key", "");
        $searchType = Control::GetRequest("search_type", -1);
        $searchKey = urldecode($searchKey);

        $newspaperArticleManageData = new NewspaperArticleManageData();
        $newspaperPageManageData = new NewspaperPageManageData();
        $newspaperManageData = new NewspaperManageData();

        $type = Control::GetRequest("type", 0);
        $newspaperPageId = 0;
        if($type == 0){
            $newspaperPageId = Control::GetRequest("newspaper_page_id", 0);
            $newspaperId = $newspaperPageManageData->GetNewspaperId($newspaperPageId, true);
            $channelId = $newspaperManageData->GetChannelId($newspaperId, true);
        }else{
            $newspaperId = Control::GetRequest("newspaper_id", 0);
            $channelId = $newspaperManageData->GetChannelId($newspaperId, true);
        }


        if ($pageIndex > 0 && $channelId > 0) {

            $tempContent = str_ireplace("{NewspaperPageId}", $newspaperPageId, $tempContent);
            $tempContent = str_ireplace("{ChannelId}", $channelId, $tempContent);

            $pageBegin = ($pageIndex - 1) * $pageSize;
            $tagId = "newspaper_article_list";
            $allCount = 0;

            $arrList = $newspaperArticleManageData->GetList($type, $newspaperId, $newspaperPageId, $pageBegin, $pageSize, $allCount, $searchKey, $searchType);
            if (count($arrList) > 0) {
                Template::ReplaceList($tempContent, $arrList, $tagId);

                $styleNumber = 1;
                $pagerTemplate = Template::Load("pager/pager_style$styleNumber.html", "common");
                $isJs = FALSE;
                if($type == 0){
                    $navUrl = "default.php?secu=manage&mod=newspaper_article&newspaper_page_id=$newspaperPageId&m=list&p={0}&ps=$pageSize";
                }else{
                    $navUrl = "default.php?secu=manage&mod=newspaper_article&type=1&newspaper_id=$newspaperId&m=list&p={0}&ps=$pageSize";
                }
                $jsFunctionName = "";
                $jsParamList = "";
                $pagerButton = Pager::ShowPageButton(
                    $pagerTemplate,
                    $navUrl,
                    $allCount,
                    $pageSize,
                    $pageIndex,
                    $styleNumber,
                    $isJs,
                    $jsFunctionName,
                    $jsParamList
                );

                $tempContent = str_ireplace(
                    "{pager_button}",
                    $pagerButton,
                    $tempContent
                );

            } else {
                Template::RemoveCustomTag($tempContent, $tagId);
                $tempContent = str_ireplace("{pager_button}", Language::Load("newspaper", 7), $tempContent);
            }

            $newspaperId=$newspaperPageManageData->GetNewspaperId($newspaperPageId,true);
            $tempContent = str_ireplace("{NewspaperId}", $newspaperId, $tempContent);
        }

        parent::ReplaceEnd($tempContent);
        return $tempContent;
    }



    /**
     * 批量修改排序号
     * @return string 返回Jsonp修改结果
     */
    private function AsyncModifySortByDrag()
    {
        $arrNewspaperArticleId = Control::GetRequest("sort", null, false);
        if (!empty($arrNewspaperArticleId)) {
            DataCache::RemoveDir(CACHE_PATH . '/newspaper_article_data');
            $newspaperArticleManageData = new NewspaperArticleManageData();
            $result = $newspaperArticleManageData->ModifySortForDrag($arrNewspaperArticleId);
            return Control::GetRequest("jsonpcallback", "") . '({"result":' . $result . '})';
        } else {
            return "";
        }
    }

    /**
     * 处理复制操作
     * @return string 操作结果
     */
    private function GenCopyToDocChannel() {
        $tempContent = Template::Load("document/document_deal.html", "common");
        parent::ReplaceFirst($tempContent);
        $mod=Control::GetRequest("mod","");
        $method=Control::GetRequest("m","");
        $channelId = Control::GetRequest("channel_id", 0);
        $docIdString = $_GET["doc_id_string"]; //GetRequest中的过滤会消去逗号
        $manageUserId = Control::GetManageUserID();
        $manageUserName = Control::GetManageUserName();
        if ($channelId > 0) {
            $newspaperArticleManageData=new NewspaperArticleManageData();
            $channelManageData = new ChannelManageData();
            $documentNewsManageData = new DocumentNewsManageData();

            $arrayOfNewspaperList = $newspaperArticleManageData->GetListByIDString($docIdString);
            if (!empty($_POST)) { //提交
                $targetCid = Control::PostRequest("pop_cid", 0); //目标频道ID
                $targetSiteId = $channelManageData->GetSiteId($targetCid,true);


                if( $targetCid > 0){
                    $channelType = $channelManageData->GetChannelType($targetCid,true);

                            if (strlen($docIdString) > 0) {
                                if ($channelType === 1) {   //新闻资讯类
                                    $result = $documentNewsManageData->CopyFromNewsPaperArticle($targetSiteId,$targetCid, $arrayOfNewspaperList, $manageUserId, $manageUserName);

                                    //加入操作日志
                                    $operateContent = 'copy Newspaper Article,POST FORM:' . implode('|', $_POST) . ';\r\nResult:result:' . $result;
                                    self::CreateManageUserLog($operateContent);


                                    if ($result > 0) {
                                        $jsCode = 'parent.$("#dialog_resultbox").dialog("close");';
                                        Control::RunJavascript($jsCode);
                                    } else {
                                        Control::ShowMessage(Language::Load('document', 17));
                                    }
                                }
                            }
            }
            }

            $documentList = "";

            //显示操作文档的标题
            //for ($i = 0; $i < count($arrList); $i++) {
            //    $columns = $arrList[$i];
                foreach ($arrayOfNewspaperList as $columnName => $columnValue) {
                    $documentList = $documentList . $columnValue["NewspaperArticleTitle"] . '<br>';
                }
            //}
            //显示当前站点的节点树
            $siteId = $channelManageData->GetSiteID($channelId,true);
            $order="";
            $arrayChannelTree=$channelManageData->GetListForManageLeft($siteId,$manageUserId,$order);
            $listName="channel_tree";
            Template::ReplaceList($tempContent,$arrayChannelTree,$listName);

            $methodName = "复制";
            $replaceArr = array(
                "{mod}" => $mod,
                "{method}" => $method,
                "{SiteId}" => $siteId,
                "{ChannelId}" => $channelId,
                "{ChannelName}" => "",
                "{Method}" => $methodName,
                "{MethodName}" => $methodName,
                "{DealType}" => $methodName,
                "{DocumentList}" => $documentList,
                "{DocIdString}" => $docIdString
            );

            $tempContent = strtr($tempContent, $replaceArr);
        }

        parent::ReplaceEnd($tempContent);
        return $tempContent;
    }
}