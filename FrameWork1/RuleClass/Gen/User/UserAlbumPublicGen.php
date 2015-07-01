<?php
/**
 * Created by PhpStorm.
 * User: zcmzc
 * Date: 14-1-21
 * Time: 下午12:11
 */

class UserAlbumPublicGen extends BasePublicGen implements IBasePublicGen{

    public function GenPublic()
    {
        $result = "";
        $action = Control::GetRequest("a", "");
        switch ($action) {

            case "gen":
                $result = self::GenUserAlbum();
                break;
        }

        $result = str_ireplace("{action}", $action, $result);
        return $result;
    }

    private function GenUserAlbum(){

        $userId = Control::GetUserId();

        if($userId<=0){
            Control::GoUrl("/default.php?mod=user&a=login&re_url=". urlencode("/default.php?mod=user_album&a=gen"));
            return "";
        }
        $siteId = Control::GetRequest("site_id", 0);
        if ($siteId <= 0) {
            $siteId = parent::GetSiteIdByDomain();
        }
        $siteId = parent::GetSiteIdByDomain();
        echo $siteId;
        $defaultTemp = "user_album_detail";
        $tempContent = parent::GetDynamicTemplateContent(
            $defaultTemp, $siteId);
        return $tempContent;

    }
} 