<?php

/**
 * 前台 电子报文章 数据类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_Newspaper
 * @author zhangchi
 */
class NewspaperArticlePublicData extends BasePublicData
{

    /**
     * 创建电子报文章（导入使用）
     * @param int $newspaperPageId
     * @param string $newspaperArticleTitle
     * @param string $newspaperArticleContent
     * @param string $newspaperArticleSubTitle
     * @param string $newspaperArticleCiteTitle
     * @param string $publishType
     * @param string $published
     * @param string $informationId
     * @param string $source
     * @param string $author
     * @param string $column
     * @param string $picRemark
     * @param string $next
     * @param string $previous
     * @param string $no
     * @param string $className
     * @param string $genre
     * @param string $reprint
     * @param string $fileName
     * @param string $abstractInfo
     * @param string $wordCount
     * @param string $picMapping
     * @return int 电子报文章id
     */
    public function CreateForImport(
        $newspaperPageId,
        $newspaperArticleTitle,
        $newspaperArticleContent,
        $newspaperArticleSubTitle,
        $newspaperArticleCiteTitle,
        $publishType,
        $published,
        $informationId,
        $source,
        $author,
        $column,
        $picRemark,
        $next,
        $previous,
        $no,
        $className,
        $genre,
        $reprint,
        $fileName,
        $abstractInfo,
        $wordCount,
        $picMapping

    )
    {

        $newspaperArticleId = self::GetNewspaperArticleIdForImport($newspaperArticleTitle, $newspaperPageId);
        if ($newspaperArticleId <= 0 && $newspaperPageId > 0) {
            $sql = "INSERT INTO " . self::TableName_NewspaperArticle . "
                    (
                    `NewspaperPageId`,
                    `NewspaperArticleTitle`,
                    `CreateDate`,
                    `NewspaperArticleContent`,
                    `NewspaperArticleSubTitle`,
                    `NewspaperArticleCiteTitle`,
                    `PublishType`,
                    `Published`,
                    `InformationId`,
                    `Source`,
                    `Author`,
                    `Column`,
                    `PicRemark`,
                    `Next`,
                    `Previous`,
                    `No`,
                    `ClassName`,
                    `Genre`,
                    `Reprint`,
                    `FileName`,
                    `Abstract`,
                    `WordCount`,
                    `PicMapping`
                    ) VALUES (
                    :NewspaperPageId,
                    :NewspaperArticleTitle,
                    now(),
                    :NewspaperArticleContent,
                    :NewspaperArticleSubTitle,
                    :NewspaperArticleCiteTitle,
                    :PublishType,
                    :Published,
                    :InformationId,
                    :Source,
                    :Author,
                    :Column,
                    :PicRemark,
                    :Next,
                    :Previous,
                    :No,
                    :ClassName,
                    :Genre,
                    :Reprint,
                    :FileName,
                    :Abstract,
                    :WordCount,
                    :PicMapping
                    );";

            $dataProperty = new DataProperty();
            $dataProperty->AddField("NewspaperPageId", $newspaperPageId);
            $dataProperty->AddField("NewspaperArticleTitle", $newspaperArticleTitle);
            $dataProperty->AddField("NewspaperArticleContent", $newspaperArticleContent);
            $dataProperty->AddField("NewspaperArticleSubTitle", $newspaperArticleSubTitle);
            $dataProperty->AddField("NewspaperArticleCiteTitle", $newspaperArticleCiteTitle);
            $dataProperty->AddField("PublishType", $publishType);
            $dataProperty->AddField("Published", $published);
            $dataProperty->AddField("InformationId", $informationId);
            $dataProperty->AddField("Source", $source);
            $dataProperty->AddField("Author", $author);
            $dataProperty->AddField("Column", $column);
            $dataProperty->AddField("PicRemark", $picRemark);
            $dataProperty->AddField("Next", $next);
            $dataProperty->AddField("Previous", $previous);
            $dataProperty->AddField("No", $no);
            $dataProperty->AddField("ClassName", $className);
            $dataProperty->AddField("Genre", $genre);
            $dataProperty->AddField("Reprint", $reprint);
            $dataProperty->AddField("FileName", $fileName);
            $dataProperty->AddField("Abstract", $abstractInfo);
            $dataProperty->AddField("WordCount", $wordCount);
            $dataProperty->AddField("PicMapping", $picMapping);
            $result = $this->dbOperator->LastInsertId($sql, $dataProperty);
        } else {
            $result = $newspaperArticleId;
        }

        return $result;
    }

    /**
     * 根据文章标题和版面id返回文章id
     * @param string $newspaperArticleTitle 文章标题
     * @param int $newspaperPageId 版面id
     * @return int 文章id
     */
    public function GetNewspaperArticleIdForImport($newspaperArticleTitle, $newspaperPageId)
    {
        $result = -1;
        if (strlen($newspaperArticleTitle) > 0 && $newspaperPageId > 0) {

            $sql = "SELECT NewspaperArticleId FROM " . self::TableName_NewspaperArticle . "

                WHERE NewspaperArticleTitle=:NewspaperArticleTitle AND NewspaperPageId=:NewspaperPageId;";

            $dataProperty = new DataProperty();
            $dataProperty->AddField("NewspaperArticleTitle", $newspaperArticleTitle);
            $dataProperty->AddField("NewspaperPageId", $newspaperPageId);
            $result = $this->dbOperator->GetInt($sql, $dataProperty);

        }

        return $result;
    }

    /**
     * 取得电子报文章列表
     * @param int $newspaperPageId 电子报版面id
     * @param string $topCount 显示条数  1或 1,10
     * @param int $state 状态
     * @param int $orderBy 排序方式
     * @return array|null 返回资讯列表
     */
    public function GetList($newspaperPageId, $topCount, $state, $orderBy = 0)
    {

        $result = null;

        if ($newspaperPageId > 0 && !empty($topCount)) {

            $orderBySql = 'Sort DESC, CreateDate DESC';

            switch ($orderBy) {

                case 0:
                    $orderBySql = 'Sort DESC, CreateDate DESC';
                    break;

            }


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
    public function GetOne($newspaperArticleId)
    {
        $result = null;
        if ($newspaperArticleId > 0) {
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