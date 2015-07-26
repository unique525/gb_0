<?php
/**
 * 客户端 活动 生成类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_Gen_Activity
 * @author zhangchi
 */
class ActivityClientGen extends BaseClientGen implements IBaseClientGen {

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

        $result = "[{}]";

        $channelId = intval(Control::GetRequest("channel_id", 0));

        if($channelId>0){
            $pageSize = intval(Control::GetRequest("ps", 20));
            $pageIndex = intval(Control::GetRequest("p", 1));
            $pageBegin = ($pageIndex - 1) * $pageSize;

            $activityClientData = new ActivityClientData();
            $arrList = $activityClientData->GetList(
                $channelId,
                $pageBegin,
                $pageSize
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

        return '{"result_code":"'.$resultCode.'","activity":{"activity_list":' . $result . '}}';
    }
} 