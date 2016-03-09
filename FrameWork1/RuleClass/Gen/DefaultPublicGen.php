<?php

/**
 * Web Gen总引导类
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
            case "channel":
                $channelPublicGen = new ChannelPublicGen();
                $result = $channelPublicGen->GenPublic();
                break;
            case "common":
                $commonPublicGen = new CommonPublicGen();
                $result = $commonPublicGen->GenPublic();
                break;
            case "comment":
                $commentPublicGen = new CommentPublicGen();
                $result = $commentPublicGen->GenPublic();
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
            case "user_order_send":
                $userOrderSendPublicGen = new UserOrderSendPublicGen();
                $result = $userOrderSendPublicGen->GenPublic();
                break;
            case "user_album":
                $userAlbumPublicGen = new UserAlbumPublicGen();
                $result = $userAlbumPublicGen->GenPublic();
                break;
            case "forum":
                $forumPublicGen = new ForumPublicGen();
                $result = $forumPublicGen->GenPublic();
                break;
            case "forum_topic":
                $forumTopicPublicGen = new ForumTopicPublicGen();
                $result = $forumTopicPublicGen->GenPublic();
                break;
            case "forum_post":
                $forumPostPublicGen = new ForumPostPublicGen();
                $result = $forumPostPublicGen->GenPublic();
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
            case "visit":
                $visitPublicGen = new VisitPublicGen();
                $result = $visitPublicGen->GenPublic();
                break;
            case "custom_form_record":
                $visitPublicGen = new CustomFormRecordPublicGen();
                $result = $visitPublicGen->GenPublic();
                break;
            case "search":
                $searchPublicGen = new SearchPublicGen();
                $result = $searchPublicGen->GenPublic();
                break;
            case "vote":
                $votePublicGen = new VotePublicGen();
                $result = $votePublicGen->GenPublic();
                break;
            case "activity":
                $activityPublicGen = new ActivityPublicGen();
                $result = $activityPublicGen->GenPublic();
                break;
            case "exam_question_class":
                $examQuestionClassPublicGen = new ExamQuestionClassPublicGen();
                $result = $examQuestionClassPublicGen->GenPublic();
                break;
            case "exam_question":
                $examQuestionPublicGen = new ExamQuestionPublicGen();
                $result = $examQuestionPublicGen->GenPublic();
                break;
            case "exam_user_paper":
                $examUserPaperPublicGen = new ExamUserPaperPublicGen();
                $result = $examUserPaperPublicGen->GenPublic();
                break;
            case "exam_user_answer":
                $examUserAnswerPublicGen = new ExamUserAnswerPublicGen();
                $result = $examUserAnswerPublicGen->GenPublic();
                break;
            case "lottery":
                $examUserAnswerPublicGen = new LotteryPublicGen();
                $result = $examUserAnswerPublicGen->GenPublic();
                break;
            case "client_direct":
                $ClientDirectUrlPublicGen = new ClientDirectUrlPublicGen();
                $result = $ClientDirectUrlPublicGen->GenPublic();
                break;
            case "promotion_record":
                $promotionRecordPublicGen = new PromotionRecordPublicGen();
                $result = $promotionRecordPublicGen->GenPublic();
                break;
            default:
                $result = self::GenDefaultPublic();
                break;
        }
        return $result;
    }

    private function GenDefaultPublic(){
        $siteId = parent::GetSiteIdByDomain();
        $templateContent = parent::GetDynamicTemplateContent("default", $siteId, "", $templateMode);

        /*******************页面级的缓存 begin********************** */


        $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'default_page';
        $cacheFile = 'site_id_' . $siteId . '_mode_' . $templateMode;
        $withCache = true;
        if($withCache){
            $pageCache = parent::GetCache($cacheDir, $cacheFile);

            if ($pageCache === false) {
                $result = self::getDefaultTemplateContent($siteId, $templateContent);
                parent::AddCache($cacheDir, $cacheFile, $result, 60);
            } else {
                $result = $pageCache;
            }
        }else{
            $result = self::getDefaultTemplateContent($siteId, $templateContent);
        }

        /*******************页面级的缓存 end  ********************** */
        parent::ReplaceUserInfoPanel($result, $siteId, "forum_user_is_login", "forum_user_no_login");
        return $result;
    }

    private function getDefaultTemplateContent($siteId, $templateContent){

        parent::ReplaceFirst($templateContent);
        parent::ReplaceSiteInfo($siteId, $templateContent);
        $templateContent = str_ireplace("{SiteId}", $siteId, $templateContent);
        //模板替换
        $templateContent = parent::ReplaceTemplate($templateContent);
        $patterns = '/\{s_(.*?)\}/';
        $templateContent = preg_replace($patterns, "", $templateContent);
        parent::ReplaceEnd($templateContent);
        return $templateContent;

    }


}

?>
