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
            PublishDate
            ';

            $sql = "SELECT $selectColumn FROM " . self::TableName_DocumentNews . "
                WHERE ChannelId=:ChannelId AND State=:State
                ORDER BY $orderBySql LIMIT " . $topCount;
            $dataProperty = new DataProperty();
            $dataProperty->AddField("ChannelId", $channelId);
            $dataProperty->AddField("State", $state);
            $result = $this->dbOperator->GetArrayList($sql, $dataProperty);



        }




        return $result;
    }
}

?>
