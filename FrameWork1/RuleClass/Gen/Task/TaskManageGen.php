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
            case "statistic_document_of_manage_user":
                $result = self::GenStatisticDocumentOfManageUser();
                break;
            case "statistic_document_of_manage_user_group":
                $result = self::GenStatisticDocumentOfManageUserGroup();
                break;
            case "statistic_newspaper_hit_rank";
                $result = self::GenStatisticNewspaperHitRank();
                break;
        }
        $result = str_ireplace("{method}", $method, $result);
        return $result;
    }

    /**
     * 统计管理员文档与点击数
     */
    public function GenStatisticDocumentOfManageUser(){
        $result="";
        $resultJavaScript="";
        $manageUserId=Control::GetRequest("manage_user_id",0);
        $currentManageUserId=Control::GetManageUserId();
        $tabIndex=Control::GetRequest("tab_index",0);

        $manageUserManageData=new ManageUserManageData();
        if($manageUserId<=0){
            $manageUserId=$currentManageUserId;
        }

        if($manageUserId!=$currentManageUserId){
            //判断权限
            /**********************************************************************
             ******************************判断是否有操作权限**********************
             **********************************************************************/
            $manageUserAuthorityManageData = new ManageUserAuthorityManageData();
            $siteId = 0;
            $channelId = 0;

            //是否有查看所有用户的权限
            $can = $manageUserAuthorityManageData->CanManageUserTaskViewAll($siteId, $channelId, $manageUserId);
            if (!$can) {
                //是否有查看同一个组的权限
                if($manageUserManageData->GetManageUserGroupId($currentManageUserId,true)==
                    $manageUserManageData->GetManageUserGroupId($manageUserId,true)){
                    $can = $manageUserAuthorityManageData->CanManageUserTaskViewSameGroup($siteId, $channelId, $manageUserId);
                    if (!$can) {
                        $result = -10;
                    }
                }
            }

        }

        if($manageUserId>0){
            $siteId=Control::PostRequest("SiteId",-1);//0为所有
            $beginDate=Control::PostRequest("BeginDate","");
            $endDate=Control::PostRequest("EndDate","");
            $tempContent=Template::Load("task/statistic_document.html","common");
            parent::ReplaceFirst($tempContent);

            $propertyOfManageUser=$manageUserManageData->GetOne($manageUserId);

            $siteManageData=New SiteManageData();
            $arraySite=$siteManageData->GetListForSelect($manageUserId);
            $listNameOfSite="site_list";
            Template::ReplaceList($tempContent,$arraySite,$listNameOfSite);


            $manageUserGroupManageData=New ManageUserGroupManageData();
            $arrayGroup=$manageUserGroupManageData->GetOne($propertyOfManageUser["ManageUserGroupId"]);
            $arrayGroupList[0]=$arrayGroup;  //转为只有一个元素的用户组数组  兼容用户组的template
            $listNameOfGroup="manage_user_group_list";
            Template::ReplaceList($tempContent,$arrayGroupList,$listNameOfGroup);


            if($siteId>=0){
                if($beginDate!=""&&$endDate!=""){
                    $strSiteIds="";
                    foreach($arraySite as $site){
                        $strSiteIds.=",".$site["SiteId"];
                    }
                    $strSiteIds=substr($strSiteIds,1);

                    $arrayOfResult=$propertyOfManageUser;

                    $documentNewsManageData=new DocumentNewsManageData();
                        $arrDocumentNews=$documentNewsManageData->GetListOfManageUser($siteId,$strSiteIds,$manageUserId,$beginDate,$endDate);
                        $hitCount=0;
                        $publishCount=0;
                        $documentNewsList="";
                        foreach($arrDocumentNews as $documentNews){
                            $hitCount+=$documentNews["Hit"];
                            if($documentNews["State"]==30){
                                $publishCount++;
                                $documentNewsList.="<li>点击：".$documentNews["Hit"]."，标题：".$documentNews["DocumentNewsTitle"]."</li>";
                            }
                        }
                    $arrayOfResult["DocumentNewsCount"]=count($arrDocumentNews);
                    $arrayOfResult["PublishCount"]=$publishCount;
                    $arrayOfResult["HitCount"]=$hitCount;
                    $arrayOfResult["DocumentNewsList"]=$documentNewsList;

                    $arrayList[0]=$arrayOfResult;  //转为只有一个元素的用户组数组  兼容用户组的template
                    $listNameOfStatistician="statistician_result_list";
                    Template::ReplaceList($tempContent,$arrayList,$listNameOfStatistician);
                    $tempContent = str_ireplace("{display}", "inline", $tempContent);

                }else{
                    //$resultJavaScript .= Control::GetJqueryMessage(Language::Load('task', 3));//日期错误
                }
            }else{
                //$resultJavaScript .= Control::GetJqueryMessage(Language::Load('task', 4)); //站点或组参数错误
            }





            $listNameOfStatistician="statistician_result_list";
            Template::RemoveCustomTag($tempContent, $listNameOfStatistician);
            $tempContent = str_ireplace("{TabIndex}", $tabIndex, $tempContent);
            $tempContent = str_ireplace("{ManageUserGroupId}", $propertyOfManageUser["ManageUserGroupId"], $tempContent);
            $tempContent = str_ireplace("{SiteId}", $siteId, $tempContent);
            $tempContent = str_ireplace("{BeginDate}", $beginDate, $tempContent);
            $tempContent = str_ireplace("{EndDate}", $endDate, $tempContent);
            $tempContent = str_ireplace("{display}", "none", $tempContent);
            $tempContent = str_ireplace("{DisplayManageUserGroup}", "none", $tempContent);
            parent::ReplaceEnd($tempContent);
            $tempContent = str_ireplace("{ResultJavascript}", $resultJavaScript, $tempContent);
            $result=$tempContent;
        }


        return $result;
    }



    /**
     * 统计组内所有管理员文档与点击数
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

                    //组内总量
                    $arrayAll["ManageUserName"]="合计";
                    $arrayAll["DocumentNewsCount"]=0;
                    $arrayAll["PublishCount"]=0;
                    $arrayAll["HitCount"]=0;
                    $arrayAll["DocumentNewsList"]="";

                    for($i=0;$i<count($arrayManageUser);$i++){
                        $arrDocumentNews=$documentNewsManageData->GetListOfManageUser($siteId,$strSiteIds,$arrayManageUser[$i]["ManageUserId"],$beginDate,$endDate);
                        $hitCount=0;
                        $publishCount=0;
                        $documentNewsList="";
                        foreach($arrDocumentNews as $documentNews){
                            $hitCount+=$documentNews["Hit"];
                            if($documentNews["State"]==30){
                                $publishCount++;
                                $documentNewsList.="<li>点击：".$documentNews["Hit"]."，标题：".$documentNews["DocumentNewsTitle"]."</li>";
                            }
                        }
                        $arrayManageUser[$i]["DocumentNewsCount"]=count($arrDocumentNews);
                        $arrayManageUser[$i]["PublishCount"]=$publishCount;
                        $arrayManageUser[$i]["HitCount"]=$hitCount;
                        $arrayManageUser[$i]["DocumentNewsList"]=$documentNewsList;

                        //处理组内总量
                        $arrayAll["DocumentNewsCount"]+=count($arrDocumentNews);
                        $arrayAll["PublishCount"]+=$publishCount;
                        $arrayAll["HitCount"]+=$hitCount;
                    }
                    $arrayManageUser[]=$arrayAll;
                    $listNameOfStatistician="statistician_result_list";
                    Template::ReplaceList($tempContent,$arrayManageUser,$listNameOfStatistician);
                    $tempContent = str_ireplace("{display}", "inline", $tempContent);

                }else{
                    //$resultJavaScript .= Control::GetJqueryMessage(Language::Load('task', 3));//日期错误
                }
            }else{
                //$resultJavaScript .= Control::GetJqueryMessage(Language::Load('task', 4)); //站点或组参数错误
            }





            $listNameOfStatistician="statistician_result_list";
            Template::RemoveCustomTag($tempContent, $listNameOfStatistician);
            $tempContent = str_ireplace("{TabIndex}", $tabIndex, $tempContent);
            $tempContent = str_ireplace("{SiteId}", $siteId, $tempContent);
            $tempContent = str_ireplace("{ManageUserGroupId}", $statisticGroupId, $tempContent);
            $tempContent = str_ireplace("{BeginDate}", $beginDate, $tempContent);
            $tempContent = str_ireplace("{EndDate}", $endDate, $tempContent);
            $tempContent = str_ireplace("{display}", "none", $tempContent);
            $tempContent = str_ireplace("{DisplayManageUserGroup}", "inline", $tempContent);
            parent::ReplaceEnd($tempContent);
            $tempContent = str_ireplace("{ResultJavascript}", $resultJavaScript, $tempContent);
            $result=$tempContent;
        }


        return $result;
    }

    public function GenStatisticNewspaperHitRank(){
        $result="";
        $resultJavaScript="";
        $resultText="";
        $manageUserId=Control::GetManageUserId();
        $tabIndex=Control::GetRequest("tab_index",0);


            $siteId=Control::PostRequest("SiteId",-1);//0为所有
            $beginDate=Control::PostRequest("BeginDate","");
            $endDate=Control::PostRequest("EndDate","");
        $count=Control::PostRequest("Count","20");
            $tempContent=Template::Load("task/statistic_newspaper.html","common");
            parent::ReplaceFirst($tempContent);

            $siteManageData=New SiteManageData();
            $arraySite=$siteManageData->GetListForSelect($manageUserId);
            $listNameOfSite="site_list";
            Template::ReplaceList($tempContent,$arraySite,$listNameOfSite);

            if($siteId>=0){
                if($beginDate!=""&&$endDate!=""){
                    $strSiteIds="";
                    foreach($arraySite as $site){
                        $strSiteIds.=",".$site["SiteId"];
                    }

                    $newspaperArticleManageData=new NewspaperArticleManageData();
                    $arrayNewspaper=$newspaperArticleManageData->GetListByHit($siteId,$strSiteIds,$count,$beginDate,$endDate);

                    $listNameOfStatistician="newspaper_article_list";
                    Template::ReplaceList($tempContent,$arrayNewspaper,$listNameOfStatistician);

                }else{

                    //$resultText .= Language::Load('task', 3); //请选择日期
            }
            }else{

                //$resultText .= Language::Load('task', 4); //请选择站点或组
            }





            $listNameOfStatistician="newspaper_article_list";
            Template::ReplaceCustomTag($tempContent, $listNameOfStatistician,$resultText);
            $tempContent = str_ireplace("{TabIndex}", $tabIndex, $tempContent);
            $tempContent = str_ireplace("{SiteId}", $siteId, $tempContent);
            $tempContent = str_ireplace("{BeginDate}", $beginDate, $tempContent);
            $tempContent = str_ireplace("{EndDate}", $endDate, $tempContent);
            parent::ReplaceEnd($tempContent);
            $tempContent = str_ireplace("{ResultJavascript}", $resultJavaScript, $tempContent);
            $result=$tempContent;


        return $result;
    }
} 