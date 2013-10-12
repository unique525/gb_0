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

    private function GenCreate() {
        
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
            $tempContent = Template::Load("document/documentnews_list.html","common");
        } else {
            $tempContent = Template::Load("document/documentnews_slider_list.html","common");
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
            } else {
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

    private function AsyncUpdateSort() {
        $arrDocumentNewsId = Control::GetRequest("docnewssort", null);
        $documentNewsManageData = new DocumentNewsManageData();
        $documentNewsManageData->UpdateSort($arrDocumentNewsId);
    }

    /*
     * 修改文档状态  0 新稿 1 已编 2  返工 11 一审 12 二审 13 三审 14 终审 20 已否   
     */

    private function AsyncChangeState() {
        $documentNewsId = Control::GetRequest("documentnewsid", 0);
        $state = Control::GetRequest("state", -1);
        if ($documentNewsId > 0 && $state >= 0) {
            $documentNewsManageData = new DocumentNewsManageData();
            $documentChannelId = $documentNewsManageData->GetDocumentChannelID($documentNewsId);
            $adminUserId = Control::GetAdminUserId();
            $documentChannelManageData = new DocumentChannelManageData();
            $siteId = $documentChannelManageData->GetSiteId($documentChannelId);
////////////////////////////////////////////////////
///////////////判断是否有操作权限///////////////////
////////////////////////////////////////////////////
            $adminUserPopedomManageData = new AdminUserPopedomManageData();
            $can = TRUE;
            switch ($state) {
                case 2 :
                    $can = $adminUserPopedomManageData->CanRework($siteId, $documentChannelId, $adminUserId);
                    break;
                case 11 :
                    $can = $adminUserPopedomManageData->CanAudit1($siteId, $documentChannelId, $adminUserId);
                    break;
                case 12 :
                    $can = $adminUserPopedomManageData->CanAudit2($siteId, $documentChannelId, $adminUserId);
                    break;
                case 13 :
                    $can = $adminUserPopedomManageData->CanAudit3($siteId, $documentChannelId, $adminUserId);
                    break;
                case 14 :
                    $can = $adminUserPopedomManageData->CanAudit4($siteId, $documentChannelId, $adminUserId);
                    break;
                case 20 :
                    $can = $adminUserPopedomManageData->CanRefused($siteId, $documentChannelId, $adminUserId);
                    break;
            }
            if (!$can) {
                return -2;
            }
            //操作他人的权限
            $documentNewsAdminUserId = $documentNewsManageData->GetAdminUserId($documentNewsId);
            if ($documentNewsAdminUserId !== $adminUserId) { //操作人不是发布人
                $can = $adminUserPopedomManageData->CanDoOthers($siteId, $documentChannelId, $adminUserId);

                if (!$can) { //不能操作他人文档时，查询是否有可以操作同组人权限
                    $adminUserManageData = new AdminUserManageData(); //组内可操作他人 FOR芙蓉区食安网
                    $documentNewsAdminUserGroupId = $adminUserManageData->GetAdminUserGroupId($documentNewsAdminUserId);
                    $adminUserGroupId = $adminUserManageData->GetAdminUserGroupID($adminUserId);

                    if ($documentNewsAdminUserGroupId == $adminUserGroupId) { //是同一组时才进行判断
                        $can = $adminUserPopedomManageData->CanDoSameGroupOthers($siteId, $documentChannelId, $adminUserId);
                    }
                }

                if (!$can) {
                    return -2;
                }
            }
////////////////////////////////////////////////////
////////////////////////////////////////////////////
////////////////////////////////////////////////////
////////////////////////////////////////////////////
            $oldState = $documentNewsManageData->GetState($documentNewsId);
            if (($oldState === 30 || $oldState === 20) && intval($state) === 20) { //从发布状态改为已否状态，从FTP上删除文件及相关附件，重新发布相关频道
                //第1步，从FTP删除文档
                $publishDate = $documentNewsManageData->GetPublishDate($documentNewsId);
                $documentNewsContent = $documentNewsManageData->GetDocumentNewsContent($documentNewsId);
                $datePath = Format::DateStringToSimple($publishDate);
                $publishFileName = $documentNewsId . '.html';
                $rank = $documentChannelManageData->GetRank($documentChannelId);
                $publishPath = parent::GetPublishPath($documentChannelId, $rank);
                $hasftp = $documentChannelManageData->GetHasFtp($documentChannelId);
                $ftptype = 0; //HTML和相关CSS,IMAGE
                $despath = $publishPath . $datePath . DIRECTORY_SEPARATOR . $publishFileName;

                $isDel = parent::DelFtp($despath, $documentChannelId, $hasftp, $ftptype);

//有详细页面分页的，循环删除各个分页页面
                $arrnewscontent = explode("<!-- pagebreak -->", $documentNewsContent);
                if (count($arrnewscontent) > 0) { //有分页的内容
                    for ($cp = 0; $cp < count($arrnewscontent); $cp++) {
                        $publishFileName = $documentNewsId . '_' . ($cp + 1) . '.html';
                        $despath = "/" . $publishPath . $datePath . '/' . $publishFileName;
                        parent::DelFtp($despath, $documentChannelId, $hasftp, $ftptype);
                    }
                }
//第2步，从FTP删除上传文件
                $ftptype = 0; //HTML和相关CSS,IMAGE
                $uploadFileData = new UploadFileData();
                $tabletype = 1; //docnews
                $arrfiles = $uploadFileData->GetList($documentNewsId, $tabletype);
//取得相关的附件文件
                if (count($arrfiles) > 0) {
                    for ($i = 0; $i < count($arrfiles); $i++) {
                        $uploadFileName = $arrfiles[$i]["UploadFileName"];
                        $uploadFilePath = $arrfiles[$i]["UploadFilePath"];
                        parent::DelFtp($uploadFilePath . $uploadFileName, $documentChannelId, $hasftp, $ftptype);
                    }
                }
//联动发布所在频道和上级频道
                $documentChannelGen = new DocumentChannelGen();
                $documentChannelGen->PublishMuti($documentChannelId);
/////////////////////////////////////////////////////////////
////////////////xunsearch全文检索引擎 索引更新///////////////
/////////////////////////////////////////////////////////////
//                global $xunfile;
//                if (file_exists($xunfile)) {
//                    require_once $xunfile;
//                    try {
//                        $xs = new XS('icms');
//                        $index = $xs->index;
//                        $index->del($documentnewsid);
//                        $index->flush();
//                    } catch (XSException $e) {
//                        $error = strval($e);
//                    }
//                }
/////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////
            }
//修改状态
            $result = $documentNewsManageData->UpdateState($documentNewsId, $state);
//加入操作log
            $operatecontent = "DocumentNews：UpdateState id ：" . $documentNewsId . "；userid：" . Control::GetAdminUserID() . "；username；" . Control::GetAdminUserName() . "；oldstate：" . $oldState . "；tostate：" . $state . "；result：" . $result;
            $adminuserlogData = new AdminUserLogData();
            $adminuserlogData->Insert($operatecontent);
            return $result;
        } else {
            return -1;
        }
    }

}

?>
