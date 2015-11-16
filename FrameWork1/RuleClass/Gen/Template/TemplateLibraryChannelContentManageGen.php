<?php

/**
 * 模板库应用到频道的模板内容的业务引擎类
 *
 * @author zhangchi
 */
class TemplateLibraryChannelContentManageGen extends BaseManageGen implements IBaseManageGen {

    /**
     * @return mixed|string|
     */
    function Gen() {
        $result = "";
        $method = Control::GetRequest("m", "");
        switch ($method) {
            case "modify":
                $result = self::GenModify();
                break;
            case "list":
                $result = self::GenList();
                break;
            case "async_modify_state":
                $result = self::AsyncModifyState();
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
            default:
                $result = self::GenList();
                break;
        }
        $replaceArr = array(
            "{method}" => $method
        );
        $result = strtr($result, $replaceArr);
        return $result;
    }

    public function GenList() {
            $templateContent = Template::Load("template/template_library_channel_content_list.html","common");
            $channelId = Control::GetRequest("channel_id", 0);

            ///////////////判断是否有操作权限///////////////////
            $channelManageData=new ChannelManageData();
            $siteId=$channelManageData->GetSiteId($channelId,TRUE);
            $manageUserId = Control::GetManageUserId();
            $manageUserAuthority = new ManageUserAuthorityManageData();
            $can = $manageUserAuthority->CanChannelManageTemplate($siteId, $channelId, $manageUserId);
            if ($can != 1) {
                    Control::ShowMessage(Language::Load('template_library', 10));//没有操作权限！
                return "";
            }


            $templateContent = str_ireplace("{ChannelId}", $channelId, $templateContent);

            $templateLibraryChannelContentManageData = new TemplateLibraryChannelContentManageData();
            $arrList = $templateLibraryChannelContentManageData->GetList($channelId);
            $listName = "template_library_channel_content_list";
            Template::ReplaceList($templateContent, $arrList, $listName);

            parent::ReplaceEnd($templateContent);
            return $templateContent;
    }


    /**
     * 编辑
     * @return string 模板内容页面
     */
    private function GenModify()
    {

        $templateLibraryChannelContentId = Control::GetRequest("template_library_channel_content_id", 0);
        $resultJavaScript = "";
        $manageUserId = Control::GetManageUserId();
        $tabIndex = Control::GetRequest("tab_index", 0);

        if ($templateLibraryChannelContentId > 0 && $manageUserId > 0) {
            $tempContent = Template::Load("template/template_library_channel_content_deal.html","common");
            parent::ReplaceFirst($tempContent);

            $templateLibraryChannelContentManageData = new TemplateLibraryChannelContentManageData();

            $channelId = $templateLibraryChannelContentManageData->GetChannelId($templateLibraryChannelContentId, true);

            //加载原有数据
            $arrOne = $templateLibraryChannelContentManageData->GetOne($templateLibraryChannelContentId);



            if(!empty($arrOne)){
                Template::ReplaceOne($tempContent, $arrOne);
            }else{
                $resultJavaScript .= Control::GetJqueryMessage(Language::Load('template_library', 4));//原有数据获取失败！请谨慎修改！
            }



            if (!empty($_POST)) {
                $httpPostData = $_POST;

                $result = $templateLibraryChannelContentManageData->Modify($httpPostData, $templateLibraryChannelContentId);
                //加入操作日志
                $operateContent = 'Modify templateLibraryChannelContent,POST FORM:'
                    . implode('|', $_POST) . ';\r\nResult:result:' . $result;
                self::CreateManageUserLog($operateContent);

                if ($result > 0) {
                    //附件操作
                    if (!empty($_FILES)) {

                        if (!empty($_FILES["file_attachment"]["tmp_name"])) {
                            $fileName = $_FILES["file_attachment"]["tmp_name"];
                            $fileData = file_get_contents($fileName);
                            $templateLibraryChannelContentManageData->ModifyAttachment(
                                $templateLibraryChannelContentId,
                                $fileData
                            );
                        }
                    }



                    $closeTab = Control::PostRequest("CloseTab", 0);
                    if ($closeTab == 1) {
                        //$resultJavaScript .= Control::GetCloseTab();
                        Control::GoUrl("/default.php?secu=manage&mod=template_library_channel_content&m=list&channel_id=$channelId&tab_index=$tabIndex");
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
        $templateLibraryChannelContentId=Control::GetRequest("template_library_channel_content_id",0);
        $templateLibraryChannelContentManageData=new TemplateLibraryChannelContentManageData();

        /**********************************************************************
         ******************************判断是否有操作权限**********************
         **********************************************************************/
        $manageUserAuthorityManageData = new ManageUserAuthorityManageData();
        $channelId = $templateLibraryChannelContentManageData->GetChannelId($templateLibraryChannelContentId);
        $siteId = $templateLibraryChannelContentManageData->GetSiteId($templateLibraryChannelContentId);

        $can = $manageUserAuthorityManageData->CanManageTemplateLibrary($siteId,$channelId,$manageUserId);
        if(!$can){
            $result = -10;
        }else{
            $result = $templateLibraryChannelContentManageData->Delete($templateLibraryChannelContentId);
            //加入操作日志
            $operateContent = 'delete Template Library Channel Content,GET PARAM:'.implode('|',$_GET).';\r\nResult:'.$result;
            self::CreateManageUserLog($operateContent);
        }

        return Control::GetRequest("jsonpcallback","").'({"result":'.$result.'})';

    }


    /**
     * 修改状态 (启用 / 停用)
     * @return string 返回Jsonp修改结果
     */
    private function AsyncModifyState() {
        $result = -1;
        $templateLibraryChannelContentId = Control::GetRequest("template_library_channel_content_id", 0);
        $state = Control::GetRequest("state", -1);
        $manageUserId = Control::GetManageUserId();

        if($templateLibraryChannelContentId>0 && $state>=0 && $manageUserId>0){
            $templateLibraryChannelContentManageData = new TemplateLibraryChannelContentManageData();
            //判断权限
            /**********************************************************************
             ******************************判断是否有操作权限**********************
             **********************************************************************/
            $manageUserAuthorityManageData = new ManageUserAuthorityManageData();
            $channelId = $templateLibraryChannelContentManageData->GetChannelId($templateLibraryChannelContentId);
            $siteId = $templateLibraryChannelContentManageData->GetSiteId($templateLibraryChannelContentId);

            $can = $manageUserAuthorityManageData->CanManageSite($siteId,$channelId,$manageUserId);
            if(!$can){
                $result = -10;
            }else{

                $result = $templateLibraryChannelContentManageData->ModifyState($templateLibraryChannelContentId,$state);
                //加入操作日志
                $operateContent = 'Modify State Template Library Channel Content,GET PARAM:'.implode('|',$_GET).';\r\nResult:'.$result;
                self::CreateManageUserLog($operateContent);
            }
        }
        return Control::GetRequest("jsonpcallback","").'({"result":'.$result.'})';
    }


    /**
     * 删除模板附件
     * @return int 返回
     */
    private function AsyncDeleteAttachment(){
        $result = -1;
        $templateLibraryChannelContentId = Control::GetRequest("template_library_channel_content_id", 0);
        if($templateLibraryChannelContentId>0){
            $channelTemplateManageData = new TemplateLibraryChannelContentManageData();
            $result = $channelTemplateManageData->DeleteAttachment($templateLibraryChannelContentId);
        }
        return Control::GetRequest("jsonpcallback","").'({"result":'.$result.'})';
    }

}

?>
