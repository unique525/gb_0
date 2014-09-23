<?php

/**
 * 前台 会员身份 数据类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_User
 * @author zhangchi
 */
class UserGroupPublicData extends BasePublicData {

    /**
     * 返回某一站点下所有会员身份列表
     * @param int $siteId 站点id
     * @return array 会员身份列表
     */
    public function GetList($siteId)
    {
        if ($siteId > 0) {
            $sql = "SELECT UserGroupId,UserGroupName,UserGroupShortName
                        FROM " . self::TableName_UserGroup . "
                        WHERE SiteId=:SiteId
                        ORDER BY Sort DESC
                        ;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("SiteId", $siteId);
            $result = $this->dbOperator->GetArrayList($sql, $dataProperty);
            return $result;
        } else {
            return null;
        }
    }
} 