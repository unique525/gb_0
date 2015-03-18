<?php
/**
 * 后台管理 资讯 数据类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_Document
 * @author 525
 */


require "/FrameWork1/include_all.php";

echo 123;

$get=new Get();
$get->GetOriginalList(0);



class Get extends BaseManageData
{
/**
 *
 * 从other库读取
 * @param int $lastId
 * @return array
 */
public function GetOriginalList($lastId=0){
    $result=null;
    $sql="SELECT * FROM cst_user LIMIT 10";
    $dataProperty = new DataProperty();
    $result=$this->dbOperator->GetArrayList($sql, $dataProperty);
    print_r($result);
    return $result;
}
}
?>