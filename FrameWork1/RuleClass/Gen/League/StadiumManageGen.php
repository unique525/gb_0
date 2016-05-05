<?php
/**
 * Created by PhpStorm.
 * User: 525
 * Date: 2016/3/16
 * Time: 21:56
 */
class StadiumManageGen extends BaseManageGen implements IBaseManageGen
{

    /**
     * 引导方法
     * @return string 返回执行结果
     */
    public function Gen()
    {
        $result = "";
        $method = Control::GetRequest("m", "");
        switch ($method) {
            case "async_get_id_by_name":
                $result = self::AsyncGetIdByName();
                break;
            case "async_get_json_with_ids":
                $result = self::AsyncGetJsonWithIds();
                break;
        }

        $result = str_ireplace("{method}", $method, $result);
        return $result;
    }


    private function AsyncGetJsonWithIds(){
        $result="-1";
        $data="";
        $siteId=Control::GetRequest("site_id",0);
        $importJson=$_POST["import_json"];
        $fieldName=Control::PostRequest("field_name","");
        $fieldType=Control::PostRequest("field_type","");
        $queryFieldName=Control::PostRequest("query_field_name",$fieldName);
        $idFieldName=str_ireplace("Name","Id",$fieldName);
        $idFieldName=Control::PostOrGetRequest("field_id_name",$idFieldName);

        //////////////判断是否有操作权限///////////////////
        $manageUserId = Control::GetManageUserId();
        $manageUserAuthority = new ManageUserAuthorityManageData();
        $can = $manageUserAuthority->CanManageLeague($siteId, $manageUserId);
        if ($can != 1) {
            $result="-10";//没权限
            return Control::GetRequest("jsonpcallback","") . '({"result":"'.$result.'","data":'.json_encode($data).'})';
        }

        if($fieldName!=""&&$importJson!=""&&$siteId>0){
            $importArray=json_decode($importJson,true);
            $nameStr="";
            switch($fieldType){
                case "int":
                    foreach($importArray as $importItem){
                        $nameStr.=','.$importItem[$fieldName];
                    }
                    break;
                default:
                    foreach($importArray as $importItem){
                        $nameStr.=',"'.$importItem[$fieldName].'"';
                    }
                    break;
            }
            $nameStr=substr($nameStr,1);
            $stadiumManageData=new StadiumManageData();
            $idArr=$stadiumManageData->GetIdByFieldValue($nameStr,$queryFieldName,$siteId);

            if(!empty($idArr)){
                $result="1";
            }else{
                $result="0";
            }
            foreach($importArray as &$importItem){
                $added=0;
                foreach($idArr as $id){
                    if($importItem[$fieldName]==$id[$queryFieldName]){

                        $importItem[$idFieldName]=$id["StadiumId"];
                        $added=1;
                    }
                }
                if($added==0){
                    $importItem[$idFieldName]=0;
                }
            }
            $data=json_encode($importArray);
        }
        return Control::GetRequest("jsonpcallback","") . '({"result":"'.$result.'","data":'.$data.'})';
    }



    private function AsyncGetIdByName(){
        $result=0;//名空
        $stadiumId=-1;
        $stadiumName=Control::PostOrGetRequest("stadium_name","");
        $leagueId=Control::PostOrGetRequest("league_id",0);
        $siteId=Control::PostOrGetRequest("site_id",0);

        if($leagueId>0){
            $leagueManageData=new LeagueManageData();
            $siteId=$leagueManageData->GetSiteId($leagueId,true);
        }



        if($stadiumName!=""&&$siteId>0){
            //////////////判断是否有操作权限///////////////////
            $manageUserId = Control::GetManageUserId();
            $manageUserAuthority = new ManageUserAuthorityManageData();
            $can = $manageUserAuthority->CanManageLeague($siteId, $manageUserId);
            if ($can != 1) {
                $result="-10";//没权限
                return Control::GetRequest("jsonpcallback","") . '({"result":"'.$result.'","team_id":"'.$stadiumId.'"})';
            }

            $stadiumManageData=new StadiumManageData();
            $stadiumId=$stadiumManageData->GetIdByName($stadiumName);
            $result=1;
            if($stadiumId>0){
            }else{
                $result=-1;//找不到
            }
        }
        return Control::GetRequest("jsonpcallback","") . '({"result":"'.$result.'","stadium_id":'.$stadiumId.'})';
    }


}