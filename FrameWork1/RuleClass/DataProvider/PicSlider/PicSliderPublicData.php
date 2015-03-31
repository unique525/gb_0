<?php

/**
 * 前台 图片轮换 数据类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_PicSlider
 * @author zhangchi
 */
class PicSliderPublicData extends BasePublicData {

    /**
     * 取得图片轮换列表
     * @param int $channelId 频道id
     * @param string $topCount 显示条数  1或 1,10
     * @param int $state 状态
     * @param int $orderBy 排序方式
     * @return array|null 返回图片轮换列表
     */
    public function GetList($channelId, $topCount, $state, $orderBy = 0) {

        $result = null;

        if($channelId>0 && !empty($topCount)){
            $orderBySql = 'ps.Sort DESC, ps.CreateDate DESC';
            switch($orderBy){
                case 0:
                    $orderBySql = 'ps.Sort DESC, ps.CreateDate DESC';
                    break;
            }

            $selectColumn = '
                ps.PicSliderId,
                ps.ChannelId,
                ps.PicSliderTitle,
                ps.DirectUrl,
                ps.TableType,
                ps.TableId,
                uf.UploadFilePath,
                uf.UploadFileMobilePath,
                uf.UploadFilePadPath,
                uf.UploadFileThumbPath1,
                uf.UploadFileThumbPath2,
                uf.UploadFileThumbPath3,
                uf.UploadFileWatermarkPath1,
                uf.UploadFileWatermarkPath2,
                uf.UploadFileCompressPath1,
                uf.UploadFileCompressPath2
            ';

            $sql = "SELECT $selectColumn FROM " . self::TableName_PicSlider . " ps,". self::TableName_UploadFile ." uf
                WHERE
                    ps.ChannelId=:ChannelId
                    AND ps.State=:State
                    AND ps.UploadFileId=uf.UploadFileId
                ORDER BY $orderBySql LIMIT " . $topCount;
            $dataProperty = new DataProperty();
            $dataProperty->AddField("ChannelId", $channelId);
            $dataProperty->AddField("State", $state);
            $result = $this->dbOperator->GetArrayList($sql, $dataProperty);
        }

        return $result;
    }
} 