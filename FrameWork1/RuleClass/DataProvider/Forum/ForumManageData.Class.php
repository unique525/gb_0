<?php

/**
 * 论坛后台数据类  
 * @category iCMS
 * @package iCMS_Rules_DataProvider_Forum
 * @author zhangchi
 */
class ForumDataManage extends BaseManageData {
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
     * @param int $forumId
     * @param string $forumPic
     * @return type
     */
    public function Modify($forumId, $forumPic = "") {
        $dataProperty = new DataProperty();
        $sql = parent::GetUpdateSql(self::tableName, self::tableIdName, $forumId, $dataProperty, "ForumPic", $forumPic);
        $result = $this->dbOperator->Execute($sql, $dataProperty);
        return $result;
    }

}

?>
