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
        $method = Control::GetRequest("a", "");
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
        }
        $result = str_ireplace("{action}", $method, $result);
        return $result;
    }

    private function GenList(){
        $siteId = Control::GetRequest("site_id", 0);
        $forumId = Control::GetRequest("forumId", 0);
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
            $listName = "site_ad";

            ///////////////判断是否有操作权限///////////////////
            $manageUserId = Control::GetManageUserId();
            $manageUserAuthority = new ManageUserAuthorityManageData();
            $can = $manageUserAuthority->CanManageAd($siteId, 0, $manageUserId);
            if ($can == 1) {

                $forumTopicArray=$forumTopicManageData->GetListPager($siteId,$forumId,$pageBegin,$pageSize,$allCount,$searchKey);
                if(count($forumTopicArray)>0){
                    Template::ReplaceList($tempContent, $forumTopicArray, $listName);
                    $styleNumber = 1;
                    $pagerTemplate = Template::Load("pager/pager_style$styleNumber.html", "common");
                    $isJs = FALSE;
                    $navUrl = "default.php?secu=manage&mod=topic&m=list&site_id=$siteId&p={0}&ps=$pageSize";
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
        $result = $tempContent;
        return $result;
    }
    private function GenModify(){
        $result = -1;
        return $result;
    }

    private function GenRemoveToBin(){
        $result = -1;
        return $result;
    }
}

?>
