<?php
/**
 * 客户端 电子报 生成类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_Gen_Newspaper
 * @author zhangchi
 */
class NewspaperPageClientGen extends BaseClientGen implements IBaseClientGen {

    /**
     * 引导方法
     * @return string 返回执行结果
     */
    public function GenClient(){
        $result = "";
        $function = Control::GetRequest("f", "");
        switch ($function) {

            case "list_of_newspaper":
                $result = self::GenListOfNewspaper();
                break;

        }
        $result = str_ireplace("{function}", $function, $result);
        return $result;
    }

    /**
     * 返回列表数据集
     * @return string
     */
    public function GenListOfNewspaper(){

        $result = "[{}]";

        $newspaperId = intval(Control::GetRequest("newspaper_id", 0));

        if($newspaperId>0){
            $pageSize = intval(Control::GetRequest("ps", 20));
            $pageIndex = intval(Control::GetRequest("p", 1));
            $searchKey = Control::GetRequest("search_key", "");
            $searchType = intval(Control::GetRequest("search_type", -1));
            $searchKey = urldecode($searchKey);

            $pageBegin = ($pageIndex - 1) * $pageSize;
            $newspaperPageClientData = new NewspaperPageClientData();
            $arrList = $newspaperPageClientData->GetList(
                $newspaperId,
                $pageBegin,
                $pageSize,
                $searchKey,
                $searchType
            );
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

        return '{"result_code":"'.$resultCode.'","newspaper_page":{"newspaper_page_list":' . $result . '}}';
    }

} 