<?php

/**
 * 前台 资讯 数据类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_Activity
 * @author zhangchi
 */
class ActivityPublicData extends BasePublicData {


    //////////////////////////////////////////////////////////////////////////////
    //////////////////////////////Get List////////////////////////////////////////
    //////////////////////////////////////////////////////////////////////////////

    /**
     * 取得列表
     * @param int $channelId 频道id
     * @param string $topCount 显示条数  1或 1,10
     * @param int $state 状态
     * @param int $orderBy 排序方式
     * @return array|null 返回列表
     */
    public function GetList($channelId, $topCount, $state, $orderBy = 0) {

        $result = null;

        if($channelId>0 && !empty($topCount)){

            $orderBySql = 'ORDER BY dn.Sort DESC, dn.CreateDate DESC';

            switch($orderBy){

                case 0:
                    $orderBySql = 'ORDER BY dn.Sort DESC, dn.CreateDate DESC';
                    break;

            }


            $selectColumn = '
            dn.*,

            uf1.UploadFilePath AS TitlePic1UploadFilePath,
            uf1.UploadFileMobilePath AS TitlePic1UploadFileMobilePath,
            uf1.UploadFilePadPath AS TitlePic1UploadFilePadPath,
            uf1.UploadFileThumbPath1 AS TitlePic1UploadFileThumbPath1,
            uf1.UploadFileThumbPath2 AS TitlePic1UploadFileThumbPath2,
            uf1.UploadFileThumbPath3 AS TitlePic1UploadFileThumbPath3,
            uf1.UploadFileWatermarkPath1 AS TitlePic1UploadFileWatermarkPath1,
            uf1.UploadFileWatermarkPath2 AS TitlePic1UploadFileWatermarkPath2,
            uf1.UploadFileCompressPath1 AS TitlePic1UploadFileCompressPath1,
            uf1.UploadFileCompressPath2 AS TitlePic1UploadFileCompressPath2,
            uf1.UploadFileCutPath1 AS TitlePic1UploadFileCutPath1,


            uf2.UploadFilePath AS TitlePic2UploadFilePath,
            uf2.UploadFileMobilePath AS TitlePic2UploadFileMobilePath,
            uf2.UploadFilePadPath AS TitlePic2UploadFilePadPath,
            uf2.UploadFileThumbPath1 AS TitlePic2UploadFileThumbPath1,
            uf2.UploadFileThumbPath2 AS TitlePic2UploadFileThumbPath2,
            uf2.UploadFileThumbPath3 AS TitlePic2UploadFileThumbPath3,
            uf2.UploadFileWatermarkPath1 AS TitlePic2UploadFileWatermarkPath1,
            uf2.UploadFileWatermarkPath2 AS TitlePic2UploadFileWatermarkPath2,
            uf2.UploadFileCompressPath1 AS TitlePic2UploadFileCompressPath1,
            uf2.UploadFileCompressPath2 AS TitlePic2UploadFileCompressPath2,
            uf2.UploadFileCutPath1 AS TitlePic2UploadFileCutPath1,


            uf3.UploadFilePath AS TitlePic3UploadFilePath,
            uf3.UploadFileMobilePath AS TitlePic3UploadFileMobilePath,
            uf3.UploadFilePadPath AS TitlePic3UploadFilePadPath,
            uf3.UploadFileThumbPath1 AS TitlePic3UploadFileThumbPath1,
            uf3.UploadFileThumbPath2 AS TitlePic3UploadFileThumbPath2,
            uf3.UploadFileThumbPath3 AS TitlePic3UploadFileThumbPath3,
            uf3.UploadFileWatermarkPath1 AS TitlePic3UploadFileWatermarkPath1,
            uf3.UploadFileWatermarkPath2 AS TitlePic3UploadFileWatermarkPath2,
            uf3.UploadFileCompressPath1 AS TitlePic3UploadFileCompressPath1,
            uf3.UploadFileCompressPath2 AS TitlePic3UploadFileCompressPath2,
            uf3.UploadFileCutPath1 AS TitlePic3UploadFileCutPath1,

            c.ChannelName
            ';

            $sql = "SELECT $selectColumn FROM " . self::TableName_Activity . " dn
                    LEFT OUTER JOIN " .self::TableName_Channel." c on dn.ChannelId = c.ChannelId
                    LEFT OUTER JOIN " .self::TableName_UploadFile." uf1 on dn.TitlePic1UploadFileId=uf1.UploadFileId
                    LEFT OUTER JOIN " .self::TableName_UploadFile." uf2 on dn.TitlePic2UploadFileId=uf2.UploadFileId
                    LEFT OUTER JOIN " .self::TableName_UploadFile." uf3 on dn.TitlePic3UploadFileId=uf3.UploadFileId
                WHERE
                    dn.ChannelId=:ChannelId
                    AND dn.State=:State
                $orderBySql LIMIT " . $topCount;
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
     * @param int $orderBy 排序
     * @return array|null 返回最新的列表数据集
     */
    public function GetNewList($channelId, $topCount, $state, $orderBy = 0) {

        $result = null;

        if(!empty($topCount)){
            $orderBySql = 'ORDER BY dn.Sort DESC, dn.CreateDate DESC';

            switch($orderBy){

                case 0:
                    $orderBySql = 'dn.Sort DESC,dn.CreateDate DESC';
                    break;

            }

            $selectColumn = '
            dn.*,

            uf1.UploadFilePath AS TitlePic1UploadFilePath,
            uf1.UploadFileMobilePath AS TitlePic1UploadFileMobilePath,
            uf1.UploadFilePadPath AS TitlePic1UploadFilePadPath,
            uf1.UploadFileThumbPath1 AS TitlePic1UploadFileThumbPath1,
            uf1.UploadFileThumbPath2 AS TitlePic1UploadFileThumbPath2,
            uf1.UploadFileThumbPath3 AS TitlePic1UploadFileThumbPath3,
            uf1.UploadFileWatermarkPath1 AS TitlePic1UploadFileWatermarkPath1,
            uf1.UploadFileWatermarkPath2 AS TitlePic1UploadFileWatermarkPath2,
            uf1.UploadFileCompressPath1 AS TitlePic1UploadFileCompressPath1,
            uf1.UploadFileCompressPath2 AS TitlePic1UploadFileCompressPath2,
            uf1.UploadFileCutPath1 AS TitlePic1UploadFileCutPath1,


            uf2.UploadFilePath AS TitlePic2UploadFilePath,
            uf2.UploadFileMobilePath AS TitlePic2UploadFileMobilePath,
            uf2.UploadFilePadPath AS TitlePic2UploadFilePadPath,
            uf2.UploadFileThumbPath1 AS TitlePic2UploadFileThumbPath1,
            uf2.UploadFileThumbPath2 AS TitlePic2UploadFileThumbPath2,
            uf2.UploadFileThumbPath3 AS TitlePic2UploadFileThumbPath3,
            uf2.UploadFileWatermarkPath1 AS TitlePic2UploadFileWatermarkPath1,
            uf2.UploadFileWatermarkPath2 AS TitlePic2UploadFileWatermarkPath2,
            uf2.UploadFileCompressPath1 AS TitlePic2UploadFileCompressPath1,
            uf2.UploadFileCompressPath2 AS TitlePic2UploadFileCompressPath2,
            uf2.UploadFileCutPath1 AS TitlePic2UploadFileCutPath1,


            uf3.UploadFilePath AS TitlePic3UploadFilePath,
            uf3.UploadFileMobilePath AS TitlePic3UploadFileMobilePath,
            uf3.UploadFilePadPath AS TitlePic3UploadFilePadPath,
            uf3.UploadFileThumbPath1 AS TitlePic3UploadFileThumbPath1,
            uf3.UploadFileThumbPath2 AS TitlePic3UploadFileThumbPath2,
            uf3.UploadFileThumbPath3 AS TitlePic3UploadFileThumbPath3,
            uf3.UploadFileWatermarkPath1 AS TitlePic3UploadFileWatermarkPath1,
            uf3.UploadFileWatermarkPath2 AS TitlePic3UploadFileWatermarkPath2,
            uf3.UploadFileCompressPath1 AS TitlePic3UploadFileCompressPath1,
            uf3.UploadFileCompressPath2 AS TitlePic3UploadFileCompressPath2,
            uf3.UploadFileCutPath1 AS TitlePic3UploadFileCutPath1,

            c.ChannelName

            ';

            $sql = "SELECT $selectColumn FROM " . self::TableName_Activity . "  dn
                    LEFT OUTER JOIN " .self::TableName_Channel." c on dn.ChannelId = c.ChannelId
                    LEFT OUTER JOIN " .self::TableName_UploadFile." uf1 on dn.TitlePic1UploadFileId=uf1.UploadFileId
                    LEFT OUTER JOIN " .self::TableName_UploadFile." uf2 on dn.TitlePic2UploadFileId=uf2.UploadFileId
                    LEFT OUTER JOIN " .self::TableName_UploadFile." uf3 on dn.TitlePic3UploadFileId=uf3.UploadFileId

                WHERE dn.ChannelId=:ChannelId AND dn.State=:State
                $orderBySql LIMIT " . $topCount;
            $dataProperty = new DataProperty();
            $dataProperty->AddField("ChannelId", $channelId);
            $dataProperty->AddField("State", $state);
            $result = $this->dbOperator->GetArrayList($sql, $dataProperty);

        }

        return $result;
    }

    /**
     * 取得分页显示的活动列表
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
            $searchSql .= " AND dn.ChannelId IN (SELECT ChannelId
                                                FROM ".self::TableName_Channel." WHERE ParentId=:ParentId) ";
            $dataProperty->AddField("ParentId", $parentId);
        } else {
            if ($channelId > 0) {
                $searchSql .= " AND dn.ChannelId=:ChannelId ";
                $dataProperty->AddField("ChannelId", $channelId);
            }
        }
        if (strlen($searchKey) > 0 && $searchKey != "undefined") {
            $searchSql .= " AND (
                    ActivityTitle LIKE :SearchKey1
            )";
            $dataProperty->AddField("SearchKey1", "%" . $searchKey . "%");
        }

        $selectColumn = '
            dn.*,

            uf1.UploadFilePath AS TitlePic1UploadFilePath,
            uf1.UploadFileMobilePath AS TitlePic1UploadFileMobilePath,
            uf1.UploadFilePadPath AS TitlePic1UploadFilePadPath,
            uf1.UploadFileThumbPath1 AS TitlePic1UploadFileThumbPath1,
            uf1.UploadFileThumbPath2 AS TitlePic1UploadFileThumbPath2,
            uf1.UploadFileThumbPath3 AS TitlePic1UploadFileThumbPath3,
            uf1.UploadFileWatermarkPath1 AS TitlePic1UploadFileWatermarkPath1,
            uf1.UploadFileWatermarkPath2 AS TitlePic1UploadFileWatermarkPath2,
            uf1.UploadFileCompressPath1 AS TitlePic1UploadFileCompressPath1,
            uf1.UploadFileCompressPath2 AS TitlePic1UploadFileCompressPath2,
            uf1.UploadFileCutPath1 AS TitlePic1UploadFileCutPath1,


            uf2.UploadFilePath AS TitlePic2UploadFilePath,
            uf2.UploadFileMobilePath AS TitlePic2UploadFileMobilePath,
            uf2.UploadFilePadPath AS TitlePic2UploadFilePadPath,
            uf2.UploadFileThumbPath1 AS TitlePic2UploadFileThumbPath1,
            uf2.UploadFileThumbPath2 AS TitlePic2UploadFileThumbPath2,
            uf2.UploadFileThumbPath3 AS TitlePic2UploadFileThumbPath3,
            uf2.UploadFileWatermarkPath1 AS TitlePic2UploadFileWatermarkPath1,
            uf2.UploadFileWatermarkPath2 AS TitlePic2UploadFileWatermarkPath2,
            uf2.UploadFileCompressPath1 AS TitlePic2UploadFileCompressPath1,
            uf2.UploadFileCompressPath2 AS TitlePic2UploadFileCompressPath2,
            uf2.UploadFileCutPath1 AS TitlePic2UploadFileCutPath1,


            uf3.UploadFilePath AS TitlePic3UploadFilePath,
            uf3.UploadFileMobilePath AS TitlePic3UploadFileMobilePath,
            uf3.UploadFilePadPath AS TitlePic3UploadFilePadPath,
            uf3.UploadFileThumbPath1 AS TitlePic3UploadFileThumbPath1,
            uf3.UploadFileThumbPath2 AS TitlePic3UploadFileThumbPath2,
            uf3.UploadFileThumbPath3 AS TitlePic3UploadFileThumbPath3,
            uf3.UploadFileWatermarkPath1 AS TitlePic3UploadFileWatermarkPath1,
            uf3.UploadFileWatermarkPath2 AS TitlePic3UploadFileWatermarkPath2,
            uf3.UploadFileCompressPath1 AS TitlePic3UploadFileCompressPath1,
            uf3.UploadFileCompressPath2 AS TitlePic3UploadFileCompressPath2,
            uf3.UploadFileCutPath1 AS TitlePic3UploadFileCutPath1,

            c.ChannelName

            ';

        $sql = "SELECT $selectColumn FROM " . self::TableName_Activity . "  dn
                    LEFT OUTER JOIN " .self::TableName_Channel." c on dn.ChannelId = c.ChannelId
                    LEFT OUTER JOIN " .self::TableName_UploadFile." uf1 on dn.TitlePic1UploadFileId=uf1.UploadFileId
                    LEFT OUTER JOIN " .self::TableName_UploadFile." uf2 on dn.TitlePic2UploadFileId=uf2.UploadFileId
                    LEFT OUTER JOIN " .self::TableName_UploadFile." uf3 on dn.TitlePic3UploadFileId=uf3.UploadFileId

                WHERE dn.ChannelId=:ChannelId AND dn.State=:State" . $searchSql. "
                ORDER BY dn.Sort DESC, dn.ActivityId DESC
                LIMIT " . $pageBegin . "," . $pageSize . "";

        $dataProperty->AddField("State", $state);
        $result = $this->dbOperator->GetArrayList($sql, $dataProperty);


        $sql = "SELECT count(*) FROM " . self::TableName_Activity . " WHERE State=:State " . $searchSql;
        $allCount = $this->dbOperator->GetInt($sql, $dataProperty);

        return $result;
    }

    /**
     * 通过ID获取一条记录
     * @param int $activityId 活动id
     * @return array 活动数据
     */
    public function GetOne($activityId) {
        $result=-1;
        if($activityId>0){
            $sql = "SELECT t.*,t1.ActivityClassName
            FROM " . self::TableName_Activity . " t left outer join " . self::TableName_ActivityClass ." t1
            ON t.ActivityClassId=t1.ActivityClassId
            WHERE ActivityId = :ActivityId ;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("ActivityId", $activityId);
            $result = $this->dbOperator->GetArray($sql, $dataProperty);
        }
        return $result;
    }


} 