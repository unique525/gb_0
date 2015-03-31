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
            case "list_for_search":
                $result = self::GenListForSearch();
                break;
            case "print":
                $result = self::GenPrint();
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
        $userOrderId = intval(Control::GetRequest("user_order_id",0));
        $siteId = intval(Control::GetRequest("site_id",0));
        $resultJavaScript = "";

        if($userOrderId > 0 && $siteId > 0){
            $templateContent = Template::Load("user/user_order_deal.html","common");
            parent::ReplaceFirst($templateContent);

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

                $oldState = $userOrderManageData->GetState($userOrderId, false);


                $result = $userOrderManageData->Modify($httpPostData,$userOrderId,$siteId);
                //加入操作日志
                if($result > 0){
                    $newState = intval(Control::PostRequest("f_State",0));
                    if (
                        $oldState != UserOrderData::STATE_CLOSE
                        && $newState == UserOrderData::STATE_CLOSE){

                        /**
                         * @TODO 关闭交易时，要恢复库存
                         */
                        $userOrderProductManageData = new UserOrderProductManageData();
                        $arrUserOrderProductList = $userOrderProductManageData->GetList($userOrderId,$siteId);
                        $productPriceManageData = new ProductPriceManageData();
                        for($i=0;$i<count($arrUserOrderProductList);$i++){
                            $productPriceId = $arrUserOrderProductList[$i]["ProductPriceId"];
                            $saleCount = $arrUserOrderProductList[$i]["SaleCount"];
                            $productPriceManageData->AddProductCount($productPriceId,$saleCount);
                        }
                    }

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
            $pageSize = Control::GetRequest("ps",27);
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

    private function GenListForSearch(){
        $siteId = Control::GetRequest("site_id",0);

        if($siteId > 0){
            $pageIndex = Control::GetRequest("p",1);
            $pageSize = Control::GetRequest("ps",27);

            $templateContent = Template::Load("user/user_order_list.html","common");

            $allCount = 0;
            $pageBegin = ($pageIndex - 1) * $pageSize;

            $userOrderNumber = Control::GetRequest("user_order_number","");
            $state = Control::GetRequest("state",0);
            $beginDate = Control::GetRequest("begin_date","");
            $endDate = Control::GetRequest("end_date","");
            $userOrderManageData = new UserOrderManageData();
            $arrUserOrderList = $userOrderManageData->GetListForSearch($siteId,$userOrderNumber,$state,$beginDate,$endDate,$pageBegin,$pageSize,$allCount);

            $tagId = "user_order_list";
            if(count($arrUserOrderList) > 0){
                $styleNumber = 1;
                $pagerTemplate = Template::Load("pager/pager_style$styleNumber.html","common");
                $isJs = FALSE;
                $navUrl = "/default.php?secu=manage&mod=user_order&m=list_for_search&site_id=$siteId"
                    ."&user_order_number=".$userOrderNumber."&state=".$state."&begin_date=".$beginDate
                    ."&end_date=".$endDate."&p={0}&ps=$pageSize";
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

    private function GenPrint(){
        $userOrderId = Control::GetRequest("user_order_id",0);
        $siteId = Control::GetRequest("site_id",0);

        if($userOrderId > 0){
            $templateContent = Template::Load("user/user_order_print.html","common");
            parent::ReplaceFirst($templateContent);

            $userOrderManageData = new UserOrderManageData();
            $userOrderProductManageData = new UserOrderProductManageData();
            $userReceiveInfoManageData = new UserReceiveInfoManageData();

            $arrUserOrderOne = $userOrderManageData->GetOne($userOrderId,$siteId);
            $arrUserOrderProductList = $userOrderProductManageData->GetList($userOrderId,$siteId);

            $tagUserOrderProductListId = "user_order_product_list";
            if(count($arrUserOrderProductList) > 0){
                Template::ReplaceList($templateContent,$arrUserOrderProductList,$tagUserOrderProductListId);
            }

            if(intval($arrUserOrderOne["UserId"]) > 0){
                $userReceiveInfoId = $arrUserOrderOne["UserReceiveInfoId"];
                $arrUserReceiveInfoOne = $userReceiveInfoManageData->GetOne($userReceiveInfoId);
                if(count($arrUserReceiveInfoOne) > 0){
                    Template::ReplaceOne($templateContent,$arrUserReceiveInfoOne);
                }
            }

            if(count($arrUserOrderOne) > 0){
                Template::ReplaceOne($templateContent,$arrUserOrderOne);
            }

            parent::ReplaceEnd($templateContent);
            return $templateContent;
        }else{
            return null;
        }
    }
}