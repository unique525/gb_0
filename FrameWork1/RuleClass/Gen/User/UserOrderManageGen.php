<?php
/**
 * 后台管理 会员订单 生成类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_Gen_User
 * Time: 下午12:09
 */
class UserOrderManageGen extends BaseManageGen implements IBaseManageGen{
    public function Gen(){
        $result = "";
        $method = Control::GetRequest("m", "");
        switch($method){
            case "modify":
                $result = self::GenModify();
                break;
            case "list":
                $result = self::GenList();
                break;
            case "modify_state":
                $result = self::AsyncModifyState();
                break;
        }
        $result = str_ireplace("{method}", $method, $result);
        return $result;
    }

    private function GenModify(){
        $userOrderId = Control::GetRequest("user_order_id",0);
        if($userOrderId > 0){
            $templateContent = Template::Load("user/user_order_deal.html","common");
            return $templateContent;
        }else{
            return null;
        }
    }

    private function GenList(){
        $siteId = Control::GetRequest("site_id",0);
        if($siteId > 0){
            $pageIndex = Control::GetRequest("p",1);
            $pageSize = Control::GetRequest("ps",0);
            $templateContent = Template::Load("user/user_order_list.html","common");

            $allCount = 0;
            $pageBegin = ($pageIndex - 1) * $pageSize;
            $userOrderManageData = new UserOrderManageData();
            $arrUserOrderList = $userOrderManageData->GetList($siteId,$pageBegin,$pageSize,$allCount);

            $tagId = "user_order_list";
            if(count($arrUserOrderList) > 0){
                $styleNumber = 1;
                $pagerTemplate = Template::Load("pager/pager_style$styleNumber.html","common");
                $isJs = FALSE;
                $navUrl = "/default.php?secu=manage&mod=user_order&m=list&site_id=$siteId&p={0}&ps=$pageSize";
                $jsFunctionName = "";
                $jsParamList = "";
                $pagerButton = Pager::ShowPageButton($pagerTemplate, $navUrl, $allCount, $pageSize, $pageIndex, $styleNumber, $isJs, $jsFunctionName, $jsParamList);
                Template::ReplaceList($templateContent,$arrUserOrderList,$tagId);
                $templateContent = str_ireplace("{pagerButton}", $pagerButton, $templateContent);
            }
            $replace_arr = array(
                "{SiteId}" => $siteId
            );
            $templateContent = strtr($templateContent,$replace_arr);
            parent::ReplaceEnd($templateContent);
            return $templateContent;
        }else{
            return null;
        }
    }

    private function AsyncModifyState(){
        $siteId = Control::GetRequest("site_id",0);
        $userOrderId = Control::GetRequest("user_order_id",0);
        if($siteId > 0 && $userOrderId >0){
            return $_GET['jsonpcallback'].'';
        }else{
            return $_GET['jsonpcallback'].'';
        }
    }
}