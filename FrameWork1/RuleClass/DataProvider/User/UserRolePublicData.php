<?php

/**
 * 公共访问 会员角色 数据类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_User
 * @author yin
 */
class UserRolePublicData extends BasePublicData
{

    public function Init($userId,$siteId,$userGroupId){
        $result = -1;
        if($userId > 0 && $siteId > 0){
            $sqlSelect = "SELECT count(*) FROM " . self::TableName_UserRole . " WHERE UserId=:UserId";
            $selectDataProperty = new DataProperty();
            $selectDataProperty->AddField("UserId", $userId);
            $selectResult = $this->dbOperator->GetInt($sqlSelect, $selectDataProperty);
            if($selectResult == 0){
                $sqlInsert = "INSERT INTO ".self::TableName_UserRole." (UserId,SiteId,UserGroupId) VALUES (:UserId,:SiteId,:UserGroupId);";
                $insertDataProperty = new DataProperty();
                $insertDataProperty->AddField("UserId",$userId);
                $insertDataProperty->AddField("SiteId",$siteId);
                $insertDataProperty->AddField("UserGroupId",$userGroupId);
                $result = $this->dbOperator->GetInt($sqlInsert, $insertDataProperty);
            }else{
                $result = $userId;
            }
        }
        return $result;
    }
}

?>
