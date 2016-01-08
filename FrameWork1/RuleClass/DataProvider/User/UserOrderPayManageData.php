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
     * 取得字段数据集
     * @param string $tableName 表名
     * @return array 字段数据集
     */
    public function GetFields($tableName = self::TableName_UserOrderPay){
        return parent::GetFields(self::TableName_UserOrderPay);
    }

    /**
     * 新增
     * @param array $httpPostData $_POST数组
     * @param int $userOrderId 订单id
     * @param int $manageUserId 管理员id
     * @return int 新增的站点id
     */
    public function Create($httpPostData, $userOrderId, $manageUserId)
    {
        $userOrderId = intval($userOrderId);
        $result = -1;
        $dataProperty = new DataProperty();
        $addFieldName = "";
        $addFieldValue = "";
        $preNumber = "";
        $addFieldNames = array("CreateDate","UserOrderId","ConfirmManageUserId");
        $addFieldValues = array(date("Y-m-d H:i:s", time()),$userOrderId, $manageUserId);
        if (!empty($httpPostData)) {
            $sql = parent::GetInsertSql(
                $httpPostData,
                self::TableName_UserOrderPay,
                $dataProperty,
                $addFieldName,
                $addFieldValue,
                $preNumber,
                $addFieldNames,
                $addFieldValues
            );
            $result = $this->dbOperator->LastInsertId($sql, $dataProperty);
        }
        return $result;
    }

    /**
     * 修改
     * @param array $httpPostData $_POST数组
     * @param int $userOrderPayId 站点id
     * @return int 返回影响的行数
     */
    public function Modify($httpPostData, $userOrderPayId)
    {
        $result = -1;
        $dataProperty = new DataProperty();
        $addFieldName = "";
        $addFieldValue = "";
        $preNumber = "";
        $addFieldNames = array();
        $addFieldValues = array();
        if (!empty($httpPostData)) {
            $sql = parent::GetUpdateSql(
                $httpPostData,
                self::TableName_UserOrderPay,
                self::TableId_UserOrderPay,
                $userOrderPayId,
                $dataProperty,
                $addFieldName,
                $addFieldValue,
                $preNumber,
                $addFieldNames,
                $addFieldValues
            );
            $result = $this->dbOperator->Execute($sql, $dataProperty);
        }
        return $result;
    }

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
     * 返回一行数据
     * @param int $userOrderPayId id
     * @return array|null 取得对应数组
     */
    public function GetOne($userOrderPayId){
        $result = null;
        if($userOrderPayId>0){
            $sql = "SELECT * FROM
                        " . self::TableName_UserOrderPay . "
                    WHERE UserOrderPayId=:UserOrderPayId
                    ;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("UserOrderPayId", $userOrderPayId);
            $result = $this->dbOperator->GetArray($sql, $dataProperty);
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
                AND uop.UserOrderId = uo.UserOrderId ORDER BY CreateDate DESC,UserOrderPayId DESC;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("UserOrderId",$userOrderId);
            $result = $this->dbOperator->GetArrayList($sql,$dataProperty);
        }
        return $result;
    }


    /**
     * 取得订单id
     * @param int $userOrderPayId 订单支付表id
     * @param bool $withCache 是否从缓冲中取
     * @return string 订单id
     */
    public function GetUserOrderId($userOrderPayId, $withCache)
    {
        $result = -1;
        if ($userOrderPayId > 0) {
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'user_order_pay_data';
            $cacheFile = 'user_order_pay_get_user_order_id.cache_' . $userOrderPayId . '';
            $sql = "SELECT UserOrderId FROM " . self::TableName_UserOrderPay . " WHERE UserOrderPayId=:UserOrderPayId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("UserOrderPayId", $userOrderPayId);
            $result = $this->GetInfoOfIntValue($sql, $dataProperty, $withCache, $cacheDir, $cacheFile);
        }

        return $result;
    }


    /**
     * 检查是否已存在记录
     * @param $userId
     * @param $userOrderId
     * @return int
     */
    public function CheckRepeatForOfflineOrder($userId,$userOrderId){
        $result=-1;
        if($userId>0&&$userOrderId>0){
            $sql="SELECT UserOrderPayId FROM " . self::TableName_UserOrderPay . " WHERE UserId=:UserId AND UserOrderId=:UserOrderId FOR UPDATE";

            $dataProperty = new DataProperty();
            $dataProperty->AddField("UserId",$userId);
            $dataProperty->AddField("UserOrderId",$userOrderId);
            $result = $this->dbOperator->GetInt($sql,$dataProperty);

        }
        return $result;
    }
}