<?php
/**
 * 前台 电子报版面 数据类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_Newspaper
 * @author zhangchi
 */
class NewspaperPagePublicData extends BasePublicData {


    //////////////////////////////////////////////////////////////////////////////
    //////////////////////////////Get Info////////////////////////////////////////
    //////////////////////////////////////////////////////////////////////////////

    /**
     * 取得第一条记录
     * @param int $newspaperId
     * @return int
     */
    public function GetNewspaperPageIdOfFirst($newspaperId){
        $result = -1;
        if($newspaperId>0){
            $sql = "SELECT NewspaperPageId FROM ".self::TableName_NewspaperPage."

                WHERE NewspaperId = :NewspaperId

                ORDER BY NewspaperPageId LIMIT 1;

                ";

            $dataProperty = new DataProperty();
            $dataProperty->AddField("NewspaperId", $newspaperId);
            $result = $this->dbOperator->GetInt($sql, $dataProperty);
        }
        return $result;
    }

    /**
     * 取得第一条记录
     * @param int $newspaperId
     * @param int $newspaperPageId
     * @return int
     */
    public function GetNewspaperPageIdOfNext($newspaperId, $newspaperPageId){
        $result = -1;
        if($newspaperId>0){
            $sql = "SELECT NewspaperPageId FROM ".self::TableName_NewspaperPage."

                WHERE NewspaperId = :NewspaperId
                    AND NewspaperPageId>:NewspaperPageId

                ORDER BY NewspaperPageId LIMIT 1;

                ";

            $dataProperty = new DataProperty();
            $dataProperty->AddField("NewspaperPageId", $newspaperPageId);
            $dataProperty->AddField("NewspaperId", $newspaperId);
            $result = $this->dbOperator->GetInt($sql, $dataProperty);
        }
        return $result;
    }

    /**
     * 取得第一条记录
     * @param int $newspaperId
     * @param int $newspaperPageId
     * @return int
     */
    public function GetNewspaperPageIdOfPrevious($newspaperId, $newspaperPageId){
        $result = -1;
        if($newspaperId>0){
            $sql = "SELECT NewspaperPageId FROM ".self::TableName_NewspaperPage."

                WHERE NewspaperId = :NewspaperId
                 AND NewspaperPageId<:NewspaperPageId

                ORDER BY NewspaperPageId DESC LIMIT 1;

                ";

            $dataProperty = new DataProperty();
            $dataProperty->AddField("NewspaperPageId", $newspaperPageId);
            $dataProperty->AddField("NewspaperId", $newspaperId);
            $result = $this->dbOperator->GetInt($sql, $dataProperty);
        }
        return $result;
    }


    /**
     * 取得一条信息
     * @param int $newspaperPageId 电子报版面id
     * @return array 电子报版面信息数组
     */
    public function GetOne($newspaperPageId)
    {

        $sql = "SELECT np.*,uf.*


                FROM " . self::TableName_NewspaperPage . " np LEFT JOIN
                     ".self::TableName_UploadFile." uf
                     ON np.PicUploadFileId=uf.UploadFileId

                WHERE " . self::TableId_NewspaperPage. "=:" . self::TableId_NewspaperPage . ";";
        $dataProperty = new DataProperty();
        $dataProperty->AddField(self::TableId_NewspaperPage, $newspaperPageId);
        $result = $this->dbOperator->GetArray($sql, $dataProperty);
        return $result;
    }



} 