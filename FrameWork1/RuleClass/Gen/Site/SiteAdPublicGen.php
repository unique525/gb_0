<?php
/**
 * Created by PhpStorm.
 * User: 525
 * Date: 14-6-5
 * Time: 下午1:34
 */

class SiteAdPublicGen extends BasePublicGen implements IBasePublicGen {

    /**
     * 引导方法
     * @return string 返回执行结果
     */
    public function GenPublic() {
        $result = "";
        $method = Control::GetRequest("m", "");
        switch ($method) {
            case "pre_show":
                $result = self::GenPreShow();
                break;
        }
        $replace_arr = array(
            "{method}" => $method
        );
        $result = strtr($result, $replace_arr);
        return $result;
    }

    /**
     * 预览
     * @return string 预览页面
     */
    private function GenPreShow() {
        $result="";
        $siteAdId=Control::GetRequest("site_ad_id","0");
        $siteId=Control::GetRequest("site_id","0");
        if(intval($siteId)>0){
            if(intval($siteAdId)>0){
                $tempContent = Template::Load("site/site_ad_pre_show.html","common");
                $replaceArr = array(
                    "{SiteId}"=>$siteId,
                    "{SiteAdId}" => $siteAdId
                );
                $tempContent = strtr($tempContent, $replaceArr);
                parent::ReplaceEnd($tempContent);
                $result=$tempContent;
            }else{
                $result.=Language::Load('site_ad', 6);//广告位site_ad_id错误！
            }
        }else{
            $result.=Language::Load('site_ad', 5);//站点siteid错误！
        }
        return $result;
    }

}

?>