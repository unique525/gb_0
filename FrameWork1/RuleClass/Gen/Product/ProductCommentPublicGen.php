<?php

/**
 * 产品评论 前台 生成类
 * @category iCMS
 * @package iCMS_Rules_Gen_Product
 * @author yin
 */
class ProductCommentPublicGen extends BasePublicGen implements IBasePublicGen
{
    /**
     *没有购买
     */
    const IS_NOT_BOUGHT = -2;
    /**
     *系统错误
     */
    const SYSTEM_ERROR = -3;
    /**
     *没有登录
     */
    const IS_NOT_LOGIN = -4;
    /**
     *成功
     */
    const SUCCESS = 1;
    /**
     *不是交易完成的订单
     */
    const NOT_FINISHED = -5;
    /**
     *参数错误
     */
    const PARAMETER_ERROR = -1;

    public function GenPublic()
    {
        $result = "";
        $action = Control::GetRequest("a", "");
        switch ($action) {
            case "create":
                $result = self::GenCreate();
                break;
            case "check_is_bought":
                $result = self::AsyncCheckIsBought();
                break;
            case "list":
                $result = self::GenList();
                break;
            case "async_get_appraisal":
                $result = self::AsyncGetAppraisal();
        }
        return $result;
    }

    private function GenCreate()
    {
        $userId = intval(Control::GetUserId());
        $userName = Control::GetUserName();

        $productId = intval(Control::GetRequest("product_id", 0));
        $userOrderId =intval(Control::GetRequest("user_order_id",0));
        $siteId = parent::GetSiteIdByDomain();

        /////参数不正确，返回
        if ($userId <= 0 || $productId <= 0 || $userOrderId <= 0 || empty($userName)){
            return Control::GetRequest("jsonpcallback", "") . '({"result":' . self::PARAMETER_ERROR . '})';
        }
        //不是买家
        $userOrderProductPublicData = new UserOrderProductPublicData();
        $isBought = $userOrderProductPublicData->CheckIsBought($userId, $productId,$userOrderId);

        if($isBought<=0){
            return Control::GetRequest("jsonpcallback", "") . '({"result":' . self::IS_NOT_BOUGHT . '})';
        }
        //订单未交易完成


        //已经评价过了，不能重复评价



        if ($userId > 0 && $productId > 0 && $userOrderId > 0 && !empty($userName)) {
            $userOrderProductPublicData = new UserOrderProductPublicData();
            $isBought = $userOrderProductPublicData->CheckIsBought($userId, $productId,$userOrderId);

            if ($isBought > 0) {
                if (!empty($_POST)) {
                    $content = Control::PostRequest("content", "");
                    $appraisal = Control::PostRequest("appraisal", -1);
                    $parentId = Control::PostRequest("parent_id", 0);
                    $productScore = Control::PostRequest("product_score", 0);
                    $sendScore = Control::PostRequest("send_score", 0);
                    $serviceScore = Control::PostRequest("service_score", 0);
                    $rank = Control::PostRequest("rank", 0);
                    $subject = Control::PostRequest("subject", "");
                    $state = 0;
                    $sort = 0;

                    $productPublicData = new ProductPublicData();
                    $channelId = $productPublicData->GetChannelIdByProductId($productId);
                    //判断订单状态
                    if($state != UserOrderData::STATE_DONE){  //交易完成才能评价
                        return Control::GetRequest("jsonpcallback", "") . '({"result":' . self::NOT_FINISHED . '})';
                    }


                    //
                    if (
                        $channelId > 0 &&
                        $serviceScore >= 0 &&
                        $sendScore >= 0 &&
                        $productScore >= 0 &&
                        $siteId > 0 &&
                        $appraisal >= 0 &&
                        !empty($content)
                    ) {
                        $productCommentPublicData = new ProductCommentPublicData();
                        $result = $productCommentPublicData->Create(
                            $productId,
                            $content,
                            $userId,
                            $userName,
                            $siteId,
                            $channelId,
                            $parentId,
                            $rank,
                            $subject,
                            $appraisal,
                            $productScore,
                            $sendScore,
                            $serviceScore,
                            $state,
                            $sort
                        );
                        return Control::GetRequest("jsonpcallback", "") . '({"result":' . $result . '})';
                    } else {
                        return Control::GetRequest("jsonpcallback", "") . '({"result":' . self::SYSTEM_ERROR . '})';
                    }

                } else {
                    $templateFileUrl = "product/comment_template_add.html";
                    $templatePath = "front_template";
                    $templateName = "default";
                    $templateContent = Template::Load($templateFileUrl, $templateName, $templatePath);

                    $templateContent = str_ireplace("{ProductId}", $productId, $templateContent);
                    $templateContent = str_ireplace("{UserOrderId}", $userOrderId, $templateContent);
                    return $templateContent;
                }
            } else {
                return Control::GetRequest("jsonpcallback", "") . '({"result":' . self::IS_NOT_BOUGHT . '})';
            }
        } else {
            if ($userId < 0) {
                return Control::GetRequest("jsonpcallback", "") . '({"result":' . self::IS_NOT_LOGIN . '})';
            } else {
                return "";
            }
        }
    }

    private function AsyncCheckIsBought(){
        $userId = intval(Control::GetUserId());
        $productId = intval(Control::GetRequest("product_id",0));
        $userOrderId =intval(Control::GetRequest("user_order_id",0));
        if($userId > 0 && $productId > 0){
            $userOrderProductPublicData = new UserOrderProductPublicData();
            $isBought = $userOrderProductPublicData->CheckIsBought($userId, $productId,$userOrderId);

            if($isBought ){
                return Control::GetRequest("jsonpcallback", "") . '({"result":' . self::SUCCESS. '})';
            }else{
                return Control::GetRequest("jsonpcallback", "") . '({"result":' . self::IS_NOT_BOUGHT. '})';
            }
        }else{
            return Control::GetRequest("jsonpcallback", "") . '({"result":' . self::SYSTEM_ERROR. '})';
        }
    }

    private function GenList()
    {
        $productId = Control::GetRequest("product_id", 0);
        if ($productId > 0) {
            $pageSize = Control::GetRequest("ps",10);
            $pageIndex = Control::GetRequest("p",1);
            $pageBegin = ($pageIndex-1)*$pageSize;
            $templateFileUrl = "product/comment_template_test.html";
            $templatePath = "front_template";
            $templateName = "default";
            $templateContent = Template::Load($templateFileUrl, $templateName, $templatePath);

            $tagId = "product_comment_list";
            $productCommentPublicData = new ProductCommentPublicData();
            $allCount = 0;
            $arrProductCommentList = $productCommentPublicData->GetListOfParent($productId, $allCount, $pageBegin, $pageSize);

            $strParentIds = "";
            for ($i = 0; $i < count($arrProductCommentList); $i++) {
                if ($i < count($arrProductCommentList) - 1) {
                    $strParentIds = $strParentIds . $arrProductCommentList[$i]["ProductCommentId"] . ",";
                } else {
                    $strParentIds = $strParentIds . $arrProductCommentList[$i]["ProductCommentId"];
                }
            }
            $arrChildProductCommentList = $productCommentPublicData->GetListOfChild($strParentIds);

            Template::ReplaceList($templateContent, $arrProductCommentList, $tagId, "icms", $arrChildProductCommentList, "ProductCommentId", "ParentId");
            return $templateContent;
        } else {
            return "";
        }
    }

    private function AsyncGetAppraisal()
    {
        $productId = Control::GetRequest("product_id", 0);
        if ($productId > 0) {
            $productCommentPublicData = new ProductCommentPublicData();

            //好评
            $positiveAppraisal = $productCommentPublicData->GetAppraisalCount($productId,0);
            //中评
            $moderateAppraisal = $productCommentPublicData->GetAppraisalCount($productId,1);
            //差评
            $negativeAppraisal = $productCommentPublicData->GetAppraisalCount($productId,2);


            return Control::GetRequest("jsonpcallback", "")
            . '({
                        "result":' . self::SUCCESS . ',
                        "positive_appraisal":' . $positiveAppraisal . ',
                        "moderate_appraisal":' . $moderateAppraisal . ',
                        "negative_appraisal":' . $negativeAppraisal . '
                        })';
        } else {
            return Control::GetRequest("jsonpcallback", "") . '({"result":' . self::PARAMETER_ERROR . '})';
        }
    }
}