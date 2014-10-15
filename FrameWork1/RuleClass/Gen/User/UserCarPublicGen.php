<?php

/**
 * 前台管理 会员购物车 生成类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_Gen_User
 * @author yin
 */
class UserCarPublicGen extends BasePublicGen implements IBasePublicGen
{
    /**
     * 加入购物车成功
     */
    const ADD_USER_CAR_SUCCESS = 1;
    /**
     * 加入购物车成功
     */
    const ADD_USER_CAR_FAIL = -1;
    /**
     * 修改购买数量成功
     */
    const MODIFY_BuyCount_SUCCESS = 1;
    /**
     * 修改购买数量失败
     */
    const MODIFY_BuyCount_FAIL = -1;
    /**
     * 删除购物车产品成功
     */
    const DELETE_SUCCESS = 1;
    /**
     * 删除购物车产品失败
     */
    const DELETE_FAIL = -1;
    /**
     * 删除购物车产品失败
     */
    const PRODUCT_TABLE_TYPE_IN_UPLOAD_FILE = -1;
    /**
     * 未登陆
     */
    const IS_NOT_LOGIN = -2;

    /**
     * 引导方法
     */
    public function GenPublic()
    {
        $method = Control::GetRequest("a", 0);
        $result = "";
        switch ($method) {
            case "async_create":
                $result = self::AsyncCreate();
                break;
            case "list":
                $result = self::GenList();
                break;
            case "async_modify_buy_count":
                $result = self::AsyncModifyBuyCount();
                break;
            case "async_remove_bin":
                $result = self::AsyncRemoveBin();
                break;
            case "async_batch_remove_bin":
                $result = self::AsyncBatchRemoveBin();
                break;
            case "async_get_count":
                $result = self::AsyncGetCount();
                break;
        }
        return $result;
    }

    private function AsyncCreate()
    {
        $userId = Control::GetUserId();
        $siteId = Control::GetRequest("site_id", 0);
        $productId = Control::GetRequest("product_id", 0);
        $buyCount = Control::GetRequest("buy_count", 0);
        $productPriceId = Control::GetRequest("product_price_id", 0);
        $activityProductId = Control::GetRequest("activity_product_id", 0);
        if ($userId > 0) {
            if ($productPriceId > 0 && $productId > 0 && $siteId > 0) {
                $userCarPublicData = new UserCarPublicData();
                $result = $userCarPublicData->Create($userId, $siteId, $productId, $productPriceId, $buyCount, $activityProductId);
                if ($result > 0) {
                    return Control::GetRequest("jsonpcallback", "") . '({"result":'.self::ADD_USER_CAR_SUCCESS.'})';
                } else {
                    return Control::GetRequest("jsonpcallback", "") . '({"result":'.self::ADD_USER_CAR_FAIL.'})';
                }
            } else {
                return Control::GetRequest("jsonpcallback", "") . '({"result":'.self::ADD_USER_CAR_FAIL.'})';
            }
        } else {
            return Control::GetRequest("jsonpcallback", "") . '({"result":'.self::IS_NOT_LOGIN.'})';
        }
    }

    private function GenList()
    {
        $templateFileUrl = "user/car.html";
        $templateName = "default";
        $templatePath = "front_template";
        $templateContent = Template::Load($templateFileUrl, $templateName, $templatePath);
        $userId = Control::GetUserId();
        $tagId = "user_car";
        if ($userId > 0) {
            parent::ReplaceFirst($templateContent);
            $userCarPublicData = new UserCarPublicData();
            $activityProductPublicData = new ActivityProductPublicData();
            $arrUserCarProductList = $userCarPublicData->GetList($userId);

            for($i=0;$i<count($arrUserCarProductList);$i++){
                $activityProductId = intval($arrUserCarProductList[$i]["ActivityProductId"]);
                $productPriceValue = intval($arrUserCarProductList[$i]["ProductPriceValue"]);
                if($activityProductId>0){
                    $discount = $activityProductPublicData->GetDiscount($activityProductId);
                    $salePrice = $discount * $productPriceValue;
                }else{
                    $salePrice = $productPriceValue;
                }
                $arrUserCarProductList[$i]["SalePrice"] = $salePrice;
                $arrUserCarProductList[$i]["BuyPrice"] = $arrUserCarProductList[$i]["BuyCount"]*$salePrice;
            }

            if (count($arrUserCarProductList) > 0) {
                Template::ReplaceList($templateContent, $arrUserCarProductList, $tagId);
            } else {
                Template::RemoveCustomTag($templateContent, $tagId);
            }
        } else {
            Template::RemoveCustomTag($templateContent, $tagId);
    }
        parent::ReplaceEnd($templateContent);
        return $templateContent;
    }

    private function AsyncRemoveBin()
    {
        $userCarId = intval(Control::GetRequest("user_car_id", 0));
        $userId = intval(Control::GetUserId());

        if ($userCarId > 0 && $userId > 0) {
            $userCarPublicData = new UserCarPublicData();
            $result = $userCarPublicData->Delete($userCarId, $userId);
            if ($result > 0) {
                return Control::GetRequest("jsonpcallback", "") . '({"result":' . self::DELETE_SUCCESS . '})';
            } else {
                return Control::GetRequest("jsonpcallback", "") . '({"result":' . self::DELETE_FAIL . '})';
            }
        } else {
            return Control::GetRequest("jsonpcallback", "") . '({"result":' . self::DELETE_FAIL . '})';
        }
    }

    private function AsyncModifyBuyCount()
    {
        $userCarId = intval(Control::GetRequest("user_car_id", 0));
        $buyCount = intval(Control::GetRequest("buy_count", 0));
        $userId = intval(Control::GetUserId());
        if ($userCarId > 0 && $userId > 0 && $buyCount > 0) {

            $userCarPublicData = new UserCarPublicData();
            $result = $userCarPublicData->ModifyBuyCount($userCarId, $buyCount, $userId);
            if ($result > 0) {
                return Control::GetRequest("jsonpcallback", "") . '({"result":' . self::MODIFY_BuyCount_SUCCESS . '})';
            } else {
                return Control::GetRequest("jsonpcallback", "") . '({"result":' . self::MODIFY_BuyCount_FAIL . '})';
            }
        } else {
            return Control::GetRequest("jsonpcallback", "") . '({"result":' . self::MODIFY_BuyCount_FAIL . '})';
        }
    }

    private function AsyncGetCount()
    {
        $userId = Control::GetUserId();
        $siteId = Control::GetRequest("site_id", 0);

        if ($userId > 0 && $siteId > 0) {
            $userCarPublicData = new UserCarPublicData();
            $result = $userCarPublicData->GetCount($userId, $siteId);
            if ($result > 0) {
                return Control::GetRequest("jsonpcallback", "") . '({"result":' . $result . '})';
            } else {
                return Control::GetRequest("jsonpcallback", "") . '({"result":0})';
            }
        } else {
            return Control::GetRequest("jsonpcallback", "") . '({"result":0})';
        }
    }

    private function AsyncBatchRemoveBin()
    {
        $userId = Control::GetUserId();
        $siteId = Control::GetRequest("site_id", 0);

        if ($userId > 0 && $siteId > 0) {
            $arrUserCarIdString = Control::PostRequest("arr_user_car_id", "");

            $userCarPublicData = new UserCarPublicData();

            $arrUserCarIdString = str_ireplace("_", ",", $arrUserCarIdString);
            $result = $userCarPublicData->BatchDelete($arrUserCarIdString, $userId);
            if ($result > 0) {
                return Control::GetRequest("jsonpcallback", "") . '({"result":' . self::DELETE_SUCCESS . '})';
            } else {
                return Control::GetRequest("jsonpcallback", "") . '({"result":' . self::DELETE_FAIL . '})';
            }
        } else {
            return Control::GetRequest("jsonpcallback", "") . '({"result":' . self::DELETE_FAIL . '})';
        }
    }
}