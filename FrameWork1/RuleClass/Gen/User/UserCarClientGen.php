<?php

/**
 * 客户端 会员购物车 生成类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_Gen_User
 * @author zhangchi
 */
class UserCarClientGen extends BaseClientGen implements IBaseClientGen
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

            case "list":
                $result = self::GenList();
                break;

            case "delete":
                $result = self::GenDelete();
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
            $productId = Control::PostOrGetRequest("product_id", 0);
            $buyCount = Control::PostOrGetRequest("buy_count", 0);
            $productPriceId = Control::PostOrGetRequest("product_price_id", 0);
            $activityProductId = Control::PostOrGetRequest("activity_product_id", 0);
            if ($productPriceId > 0 && $productId > 0 && $siteId > 0) {

                //检查库存
                $productPriceClientData = new ProductPriceClientData();
                //即时库存，不缓存
                $productCount = $productPriceClientData->GetProductCount($productPriceId, false);
                if ($buyCount <= 0 || $buyCount > $productCount) {
                    $resultCode = -20; //购买数量小于0或库存数不够
                } else {
                    $activityProductClientData = new ActivityProductClientData();
                    $productPriceValue = $productPriceClientData->GetProductPriceValue($productPriceId, false);

                    if ($activityProductId > 0) {
                        $discount = $activityProductClientData->GetDiscount($activityProductId, true);
                        $salePrice = $discount * $productPriceValue;
                    } else {
                        $salePrice = $productPriceValue;
                    }
                    //小计
                    $buyPrice = $salePrice*$buyCount;

                    $userCarClientData = new UserCarClientData();
                    $newUserCarId = $userCarClientData->Create(
                        $userId,
                        $siteId,
                        $productId,
                        $productPriceId,
                        $buyCount,
                        $salePrice,
                        $buyPrice,
                        $productCount,
                        $activityProductId
                    );

                    if ($newUserCarId > 0) {
                        $arrOne = $userCarClientData->GetOne($userId, $newUserCarId, false);

                        $result = Format::FixJsonEncode($arrOne);
                        $resultCode = 1; //加入购物车成功
                    } elseif ($newUserCarId == -20) {
                        $resultCode = -20; //如果新的产品数量大于库存数量，不新增，返回-20
                    } else {
                        $resultCode = -5; //加入购物车失败,数据库原因
                    }
                }
            } else {
                $resultCode = -15; //加入购物车失败,参数错误;
            }

        }
        return '{"result_code":"' . $resultCode . '","user_car_create":' . $result . '}';
    }

    private function GenList()
    {
        $result = "[{}]";
        $userId = parent::GetUserId();

        if ($userId <= 0) {
            $resultCode = $userId;
        } else {

            $pageSize = intval(Control::GetRequest("ps", 20));
            $pageIndex = intval(Control::GetRequest("p", 1));
            $pageBegin = ($pageIndex - 1) * $pageSize;

            $userCarClientData = new UserCarClientData();


            $arrUserCarProductList = $userCarClientData->GetList($userId, $pageBegin, $pageSize);

            if (count($arrUserCarProductList) > 0) {
                $resultCode = 1;

                $result = Format::FixJsonEncode($arrUserCarProductList);

            } else {
                $resultCode = -1;
            }

        }
        return '{"result_code":"' . $resultCode . '","user_car":{"user_car_list":' . $result . '}}';
    }

    private function GenDelete()
    {
        $result = "[{}]";
        $userId = parent::GetUserId();
        if ($userId <= 0) {
            $resultCode = $userId; //会员检验失败,参数错误
        } else {
            $userCarId = Control::PostOrGetRequest("user_car_id", 0);
            if ($userCarId > 0) {
                $userCarClientData = new UserCarClientData();
                $result = $userCarClientData->Delete($userCarId, $userId);
                if ($result > 0) {
                    $resultCode = 1; //删除购物车成功
                } else {
                    $resultCode = -5; //删除购物车失败,数据库原因
                }

            } else {
                $resultCode = -6; //加入购物车失败,参数错误;
            }

        }
        return '{"result_code":"' . $resultCode . '","user_car_delete":' . $result . '}';
    }
} 