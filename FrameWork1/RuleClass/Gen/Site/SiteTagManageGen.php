<?php

/**
 * 后台管理 站点 生成类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_Gen_Site
 * @author zhangchi
 */
class SiteTagManageGen extends BaseManageGen implements IBaseManageGen
{
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

        }

        $result = str_ireplace("{method}", $method, $result);
        return $result;
    }
    private function GenList(){

        $siteId = Control::GetRequest("site_id", 0);
        $resultJavaScript="";
        $tempContent = Template::Load("site/site_tag_list.html","common");
        $siteTagManageData = new SiteTagManageData();

        if(intval($siteId)>0){
            $pageSize = Control::GetRequest("ps", 20);
            $pageIndex = Control::GetRequest("p", 1);
            $searchKey = Control::GetRequest("search_key", "");
            $pageBegin = ($pageIndex - 1) * $pageSize;
            $allCount = 0;
            $listName = "site_tag_list";

            ///////////////判断是否有操作权限///////////////////
            $manageUserId = Control::GetManageUserId();
            $manageUserAuthority = new ManageUserAuthorityManageData();
            $can = $manageUserAuthority->CanManageUserModify($siteId, 0, $manageUserId);
            if ($can == 1) {

                $siteTagArray=$siteTagManageData->GetListPager($siteId,$pageBegin,$pageSize,$searchKey);
                if(count($siteTagArray)>0){
                    Template::ReplaceList($tempContent, $siteTagArray, $listName);
                    $styleNumber = 1;
                    $pagerTemplate = Template::Load("pager/pager_style$styleNumber.html", "common");
                    $isJs = FALSE;
                    $navUrl = "default.php?secu=manage&mod=forum_topic&a=list&site_id=$siteId&p={0}&ps=$pageSize";
                    $jsFunctionName = "";
                    $jsParamList = "";
                    $pagerButton = Pager::ShowPageButton($pagerTemplate, $navUrl, $allCount, $pageSize, $pageIndex ,$styleNumber = 1, $isJs, $jsFunctionName, $jsParamList);

                    $replaceArr = array(
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
            $resultJavaScript.=Control::GetJqueryMessage(Language::Load('site_id', 3));//站点id错误！
        }


        parent::ReplaceEnd($tempContent);
        $tempContent = str_ireplace("{ResultJavascript}", $resultJavaScript, $tempContent);
        $result = $tempContent;
        return $result;
    }
}