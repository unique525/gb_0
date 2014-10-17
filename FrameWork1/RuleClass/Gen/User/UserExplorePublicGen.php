<?php

/**
 * 前台管理 会员收藏 生成类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_Gen_User
 * @author hy
 */
class UserExplorePublicGen extends BasePublicGen implements IBasePublicGen {


    public function GenPublic() {
        $result = "";
        $method = Control::GetRequest("a", "");
        switch ($method) {
            case "async_delete":
                $result = self::AsyncDelete();
                break;
            case "ajax_list":
                $result = self::AjaxList();
                break;
        }
        return $result;
    }

    /**
     * 删除用户浏览记录COOKIE
     */
    public static function AsyncDelete()
    {
        $userId = Control::GetUserId();
        if ($userId > 0) {
            setcookie("ExploreHistory_".$userId, 0, time() - 1);
            session_start();
            session_destroy();
        }
        return Control::GetRequest("jsonpcallback", "") . "({'result':'1'})";
    }

    /**
     * ajax方法得到用户浏览记录数据
     * @return string 产品列表HTML
     */
    private function AjaxList()
    {
        $tableId = intval(Control::GetRequest("table_id",0));
        $tableType = intval(Control::GetRequest("table_type",0));
        $siteId = intval(Control::GetRequest("site_id",0));
        $userId = Control::GetUserId();
        $order = Control::GetRequest("order", "");
        $top = Control::GetRequest("ps", 4);
        $userExplorePublicData = new UserExplorePublicData();
        $arrList = $userExplorePublicData->GetList($siteId, $userId, $tableType, $tableId, $order, $top);
        $tempArrList = json_encode($arrList);
        return Control::GetRequest("jsonpcallback","") . '({"result":' . $tempArrList . '})';
    }
}

?>