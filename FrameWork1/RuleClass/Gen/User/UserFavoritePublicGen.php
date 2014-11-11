<?php

/**
 * 前台管理 会员收藏 生成类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_Gen_User
 * @author yin
 */
class UserFavoritePublicGen extends BasePublicGen implements IBasePublicGen {

    /**
     *
     */
    const CREATE_FAIL = -1;

    /**
     *
     */
    const DELETE_FAIL = -1;

    /**
     *
     */
    const DELETE_SUCCESS = 1;

    /**
     *
     */
    const TABLE_TYPE_PRODUCT = 1;

    /**
     *
     */
    const CREATE_IS_EXIST = -2;

    /**
     *
     */
    const IS_NOT_LOGIN= -3;

    public function GenPublic() {
        $result = "";
        $method = Control::GetRequest("a", "");
        switch ($method) {
            case "async_add":
                $result = self::AsyncCreate();
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
    private function AsyncCreate(){
        $tableId = intval(Control::PostRequest("table_id",0));
        $tableType = intval(Control::PostRequest("table_type",0));
        $siteId = parent::GetSiteIdByDomain();
        $userId = Control::GetUserId();
        $userFavoriteTag = Control::PostRequest("user_favorite_tag","");

        if($tableId > 0 && $tableType > 0 && !empty($userFavoriteTag) && $userId > 0 && $siteId > 0){
            $userFavoritePublicData = new UserFavoritePublicData();
            $userFavoriteTitle = "";
            $uploadFileId = 0;
            $userFavoriteUrl = "";
            if($tableType == UserFavoriteData::TableType_Product){
                $userFavoriteTitle = Control::GetRequest("user_favorite_title","");
                $productPublicData = new ProductPublicData();
                $arrProductOne =$productPublicData->GetOneForUserFavorite($tableId);
                if($userFavoriteTitle == ""){
                    $userFavoriteTitle = $arrProductOne["ProductName"];
                }
                $channelId = $arrProductOne["ChannelId"];
                $uploadFileId = $arrProductOne["TitlePic1UploadFileId"];
                $userFavoriteUrl = "/default.php?&mod=product&a=detail&channel_id=".$channelId."&product_id=".$tableId;

            }

            //判断是否重复
            $canAddFavorite = $userFavoritePublicData->CheckIsExist($tableId,$tableType);
            if($canAddFavorite > 0){
                return Control::GetRequest("jsonpcallback","").'({"result":'.self::CREATE_IS_EXIST.'})';
            }
            if($userFavoriteTitle != "" && $userFavoriteUrl != "" && $uploadFileId > 0){
                $userFavoritePublicData = new UserFavoritePublicData();
                $result = $userFavoritePublicData->Create($userId,$tableId,$tableType,$siteId,$userFavoriteTitle,$userFavoriteUrl,$uploadFileId,$userFavoriteTag);
                if($result > 0){
                    return Control::GetRequest("jsonpcallback","").'({"result":'.$result.'})';
                }else{
                    return Control::GetRequest("jsonpcallback","").'({"result":'.self::CREATE_FAIL.'})';
                }
            }else{
                return Control::GetRequest("jsonpcallback","").'({"result":'.self::CREATE_FAIL.'})';
            }
        }else if($userId <= 0){
            return Control::GetRequest("jsonpcallback","").'({"result":'.self::IS_NOT_LOGIN.'})';
        }else{
            return Control::GetRequest("jsonpcallback","").'({"result":'.self::CREATE_FAIL.'})';
        }
    }

    /**
     * 获取列表
     * @return string
     */
    private function GenList() {
        $templateFileUrl = "user/user_favorite.html";
        $templateName = "default";
        $templatePath = "front_template";
        $templateContent = Template::Load($templateFileUrl, $templateName, $templatePath);
        $userId = Control::GetUserId();
        $siteId = parent::GetSiteIdByDomain();
        if($userId > 0){
            $tagId = "user_favorite";
            $userFavoritePublicData = new UserFavoritePublicData();
//            $arrUserFavoriteList = $userFavoritePublicData->GetList($userId,$siteId);
//            if(count($arrUserFavoriteList) > 0){
//                Template::ReplaceList($templateContent,$arrUserFavoriteList,$tagId);
//            }else{
//                Template::RemoveCustomTag($templateContent, $tagId);
//            }
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