<?php

/**
 * 后台管理 会员订单产品 数据类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_User
 * @author yin
 */
class UserOrderPayManageData extends BaseManageData
{
    const STATE_CONFIRM = 10;
    /**
     * 修改订单的确认支付信息
     * @param int $userOrderPayId 会员订单支付信息Id
     * @param string $confirmWay 确认支付方式
     * @param float $confirmPrice 确认支付价格
     * @param string $confirmDate 确认时间
     * @param int $manageUserId 管理员Id
     * @return int 影响的行数
     */
    public function ModifyConfirmPay($userOrderPayId,$confirmWay,$confirmPrice,$confirmDate,$manageUserId){
        $result = -1;
        if($userOrderPayId > 0 && $confirmWay != "" && $confirmDate != "" && $manageUserId > 0){
            $sql = "UPDATE ".self::TableName_UserOrderPay." SET ConfirmWay = :ConfirmWay,ConfirmPrice = :ConfirmPrice,
                ConfirmDate=:ConfirmDate,State = :State,ConfirmManageUserId = :ConfirmManageUserId WHERE UserOrderPayId = :UserOrderPayId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("ConfirmWay",$confirmWay);
            $dataProperty->AddField("ConfirmPrice",$confirmPrice);
            $dataProperty->AddField("ConfirmDate",$confirmDate);
            $dataProperty->AddField("State",self::STATE_CONFIRM);
            $dataProperty->AddField("ConfirmManageUserId",$manageUserId);
            $dataProperty->AddField("UserOrderPayId",$userOrderPayId);
            $result = $this->dbOperator->Execute($sql,$dataProperty);

        }
        return $result;
    }

    /**
     * 获取一个订单的所有支付信息
     * @param int $userOrderId 订单Id
     * @return array|null 订单支付信息列表
     */
    public function GetList($userOrderId){
        $result = null;
        if($userOrderId > 0){
            $sql = "SELECT uop.*,mu.ManageUserName,uo.UserOrderNumber
                FROM ".self::TableName_UserOrderPay." uop LEFT JOIN ".self::TableName_ManageUser." mu
                ON uop.ConfirmManageUserId = mu.ManageUserId,".self::TableName_UserOrder." uo
                WHERE uop.UserOrderId = :UserOrderId
                AND uop.UserOrderId = uo.UserOrderId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("UserOrderId",$userOrderId);
            $result = $this->dbOperator->GetArrayList($sql,$dataProperty);
        }
        return $result;
    }
}