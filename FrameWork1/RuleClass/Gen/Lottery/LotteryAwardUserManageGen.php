<?php

/**
 * 获奖用户管理类
 *
 * @author zhangchi
 */
class LotteryAwardUserManageGen extends BaseManageGen implements IBaseManageGen {

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

        $lotterySetId = Control::GetRequest("lottery_set_id", 0);


        //////////////判断是否有操作权限///////////////////
        $lotteryManageData=new LotteryManageData();
        $channelManageData=new ChannelManageData();
        $lotterySetManageData=new LotterySetManageData();

        $lotteryId=$lotterySetManageData->GetLotteryId($lotterySetId);
        $channelId = $lotteryManageData->GetChannelId($lotteryId);
        $siteId=$channelManageData->GetSiteId($channelId,TRUE);
        $manageUserId = Control::GetManageUserId();
        $manageUserAuthority = new ManageUserAuthorityManageData();
        $can = $manageUserAuthority->CanManageAd($siteId, $channelId, $manageUserId);
        if ($can != 1) {
            Control::ShowMessage(Language::Load('custom_form', 3));
            return "";
        }


        $tempContent = Template::Load("lottery/lottery_award_user_list.html","common");


        $lotteryAwardUserManageData = new LotteryAwardUserManageData();
        $arrList = $lotteryAwardUserManageData->GetList($lotterySetId);



        $listName = "lottery_award_user_list";
        Template::ReplaceList($tempContent, $arrList, $listName);

        $tempContent = str_ireplace("{SiteId}", $lotterySetId, $tempContent);
        parent::ReplaceEnd($tempContent);
        return $tempContent;
    }
} 