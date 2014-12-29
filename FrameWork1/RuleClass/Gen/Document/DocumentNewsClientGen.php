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

        }
        $result = str_ireplace("{function}", $function, $result);
        return $result;
    }

    /**
     * 返回列表数据集
     * @return string
     */
    public function GenListOfChannel(){

        $result = "";

        $channelId = Control::GetRequest("channel_id", 0);

        if($channelId>0){
            $pageSize = Control::GetRequest("ps", 20);
            $pageIndex = Control::GetRequest("p", 1);
            $searchKey = Control::GetRequest("search_key", "");
            $searchType = Control::GetRequest("search_type", -1);
            $searchKey = urldecode($searchKey);

            $pageBegin = ($pageIndex - 1) * $pageSize;
            $allCount = 0;
            $documentNewsClientData = new DocumentNewsClientData();
            $arrDocumentNewsList = $documentNewsClientData->GetList(
                $channelId,
                $pageBegin,
                $pageSize,
                $allCount,
                $searchKey,
                $searchType
            );
            if (count($arrDocumentNewsList) > 0) {
                $result = Format::FixJsonEncode($arrDocumentNewsList);
            }
        }

        return '{"document_news":{"document_news_list":' . $result . '}}';
    }
} 