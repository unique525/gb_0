<?php
/**
 * 客户端 资讯 生成类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_Gen_Document
 * @author zhangchi
 */
class DocumentNewsClientGen extends BaseClientGen implements IBaseClientGen {

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
            case "list_of_channel":
                $result = self::GenListOfChannel();
                break;
            case "list_of_channels":
                $result = self::GenListOfChannels();
                break;
            case "get_one":
                $result = self::GetOne();
                break;
            case "relative":
                $result = self::GenRelative();
                break;

        }
        $result = str_ireplace("{function}", $function, $result);
        return $result;
    }

    /**
     * 返回列表数据集
     * @return string
     */
    private function GenListOfSite(){

        $result = "[{}]";
        $resultCode = 0;

        $siteId = intval(Control::GetRequest("site_id", 0));

        if($siteId>0){
            $pageSize = intval(Control::GetRequest("ps", 20));
            $pageIndex = intval(Control::GetRequest("p", 1));
            $searchKey = Control::GetRequest("search_key", "");
            $searchType = intval(Control::GetRequest("search_type", -1));
            $showInClientIndex = intval(Control::GetRequest("show_in_client_index", -1));
            $searchKey = urldecode($searchKey);

            $pageBegin = ($pageIndex - 1) * $pageSize;
            $documentNewsClientData = new DocumentNewsClientData();
            $arrDocumentNewsList = $documentNewsClientData->GetListOfSite(
                $siteId,
                $pageBegin,
                $pageSize,
                $searchKey,
                $searchType,
                $showInClientIndex
            );
            if (count($arrDocumentNewsList) > 0) {
                $resultCode = 1;
                $result = Format::FixJsonEncode($arrDocumentNewsList);
            }
            else{
                $resultCode = -2;
            }
        }
        else{
            $resultCode = -1;
        }

        return '{"result_code":"'.$resultCode.'","document_news":{"document_news_list":' . $result . '}}';
    }

    /**
     * 返回列表数据集
     * @return string
     */
    private function GenListOfChannel(){

        $result = "[{}]";
        $resultCode = 0;

        $channelId = intval(Control::GetRequest("channel_id", 0));

        if($channelId>0){
            $pageSize = intval(Control::GetRequest("ps", 20));
            $pageIndex = intval(Control::GetRequest("p", 1));
            $searchKey = Control::GetRequest("search_key", "");
            $searchType = intval(Control::GetRequest("search_type", -1));
            $showInClientIndex = intval(Control::GetRequest("show_in_client_index", -1));
            $searchKey = urldecode($searchKey);

            $pageBegin = ($pageIndex - 1) * $pageSize;
            $documentNewsClientData = new DocumentNewsClientData();
            $arrDocumentNewsList = $documentNewsClientData->GetList(
                $channelId,
                $pageBegin,
                $pageSize,
                $searchKey,
                $searchType,
                $showInClientIndex
            );
            if (count($arrDocumentNewsList) > 0) {
                $resultCode = 1;
                $result = Format::FixJsonEncode($arrDocumentNewsList);
            }
            else{
                $resultCode = -2;
            }
        }
        else{
            $resultCode = -1;
        }

        return '{"result_code":"'.$resultCode.'","document_news":{"document_news_list":' . $result . '}}';
    }

    /**
     * 返回列表数据集
     * @return string
     */
    private function GenListOfChannels(){

        $result = "[{}]";
        $resultCode = 0;

        $channelIds = Control::PostOrGetRequest("channel_id", "", false);

        if(strlen($channelIds)>0){
            $pageSize = intval(Control::GetRequest("ps", 20));
            $pageIndex = intval(Control::GetRequest("p", 1));
            $searchKey = Control::GetRequest("search_key", "");
            $searchType = intval(Control::GetRequest("search_type", -1));
            $searchKey = urldecode($searchKey);
            $showInClientIndex = intval(Control::GetRequest("show_in_client_index", -1));

            $pageBegin = ($pageIndex - 1) * $pageSize;
            $documentNewsClientData = new DocumentNewsClientData();
            $arrDocumentNewsList = $documentNewsClientData->GetListInChannelId(
                $channelIds,
                $pageBegin,
                $pageSize,
                $searchKey,
                $searchType,
                $showInClientIndex
            );
            if (count($arrDocumentNewsList) > 0) {
                $resultCode = 1;
                $result = Format::FixJsonEncode($arrDocumentNewsList);
            }
            else{
                $resultCode = -2;
            }
        }
        else{
            $resultCode = -1;
        }

        return '{"result_code":"'.$resultCode.'","document_news":{"document_news_list":' . $result . '}}';
    }

    private function GetOne(){

        $result = "[{}]";

        $documentNewsId = intval(Control::PostOrGetRequest("document_news_id",0));


        if(
            $documentNewsId > 0
        ){
            $documentNewsClientData = new DocumentNewsClientData();
            $arrOne = $documentNewsClientData->GetOne($documentNewsId, TRUE);

            $result = Format::FixJsonEncode($arrOne);
            $resultCode = 1; //

        }else{
            $resultCode = -6; //参数错误;
        }


        return '{"result_code":"' . $resultCode . '","document_news":' . $result . '}';


    }


    private function GenRelative(){
        $result = "[{}]";
        $resultCode = 0;

        $siteId = intval(Control::GetRequest("site_id", 0));
        $documentNewsId = intval(Control::PostOrGetRequest("document_news_id",0));
        $top = intval(Control::GetRequest("top", 5));

        if($siteId>0 && $documentNewsId>0){

            $documentNewsClientData = new DocumentNewsClientData();

            $documentNewsMainTag = $documentNewsClientData->GetDocumentNewsMainTag($documentNewsId,true);
            $documentNewsTag = $documentNewsClientData->GetDocumentNewsTag($documentNewsId,true);

            $arrLike = array();

            if (strlen($documentNewsMainTag)>0){

                $arrLike[] = $documentNewsMainTag;

            }

            if (strlen($documentNewsTag)>0){

                $arr = explode(' ',$documentNewsTag);

                if (empty($arr)){

                    $arr = explode($documentNewsTag,',');

                }
                foreach($arr as $val){

                    $val = str_ireplace(" ","",$val);

                    if(strlen($val)>0){
                        $arrLike[] = $val;
                    }

                }

            }

            $arrDocumentNewsList = $documentNewsClientData->GetListOfRelative(
                $siteId,
                $documentNewsId,
                $arrLike,
                $top
            );
            if (count($arrDocumentNewsList) > 0) {
                $resultCode = 1;
                $result = Format::FixJsonEncode($arrDocumentNewsList);
            }
            else{
                $resultCode = -2;
            }
        }
        else{
            $resultCode = -1;
        }

        return '{"result_code":"'.$resultCode.'","document_news":{"document_news_list":' . $result . '}}';

    }
}