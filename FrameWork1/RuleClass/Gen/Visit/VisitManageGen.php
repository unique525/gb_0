<?php
/**
 * 访问量统计基类 后台
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_Gen_Visit
 * @author hy
 */
class VisitManageGen extends BaseManageGen implements IBaseManageGen{

    /**
     * 引导方法
     * @return string 返回执行结果
     */
    public function Gen() {
        $result = "";
        $method = Control::GetRequest("m", "");
        switch ($method) {
            case "statistics_by_site":       //按站点统计
                $result = self::GenVisitStatisticsBySite();
                break;
            case "statistics_by_channel":       //按栏目统计
                $result = self::GenVisitStatisticsByChannel();
                break;
            case "statistics_by_document":       //按文档统计
                $result = self::GenVisitStatisticsByDocument();
                break;
            case "statistics_by_ref_domain":       //按域名来路统计
                $result = self::GenVisitStatisticsByRefDomain();
                break;

        }

        $result = str_ireplace("{method}", $method, $result);
        return $result;
    }

    private function GenVisitStatisticsBySite(){
        $manageUserId = Control::GetManageUserId();
        $siteId = Control::GetRequest("site_id", 0);
        $templateContent = Template::Load("visit/visit_statistics_of_site.html","common");
        $statisticsType = Control::GetRequest("statistics_type",0);

        if($manageUserId > 0 && $siteId > 0){
            parent::ReplaceFirst($templateContent);
            $visitManageData = new VisitManageData();


            //生成high chart图表
            //=========begin=============
            $databaseInfo = explode('|',DATABASE_INFO);
            $dataBaseName = $databaseInfo[2];

            $categories = "";
            $data = "";
            $title = "";
            $arrVisitCountOfChannel = array();
//            $totalPVCount = 0;
//            $totalUVCount = 0;
//            $totalIPCount = 0;
            if($statisticsType == VisitData::STATISTICS_BY_MONTH){
                $year = Control::GetRequest("year",2015);
                $visitCountOfEveryMonth = $visitManageData->GetVisitCountByYearAndSite($year,$siteId,$dataBaseName);
//                $arrVisitCountOfChannel = $visitManageData->GetChannelVisitCountByYearAndSite($year,$siteId,$dataBaseName);

                $categories = "['01', '02', '03', '04', '05', '06','07', '08', '09', '10', '11', '12']";
                $PVCount = "";
                $UVCount = "";
                $IPCount = "";

                for($i=0;$i<count($visitCountOfEveryMonth);$i++){
                    if($i == 0){
                        $PVCount = $visitCountOfEveryMonth[$i]["PV"];
                        $UVCount = $visitCountOfEveryMonth[$i]["UV"];
                        $IPCount = $visitCountOfEveryMonth[$i]["IP"];
                    }else{
                        $PVCount = $PVCount.",".$visitCountOfEveryMonth[$i]["PV"];
                        $UVCount = $UVCount.",".$visitCountOfEveryMonth[$i]["UV"];
                        $IPCount = $IPCount.",".$visitCountOfEveryMonth[$i]["IP"];
                    }
//                    $totalPVCount = $totalPVCount + intval($visitCountOfEveryMonth[$i]["PV"]);
//                    $totalUVCount = $totalUVCount + intval($visitCountOfEveryMonth[$i]["UV"]);
//                    $totalIPCount = $totalIPCount + intval($visitCountOfEveryMonth[$i]["IP"]);
                }
                $data = "{name: 'PV',data: [".$PVCount."]},{name:'UV',data:[".$UVCount."]},{name:'IP',data:[".$IPCount."]}";
                $title = "每月PV值";
            }else if($statisticsType == VisitData::STATISTICS_BY_DAY){
                $year = Control::GetRequest("year",2015);
                $month = Control::GetRequest("month",01);
                $visitCountOfEveryDay = $visitManageData->GetVisitCountByMonthAndSite($year,$month,$siteId);
                $arrVisitCountOfChannel = $visitManageData->GetChannelVisitCountByMonthAndSite($year,$month,$siteId);

                $innerRefDomainCount = $visitManageData->GetRefDomainCountBySiteAndMonth($year,$month,$siteId,"");
                $searchRefDomainCount = $visitManageData->GetRefDomainCountBySiteAndMonth($year,$month,$siteId,"baidu.com");
                $allRefDomainCount = $visitManageData->GetRefDomainCountBySiteAndMonth($year,$month,$siteId,"baidu.com");

                $categories = "[";
                $PVCount = "";
                $UVCount = "";
                $IPCount = "";
                for($i=0;$i<count($visitCountOfEveryDay);$i++){
                    if($i == 0){
                        $categories = $categories.$visitCountOfEveryDay[$i]["days"];
                        $PVCount = $visitCountOfEveryDay[$i]["PV"];
                        $UVCount = $visitCountOfEveryDay[$i]["UV"];
                        $IPCount = $visitCountOfEveryDay[$i]["IP"];
                    }else{
                        $categories = $categories.",".$visitCountOfEveryDay[$i]["days"];
                        $PVCount = $PVCount.",".$visitCountOfEveryDay[$i]["PV"];
                        $UVCount = $UVCount.",".$visitCountOfEveryDay[$i]["UV"];
                        $IPCount = $IPCount.",".$visitCountOfEveryDay[$i]["IP"];
                    }
//                    $totalPVCount = $totalPVCount + intval($visitCountOfEveryDay[$i]["PV"]);
//                    $totalUVCount = $totalUVCount + intval($visitCountOfEveryDay[$i]["UV"]);
//                    $totalIPCount = $totalIPCount + intval($visitCountOfEveryDay[$i]["IP"]);
                }
                $categories = $categories."]";
                $data = "{name: 'PV',data: [".$PVCount."]},{name:'UV',data:[".$UVCount."]},{name:'IP',data:[".$IPCount."]}";
                $title = "每日PV值";
            }else if($statisticsType == VisitData::STATISTICS_BY_HOUR){
                $year = Control::GetRequest("year",2015);
                $month = Control::GetRequest("month",01);
                $day = Control::GetRequest("day",01);
                $visitCountOfEveryHour = $visitManageData->GetVisitCountByHoursAndSite($year,$month,$day,$siteId,$dataBaseName);
                $arrVisitCountOfChannel = $visitManageData->GetChannelVisitCountByDayAndSite($year,$month,$day,$siteId);

                $categories = "[";
                $PVCount = "";
                $UVCount = "";
                $IPCount = "";
                for($i=0;$i<count($visitCountOfEveryHour);$i++){
                    if($i == 0){
                        $categories = $categories.$visitCountOfEveryHour[$i]["hours"];
                        $PVCount = $visitCountOfEveryHour[$i]["PV"];
                        $UVCount = $visitCountOfEveryHour[$i]["UV"];
                        $IPCount = $visitCountOfEveryHour[$i]["IP"];
                    }else{
                        $categories = $categories.",".$visitCountOfEveryHour[$i]["hours"];
                        $PVCount = $PVCount.",".$visitCountOfEveryHour[$i]["PV"];
                        $UVCount = $UVCount.",".$visitCountOfEveryHour[$i]["UV"];
                        $IPCount = $UVCount.",".$visitCountOfEveryHour[$i]["IP"];
                    }
//                    $totalPVCount = $totalPVCount + intval($visitCountOfEveryHour[$i]["PV"]);
//                    $totalUVCount = $totalUVCount + intval($visitCountOfEveryHour[$i]["UV"]);
//                    $totalIPCount = $totalIPCount + intval($visitCountOfEveryHour[$i]["IP"]);
                }
                $categories = $categories."]";
                $data = "{name: 'PV',data: [".$PVCount."]},{name:'UV',data:[".$UVCount."]},{name:'IP',data:[".$IPCount."]}";
                $title = "每小时PV值";
            }

            $templateContent = str_ireplace("{categories}", $categories, $templateContent);
            $templateContent = str_ireplace("{data}", $data, $templateContent);
            $templateContent = str_ireplace("{title}", $title, $templateContent);
//            $templateContent = str_ireplace("{TotalPVCount}", $totalPVCount, $templateContent);
//            $templateContent = str_ireplace("{TotalUVCount}", $totalUVCount, $templateContent);
//            $templateContent = str_ireplace("{TotalIPCount}", $totalIPCount, $templateContent);
            //============end==============

            //替换表格
            //=========begin==========
            $tagId = "visit_count_of_channel";

            if(count($arrVisitCountOfChannel) > 0){
                Template::ReplaceList($templateContent,$arrVisitCountOfChannel,$tagId);
            }else{
                Template::RemoveCustomTag($templateContent,$tagId);
            }

            //=========end===========

            parent::ReplaceEnd($templateContent);
        }
        return $templateContent;
    }

    private function GenVisitStatisticsByChannel(){
        $manageUserId = Control::GetManageUserId();
        $channelId = Control::GetRequest("channel_id", 0);
        $templateContent = Template::Load("visit/visit_statistics_of_channel.html","common");
        $statisticsType = Control::GetRequest("statistics_type",0);

        parent::ReplaceFirst($templateContent);
        if($manageUserId > 0 && $channelId > 0 ){
            $visitManageData = new VisitManageData();


            //生成high chart图表
            //=========begin=============
            $databaseInfo = explode('|',DATABASE_INFO);
            $dataBaseName = $databaseInfo[2];

            $categories = "";
            $data = "";
            $title = "";
            $arrVisitCountOfChannelList = array();
//            $totalPVCount = 0;
//            $totalUVCount = 0;
//            $totalIPCount = 0;
            if($statisticsType == VisitData::STATISTICS_BY_MONTH){
                $year = Control::GetRequest("year",2015);
                $visitCountOfEveryMonth = $visitManageData->GetVisitCountByMonthsAndChannel($year,$channelId,$dataBaseName);

                $categories = "['01', '02', '03', '04', '05', '06','07', '08', '09', '10', '11', '12']";
                $PVCount = "";
                $UVCount = "";
                $IPCount = "";

                for($i=0;$i<count($visitCountOfEveryMonth);$i++){
                    if($i == 0){
                        $PVCount = $visitCountOfEveryMonth[$i]["PV"];
                        $UVCount = $visitCountOfEveryMonth[$i]["UV"];
                        $IPCount = $visitCountOfEveryMonth[$i]["IP"];
                    }else{
                        $PVCount = $PVCount.",".$visitCountOfEveryMonth[$i]["PV"];
                        $UVCount = $UVCount.",".$visitCountOfEveryMonth[$i]["UV"];
                        $IPCount = $IPCount.",".$visitCountOfEveryMonth[$i]["IP"];
                    }
//                    $totalPVCount = $totalPVCount + intval($visitCountOfEveryMonth[$i]["PV"]);
//                    $totalUVCount = $totalUVCount + intval($visitCountOfEveryMonth[$i]["UV"]);
//                    $totalIPCount = $totalIPCount + intval($visitCountOfEveryMonth[$i]["IP"]);
                }
                $data = "{name: 'PV',data: [".$PVCount."]},{name:'UV',data:[".$UVCount."]},{name:'IP',data:[".$IPCount."]}";
                $title = "每月PV值";
            }else if($statisticsType == VisitData::STATISTICS_BY_DAY){
                $year = Control::GetRequest("year",2015);
                $month = Control::GetRequest("month",01);
                $visitCountOfEveryDay = $visitManageData->GetVisitCountByDaysAndChannel($year,$month,$channelId,$dataBaseName);
                $arrVisitCountOfChannelList = $visitManageData->GetDocumentVisitCountByMonthAndChannel($year,$month,$channelId);

                $categories = "[";
                $PVCount = "";
                $UVCount = "";
                $IPCount = "";
                for($i=0;$i<count($visitCountOfEveryDay);$i++){
                    if($i == 0){
                        $categories = $categories.$visitCountOfEveryDay[$i]["days"];
                        $PVCount = $visitCountOfEveryDay[$i]["PV"];
                        $UVCount = $visitCountOfEveryDay[$i]["UV"];
                        $IPCount = $visitCountOfEveryDay[$i]["IP"];
                    }else{
                        $categories = $categories.",".$visitCountOfEveryDay[$i]["days"];
                        $PVCount = $PVCount.",".$visitCountOfEveryDay[$i]["PV"];
                        $UVCount = $UVCount.",".$visitCountOfEveryDay[$i]["UV"];
                        $IPCount = $IPCount.",".$visitCountOfEveryDay[$i]["IP"];
                    }
//                    $totalPVCount = $totalPVCount + intval($visitCountOfEveryDay[$i]["PV"]);
//                    $totalUVCount = $totalUVCount + intval($visitCountOfEveryDay[$i]["UV"]);
//                    $totalIPCount = $totalIPCount + intval($visitCountOfEveryDay[$i]["IP"]);
                }
                $categories = $categories."]";
                $data = "{name: 'PV',data: [".$PVCount."]},{name:'UV',data:[".$UVCount."]},{name:'IP',data:[".$IPCount."]}";
                $title = "每日PV值";
            }else if($statisticsType == VisitData::STATISTICS_BY_HOUR){
                $year = Control::GetRequest("year",2015);
                $month = Control::GetRequest("month",01);
                $day = Control::GetRequest("day",01);
                $visitCountOfEveryHour = $visitManageData->GetVisitCountByHoursAndChannel($year,$month,$day,$channelId,$dataBaseName);
                $arrVisitCountOfChannelList = $visitManageData->GetDocumentVisitCountByDayAndChannel($year,$month,$day,$channelId);

                $categories = "[";
                $PVCount = "";
                $UVCount = "";
                $IPCount = "";
                for($i=0;$i<count($visitCountOfEveryHour);$i++){
                    if($i == 0){
                        $categories = $categories.$visitCountOfEveryHour[$i]["hours"];
                        $PVCount = $visitCountOfEveryHour[$i]["PV"];
                        $UVCount = $visitCountOfEveryHour[$i]["UV"];
                        $IPCount = $visitCountOfEveryHour[$i]["IP"];
                    }else{
                        $categories = $categories.",".$visitCountOfEveryHour[$i]["hours"];
                        $PVCount = $PVCount.",".$visitCountOfEveryHour[$i]["PV"];
                        $UVCount = $UVCount.",".$visitCountOfEveryHour[$i]["UV"];
                        $IPCount = $UVCount.",".$visitCountOfEveryHour[$i]["IP"];
                    }
//                    $totalPVCount = $totalPVCount + intval($visitCountOfEveryHour[$i]["PV"]);
//                    $totalUVCount = $totalUVCount + intval($visitCountOfEveryHour[$i]["UV"]);
//                    $totalIPCount = $totalIPCount + intval($visitCountOfEveryHour[$i]["IP"]);
                }
                $categories = $categories."]";
                $data = "{name: 'PV',data: [".$PVCount."]},{name:'UV',data:[".$UVCount."]},{name:'IP',data:[".$IPCount."]}";
                $title = "每小时PV值";
            }

            $templateContent = str_ireplace("{categories}", $categories, $templateContent);
            $templateContent = str_ireplace("{data}", $data, $templateContent);
            $templateContent = str_ireplace("{title}", $title, $templateContent);
//            $templateContent = str_ireplace("{TotalPVCount}", $totalPVCount, $templateContent);
//            $templateContent = str_ireplace("{TotalUVCount}", $totalUVCount, $templateContent);
//            $templateContent = str_ireplace("{TotalIPCount}", $totalIPCount, $templateContent);
            //============end==============

            //替换表格
            //=========begin==========
            $tagId = "visit_count_of_document";

            if(count($arrVisitCountOfChannelList) > 0){
                Template::ReplaceList($templateContent,$arrVisitCountOfChannelList,$tagId);
            }else{
                Template::RemoveCustomTag($templateContent,$tagId);
            }

            //=========end===========

            parent::ReplaceEnd($templateContent);
        }
        return $templateContent;
    }

    private function GenVisitStatisticsByDocument(){
        $templateContent = Template::Load("","common");

        return "";
    }

    private function GenVisitStatisticsByRefDomain(){
        $templateContent = Template::Load("visit/visit_statistics_of_ref_domain.html","common");
        parent::ReplaceFirst($templateContent);

        parent::ReplaceEnd($templateContent);
        return $templateContent;
    }
}