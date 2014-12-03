<?php
/**
 * 客户端 图片轮换 生成类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_Gen_Document
 * @author zhangchi
 */
class PicSliderClientGen extends BaseClientGen implements IBaseClientGen {

    /**
     * 引导方法
     * @return string 返回执行结果
     */
    public function GenClient(){
        $result = "";
        $function = Control::GetRequest("f", "");
        switch ($function) {

            case "list":
                $result = self::GenList();
                break;

        }
        $result = str_ireplace("{function}", $function, $result);
        return $result;
    }

    /**
     * 返回列表数据集
     * @return string
     */
    public function GenList(){

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
            $picSliderClientData = new PicSliderClientData();
            $arrList = $picSliderClientData->GetList(
                $channelId,
                $pageBegin,
                $pageSize,
                $allCount,
                $searchKey,
                $searchType
            );
            if (count($arrList) > 0) {
                $result = Format::FixJsonEncode($arrList);
            }
        }

        return '{"pic_slider":{"pic_slider_list":' . $result . '}}';
    }

} 