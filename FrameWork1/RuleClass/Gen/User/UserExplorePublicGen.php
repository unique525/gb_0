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
            case "async_get_list":
                $result = self::AsyncGetList();
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
            Control::DelUserExploreCookie($userId);
            session_start();
            session_destroy();
        }
        return Control::GetRequest("jsonpcallback", "") . "({'result':'1'})";
    }

    /**
     * ajax方法得到用户浏览记录数据
     * @return string 产品列表JSON
     */
    private function AsyncGetList()
    {

    }
}

?>