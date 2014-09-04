<?php
/**
 * 后台管理 会员订单 生成类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_Gen_User
 * @Author yin
 */
class UserOrderManageGen extends BaseManageGen implements IBaseManageGen{
    /**
     * 引导方法
     * @return mixed|null|string 执行结果
     */
    public function Gen(){
        $result = "";
        $method = Control::GetRequest("m", "");
        switch($method){
            case "modify":
                $result = self::GenModify();
                break;
            case "list":
                $result = self::GenList();
                break;
        }
        $result = str_ireplace("{method}", $method, $result);
        return $result;
    }

    /**
     * 修改
     * @return mixed|string
     */
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
                    Control::GoUrl($_SERVER["PHP_SELF"]);
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

            if(count($arrUserOrderOne) > 0){
                Template::ReplaceOne($templateContent,$arrUserOrderOne);
            }else{
                parent::ReplaceWhenCreate($templateContent,$userOrderManageData->GetFields());
            }

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

    /**
     * 获取会员订单列表
     * @return mixed|null|string
     */
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
}