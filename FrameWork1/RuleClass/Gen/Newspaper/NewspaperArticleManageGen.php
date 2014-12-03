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

        $newspaperPageId = Control::GetRequest("newspaper_page_id", 0);
        $newspaperId = $newspaperPageManageData->GetNewspaperId($newspaperPageId, true);
        $channelId = $newspaperManageData->GetChannelId($newspaperId, true);

        if ($pageIndex > 0 && $newspaperPageId > 0 && $channelId > 0) {

            $tempContent = str_ireplace("{NewspaperPageId}", $newspaperPageId, $tempContent);
            $tempContent = str_ireplace("{ChannelId}", $channelId, $tempContent);

            $pageBegin = ($pageIndex - 1) * $pageSize;
            $tagId = "newspaper_article_list";
            $allCount = 0;

            $arrList = $newspaperArticleManageData->GetList($newspaperPageId, $pageBegin, $pageSize, $allCount, $searchKey, $searchType);
            if (count($arrList) > 0) {
                Template::ReplaceList($tempContent, $arrList, $tagId);

                $styleNumber = 1;
                $pagerTemplate = Template::Load("pager/pager_style$styleNumber.html", "common");
                $isJs = FALSE;
                $navUrl = "default.php?secu=manage&mod=newspaper_article&newspaper_page_id=$newspaperPageId&m=list&p={0}&ps=$pageSize";
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
        }

        parent::ReplaceEnd($tempContent);
        return $tempContent;
    }
}