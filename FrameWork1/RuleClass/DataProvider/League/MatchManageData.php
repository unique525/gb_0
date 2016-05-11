<?php

/**
 * 后台管理 赛事 数据类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_League
 * @author 525
 */
class MatchManageData extends BaseManageData
{



    /**
     * 新增
     * @param array $httpPostData $_post数组
     * @param int $leagueId
     * @return int 新增活动id
     */
    public function Create($httpPostData,$leagueId) {
        $result=-1;
        $dataProperty = new DataProperty();
        $addFieldName = "LeagueId";
        $addFieldValue = $leagueId;
        $preNumber = "";
        $addFieldNames = array();
        $addFieldValues = array();
        if (!empty($httpPostData)) {
            $sql = parent::GetInsertSql($httpPostData,self::TableName_Match, $dataProperty, $addFieldName, $addFieldValue, $preNumber, $addFieldNames, $addFieldValues);
            $result = $this->dbOperator->LastInsertId($sql, $dataProperty);
        }
        return $result;
    }

    /**
     * 修改
     * @param array $httpPostData $_post数组
     * @param string $matchId 分类信息id
     * @return int 执行结果
     */
    public function Modify($httpPostData, $matchId) {

        $result = -1;
        $dataProperty = new DataProperty();
        $addFieldName = "";
        $addFieldValue = "";
        $preNumber = "";
        $addFieldNames = array();
        $addFieldValues = array();

        if (!empty($httpPostData)) {
            $sql = parent::GetUpdateSql($httpPostData, self::TableName_Match, self::TableId_Match, $matchId, $dataProperty, $addFieldName, $addFieldValue, $preNumber, $addFieldNames, $addFieldValues);
            $result = $this->dbOperator->Execute($sql, $dataProperty);
        }
        return $result;
    }



    /**
     * 批量导入
     * @param $importArray
     * @param $leagueId
     * @param $createDate
     * @return int
     */
    public function Import($importArray,$leagueId,$createDate){
        $result=-1;
        if(!empty($importArray)&&$leagueId>0){
            $arrSql=array();
            $arrDataProperty=array();
            foreach($importArray as $importItem){
                $dataProperty = new DataProperty();
                $fieldNames="";
                $fieldValues="";
                foreach($importItem as $key=>$value){
                    if (strpos($key, "f" . "_") === 0) { //text TextArea 类字段
                        $keyName = substr($key, 2);
                        $dataProperty->AddField($keyName,$value);
                        $fieldNames.=",".$keyName;
                        $fieldValues.=",:".$keyName;
                    }
                    if (strpos($key, "t" . "_") === 0) { //时间 TextArea 类字段
                        $keyName = substr($key, 2);
                        $fieldNames.=",".$keyName;
                        $fieldValues.=",'".$value."'";
                    }
                }
                if (strpos($fieldNames, ",") === 0) {
                    $fieldNames = substr($fieldNames, 1);
                }
                if (strpos($fieldValues, ",") === 0) {
                    $fieldValues = substr($fieldValues, 1);
                }

                $dataProperty->AddField("LeagueId",$leagueId);
                $arrDataProperty[]=$dataProperty;
                $arrSql[]="INSERT "." INTO ".parent::TableName_Match."
                ($fieldNames,CreateDate,LeagueId) VALUES ($fieldValues,'$createDate',:LeagueId); ";
            }
            $result=$this->dbOperator->ExecuteBatch($arrSql,$arrDataProperty);
        }
        return $result;
    }


    /**
     * 获取比赛分页列表
     * @param int $leagueId 频道id
     * @param int $pageBegin 起始页码
     * @param int $pageSize 每页大小
     * @param int $allCount 总大小
     * @param string $searchKey 搜索关键字
     * @param int $searchType 搜索范围
     * @return array 数据集
     */
    public function GetListPager($leagueId, $pageBegin, $pageSize, &$allCount, $searchKey="", $searchType=100) {
        $result=-1;
        if($leagueId>0){
            $searchSql = "";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("LeagueId", $leagueId);


            if (strlen($searchKey) > 0 && $searchKey != "undefined") {
                if ($searchType == 0) { //标题
                    $searchSql = " AND (MatchName like :SearchKey)";
                    $dataProperty->AddField("SearchKey", "%" . $searchKey . "%");
                } else if ($searchType == 1) { //球队
                    $searchSql = " AND (HomeTeamId = :SearchKey1
                                    OR GuestTeamId = :SearchKey2)";
                    $dataProperty->AddField("SearchKey1", "%" . $searchKey . "%");
                    $dataProperty->AddField("SearchKey2", "%" . $searchKey . "%");
                } else { //模糊
                    $searchSql = "AND (MatchName like :SearchKey1)
                                    OR HomeTeamId = :SearchKey2
                                    OR GuestTeamId = :SearchKey3)";
                    $dataProperty->AddField("SearchKey1", "%" . $searchKey . "%");
                    $dataProperty->AddField("SearchKey2", "%" . $searchKey . "%");
                    $dataProperty->AddField("SearchKey3", "%" . $searchKey . "%");
                }
            }

            $sql = "
                SELECT "." m.*,s.StadiumName,
                ht.TeamName as HomeTeamName,gt.TeamName as GuestTeamName,
                ht.TeamShortName as HomeTeamShortName,gt.TeamShortName as GuestTeamShortName
                FROM
                " . self::TableName_Match . " m
                LEFT OUTER JOIN " . self::TableName_Team . " ht ON ht.TeamId=m.HomeTeamId
                LEFT OUTER JOIN " . self::TableName_Team . " gt ON gt.TeamId=m.GuestTeamId
                LEFT OUTER JOIN ".self::TableName_Stadium ." s ON s.StadiumId=m.StadiumId
                WHERE LeagueId=:LeagueId " . $searchSql . " LIMIT " . $pageBegin . "," . $pageSize . " ;";


            $result = $this->dbOperator->GetArrayList($sql, $dataProperty);
            $sqlCount = "SELECT "." count(*) FROM " . self::TableName_Match . " WHERE LeagueId=:LeagueId " . $searchSql . " ;";
            $allCount = $this->dbOperator->GetInt($sqlCount, $dataProperty);
        }
        return $result;
    }




    /**
     * 获取比赛分页列表
     * @param int $leagueId id
     * @return array 数据集
     */
    public function GetListFinishedOfLeague($leagueId) {
        $result=-1;
        if($leagueId>0){
            $dataProperty = new DataProperty();
            $dataProperty->AddField("LeagueId", $leagueId);

            $sql = "
                SELECT "." * FROM
                " . self::TableName_Match . "
                WHERE LeagueId=:LeagueId AND State=2 AND Result>0 ;";


            $result = $this->dbOperator->GetArrayList($sql, $dataProperty);
        }
        return $result;
    }




    /**
     * @param $matchId
     * @param bool $withCache
     * @return int
     */
    public function GetLeagueId($matchId,$withCache=false){

            $result = -1;
            if ($matchId > 0) {
                $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'league_data';
                $cacheFile = 'match_get_league_id.cache_' . $matchId . '';
                $sql = "SELECT "." LeagueId FROM " . self::TableName_Match . " WHERE MatchId=:MatchId;";
                $dataProperty = new DataProperty();
                $dataProperty->AddField("MatchId", $matchId);
                $result = $this->GetInfoOfIntValue($sql, $dataProperty, $withCache, $cacheDir, $cacheFile);
            }
            return $result;
    }


    /**
     * @param $matchId
     * @return array
     */
    public function GetOne($matchId){

        $result = array();
        if ($matchId > 0) {
            $sql = "SELECT "." m.*,s.StadiumName,
            ht.TeamName as HomeTeamName,gt.TeamName as GuestTeamName,
            ht.TeamShortName as HomeTeamShortName,gt.TeamShortName as GuestTeamShortName
             FROM " . self::TableName_Match . " m
             LEFT OUTER JOIN ".self::TableName_Team ." ht ON ht.TeamId=m.HomeTeamId
             LEFT OUTER JOIN ".self::TableName_Team ." gt ON gt.TeamId=m.GuestTeamId
             LEFT OUTER JOIN ".self::TableName_Stadium ." s ON s.StadiumId=m.StadiumId
             WHERE m.MatchId=:MatchId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("MatchId", $matchId);
            $result=$this->dbOperator->GetArray($sql,$dataProperty);
        }
        return $result;
    }


    /**
     * 取得字段数据集
     * @param string $tableName 表名
     * @return array 字段数据集
     */
    public function GetFields($tableName = self::TableName_Match){
        return parent::GetFields(self::TableName_Match);
    }



    /**
     * submit单场结果
     * @param $matchId
     * @param $matchResult
     * @param $homeTeamGoal
     * @param $guestTeamGoal
     * @param $homePenalty
     * @param $guestPenalty
     * @return int
     */
    public function UpdateOneMatchResult($matchId,$matchResult,$homeTeamGoal,$guestTeamGoal,$homePenalty,$guestPenalty)
    {
        $result = -1;
        if ($matchId > 0) {
            $sql = 'UPDATE ' . self::TableName_Match . '
             SET Result=:Result,HomeTeamGoal=:HomeTeamGoal,GuestTeamGoal=:GuestTeamGoal,HomeTeamPenalty=:HomeTeamPenalty,GuestTeamPenalty=:GuestTeamPenalty
             WHERE MatchId=:MatchId;';
            $dataProperty = new DataProperty();
            $dataProperty->AddField("MatchId", $matchId);
            $dataProperty->AddField("Result", $matchResult);
            $dataProperty->AddField("HomeTeamGoal", $homeTeamGoal);
            $dataProperty->AddField("GuestTeamGoal", $guestTeamGoal);
            $dataProperty->AddField("HomeTeamPenalty", $homePenalty);
            $dataProperty->AddField("GuestTeamPenalty", $guestPenalty);
            $result=$this->dbOperator->Execute($sql,$dataProperty);
        }
        return $result;
    }


}