<?php
/**
 * 前台 电子报文章 数据类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_Newspaper
 * @author zhangchi
 */
class NewspaperArticlePublicData extends BasePublicData {



    /**
     * 取得电子报文章列表
     * @param int $newspaperPageId 电子报版面id
     * @param string $topCount 显示条数  1或 1,10
     * @param int $state 状态
     * @param int $orderBy 排序方式
     * @return array|null 返回资讯列表
     */
    public function GetList($newspaperPageId, $topCount, $state, $orderBy = 0) {

        $result = null;

        if($newspaperPageId>0 && !empty($topCount)){

            $orderBySql = 'Sort DESC, CreateDate DESC';

            switch($orderBy){

                case 0:
                    $orderBySql = 'Sort DESC, CreateDate DESC';
                    break;

            }


            $selectColumn = '
            *,
            (SELECT uf.UploadFilePath FROM
                '.self::TableName_NewspaperArticlePic.' nap,
                '.self::TableName_UploadFile.' uf
                WHERE uf.UploadFileId=nap.UploadFileId
                AND nap.NewspaperArticleId='.self::TableName_NewspaperArticle.'.NewspaperArticleId
                LIMIT 1
                ) AS UploadFilePath
            ';

            $sql = "SELECT $selectColumn FROM " . self::TableName_NewspaperArticle . "
                WHERE
                    NewspaperPageId=:NewspaperPageId
                    AND State=:State
                ORDER BY $orderBySql LIMIT " . $topCount;
            $dataProperty = new DataProperty();
            $dataProperty->AddField("NewspaperPageId", $newspaperPageId);
            $dataProperty->AddField("State", $state);
            $result = $this->dbOperator->GetArrayList($sql, $dataProperty);

        }




        return $result;
    }



    /**
     * 返回一行数据
     * @param int $newspaperArticleId 电子报文章id
     * @return array|null 取得对应数组
     */
    public function GetOne($newspaperArticleId){
        $result = null;
        if($newspaperArticleId>0){
            $sql = "
            SELECT *
            FROM
            " . self::TableName_NewspaperArticle . "
            WHERE NewspaperArticleId=:NewspaperArticleId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("NewspaperArticleId", $newspaperArticleId);
            $result = $this->dbOperator->GetArray($sql, $dataProperty);
        }
        return $result;
    }
} 