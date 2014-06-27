<?php
/**
 * Created by PhpStorm.
 * User: yin
 * Date: 14-6-25
 * Time: 下午3:21
 */

class UserGroupManageGen extends BaseManageGen implements IBaseManageGen{
    public function Gen(){
        $result = "";
        $method = Control::GetRequest("m","");

        switch($method){
            case "list":
                $result = self::GenList();
                break;
            case "modify":
                $result = self::GenModiy();
                break;
        }
        return $result;
    }

    private function GenModiy(){
        $templateContent = Template::Load("user/user_group_deal.html","common");
        $userGroupId = Control::GetRequest("user_group_id",0);
        $siteId = Control::GetRequest("site_id",0);

        if($userGroupId > 0 && $siteId > 0){
            $userGroupManageData = new UserGroupManageData();
            $arrUserGroupOne = $userGroupManageData->GetOne($userGroupId,$siteId);
        }


        parent::ReplaceEnd($templateContent);
        return $templateContent;
    }

    private function GenList(){
        $templateContent = Template::Load("user/user_group_list.html","common");
        $siteId = Control::GetRequest("site_id",0);
        $pageIndex = Control::GetRequest("p",1);
        $pageSize = Control::GetRequest("ps",0);
        if($siteId > 0){
            $allCount = 0;
            $pageBegin = ($pageIndex - 1) * $pageSize;
            $userGroupManageData = new UserGroupManageData();
            $arrUserGroupList = $userGroupManageData->GetList($siteId,$pageBegin,$pageSize,$allCount);
            $tagId = "user_group_list";
            if(count($arrUserGroupList) > 0){
                $styleNumber = 1;
                $pagerTemplate = Template::Load("pager/pager_style$styleNumber.html","common");
                $isJs = FALSE;
                $navUrl = "default.php?secu=manage&mod=user_group&m=list&p={0}&ps=$pageSize";
                $jsFunctionName = "";
                $jsParamList = "";
                $pagerButton = Pager::ShowPageButton($pagerTemplate, $navUrl, $allCount, $pageSize, $pageIndex, $styleNumber, $isJs, $jsFunctionName, $jsParamList);
                Template::ReplaceList($templateContent,$arrUserGroupList,$tagId);
                $templateContent = str_ireplace("{pagerButton}", $pagerButton, $templateContent);
            }else{
                Template::RemoveCustomTag($templateContent, $tagId);
                $templateContent = str_ireplace("{pagerButton}","", $templateContent);
            }

            parent::ReplaceEnd($templateContent);
            return $templateContent;
        }
    }
}