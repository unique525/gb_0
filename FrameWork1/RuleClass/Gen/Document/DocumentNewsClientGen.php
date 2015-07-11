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

            case "list_of_channel":
                $result = self::GenListOfChannel();
                break;
            case "list_of_channels":
                $result = self::GenListOfChannels();
                break;

        }
        $result = str_ireplace("{function}", $function, $result);
        return $result;
    }

    /**
     * 返回列表数据集
     * @return string
     */
    public function GenListOfChannel(){

        $result = "[{}]";
        $resultCode = 0;

        $channelId = intval(Control::GetRequest("channel_id", 0));

        if($channelId>0){
            $pageSize = intval(Control::GetRequest("ps", 20));
            $pageIndex = intval(Control::GetRequest("p", 1));
            $searchKey = Control::GetRequest("search_key", "");
            $searchType = intval(Control::GetRequest("search_type", -1));
            $searchKey = urldecode($searchKey);

            $pageBegin = ($pageIndex - 1) * $pageSize;
            $documentNewsClientData = new DocumentNewsClientData();
            $arrDocumentNewsList = $documentNewsClientData->GetList(
                $channelId,
                $pageBegin,
                $pageSize,
                $searchKey,
                $searchType
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
    public function GenListOfChannels(){

        $result = "[{}]";
        $resultCode = 0;

        $channelIds = Control::PostOrGetRequest("channel_id", "", false);

        if(strlen($channelIds)>0){
            $pageSize = intval(Control::GetRequest("ps", 20));
            $pageIndex = intval(Control::GetRequest("p", 1));
            $searchKey = Control::GetRequest("search_key", "");
            $searchType = intval(Control::GetRequest("search_type", -1));
            $searchKey = urldecode($searchKey);

            $pageBegin = ($pageIndex - 1) * $pageSize;
            $documentNewsClientData = new DocumentNewsClientData();
            $arrDocumentNewsList = $documentNewsClientData->GetListInChannelId(
                $channelIds,
                $pageBegin,
                $pageSize,
                $searchKey,
                $searchType
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