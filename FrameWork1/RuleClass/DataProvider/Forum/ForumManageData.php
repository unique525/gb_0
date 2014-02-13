<?php

/**
 * 论坛后台数据类  
 * @category iCMS
 * @package iCMS_Rules_DataProvider_Forum
 * @author zhangchi
 */
class ForumManageData extends BaseManageData {
    /**
     * 表名
     */
    const tableName = "cst_forum";
    /**
     * 表关键字段名
     */
    const tableIdName = "ForumId";

    /**
     * 新增
     * @param string $forumPic 论坛图标网址
     * @return int 返回新增的论坛id
     */
    public function Create($forumPic = "") {
        $dataProperty = new DataProperty();
        $sql = parent::GetInsertSql(self::tableName, $dataProperty, "forumpic", $forumPic);
        $result = $this->dbOperator->LastInsertId($sql, $dataProperty);
        return $result;
    }

    /**
     * 修改
     * @param int $forumId 论坛id
     * @param string $forumPic 论坛LOGO
     * @return int 执行结果
     */
    public function Modify($forumId, $forumPic = "") {
        $dataProperty = new DataProperty();
        $sql = parent::GetUpdateSql(self::tableName, self::tableIdName, $forumId, $dataProperty, "ForumPic", $forumPic);
        $result = $this->dbOperator->Execute($sql, $dataProperty);
        return $result;
    }
    
    /**
     * 修改状态
     * @param int $forumId 论坛id
     * @param int $state 状态
     * @return int 操作结果
     */
    public function ModifyState($forumId, $state) {
        $result = 0;
        if ($forumId > 0) {
            $dataProperty = new DataProperty();
            $sql = "UPDATE " . self::tableName . " SET `State`=:State WHERE ForumId=:ForumId;";
            $dataProperty->AddField("ForumId", $forumId);
            $dataProperty->AddField("State", $state);
            $result = $this->dbOperator->Execute($sql, $dataProperty);
        }
        return $result;
    }  
    
    
    /**
     * 取得上级版块名称
     * @param int $forumId
     * @return string 上级版块名称
     */
    public function GetParentName($forumId) {
        $dataProperty = new DataProperty();
        $sql = "SELECT ForumName FROM  " . self::tableName . "  WHERE  " . self::tableIdName . "=(SELECT parentid FROM  " . self::tableName . "  WHERE  " . self::tableIdName . "=:" . self::tableIdName . ")";
        $dataProperty->AddField(self::tableIdName, $forumId);
        $result = $this->dbOperator->GetString($sql, $dataProperty);
        return $result;
    }
    
    /**
     * 根据版块等级取得版块列表
     * @param int $siteId 站点id
     * @param int $forumRank 版块等级
     * @return array 版块列表
     */
    public function GetListByRank($siteId, $forumRank) {
        $sql = "SELECT * FROM " . self::tableName . " WHERE ForumRank=:ForumRank AND SiteId=:SiteId ORDER BY sort DESC";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("ForumRank", $forumRank);
        $dataProperty->AddField("SiteId", $siteId);
        $result = $this->dbOperator->GetArrayList($sql, $dataProperty);
        return $result;
    }

}

?>