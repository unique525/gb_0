<?php

/**
 * 后台管理 管理员分组 数据类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_Manage
 * @author zhangchi
 */
class ManageUserGroupManageData extends BaseManageData {

    /**
     * 取得字段数据集
     * @param string $tableName 表名
     * @return array 字段数据集
     */
    public function GetFields($tableName = self::TableName_ManageUserGroup){
        return parent::GetFields($tableName);
    }

    /**
     * 新增
     * @param array $httpPostData $_POST数组
     * @return int 新增的id
     */
    public function Create($httpPostData)
    {
        $result = -1;
        $dataProperty = new DataProperty();
        $addFieldName = "";
        $addFieldValue = "";
        $preNumber = "";
        $addFieldNames = array("CreateDate");
        $addFieldValues = array(date("Y-m-d H:i:s", time()));
        if (!empty($httpPostData)) {
            $sql = parent::GetInsertSql(
                $httpPostData,
                self::TableName_ManageUserGroup,
                $dataProperty,
                $addFieldName,
                $addFieldValue,
                $preNumber,
                $addFieldNames,
                $addFieldValues
            );
            $result = $this->dbOperator->LastInsertId($sql, $dataProperty);
        }
        return $result;
    }

    /**
     * 修改
     * @param array $httpPostData $_POST数组
     * @param int $manageUserGroupId id
     * @return int 返回影响的行数
     */
    public function Modify($httpPostData, $manageUserGroupId)
    {
        $result = -1;
        $dataProperty = new DataProperty();
        $addFieldName = "";
        $addFieldValue = "";
        $preNumber = "";
        $addFieldNames = array();
        $addFieldValues = array();

        if (!empty($httpPostData)) {
            $sql = parent::GetUpdateSql(
                $httpPostData,
                self::TableName_ManageUserGroup,
                self::TableId_ManageUserGroup,
                $manageUserGroupId,
                $dataProperty,
                $addFieldName,
                $addFieldValue,
                $preNumber,
                $addFieldNames,
                $addFieldValues
            );
            $result = $this->dbOperator->Execute($sql, $dataProperty);
        }
        return $result;
    }

    /**
     * 删除管理分组到回收站
     * @param int $manageUserGroupId 管理分组id
     * @return int 返回影响的行数
     */
    public function RemoveToBin($manageUserGroupId)
    {
        $result = -1;
        if ($manageUserGroupId > 0) {
            $sql = "UPDATE " . self::TableName_ManageUserGroup . " SET State=100 WHERE ManageUserGroupId=:ManageUserGroupId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("ManageUserGroupId", $manageUserGroupId);
            $result = $this->dbOperator->Execute($sql, $dataProperty);
        }

        return $result;
    }



    /**
     * 修改状态
     * @param int $manageUserGroupId id
     * @param int $state 状态
     * @return int 操作结果
     */
    public function ModifyState($manageUserGroupId, $state)
    {
        $result = 0;
        if ($manageUserGroupId > 0) {
            $dataProperty = new DataProperty();
            $sql = "UPDATE " . self::TableName_ManageUserGroup . " SET `State`=:State WHERE ".self::TableId_ManageUserGroup."=:".self::TableId_ManageUserGroup.";";
            $dataProperty->AddField(self::TableId_ManageUserGroup, $manageUserGroupId);
            $dataProperty->AddField("State", $state);
            $result = $this->dbOperator->Execute($sql, $dataProperty);
        }
        return $result;
    }



    /**
     * 根据Id取得一条记录
     * @param int $manageUserGroupId 管理分组id
     * @return array 一条记录数组
     */
    public function GetOne($manageUserGroupId)
    {
        $result = null;
        if ($manageUserGroupId > 0) {
            $sql = "SELECT * FROM " . self::TableName_ManageUserGroup . " WHERE " . self::TableId_ManageUserGroup . "=:" . self::TableId_ManageUserGroup . "";
            $dataProperty = new DataProperty();
            $dataProperty->AddField(self::TableId_ManageUserGroup, $manageUserGroupId);
            $result = $this->dbOperator->GetArray($sql, $dataProperty);
        }

        return $result;
    }

    /**
     * 根据管理分组列表数据集
     * @param int $pageBegin 分页起始位置
     * @param int $pageSize 分页大小
     * @param int $allCount 记录总数（输出参数）
     * @param string $searchKey 查询关键字
     * @param int $searchType 查询字段类型
     * @return array 管理分组列表数据集
     */
    public function GetList(
        $pageBegin,
        $pageSize,
        &$allCount,
        $searchKey,
        $searchType
    )
    {
        $dataProperty = new DataProperty();
        $searchSql = "";

        //查询
        if (strlen($searchKey) > 0 && $searchKey != "undefined") {
            if ($searchType == 0) { //名称
                $searchSql = " AND (ManageUserGroupName like :SearchKey)";
                $dataProperty->AddField("SearchKey", "%" . $searchKey . "%");
            } else { //名称
                $searchSql = " AND (ManageUserGroupName like :SearchKey)";
                $dataProperty->AddField("SearchKey", "%" . $searchKey . "%");
            }
        }

        $limit="";
        if($pageBegin>=0&&$pageSize>=0){
            $limit.=" LIMIT " . $pageBegin . "," . $pageSize;
        }

        $sql = "SELECT * FROM " . self::TableName_ManageUserGroup . "
                        WHERE
                            State<".ManageUserGroupData::STATE_DELETE."


                            $searchSql

                        ORDER BY Sort DESC,convert(ManageUserGroupName USING gbk)
                         ". $limit . " ;";
        $sqlCount = "SELECT Count(*) FROM " . self::TableName_ManageUserGroup . "
                        WHERE
                            State<".ManageUserGroupData::STATE_DELETE."

                            $searchSql;";

        $result = $this->dbOperator->GetArrayList($sql, $dataProperty);
        $allCount = $this->dbOperator->GetInt($sqlCount, $dataProperty);
        return $result;
    }


    /**
     * 根据用户ID取得左边导航权限
     * @param int $manageUserId
     * @return string 左边导航编号分割字符串
     */
    public function GetManageMenuOfColumnIdValue($manageUserId) {
        if($manageUserId>0){
            $dataProperty = new DataProperty();
            $sql = "SELECT ManageMenuOfColumnIdValue FROM " . self::TableName_ManageUserGroup . " WHERE ManageUserGroupId IN (SELECT ManageUserGroupId FROM ".self::TableName_ManageUser." WHERE ManageUserId=:ManageUserId);";
            $dataProperty->AddField("ManageUserId", $manageUserId);
            $result = $this->dbOperator->GetString($sql, $dataProperty);
            return $result;
        }else{
            return null;
        }
    }

    /**
     * 获取所有分组的id与组名数据集
     * @return array 管理分组列表数据集
     */
    public function GetAll(
    )
    {
        $dataProperty = new DataProperty();
        $searchSql = "";


        $sql = "SELECT ManageUserGroupId,ManageUserGroupName FROM " . self::TableName_ManageUserGroup . "
                        WHERE
                            State<".ManageUserGroupData::STATE_DELETE."


                            $searchSql

                        ORDER BY Sort DESC,convert(ManageUserGroupName USING gbk);";

        $result = $this->dbOperator->GetArrayList($sql, $dataProperty);
        return $result;
    }
}

?>
