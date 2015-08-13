<?php
/**
 * 后台管理 会员订单支付信息 生成类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_Gen_User
 * @Author yin
 */
class UserOrderPayManageGen extends BaseManageGen implements IBaseManageGen{

    const ERROR_PARAMETER = -4;

    const ERROR_FAIL_CONFIRM = -2;
    /**
     * 引导方法
     * @return string 返回执行结果
     */
    public function Gen(){
        $result = "";
        $method = Control::GetRequest("m", "");
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
            case "async_confirm":
                $result = self::AsyncConfirm();
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
            $userOrderPayManageData = new UserOrderPayManageData();
            $templateContent = Template::Load("user/user_order_pay_deal.html", "common");
            parent::ReplaceFirst($templateContent);

            $templateContent = str_ireplace("{UserOrderId}", $userOrderId, $templateContent);

            if (!empty($_POST)) {

                $httpPostData = $_POST;

                $userOrderPayId = $userOrderPayManageData->Create($httpPostData, $userOrderId, $manageUserId);
                //加入操作日志
                $operateContent = 'Create User Order Pay,POST FORM:' . implode('|', $_POST) . ';\r\nResult:User Order Pay Id:' . $userOrderPayId;
                self::CreateManageUserLog($operateContent);

                if ($userOrderPayId > 0) {
                    $userOrderManageData = new UserOrderManageData();
                    $siteId = $userOrderManageData->GetSiteId($userOrderId, true);
                    //返回列表页
                    Control::GoUrl("/default.php?secu=manage&mod=user_order_pay&m=list&user_order_id=$userOrderId&site_id=$siteId&p=1");

                } else {
                    $resultJavaScript .= Control::GetJqueryMessage(Language::Load('user_order_pay', 2)); //新增失败！

                }

            }

            $fields = $userOrderPayManageData->GetFields();
            parent::ReplaceWhenCreate($templateContent, $fields);

            $patterns = '/\{s_(.*?)\}/';
            $templateContent = preg_replace($patterns, "", $templateContent);

            parent::ReplaceEnd($templateContent);


            $templateContent = str_ireplace("{ResultJavascript}", $resultJavaScript, $templateContent);

        } else {
            $templateContent = Language::Load("user_order_pay", 8);
        }
        return $templateContent;
    }

    /**
     * 编辑
     * @return string 模板内容页面
     */
    private function GenModify()
    {
        $tempContent = Template::Load("user/user_order_pay_deal.html", "common");
        $userOrderPayId = Control::GetRequest("user_order_pay_id", 0);
        $resultJavaScript = "";
        $manageUserId = Control::GetManageUserId();

        //判断权限


        /////////////////////////////////////////

        if ($userOrderPayId > 0 && $manageUserId > 0) {

            parent::ReplaceFirst($tempContent);

            $userOrderPayManageData = new UserOrderPayManageData();

            //加载原有数据
            $arrOne = $userOrderPayManageData->GetOne($userOrderPayId);
            Template::ReplaceOne($tempContent, $arrOne);


            if (!empty($_POST)) {
                $httpPostData = $_POST;

                $result = $userOrderPayManageData->Modify($httpPostData, $userOrderPayId);
                //加入操作日志
                $operateContent = 'Modify UserOrderPay,POST FORM:' . implode('|', $_POST) . ';\r\nResult:result:' . $result;
                self::CreateManageUserLog($operateContent);

                if ($result > 0) {
                    //删除缓冲
                    parent::DelAllCache();

                    $userOrderId = $userOrderPayManageData->GetUserOrderId($userOrderPayId, true);
                    $userOrderManageData = new UserOrderManageData();
                    $siteId = $userOrderManageData->GetSiteId($userOrderId, true);

                    //返回列表页
                    Control::GoUrl("/default.php?secu=manage&mod=user_order_pay&m=list&user_order_id=$userOrderId&site_id=$siteId&p=1");

                } else {
                    $resultJavaScript .= Control::GetJqueryMessage(Language::Load('user_order_pay', 4)); //编辑失败！
                }
            }

            $patterns = '/\{s_(.*?)\}/';
            $tempContent = preg_replace($patterns, "", $tempContent);
        }
        parent::ReplaceEnd($tempContent);
        $tempContent = str_ireplace("{ResultJavascript}", $resultJavaScript, $tempContent);
        return $tempContent;
    }


    /**
     * AJAX 会员支付信息确认
     * @return string
     */
    private function AsyncConfirm(){
        $userOrderPayId = intval(Control::GetRequest("user_order_pay_id",0));
        if($userOrderPayId > 0){
            $confirmWay = Control::GetRequest("confirm_way","");
            $confirmPrice = Control::GetRequest("confirm_price",0);
            $confirmDate = date("Y-m-d");
            $manageUserId = Control::GetManageUserId();
            $manageUserName = Control::GetManageUserName();

            if($confirmWay == "" || $manageUserId == 0){
                return Control::GetRequest("jsonpcallback","").'({"result":'.self::ERROR_PARAMETER.'})';
            }

            $userOrderPayManageData = new UserOrderPayManageData();
            $result = $userOrderPayManageData->ModifyConfirmPay($userOrderPayId,$confirmWay,$confirmPrice,$confirmDate,$manageUserId);

            $operateContent = 'Modify UserOrderPay,Get FORM:'.implode('|',$_GET).';\r\nResult::'.$result;
            self::CreateManageUserLog($operateContent);

            if($result > 0){
                return Control::GetRequest("jsonpcallback","").'({"result":"'.$result.'","confirm_way":"'.$confirmWay.'","confirm_price":"'
                .$confirmPrice.'","confirm_date":"'.$confirmDate.'","manage_username":"'.$manageUserName.'"})';
            }else{
                return Control::GetRequest("jsonpcallback","").'({"result":'.self::ERROR_FAIL_CONFIRM.'})';
            }
        }else{
            return Control::GetRequest("jsonpcallback","").'({"result":'.self::ERROR_PARAMETER.'})';
        }
    }

    /**
     * 获取会员订单支付信息列表
     * @return null|string
     */
    private function GenList(){
        $userOrderId = intval(Control::GetRequest("user_order_id",0));
        if($userOrderId > 0){
            $templateContent = Template::Load("user/user_order_pay_list.html","common");
            parent::ReplaceFirst($templateContent);

            $templateContent = str_ireplace("{UserOrderId}", $userOrderId, $templateContent);

            $userOrderPayManageData = new UserOrderPayManageData();

            $arrUserOrderPayList = $userOrderPayManageData->GetList($userOrderId);
            $tagId = "user_order_pay_list";
            if(!empty($arrUserOrderPayList)){
                Template::ReplaceList($templateContent,$arrUserOrderPayList,$tagId);
            }else{
                Template::RemoveCustomTag($templateContent,$tagId);
            }

            parent::ReplaceEnd($templateContent);
            return $templateContent;
        }else{
            return null;
        }
    }
}