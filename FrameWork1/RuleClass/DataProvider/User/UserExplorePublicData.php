<?php

/**
 * 会员浏览记录数据类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_UserExplore
 * @author hy
 */
class UserExplorePublicData extends BasePublicData
{

    /**
     * 新增会员浏览记录
     * @param array $httpPostData $_post数组
     * @return int 会员浏览记录Id
     */
    public function Create($httpPostData)
    {
        $dataProperty = new DataProperty();
        $sql = parent::GetInsertSql($httpPostData, self::TableName_UserExplore, $dataProperty);
        $result = $this->dbOperator->LastInsertId($sql, $dataProperty);
        return $result;
    }

    /**
     * 删除
     * @param int $siteId 站点ID
     * @param int $userId 用户ID
     * @param int $tableType 对应表类型
     * @param int $tableId 对应表ID
     * @return int 执行结果
     */
    public function Delete($siteId,$tableId,$tableType,$userId)
    {
        $result = -1;
        if ($userId > 0 && $tableId > 0 && $tableType > 0) {
            return $result;
        }
        $sql = "DELETE " . self::TableName_UserExplore . "
        WHERE SiteId=:SiteId AND UserId=:UserId AND TableType=:TableType AND TableId=:TableId";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("SiteId", $siteId);
        $dataProperty->AddField("UserId", $userId);
        $dataProperty->AddField("TableType", $tableType);
        $dataProperty->AddField("TableId", $tableId);
        $result = $this->dbOperator->Execute($sql, $dataProperty);
        return $result;
    }

    /**
     * 修改会员浏览记录
     * @param array $httpPostData $_post数组
     * @param int $userExploreId 会员浏览记录Id
     * @return int 执行结果
     */
    public function Modify($httpPostData,$userExploreId)
    {
        $result = -1;
        if ($userExploreId < 0) {
            return $result;
        }
        $dataProperty = new DataProperty();
        $sql = parent::GetUpdateSql($httpPostData, self::TableName_UserExplore, self::TableId_UserExplore, $userExploreId, $dataProperty);
        $result = $this->dbOperator->Execute($sql, $dataProperty);
        return $result;
    }

    /**
     * 获取会员浏览记录数组通用
     * @param int $siteId 站点ID
     * @param int $userId 用户ID
     * @param int $tableType 对应表类型
     * @param int $tableId 对应表ID
     * @param string $order 排序方式
     * @param int $topCount 显示的条数
     * @return array  返回查询题目数组
     */
    public function GetList($siteId, $userId, $tableType, $tableId, $order = "", $topCount = -1)
    {
        $result = null;
        if ($topCount != -1)
            $topCount = " limit " . $topCount;
        else $topCount = "";
        if ($siteId > 0 && $userId > 0 && $tableType > 0&& $tableId > 0) {
            switch ($order) {
                default:
                    $order = " ORDER BY CreateDate DESC";
                    break;
            }
            $sql = "
            SELECT UserExploreId,SiteId,TableType,TableId,UserId,CreateDate,ExploreUrl,ExploreDevice
            FROM " . self::TableName_UserExplore . "
            WHERE SiteId=:SiteId AND UserId=:UserId AND TableType=:TableType AND TableId=:TableId"
                . $order
                . $topCount;
            $dataProperty = new DataProperty();
            $dataProperty->AddField("SiteId", $siteId);
            $dataProperty->AddField("UserId", $userId);
            $dataProperty->AddField("TableType", $tableType);
            $dataProperty->AddField("TableId", $tableId);
            $result = $this->dbOperator->GetArrayList($sql, $dataProperty);
        }
        return $result;
    }

}

?>