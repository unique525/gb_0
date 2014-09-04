<?php

/**
 * 活动表单页面生成类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_Gen_Activity
 * @author 525
 */
class ActivityManageGen extends BaseManageGen implements IBaseManageGen {


    /**
     * 表单字段id错误
     */
    const FALSE_CUSTOM_FORM_FIELD_ID = -1;
    /**
     * 写入、修改数据库操作失败
     */
    const INSERT_OR_UPDATE_FAILED = -2;
    /**
     * 表单id错误
     */
    const FALSE_CUSTOM_FORM_ID = -3;



    /**
     * 主生成方法(继承接口)
     * @return string 返回输出的HTML内容
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
        $replace_arr = array(
            "{method}" => $method
        );

        $result = strtr($result, $replace_arr);
        return $result;
    }

    /**
     * 新增活动
     * @return string 执行结果
     */
    private function GenCreate(){
        $result = '';
        $activityType = Control::GetRequest("activity_type", 0);
        switch ($activityType) {
            //case 0:
            //    $result = self::GenNew_ActivityType_1($activityType);
            //    break;
            default:
                $result = self::CreateDefaultActivity(0);
                break;

        }
        return $result;
    }

    private function GenModify(){

    }
    /**
     * 活动分页列表
     * @return string 列表页面html
     */
    private function GenList(){
        $result = Language::Load('document', 7);
        $resultJavaScript="";
        $tempContent = Template::Load("activity/activity_list.html","common");
        $channelId = Control::GetRequest("channel_id", 0);
        $activityClass = Control::GetRequest("activity_class", 0);
        $state = Control::GetRequest("state", -1);
        $pageSize = Control::GetRequest("ps", 20);
        $pageIndex = Control::GetRequest("p", 1);
        $searchKey = Control::GetRequest("search_key", "");
        $searchKey = urldecode($searchKey);

        if ($pageIndex > 0 && $channelId > 0) {
            $pageBegin = ($pageIndex - 1) * $pageSize;
            $listName = "activity";
            $allCount = 0;
            $activityData = new ActivityManageData();
            $arrList = $activityData->GetListPager($channelId, $pageBegin, $pageSize, $allCount, $searchKey, $activityClass, $state);
            if ($arrList != null && count($arrList) > 0) {
                Template::ReplaceList($tempContent, $arrList, $listName);

                $styleNumber = 1;
                $pagerTemplate = Template::Load("pager/pager_style".$styleNumber.".html", "common");
                $isJs = FALSE;
                $navUrl = "default.php?secu=manage&mod=activity&m=list&channel_id=".$channelId."&p={0}&ps=".$pageSize;
                $jsFunctionName = "";
                $jsParamList = "";
                $pagerButton = Pager::ShowPageButton($pagerTemplate, $navUrl, $allCount, $pageSize, $pageIndex, $styleNumber, $isJs, $jsFunctionName, $jsParamList);



                $replace_arr = array(
                    "{PagerButton}" => $pagerButton
                );
                $tempContent = strtr($tempContent, $replace_arr);
                parent::ReplaceEnd($tempContent);
                $tempContent = str_ireplace("{ResultJavascript}", $resultJavaScript, $tempContent);
                $result = $tempContent;
            }
        }
        return $result;
    }

    private function CreateDefaultActivity($activityType){
            $tempContent = Template::Load("activity/activity_".$activityType."_deal.html","common");
            $resultJavaScript="";
            $manageUserId = Control::GetManageUserID();
            $manageUsername = Control::GetManageUserName();
            $channelId = Control::GetRequest("channel_id", 0);
            $tab_index = Control::GetRequest("tab_index", 1);
            parent::ReplaceFirst($tempContent);
            if (intval($channelId) > 0) {
                if (!empty($_POST)) {
                    $activityManageData = new ActivityManageData();
                    $newActivityId = $activityManageData->Create($_POST);
                    if($newActivityId>0){
                        Control::ShowMessage(Language::Load('document', 1));
                        $closeTab = Control::PostRequest("CloseTab",0);
                        if($closeTab == 1){
                            $resultJavaScript .= Control::GetCloseTab();
                        }else{
                            Control::GoUrl($_SERVER["PHP_SELF"]."?".$_SERVER['QUERY_STRING']);
                            //Control::GoUrl($_SERVER["PHP_SELF"].'?secu=manage&mod=custom_form&m=create');
                        }
                    }else{
                        return DefineCode::ACTIVITY_MANAGE + self::INSERT_OR_UPDATE_FAILED;
                    }
                    //titlePic
                    //$fileElementName = "fileToUpload";
                    //$filetype = 7; //活动类activity
                    //$commongen = new CommonGen();
                    //$titlepicpath = $commongen->UploadFile($fileElementName, $filetype, 1, $uploadfileid);
                    //$titlepicpath = str_ireplace("..", "", $titlepicpath);
//

                    //发布者加入活动
                    $userId = Control::PostRequest("f_UserId", "");
                    $state = 14;
                    $createdate = date("Y-m-d H:i:s", time());
                    $activityUserManageData = new ActivityUserManageData();
                    $activityUserManageData->InsertUser($newActivityId, $userId, $createdate, $state);
                    $activityManageData->UpdateJoin($newActivityId); //更新审核人数
                    $activityManageData->UpdateApply($newActivityId); //更新申请人数
                    /////////////发布模式处理

                    $channelManageData = new ChannelManageData();
                    $_publishType = $channelManageData->GetPublishType($channelId,FALSE);
                    if ($_publishType > 0) {
                        switch ($_publishType) {
                            case 1: //自动发布新稿
                                //修改文档状态为终审
                                $state = 14;
                                $activityManageData->UpdateState($newActivityId, $state);
                                self::Publish($newActivityId);
                                break;
                        }
                    }
                    ///////////////////////////


                }


                //取活动class
                /*$activityClassData = new ActivityClassData();
                $listName = "activity_class_list";
                $listOfClassArray = $activityClassData->GetActivityClassList($channelId, $activityType);
                //没有找到就默认增加分类
                if ($listOfClassArray == null && count($listOfClassArray) <= 0) {
                    $channelManageData = new ChannelManageData();
                    $siteid = $channelManageData->GetSiteId($channelId,FALSE);
                    $activityClassName = "默认";
                    $state = 0;
                    $classId=$activityClassData->CreateInt($siteid, $channelId, $activityClassName, $state, $activityType);
                    if($classId<0)
                        Control::ShowMessage(Language::Load('custom_form', abs($classId)));//警告：新增活动类别失败！
                    $listOfClassArray = $activityClassData->GetActivityClass($channelId, $activityType);
                }

                Template::ReplaceList($tempContent, $listOfClassArray, $listName);*/



                $manageUserData = new ManageUserManageData();
                $userId = $manageUserData->GetUserID($manageUserId); //取后台管理员挂接的USERId号
                if (intval($userId) <= 0) {
                    $resultJavaScript .= Control::GetCloseTab();
                }


                $userData = new UserManageData();
                $userName = $userData->GetUserName($userId,FALSE);
                $replace_arr = array(
                    "{ChannelId}" => $channelId,
                    "{ActivityType}" => $activityType,
                    "{Sort}" => 0,
                    "{Hit}" => 0,
                    "{ActivityName}" => "",
                    "{ActivityContent}" => "",
                    "{ActivityPlace}" => "",
                    "{MeetingPlace}" => "",
                    "{ActivityId}" => 0,
                    "{ActivityNotice}" => "",
                    "{UserLimit}" => 0,
                    "{ActivityIntro}" => "",
                    "{ActivityCost}" => 0,
                    "{PhoneOfLeader}" => "",
                    "{QQOfLeader}" => "",
                    "{RecLevel}" => 0,
                    "{UserId}" => $userId,
                    "{UserName}" => $userName,
                    "{CreateDate}" => "",
                    "{StartTime}" => "", //活动时间(集合)
                    "{EndTime}" => "", //活动时间(结束)
                    "{SignUpStartTime}" => "", //报名截止时间
                    "{SignUpDeadLine}" => "", //报名开始时间
                    "{StartShowHour}" => "",
                    "{StartShowMinute}" => "",
                    "{StartShowSecond}" => "",
                    "{SignUpStartShowHour}" => "",
                    "{SignUpStartShowMinute}" => "",
                    "{SignUpStartShowSecond}" => "",
                    "{EndTimeShowHour}" => "",
                    "{EndTimeShowMinute}" => "",
                    "{EndTimeShowSecond}" => "",
                    "{SignUpDeadLineShowHour}" => "",
                    "{SignUpDeadLineShowMinute}" => "",
                    "{SignUpDeadLineShowSecond}" => "",
                    "{webapp_domain}" => WEBAPP_DOMAIN,
                    "{TitlePic}" => "",
                    "{ManageUserId}" => $manageUserId,
                    "{ManageUserName}" => $manageUsername,
                    "{UploadFile}" => "",
                    "{display}" => "",
                    "{TabIndex}" => $tab_index
                );
                $tempContent = strtr($tempContent, $replace_arr);

                //Template::ReplaceOne($tempContent, $arrList, 1);
                //替换掉{s XXX}的内容
                $patterns = "/\{s(.*?)\}/";
                $tempContent = preg_replace($patterns, "", $tempContent);
            } else {
                return "";
            }
            parent::ReplaceEnd($tempContent);
            $tempContent = str_ireplace("{ResultJavascript}", $resultJavaScript, $tempContent);
            return $tempContent;
    }
}

?>