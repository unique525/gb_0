<?php

/**
 * 活动表单页面生成类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_Gen_Activity
 * @author 525
 */
class ActivityManageGen extends BaseManageGen implements IBaseManageGen {


    /**
     * 活动id错误
     */
    const FALSE_ACTIVITY_ID = -1;
    /**
     * 写入、修改数据库操作失败
     */
    const INSERT_OR_UPDATE_FAILED = -2;
    /**
     * 活动type错误
     */
    const FALSE_ACTIVITY_TYPE = -3;
    /**
     * 查询单条活动结果为空
     */
    const GET_ONE_RESULT_NULL = -4;


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
     * 新增活动
     * @return string 执行结果
     */
    private function GenCreate(){
        $result = -1;
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
        $activityId=Control::GetRequest("activity_id",-1);
        $activityType=Control::GetRequest("activity_type",-1);
        $tabIndex = Control::GetRequest("tab_index", 1);
        $channelId=Control::GetRequest("channel_id",0);
        if ($activityType < 0) {
            return DefineCode::ACTIVITY_MANAGE + self::FALSE_ACTIVITY_TYPE;
        }
        if ($activityId <= 0) {
            return DefineCode::ACTIVITY_MANAGE + self::FALSE_ACTIVITY_ID;
        }
        $tempContent = Template::Load("activity/activity_".$activityType."_deal.html","common");
        $resultJavaScript="";
        parent::ReplaceFirst($tempContent);

        if ($activityId > 0) {
            $activityManageData = new ActivityManageData();
            if (!empty($_POST)) {
                $modifySuccess = $activityManageData->Modify($_POST, $activityId);
                if ($modifySuccess > 0) {

                    //处理titlePic

                    if(!empty($_FILES)){

                        //titlePic1
                        $fileElementName = "file_title_pic_1";
                        $tableType = UploadFileData::UPLOAD_TABLE_TYPE_ACTIVITY_TITLE_PIC_1; //activity_1 60
                        $tableId = $activityId;
                        $uploadFile1 = new UploadFile();
                        $uploadFileId1 = 0;
                        $titlePic1Result = self::Upload(
                            $fileElementName,
                            $tableType,
                            $tableId,
                            $uploadFile1,
                            $uploadFileId1
                        );
                        if (intval($titlePic1Result) <=0){
                            //上传出错或没有选择文件上传
                        }else{

                        }

                        //title pic2
                        $fileElementName = "file_title_pic_2";
                        $tableType = UploadFileData::UPLOAD_TABLE_TYPE_ACTIVITY_TITLE_PIC_2;//activity_2 61
                        $uploadFileId2 = 0;
                        $uploadFile2 = new UploadFile();
                        $titlePic2Result = self::Upload(
                            $fileElementName,
                            $tableType,
                            $tableId,
                            $uploadFile2,
                            $uploadFileId2
                        );
                        if (intval($titlePic2Result) <=0){
                            //上传出错或没有选择文件上传
                        }else{

                        }
                        //title pic3
                        $fileElementName = "file_title_pic_3";

                        $tableType = UploadFileData::UPLOAD_TABLE_TYPE_ACTIVITY_TITLE_PIC_3;//activity_3 62
                        $uploadFileId3 = 0;

                        $uploadFile3 = new UploadFile();

                        $titlePic3Result = self::Upload(
                            $fileElementName,
                            $tableType,
                            $tableId,
                            $uploadFile3,
                            $uploadFileId3
                        );
                        if (intval($titlePic3Result) <=0){
                            //上传出错或没有选择文件上传
                        }else{

                        }


                        if($uploadFileId1>0||$uploadFileId2>0||$uploadFileId3>0){
                            $activityManageData->ModifyTitlePic($activityId, $uploadFileId1, $uploadFileId2, $uploadFileId3);

                            //图片多平台处理
                            $channelManageData=new ChannelManageData();
                            $siteId=$channelManageData->GetSiteId($channelId,FALSE);
                            $siteConfigManageData = new SiteConfigManageData($siteId);
                            $activityTitlePicMobileWidth = $siteConfigManageData->$activityTitlePicMobileWidth;
                            if($activityTitlePicMobileWidth<=0){
                                $activityTitlePicMobileWidth  = 320; //默认320宽
                            }
                            self::GenUploadFileMobile($uploadFileId1,$activityTitlePicMobileWidth);

                            $activityTitlePicPadWidth = $siteConfigManageData->$activityTitlePicPadWidth;
                            if($activityTitlePicPadWidth<=0){
                                $activityTitlePicPadWidth  = 1024; //默认1024宽
                            }
                            self::GenUploadFilePad($uploadFileId1,$activityTitlePicPadWidth);
                        }
                    }


                    //$activityManageData->UpdateJoin($activityId); //更新审核人数
                    //$activityManageData->UpdateApply($activityId); //更新申请人数
                    //$uploadfiles = Control::PostRequest("f_uploadfiles", "");
                    //$uploadfile_arr = split(",", $uploadfiles);
                    //$uploadFileData = new UploadFileData();
                    ////修改题图的FILEID
                    //$uploadFileData->ModifyTableID($uploadfileid, $activityId);
                    //for ($i = 0; $i < count($uploadfile_arr); $i++) {
                    //    if (intval($uploadfile_arr[$i]) > 0) {
                    //        $uploadFileData->ModifyTableID(intval($uploadfile_arr[$i]), $activityId);
                    //    }
                    //}


                    Control::ShowMessage(Language::Load('activity', 18));
                    $closeTab = Control::PostRequest("CloseTab",0);
                    if($closeTab == 1){
                        $resultJavaScript .= Control::GetCloseTab();
                    }else{
                        Control::GoUrl($_SERVER["PHP_SELF"]."?".$_SERVER['QUERY_STRING']);
                        //Control::GoUrl($_SERVER["PHP_SELF"].'?secu=manage&mod=custom_form&m=create');
                    }
                } else {
                    return DefineCode::ACTIVITY_MANAGE + self::INSERT_OR_UPDATE_FAILED;
                }
                //加入操作log
                $operateContent = "activity：ActivityID：" . $activityId . "；result：" . $activityId . "；title：" . Control::PostRequest("f_ActivityName", "");
                self::CreateManageUserLog($operateContent);

            }

            //取活动class
            $activityClassManageData = new ActivityClassManageData();
            $listName = "activity_class_list";
            $listOfClassArray = $activityClassManageData->GetList($channelId, $activityType);
            //没有找到就默认增加分类
            if (count($listOfClassArray) <= 0) {
                $channelManageData = new ChannelManageData();
                $siteid = $channelManageData->GetSiteId($channelId,FALSE);
                $activityClassName = "默认";
                $state = 0;
                $InsertArray=array(
                    "f_SiteId" => $siteid,
                    "f_ChannelId" => $channelId,
                    "f_ActivityClassName" => $activityClassName,
                    "f_State" => $state,
                    "f_ActivityType" => $activityType
                );
                $classId=$activityClassManageData->Create($InsertArray);
                if($classId<0)
                    Control::GetJqueryMessage(Language::Load('activity', 6));//警告：新增活动类别失败！
                $listOfClassArray = $activityClassManageData->GetList($channelId, $activityType);
            }
            Template::ReplaceList($tempContent, $listOfClassArray, $listName);


            $activityManageData = new ActivityManageData();
            $arrOne = $activityManageData->GetOne($activityId);
            if(!empty($arrOne)){
                Template::ReplaceOne($tempContent, $arrOne);
            }else{
                return DefineCode::ACTIVITY_MANAGE + self::GET_ONE_RESULT_NULL . "id=" . $activityId;
            }

            $replace_arr = array(
                "{ActivityType}" => $activityType,
                "{ChannelId}" => $channelId,
                "{TabIndex}" => $tabIndex,
                "{Display}" => "none"
            );
            $tempContent = strtr($tempContent, $replace_arr);

            //替换掉{s XXX}的内容
            $patterns = '/\{s_(.*?)\}/';
            $tempContent = preg_replace($patterns, "", $tempContent);
        }
        parent::ReplaceEnd($tempContent);
        $tempContent = str_ireplace("{ResultJavascript}", $resultJavaScript, $tempContent);
        return $tempContent;
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

                        //记入操作log
                        $operateContent = "Activity：ActivityID：" . $newActivityId . "；result：" . $newActivityId . "；title：" . Control::PostRequest("f_ActivityName", "");
                        self::CreateManageUserLog($operateContent);

                        Control::ShowMessage(Language::Load('document', 1));
                        $closeTab = Control::PostRequest("CloseTab",0);
                        if($closeTab == 1){
                            $resultJavaScript .= Control::GetCloseTab();
                        }else{
                            Control::GoUrl($_SERVER["PHP_SELF"]."?".$_SERVER['QUERY_STRING']);
                        }

                        //处理titlePic

                        if(!empty($_FILES)){

                            //titlePic1
                            $fileElementName = "file_title_pic_1";
                            $tableType = UploadFileData::UPLOAD_TABLE_TYPE_ACTIVITY_TITLE_PIC_1; //activity_1 60
                            $tableId = $newActivityId;
                            $uploadFile1 = new UploadFile();
                            $uploadFileId1 = 0;
                            $titlePic1Result = self::Upload(
                                $fileElementName,
                                $tableType,
                                $tableId,
                                $uploadFile1,
                                $uploadFileId1
                            );
                            if (intval($titlePic1Result) <=0){
                                //上传出错或没有选择文件上传
                            }else{

                            }

                            //title pic2
                            $fileElementName = "file_title_pic_2";
                            $tableType = UploadFileData::UPLOAD_TABLE_TYPE_ACTIVITY_TITLE_PIC_2;//activity_2 61
                            $uploadFileId2 = 0;
                            $uploadFile2 = new UploadFile();
                            $titlePic2Result = self::Upload(
                                $fileElementName,
                                $tableType,
                                $tableId,
                                $uploadFile2,
                                $uploadFileId2
                            );
                            if (intval($titlePic2Result) <=0){
                                //上传出错或没有选择文件上传
                            }else{

                            }
                            //title pic3
                            $fileElementName = "file_title_pic_3";

                            $tableType = UploadFileData::UPLOAD_TABLE_TYPE_ACTIVITY_TITLE_PIC_3;//activity_3 62
                            $uploadFileId3 = 0;

                            $uploadFile3 = new UploadFile();

                            $titlePic3Result = self::Upload(
                                $fileElementName,
                                $tableType,
                                $tableId,
                                $uploadFile3,
                                $uploadFileId3
                            );
                            if (intval($titlePic3Result) <=0){
                                //上传出错或没有选择文件上传
                            }else{

                            }


                            if($uploadFileId1>0||$uploadFileId2>0||$uploadFileId3>0){
                                $activityManageData->ModifyTitlePic($newActivityId, $uploadFileId1, $uploadFileId2, $uploadFileId3);

                                //图片多平台处理
                                $channelManageData=new ChannelManageData();
                                $siteId=$channelManageData->GetSiteId($channelId,FALSE);
                                $siteConfigManageData = new SiteConfigManageData($siteId);
                                    $activityTitlePicMobileWidth = $siteConfigManageData->$activityTitlePicMobileWidth;
                                    if($activityTitlePicMobileWidth<=0){
                                        $activityTitlePicMobileWidth  = 320; //默认320宽
                                    }
                                    self::GenUploadFileMobile($uploadFileId1,$activityTitlePicMobileWidth);

                                    $activityTitlePicPadWidth = $siteConfigManageData->$activityTitlePicPadWidth;
                                    if($activityTitlePicPadWidth<=0){
                                        $activityTitlePicPadWidth  = 1024; //默认1024宽
                                    }
                                    self::GenUploadFilePad($uploadFileId1,$activityTitlePicPadWidth);
                            }
                        }
                    }else{
                        return DefineCode::ACTIVITY_MANAGE + self::INSERT_OR_UPDATE_FAILED;
                    }


                  // //发布者加入活动
                  // $userId = Control::PostRequest("f_UserId", "");
                  // $state = 30;//0未审 30已审核 100已否
                  // $createdate = date("Y-m-d H:i:s", time());
                  // $activityUserManageData = new ActivityUserManageData();
//
                  // $InsertArray=array(
                  //     "f_ActivityId" => $newActivityId,
                  //     "f_UserId" => $userId,
                  //     "f_createdate" => $createdate,
                  //     "f_state" => $state
                  // );
                  // $activityUserManageData->Create($InsertArray);
//
                  // $numberOfSignUp=$activityUserManageData->GetUserCount($newActivityId,0); //更新申请人数
                  // $activityManageData->UpdateNumberOfSignUp($newActivityId,$numberOfSignUp);
//
                  // $userCount=$activityUserManageData->GetUserCount($newActivityId,30); //更新审核人数
                  // $activityManageData->UpdateUserCount($newActivityId,$userCount);


                    ///////////发布模式处理
                   //$channelManageData = new ChannelManageData();
                   //$_publishType = $channelManageData->GetPublishType($channelId,FALSE);
                   //if ($_publishType > 0) {
                   //    switch ($_publishType) {
                   //        case 1: //自动发布新稿
                   //            //修改文档状态为终审
                   //            $state = 14;
                   //            $activityManageData->UpdateState($newActivityId, $state);
                   //            self::Publish($newActivityId);
                   //            break;
                   //    }
                   //}
                    ///////////////////////////


                }

                //取活动class
                $activityClassManageData = new ActivityClassManageData();
                $listName = "activity_class_list";
                $listOfClassArray = $activityClassManageData->GetList($channelId, $activityType);
                //没有找到就默认增加分类
                if (count($listOfClassArray) <= 0) {
                    $channelManageData = new ChannelManageData();
                    $siteid = $channelManageData->GetSiteId($channelId,FALSE);
                    $activityClassName = "默认";
                    $state = 0;
                    $InsertArray=array(
                        "f_SiteId" => $siteid,
                        "f_ChannelId" => $channelId,
                        "f_ActivityClassName" => $activityClassName,
                        "f_State" => $state,
                        "f_ActivityType" => $activityType
                    );
                    $classId=$activityClassManageData->Create($InsertArray);
                    if($classId<0)
                        Control::GetJqueryMessage(Language::Load('activity', 6));//警告：新增活动类别失败！
                    $listOfClassArray = $activityClassManageData->GetList($channelId, $activityType);
                }
                Template::ReplaceList($tempContent, $listOfClassArray, $listName);



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
                    "{UserId}" => $userId,
                    "{UserName}" => $userName,
                    "{ManageUserId}" => $manageUserId,
                    "{ManageUserName}" => $manageUsername,
                    "{TabIndex}" => $tab_index
                );
                $tempContent = strtr($tempContent, $replace_arr);

                $activityManageData=new ActivityManageData();
                $fieldsOfVote = $activityManageData->GetFields();
                parent::ReplaceWhenCreate($tempContent, $fieldsOfVote);

                //替换掉{s XXX}的内容
                $patterns = '/\{s_(.*?)\}/';
                $tempContent = preg_replace($patterns, "", $tempContent);
            } else {
                return "";
            }
            parent::ReplaceEnd($tempContent);
            $tempContent = str_ireplace("{ResultJavascript}", $resultJavaScript, $tempContent);
            return $tempContent;
    }

    /**
     * 修改活动状态
     * @return string 修改结果
     */
    private function ModifyState()
    {
        //$result = -1;
        $activityId = Control::GetRequest("activity_id", 0);
        $state = Control::GetRequest("state",0);
        if ($activityId > 0) {
            $activityManageData = new ActivityManageData();
            $result = $activityManageData->ModifyState($activityId,$state);
            //加入操作日志
            $operateContent = 'ModifyState Activity,Get FORM:' . implode('|', $_GET) . ';\r\nResult:ActivityId:' . $activityId;
            self::CreateManageUserLog($operateContent);
        } else {
            $result = -1;
        }
        return Control::GetRequest("jsonpcallback","") . '({"result":"'.$result.'"})';
    }
}

?>