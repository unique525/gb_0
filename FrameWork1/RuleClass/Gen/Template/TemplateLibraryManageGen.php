<?php

/**
 * 模板库的业务引擎类
 *
 * @author zhangchi
 */
class TemplateLibraryManageGen extends BaseManageGen implements IBaseManageGen {

    /**
     * 牵引生成方法(继承接口)
     * @return string 返回执行结果
     */
    function Gen() {
        $result = "";
        $method = Control::GetRequest("m", "");
        switch ($method) {
            case "import_list":
                $result = self::GenImportList();
                break;
            case "import":
                $result = self::GenImport();
                break;
            case "check":
                $result = self::CheckIsExistImportFile();
                break;
            case "template_channel_content":
                $templateLibraryChannelContentGen = new TemplateLibraryChannelContentGen();
                $result = $templateLibraryChannelContentGen->Gen();
                break;
            case "manage":
                $result = self::GenManage();
                break;
            case "channel":
                $templateLibraryChannelGen = new TemplateLibraryChannelGen();
                $result = $templateLibraryChannelGen->Gen();
                break;
            case "content":
                $templateLibraryContentGen = new TemplateLibraryContentGen();
                $result = $templateLibraryContentGen->Gen();
                break;
            case "create":
                $result = self::GenCreate();
                break;
            case "modify":
                $result = self::GenModify();
                break;
            default:
                $result = self::GenImportList();
                break;
        }
        $replaceArr = array(
            "{method}" => $method
        );
        $result = strtr($result, $replaceArr);
        return $result;
    }

    /**
     * 获取导入模板的列表
     * @return string
     */
    public function GenImportList() {
        $templateContent = Template::Load("template/template_library_list.html","common");
        $pageIndex = Control::GetRequest("p", 1);
        $pageSize = Control::GetRequest("ps", 20);
        $tabIndex = Control::GetRequest("tab", 0);
        $pageBegin = ($pageIndex - 1) * $pageSize;
        $channelId = Control::GetRequest("channel_id", 0);

        $allCount = 0;

        if($channelId>0){
            $channelManageData=new ChannelManageData();
            $siteId=$channelManageData->GetSiteId($channelId,TRUE);

            ///////////////判断是否有操作权限///////////////////
            $manageUserId = Control::GetManageUserId();
            $manageUserAuthority = new ManageUserAuthorityManageData();
            $can = $manageUserAuthority->CanChannelManageTemplate($siteId, $channelId, $manageUserId);
            if ($can != 1) {
                Control::ShowMessage(Language::Load('template_library', 10));//没有操作权限！
                return "";
            }



            $templateLibraryManageData = new TemplateLibraryManageData();
            $templateLibraryList = $templateLibraryManageData->GetListOfPage($siteId,$pageBegin, $pageSize, $allCount);

            $replaceArr = array(
                "{ChannelId}" => $channelId,
                "{SiteId}" => $siteId,
                "{TabIndex}" => $tabIndex
            );
            $templateContent = strtr($templateContent, $replaceArr);

            $listName = "template_library_list";
            Template::ReplaceList($templateContent, $templateLibraryList, $listName);
        }else{
            $listName = "template_library_list";
            Template::ReplaceCustomTag($templateContent,$listName,"");
        }

        parent::ReplaceEnd($templateContent);
        return $templateContent;
    }

    public function GenImport() {
        $result="";
        $templateLibraryId = Control::GetRequest("template_library_id", 0);
        $channelId = Control::GetRequest("channel_id", 0);
        $importChannel = Control::GetRequest("import_channel", 0);
        $channelManageData=new ChannelManageData();
        $siteId=$channelManageData->GetSiteId($channelId,TRUE);
        $createDate=stripslashes(date("Y-m-d H:i:s", time()));

        ///////////////判断是否有操作权限///////////////////
        $manageUserId = Control::GetManageUserId();
        $manageUserAuthority = new ManageUserAuthorityManageData();
        $can = $manageUserAuthority->CanChannelManageTemplate($siteId, $channelId, $manageUserId);
        if ($can != 1) {
            Control::ShowMessage(Language::Load('template_library', 10));//没有操作权限！
            return "";
        }
        //$templateLibraryChannelContentManageData = new TemplateLibraryChannelContentManageData();
        //$templateChannelContent = $templateLibraryChannelContentManageData->GetList($channelId);

        //$channelManageData = new DocumentChannelData();
        //$documentChannelArr = $channelManageData->GetChildChannelID($channelId);

        if ($templateLibraryId > 0 && $channelId > 0) {


                //清空该频道下的子频道
                self::RemoveChildrenChannelToBin($channelId);


                $arrayCreatedChannelId = array();//记录模板库频道与新增频道的对应关系    $arrayCreatedChannelId[模板库频道id] = 新建频道id
                $nowRank = $channelManageData->GetRank($channelId,true);
                $channelCreateResult=self::CreateChannelForImport($siteId,$templateLibraryId,$channelId,$nowRank,$arrayCreatedChannelId,$createDate);
                //删除缓冲
                parent::DelAllCache();



            //将模板内容插入频道模板内容表里面

            //停用该频道下的现有模板
            $templateLibraryChannelContentManageData = new TemplateLibraryChannelContentManageData();
            $templateLibraryChannelContentManageData->RemoveTemplateChannelContent($channelId);


            $replaceArray=array();  //模板替换频道id的数组
            foreach($arrayCreatedChannelId as $key=>$Id){
                $replaceArray["{template_channel_id_" . $key . "}"]="channel_".$Id;
            }
            //取模板库内模板
            $templateCreateResult="";
            $templateLibraryContentManageData = new TemplateLibraryContentManageData();
            $arrayTemplate=$templateLibraryContentManageData->GetListForImport($templateLibraryId);
            foreach($arrayTemplate as $template){
                $newTemplate=-1;
                if($template["TemplateLibraryChannelId"]==0){ //模板库节点为0 导入到根频道（被导入频道）
                    $template["ChannelId"]=$channelId;
                }else{
                    $template["ChannelId"]=$arrayCreatedChannelId[$template["TemplateLibraryChannelId"]];
                }

                if($template["ChannelId"]>0){
                    $template["SiteId"]=$siteId;
                    $template["CreateDate"]=$createDate;
                    $template["TemplateContent"] = strtr($template["TemplateContent"], $replaceArray);
                    $template["TemplateContentForMobile"] = strtr($template["TemplateContentForMobile"], $replaceArray);
                    $template["TemplateContentForPad"] = strtr($template["TemplateContentForPad"], $replaceArray);
                    $template["TemplateContentForTV"] = strtr($template["TemplateContentForTV"], $replaceArray);
                    $template=self::ImportTemplate($template);

                    $newTemplate=$templateLibraryChannelContentManageData->Create($template);
                }
                if($newTemplate<=0){
                    $templateCreateResult.="新建模板失败 id:".$template["TemplateLibraryContentId"];
                }
            }
            $result.=Language::Load('template_library', 1).$channelCreateResult.$templateCreateResult;
            Control::ShowMessage($result);//提交成功！
            Control::GoUrl("/default.php?secu=manage&mod=template_library_channel_content&m=list&channel_id=" . $channelId . "&site_id=" . $siteId);
        }

    }



    /**
     * 迭代删除原节点（包括子节点）到回收站
     * @param $channelId
     * @return string 模板内容页面
     */
    private function RemoveChildrenChannelToBin($channelId){
        if($channelId > 0){
            $channelManageData = new ChannelManageData();
            $strChildrenId=$channelManageData->GetChildrenChannelId($channelId,TRUE);
            $arrChildrenId=Format::ToSplit($strChildrenId, ',');
            if(!empty($arrChildrenId)){
                foreach($arrChildrenId as $oneChildren){
                    self::RemoveChildrenChannelToBin($oneChildren);
                    $channelManageData->ModifyState($oneChildren, ChannelData::STATE_REMOVED);
                }
            }
        }
    }



    /**
     * 迭代建立导入模板的频道树
     * @param $siteId
     * @param $templateLibraryId
     * @param int $rootId 被导入节点的id
     * @param int $rootRank  被导入节点的绝对rank
     * @param $arrayCreatedChannelId
     * @param string $createDate
     * @param int $rankInTemplateLibraryChannel  模板库设置的节点的相对rank
     * @return string
     */
    public function CreateChannelForImport($siteId,$templateLibraryId,$rootId,$rootRank,&$arrayCreatedChannelId,$createDate,$rankInTemplateLibraryChannel=0){
        $result="";
        $channelManageData=new ChannelManageData();
        $templateLibraryChannelManageData = new TemplateLibraryChannelManageData();
        $arrayChannelList = $templateLibraryChannelManageData->GetChannelListOfRank($templateLibraryId,$rankInTemplateLibraryChannel);

        if(!empty($arrayChannelList)){
            foreach ($arrayChannelList as $oneChannel) {
                $parentId=$rootId;//默认父节点为导入模板的节点
                $rank = $rootRank+1;//默认rank为被导入频道的rank+1(一级子频道)
                if($oneChannel["ParentId"]<=0){ //模板库频道没设置父频道，设置根频道为父频道
                    $newChannelId=$templateLibraryChannelManageData->InsertChannel($oneChannel["TemplateLibraryChannelId"],$siteId,$parentId,$rank,$createDate);
                }else{
                    if($arrayCreatedChannelId[$oneChannel["ParentId"]]){  //模板库频道已设置父频道，且父频道已建，设置父频道
                        $parentId=$arrayCreatedChannelId[$oneChannel["ParentId"]];
                        $rank = $rootRank+$rankInTemplateLibraryChannel+1; //设置rank
                    }
                    $newChannelId=$templateLibraryChannelManageData->InsertChannel($oneChannel["TemplateLibraryChannelId"],$siteId,$parentId,$rank,$createDate);
                }
                if($newChannelId>0){
                    //更新父频道的childId
                    $channelManageData->UpdateParentChildrenChannelId($newChannelId);

                    $arrayCreatedChannelId[$oneChannel["TemplateLibraryChannelId"]] = $newChannelId;
                }else{
                    $result.="模板库子频道新建失败:".$oneChannel["TemplateLibraryChannelId"];
                }

                //加入操作日志
                $operateContent = 'Create Channel,POST FORM:'.implode('|',$_POST).';\r\nResult:channelId:'.$newChannelId;
                self::CreateManageUserLog($operateContent);
            }
            self::CreateChannelForImport($siteId,$templateLibraryId,$rootId,$rootRank,$arrayCreatedChannelId,$createDate,$rankInTemplateLibraryChannel+1);  //如果不为空，继续找下一rank的频道
        }else{
            $result.="模板库没有设置子频道";
        }
        return $result;
    }



    public function ImportTemplate($template){
        $fakeHttpPostArray=array();
        foreach($template as $key=>$value){
            if($key!="TemplateLibraryChannelId" && $key!="TemplateLibraryId"){//目标表中没有该字段
                $fakeHttpPostArray["f_".$key]=$value;
            }
        }
        return $fakeHttpPostArray;
    }

    /**
     * @return string
     */
    public function CheckIsExistImportFile() {
        $channelId = Control::GetRequest("channel_id", 0);
        $templateLibraryChannelContentManageData = new TemplateLibraryChannelContentManageData();
        $templateChannelContent = $templateLibraryChannelContentManageData->GetList($channelId);

        $channelManageData = new ChannelManageData();
        $strChildrenChannel = $channelManageData->GetChildrenChannelId($channelId,true);
        if (count($templateChannelContent) == 0 && $strChildrenChannel=="") {
            return Control::GetRequest("jsonpcallback","") . '({"result":"0"})';
        } else {
            return Control::GetRequest("jsonpcallback","") . '({"result":"1"})';
        }
    }

    public function GenManage() {
        $templateContent = Template::Load("template/template_library_manage.html","common");
        $pageIndex = Control::GetRequest("p", 1);
        $pageSize = Control::GetRequest("ps", 20);
        $tabIndex = Control::GetRequest("tab_index", 0);
        $channelId = Control::GetRequest("channel_id", 0);
        $siteId = Control::GetRequest("site_id", 0);
        $pageBegin = ($pageIndex - 1) * $pageSize;

        $templateLibraryManageData = new TemplateLibraryManageData();
        $allCount = 0;
        $templateLibraryList = $templateLibraryManageData->GetListOfPage($siteId,$pageBegin, $pageSize, $allCount);

        $listName = "template_library_list";
        if ($templateLibraryList != null && count($templateLibraryList) > 0) {

            Template::ReplaceList($templateContent, $templateLibraryList, $listName);

            $styleNumber = 1;
            $pagerTemplate = Template::Load("pager/pager_style$styleNumber.html", "common");
            $isJs = FALSE;
            $navUrl = "default.php?secu=manage&mod=template_library&m=manage&channel_id=$channelId&p={0}&ps=$pageSize";
            $jsFunctionName = "";
            $jsParamList = "";
            $pagerButton = Pager::ShowPageButton($pagerTemplate, $navUrl, $allCount, $pageSize, $pageIndex ,$styleNumber = 1, $isJs, $jsFunctionName, $jsParamList);


            $replace_arr = array(
                "{PagerButton}" => $pagerButton
            );
            $templateContent = strtr($templateContent, $replace_arr);
        }else{
            Template::RemoveCustomTag($templateContent, $listName);
            $templateContent = str_ireplace("{PagerButton}", Language::Load("document", 7), $templateContent);
        }


        $replaceArr = array(
            "{ChannelId}" => $channelId,
            "{SiteId}" => $siteId,
            "{tabIndex}" => $tabIndex
        );
        $templateContent = strtr($templateContent, $replaceArr);

        parent::ReplaceEnd($templateContent);
        return $templateContent;
    }
/*
    public function GenChannelManage() {
        $templateContent = Template::Load("template/templatelibrary_channelmanage.tpl");
        $templateLibraryId = Control::GetRequest("tid", 0);

        $templateLibraryChannelData = new TemplateLibraryChannelData();
        $arrList = $templateLibraryChannelData->GetChannelList($templateLibraryId);

        $listName = "templatelibrarychannellist";
        Template::ReplaceList($templateContent, $arrList, $listName);

        $replaceArr = array(
            "{templatelibraryid}" => $templateLibraryId,
            "{channltype_1}" => "新闻信息类",
            "{channltype_2}" => "咨询答复类",
            "{channltype_3}" => "图片轮换类",
            "{channltype_4}" => "产品类",
            "{channltype_5}" => "频道结合产品类",
            "{channltype_6}" => "活动类",
            "{channltype_7}" => "投票类",
            "{channltype_8}" => "自定义页面类",
            "{channltype_9}" => "友情链接类",
            "{channltype_10}" => "活动表单类",
            "{channltype_11}" => "文字直播类",
            "{channltype_0}" => "站点首页类",
        );

        $templateContent = strtr($templateContent, $replaceArr);
        parent::ReplaceEnd($templateContent);
        return $templateContent;
    }

    public function GenTemplateManage() {
        $templateContent = Template::Load("template/templatelibrary_templatemanage.tpl");
        $templateLibraryId = Control::GetRequest("tid", 0);

        $templateLibraryData = new TemplateLibraryData();
        $arrList = $templateLibraryData->GetList($pageBegin, $pageSize, $allCount);
        parent::ReplaceEnd($templateContent);
        return $templateContent;
    }

    public function GenAddTemplateChannel() {
        $templateContent = Template::Load("template/templatelibrary_templatechannel_deal.tpl");
        $templateLibraryId = Control::GetRequest("templatelibraryid", 0);

        $adminUserId = Control::GetAdminUserID();
        parent::ReplaceFirst($templateContent);

        if ($templateLibraryId > 0) {
            $templateLibraryChannelData = new TemplateLibraryChannelData();

            //siteid暂时先等于0
            //$siteId = $templateLibraryChannelData->GetSiteID($templateId); 
            $siteId = 0;

            $replaceArr = array(
                "{templatelibraryid}" => $templateLibraryId,
                "{siteid}" => $siteId,
                "{documentchannelname}" => "",
                "{sort}" => "0",
                "{adminuserid}" => strval($adminUserId),
            );

            if (!empty($_POST)) {
                $result = $templateLibraryChannelData->Create();
                if ($result > 0) {
                    Control::ShowMessage(Language::Load('document', 1));
                    $jsCode = "self.parent.tb_remove();self.parent.location.reload();";
                    Control::RunJS($jsCode);
                } else {
                    Control::ShowMessage(Language::Load('document', 2));
                }
            }

            $templateContent = strtr($templateContent, $replaceArr);

            //
            $patterns = "/\{s_(.*?)\}/";
            $templateContent = preg_replace($patterns, "", $templateContent);
        }
        parent::ReplaceEnd($templateContent);
        return $templateContent;
    }

    public function GenEditTemplateChannel() {
        $templateContent = Template::Load("template/templatelibrary_templatechannel_deal.tpl");
        $templateLibraryChannelId = Control::GetRequest("tcid", 0);

        $adminUserId = Control::GetAdminUserID();
        parent::ReplaceFirst($templateContent);

        $templateLibraryChannelData = new TemplateLibraryChannelData();
        $templateLibraryChannel = $templateLibraryChannelData->GetOne($templateLibraryChannelId);
        Template::ReplaceOne($templateContent, $templateLibraryChannel);

        if (!empty($_POST)) {
            $templateLibraryChannelData = new TemplateLibraryChannelData();
            $result = $templateLibraryChannelData->Modify($templateLibraryChannelId);

            if ($result > 0) {
                Control::ShowMessage(Language::Load('document', 3));
            } else {
                Control::ShowMessage(Language::Load('document', 4));
            }
        }

        parent::ReplaceEnd($templateContent);
        return $templateContent;
    }*/

    public function GenCreate() {
        $resultJavaScript="";
        $tempContent = Template::Load("template/template_library_deal.html","common");
        $siteId = Control::GetRequest("site_id", 0);
        $tabIndex = Control::GetRequest("tab_index", 0);
        $templateLibraryManageData = new TemplateLibraryManageData();

        ///////////////判断是否有操作权限///////////////////
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


        parent::ReplaceFirst($tempContent);
        
        if (!empty($_POST)) {

            $createDate=stripslashes(date("Y-m-d H:i:s", time()));
            $templateLibraryName = Control::PostRequest("f_TemplateLibraryName", "");
            $newId = $templateLibraryManageData->Create($templateLibraryName, $manageUserId, $siteId,$createDate);
            if ($newId > 0) {


                Control::ShowMessage(Language::Load('template_library', 1));//提交成功!
                $closeTab = Control::PostRequest("CloseTab",0);
                if($closeTab == 1){
                    //$resultJavaScript .= Control::GetCloseTab();
                    Control::GoUrl("/default.php?secu=manage&mod=template_library&m=manage&tab_index=$tabIndex");
                } else {
                    Control::GoUrl($_SERVER["PHP_SELF"] . "?" . $_SERVER['QUERY_STRING']);
                }
            }else{
                $resultJavaScript .= Control::GetJqueryMessage(Language::Load('template_library', 2));//提交失败!插入或修改数据库错误！
            }
        }

        $tempContent = str_ireplace("{TabIndex}", $tabIndex, $tempContent);
        $tempContent = str_ireplace("{SiteId}", $siteId, $tempContent);

        $fieldsOfTemplateLibrary = $templateLibraryManageData->GetFields();
        parent::ReplaceWhenCreate($tempContent, $fieldsOfTemplateLibrary);


        //替换掉{s XXX}的内容
        $patterns = '/\{s_(.*?)\}/';
        $tempContent = preg_replace($patterns, "", $tempContent);

        parent::ReplaceEnd($tempContent);
        $tempContent = str_ireplace("{ResultJavascript}", $resultJavaScript, $tempContent);
        return $tempContent;
    }


    public function GenModify() {
        $resultJavaScript="";
        $tempContent = Template::Load("template/template_library_deal.html","common");
        $templateLibraryId = Control::GetRequest("template_library_id", 0);
        $tabIndex = Control::GetRequest("tab_index", 0);
        $templateLibraryManageData = new TemplateLibraryManageData();
        $siteId=$templateLibraryManageData->GetSiteId($templateLibraryId);

        ///////////////判断是否有操作权限///////////////////
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


        parent::ReplaceFirst($tempContent);

        if (!empty($_POST)) {
            $newId = $templateLibraryManageData->Modify($_POST, $templateLibraryId);
            if ($newId > 0) {


                Control::ShowMessage(Language::Load('template_library', 1));//提交成功!
                $closeTab = Control::PostRequest("CloseTab",0);
                if($closeTab == 1){
                    //$resultJavaScript .= Control::GetCloseTab();
                    Control::GoUrl("/default.php?secu=manage&mod=template_library&m=manage&tab_index=$tabIndex");
                } else {
                    Control::GoUrl($_SERVER["PHP_SELF"] . "?" . $_SERVER['QUERY_STRING']);
                }
            }else{
                $resultJavaScript .= Control::GetJqueryMessage(Language::Load('template_library', 2));//提交失败!插入或修改数据库错误！
            }
        }

        $tempContent = str_ireplace("{SiteId}", $siteId, $tempContent);
        $tempContent = str_ireplace("{TabIndex}", $tabIndex, $tempContent);


        $arrOne = $templateLibraryManageData->GetOne($templateLibraryId);
        if(!empty($arrOne)){
            Template::ReplaceOne($tempContent, $arrOne);
        }else{
            $resultJavaScript .= Control::GetJqueryMessage(Language::Load('template_library', 4));//原有数据获取失败！请谨慎修改！
        }



        //替换掉{s XXX}的内容
        $patterns = '/\{s_(.*?)\}/';
        $tempContent = preg_replace($patterns, "", $tempContent);

        parent::ReplaceEnd($tempContent);
        $tempContent = str_ireplace("{ResultJavascript}", $resultJavaScript, $tempContent);
        return $tempContent;
    }
}

?>
