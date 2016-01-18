<?php

/**
 * 客户端 客户端顶部栏目 生成类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_Gen_ClientColumn
 * @author zhangchi
 */
class ClientColumnClientGen extends BaseClientGen implements IBaseClientGen {

    /**
     * 引导方法
     * @return string 返回执行结果
     */
    public function GenClient(){
        $result = "";
        $function = Control::GetRequest("f", "");
        switch ($function) {

            case "list_of_site":
                $result = self::GenListOfSite();
                break;

        }
        $result = str_ireplace("{function}", $function, $result);
        return $result;
    }

    /**
     * 返回列表数据集
     * @return string
     */
    public function GenListOfSite(){

        $result = "[{}]";
        $resultCode = 0;

        $siteId = intval(Control::GetRequest("site_id", 0));

        if($siteId>0){

            $clientColumnClientData = new ClientColumnClientData();
            $arrList = $clientColumnClientData->GetListOfSite($siteId);
            if (count($arrList) > 0) {
                $resultCode = 1;
                $result = Format::FixJsonEncode($arrList);
            }
            else{
                $resultCode = -2;
            }
        }
        else{
            $resultCode = -1;
        }

        return '{"result_code":"'.$resultCode.'","client_column":{"client_column_list":' . $result . '}}';
    }



}