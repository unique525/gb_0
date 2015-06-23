<?php

/**
 * 后台管理 数据业务层 基类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider
 * @author zhangchi
 */
class BaseManageData extends BaseData {

    /**
     * 根据POST和附加字段生成INSERT SQL
     * @param array $httpPostData $_POST数组
     * @param string $tableName 表名
     * @param DataProperty $dataProperty 数据对象
     * @param string $addFieldName 附加字段名
     * @param string $addFieldValue 附加字段值
     * @param string $preNumber 前置字段数字
     * @param array $addFieldNames 附加字段数组
     * @param array $addFieldValues 附加字段值数组
     * @return string SQL字符串
     */
    public function GetInsertSql($httpPostData, $tableName, DataProperty &$dataProperty, $addFieldName = "", $addFieldValue = "", $preNumber = "", $addFieldNames = null, $addFieldValues = null) {
        if (!empty($httpPostData)) {
            $fieldNames = "";
            $fieldValues = "";
            foreach ($httpPostData as $key => $value) {
                if (strpos($key, "f" . $preNumber . "_") === 0) { //text TextArea 类字段
                    if ($preNumber === "") {
                        $keyName = substr($key, 2);
                    } else {
                        $keyName = substr($key, 3);
                    }
                    //修复CreateDate
                    if(strtolower($keyName) == 'createdate'){
                        if(strpos($value,'0000-00-00') >= 0 || empty($value)){
                            $value = date("Y-m-d H:i:s", time());
                        }
                    }
                    $fieldNames = $fieldNames . ",`" . $keyName . "`";
                    $fieldValues = $fieldValues . ",:" . $keyName;
                    $dataProperty->AddField($keyName, stripslashes($value));
                } else if (strpos($key, "c" . $preNumber . "_") === 0) { //radio checkbox类字段
                    $keyName = substr($key, 2);
                    $fieldNames = $fieldNames . ",`" . $keyName . "`";
                    $fieldValues = $fieldValues . ",:" . $keyName;
                    if ($value === "on") {
                        $dataProperty->AddField($keyName, "1");
                    } else {
                        $dataProperty->AddField($keyName, "0");
                    }
                }
            }
            //附加字段插入SQL
            if (strlen($addFieldName) > 0 && strlen($addFieldValue) > 0) {
                $fieldNames = $fieldNames . ",`" . $addFieldName . "`";
                $fieldValues = $fieldValues . ",:" . $addFieldName;
                $dataProperty->AddField($addFieldName, stripslashes($addFieldValue));
            }

            //附加字段数组插入SQL
            if ($addFieldNames != null && $addFieldValues != null) {
                if (count($addFieldNames) > 0 && count($addFieldValues) > 0 && count($addFieldNames) === count($addFieldValues)) {
                    for ($fi = 0; $fi < count($addFieldNames); $fi++) {
                        $fieldNames = $fieldNames . ",`" . $addFieldNames[$fi] ."`";
                        $fieldValues = $fieldValues . ",:" . $addFieldNames[$fi];
                        $dataProperty->AddField($addFieldNames[$fi], stripslashes($addFieldValues[$fi]));
                    }
                }
            }

            if (strpos($fieldNames, ",") === 0) {
                $fieldNames = substr($fieldNames, 1);
            }
            if (strpos($fieldValues, ",") === 0) {
                $fieldValues = substr($fieldValues, 1);
            }
            $sql = "INSERT INTO " . $tableName . " (" . $fieldNames . ") VALUES (" . $fieldValues . ");";
            return $sql;
        } else {
            return null;
        }
    }

    /**
     * 根据GET和附加字段生成INSERT SQL
     * @param string $tableName 表名
     * @param DataProperty $dataProperty 数据对象
     * @param string $addFieldName 附加字段名
     * @param string $addFieldValue 附加字段值
     * @param string $preNumber 前置字段数字
     * @param array $addFieldNames 附加字段数组
     * @param array $addFieldValues 附加字段值数组
     * @return string SQL字符串
     */
    public function GetInsertSqlByGet($tableName, DataProperty &$dataProperty, $addFieldName = "", $addFieldValue = "", $preNumber = "", $addFieldNames = null, $addFieldValues = null) {
        if (!empty($_GET)) {
            $fieldNames = "";
            $fieldValues = "";
            foreach ($$_GET as $key => $value) {
                if (strpos($key, "f" . $preNumber . "_") === 0) { //text textarea 类字段
                    if ($preNumber === "") {
                        $keyName = substr($key, 2);
                    } else {
                        $keyName = substr($key, 3);
                    }
                    //修复createdate
                    if(strtolower($keyName) == 'createdate'){
                        if(strpos($value,'0000-00-00') >= 0 || empty($value)){
                            $value = date("Y-m-d H:i:s", time());
                        }
                    }


                    $fieldNames = $fieldNames . ",`" . $keyName . "`";
                    $fieldValues = $fieldValues . ",:" . $keyName;
                    $dataProperty->AddField($keyName, stripslashes($value));
                } else if (strpos($key, "c" . $preNumber . "_") === 0) { //radio checkbox类字段
                    $keyName = substr($key, 2);
                    $fieldNames = $fieldNames . ",`" . $keyName . "`";
                    $fieldValues = $fieldValues . ",:" . $keyName;
                    if ($value === "on") {
                        $dataProperty->AddField($keyName, "1");
                    } else {
                        $dataProperty->AddField($keyName, "0");
                    }
                }
            }
            //附加字段插入SQL
            if (strlen($addFieldName) > 0 && strlen($addFieldValue) > 0) {
                $fieldNames = $fieldNames . "," . $addFieldName;
                $fieldValues = $fieldValues . ",:" . $addFieldName;
                $dataProperty->AddField($addFieldName, stripslashes($addFieldValue));
            }

            //附加字段数组插入SQL
            if ($addFieldNames != null && $addFieldValues != null) {
                if (count($addFieldNames) > 0 && count($addFieldValues) > 0 && count($addFieldNames) === count($addFieldValues)) {
                    for ($fi = 0; $fi < count($addFieldNames); $fi++) {
                        $fieldNames = $fieldNames . "," . $addFieldNames[$fi];
                        $fieldValues = $fieldValues . ",:" . $addFieldNames[$fi];
                        $dataProperty->AddField($addFieldNames[$fi], stripslashes($addFieldValues[$fi]));
                    }
                }
            }

            if (strpos($fieldNames, ",") === 0) {
                $fieldNames = substr($fieldNames, 1);
            }
            if (strpos($fieldValues, ",") === 0) {
                $fieldValues = substr($fieldValues, 1);
            }
            $sql = "insert into " . $tableName . " (" . $fieldNames . ") values (" . $fieldValues . ")";
            return $sql;
        } else {
            return null;
        }
    }


    /**
     * 根据POST和附加字段生成UPDATE SQL
     * @param array $httpPostData $_POST数组
     * @param string $tableName 表名
     * @param string $tableIdName 关键字段名
     * @param string $tableIdValue 关键字段值
     * @param DataProperty $dataProperty 数据对象
     * @param string $addFieldName 附加字段名
     * @param string $addFieldValue 附加字段值
     * @param string $preNumber 前置字段数字
     * @param array $addFieldNames 附加字段数组
     * @param array $addFieldValues 附加字段值数组
     * @return string SQL字符串
     */
    public function GetUpdateSql($httpPostData, $tableName, $tableIdName, $tableIdValue, DataProperty &$dataProperty, $addFieldName = "", $addFieldValue = "", $preNumber = "", $addFieldNames = null, $addFieldValues = null) {
        if (!empty($httpPostData)) {
            $fieldNames = "";
            foreach ($httpPostData as $key => $value) {
                if (strpos($key, "f" . $preNumber . "_") === 0) {
                    $keyName = substr($key, 2);
                    $fieldNames = $fieldNames . ",`" . $keyName . "`=:" . $keyName;
                    $dataProperty->AddField($keyName, stripslashes($value));
                } else if (strpos($key, "c" . $preNumber . "_") === 0) { //radio checkbox类字段
                    $keyName = substr($key, 2);
                    $fieldNames = $fieldNames . ",`" . $keyName . "`=:" . $keyName;
                    if ($value == "on") {
                        $dataProperty->AddField($keyName, "1");
                    } else {
                        $dataProperty->AddField($keyName, "0");
                    }
                }
            }

            //附加字段插入SQL
            if (strlen($addFieldName) > 0 && strlen($addFieldValue) > 0) {
                $fieldNames = $fieldNames . ",`" . $addFieldName . "`=:" . $addFieldName;
                $dataProperty->AddField($addFieldName, stripslashes($addFieldValue));
            }

            //附加字段数组插入SQL
            if ($addFieldNames != null && $addFieldValues != null) {
                if (count($addFieldNames) > 0 && count($addFieldValues) > 0 && count($addFieldNames) === count($addFieldValues)) {
                    for ($fi = 0; $fi < count($addFieldNames); $fi++) {
                        $fieldNames = $fieldNames . ",`" . $addFieldNames[$fi] . "`=:" . $addFieldNames[$fi];
                        $dataProperty->AddField($addFieldNames[$fi], stripslashes($addFieldValues[$fi]));
                    }
                }
            }

            if (strpos($fieldNames, ",") === 0) {
                $fieldNames = substr($fieldNames, 1);
            }
            $sql = "UPDATE $tableName SET $fieldNames WHERE $tableIdName = $tableIdValue;";
            return $sql;
        } else {
            return null;
        }
    }


    /**
     * 取得表的字段名称数组
     * @param string $tableName 表名
     * @return array 表的字段名称数组
     */
    public function GetFields($tableName = "")
    {
        if(strlen($tableName)>0){
            $tableName = Format::FormatSql($tableName);
            $sql = 'SHOW FIELDS FROM '.$tableName;
            $result = $this->dbOperator->GetArrayList($sql, null);
        }else{
            $result = "";
        }

        return $result;
    }
}

?>