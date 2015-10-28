<?php

/**
 * 公开访问 频道 生成类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_Gen_Channel
 * @author zhangchi
 */
class ChannelPublicGen extends BasePublicGen implements IBasePublicGen {

    /**
     * 引导方法
     * @return string 返回执行结果
     */
    public function GenPublic() {
        $result = "";

        $action = Control::GetRequest("a", "");
        switch ($action) {
            case "default":
                $result = self::GenDefault();
                break;
            case "list":
                $result = self::GenList();
                break;
        }

        $result = str_ireplace("{action}", $action, $result);

        return $result;
    }

    private function GenDefault() {

        $siteId = parent::GetSiteIdByDomain();

        $channelId = Control::GetRequest("channel_id",0);
        $templateTag=Control::GetRequest("temp","");
        $pageIndex = Control::GetRequest("p",0);
        $pageSize = Control::GetRequest("ps",0);
        if($pageIndex>1&&$pageSize>0){
            $tagTopCount = ($pageIndex-1)*$pageSize.",".$pageSize;
        }else{
            $tagTopCount="";
        }

        $defaultTemp = "default";

        $tempContent = parent::GetDynamicTemplateContent(
            $defaultTemp, $siteId, "", $templateMode);


        /*******************页面级的缓存 begin********************** */


        $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'default_page';
        $cacheFile = 'channel_default_'
            . '_site_id' . $siteId
            . $channelId
            . '_mode_' . $templateMode
            . '_p_' . $pageIndex
            . '_ps_' . $pageSize;
        $withCache = true;
        if($withCache){
            $pageCache = parent::GetCache($cacheDir, $cacheFile);

            if ($pageCache === false) {
                $result = self::getDefaultTemplateContent(
                    $siteId,
                    $channelId,
                    $templateTag,
                    $pageIndex,
                    $pageSize,
                    $tagTopCount,
                    $tempContent
                );
                parent::AddCache($cacheDir, $cacheFile, $result, 60);
            } else {
                $result = $pageCache;
            }
        }else{
            $result = self::getDefaultTemplateContent(
                $siteId,
                $channelId,
                $templateTag,
                $pageIndex,
                $pageSize,
                $tagTopCount,
                $tempContent
            );
        }

        /*******************页面级的缓存 end  ********************** */

        return $result;

    }


    private function getDefaultTemplateContent(
        $siteId,
        $channelId,
        $templateTag,
        $pageIndex,
        $pageSize,
        $tagTopCount,
        $tempContent
    ){
        parent::ReplaceFirst($tempContent);


        $channelPublicData = new ChannelPublicData();
        $currentChannelName = $channelPublicData->GetChannelName($channelId,true);
        $tempContent = str_ireplace("{CurrentChannelName}", $currentChannelName, $tempContent);


        $tempContent =  parent::ReplaceTemplate($tempContent,$tagTopCount);

//parent::ReplaceSiteConfig($siteId, $tempContent);


//分页
        $documentNewsPublicData=new DocumentNewsPublicData();
        $allCount=$documentNewsPublicData->GetCountInChannel($channelId);

        if($pageSize<=0){
            $pageSize=10;
        }
        if($pageIndex<=0){
            $pageIndex=1;
        }


        $navUrl = "/default.php?mod=channel&a=default&temp=$templateTag&channel_id=$channelId&p={0}&ps=$pageSize";
        $pagerTemplate = parent::GetDynamicTemplateContent("",$siteId,"pager");
        $pagerButton = Pager::ShowPageButton($pagerTemplate, $navUrl, $allCount, $pageSize, $pageIndex);

        $tempContent = str_ireplace("{dynamic_pager_button}", $pagerButton, $tempContent);


        parent::ReplaceSiteInfo($siteId, $tempContent);
        parent::ReplaceChannelInfo($channelId, $tempContent);
        parent::ReplaceEnd($tempContent);
        return $tempContent;
    }



    private function GenList() {
        $siteId = parent::GetSiteIdByDomain();
        $channelId = Control::GetRequest("channel_id",0);
        $templateTag = Control::GetRequest("temp","channel_list");
        $pageIndex = Control::GetRequest("p",0);
        $pageSize = Control::GetRequest("ps",0);
        if($pageIndex>1&&$pageSize>0){
            $tagTopCount = ($pageIndex-1)*$pageSize.",".$pageSize;
        }else{
            $tagTopCount="";
        }

        if ($channelId<=0){
            return "param error";
        }
        $channelPublicData = new ChannelPublicData();
        ////////////////////权限判断///////////////////
        $accessLimitType = $channelPublicData->GetAccessLimitType($channelId,true);

        if ($accessLimitType > 0){

            $accessLimitContent = $channelPublicData->GetAccessLimitContent($channelId,true);

            if (strlen($accessLimitContent)>0){

                switch($accessLimitType){

                    case ChannelData::CHANNEL_ACCESS_LIMIT_TYPE_USER_GROUP:
                        //按会员组加密

                        $userId = Control::GetUserId();

                        $canExplore = false;

                        if ($userId>0){

                            $userRolePublicData = new UserRolePublicData();

                            $userGroupId = $userRolePublicData->GetUserGroupId($siteId,$userId, true);

                            if($userGroupId>0){


                                $arrAccessLimitContent = explode(',',$accessLimitContent);

                                if(is_array($arrAccessLimitContent)
                                    && !empty($arrAccessLimitContent)){

                                    if (in_array($userGroupId, $arrAccessLimitContent)){
                                        $canExplore = true;
                                    }

                                }else{

                                    if($userGroupId == $accessLimitContent){
                                        $canExplore = true;
                                    }

                                }


                            }

                        }


                        if(!$canExplore){

                            $message = '<meta http-equiv="content-type" content="text/html;charset=utf-8" />
                            <meta name="viewport" content="width=device-width, initial-scale=1" />'.Language::Load('channel', 8);
                            $message = str_ireplace(
                                "{0}",
                                urlencode($_SERVER["PHP_SELF"]."?".$_SERVER['QUERY_STRING']),
                                $message
                            );

                            return $message;

                        }


                        break;



                }

            }
        }



        /////////////////////////////////////////////

        $defaultTemp = "default";


        $tempContent = parent::GetDynamicTemplateContent(
            $defaultTemp, $siteId, "", $templateMode);

        /*******************页面级的缓存 begin********************** */


        $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'default_page';
        $cacheFile = 'channel_list_'
            . '_site_id' . $siteId
            . $channelId
            . '_temp_' . $templateTag
            . '_mode_' . $templateMode
            . '_p_' . $pageIndex
            . '_ps_' . $pageSize;
        $withCache = true;
        if($withCache){
            $pageCache = parent::GetCache($cacheDir, $cacheFile);

            if ($pageCache === false) {
                $result = self::getListTemplateContent(
                    $siteId,
                    $channelId,
                    $templateTag,
                    $pageIndex,
                    $pageSize,
                    $tagTopCount,
                    $tempContent
                );
                parent::AddCache($cacheDir, $cacheFile, $result, 60);
            } else {
                $result = $pageCache;
            }
        }else{
            $result = self::getListTemplateContent(
                $siteId,
                $channelId,
                $templateTag,
                $pageIndex,
                $pageSize,
                $tagTopCount,
                $tempContent
            );
        }

        /*******************页面级的缓存 end  ********************** */

        return $result;

    }


    private function getListTemplateContent(
        $siteId,
        $channelId,
        $templateTag,
        $pageIndex,
        $pageSize,
        $tagTopCount,
        $tempContent
    ){
        $tempContent = str_ireplace("{ChannelId}", $channelId, $tempContent);
        parent::ReplaceFirst($tempContent);

        $channelPublicData = new ChannelPublicData();
        $currentChannelName = $channelPublicData->GetChannelName($channelId,true);
        $tempContent = str_ireplace("{CurrentChannelName}", $currentChannelName, $tempContent);
        $tempContent = str_ireplace("{ChannelName}", $currentChannelName, $tempContent);

        $tempContent =  parent::ReplaceTemplate($tempContent,$tagTopCount);

        //分页
        $documentNewsPublicData=new DocumentNewsPublicData();
        $allCount=$documentNewsPublicData->GetCountInChannel($channelId);

        if($pageSize<=0){
            $pageSize=10;
        }
        if($pageIndex<=0){
            $pageIndex=1;
        }


        $navUrl = "/default.php?mod=channel&a=list&temp=$templateTag&channel_id=$channelId&p={0}&ps=$pageSize";
        $pagerTemplate = parent::GetDynamicTemplateContent("",$siteId,"pager");
        $pagerButton = Pager::ShowPageButton($pagerTemplate, $navUrl, $allCount, $pageSize, $pageIndex);

        $tempContent = str_ireplace("{dynamic_pager_button}", $pagerButton, $tempContent);


        parent::ReplaceSiteInfo($siteId, $tempContent);
        parent::ReplaceChannelInfo($channelId, $tempContent);
        parent::ReplaceEnd($tempContent);
        $tempContent.="<!--".$pageSize."-->";
        return $tempContent;
    }
}
?>
