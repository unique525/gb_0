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
     * 最新的列表数据集
     * @param int $channelId 频道id
     * @param string $topCount 分页参数，如 9 或 3,9(第4至10条)
     * @param int $state 状态
     * @return array|null 返回最新的列表数据集
     */
    public function GetNewList($channelId, $topCount, $state) {

        $result = null;

        if(!empty($topCount)){
            $selectColumn = '
            dn.*,
            uf1.UploadFilePath AS TitlePic1,
            uf2.UploadFilePath AS TitlePic2,
            uf3.UploadFilePath AS TitlePic3,
            c.ChannelName

            ';

            $sql = "SELECT $selectColumn FROM " . self::TableName_DocumentNews . "  dn
                    LEFT OUTER JOIN " .self::TableName_Channel." c on dn.ChannelId = c.ChannelId
                    LEFT OUTER JOIN " .self::TableName_UploadFile." uf1 on dn.TitlePic1UploadFileId=uf1.UploadFileId
                    LEFT OUTER JOIN " .self::TableName_UploadFile." uf2 on dn.TitlePic2UploadFileId=uf2.UploadFileId
                    LEFT OUTER JOIN " .self::TableName_UploadFile." uf3 on dn.TitlePic3UploadFileId=uf3.UploadFileId

                WHERE dn.ChannelId=:ChannelId AND dn.State=:State
                ORDER BY dn.Sort DESC, dn.CreateDate DESC LIMIT " . $topCount;
            $dataProperty = new DataProperty();
            $dataProperty->AddField("ChannelId", $channelId);
            $dataProperty->AddField("State", $state);
            $result = $this->dbOperator->GetArrayList($sql, $dataProperty);

        }

        return $result;
    }

    /**
     * 取得子节点资讯列表
     * @param int $channelId 频道id
     * @param string $topCount 显示条数  1或 1,10
     * @param int $state 状态
     * @param int $orderBy 排序方式
     * @return array|null 返回资讯列表
     */
    public function GetListOfChild($channelId, $topCount, $state, $orderBy = 0) {

        $result = null;

        if($channelId>0 && !empty($topCount)){

            $orderBySql = 'Sort DESC, CreateDate DESC';

            switch($orderBy){

                case 0:
                    $orderBySql = 'CreateDate DESC, Sort DESC';
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
                    ParentId=:ChannelId
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
     * 子和孙节点的列表数据集
     * @param int $channelId 频道id
     * @param string $topCount 分页参数，如 9 或 3,9(第4至10条)
     * @param int $state 状态
     * @param int $orderBy 排序方式
     * @return array|null 返回子和孙节点的列表数据集
     */
    public function GetListOfGrandson($channelId, $topCount, $state, $orderBy) {

        $result = null;

        if(!empty($topCount)){
            $selectColumn = '
            dn.*,
            uf1.UploadFilePath AS TitlePic1,
            uf2.UploadFilePath AS TitlePic2,
            uf3.UploadFilePath AS TitlePic3,
            c.ChannelName


            ';

            $sql = "SELECT $selectColumn FROM " . self::TableName_DocumentNews . " dn
                    LEFT OUTER JOIN ".self::TableName_Channel." c on dn.ChannelId = c.ChannelId
                    LEFT OUTER JOIN " .self::TableName_UploadFile." uf1 on dn.TitlePic1UploadFileId=uf1.UploadFileId
                    LEFT OUTER JOIN " .self::TableName_UploadFile." uf2 on dn.TitlePic2UploadFileId=uf2.UploadFileId
                    LEFT OUTER JOIN " .self::TableName_UploadFile." uf3 on dn.TitlePic3UploadFileId=uf3.UploadFileId

                WHERE

                    (dn.ChannelId IN (SELECT ChannelId FROM ".self::TableName_Channel." WHERE ParentId=:ParentId)
                        OR
                     dn.ChannelId IN (SELECT ChannelId FROM ".self::TableName_Channel." WHERE ParentId IN (SELECT ChannelId FROM ".self::TableName_Channel." WHERE ParentId=:ParentId))
                    )
                    AND dn.State=:State



                ORDER BY dn.CreateDate DESC
                LIMIT " . $topCount;

            $dataProperty = new DataProperty();
            $dataProperty->AddField("ParentId", $channelId);
            $dataProperty->AddField("State", $state);
            $result = $this->dbOperator->GetArrayList($sql, $dataProperty);
        }

        return $result;
    }

    /**
     * 子节点按推荐级别的列表数据集
     * @param int $channelId 频道id
     * @param string $topCount 分页参数，如 9 或 3,9(第4至10条)
     * @param int $state 状态
     * @param string $recLevel 推荐级别
     * @param int $orderBy 排序方式
     * @return array|null 返回子节点的列表数据集
     */
    public function GetListOfRecLevelChild($channelId, $topCount, $state, $recLevel="", $orderBy) {

        $result = null;

        if(!empty($topCount)){
            $recLevelSelection=" AND RecLevel Between 1 AND 10 ";
            if($recLevel!=""){
                $recLevelSelection=" AND RecLevel Between ".$recLevel." ";
            }
            $selectColumn = '
            dn.*,
            uf1.UploadFilePath AS TitlePic1,
            uf2.UploadFilePath AS TitlePic2,
            uf3.UploadFilePath AS TitlePic3,
            c.ChannelName

            ';

            $sql = "SELECT $selectColumn FROM " . self::TableName_DocumentNews . " dn

                    LEFT OUTER JOIN " .self::TableName_Channel." c on dn.ChannelId = c.ChannelId
                    LEFT OUTER JOIN " .self::TableName_UploadFile." uf1 on dn.TitlePic1UploadFileId=uf1.UploadFileId
                    LEFT OUTER JOIN " .self::TableName_UploadFile." uf2 on dn.TitlePic2UploadFileId=uf2.UploadFileId
                    LEFT OUTER JOIN " .self::TableName_UploadFile." uf3 on dn.TitlePic3UploadFileId=uf3.UploadFileId

                WHERE
                    dn.ChannelId IN (SELECT ChannelId FROM ".self::TableName_Channel." WHERE ParentId=:ParentId)
                    AND dn.State=:State
                " . $recLevelSelection . "


                ORDER BY dn.ShowDate DESC, dn.RecLevel DESC, dn.Sort DESC, dn.CreateDate DESC
                LIMIT " . $topCount;

            $dataProperty = new DataProperty();
            $dataProperty->AddField("ParentId", $channelId);
            $dataProperty->AddField("State", $state);
            $result = $this->dbOperator->GetArrayList($sql, $dataProperty);
        }

        return $result;
    }

    /**
     * 子和孙节点按推荐级别的列表数据集
     * @param int $channelId 频道id
     * @param string $topCount 分页参数，如 9 或 3,9(第4至10条)
     * @param int $state 状态
     * @param string $recLevel 推荐级别
     * @param int $orderBy 排序方式
     * @return array|null 返回子和孙节点的列表数据集
     */
    public function GetListOfRecLevelGrandson($channelId, $topCount, $state, $recLevel="" ,$orderBy) {

        $result = null;

        if(!empty($topCount)){
            $recLevelSelection=" AND RecLevel Between 1 AND 10 ";
            if($recLevel!=""){
                $recLevelSelection=" AND RecLevel Between ".$recLevel." ";
            }
            $selectColumn = '
            dn.*,
            uf1.UploadFilePath AS TitlePic1,
            uf2.UploadFilePath AS TitlePic2,
            uf3.UploadFilePath AS TitlePic3,
            c.ChannelName


            ';

            $sql = "SELECT $selectColumn FROM " . self::TableName_DocumentNews . " dn
                    LEFT OUTER JOIN ".self::TableName_Channel." c on dn.ChannelId = c.ChannelId
                    LEFT OUTER JOIN " .self::TableName_UploadFile." uf1 on dn.TitlePic1UploadFileId=uf1.UploadFileId
                    LEFT OUTER JOIN " .self::TableName_UploadFile." uf2 on dn.TitlePic2UploadFileId=uf2.UploadFileId
                    LEFT OUTER JOIN " .self::TableName_UploadFile." uf3 on dn.TitlePic3UploadFileId=uf3.UploadFileId

                WHERE

                    (dn.ChannelId IN (SELECT ChannelId FROM ".self::TableName_Channel." WHERE ParentId=:ParentId)
                        OR
                     dn.ChannelId IN (SELECT ChannelId FROM ".self::TableName_Channel." WHERE ParentId IN (SELECT ChannelId FROM ".self::TableName_Channel." WHERE ParentId=:ParentId))
                    )
                    AND dn.State=:State
                " . $recLevelSelection . "



                ORDER BY dn.ShowDate DESC, dn.RecLevel DESC, dn.CreateDate DESC
                LIMIT " . $topCount;

            $dataProperty = new DataProperty();
            $dataProperty->AddField("ParentId", $channelId);
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

    public function GetOpenComment($documentNewsId,$withCache){
        $result = -1;
        if($documentNewsId > 0){
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'document_news_data';
            $cacheFile = 'channel_get_children_channel_id.cache_' . $documentNewsId . '';
            $sql = "SELECT OpenComment FROM ".self::TableName_DocumentNews." WHERE DocumentNewsId = :DocumentNewsId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("DocumentNewsId",$documentNewsId);
            $result = $this->GetInfoOfIntValue($sql,$dataProperty,$withCache,$cacheDir,$cacheFile);
        }
        return $result;
    }

    public function GetChannelId($documentNewsId,$withCache){
        $result = -1;
        if($documentNewsId > 0){
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'document_news_data';
            $cacheFile = 'channel_get_children_channel_id.cache_' . $documentNewsId . '';
            $sql = "SELECT ChannelId FROM ".self::TableName_DocumentNews." WHERE DocumentNewsId = :DocumentNewsId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("DocumentNewsId",$documentNewsId);
            $result = $this->GetInfoOfIntValue($sql,$dataProperty,$withCache,$cacheDir,$cacheFile);
        }
        return $result;
    }


    /**
     * 增加一个点击
     * @param int $documentNewsId id
     * @return int 操作结果
     */
    public function AddHit($documentNewsId){
        $result = -1;
        if($documentNewsId > 0){
            $sql = "UPDATE ".self::TableName_DocumentNews." SET Hit = Hit+1 WHERE DocumentNewsId=:DocumentNewsId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("DocumentNewsId",$documentNewsId);
            $result = $this->dbOperator->Execute($sql,$dataProperty);
        }
        return $result;
    }


    /**
     * 增加一个评论数
     * @param int $documentNewsId id
     * @return int 操作结果
     */
    public function AddCommentCount($documentNewsId){
        $result = -1;
        if($documentNewsId > 0){
            $sql = "UPDATE ".self::TableName_DocumentNews." SET CommentCount = CommentCount+1 WHERE DocumentNewsId=:DocumentNewsId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("DocumentNewsId",$documentNewsId);
            $result = $this->dbOperator->Execute($sql,$dataProperty);
        }
        return $result;
    }
}

?>
