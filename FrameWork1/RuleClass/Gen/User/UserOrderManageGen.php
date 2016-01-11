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
            case "async_create_offline_order":
                $result = self::AsyncCreateOfflineOrder();
                break;
            case "async_renewal_offline_order_for_newspaper":
                $result = self::AsyncRenewalOfflineOrderForNewspaper();
                break;

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
            case "export_excel":
                $result = self::GenExportExcel();
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
            $searchKey = Control::GetRequest("search_key","");
            $userOrderManageData = new UserOrderManageData();
            $arrUserOrderList = $userOrderManageData->GetListForSearch($siteId,$userOrderNumber,$state,$beginDate,$endDate,$pageBegin,$pageSize,$allCount,$searchKey);

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


    private function GenExportExcel(){
        $beginDate = Control::GetRequest("begin_date", "");
        $endDate = Control::GetRequest("end_date", "");
        $siteId = Control::GetRequest("site_id",0);

        if (strtotime($beginDate) <= strtotime($endDate)) {
            $userOrderManageData = new UserOrderManageData();
            $arrUserOrderListForExcel = $userOrderManageData->GetListForExportExcel($beginDate,$endDate,$siteId);

            $objPHPExcel = new PHPExcel();
            $objPHPExcel->setActiveSheetIndex(0);
            $objActSheet = $objPHPExcel->getActiveSheet(0);


            $key = ord("A");
            $arrUserOrderListHeader = array(
                "订单ID",
                "订单创建时间",
                "商品ID",
                "商品标题",
                "商品价格",
                "购买总数量",
                "小计",
                "买家实际支付金额",
                "订单状态",
                "商家编码",
                "买家会员名",
                "收货人姓名",
                "联系电话",
                "收货人地区",
                "收货地址",
                "订单付款时间"
            );
            for ($i = 0; $i < count($arrUserOrderListHeader); $i++) {
                $column = chr($key);
                $objActSheet->setCellValue($column . '1', $arrUserOrderListHeader[$i]);
                $objActSheet->getColumnDimension($column)->setAutoSize(true);
                $key ++;
            }

            $column_content = 2;
            for ($j = 0; $j < count($arrUserOrderListForExcel); $j++) {
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue("A" . strval($column_content), $arrUserOrderListForExcel[$j]["UserOrderId"]);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue("B" . strval($column_content), $arrUserOrderListForExcel[$j]["CreateDate"]);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue("C" . strval($column_content), $arrUserOrderListForExcel[$j]["ProductId"]);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue("D" . strval($column_content), $arrUserOrderListForExcel[$j]["ProductName"]);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue("E" . strval($column_content), $arrUserOrderListForExcel[$j]["ProductPrice"]);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue("F" . strval($column_content), $arrUserOrderListForExcel[$j]["SaleCount"]);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue("G" . strval($column_content), $arrUserOrderListForExcel[$j]["SubTotal"]);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue("H" . strval($column_content), $arrUserOrderListForExcel[$j]["PayPrice"]);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue("I" . strval($column_content), $arrUserOrderListForExcel[$j]["State"]);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue("J" . strval($column_content), $arrUserOrderListForExcel[$j]["ProductTag"]);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue("K" . strval($column_content), $arrUserOrderListForExcel[$j]["UserName"]);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue("L" . strval($column_content), $arrUserOrderListForExcel[$j]["ReceivePersonName"]);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue("N" . strval($column_content), $arrUserOrderListForExcel[$j]["Mobile"]);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue("M" . strval($column_content), $arrUserOrderListForExcel[$j]["District"]);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue("O" . strval($column_content), $arrUserOrderListForExcel[$j]["Address"]);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue("P" . strval($column_content), $arrUserOrderListForExcel[$j]["PayDate"]);

                $column_content++;
            }

            $fileName = $beginDate."--".$endDate."-".time()."test.xls";
            $fileName = iconv("utf-8", "gb2312", $fileName);
            //将输出重定向到一个客户端web浏览器(Excel2007)
            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
            header('Pragma:public');
            header('Expires:0');
            header('Cache-Control:must-revalidate,post-check=0,pre-check=0');
            header('Content-Type:application/force-download');
            header('Content-Type:application/vnd.ms-excel');
            header('Content-Type:application/octet-stream');
            header('Content-Type:application/download');
            header('Content-Disposition:attachment;filename=' . $fileName);
            header('Content-Transfer-Encoding:binary');
            $objWriter->save('php://output');
        }
        return "";
    }


    /**
     * 新增线下订单
     * @return int
     */
    private function AsyncCreateOfflineOrder(){
        $result=-1;
        $siteId=Control::GetRequest("site_id",0);
        $manageUserId=Control::GetManageUserId();

        /** 检查权限 **/
        $manageUserAuthorityManageData=new ManageUserAuthorityManageData();
        //$can=$manageUserAuthorityManageData->CanUserOrder
        //if(!$can){
        //    return -10;//无权限
        //}


        $userName = Control::PostOrGetRequest("user_name","");
        $realName = Control::PostOrGetRequest("real_name","");
        $userManageData=new UserManageData;
        $userOrderNewspaperManageData=new UserOrderNewspaperManageData();
        if ($userName != ""&&$siteId>0)
        {
            //检测用户名是否重复
            $newUserId=0;
            $repeatUserId=$userManageData->CheckRepeat($userName);
            if ($repeatUserId <= 0)
            {   //生成新用户
                $httpPostData=array();
                $httpPostData["f_UserName"]=$userName;
                $httpPostData["f_UserPass"]="123654888";
                $newUserId=$userManageData->Create($httpPostData,$siteId);
                if($newUserId>0){
                    //生成用户信息表
                    $userInfoManageData=new UserInfoManageData;
                    $userInfoId=$userInfoManageData->CreateForOfflineOrder($newUserId,$realName);
                    if($userInfoId<0){
                        $result=-3;//用户信息新建失败
                        return Control::GetRequest("jsonpcallback","").'({"result":"'.$result.'"})';
                    }

                    //挂接用户到对应用户组
                    $userRoleManageData=new UserRoleManageData();
                    $userGroupId = 12; //线下支付电子报
                    $userRoleId=$userRoleManageData->CreateOrModify($newUserId,$userGroupId,$siteId);
                    if($userRoleId<0){
                        $result= -4;//用户组挂接失败
                        return Control::GetRequest("jsonpcallback","").'({"result":"'.$result.'"})';
                    }
                }else{
                    $result = -2; //用户新建失败
                    return Control::GetRequest("jsonpcallback","").'({"result":"'.$result.'"})';
                }
            }else{
                //已存在用户,检查是否已有报纸订单
                $userOrderNewspaperArray=$userOrderNewspaperManageData->GetOrderOfUser($repeatUserId);
                if(count($userOrderNewspaperArray)>0){

                    $result = 10; //已有订单
                    return Control::GetRequest("jsonpcallback","").
                    '({"result":"'.$result.'","result_data":'.json_encode($userOrderNewspaperArray).'})';
                }
            }

            //插入用户订单表
            $userOrderTableType=1;//电子报类型订单
            $userOrderNumber = UserOrderData::GenUserOrderNumber();
            $allPrice = 72.00;//一个订单的总价
            $userOrderState = 20;//已付款，未发货
            $userOrderManageData=new UserOrderManageData();
            $createDate=strval(date('Y-m-d H:i:s', time()));
            $userOrderId=$userOrderManageData->CreateForOfflineOrder($userOrderNumber,$createDate,$newUserId,$allPrice,$userOrderState,$siteId,$userOrderTableType);
            if($userOrderId<0){

                $result = -11;//获取订单失败
                return Control::GetRequest("jsonpcallback","").'({"result":"'.$result.'"})';
            }


            //插入报纸订单表
            $channelId = 15;//电子报所在频道id
            $salePrice = 72.00;//订单支付金额
            $beginDate = "2016-1-1";
            $endDate = "2016-12-31";
            $userOrderNewspaperId=$userOrderNewspaperManageData->CreateForOfflineOrder($siteId,$channelId,$userOrderId,$newUserId,$createDate,$salePrice,$beginDate,$endDate);
            if($userOrderNewspaperId<0){
                $result = -12;//获取报纸订单失败
                return Control::GetRequest("jsonpcallback","").'({"result":"'.$result.'"})';
            }

            //插入支付表
            $httpPostData=array();
            $httpPostData["f_PayPrice"]=72.00;//实际支付金额;
            $httpPostData["f_PayDate"]=$createDate;//"2015-12-31";//;
            $httpPostData["f_PayWay"]="线下统一支付";//;
            $userOrderPayManageData=new UserOrderPayManageData();
            $userOrderPayId=$userOrderPayManageData->Create($httpPostData,$userOrderId,$manageUserId);
            if($userOrderPayId<0){
                $result = -13;//获取支付id失败
                return Control::GetRequest("jsonpcallback","").'({"result":"'.$result.'"})';
            }
        }
        else{
            $result = -1; //user id错误
            return Control::GetRequest("jsonpcallback","").'({"result":"'.$result.'"})';
        }
        $result = 1;
        return Control::GetRequest("jsonpcallback","").'({"result":"'.$result.'"})';
    }

    /**
     * 更新报纸续期
     * @return int
     */
    private function AsyncRenewalOfflineOrderForNewspaper(){
        $result=-1;
        $userId = Control::PostOrGetRequest("user_id","");
        $siteId = Control::PostOrGetRequest("site_id","");
        $manageUserId = Control::PostOrGetRequest("manage_user_id","");
        $userOrderNewspaperId = Control::PostOrGetRequest("user_order_newspaper_id","");
        if($userId>0&&$userOrderNewspaperId>0){

            //插入用户订单表
            $userOrderTableType=1;//电子报类型订单
            $userOrderNumber = UserOrderData::GenUserOrderNumber();
            $allPrice = 72.00;//一个订单的总价
            $userOrderState = 20;//已付款，未发货
            $userOrderManageData=new UserOrderManageData();
            $createDate=strval(date('Y-m-d H:i:s', time()));
            $userOrderId=$userOrderManageData->CreateForOfflineOrder($userOrderNumber,$createDate,$userId,$allPrice,$userOrderState,$siteId,$userOrderTableType);
            if($userOrderId<0){

                $result = -11;//获取订单失败
                return Control::GetRequest("jsonpcallback","").'({"result":"'.$result.'"})';
            }

            //插入支付表
            $httpPostData=array();
            $httpPostData["f_PayPrice"]=72.00;//实际支付金额;
            $httpPostData["f_PayDate"]=$createDate;//"2015-12-31";//;
            $httpPostData["f_PayWay"]="线下统一支付";//;
            $userOrderPayManageData=new UserOrderPayManageData();
            $userOrderPayId=$userOrderPayManageData->Create($httpPostData,$userOrderId,$manageUserId);
            if($userOrderPayId<0){

                //失败 删除新建的订单
                $httpPostData=array();
                $httpPostData["f_State"]=100;
                $userOrderManageData->Modify($httpPostData,$userOrderId,$siteId);
                $result = -13;//获取支付id失败
                return Control::GetRequest("jsonpcallback","").'({"result":"'.$result.'"})';
            }

            //更新报纸订单表

            $userOrderNewspaperManageData=new UserOrderNewspaperManageData();
            $userOrderNewspaperInfoArray=$userOrderNewspaperManageData->GetInfoForUpdate($userOrderNewspaperId);
            $lastEndDate=$userOrderNewspaperInfoArray["EndDate"];
            $lastEndDateArray=explode("-",$lastEndDate);
            if(!empty($lastEndDateArray)){
                $year=$lastEndDateArray[0]+1; //加一年
                $salePrice = 72.00+$userOrderNewspaperInfoArray["SalePrice"];//订单支付金额增加
                $endDate = $year."-12-31";
                $result=$userOrderNewspaperManageData->UpdateOrderForRenewal($userOrderNewspaperId,$userOrderId,$salePrice,$endDate);
            }
            if($result<0){
                //失败 删除新建的订单
                $httpPostData=array();
                $httpPostData["f_State"]=100;
                $userOrderManageData->Modify($httpPostData,$userOrderId,$siteId);
                $userOrderPayManageData->Modify($httpPostData,$userOrderPayId);
                $result = -12;//获取报纸订单失败
                return Control::GetRequest("jsonpcallback","").'({"result":"'.$result.'"})';
            }
        }
        return Control::GetRequest("jsonpcallback","").'({"result":"'.$result.'"})';
    }


}