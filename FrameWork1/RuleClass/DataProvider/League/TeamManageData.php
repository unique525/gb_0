<?php

/**
 * 后台管理 赛事 数据类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_League
 * @author 525
 */
class TeamManageData extends BaseManageData
{



    /**
     * 新增
     * @param array $httpPostData $_post数组
     * @param int $siteId
     * @return int 新增活动id
     */
    public function Create($httpPostData, $siteId) {
        $result=-1;
        $dataProperty = new DataProperty();
        $addFieldName = "SiteId";
        $addFieldValue = $siteId;
        $preNumber = "";
        $addFieldNames = array();
        $addFieldValues = array();
        if (!empty($httpPostData)) {
            $sql = parent::GetInsertSql($httpPostData,self::TableName_Team, $dataProperty, $addFieldName, $addFieldValue, $preNumber, $addFieldNames, $addFieldValues);
            $result = $this->dbOperator->LastInsertId($sql, $dataProperty);
        }
        return $result;
    }

    /**
     * 修改
     * @param array $httpPostData $_post数组
     * @param string $teamId 分类信息id
     * @return int 执行结果
     */
    public function Modify($httpPostData, $teamId) {

        $result = -1;
        $dataProperty = new DataProperty();
        $addFieldName = "";
        $addFieldValue = "";
        $preNumber = "";
        $addFieldNames = array();
        $addFieldValues = array();

        if (!empty($httpPostData)) {
            $sql = parent::GetUpdateSql($httpPostData, self::TableName_Team, self::TableId_Team, $teamId, $dataProperty, $addFieldName, $addFieldValue, $preNumber, $addFieldNames, $addFieldValues);
            $result = $this->dbOperator->Execute($sql, $dataProperty);
        }
        return $result;
    }


    /**
     * 修改match of league
     * @param array $teamArray $_post数组
     * @param string $teamId id
     * @param string $leagueId id
     * @return int 执行结果
     */
    public function ModifyMatchOfLeague($teamArray, $teamId, $leagueId) {

        $result = -1;
        if($teamId>0&&$leagueId>0){
            $dataProperty = new DataProperty();
            $dataProperty->AddField("Score",$teamArray["f_Score"]);
            $dataProperty->AddField("Goal",$teamArray["f_Goal"]);
            $dataProperty->AddField("LoseGoal",$teamArray["f_LoseGoal"]);
            $dataProperty->AddField("Match",$teamArray["f_Match"]);
            $dataProperty->AddField("Win",$teamArray["f_Win"]);
            $dataProperty->AddField("Lose",$teamArray["f_Lose"]);
            $dataProperty->AddField("Tie",$teamArray["f_Tie"]);
            $dataProperty->AddField("TeamId",$teamId);
            $dataProperty->AddField("LeagueId",$leagueId);
            $sql="UPDATE " . self::TableName_TeamOfLeague .
                " SET Score=:Score,Goal=:Goal,LoseGoal=:LoseGoal,`Match`=:Match,Win=:Win,Lose=:Lose,Tie=:Tie
                 WHERE TeamId=:TeamId AND LeagueId=:LeagueId ;";
            $result = $this->dbOperator->Execute($sql, $dataProperty);
        }


        return $result;
    }



    /**
     *
     * 批量导入
     * @param $importArray
     * @param $siteId
     * @param $createDate
     * @param $resultIdArray
     * @return int
     */
    public function Import($importArray,$siteId,$createDate,&$resultIdArray){
        $result=-1;
        if(!empty($importArray)&&$siteId>0){
            $arrSql=array();
            $arrDataProperty=array();
            foreach($importArray as $key=>$value){
                $dataProperty = new DataProperty();
                $dataProperty->AddField("TeamName",$value["TeamName"]);
                $dataProperty->AddField("TeamShortName",$value["TeamShortName"]);
                $dataProperty->AddField("SiteId",$siteId);
                $arrDataProperty[]=$dataProperty;
                $arrSql[]="INSERT "." INTO ".parent::TableName_Team."
                (TeamName,TeamShortName,SiteId,CreateDate) VALUES(:TeamName,:TeamShortName,:SiteId,'$createDate'); ";
            }
            $resultIdArray=$importArray;
            $result=$this->dbOperator->InsertBatch($arrSql,$arrDataProperty,$resultIdArray);
        }
        return $result;
    }


    /**
     *
     * 批量加入赛事
     * @param $importArray
     * @param $leagueId
     * @return int
     */
    public function BatchJoinLeague($importArray,$leagueId){
        $result=-1;
        if(!empty($importArray)&&$leagueId>0){
            $arrSql=array();
            $arrDataProperty=array();
            foreach($importArray as $key=>$value){
                $dataProperty = new DataProperty();
                $dataProperty->AddField("TeamId",$value["LastInsertId"]);
                $dataProperty->AddField("LeagueId",$leagueId);
                $dataProperty->AddField("GroupName",strtolower($value["GroupName"]));
                $arrDataProperty[]=$dataProperty;
                $arrSql[]="INSERT "." INTO ".parent::TableName_TeamOfLeague."
                (TeamId,LeagueId,GroupName) VALUES(:TeamId,:LeagueId,:GroupName); ";
            }
            $result=$this->dbOperator->InsertBatch($arrSql,$arrDataProperty,$resultIdArray);
        }
        return $result;
    }

    /**
     * 获取队伍分页列表
     * @param int $siteId id
     * @param int $pageBegin 起始页码
     * @param int $pageSize 每页大小
     * @param int $allCount 总大小
     * @param string $searchKey 搜索关键字
     * @return array 数据集
     */
    public function GetListPager($siteId, $pageBegin, $pageSize, &$allCount, $searchKey="") {
        $result=array();
        if($siteId>0){
            $searchSql = "";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("SiteId", $siteId);


            if (strlen($searchKey) > 0 && $searchKey != "undefined") {
                $searchSql = " AND (TeamName like :SearchKey)";
                $dataProperty->AddField("SearchKey", "%" . $searchKey . "%");
            }

            $sql = "
                SELECT "." *
                FROM
                " . self::TableName_Team . "
                WHERE SiteId=:SiteId " . $searchSql . " LIMIT " . $pageBegin . "," . $pageSize . " ;";


            $result = $this->dbOperator->GetArrayList($sql, $dataProperty);
            $sqlCount = "SELECT "." count(*) FROM " . self::TableName_Team . " WHERE SiteId=:SiteId " . $searchSql . " ;";
            $allCount = $this->dbOperator->GetInt($sqlCount, $dataProperty);
        }
        return $result;
    }


    /**
     * 获取一个赛事的队伍分页列表
     * @param int $leagueId id
     * @return array 数据集
     */
    public function GetListOfLeague($leagueId) {
        $result=array();
        if($leagueId>0){
            $dataProperty = new DataProperty();
            $dataProperty->AddField("LeagueId", $leagueId);
            $sql = "
                SELECT "." tol.*,t.TeamName,t.TeamShortName
                FROM ".self::TableName_TeamOfLeague." tol
                LEFT OUTER JOIN " . self::TableName_Team . " t ON tol.TeamId=t.TeamId
                WHERE tol.LeagueId=:LeagueId ORDER BY GroupName,Score DESC,(Goal-LoseGoal) DESC;";
            $result = $this->dbOperator->GetArrayList($sql, $dataProperty);
        }
        return $result;
    }

    /**
     * 检查重复项
     * @param $checkStr
     * @param $fieldName
     * @return array
     */
    public function GetRepeat($checkStr,$fieldName){
        $result=array();
        if($checkStr!=""&&$fieldName!=""){
            $dataProperty = new DataProperty();
            $sql="SELECT ". "$fieldName FROM ".self::TableName_Team. " WHERE $fieldName IN ($checkStr) ;";
            $result = $this->dbOperator->GetArrayList($sql, $dataProperty);
        }
        return $result;
    }

    /**
     * 根据唯一属性获取id
     * @param $TeamNameStr
     * @param $fieldName
     * @param $siteId
     * @return array
     */
    public function GetIdByFieldValue($TeamNameStr,$fieldName,$siteId){
        $result=array();
        if($TeamNameStr!=""&&$siteId>0){
            $dataProperty = new DataProperty();
            $dataProperty->AddField("SiteId", $siteId);
            $sql="SELECT ". " TeamId,TeamName FROM ".self::TableName_Team. " WHERE $fieldName IN ($TeamNameStr) AND SiteId=:SiteId ;";
            $result = $this->dbOperator->GetArrayList($sql, $dataProperty);
        }
        return $result;
    }


    /**
     * @param $teamName
     * @return int
     */
    public function GetIdByName($teamName){
        $result=-1;
        if($teamName!=""){
            $dataProperty = new DataProperty();
            $dataProperty->AddField("TeamName", $teamName);
            $sql="SELECT ". " TeamId FROM ".self::TableName_Team. " WHERE TeamName=:TeamName;";
            $result = $this->dbOperator->GetInt($sql, $dataProperty);
        }
        return $result;
    }

    /**
     * @param $teamId
     * @param $leagueId
     * @return int
     */
    public function CheckInLeague($teamId,$leagueId){
        $result=-1;
        if($teamId>0&&$leagueId>0){
            $dataProperty = new DataProperty();
            $dataProperty->AddField("LeagueId", $leagueId);
            $dataProperty->AddField("TeamId", $teamId);
            $sql="SELECT ". " COUNT(*) FROM " .self::TableName_TeamOfLeague . "
             WHERE TeamId=:TeamId AND LeagueId=:LeagueId ;";
            $result = $this->dbOperator->Getint($sql, $dataProperty);
        }
        return $result;
    }

    /**
     * @param $teamId
     * @param $leagueId
     * @param $groupName
     * @return int
     */
    public function JoinLeague($teamId,$leagueId,$groupName){

        $result=-1;
        if($teamId>0&&$leagueId>0){
            $dataProperty = new DataProperty();
            $dataProperty->AddField("LeagueId", $leagueId);
            $dataProperty->AddField("TeamId", $teamId);
            $dataProperty->AddField("GroupName", $groupName);
            $sql="INSERT ". " INTO " .self::TableName_TeamOfLeague . "
             (TeamId,LeagueId,GroupName) VALUES (:TeamId,:LeagueId,:GroupName) ;";
            $debug=new DebugLogManageData();
            $debug->Create($sql);
            $debug->Create($leagueId);
            $debug->Create($teamId);
            $result = $this->dbOperator->LastInsertId($sql, $dataProperty);
        }
        return $result;
    }

    /**
     * 取得站点id
     * @param int $teamId id
     * @param bool $withCache 是否从缓冲中取
     * @return int 站点id
     */
    public function GetSiteId($teamId, $withCache)
    {
        $result = -1;
        if ($teamId > 0) {
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'league_data';
            $cacheFile = 'team_get_site_id.cache_' . $teamId . '';
            $sql = "SELECT "." SiteId FROM " . self::TableName_Team . " WHERE TeamId=:TeamId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("TeamId", $teamId);
            $result = $this->GetInfoOfIntValue($sql, $dataProperty, $withCache, $cacheDir, $cacheFile);
        }
        return $result;
    }


    /**
     * 通过ID获取一条记录
     * @param int $teamId 活动id
     * @return array 活动数据
     */
    public function GetOne($teamId) {
        $result=-1;
        if($teamId>0){
            $sql = "SELECT "." * FROM " . self::TableName_Team . " WHERE TeamId = :TeamId ;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("TeamId", $teamId);
            $result = $this->dbOperator->GetArray($sql, $dataProperty);
        }
        return $result;
    }


    /**
     * 取得字段数据集
     * @param string $tableName 表名
     * @return array 字段数据集
     */
    public function GetFields($tableName = self::TableName_Team){
        return parent::GetFields(self::TableName_Team);
    }



}