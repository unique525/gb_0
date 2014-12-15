<?php

/**
 * 后台 统计 生成类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_Gen_UploadFile
 * @author zhangchi
 */
class StatisticsManageGen extends BaseManageGen implements IBaseManageGen
{
    public function Gen(){
        $result = "";
        $method = Control::GetRequest("m","");
        switch($method){
            case "statistics":
               $result = self::GenStatistics();
                break;
            case "async_get_statistics_data":
                $result = self::AsyncGetStatisticsData();
                break;
        }
        return $result;
    }

    private function GenStatistics(){
        $module = Control::GetRequest("module","");
        $style = Control::GetRequest("style","");

        $tempContent = "";
        if($module != ""){
            if($style == ""){
                $tempContent = Template::Load("statistics/".$module."_statistics.html","common");
            }else{
                $tempContent = Template::Load("statistics/".$module."_statistics_".$style.".html","common");
            }
        }
        return $tempContent;
    }

    private function AsyncGetStatisticsData(){
        $module = Control::GetRequest("module","");

        $result = "";
        switch($module){
            case "visit":
                $result = self::GetVisitStatisticsData();
                break;
            case "user_order":
                $result = self::GetUserOrderStatisticsData();
                break;
        }
        self::FormatData($result);
        return $result;
    }

    private function GetVisitStatisticsData(){
        $groupBy = Control::GetRequest("group_by","");


        return "";
    }

    private function GetUserOrderStatisticsData(){
        return "";
    }

    private function FormatData(){}
}