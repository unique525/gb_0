<?php

/**
 * 前台 报纸基类 生成类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_Gen_Newspaper
 * @author zhangchi
 */
class NewspaperBasePublicGen extends BasePublicGen {

    //检查当前版面是否属于免费内容
    function IsFreeReadByNewspaperArticleId($newspaperArticleId)
    {
        $result = false;
        $newspaperArticlePublicData = new NewspaperArticlePublicData();
        $newspaperPagePublicData = new NewspaperPagePublicData();
        $newspaperPageId = $newspaperArticlePublicData->GetNewspaperPageId($newspaperArticleId, true);
        $newspaperId = $newspaperPagePublicData->GetNewspaperId($newspaperPageId, true);
        if ($newspaperId > 0 && $newspaperPageId > 0) {
            $pageIndex = self::GetNewspaperPageIndex($newspaperId, $newspaperPageId);
            //前八版免费
            if($newspaperId==43)
            {
                if ($pageIndex >=0 && $pageIndex < 8) {
                    $result = true;
                }
            }
            else{
                $result = true;
            }
        }
        return $result;
    }

    //得到当前版面序号
    function GetNewspaperPageIndex($newspaperId,$newspaperPageId)
    {
        $result=-1;
        if ($newspaperId > 0 && $newspaperPageId > 0) {
            $newspaperPagePublicData = new NewspaperPagePublicData();
            $newspaperPageIdList = $newspaperPagePublicData->GetNewspaperIdList($newspaperId, true);
            if (count($newspaperPageIdList) > 0) {
                $newspaperPageIdArr = array();
                foreach($newspaperPageIdList as $columnValue) {
                    $newspaperPageIdArr[] = $columnValue["NewspaperPageId"];
                }
                $result = array_search($newspaperPageId, $newspaperPageIdArr);
            }
        }
        return $result;
    }

    //根据用户判断是否能看报纸
    function IsAuthorizedUser($userId,$newspaperArticleId)
    {
        $result=false;
        if ($userId > 0){
            //是否购买了报纸
            $newspaperArticlePublicData = new NewspaperArticlePublicData();
            $newspaperPagePublicData = new NewspaperPagePublicData();
            $newspaperPageId = $newspaperArticlePublicData->GetNewspaperPageId($newspaperArticleId, true);
            $newspaperId = $newspaperPagePublicData->GetNewspaperId($newspaperPageId, true);
            $newspaperPublicData = new NewspaperPublicData();
            $channelId=$newspaperPublicData->GetChannelId($newspaperId, true);
            $publishDate=$newspaperPublicData->GetPublishDate($newspaperId, true);
            $userOrderNewspaperPublicData = new UserOrderNewspaperPublicData();
            $isBuy = $userOrderNewspaperPublicData->CheckIsBoughtInTimeByChannelId($userId, $channelId, $publishDate);
            //可以直接免费看付费报纸
            $canExplore = parent::GetUserPopedomBoolValue(UserPopedomData::UserCanExploreMustPayNewspaper);
            //购买了报纸或有权限可以直接免费看报纸
            if ($isBuy>0 || $canExplore){
                $result=true;
            }
        }
        return $result;
    }
} 