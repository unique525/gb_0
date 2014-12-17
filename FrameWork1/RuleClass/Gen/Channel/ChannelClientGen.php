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

            case "list_of_parent_id":
                $result = self::GenListOfParentId();
                break;

        }
        $result = str_ireplace("{function}", $function, $result);
        return $result;
    }

    /**
     * 根据父亲节点返回列表数据集
     * @return string
     */
    private function GenListOfParentId(){

        $result = "";

        $parentId = Control::GetRequest("parent_id", 0);

        if($parentId>0){
            $topCount = Control::GetRequest("top", null);
            $order = Control::GetRequest("order", "");

            $channelClientData = new ChannelClientData();
            $arrList = $channelClientData->GetListByParentId(
                $parentId,
                $order,
                $topCount
            );
            if (count($arrList) > 0) {
                $result = Format::FixJsonEncode($arrList);
            }
            return '{"channel":{"channel_list":' . $result . '}}';
        }
        else return "";
    }
} 