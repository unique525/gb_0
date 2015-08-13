<?php
/**
 * 后台管理 会员订单发货信息 生成类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_Gen_User
 * @author yin
 */
class UserOrderSendManageGen extends BaseManageGen implements IBaseManageGen{
    /**
     *
     */
    const FAIL = 0;

    /**
     * 引导方法
     * @return string 返回执行结果
     */
    public function Gen(){
        $result = "";
        $method = Control::GetRequest("m","");
        switch($method){
            case "create":
                $result = self::GenCreate();
                break;
            case "modify":
                $result = self::GenModify();
                break;
            case "list":
                $result = self::GenList();
                break;
            case "async_modify":
                $result = self::AsyncModify();
                break;
            case "async_create":
                $result = self::AsyncCreate();
                break;
            case "async_remove":
                $result = self::AsyncRemove();
                break;
        }
        $result = str_ireplace("{method}", $method, $result);
        return $result;
    }


    private function GenCreate(){
        $manageUserId = Control::GetManageUserId();
        $userOrderId = Control::GetRequest("user_order_id", 0);
        $resultJavaScript = "";

        if ($manageUserId > 0 && $userOrderId>0) {
            $userOrderSendManageData = new UserOrderSendManageData();
            $templateContent = Template::Load("user/user_order_send_deal.html", "common");
            parent::ReplaceFirst($templateContent);
            $userOrderManageData = new UserOrderManageData();
            $userReceiveInfoId = $userOrderManageData->GetUserReceiveInfoId($userOrderId,TRUE);
            $userReceiveInfoManageData = new UserReceiveInfoManageData();

            $arrUserReceiveInfoOne = $userReceiveInfoManageData->GetOne($userReceiveInfoId);


            $templateContent = str_ireplace("{UserOrderId}", $userOrderId, $templateContent);
            $templateContent = str_ireplace("{AcceptPersonName}", $arrUserReceiveInfoOne["ReceivePersonName"], $templateContent);
            $templateContent = str_ireplace("{AcceptAddress}", $arrUserReceiveInfoOne["Address"], $templateContent);
            $templateContent = str_ireplace("{AcceptTel}", $arrUserReceiveInfoOne["Mobile"], $templateContent);

            if (!empty($_POST)) {

                $httpPostData = $_POST;

                $userOrderPayId = $userOrderSendManageData->Create($httpPostData, $userOrderId, $manageUserId);
                //加入操作日志
                $operateContent = 'Create User Order Send,POST FORM:' . implode('|', $_POST) . ';\r\nResult:User Order Send Id:' . $userOrderPayId;
                self::CreateManageUserLog($operateContent);

                if ($userOrderPayId > 0) {
                    $userOrderManageData = new UserOrderManageData();
                    $siteId = $userOrderManageData->GetSiteId($userOrderId, true);
                    //返回列表页
                    Control::GoUrl("/default.php?secu=manage&mod=user_order_send&m=list&user_order_id=$userOrderId&site_id=$siteId&p=1");

                } else {
                    $resultJavaScript .= Control::GetJqueryMessage(Language::Load('user_order_send', 3)); //新增失败！

                }

            }

            $fields = $userOrderSendManageData->GetFields();
            parent::ReplaceWhenCreate($templateContent, $fields);

            $patterns = '/\{s_(.*?)\}/';
            $templateContent = preg_replace($patterns, "", $templateContent);

            parent::ReplaceEnd($templateContent);


            $templateContent = str_ireplace("{ResultJavascript}", $resultJavaScript, $templateContent);

        } else {
            $templateContent = Language::Load("user_order_send", 9);
        }
        return $templateContent;
    }

    /**
     * 编辑
     * @return string 模板内容页面
     */
    private function GenModify()
    {
        $tempContent = Template::Load("user/user_order_send_deal.html", "common");
        $userOrderSendId = Control::GetRequest("user_order_send_id", 0);
        $resultJavaScript = "";
        $manageUserId = Control::GetManageUserId();

        //判断权限


        /////////////////////////////////////////
        if ($userOrderSendId > 0 && $manageUserId > 0) {

            parent::ReplaceFirst($tempContent);

            $userOrderSendManageData = new UserOrderSendManageData();
            //加载原有数据
            $arrOne = $userOrderSendManageData->GetOne($userOrderSendId);
            Template::ReplaceOne($tempContent, $arrOne);


            if (!empty($_POST)) {
                $httpPostData = $_POST;

                $result = $userOrderSendManageData->Modify($httpPostData, $userOrderSendId);
                //加入操作日志
                $operateContent = 'Modify UserOrderSend,POST FORM:' . implode('|', $_POST) . ';\r\nResult:result:' . $result;
                self::CreateManageUserLog($operateContent);

                if ($result > 0) {
                    //删除缓冲
                    parent::DelAllCache();

                    $userOrderId = $userOrderSendManageData->GetUserOrderId($userOrderSendId, true);
                    $userOrderManageData = new UserOrderManageData();
                    $siteId = $userOrderManageData->GetSiteId($userOrderId, true);

                    //返回列表页
                    Control::GoUrl("/default.php?secu=manage&mod=user_order_send&m=list&user_order_id=$userOrderId&site_id=$siteId&p=1");

                } else {
                    $resultJavaScript .= Control::GetJqueryMessage(Language::Load('user_order_send', 5)); //编辑失败！
                }
            }

            $patterns = '/\{s_(.*?)\}/';
            $tempContent = preg_replace($patterns, "", $tempContent);
        }
        parent::ReplaceEnd($tempContent);
        $tempContent = str_ireplace("{ResultJavascript}", $resultJavaScript, $tempContent);
        return $tempContent;
    }




    private function GenList(){
        $userOrderId = Control::GetRequest("user_order_id",0);

        $templateContent = "";
        if($userOrderId > 0){
            $templateContent = Template::Load("user/user_order_send_list.html","common");
            parent::ReplaceFirst($templateContent);
            $userOrderSendManageData = new UserOrderSendManageData();
            $arrUserOrderSendList = $userOrderSendManageData->GetList($userOrderId);

            $tagId = "user_order_send_list";
            if(count($arrUserOrderSendList) > 0){
                Template::ReplaceList($templateContent,$arrUserOrderSendList,$tagId);
            }else{
                Template::ReplaceCustomTag($templateContent,$tagId,Language::Load("user_order_send",1));
            }
        }

        $templateContent = str_ireplace("{UserOrderId}",$userOrderId,$templateContent);

        parent::ReplaceEnd($templateContent);
        return $templateContent;

    }

    private function AsyncModify(){
        $userOrderSendId = Control::GetRequest("user_order_send_id",0);

        $result = self::FAIL;
        if($userOrderSendId > 0){
            $acceptPersonName = Control::PostRequest("accept_person_name","");
            $acceptAddress = Control::PostRequest("accept_address","");
            $acceptTel = Control::PostRequest("accept_tel","");
            $acceptTime = Control::PostRequest("accept_time","");
            $sendCompany = Control::PostRequest("send_company","");

            $userOrderSendManageData = new UserOrderSendManageData();
            $result = $userOrderSendManageData->Modify($userOrderSendId,$acceptPersonName,$acceptAddress,$acceptTel,$acceptTime,$sendCompany);
        }
        return Control::GetRequest("jsonpcallback","").'({"result":'.$result.'})';
    }

    private function AsyncCreate(){
        $siteId = Control::GetRequest("site_id",0);
        $userOrderId = Control::GetRequest("user_order_id",0);

        $result = self::FAIL;
        if($siteId > 0 && $userOrderId > 0){
            $acceptPersonName = Control::PostRequest("acceptPersonName","");
            $acceptAddress = Control::PostRequest("acceptAddress","");
            $acceptTel = Control::PostRequest("acceptTel","");
            $acceptTime = Control::PostRequest("acceptTime","");
            $sendCompany = Control::PostRequest("sendCompany","");

            $userOrderSendManageData = new UserOrderSendManageData();
            $result = $userOrderSendManageData->Create($userOrderId,$acceptPersonName,$acceptAddress,$acceptTel,$acceptTime,$sendCompany);
        }
        return Control::GetRequest("jsonpcallback","").'({"result":'.$result.'})';
    }

    private function AsyncRemove(){
        $userOrderSendId = Control::GetRequest("user_order_send_id",0);

        $result = self::FAIL;
        if($userOrderSendId > 0){
            $userOrderSendManageData = new UserOrderSendManageData();
            $result = $userOrderSendManageData->Delete($userOrderSendId);
        }

        return Control::GetRequest("jsonpcallback","").'({"result":'.$result.'})';
    }
}