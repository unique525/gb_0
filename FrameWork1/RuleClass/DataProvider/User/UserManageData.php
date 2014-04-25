<?php

/**
 * 后台管理 会员 数据类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_User
 * @author zhangchi
 */
class UserManageData extends BaseManageData {

    /**
     * 取得后台会员列表数据集
     * @param int $siteId 站点id
     * @param int $pageBegin 起始记录行
     * @param int $pageSize 页大小
     * @param int $allCount 记录总数
     * @param string $searchKey 查询字符
     * @param int $searchType 查询下拉框的类别
     * @param int $manageUserId 后台管理员id
     * @return array 会员列表数据集
     */
    public function GetList($siteId, $pageBegin, $pageSize, &$allCount, $searchKey = "", $searchType = 0, $manageUserId = 0)
    {
        $searchSql = "";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("SiteId", $siteId);
        if (strlen($searchKey) > 0 && $searchKey != "undefined") {
            if ($searchType == 0) { //会员名
                $searchSql = " AND (u.UserName like :SearchKey)";
                $dataProperty->AddField("SearchKey", "%" . $searchKey . "%");
            }
        }

        $sql = "
            SELECT
            u.*,
            ui.Avatar
            FROM
            " . self::TableName_User . " u,". self::TableName_UserInfo ." ui
            WHERE u.UserId=ui.UserId AND u.SiteId=:SiteId " . $searchSql . " ORDER BY u.CreateDate DESC LIMIT " . $pageBegin . "," . $pageSize . ";";

        $result = $this->dbOperator->GetArrayList($sql, $dataProperty);

        $sql = "SELECT count(*) FROM " . self::TableName_User . " u,". self::TableName_UserInfo ." ui WHERE u.UserId=ui.UserId AND u.SiteId=:SiteId " . $searchSql . ";";
        $allCount = $this->dbOperator->GetInt($sql, $dataProperty);

        return $result;
    }
} 