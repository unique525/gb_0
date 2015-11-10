<?php

/**
 * 模板库模板内容的业务引擎类
 *
 * @author zhangchi
 */
class TemplateLibraryContentManageGen extends BaseManageGen implements IBaseManageGen {

    /**
     * 牵引生成方法(继承接口)
     * @return string
     */
    function Gen() {
        $result = "";
        $method = Control::GetRequest("m", "");
        switch ($method) {
            case "list":
                $result = self::GenList();
                break;
            case "create":
                $result = self::GenCreate();
                break;
            case "modify":
                $result = self::GenModify();
                break;
            case "preview":
                $result = self::GenPreview();
                break;
            case "async_modify_state":
                $result = self::AsyncModifyState();
                break;
            case "async_delete":
                $result = self::AsyncDelete();
                break;
            case "get_attachment":
                $result = self::GetAttachment();
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

    function GenList() {
        $templateContent = Template::Load("template/template_library_content_list.html","common");
        $templateLibraryChannelId = Control::GetRequest("template_library_channel_id", 0);
        if($templateLibraryChannelId>0){
            $templateLibraryChannelManageData=new TemplateLibraryChannelManageData();
            $templateLibraryId=$templateLibraryChannelManageData->GetTemplateLibraryId($templateLibraryChannelId);
        }else{
            $templateLibraryId = Control::GetRequest("template_library_id", 0);
        }

        ///////////////判断是否有操作权限///////////////////
        $templateLibraryManageData=new TemplateLibraryManageData();
        $siteId=$templateLibraryManageData->GetSiteId($templateLibraryId);
        $manageUserId = Control::GetManageUserId();
        $manageUserAuthority = new ManageUserAuthorityManageData();
        $can = $manageUserAuthority->CanManageTemplateLibrary($siteId, 0, $manageUserId);
        if ($can != 1) {
            if($siteId==0){
                Control::ShowMessage(Language::Load('template_library', 11));//模板为所有站点通用，需要所有站点的权限！
            }else{
                Control::ShowMessage(Language::Load('template_library', 10));//没有操作权限！
            }
            return "";
        }


        $templateContent = str_ireplace("{TemplateLibraryId}", $templateLibraryId, $templateContent);
        $templateContent = str_ireplace("{TemplateLibraryChannelId}", $templateLibraryChannelId, $templateContent);

        $templateLibraryContentManageData = new TemplateLibraryContentManageData();
        $arrList = $templateLibraryContentManageData->GetList($templateLibraryId,$templateLibraryChannelId);
        $listName = "template_library_content_list";
        Template::ReplaceList($templateContent, $arrList, $listName);

        parent::ReplaceEnd($templateContent);
        return $templateContent;
    }

    public function GenCreate() {
        $resultJavaScript="";
        $templateContent = Template::Load("template/template_library_content_deal.html","common");
        $templateLibraryChannelId = Control::GetRequest("template_library_channel_id", 0);
        if($templateLibraryChannelId>0){
            $templateLibraryChannelManageData=new TemplateLibraryChannelManageData();
            $templateLibraryId=$templateLibraryChannelManageData->GetTemplateLibraryId($templateLibraryChannelId);
        }else{
            $templateLibraryId = Control::GetRequest("template_library_id", 0);
        }
        $tabIndex=Control::GetRequest("tab_index", 0);

        ///////////////判断是否有操作权限///////////////////
        $templateLibraryManageData=new TemplateLibraryManageData();
        $siteId=$templateLibraryManageData->GetSiteId($templateLibraryId);
        $manageUserId = Control::GetManageUserId();
        $manageUserAuthority = new ManageUserAuthorityManageData();
        $can = $manageUserAuthority->CanManageTemplateLibrary($siteId, 0, $manageUserId);
        if ($can != 1) {
            if($siteId==0){
                Control::ShowMessage(Language::Load('template_library', 11));//模板为所有站点通用，需要所有站点的权限！
            }else{
                Control::ShowMessage(Language::Load('template_library', 10));//没有操作权限！
            }
            return "";
        }


        parent::ReplaceFirst($templateContent);

        if ($templateLibraryId > 0) {

            $templateLibraryContentManageData = new TemplateLibraryContentManageData();
            if (!empty($_POST)) {

                $result = $templateLibraryContentManageData->Create($_POST,$templateLibraryChannelId,$templateLibraryId,$siteId);
                //加入操作日志
                $operateContent = 'Create TemplateLibraryContent,POST FORM:' . implode('|', $_POST) . ';\r\nResult:TemplateLibraryContentId:' . $result;
                self::CreateManageUserLog($operateContent);


                if ($result > 0) {

                    //模板附件处理
                    if (!empty($_FILES["file_attachment"]["tmp_name"])) {
                        $fileName = $_FILES["file_attachment"]["tmp_name"];
                        $fileData = file_get_contents($fileName);
                        $templateLibraryContentManageData->ModifyAttachment(
                            $result,
                            $fileData
                        );
                    }


                    $closeTab = Control::PostRequest("CloseTab", 0);
                    if ($closeTab == 1) {
                        //$resultJavaScript .= Control::GetCloseTab();
                        Control::GoUrl("/default.php?secu=manage&mod=template_library_content&m=list&template_library_id=$templateLibraryId&template_library_channel_id=$templateLibraryChannelId&tab_index=$tabIndex");

                    } else {
                        Control::GoUrl($_SERVER["PHP_SELF"] . "?" . $_SERVER['QUERY_STRING']);
                    }
                } else {
                    $resultJavaScript .= Control::GetJqueryMessage(Language::Load('document', 4));

                }
            }

            $templateContent = str_ireplace("{TemplateLibraryId}", $templateLibraryId, $templateContent);
            $templateContent = str_ireplace("{TemplateLibraryChannelId}", $templateLibraryChannelId, $templateContent);
            $templateContent = str_ireplace("{TemplateLibraryContentId}", "", $templateContent);
            $templateContent = str_ireplace("{display}", "inline", $templateContent);

            //初始化附件目录名
            $templateContent = str_ireplace("{AttachmentName}", "images", $templateContent);


            $field = $templateLibraryContentManageData->GetFields();
            parent::ReplaceWhenCreate($templateContent, $field);

            $patterns = '/\{s_(.*?)\}/';
            $templateContent = preg_replace($patterns, "", $templateContent);

            parent::ReplaceEnd($templateContent);


            $templateContent = str_ireplace("{ResultJavascript}", $resultJavaScript, $templateContent);


        }else{
            $templateContent = Language::Load("channel_template",8);
        }

        return $templateContent;

    }

    public function GenModify() {
        $resultJavaScript="";
        $templateContent = Template::Load("template/template_library_content_deal.html","common");
        $templateLibraryContentId = Control::GetRequest("template_library_content_id", 0);
        $tabIndex=Control::GetRequest("tab_index", 0);

        ///////////////判断是否有操作权限///////////////////
        $templateLibraryContentManageData = new TemplateLibraryContentManageData();
        $templateLibraryManageData=new TemplateLibraryManageData();
        $templateLibraryId=$templateLibraryContentManageData->GetTemplateLibraryId($templateLibraryContentId);
        $siteId=$templateLibraryManageData->GetSiteId($templateLibraryId);
        $manageUserId = Control::GetManageUserId();
        $manageUserAuthority = new ManageUserAuthorityManageData();
        $can = $manageUserAuthority->CanManageTemplateLibrary($siteId, 0, $manageUserId);
        if ($can != 1) {
            if($siteId==0){
                Control::ShowMessage(Language::Load('template_library', 11));//模板为所有站点通用，需要所有站点的权限！
            }else{
                Control::ShowMessage(Language::Load('template_library', 10));//没有操作权限！
            }
            return "";
        }

        $templateLibraryChannelId=$templateLibraryContentManageData->GetTemplateLibraryChannelId($templateLibraryContentId);

        parent::ReplaceFirst($templateContent);

        if ($templateLibraryContentId > 0) {

            if (!empty($_POST)) {



                $result = $templateLibraryContentManageData->Modify($_POST,$templateLibraryContentId);
                //加入操作日志
                $operateContent = 'Modify TemplateLibraryContent,POST FORM:' . implode('|', $_POST) . ';\r\nResult:TemplateLibraryContentId:' . $result;
                self::CreateManageUserLog($operateContent);


                if ($result > 0) {

                    //模板附件处理
                    if (!empty($_FILES["file_attachment"]["tmp_name"])) {
                        $fileName = $_FILES["file_attachment"]["tmp_name"];
                        $fileData = file_get_contents($fileName);
                        $templateLibraryContentManageData->ModifyAttachment(
                            $result,
                            $fileData
                        );
                    }


                    $closeTab = Control::PostRequest("CloseTab", 0);
                    if ($closeTab == 1) {
                        //$resultJavaScript .= Control::GetCloseTab();
                        Control::GoUrl("/default.php?secu=manage&mod=template_library_content&m=list&template_library_id=$templateLibraryId&template_library_channel_id=$templateLibraryChannelId&tab_index=$tabIndex");

                    } else {
                        Control::GoUrl($_SERVER["PHP_SELF"] . "?" . $_SERVER['QUERY_STRING']);
                    }
                } else {
                    $resultJavaScript .= Control::GetJqueryMessage(Language::Load('document', 4));

                }
            }

            $templateContent = str_ireplace("{TemplateLibraryId}", $templateLibraryId, $templateContent);
            $templateContent = str_ireplace("{TemplateLibraryChannelId}", $templateLibraryChannelId, $templateContent);
            $templateContent = str_ireplace("{TemplateLibraryContentId}", $templateLibraryContentId, $templateContent);
            $templateContent = str_ireplace("{display}", "none", $templateContent);

            //初始化附件目录名
            $templateContent = str_ireplace("{AttachmentName}", "images_tpl/".$templateLibraryId."_".$templateLibraryContentId, $templateContent);  //文件目录 /images template library/xx/xx


            $arrayOne = $templateLibraryContentManageData->GetOne($templateLibraryContentId);
            if(!empty($arrayOne)){
                Template::ReplaceOne($templateContent, $arrayOne);
            }else{
                $resultJavaScript .= Control::GetJqueryMessage(Language::Load('template_library', 4));//原有数据获取失败！请谨慎修改！
            }

            $patterns = '/\{s_(.*?)\}/';
            $templateContent = preg_replace($patterns, "", $templateContent);

            parent::ReplaceEnd($templateContent);


            $templateContent = str_ireplace("{ResultJavascript}", $resultJavaScript, $templateContent);


        }else{
            $templateContent = Language::Load("channel_template",8);
        }

        return $templateContent;
    }

    /**
     * 修改文档状态 状态值定义在Data类中
     * @return string 返回Jsonp修改结果
     */
    private function AsyncModifyState() {
        $result = -1;
        $templateLibraryContentId = Control::GetRequest("template_library_content_id", 0);
        $state = Control::GetRequest("state", -1);
        $manageUserId = Control::GetManageUserId();

        if($templateLibraryContentId>0 && $state>=0 && $manageUserId>0){
            ///////////////判断是否有操作权限///////////////////
            $templateLibraryContentManageData=new TemplateLibraryContentManageData();
            $templateLibraryManageData=new TemplateLibraryManageData();
            $templateLibraryId=$templateLibraryContentManageData->GetTemplateLibraryId($templateLibraryContentId);
            $siteId=$templateLibraryManageData->GetSiteId($templateLibraryId);
            $manageUserId = Control::GetManageUserId();
            $manageUserAuthority = new ManageUserAuthorityManageData();
            $can = $manageUserAuthority->CanManageTemplateLibrary($siteId, 0, $manageUserId);
            if ($can != 1) {
                $result = -10;
            }else{

                $result = $templateLibraryContentManageData->ModifyState($templateLibraryContentId,$state);
                //加入操作日志
                $operateContent = 'Modify State TemplateLibraryContent ,GET PARAM:'.implode('|',$_GET).';\r\nResult:'.$result;
                self::CreateManageUserLog($operateContent);
            }
        }
        return Control::GetRequest("jsonpcallback","").'({"result":'.$result.'})';
    }



    /**
     * 异步删除
     */
    private function AsyncDelete(){
        $manageUserId=Control::GetManageUserId();
        $templateLibraryContentId = Control::GetRequest("template_library_content_id", 0);

        /**********************************************************************
         ******************************判断是否有操作权限**********************
         **********************************************************************/
        $templateLibraryContentManageData=new TemplateLibraryContentManageData();
        $templateLibraryManageData=new TemplateLibraryManageData();
        $templateLibraryId=$templateLibraryContentManageData->GetTemplateLibraryId($templateLibraryContentId);
        $siteId=$templateLibraryManageData->GetSiteId($templateLibraryId);
        $manageUserId = Control::GetManageUserId();
        $manageUserAuthority = new ManageUserAuthorityManageData();
        $can = $manageUserAuthority->CanManageTemplateLibrary($siteId, 0, $manageUserId);
        if ($can != 1) {
            $result = -10;
        }else{
            $result = $templateLibraryContentManageData->Delete($templateLibraryContentId);
        }

        return Control::GetRequest("jsonpcallback","").'({"result":'.$result.'})';

    }



    /**
     * 返回模板附件
     * @return null|string
     */
    private function GetAttachment(){
        $result = null;
        $templateLibraryContentId = Control::GetRequest("template_library_content_id", 0);
        if($templateLibraryContentId>0){
            $templateLibraryContentManageData=new TemplateLibraryContentManageData();
            $fileData = $templateLibraryContentManageData->GetAttachment($templateLibraryContentId);
            if (!empty($fileData)) {
                header("Content-type: application/zip;");
                header("Content-Disposition: attachment; filename=attachment.zip");
                $result = $fileData;
            }
        }
        return $result;
    }


    /**
     * 删除模板附件
     * @return int 返回
     */
    private function AsyncDeleteAttachment(){
        $result = -1;
        $templateLibraryContentId = Control::GetRequest("template_library_content_id", 0);
        if($templateLibraryContentId>0){
            $templateLibraryContentManageData=new TemplateLibraryContentManageData();
            $result = $templateLibraryContentManageData->DeleteAttachment($templateLibraryContentId);
        }
        //删除缓冲
        parent::DelAllCache();
        return Control::GetRequest("jsonpcallback","").'({"result":'.$result.'})';
    }

    public function GenPreview() {
        $templateLibraryId = Control::GetRequest("template_library_id", 0);
        $templateLibraryContentId = Control::GetRequest("template_library_content_id", 0);
        if ($templateLibraryContentId > 0) {
        }
    }

}

?>
