<?php

/**
 * 后台论坛生成类 
 * @category iCMS
 * @package iCMS_Rules_Gen_Forum
 * @author zhangchi
 */
class ForumTopicManageGen extends BaseManageGen implements IBaseManageGen  {
    
    /**
     * 引导方法
     * @return string 返回执行结果
     */
    public function Gen() {
        $result = "";
        $method = Control::GetRequest("m", "");
        switch ($method) {
            case "list":
                $result = self::GenList();
                break;
            case "modify":
                $result = self::GenModify();
                break;
            case "remove_to_bin":
                $result = self::GenRemoveToBin();
                break;
            case "move_topic_to_other_block":
                $result = self::moveTopicToOtherBlock();
                break;
        }
        $result = str_ireplace("{action}", $method, $result);
        return $result;
    }

    private function GenList(){

        $siteId = Control::GetRequest("site_id", 0);
        $forumId = Control::GetRequest("forum_id", 0);
        $siteName = Control::GetRequest("site_name", "");
        $resultJavaScript="";
        $tempContent = Template::Load("forum/forum_topic_list.html","common");
        $forumTopicManageData = new ForumTopicManageData();
        if(intval($siteId)>0){
            $pageSize = Control::GetRequest("ps", 20);
            $pageIndex = Control::GetRequest("p", 1);
            $searchKey = Control::GetRequest("search_key", "");
            $pageBegin = ($pageIndex - 1) * $pageSize;
            $allCount = 0;
            $listName = "forum_topic_list";

            ///////////////判断是否有操作权限///////////////////
            $manageUserId = Control::GetManageUserId();
            $manageUserAuthority = new ManageUserAuthorityManageData();
            $can = $manageUserAuthority->CanManageUserModify($siteId, 0, $manageUserId);
            if ($can == 1) {

                $forumTopicArray=$forumTopicManageData->GetListPager($siteId,$forumId,$pageBegin,$pageSize,$allCount,$searchKey);
                    if(count($forumTopicArray)>0){
                    Template::ReplaceList($tempContent, $forumTopicArray, $listName);
                    $styleNumber = 1;
                    $pagerTemplate = Template::Load("pager/pager_style$styleNumber.html", "common");
                    $isJs = FALSE;
                    $navUrl = "default.php?secu=manage&mod=forum_topic&m=list&site_id=$siteId&forum_id=$forumId&p={0}&ps=$pageSize";
                    $jsFunctionName = "";
                    $jsParamList = "";
                    $pagerButton = Pager::ShowPageButton($pagerTemplate, $navUrl, $allCount, $pageSize, $pageIndex ,$styleNumber = 1, $isJs, $jsFunctionName, $jsParamList);

                    $replaceArr = array(
                        "{f_SiteName}"=>$siteName,
                        "{PagerButton}" => $pagerButton
                    );
                    $tempContent = strtr($tempContent, $replaceArr);
                }else{
                    Template::RemoveCustomTag($tempContent, $listName);
                    $tempContent = str_ireplace("{PagerButton}", Language::Load("document", 7), $tempContent);
                }
            }   else{

                Template::RemoveCustomTag($tempContent, $listName);
                $tempContent = str_ireplace("{PagerButton}", Language::Load('document', 26), $tempContent);//您尚未开通操作此功能的权限，如需开通此权限，请联系管理人员！
            }
        }else{
            $resultJavaScript.=Control::GetJqueryMessage(Language::Load('site_ad', 3));//站点id错误！
        }


        parent::ReplaceEnd($tempContent);
        $tempContent = str_ireplace("{ResultJavascript}", $resultJavaScript, $tempContent);
        $tempContent = str_ireplace('{f_ForumId}', $forumId, $tempContent);
        $result = $tempContent;
        return $result;
    }
    private function GenModify(){
        $result = -1;
        $forumId = Control::GetRequest("forum_id", 0);
        if ($forumId <= 0) {
            die("");
        }

        $forumTopicId = Control::GetRequest("forum_topic_id", 0);
        if ($forumTopicId <= 0) {
            die("");
        }
        $siteId = Control::GetRequest("site_id", 0);

        $tempContent = Template::Load("forum/forum_topic_deal.html","common");
        //echo $tempContent;
        $forumTopicManageData = new ForumTopicManageData();
        $arrOne = $forumTopicManageData->GetOne($forumTopicId);

        Template::ReplaceOne($tempContent, $arrOne, false, false);

        $tempContent = str_ireplace("{f_ForumTopicTitle}", $arrOne["ForumTopicTitle"], $tempContent);
        $tempContent = str_ireplace("{f_ForumPostContent}", $arrOne["ForumPostContent"], $tempContent);
        if (!empty($_POST)) {
            $forumTopicTitle = Control::PostRequest("f_ForumTopicTitle", "");
            $forumTopicTitle = Format::FormatHtmlTag($forumTopicTitle);

            $forumPostContent = Control::PostRequest("f_ForumPostContent", "");
            //内容中不允许脚本等
            $forumPostContent = Format::RemoveScript($forumPostContent);
            $result = $forumTopicManageData->Modify($forumTopicId,$forumTopicTitle);
            if($result > 0){
                $forumPostManageData = new ForumPostManageData();
                $result = $forumPostManageData->Modify($forumTopicId,$forumPostContent);
                if($result > 0 && $isTopic=1){
                    Control::GoUrl("/default.php?secu=manage&mod=forum_topic&a=list&forum_id=".$forumId."&site_id=".$siteId);
                }
            }
        }

        parent::ReplaceEnd($tempContent);
        $result = $tempContent;
        return $result;
    }

    private function GenRemoveToBin(){
        $result = -1;
        return $result;
    }

    private function moveTopicToOtherBlock()
    {

        $result = -4;

        $siteId = Control::GetRequest("site_id", 0);
        $forumIdMoveTo = Control::PostRequest("forum_id_move_to", 0);
        $forumTopicIds = Control::GetRequest("forum_ids",0);
        ///////////////判断是否有操作权限///////////////////
        $manageUserId = Control::GetManageUserId();
        $manageUserAuthority = new ManageUserAuthorityManageData();
        $can = $manageUserAuthority->CanManageUserModify($siteId, 0, $manageUserId);
        if ($can == 1) {
            $forumTopicManageData = new ForumTopicManageData();
            $forumTopicIdArr = explode('|!|', $forumTopicIds);
            $result = $forumTopicManageData->MoveTopicToOtherBlock($siteId, $forumIdMoveTo, $forumTopicIdArr);
        }
        return Control::GetRequest("jsonpcallback", "") . '({"result":' . $result . '})';
    }
}

?>
