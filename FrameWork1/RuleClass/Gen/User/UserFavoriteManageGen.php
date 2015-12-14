<?php

/**
 * 后台管理 会员 生成类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_Gen_User
 * @author yin
 */
class UserFavoriteManageGen extends BaseManageGen implements IBaseManageGen {
    /**
     * 引导方法
     * @return string 返回执行结果
     */
    public function Gen() {
        $result = "";
        $method = Control::GetRequest("m", "");
        switch ($method) {
            case "list":
                $result = self::GenList();
                break;
            case "async_remove_bin":
                $result = self::AsyncRemoveBin();
                break;
        }
        $result = str_ireplace("{method}", $method, $result);
        return $result;
    }

    private function GenList(){
        $templateContent = Template::Load("user/user_favorite.html","common");
        $siteId = Control::GetRequest("site_id",0);
        $pageIndex = Control::GetRequest("p",1);
        $pageSize = Control::GetRequest("ps",0);

        parent::ReplaceFirst($templateContent);
        if($siteId > 0){
            $allCount = 0;
            $pageBegin = ($pageIndex - 1) * $pageSize;

            $searchKey = Control::GetRequest("search_key", "");

            $userFavoriteManageData = new UserFavoriteManageData();
            $arrUserFavoriteList = $userFavoriteManageData->GetList($siteId,$pageBegin,$pageSize,$allCount,$searchKey);

            $tagId = "user_favorite";
            if(count($arrUserFavoriteList) > 0){
                $styleNumber = 1;
                $pagerTemplate = Template::Load("pager/pager_style$styleNumber.html","common");
                $isJs = FALSE;
                $navUrl = "/default.php?secu=manage&mod=user_favorite&m=list&site_id=$siteId&p={0}&ps=$pageSize";
                $jsFunctionName = "";
                $jsParamList = "";
                $pagerButton = Pager::ShowPageButton($pagerTemplate, $navUrl, $allCount, $pageSize, $pageIndex, $styleNumber, $isJs, $jsFunctionName, $jsParamList);
                Template::ReplaceList($templateContent,$arrUserFavoriteList,$tagId);
                $templateContent = str_ireplace("{pagerButton}", $pagerButton, $templateContent);
            }else{
                Template::RemoveCustomTag($templateContent);
                $templateContent = str_ireplace("{pagerButton}", "",$templateContent);
            }
            parent::ReplaceEnd($templateContent);
        }
        return $templateContent;
    }

    private function AsyncRemoveBin(){
        $userFavoriteId = Control::GetRequest("user_favorite_id",0);
        $siteId = Control::GetRequest("site_id",0);

        if($userFavoriteId > 0 && $siteId > 0){
            $userFavoriteManageData = new UserFavoriteManageData();
            $result = $userFavoriteManageData->Delete($userFavoriteId,$siteId);

            return Control::GetRequest("jsonpcallback","").'({"result":'.$result.'})';
        }else{
            return Control::GetRequest("jsonpcallback","").'({"result":-1})';
        }
    }
}

?>
