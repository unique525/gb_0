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

        $tableId = intval(Control::PostOrGetRequest("table_id",0));
        $tableType = intval(Control::PostOrGetRequest("table_type",0));
        $siteId = parent::GetSiteIdByDomain();
        $userId = Control::GetUserId();

        //收藏标签可以为空
        $userFavoriteTag = urldecode(Control::PostOrGetRequest("user_favorite_tag",""));

        if($tableId > 0 && $tableType > 0 && $userId > 0 && $siteId > 0){
            $userFavoritePublicData = new UserFavoritePublicData();
            $userFavoriteTitle = "";
            $uploadFileId = 0;
            $userFavoriteUrl = "";
            if($tableType == UserFavoriteData::TABLE_TYPE_PRODUCT){
                $userFavoriteTitle = urldecode(Control::PostOrGetRequest("user_favorite_title",""));
                $productPublicData = new ProductPublicData();
                $arrProductOne =$productPublicData->GetOne($tableId);
                if($userFavoriteTitle == ""){
                    $userFavoriteTitle = $arrProductOne["ProductName"];
                }
                $channelId = $arrProductOne["ChannelId"];
                $uploadFileId = $arrProductOne["TitlePic1UploadFileId"];
                $userFavoriteUrl = "/default.php?&mod=product&a=detail&channel_id=".$channelId."&product_id=".$tableId;

            }elseif($tableType == UserFavoriteData::TABLE_TYPE_FORUM_TOPIC){

                $userFavoriteTitle = urldecode(Control::PostOrGetRequest("user_favorite_title",""));
                $userFavoriteUrl = urldecode(Control::PostOrGetRequest("user_favorite_url",""));

            }
            //判断是否重复
            $canAddFavorite = $userFavoritePublicData->CheckIsExist($tableId,$tableType);
            if($canAddFavorite > 0){
                $result = self::CREATE_IS_EXIST;
            }else{
                if($userFavoriteTitle != "" && $userFavoriteUrl != ""){

                    $result = $userFavoritePublicData->Create(
                        $userId,
                        $tableId,
                        $tableType,
                        $siteId,
                        $userFavoriteTitle,
                        $userFavoriteUrl,
                        $uploadFileId,
                        $userFavoriteTag
                    );
                    if($result > 0){

                    }else{
                        $result = self::CREATE_FAIL;
                    }
                }else{
                    $result = self::CREATE_FAIL;
                }
            }

        }else if($userId <= 0){
            $result = self::IS_NOT_LOGIN;
        }else{
            $result = self::CREATE_FAIL;
        }

        return Control::GetRequest("jsonpcallback","").'({"result":'.$result.'})';

    }

    /**
     * 获取列表
     * @return string
     */
    private function GenList() {
        $userId = Control::GetUserId();
        $siteId = parent::GetSiteIdByDomain();
        if($userId > 0){
            //$templateFileUrl = "user/user_favorite_list.html";
            //$templateName = "default";
            //$templatePath = "front_template";
            //$templateContent = Template::Load($templateFileUrl, $templateName, $templatePath);
            $templateContent = parent::GetDynamicTemplateContent("user_favorite_list");
            parent::ReplaceFirst($templateContent);
            parent::ReplaceSiteInfo($siteId, $templateContent);

            $pageIndex = Control::GetRequest("p",1);
            $pageSize = Control::GetRequest("ps",16);

            $tagId = "user_favorite_list";
            $pageBegin = ($pageIndex - 1) * $pageSize;
            $pageSize = 16;
            $allCount = 0;
            $userFavoritePublicData = new UserFavoritePublicData();
            $arrUserFavoriteList = $userFavoritePublicData->GetList($userId,$siteId,$pageBegin,$pageSize,$allCount);
            if(count($arrUserFavoriteList) > 0){
                $styleNumber = 1;
                $pagerTemplate = Template::Load("pager/pager_style$styleNumber.html","common");
                $isJs = FALSE;
                $navUrl = "/default.php?mod=user_favorite&a=list&p={0}&ps=$pageSize";
                $jsFunctionName = "";
                $jsParamList = "";
                $pagerButton = Pager::ShowPageButton($pagerTemplate, $navUrl, $allCount, $pageSize, $pageIndex, $styleNumber, $isJs, $jsFunctionName, $jsParamList);
                Template::ReplaceList($templateContent,$arrUserFavoriteList,$tagId);
                $templateContent = str_ireplace("{pagerButton}", $pagerButton, $templateContent);
            }else{
                $templateContent = Template::ReplaceCustomTag($templateContent,$tagId,Language::Load("user_favorite",1));
                $templateContent = str_ireplace("{pagerButton}", "", $templateContent);
            }
            parent::ReplaceEnd($templateContent);
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
        $userFavoriteId = intval(Control::GetRequest("user_favorite_id",0));
        $userId = intval(Control::GetUserId());
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