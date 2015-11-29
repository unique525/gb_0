<?php

/**
 * 后台管理 论坛分类(小) 数据类
 *
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_Forum
 * @author momo
 */
class ForumTopicTypeManageData extends BaseManageData
{

    public function GetListPager($siteId, $forumId, $pageBegin, $pageSize, &$allCount, $searchKey) {
        $result=-1;
        if($siteId > 0){
            $searchSql = "";
            $dataProperty = new DataProperty();
            $dataProperty->AddField('SiteId', $siteId);
            $dataProperty->AddField('ForumId', $forumId);

            if (strlen($searchKey) > 0 && $searchKey != "undefined") {
                $searchSql.=' AND ForumTopicTypeName LIKE :SearchKey ';
                $dataProperty->AddField("SearchKey", "%" . $searchKey . "%");
            }


            $sql = 'SELECT *'.
                    ' FROM '.self::TableName_ForumTopicType.
                    ' WHERE SiteId=:SiteId AND ForumId=:ForumId '.$searchSql.
                    ' ORDER BY Sort DESC'.
                    ' LIMIT '.$pageBegin.','.$pageSize.';';

            $result = $this->dbOperator->GetArrayList($sql, $dataProperty);

            $sqlCount = 'SELECT COUNT(ForumTopicTypeId)'.
                        ' FROM '.self::TableName_ForumTopicType.
                        ' WHERE SiteId=:SiteId AND ForumId=:ForumId'.$searchSql.';';
            $allCount = $this->dbOperator->GetInt($sqlCount, $dataProperty);
        }
        return $result;
    }

    public function Create($siteId, $forumId, $newType){
        $result = -1;

        if($siteId > 0 && $forumId >0 &&count($newType) > 0){
            $dataProperty = new DataProperty();
            $dataProperty->AddField('SiteId', $siteId);
            $dataProperty->AddField('ForumId', $forumId);
            $dataProperty->AddField('ForumTopicTypeName', $newType);

            $sql = 'SELECT COUNT(ForumTopicTypeName)'.
                   ' FROM '.self::TableName_ForumTopicType.
                   ' WHERE SiteId=:SiteId AND ForumId=:ForumId AND ForumTopicTypeName=:ForumTopicTypeName;';
            $is_exist_type = $this->dbOperator->GetInt($sql, $dataProperty);
            if($is_exist_type > 0){
                $result = -2;
            }
            else{
                $dataProperty->AddField("State",1);
                $dataProperty->AddField("Sort",0);
                $sql = 'INSERT INTO '.self::TableName_ForumTopicType.'(SiteId, ForumId, ForumTopicTypeName, State, Sort)'.
                       ' VALUE (:SiteId, :ForumId, :ForumTopicTypeName, :State, :Sort)';
                $result = $this->dbOperator->Execute($sql, $dataProperty);
            }
        }
        return $result;
    }

    public function ModifyState($siteId, $forumId, $forumTopicTypeId, $state){
        $result = -1;

        $dataProperty = new DataProperty();
        $dataProperty->AddField('SiteId', $siteId);
        $dataProperty->AddField('ForumId', $forumId);
        $dataProperty->AddField('ForumTopicTypeId', $forumTopicTypeId);
        $dataProperty->AddField('State', $state);

        $sql =  'UPDATE '.self::TableName_ForumTopicType.
                ' SET State=:State'.
                ' WHERE SiteId=:SiteId AND ForumId=:ForumId AND ForumTopicTypeId=:ForumTopicTypeId;';

        $resultTemp = $this->dbOperator->Execute($sql, $dataProperty);

        if($resultTemp > 0){
            $result = $resultTemp;
        }

        return $result;
    }

    public function DeleteType($siteId, $forumId, $forumTopicTypeId){
        $result = -1;

        $dataProperty = new DataProperty();
        $dataProperty->AddField('SiteId', $siteId);
        $dataProperty->AddField('ForumId', $forumId);
        $dataProperty->AddField('ForumTopicTypeId', $forumTopicTypeId);

        $sql =  'DELETE FROM '.self::TableName_ForumTopicType.
                ' WHERE SiteId=:SiteId AND ForumId=:ForumId AND ForumTopicTypeId=:ForumTopicTypeId;';

        $resultTemp = $this->dbOperator->Execute($sql, $dataProperty);

        if($resultTemp > 0){
            $result = $resultTemp;
        }

        return $result;
    }

    public function ModifyTypeName($siteId, $forumId, $forumTopicTypeId, $newTypeName){
        $result = -1;

        $dataProperty = new DataProperty();
        $dataProperty->AddField('SiteId', $siteId);
        $dataProperty->AddField('ForumId', $forumId);
        $dataProperty->AddField('ForumTopicTypeName',$newTypeName);

        $sql = 'SELECT COUNT(ForumTopicTypeName)'.
            ' FROM '.self::TableName_ForumTopicType.
            ' WHERE SiteId=:SiteId AND ForumId=:ForumId AND ForumTopicTypeName=:ForumTopicTypeName;';
        $is_exist_type = $this->dbOperator->GetInt($sql, $dataProperty);
        if($is_exist_type > 0){
            $result = -2;
        }
        else{
            $dataProperty->AddField('ForumTopicTypeId', $forumTopicTypeId);
            $sql =  'UPDATE '.self::TableName_ForumTopicType.
                    ' SET ForumTopicTypeName=:ForumTopicTypeName'.
                    ' WHERE SiteId=:SiteId AND ForumId=:ForumId AND ForumTopicTypeId=:ForumTopicTypeId;';
            $resultTemp = $this->dbOperator->Execute($sql, $dataProperty);

            if($resultTemp > 0){
                $result = $resultTemp;
            }
        }
        return $result;
    }
}