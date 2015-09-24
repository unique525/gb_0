<?php
/**
 * 客户端 活动 数据类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_Activity
 * @author zhangchi
 */
class ActivityClientData extends BaseClientData {

    /**
     * 取得分页显示的活动列表
     * @param int $channelId 频道id
     * @param string $timeState 活动所处时间状态类型，all全部，inTime进行中，end已结束
     * @param int $pageBegin 记录开始位置
     * @param int $pageSize 显示数量
     * @return array 分页显示的列表
     */
    public function GetList(
        $channelId,
        $timeState,
        $pageBegin,
        $pageSize
    ) {
        if ($timeState == "end") { //已结束活动
            $timeConditionSql = " AND EndDate<NOW() ";
        }
        else if($timeState == "inTime"){//正进行中的活动
            $timeConditionSql = " AND EndDate>=NOW() ";
        }
        else $timeConditionSql="";//所有活动
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
                    LEFT OUTER JOIN " .self::TableName_Channel." c ON dn.ChannelId = c.ChannelId
                    LEFT OUTER JOIN " .self::TableName_UploadFile." uf1 ON dn.TitlePic1UploadFileId=uf1.UploadFileId
                    LEFT OUTER JOIN " .self::TableName_UploadFile." uf2 ON dn.TitlePic2UploadFileId=uf2.UploadFileId
                    LEFT OUTER JOIN " .self::TableName_UploadFile." uf3 ON dn.TitlePic3UploadFileId=uf3.UploadFileId

                WHERE dn.ChannelId=:ChannelId AND dn.State<100 AND dn.ShowInClient=1 " . $timeConditionSql . "
                ORDER BY dn.Sort DESC, dn.ActivityId DESC
                LIMIT " . $pageBegin . "," . $pageSize . "";

        $dataProperty = new DataProperty();
        $dataProperty->AddField("ChannelId", $channelId);

        $result = $this->dbOperator->GetArrayList($sql, $dataProperty);

        return $result;
    }

    /**
     * 取得分页显示的会员参加的活动列表
     * @param int $userId 会员id
     * @param int $pageBegin 记录开始位置
     * @param int $pageSize 显示数量
     * @return array 分页显示的列表
     */
    public function GetListOfUser(
        $userId,
        $pageBegin,
        $pageSize
    ) {

        $selectColumn = '
            a.*,

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
            uf3.UploadFileCutPath1 AS TitlePic3UploadFileCutPath1


            ';

        $sql = "SELECT $selectColumn FROM " . self::TableName_Activity . "  a
                    INNER JOIN " .self::TableName_ActivityUser." au ON a.ActivityId = au.ActivityId
                    LEFT OUTER JOIN " .self::TableName_UploadFile." uf1 ON a.TitlePic1UploadFileId=uf1.UploadFileId
                    LEFT OUTER JOIN " .self::TableName_UploadFile." uf2 ON a.TitlePic2UploadFileId=uf2.UploadFileId
                    LEFT OUTER JOIN " .self::TableName_UploadFile." uf3 ON a.TitlePic3UploadFileId=uf3.UploadFileId

                WHERE au.UserId=:UserId AND a.State<100
                    AND a.ShowInClient=1
                ORDER BY a.Sort DESC, a.ActivityId DESC
                LIMIT " . $pageBegin . "," . $pageSize . "";

        $dataProperty = new DataProperty();
        $dataProperty->AddField("UserId", $userId);

        $result = $this->dbOperator->GetArrayList($sql, $dataProperty);

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
            $sql = "SELECT t.*,t1.ActivityClassName,


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
                        uf3.UploadFileCutPath1 AS TitlePic3UploadFileCutPath1


            FROM " . self::TableName_Activity . " t
            LEFT OUTER JOIN " . self::TableName_ActivityClass ." t1 ON t.ActivityClassId=t1.ActivityClassId

            LEFT OUTER JOIN " .self::TableName_UploadFile." uf1 ON (t.TitlePic1UploadFileId=uf1.UploadFileId)
            LEFT OUTER JOIN " .self::TableName_UploadFile." uf2 ON (t.TitlePic2UploadFileId=uf2.UploadFileId)
            LEFT OUTER JOIN " .self::TableName_UploadFile." uf3 ON (t.TitlePic3UploadFileId=uf3.UploadFileId)


            WHERE ActivityId = :ActivityId ;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("ActivityId", $activityId);
            $result = $this->dbOperator->GetArray($sql, $dataProperty);
        }
        return $result;
    }
} 