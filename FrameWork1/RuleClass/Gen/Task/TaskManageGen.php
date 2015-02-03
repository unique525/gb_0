<?php
/**
 * 后台管理 任务管理类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_Gen_Newspaper
 * @author zhangchi
 */
class TaskManageGen extends BaseManageGen {
    /**
     * 引导方法
     * @return string 返回执行结果
     */
    public function Gen()
    {
        $result = "";
        $method = Control::GetRequest("m", "");
        switch ($method) {
            case "statistic_document_of_manage_user_group":
                $result = self::GenStatisticDocumentOfManageUserGroup();
                break;
        }
        $result = str_ireplace("{method}", $method, $result);
        return $result;
    }

    /**
     * 统计管理员文档与点击数
     */
    public function GenStatisticDocumentOfManageUserGroup(){
        $result="";
        $resultJavaScript="";
        $manageUserId=Control::GetManageUserId();
        $tabIndex=Control::GetRequest("tab_index",0);

        //判断权限
        /**********************************************************************
         ******************************判断是否有操作权限**********************
         **********************************************************************/
        $manageUserAuthorityManageData = new ManageUserAuthorityManageData();
        $siteId = 0;
        $channelId = 0;
        $can = $manageUserAuthorityManageData->CanManageUserGroupExplore($siteId, $channelId, $manageUserId);
        if (!$can) {
            $result = -10;
        }else{


            $statisticGroupId=Control::PostRequest("ManageUserGroupId",-1);//0为所有
            $siteId=Control::PostRequest("SiteId",-1);//0为所有
            $beginDate=Control::PostRequest("BeginDate","");
            $endDate=Control::PostRequest("EndDate","");
            $tempContent=Template::Load("task/statistic_document.html","common");
            parent::ReplaceFirst($tempContent);

            $siteManageData=New SiteManageData();
            $arraySite=$siteManageData->GetListForSelect($manageUserId);
            $listNameOfSite="site_list";
            Template::ReplaceList($tempContent,$arraySite,$listNameOfSite);


            $manageUserGroupManageData=New ManageUserGroupManageData();
            $pageBegin=0;
            $pageSize=-1;
            $allCount=0;
            $arrayGroup=$manageUserGroupManageData->GetList($pageBegin,$pageSize,$allCount,"","");
            $listNameOfGroup="manage_user_group_list";
            Template::ReplaceList($tempContent,$arrayGroup,$listNameOfGroup);

            if($siteId>=0 && $statisticGroupId>=0){
                if($beginDate!=""&&$endDate!=""){
                    $strSiteIds="";
                    foreach($arraySite as $site){
                        $strSiteIds.=",".$site["SiteId"];
                    }
                    $strSiteIds=substr($strSiteIds,1);
                    $manageUserManageData=new ManageUserManageData();
                    $arrayManageUser=$manageUserManageData->GetList($pageBegin,$pageSize,$allCount,$searchKey="",$searchType=0,$statisticGroupId);

                    $documentNewsManageData=new DocumentNewsManageData();
                    for($i=0;$i<count($arrayManageUser);$i++){
                        $arrDocumentNews=$documentNewsManageData->GetListOfManageUser($siteId,$strSiteIds,$arrayManageUser[$i]["ManageUserId"],$beginDate,$endDate);
                        //$hitCount=0;
                        $publishCount=0;
                        foreach($arrDocumentNews as $documentNews){
                        //    $hitCount+=$documentNews["hit"];
                            if($documentNews["State"]==30){
                                $publishCount++;
                            }
                        }
                        $arrayManageUser[$i]["DocumentNewsCount"]=count($arrDocumentNews);
                        $arrayManageUser[$i]["PublishCount"]=$publishCount;
                        //$arrayManageUser[$i]["HitCount"]=$hitCount;
                    }
                    $listNameOfStatistician="statistician_result_list";
                    Template::ReplaceList($tempContent,$arrayManageUser,$listNameOfStatistician);

                }else{
                    //日期错误
                }
            }else{
                //站点或组参数错误
            }





            $listNameOfStatistician="statistician_result_list";
            Template::RemoveCustomTag($tempContent, $listNameOfStatistician);
            $tempContent = str_ireplace("{TabIndex}", $tabIndex, $tempContent);
            $tempContent = str_ireplace("{SiteId}", $siteId, $tempContent);
            $tempContent = str_ireplace("{ManageUserGroupId}", $statisticGroupId, $tempContent);
            $tempContent = str_ireplace("{BeginDate}", $beginDate, $tempContent);
            $tempContent = str_ireplace("{EndDate}", $endDate, $tempContent);
            parent::ReplaceEnd($tempContent);
            $tempContent = str_ireplace("{ResultJavascript}", $resultJavaScript, $tempContent);
            $result=$tempContent;
        }


        return $result;
    }
} 