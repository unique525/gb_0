<?php
/**
 * 客户端 频道 生成类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_Gen_Channel
 * @author hy
 */
class ChannelClientGen extends BaseClientGen implements IBaseClientGen {

    /**
     * 引导方法
     * @return string 返回执行结果
     */
    public function GenClient(){
        $result = "";
        $function = Control::GetRequest("f", "");

        switch ($function) {

            case "all_child_list_by_parent_id":
                $result = self::GetAllChildListByParentId();
                break;
            case "list_by_channel_type":
                $result = self::GetListByChannelType();
                break;
            case "list_by_site_id":
                $result = self::GetListBySiteId();
                break;

        }
        $result = str_ireplace("{function}", $function, $result);
        return $result;
    }

    /**
     * 根据父频道id返回其下所有子频道数组
     * @return array 频道数据集
     */
    private function GetAllChildListByParentId(){

        $result = "[{}]";

        $parentId = Control::GetRequest("parent_id", 0);

        if($parentId>0){
            $topCount = Control::GetRequest("top", null);
            $order = Control::GetRequest("order", "");

            $channelClientData = new ChannelClientData();
            $channelIds = $channelClientData->GetChildrenChannelId($parentId, true);
            $arrList = $channelClientData->GetAllChildListByChannelId(
                $parentId,
                $channelIds,
                $order,
                $topCount
            );
            if (count($arrList) > 0) {
                $resultCode = 1;
                $result = Format::FixJsonEncode($arrList);
            }else{
                $resultCode = -2;
            }
        }else{
            $resultCode = -1;
        }
        return '{"result_code":"'.$resultCode.'","channel":{"channel_list":' . $result . '}}';
    }

    /**
     * 根据频道类型返回频道数据集
     * @return array 频道数据集
     */
    private function GetListByChannelType(){

        $result = "[{}]";

        $siteId = intval(Control::GetRequest("site_id", 0));

        if($siteId>0){
            $topCount = Control::GetRequest("top", "");
            $order = Control::GetRequest("order", "");

            $channelType = intval(Control::PostOrGetRequest("channel_type", "0"));

            $channelClientData = new ChannelClientData();
            $arrList = $channelClientData->GetListByChannelType(
                $siteId,
                $channelType,
                $order,
                $topCount
            );
            if (count($arrList) > 0) {
                $resultCode = 1;
                $result = Format::FixJsonEncode($arrList);
            }else{
                $resultCode = -2;
            }
        }else{
            $resultCode = -1;
        }
        return '{"result_code":"'.$resultCode.'","channel":{"channel_list":' . $result . '}}';
    }

    private function GetListBySiteId(){
        $result = "[{}]";

        $siteId = Control::GetRequest("site_id", 0);

        if($siteId>0){
            $topCount = Control::GetRequest("top", null);
            $order = Control::GetRequest("order", "");

            $channelClientData = new ChannelClientData();
            $arrList = $channelClientData->GetListBySiteId(
                $siteId,
                $order,
                $topCount
            );
            if (count($arrList) > 0) {
                $resultCode = 1;
                $result = Format::FixJsonEncode($arrList);
            }else{
                $resultCode = -2;
            }
        }else{
            $resultCode = -1;
        }
        return '{"result_code":"'.$resultCode.'","channel":{"channel_list":' . $result . '}}';

    }
} 