<?php
/**
 * 客户端 会员收货信息 生成类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_Gen_User
 * @author zhangchi
 */
class UserReceiveInfoClientGen extends BaseClientGen implements IBaseClientGen {
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

            case "modify":
                $result = self::GenModify();
                break;

            case "list":
                $result = self::GenList();
                break;

        }
        $result = str_ireplace("{function}", $function, $result);
        return $result;
    }

    private function GenList(){


        $result = "[{}]";

        $userId = parent::GetUserId();

        if($userId<=0){
            $resultCode = $userId;
        }else{

            $pageSize = Control::GetRequest("ps", 20);
            $pageIndex = Control::GetRequest("p", 1);
            $pageBegin = ($pageIndex - 1) * $pageSize;

            $userCarClientData = new UserCarClientData();
            $activityProductClientData = new ActivityProductClientData();

            $arrUserCarProductList = $userCarClientData->GetList($userId, $pageBegin, $pageSize);

            if (count($arrUserCarProductList) > 0){
                $resultCode = 1;

                for($i=0;$i<count($arrUserCarProductList);$i++){
                    $activityProductId = intval($arrUserCarProductList[$i]["ActivityProductId"]);
                    $productPriceValue = floatval($arrUserCarProductList[$i]["ProductPriceValue"]);
                    if($activityProductId>0){
                        $discount = $activityProductClientData->GetDiscount($activityProductId, true);
                        $salePrice = $discount * $productPriceValue;
                    }else{
                        $salePrice = $productPriceValue;
                    }
                    $arrUserCarProductList[$i]["SalePrice"] = $salePrice;
                    $arrUserCarProductList[$i]["BuyPrice"] = $arrUserCarProductList[$i]["BuyCount"]*$salePrice;
                }

                $result = Format::FixJsonEncode($arrUserCarProductList);

            }else{
                $resultCode = -1;
            }

        }
        return '{"result_code":"'.$resultCode.'","user_car":{"user_car_list":' . $result . '}}';

    }
} 