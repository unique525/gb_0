<?php

/**
 * 模板库要自动创建的频道的业务引擎类
 *
 * @author zhangchi
 */
class TemplateLibraryChannelManageGen extends BaseManageGen implements IBaseManageGen {

    /**
     * 牵引生成方法(继承接口)
     * @return string
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
            case "delete":
                $result = self::GenDelete();
                break;
            case "list":
                $result = self::GenList();
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

        $templateLibraryId = Control::GetRequest("template_library_id", 0);
        $templateLibraryManageData=new TemplateLibraryManageData();
        ///////////////判断是否有操作权限///////////////////
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

        $tempContent = Template::Load("template/template_library_channel_list.html","common");

        $templateLibraryChannelManageData = new TemplateLibraryChannelManageData();
        $arrList = $templateLibraryChannelManageData->GetChannelList($templateLibraryId);

        $listName = "template_library_channel_list";
        Template::ReplaceList($tempContent, $arrList, $listName);

        $tempContent = str_ireplace("{TemplateLibraryId}", $templateLibraryId, $tempContent);
        parent::ReplaceEnd($tempContent);
        return $tempContent;
    }

    public function GenCreate() {
        $resultJavaScript="";
        $templateContent = Template::Load("template/template_library_channel_deal.html","common");
        $templateLibraryId = Control::GetRequest("template_library_id", 0);
        $tabIndex = Control::GetRequest("tab_index", 0);

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
            $templateLibraryChannelManageData = new TemplateLibraryChannelManageData();

            $siteId = $templateLibraryManageData->GetSiteId($templateLibraryId);

            
            if (!empty($_POST)) {
                //处理父节点
                if($_POST["f_ParentId"]>0){
                    $parentRank=$templateLibraryChannelManageData->GetRank($_POST["f_ParentId"]);
                    $_POST["f_Rank"]=$parentRank+1;
                }

                $result = $templateLibraryChannelManageData->Create($_POST);
                //加入操作日志
                $operateContent = 'Create TemplateLibrary,POST FORM:' . implode('|', $_POST) . ';\r\nResult:TemplateLibraryId:' . $result;
                self::CreateManageUserLog($operateContent);

                if ($result > 0) {
                    $closeTab = Control::PostRequest("CloseTab", 0);
                    if ($closeTab == 1) {
                        //$resultJavaScript .= Control::GetCloseTab();
                        Control::GoUrl("/default.php?secu=manage&mod=template_library_channel&m=list&template_library_id=$templateLibraryId&tab_index=$tabIndex");
                    } else {
                        Control::GoUrl($_SERVER["PHP_SELF"] . "?" . $_SERVER['QUERY_STRING']);
                    }
                } else {
                    $resultJavaScript = Control::GetJqueryMessage(Language::Load('document', 4));
                }
            }

            $replaceArr = array(
                "{TemplateLibraryId}" => $templateLibraryId,
                "{SiteId}" => $siteId,
                "{Sort}" => "0",
                "{ManageUserId}" => strval($manageUserId),
                "{display}" => "inline"
            );
            $templateContent = strtr($templateContent, $replaceArr);

            $arrList = $templateLibraryChannelManageData->GetChannelList($templateLibraryId);
            $listName = "template_library_channel_list";
            Template::ReplaceList($templateContent, $arrList, $listName);



            parent::ReplaceWhenCreate($templateContent, $templateLibraryChannelManageData->GetFields());
            //
            $patterns = "/\{s_(.*?)\}/";
            $templateContent = preg_replace($patterns, "", $templateContent);
        }
        parent::ReplaceEnd($templateContent);
        $templateContent = str_ireplace("{ResultJavascript}", $resultJavaScript, $templateContent);
        return $templateContent;
    }



    public function GenModify() {
        $resultJavaScript="";
        $templateContent = Template::Load("template/template_library_channel_deal.html","common");
        $templateLibraryChannelId = Control::GetRequest("template_library_channel_id", 0);
        $tabIndex = Control::GetRequest("tab_index", 0);

        $templateLibraryChannelManageData = new TemplateLibraryChannelManageData();
        $templateLibraryId=$templateLibraryChannelManageData->GetTemplateLibraryId($templateLibraryChannelId);
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
        $manageUserId = Control::GetManageUserId();
        parent::ReplaceFirst($templateContent);

        if ($templateLibraryChannelId > 0) {

            $templateLibraryManageData=new TemplateLibraryManageData();
            $siteId = $templateLibraryManageData->GetSiteId($templateLibraryChannelId);


            if (!empty($_POST)) {

                //处理父节点
                if($_POST["f_ParentId"]>0){
                    $parentRank=$templateLibraryChannelManageData->GetRank($_POST["f_ParentId"]);
                    $_POST["f_Rank"]=$parentRank+1;
                }else{
                    $_POST["f_Rank"]=0;
                }


                $result = $templateLibraryChannelManageData->Modify($_POST,$templateLibraryChannelId);

                //加入操作日志
                $operateContent = 'Modify TemplateLibrary,POST FORM:' . implode('|', $_POST) . ';\r\nResult:TemplateLibraryId:' . $result;
                self::CreateManageUserLog($operateContent);

                if ($result > 0) {
                    $closeTab = Control::PostRequest("CloseTab", 0);
                    if ($closeTab == 1) {
                        //$resultJavaScript .= Control::GetCloseTab();
                        Control::GoUrl("/default.php?secu=manage&mod=template_library_channel&m=list&template_library_id=$templateLibraryId&tab_index=$tabIndex");
                    } else {
                        Control::GoUrl($_SERVER["PHP_SELF"] . "?" . $_SERVER['QUERY_STRING']);
                    }
                } else {
                    $resultJavaScript = Control::GetJqueryMessage(Language::Load('document', 4));
                }
            }

            $replaceArr = array(
                "{TemplateLibraryId}" => $templateLibraryId,
                "{SiteId}" => $siteId,
                "{Sort}" => "0",
                "{ManageUserId}" => strval($manageUserId),
                "{display}" => "none"
            );
            $templateContent = strtr($templateContent, $replaceArr);

            $arrListOfChannel = $templateLibraryChannelManageData->GetChannelList($templateLibraryId);
            $listName = "template_library_channel_list";
            Template::ReplaceList($templateContent, $arrListOfChannel, $listName);

            $arrOne = $templateLibraryChannelManageData->GetOne($templateLibraryChannelId);
            if(!empty($arrOne)){
                Template::ReplaceOne($templateContent, $arrOne);
            }else{
                $resultJavaScript .= Control::GetJqueryMessage(Language::Load('template_library', 4));//原有数据获取失败！请谨慎修改！
            }
            //
            $patterns = "/\{s_(.*?)\}/";
            $templateContent = preg_replace($patterns, "", $templateContent);

        }
        parent::ReplaceEnd($templateContent);
        $templateContent = str_ireplace("{ResultJavascript}", $resultJavaScript, $templateContent);
        return $templateContent;
    }



    public function GenDelete(){
        $result=-1;
        $templateLibraryChannelId = Control::GetRequest("template_library_channel_id", 0);
        if($templateLibraryChannelId>0){
            ///////////////判断是否有操作权限///////////////////
            $templateLibraryChannelData = new TemplateLibraryChannelManageData();
            $templateLibraryManageData=new TemplateLibraryManageData();
            $templateLibraryId=$templateLibraryChannelData->GetTemplateLibraryId($templateLibraryChannelId);
            $siteId=$templateLibraryManageData->GetSiteId($templateLibraryId);
            $manageUserId = Control::GetManageUserId();
            $manageUserAuthority = new ManageUserAuthorityManageData();
            $can = $manageUserAuthority->CanManageTemplateLibrary($siteId, 0, $manageUserId);
            if ($can != 1) {
                if($siteId==0){

                    return Control::GetRequest("jsonpcallback","").'({"result":' . -1 . '})';//模板为所有站点通用，需要所有站点的权限！
                }else{

                    return Control::GetRequest("jsonpcallback","").'({"result":' . -1 . '})';//没有操作权限！
                }
            }
            $result = self::MoveToBinWithChildrenAndTemplate($templateLibraryId,$templateLibraryChannelId);//迭代移动子节点、模板到回收站
        }
        return Control::GetRequest("jsonpcallback","").'({"result":'.$result.'})';
    }


    /**
     * 迭代移动子节点、模板到回收站
     * @param $templateLibraryId
     * @param $templateLibraryChannelId
     * @return int
     */
    function MoveToBinWithChildrenAndTemplate($templateLibraryId,$templateLibraryChannelId){
        $templateLibraryChannelData = new TemplateLibraryChannelManageData();
        $templateLibraryContentData = new TemplateLibraryContentManageData();
        $result = $templateLibraryChannelData->Delete($templateLibraryChannelId);  //停用节点
        $templateLibraryContentData->MoveToBinWithChannel($templateLibraryId,$templateLibraryChannelId);  //停用模板
        //加入操作日志
        $operateContent = 'Delete TemplateLibrary,POST FORM:' . implode('|', $_POST) . ';\r\nResult:TemplateLibraryId:' . $result;
        self::CreateManageUserLog($operateContent);
        $arrayChildren=$templateLibraryChannelData->GetChildrenId($templateLibraryChannelId);
        if(count($arrayChildren)>0&&$arrayChildren!=null){  //子节点
            foreach($arrayChildren as $childrenId){
                self::MoveToBinWithChildrenAndTemplate($templateLibraryId,$childrenId);
            }
        }
        return $result;
    }
}

?>
