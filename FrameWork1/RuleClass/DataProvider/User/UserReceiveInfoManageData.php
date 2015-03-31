<?php
/**
 * 后台管理 会员收货信息 数据类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_User
 * @author yin
 */
class UserReceiveInfoManageData extends BaseManageData{

    /**
     * @param int $userId 会员Id
     * @return array|null 会员所有的收货信息
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

    public function GetOne($userReceiveInfoId){
        $result = null;
        if($userReceiveInfoId > 0){
            $sql = "SELECT * FROM ".self::TableName_UserReceiveInfo." WHERE UserReceiveInfoId = :UserReceiveInfoId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("UserReceiveInfoId",$userReceiveInfoId);
            $result = $this->dbOperator->GetArray($sql,$dataProperty);
        }
        return $result;
    }
}