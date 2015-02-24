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

        $result = "[{}]";
        $resultCode = 0;

        $channelId = Control::GetRequest("channel_id", 0);

        if($channelId>0){
            $topCount = Control::GetRequest("top", 5);
            $state = Control::GetRequest("state", 30);
            $orderBy = Control::GetRequest("order", 0);

            $picSliderClientData = new PicSliderClientData();
            $arrList = $picSliderClientData->GetList(
                $channelId,
                $topCount,
                $state,
                $orderBy
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

        return '{"result_code":"'.$resultCode.'","pic_slider":{"pic_slider_list":' . $result . '}}';
    }

} 