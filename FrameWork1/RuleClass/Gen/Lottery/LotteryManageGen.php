<?php

/**
 * 抽奖业务引擎类
 *
 * @author zhangchi
 */
class LotteryManageGen extends BaseManageGen implements IBaseManageGen {

    /**
     * 牵引生成方法(继承接口)
     * @return string
     */
    function Gen() {
        $result = "";
        $method = Control::GetRequest("m", "");
        switch ($method) {
            case "create":
                $result = self::GenCreate();
                break;
            case "modify":
                $result = self::GenModify();
                break;
            case "list":
                $result = self::GenList();
                break;
            case "async_modify_state":
                $result = self::AsyncModifyState();
                break;
            default:
                $result = self::GenList();
                break;
        }
        $replaceArr = array(
            "{method}" => $method
        );
        $result = strtr($result, $replaceArr);
        return $result;
    }

    public function GenList() {

        $channelId = Control::GetRequest("channel_id", 0);
        //////////////判断是否有操作权限///////////////////
        $channelManageData=new ChannelManageData();
        $siteId=$channelManageData->GetSiteId($channelId,TRUE);
        $manageUserId = Control::GetManageUserId();
        $manageUserAuthority = new ManageUserAuthorityManageData();
        $can = $manageUserAuthority->CanManageAd($siteId, $channelId, $manageUserId);
        if ($can != 1) {
            Control::ShowMessage(Language::Load('custom_form', 3));
            return "";
        }

        $tempContent = Template::Load("lottery/lottery_list.html","common");

        $pageSize = Control::GetRequest("ps", 20);
        $pageIndex = Control::GetRequest("p", 1);
        $searchKey = Control::GetRequest("search_key", "");
        $searchKey = urldecode($searchKey);

        $pageBegin = ($pageIndex - 1) * $pageSize;
        $allCount = 0;

        $lotteryManageData = new LotteryManageData();
        $arrList = $lotteryManageData->GetListPager($channelId,$pageBegin, $pageSize, $allCount, $searchKey);


        $listName = "lottery_list";
        if(count($arrList)>0){
            Template::ReplaceList($tempContent, $arrList, $listName);
            $styleNumber = 1;
            $pagerTemplate = Template::Load("pager/pager_style$styleNumber.html", "common");
            $isJs = FALSE;
            $navUrl = "default.php?secu=manage&mod=lottery&m=list&channel_id=$channelId&p={0}&ps=$pageSize";
            $jsFunctionName = "";
            $jsParamList = "";
            $pagerButton = Pager::ShowPageButton($pagerTemplate, $navUrl, $allCount, $pageSize, $pageIndex ,$styleNumber = 1, $isJs, $jsFunctionName, $jsParamList);

            $tempContent = str_ireplace("{PagerButton}", $pagerButton, $tempContent);
        }else{
            Template::RemoveCustomTag($tempContent, $listName);
            $tempContent = str_ireplace("{PagerButton}", Language::Load("document", 7), $tempContent);
        }


        Template::ReplaceList($tempContent, $arrList, $listName);

        //删除缓冲(没做modify暂时放这里)
        parent::DelAllCache();

        $tempContent = str_ireplace("{SiteId}", $siteId, $tempContent);
        $tempContent = str_ireplace("{ChannelId}", $channelId, $tempContent);
        parent::ReplaceEnd($tempContent);
        return $tempContent;
    }


    /**
     * 编辑
     * @return string 执行结果
     */
    private function GenModify(){
        $tempContent = Template::Load("lottery/lottery_deal.html","common");
        $resultJavaScript="";
        $lotteryId=Control::GetRequest("lottery_id",-1);
        $tabIndex=Control::GetRequest("tab_index",0);


        $lotteryManageData=new LotteryManageData();
        //////////////判断是否有操作权限///////////////////
        $channelManageData=new ChannelManageData();

        $channelId = $lotteryManageData->GetChannelId($lotteryId);
        $siteId=$channelManageData->GetSiteId($channelId,TRUE);
        $manageUserId = Control::GetManageUserId();
        $manageUserAuthority = new ManageUserAuthorityManageData();
        $can = $manageUserAuthority->CanManageAd($siteId, $channelId, $manageUserId);
        if ($can != 1) {
            Control::ShowMessage(Language::Load('custom_form', 3));
            return "";
        }

        parent::ReplaceFirst($tempContent);

        if (intval($lotteryId) > 0) {
            if (!empty($_POST)) {
                $modifySuccess = $lotteryManageData->Modify($_POST, $lotteryId);

                //记入操作log
                $operateContent = "Modify lottery：LotteryId：" . $lotteryId .",POST FORM:".implode("|",$_POST).";\r\nResult:". $modifySuccess;
                self::CreateManageUserLog($operateContent);

                if ($modifySuccess > 0) {

                    Control::ShowMessage(Language::Load('lottery', 1));//提交成功!
                    $closeTab = Control::PostRequest("CloseTab",0);
                    if($closeTab == 1){
                        //$resultJavaScript .= Control::GetCloseTab();
                        Control::GoUrl("/default.php?secu=manage&mod=lottery&m=list&channel_id=$channelId&tab_index=$tabIndex");
                    }else{
                        Control::GoUrl($_SERVER["PHP_SELF"]."?".$_SERVER['QUERY_STRING']);
                    }



                }else{
                    $resultJavaScript .= Control::GetJqueryMessage(Language::Load('lottery', 2));//提交失败!插入或修改数据库错误！
                }


            }

            $arrOneLottery = $lotteryManageData->GetOne($lotteryId);
            if(!empty($arrOneLottery)){
                Template::ReplaceOne($tempContent, $arrOneLottery);
            }else{
                $resultJavaScript .= Control::GetJqueryMessage(Language::Load('lottery', 4));//原有数据获取失败！请谨慎修改！
            }



            $replaceArr = array(
                "{TabIndex}" => $tabIndex,
                "{display}" => "none"
            );
            $tempContent = strtr($tempContent, $replaceArr);
            $tempContent = str_ireplace("{ChannelId}", $channelId, $tempContent);


            //替换掉{s XXX}的内容
            $patterns = '/\{s_(.*?)\}/';
            $tempContent = preg_replace($patterns, "", $tempContent);
        } else {
            $resultJavaScript .= Control::GetJqueryMessage(Language::Load('lottery', 5));//id错误！;
        }
        parent::ReplaceEnd($tempContent);
        $tempContent = str_ireplace("{ResultJavascript}", $resultJavaScript, $tempContent);
        return $tempContent;
    }



    /**
     * 新增
     * @return string 执行结果
     */
    private function GenCreate(){
        $tempContent = Template::Load("lottery/lottery_deal.html","common");
        $resultJavaScript="";
        $channelId=Control::GetRequest("channel_id",-1);
        $tabIndex=Control::GetRequest("tab_index",0);


        $lotteryManageData=new LotteryManageData();
        //////////////判断是否有操作权限///////////////////
        $channelManageData=new ChannelManageData();
        $siteId=$channelManageData->GetSiteId($channelId,TRUE);
        $manageUserId = Control::GetManageUserId();
        $manageUserAuthority = new ManageUserAuthorityManageData();
        $can = $manageUserAuthority->CanManageAd($siteId, $channelId, $manageUserId);
        if ($can != 1) {
            Control::ShowMessage(Language::Load('custom_form', 3));
            return "";
        }

        parent::ReplaceFirst($tempContent);

        if (intval($channelId) > 0) {
            if (!empty($_POST)) {
                $newId = $lotteryManageData->Create($_POST,$channelId);

                //记入操作log
                $operateContent = "Create lottery：ChannelId：" . $channelId .",POST FORM:".implode("|",$_POST).";\r\nResult:". $newId;
                self::CreateManageUserLog($operateContent);

                if ($newId > 0) {

                    Control::ShowMessage(Language::Load('lottery', 1));//提交成功!
                    $closeTab = Control::PostRequest("CloseTab",0);
                    if($closeTab == 1){
                        //$resultJavaScript .= Control::GetCloseTab();
                        Control::GoUrl("/default.php?secu=manage&mod=lottery&m=list&channel_id=$channelId&tab_index=$tabIndex");
                    }else{
                        Control::GoUrl($_SERVER["PHP_SELF"]."?".$_SERVER['QUERY_STRING']);
                    }



                }else{
                    $resultJavaScript .= Control::GetJqueryMessage(Language::Load('lottery', 2));//提交失败!插入或修改数据库错误！
                }


            }




            $replaceArr = array(
                "{TabIndex}" => $tabIndex,
                "{display}" => "inline"
            );
            $tempContent = strtr($tempContent, $replaceArr);
            $tempContent = str_ireplace("{ChannelId}", $channelId, $tempContent);

            $arrField = $lotteryManageData->GetFields();
            parent::ReplaceWhenCreate($tempContent, $arrField);

            //去掉s开头的标记 {s_xxx_xxx}
            $patterns = '/\{s_(.*?)\}/';
            $tempContent = preg_replace($patterns, "", $tempContent);
            //去掉c开头的标记 {c_xxx}
            $patterns = '/\{c_(.*?)\}/';
            $tempContent = preg_replace($patterns, "", $tempContent);
            //去掉r开头的标记 {r_xxx_xxx}
            $patterns = '/\{r_(.*?)\}/';
            $tempContent = preg_replace($patterns, "", $tempContent);

        } else {
            $resultJavaScript .= Control::GetJqueryMessage(Language::Load('lottery', 5));//id错误！;
        }
        parent::ReplaceEnd($tempContent);
        $tempContent = str_ireplace("{ResultJavascript}", $resultJavaScript, $tempContent);
        return $tempContent;
    }


    /**
     * 修改状态
     * @return string 修改结果
     */
    private function AsyncModifyState()
    {

        $lotteryId = Control::GetRequest("lottery_id", -1);
        $lotteryManageData=new LotteryManageData();
        //////////////判断是否有操作权限///////////////////
        $channelManageData=new ChannelManageData();

        $channelId = $lotteryManageData->GetChannelId($lotteryId);
        $siteId=$channelManageData->GetSiteId($channelId,TRUE);
        $manageUserId = Control::GetManageUserId();
        $manageUserAuthority = new ManageUserAuthorityManageData();
        $can = $manageUserAuthority->CanManageAd($siteId, $channelId, $manageUserId);
        if ($can != 1) {
            Control::ShowMessage(Language::Load('custom_form', 3));
            return "";
        }


        $state = Control::GetRequest("state",-1);
        if ($lotteryId > 0) {
            $result = $lotteryManageData->ModifyState($lotteryId,$state);
            //加入操作日志
            $operateContent = 'ModifyState lottery,Get FORM:' . implode('|', $_GET) . ';\r\nResult:site_ad:' . $result;
            self::CreateManageUserLog($operateContent);
        } else {
            $result = -1;
        }
        return Control::GetRequest("jsonpcallback","") . '({"result":"'.$result.'"})';
    }
} 