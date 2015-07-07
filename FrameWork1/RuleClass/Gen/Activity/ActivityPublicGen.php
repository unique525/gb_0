<?php

/**
 * 公共 活动 生成类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_Gen_Activity
 * @author 525
 */
class ActivityPublicGen extends BasePublicGen implements IBasePublicGen {

    /**
     * 活动id错误
     */
    const ACTIVITY_FALSE_ACTIVITY_ID = -1;
    /**
     * 用户id错误
     */
    const ACTIVITY_FALSE_USER_ID = -2;
    /**
     * 活动报名用户重复
     */
    const ACTIVITY_USER_ID_REPEATED = -3;

    /**
     * 引导方法
     * @return string 返回执行结果
     */
    public function GenPublic() {
        $result = "";

        $action = Control::GetRequest("a", "");
        switch ($action) {
            case "async_user_sign_up":
                $result = self::AsyncUserSignUp();
                break;
            case "async_get_list":
                $result = self::AsyncGetList();
                break;
            case "detail":
                $result = self::GenDetail();
                break;
        }

        $result = str_ireplace("{action}", $action, $result);

        return $result;
    }

    /**
     * 报名
     */
    private function AsyncUserSignUp(){
        $result="";
        $userId=Control::GetUserId();
        $activityId=Control::PostOrGetRequest("activity_id",-1);
        if($userId<0){
            $result=DefineCode::ACTIVITY_PUBLIC+self::ACTIVITY_FALSE_USER_ID; //用户id错误、未登录
            return Control::GetRequest("jsonpcallback","") . '('.$result.')';
            //$userId=1;
        }

        if($activityId>0){
            $activityUserPublicData= new ActivityUserPublicData();

            /**检查是否重复报名**/
            $count=$activityUserPublicData->IsRepeat($userId,$activityId);

            if($count==0){

                $createDate = date("Y-m-d H:i:s", time());
                $result=$activityUserPublicData->Create($userId,$activityId,$createDate);
                return Control::GetRequest("jsonpcallback","") . '('.$result.')';
            }else{
                $result=DefineCode::ACTIVITY_PUBLIC+self::ACTIVITY_USER_ID_REPEATED; //用户id已存在、重复报名
                return Control::GetRequest("jsonpcallback","") . '('.$result.')';
            }
        }else{
            $result=DefineCode::ACTIVITY_PUBLIC+self::ACTIVITY_FALSE_ACTIVITY_ID; //活动id错误
            return Control::GetRequest("jsonpcallback","") . '('.$result.')';
        }
    }

    /**
     * 生成活动详细页面
     * @return string 活动详细页面HTML
     */
    private function GenDetail()
    {
        $temp = Control::GetRequest("temp", "");
        $activityId = Control::GetRequest("activity_id", 0);
        $templateContent = "";
        $siteId = parent::GetSiteIdByDomain();

        if ($activityId > 0) {
            //$templateContent = self::loadDetailTemp();
            $templateContent = parent::GetDynamicTemplateContent("activity_detail");
            parent::ReplaceFirst($templateContent);

            if($siteId>0){
                parent::ReplaceSiteInfo($siteId, $templateContent);
            }

            //加载活动数据
            $activityPublicData = new ActivityPublicData();
            $arrOne = $activityPublicData->GetOne($activityId);
            Template::ReplaceOne($templateContent, $arrOne);
            if (count($arrOne) > 0) {
                $channelId = $arrOne["ChannelId"];
                $channelPublicData= new ChannelPublicData();
                $channelName=$channelPublicData->GetChannelName($channelId,true);
                $templateContent = str_ireplace("{ChannelName}", $channelName, $templateContent);

                //加载活动报名人员名单
                $activityUserPublicData=new ActivityUserPublicData();
                $arrActivityUser=$activityUserPublicData->GetListByActivityId($activityId);
                $listName = "activity_user_list";
                if(count($arrActivityUser)>0){
                    Template::ReplaceList($templateContent, $arrActivityUser, $listName);
                }else{
                    Template::RemoveCustomTag($templateContent, $listName);
                }
                $signUpCount=count($arrActivityUser);
                $templateContent = str_ireplace("{SignUpCount}", $signUpCount, $templateContent);
                $patterns = '/\{s_(.*?)\}/';
                $templateContent = preg_replace($patterns, "", $templateContent);
                parent::ReplaceSiteConfig($siteId,$templateContent);
                parent::ReplaceVisitCode($templateContent,$siteId,$channelId,VisitData::VISIT_TABLE_TYPE_ACTIVITY,$activityId);
                parent::ReplaceEnd($templateContent);
            }
        }
        return $templateContent;
    }

    /**
     * 生成活动列表页面
     * @return string 活动列表页面HTML
     */
    private function AsyncGetList(){
        $result = "";

        $channelId = Control::GetRequest("channel_id", 0);
        $pageSize = Control::GetRequest("ps", 20);
        $pageIndex = Control::GetRequest("p", 1);
        $pagerTempType = Control::GetRequest("ptt",1);
        $parentId=0;

        if ($pageIndex === 0) {
            $pageIndex = 1;
        }
        $searchKey = urldecode(Control::GetRequest("search_key", ""));
        $searchKey = Format::RemoveXSS($searchKey);

        if ($pageIndex > 0 && $channelId >= 0) {
            $pageBegin = ($pageIndex - 1) * $pageSize;
            $state = DocumentNewsData::STATE_PUBLISHED;
            $allCount = 0;
            $activityPublicData = new ActivityPublicData();
            $arrList = $activityPublicData->GetListForPager(
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

                $arrResult["result_list"] = $arrList ;
                $arrResult["pager_button"] = $pagerButton;
                $result = Format::FixJsonEncode($arrResult);

            }
        }


        return Control::GetRequest("jsonpcallback","") . '('.$result.')';
    }
} 