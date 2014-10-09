<?php
/**
 * 产品评论 前台 生成类
 * @category iCMS
 * @package iCMS_Rules_Gen_Product
 * @author yin
 */
class ProductCommentPublicGen extends BasePublicGen implements IBasePublicGen{
    const IS_NOT_BOUGHT = -2;
    const SYSTEM_ERROR = -3;

    public function GenPublic()
    {
        $result = "";
        $action = Control::GetRequest("a","");
        switch($action){
            case "add":
                $result = self::GenCreate();
                break;
        }
        return $result;
    }

    private function GenCreate(){
        $userId = Control::GetUserId();
        $userName = Control::GetUserName();

        $productId =Control::PostRequest("product_id",0);

        if($userId > 0 && $productId > 0 && !empty($userName)){
            $userOrderProductPublicData = new UserOrderProductPublicData();
            $isBought = $userOrderProductPublicData->CheckIsBought($userId,$productId);

            if($isBought > 0){
                if(!empty($_POST)){
                    $content = Control::PostRequest("content","");
                    $appraisal = Control::PostRequest("appraisal",-1);
                    $siteId = Control::PostRequest("site_id",0);
                    $parentId = Control::PostRequest("parent_id",0);
                    $productScore = Control::PostRequest("product_score",0);
                    $sendScore = Control::PostRequest("send_score",0);
                    $serviceScore = Control::PostRequest("service_score",0);
                    $rank = Control::PostRequest("rank",0);
                    $subject = Control::PostRequest("subject","");
                    $state = 0;
                    $sort = 0;

                    $productPublicData = new ProductPublicData();
                    $channelId =$productPublicData->GetChannelIdByProductId($productId);
                    if(
                        $channelId > 0 &&
                        $serviceScore >=0 &&
                        $sendScore >= 0 &&
                        $productScore >= 0 &&
                        $siteId > 0 &&
                        $appraisal >= 0 &&
                        !empty($content)
                    ){
                        $productCommentPublicData = new ProductCommentPublicData();
                        $result = $productCommentPublicData->Create($productId,$content,$userId,$userName,$siteId,$channelId,
                            $parentId,$rank,$subject,$appraisal,$productScore,$sendScore,$serviceScore,$state,$sort);
                        return Control::GetRequest("jsonpcallback","").'({"result":'.$result.'})';
                    }else{
                        return Control::GetRequest("jsonpcallback","").'({"result":'.self::SYSTEM_ERROR.'})';
                    }

                }
            }else{
                return Control::GetRequest("jsonpcallback","").'({"result":'.self::IS_NOT_BOUGHT.'})';
            }
        }else{
            return null;
        }
    }
}