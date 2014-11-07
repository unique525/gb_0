<?php

/**
 * 公共访问 会员信息 数据类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_User
 * @author yin
 */
class UserInfoData extends BaseData
{
    const State_Unavailable_User = 100;

    public function GetOne($userId,$siteId){
        $result = null;
        if($userId > 0){
            $sql = "SELECT
                            ui.*,
                            ul.UserLevelId,
                            ur.UserGroupId
                            FROM ".self::TableName_User." u
                            INNER JOIN ".self::TableName_UserInfo." ui ON (u.UserId = ui.UserId)
                            LEFT JOIN ".self::TableName_UserSiteLevel." usl ON (u.UserId = usl.UserId) AND usl.SiteId=:SiteId
                            LEFT JOIN ".self::TableName_UserRole." ur ON (u.UserId = ur.UserId) AND ur.SiteId = :SiteId2
                            LEFT JOIN ".self::TableName_UserLevel." ul ON (usl.SiteId = ul.SiteId) AND (usl.UserLevelId = ul.UserLevelId)
                            LEFT JOIN ".self::TableName_UserGroup." ug ON (ur.UserGroupId = ug.UserGroupId) AND (ur.SiteId = ug.SiteId)
                            WHERE u.UserId=:UserId AND u.SiteId = :SiteId3 AND u.State<".self::State_Unavailable_User.";";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("SiteId",$siteId);
            $dataProperty->AddField("SiteId2",$siteId);
            $dataProperty->AddField("SiteId3",$siteId);
            $dataProperty->AddField("UserId",$userId);
            $result = $this->dbOperator->GetArray($sql,$dataProperty);
        }
        return $result;
    }

}

?>
