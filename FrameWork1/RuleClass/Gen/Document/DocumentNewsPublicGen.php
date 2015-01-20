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
            case "async_add_hit":
                $result = self::GenAsyncAddHit();
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


    /**
     * 记录广告点击
     * @return string
     */
    private function GenAsyncAddHit() {
        $result="";
            $documentNewsId = intval(Control::GetRequest("id", "0"));
            if ($documentNewsId > 0) {
                $documentNewsPublicData=new DocumentNewsPublicData();
                $result = $documentNewsPublicData->AddHit($documentNewsId);
                if($result>0){
                    //if (isset($_GET['jsonpcallback'])) {
                    //    echo Control::GetRequest("jsonpcallback","") . '([{ReCommon:"' . $result . '"}])';
                    //}
                }
            }
        return $result;
    }

} 