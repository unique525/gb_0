<?php
/**
 * 后台管理 频道模板 生成类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_Gen_Channel
 * @author zhangchi
 */
class ChannelTemplateManageGen extends BaseManageGen implements IBaseManageGen {
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
            case "async_delete":
                $result = self::AsyncDelete();
                break;
            case "remove_to_bin":
                $result = self::GenRemoveToBin();
                break;
            case "async_delete_attachment":
                $result = self::AsyncDeleteAttachment();
                break;
            case "list":
                $result = self::GenList();
                break;
            case "async_modify_state":
                $result = self::AsyncModifyState();
                break;
            case "get_attachment":
                $result = self::GetAttachment();
                break;
        }

        $result = str_ireplace("{method}", $method, $result);
        return $result;
    }

    /**
     * 新增
     * @return string 模板内容页面
     */
    private function GenCreate(){
        $manageUserId = Control::GetManageUserId();

        $channelId = Control::GetRequest("channel_id", 0);
        $resultJavaScript = "";
        $tabIndex = Control::GetRequest("tab_index", 0);

        if($channelId>0 && $manageUserId>0){

            $channelTemplateManageData = new ChannelTemplateManageData();
            $channelManageData = new ChannelManageData();
            $siteId = $channelManageData->GetSiteId($channelId, false);

            $tempContent = Template::Load("channel/channel_template_deal.html", "common");

            parent::ReplaceFirst($tempContent);

            if (!empty($_POST)) {

                $httpPostData = $_POST;

                $channelTemplateId = $channelTemplateManageData->Create(
                    $httpPostData,$manageUserId,$channelId,$siteId
                );
                //加入操作日志
                $operateContent = 'Create ChannelTemplate,POST FORM:'
                    . implode('|', $_POST) . ';\r\nResult:siteId:' . $channelTemplateId;
                self::CreateManageUserLog($operateContent);

                if ($channelTemplateId > 0) {

                    //模板附件处理
                    if (!empty($_FILES["file_attachment"]["tmp_name"])) {
                        $fileName = $_FILES["file_attachment"]["tmp_name"];
                        $fileData = file_get_contents($fileName);
                        $channelTemplateManageData->ModifyAttachment(
                            $channelTemplateId,
                            $fileData
                        );
                    }


                    $closeTab = Control::PostRequest("CloseTab", 0);
                    if ($closeTab == 1) {
                        //$resultJavaScript .= Control::GetCloseTab();
                        Control::GoUrl("/default.php?secu=manage&mod=channel_template&m=list&channel_id=$channelId&tab_index=$tabIndex");

                    } elseif ($closeTab == 2) {
                        Control::GoUrl("/default.php?secu=manage&mod=channel_template&m=modify
                        &channel_template_id=$channelTemplateId&tab_index=$tabIndex");

                    } else {
                        Control::GoUrl($_SERVER["PHP_SELF"] . "?" . $_SERVER['QUERY_STRING']);
                    }
                } else {
                    $resultJavaScript .=
                        Control::GetJqueryMessage(Language::Load('channel_template', 2)); //新增失败！

                }

            }


            $tempContent = str_ireplace("{b_ChannelId}", $channelId, $tempContent);

            //初始化附件目录名
            $tempContent = str_ireplace("{b_AttachmentName}", "images".$channelId, $tempContent);


            $fieldsOfChannel = $channelTemplateManageData->GetFields();
            parent::ReplaceWhenCreate($tempContent, $fieldsOfChannel);

            $patterns = '/\{b_s_(.*?)\}/';
            $tempContent = preg_replace($patterns, "", $tempContent);

            parent::ReplaceEnd($tempContent);


            $tempContent = str_ireplace("{ResultJavascript}", $resultJavaScript, $tempContent);




        }else{
            $tempContent = Language::Load("channel_template",8);
        }





        return $tempContent;


    }


    /**
     * 编辑
     * @return string 模板内容页面
     */
    private function GenModify()
    {

        $channelTemplateId = Control::GetRequest("channel_template_id", 0);
        $resultJavaScript = "";
        $manageUserId = Control::GetManageUserId();
        $tabIndex = Control::GetRequest("tab_index", 0);

        if ($channelTemplateId > 0 && $manageUserId > 0) {
            $tempContent = Template::Load("channel/channel_template_deal.html", "common");
            parent::ReplaceFirst($tempContent);

            $channelTemplateManageData = new ChannelTemplateManageData();

            $channelId = $channelTemplateManageData->GetChannelId($channelTemplateId, true);

            //加载原有数据
            $arrOne = $channelTemplateManageData->GetOne($channelTemplateId);

            $isArrayList = false;
            $isForTemplateManage = TRUE;

            Template::ReplaceOne($tempContent, $arrOne, $isArrayList, $isForTemplateManage);


            if (!empty($_POST)) {
                $httpPostData = $_POST;

                $result = $channelTemplateManageData->Modify($httpPostData, $channelTemplateId);
                //加入操作日志
                $operateContent = 'Modify ChannelTemplate,POST FORM:'
                    . implode('|', $_POST) . ';\r\nResult:result:' . $result;
                self::CreateManageUserLog($operateContent);

                if ($result > 0) {
                    //附件操作
                    if (!empty($_FILES)) {

                        if (!empty($_FILES["file_attachment"]["tmp_name"])) {
                            $fileName = $_FILES["file_attachment"]["tmp_name"];
                            $fileData = file_get_contents($fileName);
                            $channelTemplateManageData->ModifyAttachment(
                                $channelTemplateId,
                                $fileData
                            );
                        }
                    }

                    //删除缓冲,修改模板的时候，所有缓存清空
                    parent::DelAllCache();

                    $closeTab = Control::PostRequest("CloseTab", 0);
                    if ($closeTab == 1) {
                        //$resultJavaScript .= Control::GetCloseTab();
                        Control::GoUrl("/default.php?secu=manage&mod=channel_template&m=list&channel_id=$channelId&tab_index=$tabIndex");
                    } elseif ($closeTab == 2) {
                        Control::GoUrl("/default.php?secu=manage&mod=channel_template&m=modify
                        &channel_template_id=$channelTemplateId&tab_index=$tabIndex");

                    } else {
                        Control::GoUrl($_SERVER["PHP_SELF"] . "?" . $_SERVER['QUERY_STRING']);
                    }
                } else {
                    $resultJavaScript .= Control::GetJqueryMessage(Language::Load('channel_template', 4)); //编辑失败！
                }
            }




            $patterns = '/\{s_(.*?)\}/';
            $tempContent = preg_replace($patterns, "", $tempContent);
        }
        parent::ReplaceEnd($tempContent);
        $tempContent = str_ireplace("{ResultJavascript}", $resultJavaScript, $tempContent);
        return $tempContent;
    }

    /**
     * 异步删除
     */
    private function AsyncDelete(){
        $manageUserId=Control::GetManageUserId();
        $channelTemplateId=Control::GetRequest("channel_template_id",0);
        $channelTemplateManageData=new ChannelTemplateManageData();

        /**********************************************************************
         ******************************判断是否有操作权限**********************
         **********************************************************************/
        $manageUserAuthorityManageData = new ManageUserAuthorityManageData();
        $channelId = 0;
        $siteId = $channelTemplateManageData->GetSiteId($channelTemplateId, true);

        $can = $manageUserAuthorityManageData->CanManageTemplateLibrary($siteId,$channelId,$manageUserId);
        if(!$can){
            $result = -10;
        }else{
            $result = $channelTemplateManageData->Delete($channelTemplateId);
        }

        return Control::GetRequest("jsonpcallback","").'({"result":'.$result.'})';

    }

    /**
     * 修改文档状态 状态值定义在Data类中
     * @return string 返回Jsonp修改结果
     */
    private function AsyncModifyState() {
        $result = -1;
        $channelTemplateId = Control::GetRequest("channel_template_id", 0);
        $state = Control::GetRequest("state", -1);
        $manageUserId = Control::GetManageUserId();

        if($channelTemplateId>0 && $state>=0 && $manageUserId>0){
            //判断权限
            /**********************************************************************
             ******************************判断是否有操作权限**********************
             **********************************************************************/
            $manageUserAuthorityManageData = new ManageUserAuthorityManageData();
            $channelId = 0;
            $channelTemplateManageData = new ChannelTemplateManageData();
            $siteId = $channelTemplateManageData->GetSiteId($channelTemplateId, true);

            $can = $manageUserAuthorityManageData->CanManageSite($siteId,$channelId,$manageUserId);
            if(!$can){
                $result = -10;
            }else{

                $result = $channelTemplateManageData->ModifyState($channelTemplateId,$state);
                //加入操作日志
                $operateContent = 'Modify State Channel Template,GET PARAM:'.implode('|',$_GET).';\r\nResult:'.$result;
                self::CreateManageUserLog($operateContent);
                //删除缓冲
                parent::DelAllCache();
            }
        }
        return Control::GetRequest("jsonpcallback","").'({"result":'.$result.'})';
    }


    /**
     * 返回列表页面
     * @return string 模板内容页面
     */
    private function GenList(){
        $manageUserId = Control::GetManageUserId();

        ///////////////判断是否有操作权限///////////////////
        //$manageUserAuthorityManageData = new ManageUserAuthorityManageData();
        //$canExplore = $manageUserAuthorityManageData->CanExplore($siteId, $channelId, $manageUserId);
        //if (!$canExplore) {
        //    return ;
        //}

        //load template
        $tempContent = Template::Load("channel/channel_template_list.html", "common");

        parent::ReplaceFirst($tempContent);

        ////////////////////////////////////////////////////
        ///////////////输出权限到页面///////////////////
        ////////////////////////////////////////////////////

        $channelId = Control::GetRequest("channel_id", 0);
        $searchKey = Control::GetRequest("search_key", "");
        $searchType = Control::GetRequest("search_type", -1);
        $searchKey = urldecode($searchKey);

        if ($channelId > 0) {

            $tagId = "channel_template_list";
            $allCount = 0;
            $channelTemplateManageData = new ChannelTemplateManageData();
            $arrList = $channelTemplateManageData->GetListForManage($channelId, $searchKey, $searchType, $manageUserId);
            if (count($arrList) > 0) {
                Template::ReplaceList($tempContent, $arrList, $tagId);
                $tempContent = str_ireplace("{pager_button}", "", $tempContent);

            } else {
                Template::RemoveCustomTag($tempContent, $tagId);
                $tempContent = str_ireplace("{pager_button}", Language::Load("channel_template", 9), $tempContent);
            }

            $tempContent = str_ireplace("{ChannelId}", $channelId, $tempContent);



        }else{

        }

        parent::ReplaceEnd($tempContent);
        return $tempContent;
    }

    /**
     * 返回模板附件
     * @return null|string
     */
    private function GetAttachment(){
        $result = null;
        $channelTemplateId = Control::GetRequest("channel_template_id", 0);
        if($channelTemplateId>0){
            $channelTemplateManageData = new ChannelTemplateManageData();
            $fileData = $channelTemplateManageData->GetAttachment($channelTemplateId);
            if (!empty($fileData)) {
                header("Content-type: application/zip;");
                header("Content-Disposition: attachment; filename=attachment.zip");
                $result = $fileData;
            }
        }
        return $result;
    }

    /**
     * 返回模板附件
     * @return int 返回
     */
    private function AsyncDeleteAttachment(){
        $result = -1;
        $channelTemplateId = Control::GetRequest("channel_template_id", 0);
        if($channelTemplateId>0){
            $channelTemplateManageData = new ChannelTemplateManageData();
            $result = $channelTemplateManageData->DeleteAttachment($channelTemplateId);
        }
        //删除缓冲
        parent::DelAllCache();

        return Control::GetRequest("jsonpcallback","").'({"result":'.$result.'})';
    }

} 