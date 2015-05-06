<?php

/**
 * 公共访问 会员收货信息 数据类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_User
 * @author yin
 */
class UserReceiveInfoPublicData extends BasePublicData
{
    /**
     * 新增
     * @param int $userId 会员id
     * @param string $address 收货地址
     * @param string $postcode 邮编
     * @param string $receivePersonName 收件人姓名
     * @param string $homeTel 固定电话
     * @param string $mobile 手机号码
     * @param string $city 城市
     * @param string $district 区
     * @return int 返回新增的id
     */
    public function Create(
        $userId,
        $address,
        $postcode,
        $receivePersonName,
        $homeTel = "",
        $mobile="",
        $city = "",
        $district = ""
    ){
        $result = -1;
        if($userId > 0){
            $sql = "INSERT INTO ".self::TableName_UserReceiveInfo."
                        (
                        UserId,
                        Address,
                        PostCode,
                        ReceivePersonName,
                        HomeTel,
                        Mobile,
                        City,
                        District
                        )

                    VALUES (
                        :UserId,
                        :Address,
                        :PostCode,
                        :ReceivePersonName,
                        :HomeTel,
                        :Mobile,
                        :City,
                        :District
                        );";

            $dataProperty = new DataProperty();
            $dataProperty->AddField("UserId",$userId);
            $dataProperty->AddField("Address",$address);
            $dataProperty->AddField("PostCode",$postcode);
            $dataProperty->AddField("ReceivePersonName",$receivePersonName);
            $dataProperty->AddField("HomeTel",$homeTel);
            $dataProperty->AddField("Mobile",$mobile);
            $dataProperty->AddField("City",$city);
            $dataProperty->AddField("District",$district);
            $result = $this->dbOperator->LastInsertId($sql,$dataProperty);
        }
        return $result;
    }

    /**
     * 编辑
     * @param int $userId 会员id
     * @param int $userReceiveInfoId
     * @param string $address 收货地址
     * @param string $postcode 邮编
     * @param string $receivePersonName 收件人姓名
     * @param string $homeTel 固定电话
     * @param string $mobile 手机号码
     * @param string $city 城市
     * @param string $district 区
     * @return int 编辑结果
     */
    public function Modify(
        $userId,
        $userReceiveInfoId,
        $address,
        $postcode,
        $receivePersonName,
        $homeTel = "",
        $mobile="",
        $city = "",
        $district = ""

    ){
        $result = -1;
        if($userId > 0){
            $sql = "UPDATE ".self::TableName_UserReceiveInfo." SET
                            Address=:Address,
                            Postcode=:Postcode,
                            ReceivePersonName=:ReceivePersonName,
                            HomeTel=:HomeTel,
                            Mobile=:Mobile,
                            City=:City,
                            District=:District
                            WHERE
                                UserId = :UserId
                                AND UserReceiveInfoId = :UserReceiveInfoId;";

            $dataProperty = new DataProperty();
            $dataProperty->AddField("UserReceiveInfoId",$userReceiveInfoId);
            $dataProperty->AddField("UserId",$userId);
            $dataProperty->AddField("Address",$address);
            $dataProperty->AddField("Postcode",$postcode);
            $dataProperty->AddField("ReceivePersonName",$receivePersonName);
            $dataProperty->AddField("HomeTel",$homeTel);
            $dataProperty->AddField("Mobile",$mobile);
            $dataProperty->AddField("City",$city);
            $dataProperty->AddField("District",$district);
            $result = $this->dbOperator->Execute($sql,$dataProperty);
        }
        return $result;
    }

    /**
     * 返回列表
     * @param int $userId 会员id
     * @return array|null 返回列表数据集
     */
    public function GetList($userId){
        $result = null;
        if($userId > 0){
            $sql = "SELECT * FROM ".self::TableName_UserReceiveInfo." WHERE UserId = :UserId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("UserId",$userId);
            $result = $this->dbOperator->GetArrayList($sql,$dataProperty);
        }
        return $result;
    }

    public function GetOne($userReceiveInfoId,$userId){
        $result = null;
        if($userReceiveInfoId > 0 && $userId > 0){
            $sql = "SELECT * FROM ".self::TableName_UserReceiveInfo." WHERE UserReceiveInfoId = :UserReceiveInfoId AND UserId = :UserId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("UserReceiveInfoId",$userReceiveInfoId);
            $dataProperty->AddField("UserId",$userId);
            $result = $this->dbOperator->GetArray($sql,$dataProperty);
        }
        return $result;
    }
}

?>
