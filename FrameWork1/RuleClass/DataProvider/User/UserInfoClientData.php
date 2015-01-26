<?php
/**
 * 客户端 会员信息 数据类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_User
 * @author yin
 */
class UserInfoClientData extends BaseClientData {


    /**
     * 初始化
     * @param $userId
     * @return int
     */
    public function Init($userId){
        $result = -1;
        if($userId > 0){
            $sqlSelect = "SELECT count(*) FROM " . self::TableName_UserInfo . " WHERE UserId=:UserId";
            $selectDataProperty = new DataProperty();
            $selectDataProperty->AddField("UserId", $userId);
            $selectResult = $this->dbOperator->GetInt($sqlSelect, $selectDataProperty);
            if($selectResult == 0){
                $sqlInsert = "INSERT INTO ".self::TableName_UserInfo." (UserId) VALUES (:UserId);";
                $insertDataProperty = new DataProperty();
                $insertDataProperty->AddField("UserId",$userId);
                $result = $this->dbOperator->Execute($sqlInsert, $insertDataProperty);
            }else{
                $result = $userId;
            }
        }
        return $result;
    }
} 