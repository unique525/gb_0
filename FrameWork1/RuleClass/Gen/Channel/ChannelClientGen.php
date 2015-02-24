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

        }
        $result = str_ireplace("{function}", $function, $result);
        return $result;
    }

    /**
     * 根据父频道id返回其下所有子频道数组
     * @return array 数组
     */
    private function GetAllChildListByParentId(){

        $result = "[{}]";
        $resultCode = 0;

        $parentId = Control::GetRequest("parent_id", 0);

        if($parentId>0){
            $topCount = Control::GetRequest("top", null);
            $order = Control::GetRequest("order", "");

            $channelClientData = new ChannelClientData();
            $channelIds = $channelClientData->GetChildrenChannelId($parentId, true);
            $arrList = $channelClientData->GenAllChildListByChannelId(
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
} 