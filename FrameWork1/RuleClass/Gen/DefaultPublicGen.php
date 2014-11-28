<?php

/**
 * 前台Gen总引导类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_Gen
 * @author zhangchi
 */
class DefaultPublicGen extends BasePublicGen implements IBasePublicGen {

    /**
     * 引导方法
     * @return string 返回执行结果
     */
    public function GenPublic() {
        $module = Control::GetRequest("mod", "");
        switch ($module) {
            case "manage":                
                $manageLoginGen = new ManageLoginPublicGen();
                $result = $manageLoginGen->GenPublic();
                break;
            case "common":
                $commonPublicGen = new CommonPublicGen();
                $result = $commonPublicGen->GenPublic();
                break;
            case "user":
                $userPublicGen = new UserPublicGen();
                $result = $userPublicGen->GenPublic();
                break;
            case "user_info":
                $userInfoPublicGen = new UserInfoPublicGen();
                $result = $userInfoPublicGen->GenPublic();
                break;
            case "user_car":
                $userCarPublicGen = new UserCarPublicGen();
                $result = $userCarPublicGen->GenPublic();
                break;
            case "user_order":
                $userOrderPublicGen = new UserOrderPublicGen();
                $result = $userOrderPublicGen->GenPublic();
                break;
            case "user_receive_info":
                $userReceivePublicGen = new UserReceiveInfoPublicGen();
                $result = $userReceivePublicGen->GenPublic();
                break;
            case "user_favorite":
                $userFavoritePublicGen = new UserFavoritePublicGen();
                $result = $userFavoritePublicGen->GenPublic();
                break;
            case "user_explore":
                $userExplorePublicGen = new UserExplorePublicGen();
                $result = $userExplorePublicGen->GenPublic();
                break;
            case "forum":
                $forumPublicGen = new ForumPublicGen();
                $result = $forumPublicGen->GenPublic();
                break;
            case "forum_topic":
                $forumTopicPublicGen = new ForumTopicPublicGen();
                $result = $forumTopicPublicGen->GenPublic();
                break;
            case "upload_file":
                $uploadFilePublicGen = new UploadFilePublicGen();
                $result = $uploadFilePublicGen->GenPublic();
                break;
            case "product":
                $productPublicGen = new ProductPublicGen();
                $result = $productPublicGen->GenPublic();
                break;
            case "product_comment":
                $productCommentPublicGen = new ProductCommentPublicGen();
                $result = $productCommentPublicGen->GenPublic();
                break;
            case "site_ad":
                $siteAdPublicGen = new SiteAdPublicGen();
                $result = $siteAdPublicGen->GenPublic();
                break;
            case "site_ad_content":
                $siteAdContentPublicGen = new SiteAdContentPublicGen();
                $result = $siteAdContentPublicGen->GenPublic();
                break;
            case "document_news":
                $documentNewsPublicGen = new DocumentNewsPublicGen();
                $result = $documentNewsPublicGen->GenPublic();
                break;
            case "newspaper":
                $newspaperPublicGen = new NewspaperPublicGen();
                $result = $newspaperPublicGen->GenPublic();
                break;
            case "newspaper_page":
                $newspaperPagePublicGen = new NewspaperPagePublicGen();
                $result = $newspaperPagePublicGen->GenPublic();
                break;
            case "newspaper_article":
                $newspaperArticlePublicGen = new NewspaperArticlePublicGen();
                $result = $newspaperArticlePublicGen->GenPublic();
                break;
            case "newspaper_article_pic":
                $newspaperArticlePicPublicGen = new NewspaperArticlePicPublicGen();
                $result = $newspaperArticlePicPublicGen->GenPublic();
                break;
            default:
                $result = self::GenDefaultPublic();
                break;
        }
        return $result;
    }

    private function GenDefaultPublic(){
        $temp = Control::GetRequest("temp", "");
        $siteId = parent::GetSiteIdByDomain();
        $templateContent = self::loadDefaultTemp($temp,$siteId);

//        //加载站点数据数据
//        $sitePublicData = new SitePublicData();
//        $arrOne = $sitePublicData->GetOne($siteId);
//        Template::ReplaceOne($templateContent, $arrOne);

        parent::ReplaceFirst($templateContent);
        parent::ReplaceSiteInfo($siteId, $templateContent);

        //模板替换
        $templateContent = parent::ReplaceTemplate($templateContent);
        $patterns = '/\{s_(.*?)\}/';
        $templateContent = preg_replace($patterns, "", $templateContent);
        parent::ReplaceEnd($templateContent);
        return $templateContent;
    }

    private function loadDefaultTemp($temp,$siteId)
    {
        $templateFileUrl = "default.html";
        $templateName = "default";
        $templatePath = "front_template";
        $templateContent = Template::Load($templateFileUrl, $templateName, $templatePath);
        $templateContent = str_ireplace("{ChannelId}", $siteId, $templateContent);
        return $templateContent;
    }

}

?>
