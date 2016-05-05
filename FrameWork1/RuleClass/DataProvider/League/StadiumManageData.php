<?php
/**
 * Created by PhpStorm.
 * User: 525
 * Date: 2016/5/5
 * Time: 21:30
 */
class StadiumManageData extends BaseManageData
{


    /**
     * @param $stadiumName
     * @return int
     */
    public function GetIdByName($stadiumName)
    {
        $result = -1;
        if ($stadiumName != "") {
            $dataProperty = new DataProperty();
            $dataProperty->AddField("StadiumName", $stadiumName);
            $sql = "SELECT " . " StadiumId FROM " . self::TableName_Stadium . " WHERE StadiumName=:StadiumName;";
            $result = $this->dbOperator->GetInt($sql, $dataProperty);
        }
        return $result;
    }


    /**
     * 根据唯一属性获取id
     * @param $StadiumNameStr
     * @param $fieldName
     * @param $siteId
     * @return array
     */
    public function GetIdByFieldValue($StadiumNameStr, $fieldName, $siteId){
        $result=array();
        if($StadiumNameStr!=""&&$siteId>0){
            $dataProperty = new DataProperty();
            $dataProperty->AddField("SiteId", $siteId);
            $sql="SELECT ". " StadiumId,StadiumName FROM ".self::TableName_Stadium. " WHERE $fieldName IN ($StadiumNameStr) AND SiteId=:SiteId ;";
            $result = $this->dbOperator->GetArrayList($sql, $dataProperty);
        }
        return $result;
    }


}