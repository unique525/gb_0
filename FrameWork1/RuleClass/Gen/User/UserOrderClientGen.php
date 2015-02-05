<?php
/**
 * 客户端 会员订单 生成类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_Gen_User
 * @author zhangchi
 */
class UserOrderClientGen extends BaseClientGen implements IBaseClientGen {
    /**
     * 引导方法
     * @return string 返回执行结果
     */
    public function GenClient()
    {
        $result = "";
        $function = Control::GetRequest("f", "");
        switch ($function) {

            case "create":
                $result = self::GenCreate();
                break;

            case "list":
                $result = self::GenList();
                break;

        }
        $result = str_ireplace("{function}", $function, $result);
        return $result;
    }

    private function GenCreate(){
        $result = "[{}]";

        $userId = parent::GetUserId();

        if ($userId <= 0) {
            $resultCode = $userId; //会员检验失败,参数错误
        } else {

            $address = Control::PostOrGetRequest("Address", "");
            $postcode = Control::PostOrGetRequest("Postcode", "");
            $receivePersonName = Control::PostOrGetRequest("ReceivePersonName", "");
            $homeTel = Control::PostOrGetRequest("HomeTel", "");
            $mobile = Control::PostOrGetRequest("Mobile", "");
            if (strlen($address) > 0) {
                $userReceiveInfoClientData = new UserReceiveInfoClientData();
                $result = $userReceiveInfoClientData->Create(
                    $userId,
                    $address,
                    $postcode,
                    $receivePersonName,
                    $homeTel,
                    $mobile
                );
                if ($result > 0) {
                    $resultCode = 1; //新增成功
                } else {
                    $resultCode = -5; //新增失败,数据库原因
                }
            } else {
                $resultCode = -6; //没有填写地址
            }

        }
        return '{"result_code":"' . $resultCode . '","user_receive_info_create":' . $result . '}';

    }
} 