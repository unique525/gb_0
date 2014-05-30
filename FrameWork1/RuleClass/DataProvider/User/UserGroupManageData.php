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

    /**
     * 返回对应siteid和state的会员组列表
     * @param int $siteId 站点ID
     * @param int $state 数据状态
     * @return array 会员数组列表数据集
     */
    public function GetList($siteId = 0, $state = 0) {
        $dataProperty = new DataProperty();
        $sql = "SELECT * FROM " . self::TableId_ManageUserGroup . " WHERE State=:State AND SiteId=:SiteId";
        $dataProperty->AddField("State", $state);
        $dataProperty->AddField("SiteId", $siteId);
        $result = $this->dbOperator->GetArrayList($sql, $dataProperty);
        return $result;
    }
}