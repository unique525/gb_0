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
     * 引导方法
     */
    public function GenPublic()
    {
        $method = Control::GetRequest("a", 0);
        $result = "";
        switch ($method) {
            case "add":
                $result = self::GenCreate();
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
            case "async_get_car_count":
                $result = self::AsyncGetCarCount();
                break;
        }
        return $result;
    }

    private function GenCreate()
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
                    return Control::GetRequest("jsonpcallback", "") . '({"result":1})';
                } else {
                    return Control::GetRequest("jsonpcallback", "") . '({"result":-1})';
                }
            } else {
                return Control::GetRequest("jsonpcallback", "") . '({"result":-1})';
            }
        } else {
            Control::GoUrl("/login.htm");
            return "";
        }
    }

    private function GenList()
    {
        $templateFileUrl = "user/car.html";
        $templateName = "default";
        $templatePath = "front_template";
        $templateContent = Template::Load($templateFileUrl, $templateName, $templatePath);
        $userId = Control::GetUserId();
        if ($userId > 0) {
            $userCarPublicData = new UserCarPublicData();
            $arrUserCarProductList = $userCarPublicData->GetList($userId);
            $tagId = "user_car";

            if (count($arrUserCarProductList) > 0) {
                Template::ReplaceList($templateContent, $arrUserCarProductList, $tagId);
            } else {
                Template::RemoveCustomTag($templateContent, $tagId);
            }
        } else {
            Control::GoUrl("/login.htm");
        }
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

    private function AsyncGetCarCount()
    {
        $userId = Control::GetUserId();
        $siteId = Control::GetRequest("site_id", 0);

        if ($userId > 0 && $siteId > 0) {
            $userCarPublicData = new UserCarPublicData();
            $result = $userCarPublicData->GetCarCount($userId, $siteId);
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