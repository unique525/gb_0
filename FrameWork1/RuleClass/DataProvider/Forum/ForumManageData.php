<?php

/**
 * 后台管理 论坛 数据类
 * @category iCMS
 * @package iCMS_Rules_DataProvider_Forum
 * @author zhangchi
 */
class ForumManageData extends BaseManageData {

    /**
     * 取得字段数据集
     * @param string $tableName 表名
     * @return array 字段数据集
     */
    public function GetFields($tableName = self::TableName_Forum){
        return parent::GetFields(self::TableName_Forum);
    }

    /**
     * 新增论坛版块
     * @param array $httpPostData $_POST数组
     * @param string $forumPic1 版块图标1
     * @param string $forumPic2 版块图标2
     * @param string $forumPicMobile 移动客户端版块图标
     * @param string $forumPicPad 平板客户端版块图标
     * @return int 新增的论坛版块id
     */
    public function Create($httpPostData, $forumPic1 = '', $forumPic2 = '', $forumPicMobile = '', $forumPicPad = '') {
        $result = -1;
        $dataProperty = new DataProperty();
        $addFieldName = "";
        $addFieldValue = "";
        $preNumber = "";
        $addFieldNames = array("ForumPic1", "ForumPic2", "ForumPicMobile", "ForumPicPad");
        $addFieldValues = array($forumPic1, $forumPic2, $forumPicMobile, $forumPicPad);
        if (!empty($httpPostData)) {
            $sql = parent::GetInsertSql($httpPostData, self::TableName_Forum, $dataProperty, $addFieldName, $addFieldValue, $preNumber, $addFieldNames, $addFieldValues);
            $result = $this->dbOperator->LastInsertId($sql, $dataProperty);
        }
        return $result;
    }

    /**
     * 修改版块
     * @param array $httpPostData $_POST数组
     * @param int $forumId 版块id
     * @param string $forumPic1 版块图标1
     * @param string $forumPic2 版块图标2
     * @param string $forumPicMobile 移动客户端版块图标
     * @param string $forumPicPad 平板客户端版块图标
     * @return int 返回影响的行数
     */
    public function Modify($httpPostData, $forumId, $forumPic1 = "", $forumPic2 = "", $forumPicMobile = "", $forumPicPad = "") {
        $dataProperty = new DataProperty();
        $addFieldNames = array();
        $addFieldValues = array();
        if (!empty($forumPic1)) {
            $addFieldNames[] = "ForumPic1";
            $addFieldValues[] = $forumPic1;
        }
        if (!empty($forumPic2)) {
            $addFieldNames[] = "ForumPic2";
            $addFieldValues[] = $forumPic2;
        }
        if (!empty($titlePicMobile)) {
            $addFieldNames[] = "ForumPicMobile";
            $addFieldValues[] = $forumPicMobile;
        }
        if (!empty($titlePicPad)) {
            $addFieldNames[] = "ForumPicPad";
            $addFieldValues[] = $forumPicPad;
        }
        $sql = parent::GetUpdateSql($httpPostData, self::TableName_Forum, self::TableId_Forum, $forumId, $dataProperty, "", "", "", $addFieldNames, $addFieldValues);
        $result = $this->dbOperator->Execute($sql, $dataProperty);
        return $result;
    }
    
    /**
     * 修改版块状态
     * @param int $forumId 论坛版块id
     * @param int $state 状态
     * @return int 操作结果
     */
    public function ModifyState($forumId, $state) {
        $result = 0;
        if ($forumId > 0) {
            $dataProperty = new DataProperty();
            $sql = "UPDATE " . self::TableName_Forum . " SET `State`=:State WHERE ForumId=:ForumId;";
            $dataProperty->AddField("ForumId", $forumId);
            $dataProperty->AddField("State", $state);
            $result = $this->dbOperator->Execute($sql, $dataProperty);
        }
        return $result;
    }  
    
    
    /**
     * 取得上级版块名称
     * @param int $forumId 论坛版块id
     * @param bool $withCache 是否从缓冲中取
     * @return string 上级版块名称
     */
    public function GetParentName($forumId, $withCache) {
        $result = -1;
        if ($forumId > 0) {
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'forum_data';
            $cacheFile = 'forum_get_parent_name.cache_' . $forumId . '';
            $sql = "SELECT ForumName FROM " . self::TableName_Forum . "
                    WHERE ForumId =
                       (SELECT ParentId FROM " . self::TableName_Forum . " WHERE ForumId=:ForumId);";
            $dataProperty = new DataProperty();
            $dataProperty->AddField(self::TableId_Forum, $forumId);
            $result = $this->GetInfoOfStringValue($sql, $dataProperty, $withCache, $cacheDir, $cacheFile);
        }
        return $result;
    }
    
    /**
     * 根据版块等级取得版块列表
     * @param int $siteId 站点id
     * @param int $forumRank 版块等级
     * @return array 版块列表
     */
    public function GetListByRank($siteId, $forumRank) {
        $result = null;
        if($siteId>0 && $forumRank>=0){
            $sql = "SELECT * FROM " . self::TableName_Forum . " WHERE ForumRank=:ForumRank AND SiteId=:SiteId ORDER BY Sort DESC;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("ForumRank", $forumRank);
            $dataProperty->AddField("SiteId", $siteId);
            $result = $this->dbOperator->GetArrayList($sql, $dataProperty);
        }
        return $result;
    }

}

?>
