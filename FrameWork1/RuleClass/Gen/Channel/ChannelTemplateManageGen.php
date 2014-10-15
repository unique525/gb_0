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
     * 新增站点
     * @return string 模板内容页面
     */
    private function GenCreate(){
        $manageUserId = Control::GetManageUserId();
        if($manageUserId<=0){
            return Language::Load("channel_template",8);
        }

        $tempContent = Template::Load("channel/channel_template_deal.html", "common");



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
        }else{

        }

        parent::ReplaceEnd($tempContent);
        return $tempContent;
    }


} 