<?php

/**
 * 公共访问 会员收货信息 数据类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_User
 * @author yin
 */
class UserReceiveInfoPublicData extends BasePublicData
{
    public function Create($userId,$address,$postcode,$receivePersonName,$homeTel = "",$mobile=""){
        $result = -1;
        if($userId > 0){
            $sql = "INSERT INTO ".self::TableName_UserReceiveInfo." (UserId,Address,PostCode,ReceivePersonName,HomeTel,Mobile)
                            VALUES (:UserId,:Address,:PostCode,:ReceivePersonName,:HomeTel,:Mobile);";

            $dataProperty = new DataProperty();
            $dataProperty->AddField("UserId",$userId);
            $dataProperty->AddField("Address",$address);
            $dataProperty->AddField("PostCode",$postcode);
            $dataProperty->AddField("ReceivePersonName",$receivePersonName);
            $dataProperty->AddField("HomeTel",$homeTel);
            $dataProperty->AddField("Mobile",$mobile);
            $result = $this->dbOperator->LastInsertId($sql,$dataProperty);
        }
        return $result;
    }

    public function Modify($userId,$userReceiveInfoId,$address,$postcode,$receivePersonName,$homeTel = "",$mobile=""){
        $result = -1;
        if($userId > 0){
            $sql = "UPDATE ".self::TableName_UserReceiveInfo." SET
                            Address=:Address,
                            Postcode=:Postcode,
                            ReceivePersonName=:ReceivePersonName,
                            HomeTel=:HomeTel,
                            Mobile=:Mobile
                            WHERE UserId = :UserId AND UserReceiveInfoId = :UserReceiveInfoId;";

            $debug = new DebugLogManageData();
            $debug->Create($sql);
            $dataProperty = new DataProperty();
            $dataProperty->AddField("UserReceiveInfoId",$userReceiveInfoId);
            $dataProperty->AddField("UserId",$userId);
            $dataProperty->AddField("Address",$address);
            $dataProperty->AddField("Postcode",$postcode);
            $dataProperty->AddField("ReceivePersonName",$receivePersonName);
            $dataProperty->AddField("HomeTel",$homeTel);
            $dataProperty->AddField("Mobile",$mobile);
            $result = $this->dbOperator->Execute($sql,$dataProperty);
        }
        return $result;
    }

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
}

?>
