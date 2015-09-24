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
            case "list_of_user":
                $result = self::GenListOfUser();
                break;
            case "get_one":
                $result = self::GetOne();
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
            //活动所处时间状态类型，all全部，inTime进行中，end已结束
            $timeState = Control::GetRequest("time_state", "all");
            $pageBegin = ($pageIndex - 1) * $pageSize;

            $activityClientData = new ActivityClientData();
            $arrList = $activityClientData->GetList(
                $channelId,
                $timeState,
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

    /**
     * 返回列表数据集
     * @return string
     */
    public function GenListOfUser(){

        $result = "[{}]";

        $userId = parent::GetUserId();

        if ($userId <= 0) {
            $resultCode = $userId; //会员检验失败,参数错误
        } else {
            if($userId>0){
                $pageSize = intval(Control::GetRequest("ps", 20));
                $pageIndex = intval(Control::GetRequest("p", 1));
                $pageBegin = ($pageIndex - 1) * $pageSize;

                $activityClientData = new ActivityClientData();
                $arrList = $activityClientData->GetListOfUser(
                    $userId,
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

        }



        return '{"result_code":"'.$resultCode.'","activity":{"activity_list":' . $result . '}}';
    }


    private function GetOne(){

        $result = "[{}]";

        $activityId = intval(Control::PostOrGetRequest("activity_id",0));


        if(
            $activityId > 0
        ){
            $activityClientData = new ActivityClientData();
            $arrOne = $activityClientData->GetOne($activityId, TRUE);

            $result = Format::FixJsonEncode($arrOne);
            $resultCode = 1; //

        }else{
            $resultCode = -6; //参数错误;
        }


        return '{"result_code":"' . $resultCode . '","activity":' . $result . '}';


    }
} 