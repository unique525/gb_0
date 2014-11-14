<?php

/**
 * 前台 资讯 数据类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_Document
 * @author zhangchi
 */
class DocumentNewsPublicData extends BasePublicData {


    //////////////////////////////////////////////////////////////////////////////
    //////////////////////////////Get List////////////////////////////////////////
    //////////////////////////////////////////////////////////////////////////////



    /**
     * 取得资讯列表
     * @param int $channelId 频道id
     * @param string $topCount 显示条数  1或 1,10
     * @param int $state 状态
     * @param int $orderBy 排序方式
     * @return array|null 返回资讯列表
     */
    public function GetList($channelId, $topCount, $state, $orderBy = 0) {

        $result = null;

        if($channelId>0 && !empty($topCount)){

            $orderBySql = 'Sort DESC, CreateDate DESC';

            switch($orderBy){

                case 0:
                    $orderBySql = 'Sort DESC, CreateDate DESC';
                    break;

            }


            $selectColumn = '
            DocumentNewsId,
            SiteId,
            ChannelId,
            DocumentNewsTitle,
            DocumentNewsSubTitle,
            DocumentNewsCiteTitle,
            DocumentNewsShortTitle,
            DocumentNewsIntro,
            CreateDate,
            ManageUserId,
            ManageUserName,
            UserId,
            UserName,
            Author,
            State,
            DocumentNewsType,
            DirectUrl,
            ShowDate,
            SourceName,
            DocumentNewsMainTag,
            DocumentNewsTag,
            Sort,
            TitlePic1UploadFileId,
            TitlePic2UploadFileId,
            TitlePic3UploadFileId,
            DocumentNewsTitleColor,
            DocumentNewsTitleBold,
            OpenComment,
            ShowHour,
            ShowMinute,
            ShowSecond,
            IsHot,
            RecLevel,
            ShowPicMethod,
            ClosePosition,
            Hit,
            PublishDate,
            (SELECT UploadFilePath FROM '. self::TableName_UploadFile .' WHERE UploadFileId=
                ' . self::TableName_DocumentNews . '.TitlePic1UploadFileId) AS TitlePic1Path
            ';

            $sql = "SELECT $selectColumn FROM " . self::TableName_DocumentNews . "
                WHERE
                    ChannelId=:ChannelId
                    AND State=:State
                ORDER BY $orderBySql LIMIT " . $topCount;
            $dataProperty = new DataProperty();
            $dataProperty->AddField("ChannelId", $channelId);
            $dataProperty->AddField("State", $state);
            $result = $this->dbOperator->GetArrayList($sql, $dataProperty);

        }




        return $result;
    }

    /**
     * 取得分页显示的资讯列表
     * @param int $channelId 频道id
     * @param int $pageBegin 记录开始位置
     * @param int $pageSize 显示数量
     * @param int $allCount 总数量
     * @param int $state 状态
     * @param string $searchKey 查询关键字
     * @param int $parentId 父频道id
     * @return array 分页显示的资讯列表
     */
    public function GetListForPager(
        $channelId,
        $pageBegin,
        $pageSize,
        &$allCount,
        $state,
        $searchKey = "",
        $parentId = 0
    ) {
        $searchSql = "";
        $dataProperty = new DataProperty();
        if ($parentId > 0) {
            $searchSql .= " AND ChannelId IN (SELECT ChannelId
                                                FROM ".self::TableName_Channel." WHERE ParentId=:ParentId) ";
            $dataProperty->AddField("ParentId", $parentId);
        } else {
            if ($channelId > 0) {
                $searchSql .= " AND ChannelId=:ChannelId ";
                $dataProperty->AddField("ChannelId", $channelId);
            }
        }
        if (strlen($searchKey) > 0 && $searchKey != "undefined") {
            $searchSql .= " AND (
                    DocumentNewsTitle LIKE :SearchKey1
                OR ManageUserName LIKE :SearchKey2
                OR UserName LIKE :SearchKey3
                OR Author LIKE :SearchKey4
                OR DocumentNewsSubTitle LIKE :SearchKey5
                OR DocumentNewsCiteTitle LIKE :SearchKey6
                OR DocumentNewsShortTitle LIKE :SearchKey7

            )";
            $dataProperty->AddField("SearchKey1", "%" . $searchKey . "%");
            $dataProperty->AddField("SearchKey2", "%" . $searchKey . "%");
            $dataProperty->AddField("SearchKey3", "%" . $searchKey . "%");
            $dataProperty->AddField("SearchKey4", "%" . $searchKey . "%");
            $dataProperty->AddField("SearchKey5", "%" . $searchKey . "%");
            $dataProperty->AddField("SearchKey6", "%" . $searchKey . "%");
            $dataProperty->AddField("SearchKey7", "%" . $searchKey . "%");
        }

        $selectColumn = '
            DocumentNewsId,
            SiteId,
            ChannelId,
            DocumentNewsTitle,
            DocumentNewsSubTitle,
            DocumentNewsCiteTitle,
            DocumentNewsShortTitle,
            DocumentNewsIntro,
            CreateDate,
            ManageUserId,
            ManageUserName,
            UserId,
            UserName,
            Author,
            State,
            DocumentNewsType,
            DirectUrl,
            ShowDate,
            SourceName,
            DocumentNewsMainTag,
            DocumentNewsTag,
            Sort,
            TitlePic1UploadFileId,
            TitlePic2UploadFileId,
            TitlePic3UploadFileId,
            DocumentNewsTitleColor,
            DocumentNewsTitleBold,
            OpenComment,
            ShowHour,
            ShowMinute,
            ShowSecond,
            IsHot,
            RecLevel,
            ShowPicMethod,
            ClosePosition,
            Hit,
            PublishDate,
            (SELECT UploadFilePath FROM '. self::TableName_UploadFile .' WHERE UploadFileId=
                ' . self::TableName_DocumentNews . '.TitlePic1UploadFileId) AS TitlePic1Path
            ';

        $sql = "
            SELECT
            $selectColumn
            FROM
            " . self::TableName_DocumentNews . "
            WHERE State=:State " . $searchSql . "
            ORDER BY Sort DESC, PublishDate DESC
            LIMIT " . $pageBegin . "," . $pageSize . "";


        $dataProperty->AddField("State", $state);
        $result = $this->dbOperator->GetArrayList($sql, $dataProperty);


        $sql = "SELECT count(*) FROM " . self::TableName_DocumentNews . " WHERE State=:State " . $searchSql;
        $allCount = $this->dbOperator->GetInt($sql, $dataProperty);

        return $result;
    }
}

?>
