<?php

/**
 * 模板库模板内容的业务引擎类
 *
 * @author zhangchi
 */
class TemplateLibraryContentGen extends BaseManageGen implements IBaseManageGen {

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
        DataCache::RemoveDir(CACHE_PATH . '/channel_template_data');
        return Control::GetRequest("jsonpcallback","").'({"result":'.$result.'})';
    }

    public function GenPreview() {
        $templateLibraryId = Control::GetRequest("template_library_id", 0);
        $templateLibraryContentId = Control::GetRequest("template_library_content_id", 0);
        if ($templateLibraryContentId > 0) {
            $templateLibraryContentManageData = new TemplateLibraryContentManageData();
            $result = $templateLibraryContentManageData->GetTemplateLibraryContentByTemplateLibraryContentId($templateLibraryContentId);
            //读取频道附件，生成临时ZIP文件并解压到临时目录，然后迭代循环通过FTP传输到目标服务器
/*
            $attachmentFileData = $templateLibraryContentManageData->GetAttachment($templateLibraryContentId);
            if (!empty($attachmentFileData)) {
                $attachName = $templateLibraryContentManageData->GetAttachmentNameByID($templateLibraryContentId);
                $attachmentDir = P_PATH . DIRECTORY_SEPARATOR . "templatelibrary" . DIRECTORY_SEPARATOR . $templateLibraryId . DIRECTORY_SEPARATOR .$attachName;
                $attachmentDir = str_ireplace("//", "/", $attachmentDir);
                File::CreateFolder($attachmentDir);
                $attachmentFileName = $attachmentDir . ".zip";
                File::Write($attachmentFileName, $attachmentFileData);

                //////////////zip解压到临时目录//////////////
                $zip = new ZipArchive();
                if ($zip->open($attachmentFileName) !== TRUE) {
                    exit("cannot open $attachmentFileName\n");
                }
                $zip->extractTo($attachmentDir);
                $zip->close();

            }
*/
            //替换{template_channel_id_   频道ID
            $result = preg_replace("{\{template_channel_id_[0-9]?\}}", "1", $result);

            //替换其他的标签
            $replaceArr = array(
                "{ChannelId}" => "1",
                "{ChannelIntro}" => "测试频道简介测试频道简介测试频道简介测试频道简介测试频道简介测试频道简介测试频道简介测试频道简介测试频道简介测试频道简介测试频道简介测试频道简介测试频道简介",
                "{ChannelName}" => "测试频道名",
                "{SiteId}" => "1"
            );
            $result = strtr($result, $replaceArr);

            //替换基础标签
            parent::ReplaceFirst($result);

            //替换icms标签


            $arrCustomTags = Template::GetAllCustomTag($result);
            if (count($arrCustomTags) > 0) {
                $arrTempContents = $arrCustomTags[0];

                foreach ($arrTempContents as $tagContent) {
                    //标签id channel_1 document_news_1
                    $tagId = Template::GetParamValue($tagContent, "id");
                    //显示条数
                    $tagTopCount = Template::GetParamValue($tagContent, "top");

                }
            }






            $keyName = "icms";
            $arrICMS = Template::GetAllCustomTag($result, $keyName);
            if (isset($arrICMS)) {
                if (count($arrICMS) > 1) {
                    $arr2ICMSDC = $arrICMS[1];
                    foreach ($arr2ICMSDC as $key => $val) {
                        $docContent = '<' . $keyName . '' . $val . '</' . $keyName . '>';
                        $id = Template::GetDocParamValue($docContent, "id", $keyName);
                        $topCount = Template::GetDocParamValue($docContent, "top", $keyName);
                        $previewDataForICMSDC = array();
                        for ($i = 0; $i < $topCount; $i++) {
                            $previewDataForICMSDC[$i]["documentchannelname"] = "测试频道" . $i . "测试频道" . $i . "测试频道" . $i . "测试频道" . $i . "测试频道" . $i . "测试频道" . $i . "测试频道" . $i . "测试频道" . $i;
                            $previewDataForICMSDC[$i]["documentchannelid"] = "1";
                        }
                        Template::ReplaceList($docContent, $previewDataForICMSDC, $id, $keyName);
                        //把对应ID的CMS标记替换成指定内容
                        //替换子循环里的<![CDATA[标记
                        $docContent = str_ireplace("[CDATA]", "<![CDATA[", $docContent);
                        $docContent = str_ireplace("[/CDATA]", "]]>", $docContent);
                        $result = Template::ReplaceCMS($result, $id, $docContent, $keyName);
                    }
                }
            }

            //替换CSCMS标签
            $arrCSCMS = Template::GetAllCMS($result);

            if (isset($arrCSCMS)) {
                if (count($arrCSCMS) > 1) {
                    $arr2 = $arrCSCMS[1];
                    foreach ($arr2 as $key => $val) {
                        $docContent = '<cscms' . $val . '</cscms>';
                        //查询类型，比如新闻类，咨询类
                        $type = Template::GetDocParamValue($docContent, "type");
                        $id = Template::GetDocParamValue($docContent, "id");
                        //显示条数
                        $topCount = Template::GetDocParamValue($docContent, "top");
                        if ($type == "docnewslist") {
                            //预览测试数据
                            $previewDataForDocumentNews = array();
                            for ($i = 0; $i < $topCount; $i++) {
                                $previewDataForDocumentNews[$i]["documentnewstitle"] = "测试标题测试标题测试标题测试标题测试标题测试标题测试标题测试标题" . $i;
                                $previewDataForDocumentNews[$i]["documentnewsintro"] = "测试简介" . $i . "测试简介" . $i . "测试简介" . $i . "测试简介" . $i . "测试简介" . $i . "测试简介" . $i . "测试简介" . $i . "测试简"
                                        . "介" . $i . "测试简介" . $i . "测试简介" . $i . "测试简介" . $i . "测试简介" . $i . "测试简" . $i . "介测试简介" . $i . "测试简介" . $i
                                        . "测试简介" . $i . "测试简介" . $i . "测试简介" . $i . "测试简介" . $i . "测试简介" . $i . "测试简介" . $i . "测试简介" . $i . "测"
                                        . "试简介" . $i . "测试简介" . $i . "测试简介" . $i . "测试简介" . $i . "测试简介" . $i . "测试简介" . $i . "测试简介" . $i . "测试"
                                        . "简介" . $i . "测试简介" . $i . "测试简介" . $i . "测试简介" . $i . "测试简介" . $i . "测试简介" . $i . "测试简介" . $i . "测试简"
                                        . "介" . $i . "测试简介" . $i . "测试简介" . $i . "测试简介" . $i . "测试简介" . $i . "测试简介" . $i . "测试简介" . $i . "测试简介" . $i . ""
                                        . "测试简介" . $i . "测试简介" . $i . "测试简介" . $i . "测试简介" . $i . "测试简介" . $i . "测试简介" . $i . "测试简介" . $i . "测"
                                        . "试简介" . $i . "测试简介" . $i . "测试简介" . $i . "测试简介" . $i . "测试简介" . $i . "测试简介" . $i . "测试简介" . $i . "测试简介" . $i;
                                $previewDataForDocumentNews[$i]["titlepic"] = "http://icms.changsha.cn/upload/docnews/79/20110318/79_1300466402.jpg";
                                $previewDataForDocumentNews[$i][""] = "";
                                $previewDataForDocumentNews[$i][""] = "";
                                $previewDataForDocumentNews[$i][""] = "";
                                $previewDataForDocumentNews[$i][""] = "";
                                $previewDataForDocumentNews[$i][""] = "";
                                $previewDataForDocumentNews[$i][""] = "";
                                $previewDataForDocumentNews[$i][""] = "";
                                $previewDataForDocumentNews[$i][""] = "";
                                $previewDataForDocumentNews[$i][""] = "";
                                $previewDataForDocumentNews[$i][""] = "";
                                $previewDataForDocumentNews[$i][""] = "";
                            }
                        }
                        Template::ReplaceList($docContent, $previewDataForDocumentNews, $id); //把对应ID的CMS标记替换成指定内容
                        $result = Template::ReplaceCMS($result, $id, $docContent);
                    }
                }
            }

            parent::ReplaceEnd($result);
            echo $result;
        }
    }

}

?>
