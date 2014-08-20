<?php
/**
 * 后台管理 产品参数 数据类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_Product
 * @author hy
 */
class ProductParamManageData extends BaseManageData {

    public function Create($titlepicpath = ""){
        $dataProperty = new DataProperty();
        $sql = parent::GetInsertSql(self::tablename,$dataProperty,"titlepic",$titlepicpath);
//        echo $sql;
//        die();
        $dboperator = DBOperator::getInstance();
        $result = $dboperator->LastInsertId($sql, $dataProperty);
        return $result;
    }

    public function Modify($tableidvalue,$titlepicpath = ""){
        $dataProperty = new DataProperty();
        $sql = parent::GetUpdateSql(self::tablename,self::tableidname,$tableidvalue,$dataProperty,"titlepic",$titlepicpath);
        $dboperator = DBOperator::getInstance();
        $result = $dboperator->Execute($sql, $dataProperty);
        return $result;
    }

    public function Move($documentchannelid,$ids){
        $sql = "UPDATE ".self::tablename." SET `documentchannelid`=:documentchannelid where productid in (".$ids.")";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("documentchannelid", $documentchannelid);
        $dboperator = DBOperator::getInstance();
        $result = $dboperator->Execute($sql, $dataProperty);
        return $result;
    }

    public function GetDelete($productid){
        $sql = "delete from ".self::tablename." where productid=:productid";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("productid", $productid);
        $dboperator = DBOperator::getInstance();
        $result = $dboperator->ReturnRow($sql, $dataProperty);
        return $result;
    }

    public function UpdateState($productid,$state){
        $sql = "update ".self::tablename." set state=:state where productid=:productid";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("productid", $productid);
        $dataProperty->AddField("state", $state);
        $dboperator = DBOperator::getInstance();
        $result = $dboperator->Execute($sql, $dataProperty);
        return $result;
    }

    public function GetList($sqlwhere,$dataProperty,$order,$topcount=-1){
        $limitcount = "";
        if ($topcount != -1)
            $limitcount = " limit " . $topcount;
        $sql = "select t.*
            from cst_productparam t
            left outer join cst_productparamtype t1
            on t.productparamtypeid=t1.productparamtypeid
	    where " . $sqlwhere . " " . $order . $limitcount;
        $dboperator = DBOperator::getInstance();
        $result = $dboperator->ReturnArray($sql, $dataProperty);
        return $result;
    }

    //取得对应参数数据
    public function GetDetailList($productid,$rankid=-1,$parentid=-1,$topcount){
        $dataProperty = new DataProperty();
        $limitcount = "";
        $addconditon = "";
        if ($rankid != -1)
        {
            $addconditon = " and t1.rankid =:rankid";
            $dataProperty->AddField("rankid", $rankid);
        }
        if ($parentid != -1) {
            $addconditon = $addconditon . " and t1.parentid =:parentid";
            $dataProperty->AddField("parentid", $parentid);
        }
        if ($topcount != -1)
            $limitcount = " limit " . $topcount;
        $sql = "select t.*,t1.productparamtypeid,t1.paramtypename
            from cst_productparam t
            left outer join cst_productparamtype t1
            on t.productparamtypeid=t1.productparamtypeid
	    where t.productid=:productid"  . $addconditon . $limitcount;
        
        $dataProperty->AddField("productid", $productid);
        $dboperator = DBOperator::getInstance();
        $result = $dboperator->ReturnArray($sql, $dataProperty);
        return $result;
    }

    public function GetParamCount($productparamtypeid, $productparamtypeoptionid){
        $sql = "select count(*) from ".self::tablename." where productparamtypeid=:productparamtypeid and shortstringvalue=:shortstringvalue";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("productparamtypeid", $productparamtypeid);
        $dataProperty->AddField("shortstringvalue", $productparamtypeoptionid);
        $dboperator = DBOperator::getInstance();
        $result = $dboperator->ReturnInt($sql, $dataProperty);
        return $result;
    }

    public function GetParamCountByID($productparamtypeid){
        $sql = "select count(*) from ".self::tablename." where productparamtypeid=:productparamtypeid";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("productparamtypeid", $productparamtypeid);
        $dboperator = DBOperator::getInstance();
        $result = $dboperator->ReturnInt($sql, $dataProperty);
        return $result;
    }
    public function Merge($sourceid,$targetid,$paramtypeid){
        $sql = "update ".self::tablename." set shortstringvalue=:targetid where shortstringvalue=:sourceid and productparamtypeid=:paramtypeid";
        $dataProperty = new DataProperty();        
        $dataProperty->AddField("sourceid", $sourceid);
        $dataProperty->AddField("targetid", $targetid);
        $dataProperty->AddField("paramtypeid", $paramtypeid);
        $dboperator = DBOperator::getInstance();
        //echo $sql;echo $id;echo $parentid;die();
        $result = $dboperator->Execute($sql, $dataProperty);
        return $result;
    }


}
?>