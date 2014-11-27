<?php

/**
 * 前台 电子报文章图片 数据类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_Newspaper
 * @author zhangchi
 */
class NewspaperArticlePicPublicData extends BasePublicData
{

    /**
     *
     * @param int $newspaperArticleId
     * @param string $remark
     * @param int $uploadFileId
     * @param string $picMapping
     * @return int
     */
    public function CreateForImport(
        $newspaperArticleId,
        $remark,
        $uploadFileId,
        $picMapping
    )
    {

        $newspaperArticlePicId = self::GetNewspaperArticlePicIdForImport($remark, $newspaperArticleId);

        if ($newspaperArticlePicId <= 0 && $newspaperArticleId > 0) {
            $sql = "INSERT INTO " . self::TableName_NewspaperArticlePic . "
                (
                    NewspaperArticleId,
                    Remark,
                    CreateDate,
                    UploadFileId,
                    PicMapping
                ) VALUES (
                    :NewspaperArticleId,
                    :Remark,
                     now(),
                    :UploadFileId,
                    :PicMapping

                );";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("NewspaperArticleId", $newspaperArticleId);
            $dataProperty->AddField("Remark", $remark);
            $dataProperty->AddField("UploadFileId", $uploadFileId);
            $dataProperty->AddField("PicMapping", $picMapping);
            $result = $this->dbOperator->LastInsertId($sql, $dataProperty);
        } else {
            $result = $newspaperArticlePicId;
        }

        return $result;
    }

    /**
     * @param string $remark
     * @param int $newspaperArticleId
     * @return int
     */
    public function GetNewspaperArticlePicIdForImport($remark, $newspaperArticleId)
    {
        $result = -1;
        if (strlen($remark) > 0 && $newspaperArticleId > 0) {
            $sql = "SELECT NewspaperArticlePicId FROM " . self::TableName_NewspaperArticlePic . "
                        WHERE Remark=:Remark AND NewspaperArticleId=:NewspaperArticleId;";

            $dataProperty = new DataProperty();
            $dataProperty->AddField("Remark", $remark);
            $dataProperty->AddField("NewspaperArticleId", $newspaperArticleId);
            $result = $this->dbOperator->GetInt($sql, $dataProperty);
        }


        return $result;
    }

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