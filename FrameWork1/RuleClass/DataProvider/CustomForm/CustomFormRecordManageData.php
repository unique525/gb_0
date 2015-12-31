<?php

/**
 * 后台管理 活动表单记录 数据类
 *
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_Customform
 * @author 525
 */
class CustomFormRecordManageData extends BaseManageData {
    /**
     * 新增一条记录
     * @param array $httpPostData $_post数组
     * @return int 新增表单记录id
     */
    public function Create($httpPostData) {
        $result=-1;
        if(!empty($httpPostData)){
            $dataProperty = new DataProperty();
            $sql = parent::GetInsertSql($httpPostData,self::TableName_CustomFormRecord, $dataProperty);
            $result = $this->dbOperator->LastInsertId($sql, $dataProperty);
        }
        return $result;
    }

    /**
     * 修改
     * @param array $httpPostData $_post数组
     * @param int $customFormRecordId 表单记录id
     * @return int 执行结果
     */
    public function Modify($httpPostData,$customFormRecordId) {
        $result=-1;
        if(!empty($httpPostData)){
            $dataProperty = new DataProperty();
            $sql = parent::GetUpdateSql($httpPostData,self::TableName_CustomFormRecord, self::TableId_CustomFormRecord, $customFormRecordId, $dataProperty);
            $result = $this->dbOperator->Execute($sql, $dataProperty);
        }
        return $result;
    }

    /**
     * 获取表单记录分页列表
     * @param int $customFormId 表单id
     * @param int $pageBegin 起始页码
     * @param int $pageSize 每页大小
     * @param int $allCount 总大小
     * @return array 表单记录数据集
     */
    public function GetListPager($customFormId, $pageBegin, $pageSize, &$allCount) {
        $result=-1;
        if($customFormId>0){
            $dataProperty = new DataProperty();
            $dataProperty->AddField("CustomFormId", $customFormId);
            $sql = "SELECT * FROM " . self::TableName_CustomFormRecord . " WHERE CustomFormId=:CustomFormId ORDER BY Sort DESC,CreateDate DESC LIMIT " . $pageBegin . "," . $pageSize . " ;";
            $result = $this->dbOperator->GetArrayList($sql, $dataProperty);

            $sqlCount = "SELECT count(*) FROM ".self::TableName_CustomFormRecord." WHERE CustomFormId=:CustomFormId ;";
            $allCount = $this->dbOperator->GetInt($sqlCount, $dataProperty);
        }
        return $result;
    }

    /**
     * 获取随机表单记录列表
     * @param int $customFormId 表单id
     * @param int $pageSize 每页大小
     * @param int $beginTime 开始时间
     * @param int $endTime 截止时间
     * @return array 表单记录数据集
     */
    public function GetRandomList($customFormId, $pageSize,$beginTime,$endTime) {
        $result=-1;
        if($customFormId>0){
            $selectDate="";
            if($beginTime!=""&&$endTime!=""){
                $selectDate=" AND CreateDate>'$beginTime' AND CreateDate<'$endTime' ";
            }
            $dataProperty = new DataProperty();
            $dataProperty->AddField("CustomFormId", $customFormId);
            $sql = "SELECT * FROM " . self::TableName_CustomFormRecord . " WHERE CustomFormId=:CustomFormId AND State=0 $selectDate ORDER BY Rand() LIMIT $pageSize ;";
            $result = $this->dbOperator->GetArrayList($sql, $dataProperty);
        }
        return $result;
    }


    /**
     * 通过ID获取一条记录
     * @param int $customFormRecordId
     * @return array 取得的表单记录
     */
    public function GetOne($customFormRecordId) {
        $result=-1;
        if($customFormRecordId>0){
            $sql = "SELECT * FROM " . self::TableName_CustomFormRecord . " WHERE CustomFormRecordId = :CustomFormRecordId ;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("CustomFormRecordId", $customFormRecordId);
            $result = $this->dbOperator->GetArray($sql, $dataProperty);
        }
        return $result;
    }

    /**
     * 修改状态
     * @param int $customFormRecordId id
     * @param int $state 状态
     * @return int 操作结果
     */
    public function ModifyState($customFormRecordId, $state)
    {
        $result = 0;
        if ($customFormRecordId > 0) {
            $dataProperty = new DataProperty();
            $sql = "UPDATE " . self::TableName_CustomFormRecord . " SET `State`=:State WHERE CustomFormRecordId=:CustomFormRecordId;";
            $dataProperty->AddField("CustomFormRecordId", $customFormRecordId);
            $dataProperty->AddField("State", $state);
            $result = $this->dbOperator->Execute($sql, $dataProperty);
        }
        return $result;
    }

    /**
     * 批量修改状态
     * @param string $strCustomFormRecordId id
     * @param int $state 状态
     * @return int 操作结果
     */
    public function BatchModifyState($strCustomFormRecordId, $state)
    {
        $result = 0;
        if (strlen($strCustomFormRecordId) > 0) {
            $dataProperty = new DataProperty();
            $sql = "UPDATE " . self::TableName_CustomFormRecord . " SET `State`=:State WHERE CustomFormRecordId IN ($strCustomFormRecordId);";
            $dataProperty->AddField("State", $state);
            $result = $this->dbOperator->Execute($sql, $dataProperty);
        }
        return $result;
    }

    /**
     * 获取表单记录分页列表
     * @param int $customFormId 表单id
     * @param int $pageBegin 起始页码
     * @param int $pageSize 每页大小
     * @param int $allCount 总大小
     * @param array $searchArray 搜索字段内容数据集
     * @return array 表单记录数据集
     */
    public function GetListPagerOfContentSearch($customFormId, $pageBegin, $pageSize, &$allCount, $searchArray) {
        $result=-1;
        if($customFormId>0){
            $pageSize=1000;//由于太耗资源。。 不分页了  调1000条
            $dataProperty = new DataProperty();
            $dataProperty->AddField("CustomFormId", $customFormId);
            $sqlSearch="";
            if(count($searchArray)>0&&$searchArray!=null){
                foreach($searchArray as $value){
                    if($value["content"]==""){
                        continue;
                    }
                    //echo $value["type"];
                    switch ($value["type"]) {
                        case 0://int
                            $arr = Format::ToSplit($value["content"], '_');
                            if(count($arr)<2||$arr==null){
                                break;
                            }
                            $dataProperty->AddField("Greater", $arr[0]);
                            $dataProperty->AddField("Less", $arr[1]);
                            $sqlSearch.=" AND CustomFormRecordId IN (SELECT CustomFormRecordId FROM " . self::TableName_CustomFormContent . " WHERE CustomFormId=:CustomFormId AND ContentOfInt BETWEEN :Greater AND :Less ";
                            if($value["field"]>0&&$value["field"]!=""){
                                $dataProperty->AddField("CustomFormFieldId", $value["field"]);
                                $sqlSearch.=" AND CustomFormFieldId=:CustomFormFieldId ";
                            }
                            $sqlSearch.=" ) ";
                            break;
                        case 1://string
                            $dataProperty->AddField("ContentOfString", "%".$value["content"]."%");
                            $sqlSearch.=" AND CustomFormRecordId IN (SELECT CustomFormRecordId FROM " . self::TableName_CustomFormContent . " WHERE CustomFormId=:CustomFormId AND ContentOfString LIKE :ContentOfString ";
                            if($value["field"]>0&&$value["field"]!=""){
                                $dataProperty->AddField("CustomFormFieldId", $value["field"]);
                                $sqlSearch.=" AND CustomFormFieldId=:CustomFormFieldId ";
                            }
                            $sqlSearch.=" ) ";
                            break;
                        case 2://text
                            $dataProperty->AddField("ContentOfText", "%".$value["content"]."%");
                            $sqlSearch.=" AND CustomFormRecordId IN (SELECT CustomFormRecordId FROM " . self::TableName_CustomFormContent . " WHERE CustomFormId=:CustomFormId AND ContentOfText LIKE :ContentOfText ";
                            if($value["field"]>0&&$value["field"]!=""){
                                $dataProperty->AddField("CustomFormFieldId", $value["field"]);
                                $sqlSearch.=" AND CustomFormFieldId=:CustomFormFieldId ";
                            }
                            $sqlSearch.=" ) ";
                            break;
                        case 3://float
                            $arr = Format::ToSplit($value["content"], '_');
                            if(count($arr)<2||$arr==null){
                                break;
                            }
                            $dataProperty->AddField("Greater", $arr[0]);
                            $dataProperty->AddField("Less", $arr[1]);
                            $sqlSearch.=" AND CustomFormRecordId IN (SELECT CustomFormRecordId FROM " . self::TableName_CustomFormContent . " WHERE CustomFormId=:CustomFormId AND ContentOfFloat BETWEEN :Greater AND :Less ";
                            if($value["field"]>0&&$value["field"]!=""){
                                $dataProperty->AddField("CustomFormFieldId", $value["field"]);
                                $sqlSearch.=" AND CustomFormFieldId=:CustomFormFieldId ";
                            }
                            $sqlSearch.=" ) ";
                            break;
                        case 4://date
                            break;
                        case 5://blob
                            break;
                        case -1://state状态
                            //echo "in";
                            $dataProperty->AddField("State", $value['content']);
                            $sqlSearch.=' AND State=:State ';
                            break;

                    }



                }

            }
            $sql="SELECT * FROM " . self::TableName_CustomFormRecord . " WHERE CustomFormId=:CustomFormId " .$sqlSearch. " ORDER BY Sort DESC,CreateDate DESC LIMIT " . $pageBegin . "," . $pageSize . " ;";
            //echo $sql;
            $result = $this->dbOperator->GetArrayList($sql, $dataProperty);

            /*  由于太耗资源。。 不分页了  调1000条
                        $sqlCount = "SELECT count(*) FROM ".self::TableName_CustomFormRecord . " WHERE CustomFormId=:CustomFormId AND CustomFormRecordId IN
                              (SELECT CustomFormRecordId FROM " . self::TableName_CustomFormContent. " WHERE CustomFormId=:CustomFormId "
                                .$sqlSearch. ");";
                        $allCount = $this->dbOperator->GetInt($sqlCount, $dataProperty);
                        */
        }
        return $result;
    }

}

?>
