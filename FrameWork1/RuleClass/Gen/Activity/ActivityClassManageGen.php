<?php

/**
 * 活动类型管理类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_Gen_Activity
 * @author 525
 */
class ActivityClassManageGen extends BaseManageGen implements IBaseManageGen {


    /**
     * 活动类型id错误
     */
    const ACTIVITY_CLASS_FALSE_ACTIVITY_CLASS_ID = -1;
    /**
     * 写入、修改数据库操作失败
     */
    const ACTIVITY_CLASS_INSERT_OR_UPDATE_FAILED = -2;
    /**
     * 查询单条活动类型结果为空
     */
    const ACTIVITY_CLASS_GET_ONE_RESULT_NULL = -3;
    /**
     * 频道id错误
     */
    const ACTIVITY_CLASS_FALSE_CHANNEL_OR_SITE_ID = -4;


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
            case "modify_state":
                $result = self::ModifyState();
                break;
        }
        $replace_arr = array(
            "{method}" => $method
        );

        $result = strtr($result, $replace_arr);
        return $result;
    }

    /**
     * 新增活动类型
     * @return string 执行结果
     */
    private function GenCreate(){
        $tempContent = Template::Load("activity/activity_class_deal.html","common");
        $resultJavaScript="";
        $channelId = Control::GetRequest("channel_id", 0);
        $siteId = Control::GetRequest("site_id", 0);
        $tabIndex = Control::GetRequest("tab_index", 1);


        if (intval($channelId) > 0 && intval($siteId) > 0) {
            parent::ReplaceFirst($tempContent);

            if(!empty($_POST)){
                $activityClassManageData=new ActivityClassManageData();


                $activityClassName=Control::PostRequest("f_ActivityClassName",FALSE);
                $hasOne=$activityClassManageData->GetCount($activityClassName,$channelId);
                if($hasOne<=0){

                $newActivityClassId=$activityClassManageData->Create($_POST);
                    //记入操作log
                    $operateContent = "Create Activity_class：ActivityClassId：" . $newActivityClassId .",POST FORM:".implode("|",$_POST).";\r\nResult:". $newActivityClassId;
                    self::CreateManageUserLog($operateContent);

                if($newActivityClassId>0){
                    Control::ShowMessage(Language::Load('activity', 18));
                    $closeTab = Control::PostRequest("CloseTab",0);
                    if($closeTab == 1){
                        $resultJavaScript .= Control::GetCloseTab();
                    }else{
                        Control::GoUrl($_SERVER["PHP_SELF"]."?".$_SERVER['QUERY_STRING']);
                    }
                }else{
                    return DefineCode::ACTIVITY_CLASS_MANAGE+self::ACTIVITY_CLASS_INSERT_OR_UPDATE_FAILED;
                }

                }else{
                    $resultJavaScript.=Control::GetJqueryMessage(Language::Load('activity', 1));//失败，活动类型已存在!
                }
            }

            $channelManageData=new ChannelManageData();
            $channelName=$channelManageData->GetChannelName($channelId,FALSE);
            $replace_arr = array(
                "{ChannelName}" => $channelName,
                "{ChannelId}" => $channelId,
                "{TabIndex}" => $tabIndex,
                "{SiteId}" => $siteId,
                "{display}" => ""
            );
            $tempContent = strtr($tempContent, $replace_arr);

            $activityClassManageData=new ActivityClassManageData();
            $fieldsOfVote = $activityClassManageData->GetFields();
            parent::ReplaceWhenCreate($tempContent, $fieldsOfVote);

            //替换掉{s XXX}的内容
            $patterns = '/\{s_(.*?)\}/';
            $tempContent = preg_replace($patterns, "", $tempContent);
        }else{
            return DefineCode::ACTIVITY_CLASS_MANAGE+self::ACTIVITY_CLASS_FALSE_CHANNEL_OR_SITE_ID;
        }

        parent::ReplaceEnd($tempContent);
        $tempContent = str_ireplace("{ResultJavascript}", $resultJavaScript, $tempContent);
        return $tempContent;
    }

    private function GenModify(){
        $tempContent = Template::Load("activity/activity_class_deal.html","common");
        $resultJavaScript="";
        $channelId = Control::GetRequest("channel_id", 0);
        $siteId = Control::GetRequest("site_id", 0);
        $tabIndex = Control::GetRequest("tab_index", 1);
        $activityClassId=Control::GetRequest("activity_class_id", 1);
        $activityClassManageData=new ActivityClassManageData();

        if($activityClassId>0){
            if (intval($channelId) > 0) {
                parent::ReplaceFirst($tempContent);

                if(!empty($_POST)){
                    $activityClassName=Control::PostRequest("f_ActivityClassName",FALSE);
                    $hasOne=$activityClassManageData->GetCount($activityClassName,$channelId);
                    if($hasOne<=0){
                    $newActivityClassId=$activityClassManageData->Modify($_POST,$activityClassId);
                        //记入操作log
                        $operateContent = "Modify ActivityClass：ActivityClassId：" . $newActivityClassId .",POST FORM:".implode("|",$_POST).";\r\nResult:". $newActivityClassId;
                        self::CreateManageUserLog($operateContent);
                    if($newActivityClassId>0){


                        Control::ShowMessage(Language::Load('activity', 18));
                        $closeTab = Control::PostRequest("CloseTab",0);
                        if($closeTab == 1){
                            $resultJavaScript .= Control::GetCloseTab();
                        }else{
                            Control::GoUrl($_SERVER["PHP_SELF"]."?".$_SERVER['QUERY_STRING']);
                        }
                    }else{
                        return DefineCode::ACTIVITY_CLASS_MANAGE+self::ACTIVITY_CLASS_INSERT_OR_UPDATE_FAILED;
                    }
                    }else{
                        $resultJavaScript.=Control::GetJqueryMessage(Language::Load('activity', 1));//失败，活动类型已存在!
                    }
                }

                //加载原数据
                $arrOne=$activityClassManageData->GetOne($activityClassId);
                if(!empty($arrOne)){
                    Template::ReplaceOne($tempContent, $arrOne);
                }else{
                    return DefineCode::ACTIVITY_CLASS_MANAGE + self::ACTIVITY_CLASS_GET_ONE_RESULT_NULL . "id=" . $activityClassId;
                }

                $channelManageData=new ChannelManageData();
                $channelName=$channelManageData->GetChannelName($channelId,FALSE);
                $replace_arr = array(
                    "{ChannelName}" => $channelName,
                    "{ChannelId}" => $channelId,
                    "{TabIndex}" => $tabIndex,
                    "{SiteId}" => $siteId,
                    "{display}" => "none"
                );
                $tempContent = strtr($tempContent, $replace_arr);

                $activityClassManageData=new ActivityClassManageData();
                $fieldsOfVote = $activityClassManageData->GetFields();
                parent::ReplaceWhenCreate($tempContent, $fieldsOfVote);

                //替换掉{s XXX}的内容
                $patterns = '/\{s_(.*?)\}/';
                $tempContent = preg_replace($patterns, "", $tempContent);
            }else{
                return DefineCode::ACTIVITY_CLASS_MANAGE+self::ACTIVITY_CLASS_FALSE_CHANNEL_OR_SITE_ID;
            }
        }else{
            return DefineCode::ACTIVITY_CLASS_MANAGE+self::ACTIVITY_CLASS_FALSE_ACTIVITY_CLASS_ID;
        }

        parent::ReplaceEnd($tempContent);
        $tempContent = str_ireplace("{ResultJavascript}", $resultJavaScript, $tempContent);
        return $tempContent;
    }
    /**
     * 活动类型分页列表
     * @return string 列表页面html
     */
    private function GenList(){
        $result=-1;
        $resultJavaScript="";
        $tempContent = Template::Load("activity/activity_class_list.html","common");
        $channelId = Control::GetRequest("channel_id", 0);
        $pageSize = Control::GetRequest("ps", 20);
        $pageIndex = Control::GetRequest("p", 1);

        if ($pageIndex > 0 && $channelId > 0) {
            $pageBegin = ($pageIndex - 1) * $pageSize;
            $listName = "activity_class";
            $allCount = 0;
            $activityClassManageData = new ActivityClassManageData();
            $listOfClassArray = $activityClassManageData->GetListPager($channelId, $pageBegin, $pageSize, $allCount);

            if(count($listOfClassArray)>0){
                Template::ReplaceList($tempContent, $listOfClassArray, $listName);

                $styleNumber = 1;
                $pagerTemplate = Template::Load("pager/pager_style$styleNumber.html", "common");
                $isJs = FALSE;
                $navUrl = "default.php?secu=manage&mod=activity&m=list&channel_id=$channelId&p={0}&ps=$pageSize";
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
            };
            parent::ReplaceEnd($tempContent);
            $tempContent = str_ireplace("{ResultJavascript}", $resultJavaScript, $tempContent);
            $result=$tempContent;
        }else{
            $result = DefineCode::ACTIVITY_CLASS_MANAGE + self::ACTIVITY_CLASS_FALSE_CHANNEL_OR_SITE_ID;
        }
        return $result;
    }

    /**
     * 修改活动状态
     * @return string 修改结果
     */
    private function ModifyState()
    {
        //$result = -1;
        $activityClassId = Control::GetRequest("table_id", 0);
        $state = Control::GetRequest("state",0);
        if ($activityClassId > 0) {
            $activityManageData = new ActivityClassManageData();
            $result = $activityManageData->ModifyState($activityClassId,$state);
            //加入操作日志
            $operateContent = 'ModifyState ActivityClass,Get FORM:' . implode('|', $_GET) . ';\r\nResult:ActivityClassId:' . $activityClassId;
            self::CreateManageUserLog($operateContent);
        } else {
            $result = -1;
        }
        return Control::GetRequest("jsonpcallback","") . '({"result":"'.$result.'"})';
    }
}

?>