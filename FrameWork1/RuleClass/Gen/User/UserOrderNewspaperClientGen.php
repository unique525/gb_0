<?php

/**
 * 客户端 会员订单电子报 生成类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_Gen_User
 * @author zhangchi
 */
class UserOrderNewspaperClientGen extends BaseClientGen implements IBaseClientGen
{
    /**
     * 引导方法
     * @return string 返回执行结果
     */
    public function GenClient()
    {
        $result = "";
        $function = Control::GetRequest("f", "");
        switch ($function) {

            case "create":
                $result = self::GenCreate();
                break;

        }
        $result = str_ireplace("{function}", $function, $result);
        return $result;
    }

    private function GenCreate()
    {
        $result = "[{}]";

        $userId = parent::GetUserId();

        if ($userId <= 0) {
            $resultCode = $userId; //会员检验失败,参数错误
        } else {
            $siteId = Control::PostOrGetRequest("site_id", 0);
            $userOrderId = intval(Control::PostOrGetRequest("UserOrderId", 0));
            $newspaperId = intval(Control::PostOrGetRequest("NewspaperId", 0));
            $newspaperArticleId = intval(Control::PostOrGetRequest("NewspaperArticleId", 0));
            $salePrice = floatval(Control::PostOrGetRequest("SalePrice", 0));


            if ($siteId > 0
                && $userOrderId > 0
                && $salePrice > 0
            ) {
                $beginDate = date('Y-m-d');
                $endDate = date("Y-m-d", strtotime("+1 year"));
                $newspaperClientData = new NewspaperClientData();
                $newspaperArticleClientData = new NewspaperArticleClientData();
                $newspaperPageClientData = new NewspaperPageClientData();
                $channelId=0;
                if($newspaperId>0){
                $channelId = $newspaperClientData->GetChannelId($newspaperId, true);
                }
                else if($newspaperArticleId>0){
                    $newspaperPageId = $newspaperArticleClientData->GetNewspaperPageId($newspaperArticleId, true);
                    $newspaperId = $newspaperPageClientData->GetNewspaperId($newspaperPageId,true);
                    $channelId = $newspaperClientData->GetChannelId($newspaperId, true);
                }

                $userOrderNewspaperClientData = new UserOrderNewspaperClientData();
                $newUserOrderNewspaperId = $userOrderNewspaperClientData->Create(
                    $userOrderId,
                    $siteId,
                    $channelId,
                    $userId,
                    $newspaperId,
                    $newspaperArticleId,
                    $salePrice,
                    $beginDate,
                    $endDate
                );
                if ($newUserOrderNewspaperId > 0) {
                    $resultCode = 1;


                    $result = Format::FixJsonEncode($userOrderNewspaperClientData->GetOne($newUserOrderNewspaperId));

                } else {
                    $resultCode = -2; //数据库错误
                }

            } else {
                $resultCode = -5;//参数不正确
            }

        }
        return '{"result_code":"' . $resultCode . '","user_order_product_create":' . $result . '}';
    }

}