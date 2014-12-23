<?php
/**
 * 后台管理 会员订单发货信息 数据类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_User
 * @author yin
 */
class UserOrderSendManageData extends BaseManageData{



    /**
     * 取得字段数据集
     * @param string $tableName 表名
     * @return array 字段数据集
     */
    public function GetFields($tableName = self::TableName_UserOrderSend){
        return parent::GetFields(self::TableName_UserOrderSend);
    }

    /**
     * 新增
     * @param array $httpPostData $_POST数组
     * @param int $userOrderId 订单id
     * @param int $manageUserId 管理员id
     * @return int 新增的id
     */
    public function Create($httpPostData, $userOrderId, $manageUserId)
    {
        $userOrderId = intval($userOrderId);
        $result = -1;
        $dataProperty = new DataProperty();
        $addFieldName = "";
        $addFieldValue = "";
        $preNumber = "";
        $addFieldNames = array("CreateDate","UserOrderId","ManageUserId");
        $addFieldValues = array(date("Y-m-d H:i:s", time()),$userOrderId, $manageUserId);
        if (!empty($httpPostData)) {
            $sql = parent::GetInsertSql(
                $httpPostData,
                self::TableName_UserOrderSend,
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
     * @param int $userOrderSendId id
     * @return int 返回影响的行数
     */
    public function Modify($httpPostData, $userOrderSendId)
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
                self::TableName_UserOrderSend,
                self::TableId_UserOrderSend,
                $userOrderSendId,
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
    public function Create($userOrderId,$acceptPersonName,$acceptAddress,$acceptTel,$acceptTime,$sendCompany){
        $result = -1;
        if($userOrderId > 0 && !empty($acceptPersonName) && !empty($acceptTel)){
            $sql = "INSERT INTO ".self::TableName_UserOrderSend." (UserOrderId,AcceptPersonName,AcceptAddress,AcceptTel,AcceptTime,SendCompany)
                VALUES (:UserOrderId,:AcceptPersonName,:AcceptAddress,:AcceptTel,:AcceptTime,:SendCompany);";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("UserOrderId",$userOrderId);
            $dataProperty->AddField("AcceptPersonName",$acceptPersonName);
            $dataProperty->AddField("AcceptAddress",$acceptAddress);
            $dataProperty->AddField("AcceptTel",$acceptTel);
            $dataProperty->AddField("AcceptTime",$acceptTime);
            $dataProperty->AddField("SendCompany",$sendCompany);

            $result = $this->dbOperator->LastInsertId($sql,$dataProperty);
        }
        return $result;
    }
*/
    /**
     * @param int $userOrderId 会员订单Id
     * @return array|null 会员所有的收货信息
     */
    public function GetList($userOrderId){
        $result = null;
        if($userOrderId > 0){
            $sql = "SELECT * FROM ".self::TableName_UserOrderSend." WHERE UserOrderId = :UserOrderId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("UserOrderId",$userOrderId);
            $result = $this->dbOperator->GetArrayList($sql,$dataProperty);
        }
        return $result;
    }
/**
    public function Modify($userOrderSendId,$acceptPersonName,$acceptAddress,$acceptTel,$acceptTime,$sendCompany){
        $result = -1;
        if($userOrderSendId > 0){
            $sql = "UPDATE ".self::TableName_UserOrderSend." SET
                AcceptPersonName = :AcceptPersonName,
                AcceptAddress=:AcceptAddress,
                AcceptTel=:AcceptTel,
                AcceptTime=:AcceptTime,
                SendCompany=:SendCompany
                WHERE UserOrderSendId = :UserOrderSendId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("AcceptPersonName",$acceptPersonName);
            $dataProperty->AddField("AcceptAddress",$acceptAddress);
            $dataProperty->AddField("AcceptTel",$acceptTel);
            $dataProperty->AddField("AcceptTime",$acceptTime);
            $dataProperty->AddField("SendCompany",$sendCompany);
            $dataProperty->AddField("UserOrderSendId",$userOrderSendId);

            $result = $this->dbOperator->Execute($sql,$dataProperty);
        }
        return $result;
    }
*/
    public function Delete($userOrderSendId){
        $result = -1;
        if($userOrderSendId > 0){
            $sql = "DELETE FROM ".self::TableName_UserOrderSend." WHERE UserOrderSendId = :UserOrderSendId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("UserOrderSendId",$userOrderSendId);
            $result = $this->dbOperator->Execute($sql,$dataProperty);
        }
        return $result;
    }


    /**
     * 返回一行数据
     * @param int $userOrderSendId id
     * @return array|null 取得对应数组
     */
    public function GetOne($userOrderSendId){
        $result = null;
        if($userOrderSendId>0){
            $sql = "SELECT * FROM
                        " . self::TableName_UserOrderSend . "
                    WHERE UserOrderSendId=:UserOrderSendId
                    ;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("UserOrderSendId", $userOrderSendId);
            $result = $this->dbOperator->GetArray($sql, $dataProperty);
        }
        return $result;
    }


    /**
     * 取得订单id
     * @param int $userOrderSendId 订单发货表id
     * @param bool $withCache 是否从缓冲中取
     * @return string 订单id
     */
    public function GetUserOrderId($userOrderSendId, $withCache)
    {
        $result = -1;
        if ($userOrderSendId > 0) {
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'user_order_send_data';
            $cacheFile = 'user_order_send_get_user_order_id.cache_' . $userOrderSendId . '';
            $sql = "SELECT UserOrderId FROM " . self::TableName_UserOrderSend . " WHERE UserOrderSendId=:UserOrderSendId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("UserOrderSendId", $userOrderSendId);
            $result = $this->GetInfoOfIntValue($sql, $dataProperty, $withCache, $cacheDir, $cacheFile);
        }

        return $result;
    }
}