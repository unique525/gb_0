<?php
/**
 * 后台管理 会员订单 生成类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_Gen_User
 * @Author yin
 */
class UserOrderManageGen extends BaseManageGen implements IBaseManageGen{
    public function Gen(){
        $result = "";
        $method = Control::GetRequest("m", "");
        switch($method){
            case "modify":
                $result = self::GenModify();
                break;
            case "modify_order_product":
                $result = self::GenModifyOrderProduct();
                break;
            case "list":
                $result = self::GenList();
                break;
            case "confirm_pay":
                $result = self::AsyncConfirmPay();
                break;
            case "user_order_pay_list":
                $result = self::GenUserOrderPayList();
                break;
            case "delete_user_order_product":
                $result = self::GenDeleteUserOrderProduct();
                break;
        }
        $result = str_ireplace("{method}", $method, $result);
        return $result;
    }

    private function GenModify(){
        $templateContent = Template::Load("user/user_order_deal.html","common");
        $userOrderId = intval(Control::GetRequest("user_order_id",0));
        $siteId = intval(Control::GetRequest("site_id",0));
        parent::ReplaceFirst($templateContent);
        $resultJavaScript = "";

        if($userOrderId > 0 && $siteId > 0){
            $pageSize = intval(Control::GetRequest("ps",0));
            $tabIndex = intval(Control::GetRequest("tab_index",0));
            $pageIndex = intval(Control::GetRequest("p",1));

            $userOrderManageData = new UserOrderManageData();
            $userOrderProductManageData = new UserOrderProductManageData();
            $userReceiveInfoManageData = new UserReceiveInfoManageData();

            if(!empty($_POST)){
                $httpPostData = $_POST;
                $closeTab = intval(Control::PostRequest("CloseTab",0));
                $tabIndex = intval(Control::GetRequest("TabIndex",0));
                $pageSize = intval(Control::GetRequest("PageSize",27));
                $pageIndex  = intval(Control::GetRequest("PageIndex",1));
                $result = $userOrderManageData->Modify($httpPostData,$userOrderId,$siteId);
                //加入操作日志
                if($result > 0){
                    $operateContent = 'Modify UserOrder,POST FORM:'.implode('|',$_POST).';\r\nResult:'.$result;
                    self::CreateManageUserLog($operateContent);
                    $resultJavaScript .= Control::GetJqueryMessage(Language::Load('user_order', 3));
                }else{
                    $resultJavaScript .= Control::GetJqueryMessage(Language::Load('user_order', 4));
                }
                if($closeTab == 1){
                    Control::GoUrl("/default.php?secu=manage&mod=user_order&m=list&site_id=".$siteId."&ps=".$pageSize."&p=".$pageIndex."&tab_index=".$tabIndex);
                }else{
                    Control::GoUrl($_SERVER["HTTP_SELF"]);
                }
            }

            $arrUserOrderOne = $userOrderManageData->GetOne($userOrderId,$siteId);
            $arrUserOrderProductList = $userOrderProductManageData->GetList($userOrderId,$siteId);

            if(intval($arrUserOrderOne["UserId"]) > 0){
                $userId = $arrUserOrderOne["UserId"];
                $arrUserReceiveInfoList = $userReceiveInfoManageData->GetList($userId);
                $tagUserReceiveInfoListId = "user_receive_info_list";
                if(count($arrUserReceiveInfoList) > 0){
                    Template::ReplaceList($templateContent,$arrUserReceiveInfoList,$tagUserReceiveInfoListId);
                }else{
                    Template::RemoveCustomTag($templateContent);
                }
            }

            $tagUserOrderProductListId = "user_order_product_list";
            if(count($arrUserOrderProductList) > 0){
                Template::ReplaceList($templateContent,$arrUserOrderProductList,$tagUserOrderProductListId);
            }else{
                Template::RemoveCustomTag($templateContent);
            }

            Template::ReplaceOne($templateContent,$arrUserOrderOne);

            $replace_arr = array(
                "{TabIndex}" => $tabIndex,
                "{PageSize}" => $pageSize,
                "{PageIndex}" => $pageIndex,
                "{SiteId}" => $siteId
            );

            $templateContent = strtr($templateContent,$replace_arr);
        }

        parent::ReplaceEnd($templateContent);
        $templateContent = str_ireplace("{ResultJavascript}", $resultJavaScript, $templateContent);
        return $templateContent;
    }

    private function GenModifyOrderProduct(){
        $templateContent = Template::Load("user/user_order_product_deal.html","common");
        $userOrderProductId = intval(Control::GetRequest("user_order_product_id",0));
        $userOrderId = intval(Control::GetRequest("user_order_id",0));
        $siteId = intval(Control::GetRequest("site_id",0));

        parent::ReplaceFirst($templateContent);
        $resultJavaScript = "";
        if($userOrderProductId > 0 && $userOrderId > 0){
            $userOrderProductManageData = new UserOrderProductManageData();

            if(!empty($_POST)){
                $httpPostData = $_POST;
                $result = $userOrderProductManageData->Modify($httpPostData,$userOrderProductId,$userOrderId);

                if($result > 0){
                    $jsCode = 'parent.location.href=parent.location.href';
                    Control::RunJavascript($jsCode);
                    $resultJavaScript .= Control::GetJqueryMessage(Language::Load('user_order', 3));
                }else{
                    $resultJavaScript .= Control::GetJqueryMessage(Language::Load('user_order', 4));
                }
                $operateContent = 'Modify UserOrderProduct,POST FORM:'.implode('|',$_POST).';\r\nResult::'.$result;
                self::CreateManageUserLog($operateContent);
            }
            $arrUserOrderProductOne = $userOrderProductManageData->GetOne($userOrderProductId,$siteId);

            Template::ReplaceOne($templateContent,$arrUserOrderProductOne);
        }

        parent::ReplaceEnd($templateContent);
        $templateContent = str_ireplace("{ResultJavascript}", $resultJavaScript, $templateContent);
        return $templateContent;
    }

    private function GenList(){
        $siteId = Control::GetRequest("site_id",0);
        if($siteId > 0){
            $pageIndex = Control::GetRequest("p",1);
            $pageSize = Control::GetRequest("ps",0);
            $templateContent = Template::Load("user/user_order_list.html","common");

            $allCount = 0;
            $pageBegin = ($pageIndex - 1) * $pageSize;
            $userOrderManageData = new UserOrderManageData();
            $arrUserOrderList = $userOrderManageData->GetList($siteId,$pageBegin,$pageSize,$allCount);

            $tagId = "user_order_list";
            if(count($arrUserOrderList) > 0){
                $styleNumber = 1;
                $pagerTemplate = Template::Load("pager/pager_style$styleNumber.html","common");
                $isJs = FALSE;
                $navUrl = "/default.php?secu=manage&mod=user_order&m=list&site_id=$siteId&p={0}&ps=$pageSize";
                $jsFunctionName = "";
                $jsParamList = "";
                $pagerButton = Pager::ShowPageButton($pagerTemplate, $navUrl, $allCount, $pageSize, $pageIndex, $styleNumber, $isJs, $jsFunctionName, $jsParamList);
                Template::ReplaceList($templateContent,$arrUserOrderList,$tagId);
                $templateContent = str_ireplace("{pagerButton}", $pagerButton, $templateContent);
            }else{
                Template::RemoveCustomTag($templateContent, $tagId);
                $templateContent = str_ireplace("{pagerButton}","", $templateContent);
            }
            $arrReplace = array(
                "{SiteId}" => $siteId
            );


            $templateContent = strtr($templateContent,$arrReplace);
            parent::ReplaceEnd($templateContent);
            return $templateContent;
        }else{
            return null;
        }
    }

    public function AsyncConfirmPay(){
        $userOrderPayId = Control::GetRequest("user_order_pay_id",0);
        if($userOrderPayId > 0){
            $confirmWay = Control::GetRequest("confirm_way","");
            $confirmPrice = Control::GetRequest("confirm_price",0);
            $confirmDate = date("Y-m-d");
            $manageUserId = Control::GetManageUserId();
            $manageUserName = Control::GetManageUserName();

            if($confirmWay == "" || $manageUserId == 0){
                return Control::GetRequest("jsonpcallback","").'({"result":-4})';
            }

            $userOrderPayManageData = new UserOrderPayManageData();
            $result = $userOrderPayManageData->ModifyConfirmPay($userOrderPayId,$confirmWay,$confirmPrice,$confirmDate,$manageUserId);

            $operateContent = 'Modify UserOrderPay,Get FORM:'.implode('|',$_GET).';\r\nResult::'.$result;
            self::CreateManageUserLog($operateContent);

            if($result > 0){
                return Control::GetRequest("jsonpcallback","").'({"result":"'.$result.'","confirm_way":"'.$confirmWay.'","confirm_price":"'
                    .$confirmPrice.'","confirm_date":"'.$confirmDate.'","manage_username":"'.$manageUserName.'"})';
            }else{
                return Control::GetRequest("jsonpcallback","").'({"result":-2})';
            }
        }else{
            return Control::GetRequest("jsonpcallback","").'({"result":-3})';
        }
    }

//    private function AsyncModifyState(){
//        $siteId = Control::GetRequest("site_id",0);
//        $userOrderId = Control::GetRequest("user_order_id",0);
//        if($siteId > 0 && $userOrderId >0){
//            $state = Control::GetRequest("state",0);
//            return Control::GetRequest("jsonpcallback","").'';
//        }else{
//            return Control::GetRequest("jsonpcallback","").'';
//        }
//    }

    private function GenUserOrderPayList(){
        $userOrderId = intval(Control::GetRequest("user_order_id",0));
        if($userOrderId > 0){
            $templateContent = Template::Load("user/user_order_pay_list.html","common");
            parent::ReplaceFirst($templateContent);

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

    private function GenDeleteUserOrderProduct(){
        $userOrderProductId = Control::GetRequest("user_order_pay_id",0);

        if($userOrderProductId > 0){

        }
    }
}