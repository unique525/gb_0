<?php

/**
 * 前台管理 会员收藏 生成类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_Gen_User
 * @author yin
 */
class UserFavoritePublicGen extends BasePublicGen implements IBasePublicGen {

    const CREATE_FAIL = -1;

    const DELETE_FAIL = -1;

    const DELETE_SUCCESS = 1;

    const TABLE_TYPE_PRODUCT = 1;

    public function GenPublic() {
        $result = "";
        $method = Control::GetRequest("a", "");
        switch ($method) {
            case "add":
                $result = self::GenCreate();
                break;
            case "list":
                $result = self::GenList();
                break;
            case "async_remove_bin":
                $result = self::AsyncRemoveBin();
                break;
        }
        return $result;
    }

    /**
     * 新增
     * @return string
     */
    private function GenCreate(){
        $tableId = intval(Control::GetRequest("table_id",0));
        $tableType = intval(Control::GetRequest("table_type",0));
        $userFavoriteTitle = Control::GetRequest("user_favorite_title","");
        $userId = Control::GetUserId();
        $userFavoriteUrl = $_SERVER['HTTP_REFERER'];
        $userFavoriteTag = Control::GetRequest("user_favorite_tag","");
        if($tableId > 0 && $tableType > 0 && !empty($userFavoriteTitle) && $userId > 0){
            $userFavoritePublicData = new UserFavoritePublicData();
            $result = $userFavoritePublicData->Create($userId,$tableId,$tableType,$userFavoriteTag,$userFavoriteTitle,$userFavoriteUrl);
            return Control::GetRequest("jsonpcallback","").'({"result":'.$result.'})';
        }else{
            return Control::GetRequest("jsonpcallback","").'({"result":'.self::CREATE_FAIL.'})';
        }
    }

    /**
     * 获取列表
     * @return string
     */
    private function GenList() {
        Control::SetUserCookie(1,"test");
        $templateFileUrl = "user/user_favorite.html";
        $templateName = "default";
        $templatePath = "front_template";
        $templateContent = Template::Load($templateFileUrl, $templateName, $templatePath);
        $userId = Control::GetUserId();
        if($userId > 0){
            $tagId = "user_favorite";
            $userFavoritePublicData = new UserFavoritePublicData();
            $arrUserFavoriteList = $userFavoritePublicData->GetList($userId);
            if(count($arrUserFavoriteList) > 0){
                Template::ReplaceList($templateContent,$arrUserFavoriteList,$tagId);
            }else{
                Template::RemoveCustomTag($templateContent, $tagId);
            }
            return $templateContent;
        }else{
            return "";
        }
    }

    /**
     * 删除
     * @return string
     */
    private function AsyncRemoveBin(){
        $userFavoriteId = Control::GetRequest("user_favorite_id",0);
        $userId = Control::GetUserId();
        if($userId > 0 && $userFavoriteId > 0){
            $userFavoritePublicData = new UserFavoritePublicData();
            $result = $userFavoritePublicData->Delete($userFavoriteId,$userId);
            if($result > 0){
                return Control::GetRequest("jsonpcallback","").'({"result":'.self::DELETE_SUCCESS.'})';
            }else{
                return Control::GetRequest("jsonpcallback","").'({"result":'.self::DELETE_FAIL.'})';
            }
        }else{
            return Control::GetRequest("jsonpcallback","").'({"result":'.self::DELETE_FAIL.'})';
        }
    }
}

?>