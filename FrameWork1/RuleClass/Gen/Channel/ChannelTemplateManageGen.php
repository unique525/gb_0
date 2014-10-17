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
            case "remove_to_bin":
                $result = self::GenRemoveToBin();
                break;
            case "list":
                $result = self::GenList();
                break;
            case "async_modify_state":
                $result = self::AsyncModifyState();
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

        if($channelId>0 && $manageUserId>0){

            $channelTemplateManageData = new ChannelTemplateManageData();

            $tempContent = Template::Load("channel/channel_template_deal.html", "common");

            parent::ReplaceFirst($tempContent);

            if (!empty($_POST)) {

                $httpPostData = $_POST;

                $channelTemplateId = $channelTemplateManageData->Create($httpPostData);
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
                        $resultJavaScript .= Control::GetCloseTab();
                    } else {
                        Control::GoUrl($_SERVER["PHP_SELF"] . "?" . $_SERVER['QUERY_STRING']);
                    }
                } else {
                    $resultJavaScript .=
                        Control::GetJqueryMessage(Language::Load('channel_template', 2)); //新增失败！

                }

            }

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

        if ($channelTemplateId > 0 && $manageUserId > 0) {
            $tempContent = Template::Load("channel/channel_template_deal.html", "common");
            parent::ReplaceFirst($tempContent);

            $channelTemplateManageData = new ChannelTemplateManageData();

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

                    //删除缓冲
                    DataCache::RemoveDir(CACHE_PATH . '/channel_template_data');
                    $closeTab = Control::PostRequest("CloseTab", 0);
                    if ($closeTab == 1) {
                        $resultJavaScript .= Control::GetCloseTab();
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
     * 修改文档状态 状态值定义在Data类中
     * @return string 返回Jsonp修改结果
     */
    private function AsyncModifyState() {
        $result = -1;
        $siteId = Control::GetRequest("site_id", 0);
        $state = Control::GetRequest("state", -1);
        $manageUserId = Control::GetManageUserId();

        if($siteId>0 && $state>=0 && $manageUserId>0){
            //判断权限
            /**********************************************************************
             ******************************判断是否有操作权限**********************
             **********************************************************************/
            $manageUserAuthorityManageData = new ManageUserAuthorityManageData();
            $channelId = 0;
            $can = $manageUserAuthorityManageData->CanManageSite($siteId,$channelId,$manageUserId);
            if(!$can){
                $result = -10;
            }else{
                $siteManageData = new SiteManageData();
                $result = $siteManageData->ModifyState($siteId,$state);
                //加入操作日志
                $operateContent = 'Modify State Site,GET PARAM:'.implode('|',$_GET).';\r\nResult:'.$result;
                self::CreateManageUserLog($operateContent);
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


} 