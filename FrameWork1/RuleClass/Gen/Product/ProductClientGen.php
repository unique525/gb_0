<?php
/**
 * 客户端 产品 生成类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_Gen_Product
 * @author zhangchi
 */
class ProductClientGen extends BaseClientGen implements IBaseClientGen {

    /**
     * 引导方法
     * @return string 返回执行结果
     */
    public function GenClient(){
        $result = "";
        $function = Control::GetRequest("f", "");
        switch ($function) {

            case "list_of_channel_id":
                $result = self::GenListOfChannelId();
                break;

        }
        $result = str_ireplace("{function}", $function, $result);
        return $result;
    }

    /**
     * 返回列表数据集
     * @return string
     */
    public function GenListOfChannelId(){

        $result = "";

        $channelId = Control::GetRequest("channel_id", 0);

        if($channelId>0){
            $topCount = Control::GetRequest("top", 5);
            $orderBy = Control::GetRequest("order", 0);

            $channelClientData = new ChannelClientData();

            $channelIds = $channelClientData->GetChildrenChannelId($channelId, true);

            $productClientData = new ProductClientData();
            $arrList = $productClientData->GetListOfChannelId(
                $channelIds,
                $orderBy,
                $topCount
            );
            if (count($arrList) > 0) {
                $result = Format::FixJsonEncode($arrList);
            }
        }

        return '{"product":{"product_list":' . $result . '}}';
    }

} 