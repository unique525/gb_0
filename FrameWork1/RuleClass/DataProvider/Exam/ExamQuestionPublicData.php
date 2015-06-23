<?php
/**
 * Created by PhpStorm.
 * User: xzz
 * Date: 15/6/18
 * Time: 下午2:48
 */
class ExamQuestionPublicData extends BasePublicData{

    public function GetQuestionList($mustSelect,$count)
    {
        $result = null;
        if($count > 0){

            $dataProperty = new DataProperty();

            $sql = "SELECT * FROM " . self::TableName_ExamQuestion . " WHERE MustSelect=:MustSelect ORDER BY rand() LIMIT " . $count . "";
            $dataProperty->AddField("MustSelect", $mustSelect);
            $result = $this->dbOperator->GetArrayList($sql, $dataProperty);
        }
        return $result;
    }
}