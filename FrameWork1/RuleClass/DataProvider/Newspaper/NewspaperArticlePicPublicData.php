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
     * @param string $fileName
     * @return int
     */
    public function CreateForImport(
        $newspaperArticleId,
        $remark,
        $uploadFileId,
        $picMapping,
        $fileName
    )
    {

        $newspaperArticlePicId = self::GetNewspaperArticlePicIdForImport($fileName, $newspaperArticleId);

        if ($newspaperArticlePicId <= 0 && $newspaperArticleId > 0) {
            $sql = "INSERT INTO " . self::TableName_NewspaperArticlePic . "
                (
                    NewspaperArticleId,
                    Remark,
                    CreateDate,
                    UploadFileId,
                    PicMapping,
                    FileName
                ) VALUES (
                    :NewspaperArticleId,
                    :Remark,
                     now(),
                    :UploadFileId,
                    :PicMapping,
                    :FileName

                );";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("NewspaperArticleId", $newspaperArticleId);
            $dataProperty->AddField("Remark", $remark);
            $dataProperty->AddField("UploadFileId", $uploadFileId);
            $dataProperty->AddField("PicMapping", $picMapping);
            $dataProperty->AddField("FileName", $fileName);
            $result = $this->dbOperator->LastInsertId($sql, $dataProperty);
        } else {
            $result = $newspaperArticlePicId;
        }

        return $result;
    }

    /**
     * @param string $fileName
     * @param int $newspaperArticleId
     * @return int
     */
    public function GetNewspaperArticlePicIdForImport($fileName, $newspaperArticleId)
    {
        $result = -1;
        if (strlen($fileName) > 0 && $newspaperArticleId > 0) {
            $sql = "SELECT NewspaperArticlePicId FROM " . self::TableName_NewspaperArticlePic . "
                        WHERE FileName=:FileName
                        AND NewspaperArticleId=:NewspaperArticleId
                        AND State<100
                        ;";

            $dataProperty = new DataProperty();
            $dataProperty->AddField("FileName", $fileName);
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
                    AND nap.State<100
                ORDER BY $orderBySql";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("NewspaperArticleId", $newspaperArticleId);
            $result = $this->dbOperator->GetArrayList($sql, $dataProperty);

        }


        return $result;
    }
} 