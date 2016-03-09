<?php
/**
 * 客户端 邀请码 生成类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_Gen_Promotion
 * @author zhangchi
 */
class PromotionRecordClientGen extends BaseClientGen implements IBaseClientGen {

    /**
     * 引导方法
     * @return string 返回执行结果
     */
    public function GenClient(){
        $result = "";
        $function = Control::GetRequest("f", "");
        switch ($function) {

            case "create":
                $result = self::GenCreate();
                break;
            case "get_one":
                $result = self::GetOne();
                break;

        }
        $result = str_ireplace("{function}", $function, $result);
        return $result;
    }

    private function GenCreate(){

        $userId = parent::GetUserId();
        //if ($userId > 0) {

            $encryptStr = Control::PostOrGetRequest("device_id", "");
            $md5Str = Control::PostOrGetRequest("key_2", "");

            //检查密文和指纹
            if (!empty($encryptStr) && !empty($md5Str)) {

                $des = DesFitAllPlatForm::GetInstance();
                $decryptStr = $des->Decode($encryptStr, "ZAQ!xsw2");
                $decryptMd5Str = md5($decryptStr);

                //密文指纹比对
                if ($decryptMd5Str == $md5Str) {

                    $promotionRecordClientData = new PromotionRecordClientData();
                    //判断是否重复
                    $isExist = $promotionRecordClientData->CheckIsExist($decryptStr);
                    if (!$isExist) {
                        $promoterId = Control::PostOrGetRequest("promoter_id", "");
                        $createDate = date("Y-m-d H:i:s", time());
                        $deviceType = Control::PostOrGetRequest("device_type", 0);
                        $deviceNumber = $decryptStr;
                        $newPromotionRecordId = $promotionRecordClientData->Create($promoterId,$createDate,$deviceType,$deviceNumber,$userId);
                        //添加成功
                        if ($newPromotionRecordId > 0) {
                            $resultCode = 1;
                        } else {
                            $resultCode = -4; //添加失败；
                        }
                    } else {
                        $resultCode = -3; //重复添加；
                    }
                } else {
                    $resultCode = -2; //密文错误;
                }

            } else {
                $resultCode = -1; //参数错误;
            }
//        } else {
//            $resultCode = $userId; //会员检验失败,参数错误
//        }

        return '{"result_code":"' . $resultCode . '"}';
    }

    private function GetOne(){

        $result = "[{}]";

        $promotionRecordId = intval(Control::PostOrGetRequest("promotion_record_id",0));


        if($promotionRecordId > 0){
            $newspaperArticleClientData = new PromotionRecordClientData();
            $arrOne = $newspaperArticleClientData->GetOne($promotionRecordId, TRUE);

            $result = Format::FixJsonEncode($arrOne);
            $resultCode = 1; //

        }else{
            $resultCode = -1; //参数错误;
        }


        return '{"result_code":"' . $resultCode . '","promotion_record":' . $result . '}';


    }

} 