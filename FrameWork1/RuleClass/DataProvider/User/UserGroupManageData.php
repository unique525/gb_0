<?php
/**
 * 后台管理 会员组  数据类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_User
 * @author zhangchi
 */
class UserGroupManageData extends BaseManageData {
    public function GetIsLock($userGroupId){
        $sql = "SELECT IsLock FROM " . self::TableId_ManageUserGroup . "  WHERE " . self::TableId_ManageUserGroup . "=:" . self::TableId_ManageUserGroup;
        $dataProperty = new DataProperty();
        $dataProperty->AddField(self::TableId_ManageUserGroup, $userGroupId);
        return $this->dbOperator->GetInt($sql, $dataProperty);
    }
}