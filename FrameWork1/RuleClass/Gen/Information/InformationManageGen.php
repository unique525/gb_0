<?php

/**
 * 活动管理类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_Gen_Activity
 * @author 525
 */
class InformationManageGen extends BaseManageGen implements IBaseManageGen {


    /**
     * 活动id错误
     */
    const ACTIVITY_FALSE_ACTIVITY_ID = -1;
    /**
     * 写入、修改数据库操作失败
     */
    const ACTIVITY_INSERT_OR_UPDATE_FAILED = -2;
    /**
     * 活动type错误
     */
    const ACTIVITY_FALSE_ACTIVITY_TYPE = -3;
    /**
     * 查询单条活动结果为空
     */
    const ACTIVITY_GET_ONE_RESULT_NULL = -4;


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
     * 新增分类信息
     * @return string 执行结果
     */
    private function GenCreate(){

        $tempContent = Template::Load("information/information_deal.html","common");
        $resultJavaScript="";
        $manageUserId = Control::GetManageUserID();
        $userId = Control::GetUserId();
        $userName = Control::GetUserName();
        $channelId = Control::GetRequest("channel_id", 0);
        $tabIndex = Control::GetRequest("tab_index", 1);
        parent::ReplaceFirst($tempContent);
        if (intval($channelId) > 0) {
            if (!empty($_POST)) {
                $informationManageData = new InformationManageData();
                $newInformationId = $informationManageData->Create($_POST);
                if($newInformationId>0){

                    //记入操作log
                    $operateContent = "Create Information：InformationID：" . $newInformationId . "；result：" . $newInformationId . "；title：" . Control::PostRequest("f_InformationTitle", "");
                    self::CreateManageUserLog($operateContent);

                    Control::ShowMessage(Language::Load('information', 1));
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
                        $tableType = UploadFileData::UPLOAD_TABLE_TYPE_INFORMATION_TITLE_PIC_1; //information_1 130
                        $tableId = $newInformationId;
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


                        if($uploadFileId1>0){
                            $informationManageData->ModifyTitlePic($newInformationId, $uploadFileId1);

                            //图片多平台处理
                            $channelManageData=new ChannelManageData();
                            $siteId=$channelManageData->GetSiteId($channelId,FALSE);
                            $siteConfigManageData = new SiteConfigManageData($siteId);
                            $informationTitlePicMobileWidth = $siteConfigManageData->$informationTitlePicMobileWidth;
                            if($informationTitlePicMobileWidth<=0){
                                $informationTitlePicMobileWidth  = 320; //默认320宽
                            }
                            self::GenUploadFileMobile($uploadFileId1,$informationTitlePicMobileWidth);

                            $informationTitlePicPadWidth = $siteConfigManageData->$informationTitlePicPadWidth;
                            if($informationTitlePicPadWidth<=0){
                                $informationTitlePicPadWidth  = 1024; //默认1024宽
                            }
                            self::GenUploadFilePad($uploadFileId1,$informationTitlePicPadWidth);
                        }
                    }
                }else{
                    $resultJavaScript .= Control::GetJqueryMessage(Language::Load('information', 2));
                }
            }

            //用户资料处理
            $userMobile="";
            $userQQ="";
            $userEmail="";
            $manageUserData = new ManageUserManageData();
            if(!$userId){
                $userId = $manageUserData->GetUserID($manageUserId); //如果找不到登陆user 则取后台管理员挂接的USERId号
            }

            if (intval($userId) <= 0) {
                $resultJavaScript .= Control::GetJqueryMessage(Language::Load('information', 3));//用户数据获取失败！继续操作可能会对信息记录造成影响！
            }else{
                $userData = new UserManageData();
                $userName= $userData->GetOne($userId,FALSE);

                $userInfoManageData= new UserInfoManageData();
                $oneUserInfoArray=$userInfoManageData->GetOne($userId,FALSE);
                $userMobile = $oneUserInfoArray["UserMobile"];
                $userQQ = $oneUserInfoArray["UserQQ"];
                $userEmail = $oneUserInfoArray["UserEmail"];
            }


            $replace_arr = array(
                "{ChannelId}" => $channelId,
                "{UserId}" => $userId,
                "{UserName}" => $userName,
                "{UserMobile}" => $userMobile,
                "{UserQQ}" => $userQQ,
                "{UserEmail}" => $userEmail,
                "{TabIndex}" => $tabIndex,
                "{display}" => ""
            );
            $tempContent = strtr($tempContent, $replace_arr);

            $informationManageData=new InformationManageData();
            $fieldsOfInformation = $informationManageData->GetFields();
            parent::ReplaceWhenCreate($tempContent, $fieldsOfInformation);

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
     * 编辑活动
     * @return string 执行结果
     */
    private function GenModify(){

        $tempContent = Template::Load("information/information_deal.html","common");
        $resultJavaScript="";
        $manageUserId = Control::GetManageUserID();
        $userId = Control::GetUserId();
        $userName = Control::GetUserName();
        $channelId = Control::GetRequest("channel_id", 0);
        $tabIndex = Control::GetRequest("tab_index", 1);
        $informationId= Control::GetRequest("information_id",-1);
        $informationId=intval($informationId);


        parent::ReplaceFirst($tempContent);
        $informationManageData = new InformationManageData();

        if (intval($channelId) > 0) {
            if (!empty($_POST)) {
                $modifySuccess = $informationManageData->Modify($_POST, $informationId);
                if ($modifySuccess > 0) {

                    //记入操作log
                    $operateContent = "Modify Information：InformationID：" . $informationId . "；result：" . $informationId . "；title：" . Control::PostRequest("f_InformationTitle", "");
                    self::CreateManageUserLog($operateContent);

                    Control::ShowMessage(Language::Load('information', 1));
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
                        $tableType = UploadFileData::UPLOAD_TABLE_TYPE_INFORMATION_TITLE_PIC_1; //information_1 130
                        $tableId = $newInformationId;
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


                        if($uploadFileId1>0){
                            $informationManageData->ModifyTitlePic($newInformationId, $uploadFileId1);

                            //图片多平台处理
                            $channelManageData=new ChannelManageData();
                            $siteId=$channelManageData->GetSiteId($channelId,FALSE);
                            $siteConfigManageData = new SiteConfigManageData($siteId);
                            $informationTitlePicMobileWidth = $siteConfigManageData->$informationTitlePicMobileWidth;
                            if($informationTitlePicMobileWidth<=0){
                                $informationTitlePicMobileWidth  = 320; //默认320宽
                            }
                            self::GenUploadFileMobile($uploadFileId1,$informationTitlePicMobileWidth);

                            $informationTitlePicPadWidth = $siteConfigManageData->$informationTitlePicPadWidth;
                            if($informationTitlePicPadWidth<=0){
                                $informationTitlePicPadWidth  = 1024; //默认1024宽
                            }
                            self::GenUploadFilePad($uploadFileId1,$informationTitlePicPadWidth);
                        }
                    }
                }else{
                    $resultJavaScript .= Control::GetJqueryMessage(Language::Load('information', 2));
                }
            }

            $arrOneInformation = $informationManageData->GetOne($informationId);
            if(!empty($arrOneInformation)){
                Template::ReplaceOne($tempContent, $arrOneInformation);
            }else{
                $resultJavaScript .= Control::GetJqueryMessage(Language::Load('information', 4));
            }

            //用户资料处理
            $userMobile="";
            $userQQ="";
            $userEmail="";
            $manageUserData = new ManageUserManageData();
            if(!$userId){
                $userId = $manageUserData->GetUserID($manageUserId); //如果找不到登陆user 则取后台管理员挂接的USERId号
            }

            if (intval($userId) <= 0) {
                $resultJavaScript .= Control::GetJqueryMessage(Language::Load('information', 3));//用户数据获取失败！继续操作可能会对信息记录造成影响！
            }else{
                $userData = new UserManageData();
                $userName= $userData->GetOne($userId,FALSE);

                $userInfoManageData= new UserInfoManageData();
                $oneUserInfoArray=$userInfoManageData->GetOne($userId,FALSE);
                $userMobile = $oneUserInfoArray["UserMobile"];
                $userQQ = $oneUserInfoArray["UserQQ"];
                $userEmail = $oneUserInfoArray["UserEmail"];
            }


            $replace_arr = array(
                "{ChannelId}" => $channelId,
                "{UserId}" => $userId,
                "{UserName}" => $userName,
                "{UserMobile}" => $userMobile,
                "{UserQQ}" => $userQQ,
                "{UserEmail}" => $userEmail,
                "{TabIndex}" => $tabIndex,
                "{display}" => ""
            );
            $tempContent = strtr($tempContent, $replace_arr);

            $informationManageData=new InformationManageData();
            $fieldsOfInformation = $informationManageData->GetFields();
            parent::ReplaceWhenCreate($tempContent, $fieldsOfInformation);

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
     * 分类信息分页列表
     * @return string 列表页面html
     */
    private function GenList(){
        $result = Language::Load('document', 7);
        $resultJavaScript="";
        $tempContent = Template::Load("information/information_list.html","common");
        $channelId = Control::GetRequest("channel_id", 0);
        $state = Control::GetRequest("state", -1);
        $pageSize = Control::GetRequest("ps", 20);
        $pageIndex = Control::GetRequest("p", 1);
        $searchKey = Control::GetRequest("search_key", "");
        $searchKey = urldecode($searchKey);

        if ($pageIndex > 0 && $channelId > 0) {
            $pageBegin = ($pageIndex - 1) * $pageSize;
            $listName = "Information";
            $allCount = 0;
            $informationManageData = new InformationManageData();
            $listOfInformationArray = $informationManageData->GetListPager($channelId, $pageBegin, $pageSize, $allCount, $searchKey);
            if ($listOfInformationArray != null && count($listOfInformationArray) > 0) {

                Template::ReplaceList($tempContent, $listOfInformationArray, $listName);

                $styleNumber = 1;
                $pagerTemplate = Template::Load("pager/pager_style$styleNumber.html", "common");
                $isJs = FALSE;
                $navUrl = "default.php?secu=manage&mod=information&m=list&channel_id=$channelId&p={0}&ps=$pageSize";
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


    /**
     * 修改分类信息状态
     * @return string 修改结果
     */
    private function ModifyState()
    {
        //$result = -1;
        $informationId = Control::GetRequest("table_id", 0);
        $state = Control::GetRequest("state",0);
        if ($informationId > 0) {
            $informationManageData = new InformationManageData();
            $result = $informationManageData->ModifyState($informationId,$state);
            //加入操作日志
            $operateContent = 'ModifyState Information,Get FORM:' . implode('|', $_GET) . ';\r\nResult:Information:' . $informationId;
            self::CreateManageUserLog($operateContent);
        } else {
            $result = -1;
        }
        return Control::GetRequest("jsonpcallback","") . '({"result":"'.$result.'"})';
    }
}

?>