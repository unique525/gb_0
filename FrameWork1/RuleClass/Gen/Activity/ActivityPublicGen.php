<?php

/**
 * 活动业务类（前台）
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
     * 生成产品详细页面
     * @return string 产品详细页面HTML
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
                    Template::RemoveCustomTag($tempContent, $listName);
                }
                $patterns = '/\{s_(.*?)\}/';
                $templateContent = preg_replace($patterns, "", $templateContent);
                parent::ReplaceSiteConfig($siteId,$templateContent);
                parent::ReplaceVisitCode($templateContent,$siteId,$channelId,VisitData::VISIT_TABLE_TYPE_ACTIVITY,$activityId);
                parent::ReplaceEnd($templateContent);
            }
        }
        return $templateContent;
    }
} 