<?php

/**
 * 前台 报纸客户端基类 生成类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_Gen_Newspaper
 * @author zhangchi
 */
class NewspaperBaseClientGen extends BaseClientGen
{

    //检查当前版面是否属于免费内容
    function IsFreeReadByNewspaperArticleId($newspaperArticleId)
    {
        $result = false;
        $newspaperArticlePublicData = new NewspaperArticlePublicData();
        $newspaperPagePublicData = new NewspaperPagePublicData();
        $newspaperPublicData = new NewspaperPublicData();
        $newspaperArticleTitle = $newspaperArticlePublicData->GetNewspaperArticleTitle($newspaperArticleId, true);
        $newspaperArticleType = $newspaperArticlePublicData->GetNewspaperArticleType($newspaperArticleId, true);
        $newspaperPageId = $newspaperArticlePublicData->GetNewspaperPageId($newspaperArticleId, true);
        $newspaperPageName = $newspaperPagePublicData->GetNewspaperPageName($newspaperPageId, true);
        $newspaperId = $newspaperPagePublicData->GetNewspaperId($newspaperPageId, true);
        //如果文章属于广告类别或者文章所属版面名称带了广告两字或文章标题带有广告两字可以免费查看
        if ($newspaperArticleType == 2
            || (!empty($newspaperPageName) && strpos($newspaperPageName, "广告") !== false)
            || (!empty($newspaperArticleTitle) && strpos($newspaperArticleTitle, "广告") !== false)
        ) {
            $result = true;
        }
        else if ($newspaperId > 0 && $newspaperPageId > 0) { //当日报纸免费
            $publishDate = $newspaperPublicData->GetPublishDate($newspaperId, true);
            $publishDate = date("Y-m-d", strtotime($publishDate));
            $nowDate = date("Y-m-d", time());
            if ($publishDate == $nowDate) {
                $result = true;
            }
        }
//        else if ($newspaperId > 0 && $newspaperPageId > 0) { //当日报纸前八版免费
//            $pageIndex = self::GetNewspaperPageIndex($newspaperId, $newspaperPageId);
//            $publishDate = $newspaperPublicData->GetPublishDate($newspaperId, true);
//            $publishDate = date("Y-m-d", strtotime($publishDate));
//            $nowDate = date("Y-m-d", time());
//            if ($publishDate == $nowDate && $pageIndex >= 0 && $pageIndex < 8) {
//                $result = true;
//            }
//        }
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
    function IsAuthorizedUser($siteId,$userId,$newspaperArticleId)
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
            $canExplore = parent::GetUserPopedomBoolValue($siteId,$userId,UserPopedomData::UserCanExploreMustPayNewspaper);
            //购买了报纸或有权限可以直接免费看报纸
            if ($isBuy>0 || $canExplore){
                $result=true;
            }
        }
        return $result;
    }
} 