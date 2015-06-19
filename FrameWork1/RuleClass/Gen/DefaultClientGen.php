<?php
/**
 * Client Gen总引导类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_Gen
 * @author zhangchi
 */
class DefaultClientGen extends BaseClientGen implements IBaseClientGen {
    /**
     * 引导方法
     * @return string 返回执行结果
     */
    public function GenClient() {
        $module = Control::GetRequest("mod", "");

        $authKey = Control::GetRequest("auth_key","");
        switch ($module) {
            case "pic_slider":
                $picSliderClientGen = new PicSliderClientGen();
                $result = $picSliderClientGen->GenClient();
                break;
            case "product":
                $productClientGen = new ProductClientGen();
                $result = $productClientGen->GenClient();
                break;
            case "product_pic":
                $productPicClientGen = new ProductPicClientGen();
                $result = $productPicClientGen->GenClient();
                break;
            case "product_price":
                $productPriceClientGen = new ProductPriceClientGen();
                $result = $productPriceClientGen->GenClient();
                break;
            case "product_param":
                $productParamClientGen = new ProductParamClientGen();
                $result = $productParamClientGen->GenClient();
                break;
            case "product_comment":
                $productCommentClientGen = new ProductCommentClientGen();
                $result = $productCommentClientGen->GenClient();
                break;
            case "channel":
                $channelClientGen = new ChannelClientGen();
                $result = $channelClientGen->GenClient();
                break;
            case "document_news":
                $documentNewsClientGen = new DocumentNewsClientGen();
                $result = $documentNewsClientGen->GenClient();
                break;
            case "user":
                $userClientGen = new UserClientGen();
                $result = $userClientGen->GenClient();
                break;
            case "user_car":
                $userCarClientGen = new UserCarClientGen();
                $result = $userCarClientGen->GenClient();
                break;
            case "user_order":
                $userOrderClientGen = new UserOrderClientGen();
                $result = $userOrderClientGen->GenClient();
                break;
            case "user_order_product":
                $userOrderProductClientGen = new UserOrderProductClientGen();
                $result = $userOrderProductClientGen->GenClient();
                break;
            case "user_order_send":
                $userOrderSendClientGen = new UserOrderSendClientGen();
                $result = $userOrderSendClientGen->GenClient();
                break;
            case "user_receive_info":
                $userReceiveInfoClientGen = new UserReceiveInfoClientGen();
                $result = $userReceiveInfoClientGen->GenClient();
                break;
            case "user_favorite":
                $userFavoriteClientGen = new UserFavoriteClientGen();
                $result = $userFavoriteClientGen->GenClient();
                break;
            case "client_app":
                $clientAppClientGen = new ClientAppClientGen();
                $result = $clientAppClientGen->GenClient();
                break;
            case "forum":
                $forumClientGen = new ForumClientGen();
                $result = $forumClientGen->GenClient();
                break;
            case "forum_topic":
                $forumTopicClientGen = new ForumTopicClientGen();
                $result = $forumTopicClientGen->GenClient();
                break;
            case "forum_post":
                $forumPostClientGen = new ForumPostClientGen();
                $result = $forumPostClientGen->GenClient();
                break;
            default:
                $result = "";
                break;
        }
        return $result;
    }
} 