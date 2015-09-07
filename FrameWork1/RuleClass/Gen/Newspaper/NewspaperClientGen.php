<?php
/**
 * 客户端 电子报 生成类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_Gen_Newspaper
 * @author zhangchi
 */
class NewspaperClientGen extends BaseClientGen implements IBaseClientGen {

    /**
     * 引导方法
     * @return string 返回执行结果
     */
    public function GenClient(){
        $result = "";
        $function = Control::GetRequest("f", "");
        switch ($function) {

            case "new":
                $result = self::GenNew();
                break;

        }
        $result = str_ireplace("{function}", $function, $result);
        return $result;
    }

    private function GenNew()
    {
        $result = "[{}]";

        $channelId = Control::PostOrGetRequest("channel_id", 0);
        $publishDate = Control::PostOrGetRequest("publish_date", "");

        if($channelId>0){

            $newspaperClientData = new NewspaperClientData();

            if (strlen($publishDate) > 0) {
                $currentNewspaperId = $newspaperClientData->GetNewspaperIdByPublishDate($channelId, $publishDate);

            } else {
                $currentNewspaperId = $newspaperClientData->GetNewspaperIdOfNew($channelId);
            }

            $arrOne = $newspaperClientData->GetOne($currentNewspaperId);

            if($arrOne!=null){
            $result = Format::FixJsonEncode($arrOne);
            $resultCode = 1;
            }
            else {
                $resultCode = -1;
            }


        }else{
            $resultCode = -5;
        }

        return '{"result_code":"' . $resultCode . '","newspaper":' . $result . '}';
    }

} 