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
                $productPublicGen = new SiteAdPublicGen();
                $result = $productPublicGen->GenPublic();
                break;
            default:
                $result = self::GenDefaultPublic();
                break;
        }
        return $result;
    }

    private function GenDefaultPublic(){
        return "";
    }

}

?>
