<?php
/**
 * 后台管理 会员相册类别 生成类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_Gen_User
 * Time: 下午12:09
 */

class UserAlbumTypeManageGen extends BaseManageGen{
    public function Gen(){
        $result = "";
        $method = Control::GetRequest("m", "");
        switch($method){
            case "create":
                $result = self::GenCreate();
                break;
            case "list":
                $result = self::GenList();
                break;
        }
        return $result;
    }

    public function GenCreate(){
        $templateContent = Template::Load("");

        $siteId = Control::GetRequest("site_id",0);
        if(!empty($_POST)){
            $userAlbumTypeManageData = new UserAlbumTypeManageData();
            $result = $userAlbumTypeManageData->Create($siteId);
            if($result > 0){

            }else{

            }
        }
        return $templateContent;
    }

    public function GenList(){
        $siteId = Control::GetRequest("site_id",0);
        $templateContent = Template::Load("user/user_album_type_list.html","common");
        if($siteId > 0){
            $userAlbumTypeManageData = new UserAlbumTypeManageData();

            $arrUserAlbumTypeList = $userAlbumTypeManageData->GetList($siteId);
            $tagId = "user_album_type_list";
            Template::ReplaceList($templateContent,$arrUserAlbumTypeList,$tagId);


            parent::ReplaceEnd($templateContent);
        }
        return $templateContent;
    }
}
?>