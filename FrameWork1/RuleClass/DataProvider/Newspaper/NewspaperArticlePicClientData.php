<?php
/**
 * 客户端 电子报文章图片 数据类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_Newspaper
 * @author zhangchi
 */
class NewspaperArticlePicClientData extends BaseClientData {
    /**
     *
     * @param $newspaperArticleId
     * @return array|null
     */
    public function GetList($newspaperArticleId)
    {
        $result = null;

        if ($newspaperArticleId > 0) {

            $orderBySql = 'nap.Sort DESC, nap.CreateDate DESC';

            $selectColumn = '
            nap.*,uf.*
            ';

            $sql = "SELECT
                        $selectColumn
                    FROM " . self::TableName_NewspaperArticlePic . " nap,
                    " . self::TableName_UploadFile . " uf
                WHERE
                    nap.NewspaperArticleId=:NewspaperArticleId
                    AND nap.UploadFileId = uf.UploadFileId
                ORDER BY $orderBySql";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("NewspaperArticleId", $newspaperArticleId);
            $result = $this->dbOperator->GetArrayList($sql, $dataProperty);

        }


        return $result;
    }
}