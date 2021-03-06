<?php
/**
 * 公开访问 资讯 生成类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_Gen_Document
 * @author zhangchi
 */
class DocumentNewsPublicGen extends BasePublicGen implements IBasePublicGen {

    /**
     * 引导方法
     * @return string 返回执行结果
     */
    public function GenPublic() {
        $result = "";
        $action = Control::GetRequest("a", "");
        switch ($action) {
            case "async_get_list":
                $result = self::AsyncGetList();
                break;
            case "async_get_list_for_push":
                $result = self::AsyncGetListForPush();
                break;
            case "async_get_rss":
                $result = self::AsyncGetListForXmlPush();
                break;
            case "async_add_hit":
                $result = self::GenAsyncAddHit();
                break;
            case "async_get_hit":
                $result = self::GenAsyncGetHit();
                break;
            case "async_add_and_get_hit":
                $result = self::GenAsyncAddAndGetHit();
                break;
            case "async_add_and_get_agree_count":
                $result = self::GenAsyncAddAndGetAgreeCount();
                break;
            case "async_get_agree_count":
                $result = self::GenAsyncGetAgreeCount();
                break;

        }
        return $result;
    }

    private function AsyncGetList(){
        $result = "";

        $channelId = Control::GetRequest("channel_id", 0);
        $pageSize = Control::GetRequest("ps", 20);
        $pageIndex = Control::GetRequest("p", 1);
        $parentId = Control::GetRequest("parent_id", 0);
        $pagerTempType = Control::GetRequest("ptt",1);

        if ($pageIndex === 0) {
            $pageIndex = 1;
        }
        $searchKey = urldecode(Control::GetRequest("search_key", ""));
        $searchKey = Format::RemoveXSS($searchKey);

        if ($pageIndex > 0 && $channelId >= 0) {
            $pageBegin = ($pageIndex - 1) * $pageSize;
            $state = DocumentNewsData::STATE_PUBLISHED;
            $allCount = 0;
            $documentNewsPublicData = new DocumentNewsPublicData();
            $arrList = $documentNewsPublicData->GetListForPager(
                $channelId,
                $pageBegin,
                $pageSize,
                $allCount,
                $state,
                $searchKey,
                $parentId
            );
            if (count($arrList) > 0) {

                $templateFileUrl = "pager/pager_style".$pagerTempType."_js.html";
                $templateName = "default";
                $templatePath = "front_template";
                $pagerTemplate = Template::Load($templateFileUrl, $templateName, $templatePath);


                $isJs = true;
                $navUrl = "";
                $jsFunctionName = "getDocumentNewsList";
                $jsParamList = ",$channelId,'". urlencode($searchKey) ."',$parentId,'". WEBAPP_DOMAIN ."'";
                $pageIndexName = "p";
                $pageSizeName = "pz";
                $showGoTo = TRUE;
                $isFront = TRUE;

                $templateFileUrl = "pager/pager_content_style".$pagerTempType."_js.html";
                $pagerContentTemplate = Template::Load($templateFileUrl, $templateName, $templatePath);

                $pagerButton = Pager::ShowPageButton(
                    $pagerTemplate,
                    $navUrl,
                    $allCount,
                    $pageSize,
                    $pageIndex,
                    $pagerTempType,
                    $isJs,
                    $jsFunctionName,
                    $jsParamList,
                    $pageIndexName,
                    $pageSizeName,
                    $showGoTo,
                    $isFront,
                    $pagerContentTemplate
                );


                //格式化arrList
                $resultArrList = null;
                foreach ($arrList as $columnValue) {

                    $directUrl = $columnValue['DirectUrl'];
                    $publishDate = Format::DateStringToSimple($columnValue['PublishDate']);

                    if(strlen($directUrl)>0){
                        $columnValue['DocumentNewsUrl'] = $directUrl;
                    }else{
                        $columnValue['DocumentNewsUrl'] =
                            '/h/'.$columnValue['ChannelId'].
                            '/'.$publishDate.
                            '/'.$columnValue['DocumentNewsId'].'.html';
                    }

                    $resultArrList[] = $columnValue;
                }

                $arrResult["result_list"] = $resultArrList;
                $arrResult["pager_button"] = $pagerButton;
                $result = Format::FixJsonEncode($arrResult);

            }
        }


        return Control::GetRequest("jsonpcallback","") . '('.$result.')';
    }


    private function AsyncGetListForPush(){
        $result = "";

        $channelId = Control::GetRequest("channel_id", 0);
        $pageSize = Control::GetRequest("ps", 20);
        $pageIndex = Control::GetRequest("p", 1);
        $parentId = Control::GetRequest("parent_id", 0);
        $pagerTempType = Control::GetRequest("ptt",1);

        if ($pageIndex === 0) {
            $pageIndex = 1;
        }
        $searchKey = urldecode(Control::GetRequest("search_key", ""));
        $searchKey = Format::RemoveXSS($searchKey);

        if ($pageIndex > 0 && $channelId >= 0) {
            $pageBegin = ($pageIndex - 1) * $pageSize;
            $state = DocumentNewsData::STATE_PUBLISHED;
            $allCount = 0;
            $documentNewsPublicData = new DocumentNewsPublicData();
            $arrList = $documentNewsPublicData->GetListForInterface(
                $channelId,
                $pageBegin,
                $pageSize,
                $allCount,
                $state,
                $searchKey,
                $parentId
            );
            if (count($arrList) > 0) {

                $templateFileUrl = "pager/pager_style".$pagerTempType."_js.html";
                $templateName = "default";
                $templatePath = "front_template";
                $pagerTemplate = Template::Load($templateFileUrl, $templateName, $templatePath);


                $isJs = true;
                $navUrl = "";
                $jsFunctionName = "getDocumentNewsList";
                $jsParamList = ",$channelId,'". urlencode($searchKey) ."',$parentId,'". WEBAPP_DOMAIN ."'";
                $pageIndexName = "p";
                $pageSizeName = "pz";
                $showGoTo = TRUE;
                $isFront = TRUE;

                $templateFileUrl = "pager/pager_content_style".$pagerTempType."_js.html";
                $pagerContentTemplate = Template::Load($templateFileUrl, $templateName, $templatePath);

                $pagerButton = Pager::ShowPageButton(
                    $pagerTemplate,
                    $navUrl,
                    $allCount,
                    $pageSize,
                    $pageIndex,
                    $pagerTempType,
                    $isJs,
                    $jsFunctionName,
                    $jsParamList,
                    $pageIndexName,
                    $pageSizeName,
                    $showGoTo,
                    $isFront,
                    $pagerContentTemplate
                );


                //格式化arrList
                $resultArrList = null;
                foreach ($arrList as $columnValue) {

                    $directUrl = $columnValue['DirectUrl'];
                    $publishDate = Format::DateStringToSimple($columnValue['PublishDate']);

                    if(strlen($directUrl)>0){
                        $columnValue['DocumentNewsUrl'] = $directUrl;
                    }else{
                        $columnValue['DocumentNewsUrl'] =
                            '/h/'.$columnValue['ChannelId'].
                            '/'.$publishDate.
                            '/'.$columnValue['DocumentNewsId'].'.html';
                    }

                    $resultArrList[] = $columnValue;
                }

                $arrResult["result_list"] = $resultArrList;
                $arrResult["pager_button"] = $pagerButton;
                $result = Format::FixJsonEncode($arrResult);

            }
        }


        return Control::GetRequest("jsonpcallback","") . '('.$result.')';
    }
    /**
     * 记录点击
     * @return string
     */
    private function GenAsyncAddHit() {
        $result="";
        $documentNewsId = intval(Control::GetRequest("document_news_id", "0"));
        if($documentNewsId<=0){
            $documentNewsId = intval(Control::GetRequest("id", "0"));
        }
        if ($documentNewsId > 0) {
            $documentNewsPublicData=new DocumentNewsPublicData();
            $result = $documentNewsPublicData->AddHit($documentNewsId);
            if($result>0){
                if (isset($_GET['jsonpcallback'])) {
                    echo Control::GetRequest("jsonpcallback","") . '([{ReCommon:"' . $result . '"}])';
                }
                $arrayOne = $documentNewsPublicData->GetHit($documentNewsId);
                $result=$arrayOne["Hit"]+$arrayOne["VirtualHit"];
            }
        }
        return $result;
    }
    /**
     * 获取点击
     * @return string
     */
    private function GenAsyncGetHit() {
        $result=0;
            $documentNewsId = intval(Control::GetRequest("document_news_id", "0"));
            if($documentNewsId<=0){
                $documentNewsId = intval(Control::GetRequest("id", "0"));
            }

            if ($documentNewsId > 0) {
                $documentNewsPublicData=new DocumentNewsPublicData();
                $arrayOne = $documentNewsPublicData->GetHit($documentNewsId);
                if($arrayOne!=null){
                    $result=$arrayOne["Hit"]+$arrayOne["VirtualHit"];
                }
            }
        return Control::GetRequest("jsonpcallback","") . '('.$result.')';
    }

    private function GenAsyncAddAndGetHit(){
        $result=0;
        $documentNewsId = intval(Control::GetRequest("document_news_id", "0"));
        if($documentNewsId<=0){
            $documentNewsId = intval(Control::GetRequest("id", "0"));
        }

        if ($documentNewsId > 0) {
            $documentNewsPublicData=new DocumentNewsPublicData();
            $documentNewsPublicData->AddHit($documentNewsId);
            $arrayOne = $documentNewsPublicData->GetHit($documentNewsId);
            if($arrayOne!=null){
                $result=$arrayOne["Hit"]+$arrayOne["VirtualHit"];
            }
        }
        return $result;
    }


    private function AsyncGetListForXmlPush() {
        $result="";
        $siteId = Control::GetRequest("site_id", 0);
        $channelId = Control::GetRequest("channel_id", 0);
        $topCount = Control::GetRequest("top", 0);
        $showIndex = Control::GetRequest("show_index", 0);
        $childType=Control::GetRequest("child_type", "self");
        $state=30;
        $order=0;

        if ($siteId > 0) {

            $channelPublicData=new ChannelPublicData();
            $channelName=$channelPublicData->GetChannelName($channelId,true);
            $documentNewsPublicData = new DocumentNewsPublicData();
            switch($childType){
                case "self":
                    $arrList = $documentNewsPublicData->GetNewList($siteId, $channelId,$topCount,$state,$order,$showIndex);
                    break;
                case "child":
                    $channelPublicData=new ChannelPublicData();
                    $strChannelId=$channelPublicData->GetChildrenChannelId($channelId,true);
                    $arrList = $documentNewsPublicData->GetListOfChild($strChannelId,$topCount,$state,$order,$showIndex);
                    break;
                case "grandson":
                    $channelPublicData=new ChannelPublicData();
                    $strChannelId=$channelPublicData->GetChildrenChannelId($channelId,true);
                    $arrList = $documentNewsPublicData->GetListOfChild($strChannelId,$topCount,$state,$order,$showIndex);
                    break;
                default:
                    $arrList = $documentNewsPublicData->GetNewList($channelId,$topCount,$state,$order,$showIndex);
                    break;
            }
            if (count($arrList) > 0) {

                $resultArrList=array();
                //格式化 url
                foreach ($arrList as $columnValue) {

                    $directUrl = $columnValue['DirectUrl'];
                    $publishDate = Format::DateStringToSimple($columnValue['PublishDate']);

                    if(strlen($directUrl)>0){
                        $columnValue['DocumentNewsUrl'] = $directUrl;
                    }else{
                        $sitePublicData=new SitePublicData();
                        $siteUrl=$sitePublicData->GetSiteUrl($siteId,true);

                        //暂时解决www.changsha.cn资讯热点没有域名的问题
                        if($siteUrl==''){
                            $siteUrl='http://news.changsha.cn/';
                        }
                        //end

                        $columnValue['DocumentNewsUrl'] =
                            $siteUrl.
                            '/h/'.$columnValue['ChannelId'].
                            '/'.$publishDate.
                            '/'.$columnValue['DocumentNewsId'].'.html';
                    }

                    $resultArrList[] = $columnValue;
                }

                $channelTitle = $channelName;
                $channelDescription = "";
                $channelLink = "";
                $language = "";
                $result = XMLGenerator::GenForDocumentNews($channelTitle, $channelDescription, $channelLink, $language, $resultArrList);
            }
        }
        return $result;
    }


    /**
     * 增加并返回赞同数
     * @return string
     */
    private function GenAsyncAddAndGetAgreeCount() {
        $result="";
        $documentNewsId = intval(Control::GetRequest("document_news_id", "0"));
        if ($documentNewsId > 0) {
            $documentNewsPublicData=new DocumentNewsPublicData();

            $documentNewsPublicData->AddAgreeCount($documentNewsId);

            $result = $documentNewsPublicData->GetAgreeCount($documentNewsId);
        }
        return $result;
    }


    /**
     * 增加并返回赞同数
     * @return string
     */
    private function GenAsyncGetAgreeCount() {
        $result="";
        $documentNewsId = intval(Control::GetRequest("document_news_id", "0"));
        if ($documentNewsId > 0) {
            $documentNewsPublicData=new DocumentNewsPublicData();

            $result = $documentNewsPublicData->GetAgreeCount($documentNewsId);
        }
        return $result;
    }

} 