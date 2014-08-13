<?php
/**
 * 后台管理 会员订单 数据类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_User
 * Time: 下午12:09
 */
class UserOrderPublicData extends BasePublicData{

    public function Create($httpPostData,$siteId){
        $result = -1;
        if(!empty($httpPostData) && $siteId > 0){
            $sql = "";
        }
        return $result;
    }

    public function Modify($httpPostData,$userOrderId,$userId){
        $result = -1;
        if(!empty($httpPostData) && $userOrderId > 0 && $userId > 0){
            $sql = "";
        }
        return $result;
    }

    public function GetList($userId,$pageBegin,$pageSize,$allCount){
        $result = null;
        if($userId > 0){
            $sql = "";
        }
        return $result;
    }
}