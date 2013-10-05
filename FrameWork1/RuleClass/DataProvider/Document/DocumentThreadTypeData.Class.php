<?php

/**
 * Description of DocumentThreadTypeData
 * 咨询问答烦类的信息分类管理模块
 * $Id: DocumentThreadTypeData.php 2011-01-06 09:13:24 L junyi $
 */
class DocumentThreadTypeData extends BaseFrontData {
    const tablename = "cst_documentthreadtype";
    const tableidname = "documentthreadtypeid";

    /**
     * 新增信息
     * @return <type>
     */
    public function Create() {
        $threadtypename = Control::PostRequest("f_documentthreadtypename", "");
        $rankid = Control::PostRequest("f_rank", "");
        $parentid = Control::PostRequest("f_parentid", "");
        $fsiteid = Control::PostRequest("f_siteid", "");

        $threadtypecount = self::GetCount($threadtypename, $rankid, $parentid, $fsiteid);
        //处理相同分类名称问题
        if ($threadtypecount > 0) {
            return -10; //有同名分类名称存在
        } else if ($threadtypecount === -1) {
            return -11; //分类名称不能为空
        }

        $dataProperty = new DataProperty();
        $sql = parent::GetInsertSql(self::tablename, $dataProperty);
        $dboperator = DBOperator::getInstance();
        $result = $dboperator->LastInsertId($sql, $dataProperty);
        return $result;
    }

    /**
     * 修改信息
     * @param <type> $tableidvalue
     * @return <type>
     */
    public function Modify($tableidvalue) {
        $dataProperty = new DataProperty();
        $sql = parent::GetUpdateSql(self::tablename, self::tableidname, $tableidvalue, $dataProperty);
        $dboperator = DBOperator::getInstance();
        $result = $dboperator->Execute($sql, $dataProperty);
        return $result;
    }

    /**
     * 删除信息
     * @param <type> $docthreadtypeid
     * @return <type>
     */
    public function RemoveBin($docthreadtypeid) {
        $sql = "UPDATE " . self::tablename . " SET state=100 WHERE documentthreadtypeid=:documentthreadtypeid";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("documentthreadtypeid", $docthreadtypeid);
        $dboperator = DBOperator::getInstance();
        $result = $dboperator->Execute($sql, $dataProperty);
        return $result;
    }

    /**
     * 取得信息列表
     * @param <type> $siteid
     * @return <type>
     */
    public function GetList($siteid) {
        $sql = "SELECT * FROM " . self::tablename . " WHERE rank=1 AND siteid=:siteid order by sort desc";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("siteid", $siteid);

        $dboperator = DBOperator::getInstance();
        $result = $dboperator->ReturnArray($sql, $dataProperty);
        return $result;
    }

    /**
     * 根据$threadtypename, $rank, $parentid, $siteid取得documentthreadtypeid
     * @param <type> $threadtypename
     * @param <type> $rank
     * @param <type> $parentid
     * @param <type> $siteid
     * @return <type> 
     */
    public function GetDocumentThreadTypeID($threadtypename, $rank=0, $parentid=0, $siteid=0) {
        $dataProperty = new DataProperty();
        $sql = "SELECT documentthreadtypeid FROM " . self::tablename . "  WHERE " . self::tableidname . ">0 ";
        if (strlen($threadtypename) > 0 && $threadtypename != "undefined") {
            $sql .= " AND documentthreadtypename=:documentthreadtypename ";
            $dataProperty->AddField("documentthreadtypename", $threadtypename);
        }
        $sql .= " AND rank=:rank AND parentid=:parentid AND siteid=:siteid ";
        $dataProperty->AddField("rank", $rank);
        $dataProperty->AddField("parentid", $parentid);
        $dataProperty->AddField("siteid", $siteid);
        $sql .=" ORDER BY sort DESC";
        $dboperator = DBOperator::getInstance();
        $result = $dboperator->ReturnInt($sql, $dataProperty);
        return $result;
    }

    /**
     * 根据$threadtypename, $rank, $parentid, $siteid取得记录条数
     * @param <type> $threadtypename
     * @param <type> $rank
     * @param <type> $parentid
     * @param <type> $siteid
     * @return <type>
     */
    public function GetCount($threadtypename, $rank=0, $parentid=0, $siteid=0) {
        if (strlen(strval($threadtypename)) > 0) {
            $dataProperty = new DataProperty();
            $sql = "SELECT count(*) FROM " . self::tablename . "  WHERE " . self::tableidname . ">0 ";
            if (strlen(strval($threadtypename)) > 0 && $threadtypename != "undefined") {
                $sql .= " AND documentthreadtypename=:documentthreadtypename ";
                $dataProperty->AddField("documentthreadtypename", $threadtypename);
            }
            $sql .= " AND rank=:rank AND parentid=:parentid AND siteid=:siteid ";
            $dataProperty->AddField("rank", $rank);
            $dataProperty->AddField("parentid", $parentid);
            $dataProperty->AddField("siteid", $siteid);
            $sql .=" ORDER BY sort DESC";
            $dboperator = DBOperator::getInstance();
            $result = $dboperator->ReturnInt($sql, $dataProperty);
            return $result;
        } else {
            return -1;
        }
    }

    /**
     * 得到一行信息
     * @param <type> $str
     * @param <type> $type
     * @return <type>
     */
    public function GetRow($str, $type =0) {
        $dataProperty = new DataProperty();
        if ($type == 0) {
            $sql = "SELECT * FROM " . self::tablename . " WHERE documentthreadtypeid=:documentthreadtypeid";
            $dataProperty->AddField("documentthreadtypeid", $str);
        } else {
            $sql = "SELECT * FROM " . self::tablename . " WHERE documentthreadtypename=:documentthreadtypename";
            $dataProperty->AddField("documentthreadtypename", $str);
        }
        $dboperator = DBOperator::getInstance();
        $result = $dboperator->ReturnRow($sql, $dataProperty);
        return $result;
    }

    /**
     * 得到ThreadTypeIDList
     * @param <type> $str
     * @param <type> $type  0为按site查询,1为按documentthreadtypename查询
     * @return <type>
     */
    public function GetDocumentThreadTypeIDList($str, $type =0) {
        $dataProperty = new DataProperty();
        if ($type == 0) {
            $sql = "SELECT documentthreadtypeid,documentthreadtypename FROM " . self::tablename . " WHERE state=0 AND rank=1 AND siteid=:siteid";
            $dataProperty->AddField("siteid", $str);
        } else {
            $sql = "SELECT documentthreadtypeid,documentthreadtypename FROM " . self::tablename . " WHERE state=0 AND rank=1 AND documentthreadtypename=:documentthreadtypename";
            $dataProperty->AddField("documentthreadtypename", $str);
        }
        $dboperator = DBOperator::getInstance();
        $result = $dboperator->ReturnArray($sql, $dataProperty);
        return $result;
    }
    
    /**
     * 根据siteid及parentid到得threadtypelist
     * @param <type> $siteid
     * @param <type> $parentid
     * @return <type> 
     */
    public function GetThreadTypeList($siteid, $parentid) {
        $dataProperty = new DataProperty();
        $sql = "SELECT documentthreadtypeid,documentthreadtypename FROM " . self::tablename . " WHERE state=0 AND siteid=:siteid AND parentid=:parentid";
        $dataProperty->AddField("siteid", $siteid);
        $dataProperty->AddField("parentid", $parentid);

        $dboperator = DBOperator::getInstance();
        $result = $dboperator->ReturnArray($sql, $dataProperty);
        return $result;
    }

    /**
     * 取得分类信息分页列表
     * @param <type> $pagebegin
     * @param <type> $pagesize
     * @param <type> $allcount
     * @param <type> $searchkey
     * @param <type> $siteid
     * @return <type>
     */
    public function GetListPager($pagebegin, $pagesize, &$allcount, $searchkey, $siteid = 0) {
        $searchsql = "";
        $dataProperty = new DataProperty();
        if (strlen($searchkey) > 0 && $searchkey != "undefined") {
            $searchsql .= " AND (documentthreadtypename like :searchkey1 )";
            $dataProperty->AddField("searchkey1", "%" . $searchkey . "%");
        }
        if ($siteid > 0) {
            $searchsql .= " AND siteid=:siteid ";
            $dataProperty->AddField("siteid", $siteid);
        }
        $sql = "SELECT
            siteid,documentthreadtypename,state,documentthreadtypeid
            FROM
            " . self::tablename . " 
            WHERE documentthreadtypeid>0 AND rank=1 " . $searchsql . " order by documentthreadtypeid desc limit " . $pagebegin . "," . $pagesize . "";
        $dboperator = DBOperator::getInstance();
        $result = $dboperator->ReturnArray($sql, $dataProperty);
        //统计总数
        $sql = "SELECT count(*) FROM " . self::tablename . " WHERE documentthreadtypeid>0 " . $searchsql;
        $allcount = $dboperator->ReturnInt($sql, $dataProperty);
        return $result;
    }

}

?>
