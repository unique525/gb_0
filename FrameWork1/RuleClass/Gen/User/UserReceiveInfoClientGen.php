<?php

/**
 * 客户端 会员收货信息 生成类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_Gen_User
 * @author zhangchi
 */
class UserReceiveInfoClientGen extends BaseClientGen implements IBaseClientGen
{
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

            case "modify":
                $result = self::GenModify();
                break;

            case "set_default":
                $result = self::GenSetDefault();
                break;

            case "delete":
                $result = self::GenDelete();
                break;

            case "list":
                $result = self::GenList();
                break;

        }
        $result = str_ireplace("{function}", $function, $result);
        return $result;
    }

    private function GenCreate()
    {
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
            $city = Control::PostOrGetRequest("City", "");
            $district = Control::PostOrGetRequest("District", "");
            if (strlen($address) > 0) {
                $userReceiveInfoClientData = new UserReceiveInfoClientData();
                $newUserReceiveInfoId = $userReceiveInfoClientData->Create(
                    $userId,
                    $address,
                    $postcode,
                    $receivePersonName,
                    $homeTel,
                    $mobile,
                    $city,
                    $district
                );
                if ($newUserReceiveInfoId > 0) {
                    $resultCode = 1; //新增成功

                    $result = Format::FixJsonEncode($userReceiveInfoClientData->GetOne($newUserReceiveInfoId,$userId));


                } else {
                    $resultCode = -5; //新增失败,数据库原因
                }
            } else {
                $resultCode = -6; //没有填写地址
            }

        }
        return '{"result_code":"' . $resultCode . '","user_receive_info_create":' . $result . '}';
    }

    private function GenModify()
    {
        $result = "[{}]";

        $userId = parent::GetUserId();

        if ($userId <= 0) {
            $resultCode = $userId; //会员检验失败,参数错误
        } else {
            $userReceiveInfoId = intval(Control::PostOrGetRequest("UserReceiveInfoId", 0));
            $address = Control::PostOrGetRequest("Address", "");
            $postcode = Control::PostOrGetRequest("Postcode", "");
            $receivePersonName = Control::PostOrGetRequest("ReceivePersonName", "");
            $homeTel = Control::PostOrGetRequest("HomeTel", "");
            $mobile = Control::PostOrGetRequest("Mobile", "");
            $city = Control::PostOrGetRequest("City", "");
            $district = Control::PostOrGetRequest("District", "");

            if ($userReceiveInfoId > 0 && strlen($address) > 0) {
                $userReceiveInfoClientData = new UserReceiveInfoClientData();
                $result = $userReceiveInfoClientData->Modify(
                    $userId,
                    $userReceiveInfoId,
                    $address,
                    $postcode,
                    $receivePersonName,
                    $homeTel,
                    $mobile,
                    $city,
                    $district
                );
                if ($result > 0) {
                    $resultCode = 1; //修改成功

                    $result = Format::FixJsonEncode($userReceiveInfoClientData->GetOne($userReceiveInfoId,$userId));


                } else {
                    $resultCode = -5; //修改失败,数据库原因
                }
            } else {
                $resultCode = -6; //参数错误或没有填写地址
            }

        }
        return '{"result_code":"' . $resultCode . '","user_receive_info_modify":' . $result . '}';
    }

    private function GenSetDefault(){
        $result = "[{}]";
        $userId = parent::GetUserId();
        if ($userId <= 0) {
            $resultCode = $userId; //会员检验失败,参数错误
        } else {
            $userReceiveInfoId = intval(Control::PostOrGetRequest("UserReceiveInfoId", 0));
            if ($userReceiveInfoId > 0) {
                $userReceiveInfoClientData = new UserReceiveInfoClientData();
                $result = $userReceiveInfoClientData->SetDefault($userReceiveInfoId, $userId);
                if ($result > 0) {
                    $resultCode = 1; //设置成功
                } else {
                    $resultCode = -5; //设置失败,数据库原因
                }

            } else {
                $resultCode = -6; //设置失败,参数错误;
            }

        }
        return '{"result_code":"' . $resultCode . '","user_receive_info_delete":' . $result . '}';

    }

    private function GenDelete()
    {
        $result = "[{}]";
        $userId = parent::GetUserId();
        if ($userId <= 0) {
            $resultCode = $userId; //会员检验失败,参数错误
        } else {
            $userReceiveInfoId = intval(Control::PostOrGetRequest("UserReceiveInfoId", 0));
            if ($userReceiveInfoId > 0) {
                $userReceiveInfoClientData = new UserReceiveInfoClientData();
                $result = $userReceiveInfoClientData->Delete($userReceiveInfoId, $userId);
                if ($result > 0) {
                    $resultCode = 1; //删除成功
                } else {
                    $resultCode = -5; //删除失败,数据库原因
                }

            } else {
                $resultCode = -6; //加入失败,参数错误;
            }

        }
        return '{"result_code":"' . $resultCode . '","user_receive_info_delete":' . $result . '}';
    }

    private function GenList()
    {

        $result = "[{}]";

        $userId = parent::GetUserId();

        if ($userId <= 0) {
            $resultCode = $userId;
        } else {


            $userReceiveInfoClientData = new UserReceiveInfoClientData();

            $arrList = $userReceiveInfoClientData->GetList($userId);

            if (count($arrList) > 0) {
                $resultCode = 1;


                $result = Format::FixJsonEncode($arrList);

            } else {
                $resultCode = -1;
            }

        }
        return '{"result_code":"' . $resultCode . '","user_receive_info":{"user_receive_info_list":' . $result . '}}';

    }
} 