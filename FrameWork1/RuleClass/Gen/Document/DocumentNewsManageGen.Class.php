<?php

/**
 * 后台Gen总引导类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_Gen
 * @author zhangchi
 */
class DocumentNewsManageGen extends BaseManageGen implements IBaseManageGen {

    /**
     * 引导方法
     * @return string 返回执行结果
     */
    public function Gen() {
        $result = "";
        $method = Control::GetRequest("m", "");
        switch ($method) {
            case "create":
                $result = self::GenCreate();
                break;
            case "modify":
                $result = self::GenModify();
                break;
            case "removebin":
                $result = self::GenRemoveBin();
                break;
            case "listformanage":
                $result = self::GenListForManage();
                break;
            case "async_updatesort":
                self::AsyncUpdateSort();
                break;
            case "async_changestate":
                $result = self::AsyncChangeState();
                break;
        }
        $result = str_ireplace("{method}", $method, $result);
        return $result;
    }

    /*
     * 生成资讯管理新增页面
     */
    private function GenCreate(){
        
    }
    
    /*
     * 生成资讯管理列表页面
     */
    private function GenListForManage() {
        $documentChannelId = Control::GetRequest("cid", 0);
        if ($documentChannelId <= 0) {
            return "";
        }
        $adminUserId = Control::GetAdminUserId();
        $documentChannelManageData = new DocumentChannelManageData();
        $siteId = $documentChannelManageData->GetSiteId($documentChannelId);

        ///////////////判断是否有操作权限///////////////////
        $adminUserPopedomManageData = new AdminUserPopedomManageData();
        $op = "explore";
        $hasPopedom = parent::CheckAdminUserPopedom($adminUserPopedomManageData, $adminUserId, $documentChannelId, $siteId, $op);
        if (!$hasPopedom) {
            Control::ShowMessage(Language::Load('document', 26));
            return "";
        }
        $adminUserName = Control::GetAdminUserName();
        $clientIp = Control::GetIp();

        $siteManageData = new SiteManageData();
        $siteUrl = $siteManageData->GetSiteUrl($siteId);
        $_pos = stripos($siteUrl, "http://");
        if ($_pos === false) {
            $siteUrl = "http://" . $siteUrl;
        }
        //Language::Load('document', 7);
        //
        //load template
        $documentChannelType = $documentChannelManageData->GetDocumentChannelType($documentChannelId);
        if ($documentChannelType === 1) {
            $tempContent = Template::Load("document/documentnews_list.html");
        } else {
            $tempContent = Template::Load("document/documentnews_slider_list.html");
        }

        parent::ReplaceFirst($tempContent);

        ////////////////////////////////////////////////////
        ///////////////输出权限到页面///////////////////
        ////////////////////////////////////////////////////
        $canRework = $adminUserPopedomManageData->CanRework($siteId, $documentChannelId, $adminUserId);
        $canAudit1 = $adminUserPopedomManageData->CanAudit1($siteId, $documentChannelId, $adminUserId);
        $canAudit2 = $adminUserPopedomManageData->CanAudit2($siteId, $documentChannelId, $adminUserId);
        $canAudit3 = $adminUserPopedomManageData->CanAudit3($siteId, $documentChannelId, $adminUserId);
        $canAudit4 = $adminUserPopedomManageData->CanAudit4($siteId, $documentChannelId, $adminUserId);
        $canRefused = $adminUserPopedomManageData->CanRefused($siteId, $documentChannelId, $adminUserId);
        $canPublish = $adminUserPopedomManageData->CanPublish($siteId, $documentChannelId, $adminUserId);
        $canModify = $adminUserPopedomManageData->CanModify($siteId, $documentChannelId, $adminUserId);
        $canCreate = $adminUserPopedomManageData->CanCreate($siteId, $documentChannelId, $adminUserId);


        $tempContent = str_ireplace("{CanRework}", $canRework == 1 ? "" : "display:none", $tempContent);
        $tempContent = str_ireplace("{CanAudit1}", $canAudit1 == 1 ? "" : "display:none", $tempContent);
        $tempContent = str_ireplace("{CanAudit2}", $canAudit2 == 1 ? "" : "display:none", $tempContent);
        $tempContent = str_ireplace("{CanAudit3}", $canAudit3 == 1 ? "" : "display:none", $tempContent);
        $tempContent = str_ireplace("{CanAudit4}", $canAudit4 == 1 ? "" : "display:none", $tempContent);
        $tempContent = str_ireplace("{CanRefused}", $canRefused == 1 ? "" : "display:none", $tempContent);
        $tempContent = str_ireplace("{CanPublish}", $canPublish == 1 ? "" : "display:none", $tempContent);
        $tempContent = str_ireplace("{CanModify}", $canModify == 1 ? "" : "display:none", $tempContent);
        $tempContent = str_ireplace("{CanCreate}", $canCreate == 1 ? "" : "display:none", $tempContent);

        //$type = Control::GetRequest("type", "");
        $pageSize = Control::GetRequest("ps", 20);
        if ($documentChannelType !== 1) {
            $pageSize = 16;
        }
        $pageIndex = Control::GetRequest("p", 1);
        $searchKey = Control::GetRequest("searchkey", "");
        $searchKey = urldecode($searchKey);
        $searchTypeBox = Control::GetRequest("searchtype_box", "");
        if (isset($searchKey) && strlen($searchKey) > 0) {
            $can = $adminUserPopedomManageData->CanSearch($siteId, $documentChannelId, $adminUserId);
            if (!$can) {
                Control::ShowMessage(Language::Load('document', 26));
                return "";
            }
        }
        if ($pageIndex > 0 && $documentChannelId > 0) {
            $pageBegin = ($pageIndex - 1) * $pageSize;
            $listName = "documentnewslist";
            $allCount = 0;
            $isSelf = Control::GetRequest("isself", 0);
            $documentNewsManageData = new DocumentNewsManageData();
            $arrDocumentNewsList = $documentNewsManageData->GetListForManage($documentChannelId, $pageBegin, $pageSize, $allCount, $searchKey, $searchTypeBox, $isSelf, $adminUserId);
            
            if (count($arrDocumentNewsList) > 0) {
                Template::ReplaceList($tempContent, $arrDocumentNewsList, $listName);
                
                $styleNumber = 1;
                $pagerTemplate = Template::Load("../common/pager_style$styleNumber.html");
                $isJs = FALSE;
                $navUrl = "default.php?secu=manage&mod=documentnews&m=listformanage&cid=$documentChannelId&p={0}&ps=$pageSize&isself=$isSelf";
                $jsFunctionName = "";
                $jsParamList = "";
                $pagerButton = Pager::ShowPageButton($pagerTemplate, $navUrl, $allCount, $pageSize, $pageIndex, $styleNumber, $isJs, $jsFunctionName, $jsParamList);

                $tempContent = str_ireplace("{documentchannelid}", $documentChannelId, $tempContent);
                $tempContent = str_ireplace("{cid}", $documentChannelId, $tempContent);
                $tempContent = str_ireplace("{pagerbutton}", $pagerButton, $tempContent);
                $tempContent = str_ireplace("{siteurl}", $siteUrl, $tempContent);
            }else{
                Template::RemoveCMS($tempContent, $listName);
                $tempContent = str_ireplace("{pagerbutton}", Language::Load("document", 7), $tempContent);
            }
        }

        parent::ReplaceEnd($tempContent);
        return $tempContent;
    }

    /*
     * 更新文档的排序（前台拖动时使用）
     */
    private function AsyncUpdateSort(){
        $arrDocumentNewsId = Control::GetRequest("docnewssort", null);
        $documentNewsManageData = new DocumentNewsManageData();
        $documentNewsManageData->UpdateSort($arrDocumentNewsId);
    }
    
    private function AsyncChangeState(){
        
    }
}

?>
