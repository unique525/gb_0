<?php
/**
 * 客户端 电子报文章 数据类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_Newspaper
 * @author zhangchi
 */
class NewspaperArticleClientData extends BaseClientData {

    /**
     * 取得电子报文章列表
     * @param int $newspaperPageId 电子报版面id
     * @return array|null 返回资讯列表
     */
    public function GetList($newspaperPageId)
    {

        $result = null;

        if ($newspaperPageId > 0) {

            $orderBySql = 'Sort , CreateDate DESC';


            $selectColumn = '
            *,
            (SELECT uf.UploadFilePath FROM
                ' . self::TableName_NewspaperArticlePic . ' nap,
                ' . self::TableName_UploadFile . ' uf
                WHERE uf.UploadFileId=nap.UploadFileId
                AND nap.NewspaperArticleId=' . self::TableName_NewspaperArticle . '.NewspaperArticleId
                LIMIT 1
                ) AS UploadFilePath
            ';

            $sql = "SELECT $selectColumn FROM " . self::TableName_NewspaperArticle . "
                WHERE
                    NewspaperPageId=:NewspaperPageId
                    AND State<100
                ORDER BY $orderBySql";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("NewspaperPageId", $newspaperPageId);
            $result = $this->dbOperator->GetArrayList($sql, $dataProperty);

        }


        return $result;
    }
}