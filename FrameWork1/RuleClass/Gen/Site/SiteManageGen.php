<?php

/**
 * 后台站点配置生成类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_Gen_Site
 * @author zhangchi
 */
class SiteManageGen extends BaseManageGen implements IBaseManageGen {
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
            return Language::Load("site",8);
        }

        $tempContent = Template::Load("site/site_deal.html", "common");



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
        return $_GET['JsonpCallBack'].'({"result":'.$result.'})';
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
        $tempContent = Template::Load("site/site_list.html", "common");

        parent::ReplaceFirst($tempContent);

        ////////////////////////////////////////////////////
        ///////////////输出权限到页面///////////////////
        ////////////////////////////////////////////////////

        $pageSize = Control::GetRequest("ps", 20);
        $pageIndex = Control::GetRequest("p", 1);
        $searchKey = Control::GetRequest("search_key", "");
        $searchType = Control::GetRequest("search_type", -1);
        $searchKey = urldecode($searchKey);

        if ($pageIndex > 0) {
            $pageBegin = ($pageIndex - 1) * $pageSize;
            $tagId = "site_list";
            $allCount = 0;
            $siteManageData = new SiteManageData();
            $arrList = $siteManageData->GetList($pageBegin, $pageSize, $allCount, $searchKey, $searchType, $manageUserId);
            if (count($arrList) > 0) {
                Template::ReplaceList($tempContent, $arrList, $tagId);
            } else {
                Template::RemoveCustomTag($tempContent, $tagId);
                //$tempContent = str_ireplace("{pager_button}", Language::Load("site", 7), $tempContent);
            }
        }

        parent::ReplaceEnd($tempContent);
        return $tempContent;
    }
} 