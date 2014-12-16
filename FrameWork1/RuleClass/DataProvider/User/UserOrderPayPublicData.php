<?php
/**
 * 前台 会员订单付款记录 数据类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_User
 * Time: 下午12:09
 */
class UserOrderPayPublicData extends BasePublicData {

    /**
     * 增加订单付款记录
     * @param int $userOrderId
     * @param float $payPrice
     * @param string $payWay
     * @return int
     */
    public function Create($userOrderId, $payPrice, $payWay){

        $result = -1;

        if($userOrderId>0){

            $sql = "INSERT INTO ".self::TableName_UserOrderPay."
                    (
                        UserOrderId,
                        CreateDate,
                        PayPrice,
                        PayDate,
                        PayWay
                    )
                    VALUES
                    (
                        :UserOrderId,
                        now(),
                        :PayPrice,
                        now(),
                        :PayWay
                    )
                    ;";

            $dataProperty = new DataProperty();
            $dataProperty->AddField("UserOrderId",$userOrderId);
            $dataProperty->AddField("PayPrice",$payPrice);
            $dataProperty->AddField("PayWay",$payWay);
            $result = $this->dbOperator->LastInsertId($sql,$dataProperty);

        }

        return $result;
    }

} 