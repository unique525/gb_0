<?php

/**
 * 后台管理 电子报版面 生成类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_Gen_Newspaper
 * @author zhangchi
 */
class NewspaperPageManageGen extends BaseManageGen {
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
            case "async_modify_sort":
                $result = self::AsyncModifySort();
                break;
            case "async_modify_sort_by_drag":
                $result = self::AsyncModifySortByDrag();
                break;
            case "async_modify_state":
                $result = self::AsyncModifyState();
                break;
            case "async_delete":
                $result = self::AsyncDelete();
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
        $tempContent = Template::Load("newspaper/newspaper_page_deal.html", "common");
        $newspaperPageId = Control::GetRequest("newspaper_page_id", 0);
        $resultJavaScript = "";
        $manageUserId = Control::GetManageUserId();

        if ($newspaperPageId > 0 && $manageUserId > 0) {

            parent::ReplaceFirst($tempContent);

            $newspaperPageManageData = new NewspaperPageManageData();

            //加载原有数据
            $arrOne = $newspaperPageManageData->GetOne($newspaperPageId);
            Template::ReplaceOne($tempContent, $arrOne);


            if (!empty($_POST)) {
                $httpPostData = $_POST;

                $result = $newspaperPageManageData->Modify($httpPostData, $newspaperPageId);
                //加入操作日志
                $operateContent = 'Modify Newspaper Page,POST FORM:' . implode('|', $_POST) . ';\r\nResult:result:' . $result;
                self::CreateManageUserLog($operateContent);

                if ($result > 0) {
                    //删除缓冲
                    parent::DelAllCache();


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

    private function AsyncDelete(){
        $newspaperPageId = Control::GetRequest("newspaper_page_id", 0);
        $result = -1;
        if ($newspaperPageId > 0) {
            parent::DelAllCache();

            $newspaperPageManageData = new NewspaperPageManageData();
            $result = $newspaperPageManageData->Delete($newspaperPageId);

            if($result>0){

                $newspaperArticleManageData = new
                    NewspaperArticleManageData();
                $newspaperArticleManageData->DeleteByNewspaperPageId(
                    $newspaperPageId
                );

                $newspaperArticlePicManageData = new
                NewspaperArticlePicManageData();

                $newspaperArticlePicManageData->DeleteOfNull();

            }

        }
        return Control::GetRequest("jsonpcallback", "") . '({"result":' . $result . '})';
    }

    /**
     * 修改排序号
     * @return int 修改结果
     */
    private function AsyncModifySort()
    {
        $result = -1;
        $newspaperPageId = Control::GetRequest("newspaper_page_id", 0);
        $sort = Control::GetRequest("sort", 0);
        if ($newspaperPageId > 0) {
            parent::DelAllCache();
            $newspaperPageManageData = new NewspaperPageManageData();
            $result = $newspaperPageManageData->ModifySort($sort, $newspaperPageId);
        }
        return $result;
    }


    /**
     * 批量修改排序号
     * @return string 返回Jsonp修改结果
     */
    private function AsyncModifySortByDrag()
    {
        $arrNewspaperPageId = Control::GetRequest("sort", null, false);
        if (!empty($arrNewspaperPageId)) {
            parent::DelAllCache();
            $newspaperPageManageData = new NewspaperPageManageData();
            $result = $newspaperPageManageData->ModifySortForDrag($arrNewspaperPageId);
            return Control::GetRequest("jsonpcallback", "") . '({"result":' . $result . '})';
        } else {
            return "";
        }
    }

    /**
     * 修改文档状态 状态值定义在Data类中
     * @return string 返回Jsonp修改结果
     */
    private function AsyncModifyState()
    {
        $result = -1;
        $newspaperPageId = Control::GetRequest("newspaper_page_id", 0);
        $state = Control::GetRequest("state", -1);
        $manageUserId = Control::GetManageUserId();

        if ($newspaperPageId > 0 && $state >= 0 && $manageUserId > 0) {

            $newspaperPageManageData = new NewspaperPageManageData();
            $newspaperManageData = new NewspaperManageData();
            //判断权限

            /**********************************************************************
             ******************************判断是否有操作权限**********************
             **********************************************************************/
            $newspaperId = $newspaperPageManageData->GetNewspaperId($newspaperPageId, true);
            $manageUserAuthorityManageData = new ManageUserAuthorityManageData();
            $channelId = $newspaperManageData->GetChannelId($newspaperId, true);
            $can = $manageUserAuthorityManageData->CanChannelModify(0, $channelId, $manageUserId);
            if (!$can) {
                $result = -10;
            } else {
                //删除缓冲
                parent::DelAllCache();

                $result = $newspaperPageManageData->ModifyState($newspaperPageId, $state);
                //加入操作日志
                $operateContent = 'Modify State Newspaper Page,GET PARAM:' . implode('|', $_GET) . ';\r\nResult:' . $result;
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
        $tempContent = Template::Load("newspaper/newspaper_page_list.html", "common");

        parent::ReplaceFirst($tempContent);

        ////////////////////////////////////////////////////
        ///////////////输出权限到页面///////////////////
        ////////////////////////////////////////////////////

        $pageSize = Control::GetRequest("ps", 40);
        $pageIndex = Control::GetRequest("p", 1);
        $searchKey = Control::GetRequest("search_key", "");
        $searchType = Control::GetRequest("search_type", -1);
        $searchKey = urldecode($searchKey);

        $newspaperPageManageData = new NewspaperPageManageData();
        $newspaperManageData = new NewspaperManageData();

        $newspaperId = Control::GetRequest("newspaper_id", 0);
        $channelId = $newspaperManageData->GetChannelId($newspaperId, true);

        if ($pageIndex > 0 && $newspaperId > 0 && $channelId > 0) {


            $tempContent = str_ireplace("{NewspaperId}", $newspaperId, $tempContent);
            $tempContent = str_ireplace("{ChannelId}", $channelId, $tempContent);

            $pageBegin = ($pageIndex - 1) * $pageSize;
            $tagId = "newspaper_page_list";
            $allCount = 0;

            $arrList = $newspaperPageManageData->GetList($newspaperId, $pageBegin, $pageSize, $allCount, $searchKey, $searchType);
            if (count($arrList) > 0) {
                Template::ReplaceList($tempContent, $arrList, $tagId);

                $styleNumber = 1;
                $pagerTemplate = Template::Load("pager/pager_style$styleNumber.html", "common");
                $isJs = FALSE;
                $navUrl = "default.php?secu=manage&mod=newspaper_page&newspaper_id=$newspaperId&m=list&p={0}&ps=$pageSize";
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