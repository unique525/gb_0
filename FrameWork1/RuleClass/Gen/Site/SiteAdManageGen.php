<?php

/**
 * 活动表单页面生成类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_Gen_CustomForm
 * @author 525
 */

class SiteAdManageGen extends BaseManageGen implements IBaseManageGen {

    /**
     * 引导方法
     * @return string 返回执行结果
     */
    function Gen() {
        $result = "";
        $method = Control::GetRequest("m", "");
        switch ($method) {
            case "create":
                $result = self::GenCreate();
                break;
            case "modify":
                $result = self::GenModify();
                break;
            case "list":
                $result = self::GenList();
                break;
        }
        $replaceArray = array(
            "{method}" => $method
        );
        $result = strtr($result, $replaceArray);
        return $result;
    }

    private function GenCreate(){

    }

    private function GenModify(){

    }

    private function GenList(){
    $siteId = Control::GetRequest("site_id", 0);
    $resultJavaScript="";
    $tempContent = Template::Load("site/site_ad_list.html","common");
    $siteAdManageData=new SiteAdManageData();
    if(intval($siteId)>0){
        $pageSize = Control::GetRequest("ps", 20);
        $pageIndex = Control::GetRequest("p", 1);
        $searchKey = Control::GetRequest("search_key", "");
        $pageBegin = ($pageIndex - 1) * $pageSize;
        $allCount = 0;
        $listName = "site_ad";
        $listOfSiteAdArray=$siteAdManageData->GetListPager($siteId,$pageBegin,$pageSize,$allCount,$searchKey);
        if(count($listOfSiteAdArray)>0){
            Template::ReplaceList($tempContent, $listOfSiteAdArray, $listName);

            $styleNumber = 1;
            $pagerTemplate = Template::Load("pager/pager_style$styleNumber.html", "common");
            $isJs = FALSE;
            $navUrl = "default.php?secu=manage&mod=site_ad&m=list&site_id=$siteId&p={0}&ps=$pageSize";
            $jsFunctionName = "";
            $jsParamList = "";
            $pagerButton = Pager::ShowPageButton($pagerTemplate, $navUrl, $allCount, $pageSize, $pageIndex ,$styleNumber = 1, $isJs, $jsFunctionName, $jsParamList);


            $replace_arr = array(
                "{PagerButton}" => $pagerButton
            );
            $tempContent = strtr($tempContent, $replace_arr);
        }else{
            Template::RemoveCustomTag($tempContent, $listName);
            $tempContent = str_ireplace("{PagerButton}", Language::Load("document", 7), $tempContent);
        }
    }else{
        $resultJavaScript.=Control::GetJqueryMessage(Language::Load('information', 3));//站点id错误！
    }
    parent::ReplaceEnd($tempContent);
    $tempContent = str_ireplace("{ResultJavascript}", $resultJavaScript, $tempContent);
    $result = $tempContent;
    return $result;
    }
}
?>