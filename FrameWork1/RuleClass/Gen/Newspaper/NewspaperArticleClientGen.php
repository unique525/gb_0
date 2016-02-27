<?php
/**
 * 客户端 电子报文章 生成类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_Gen_Newspaper
 * @author zhangchi
 */
class NewspaperArticleClientGen extends NewspaperBaseClientGen implements IBaseClientGen {

    /**
     * 引导方法
     * @return string 返回执行结果
     */
    public function GenClient(){
        $result = "";
        $function = Control::GetRequest("f", "");
        switch ($function) {

            case "list":
                $result = self::GenList();
                break;
            case "list_by_newspaper":
                $result = self::GenListByNewspaper();
                break;
            case "get_one":
                $result = self::GetOne();
                break;

            case "check_is_bought":
                $result = self::CheckIsBought();
                break;
            case "get_one_by_right_check":
                $result = self::GetOneByRightCheck();
                break;

        }
        $result = str_ireplace("{function}", $function, $result);
        return $result;
    }

    /**
 * 返回列表数据集
 * @return string
 */
    public function GenList(){

        $result = "[{}]";

        $newspaperPageId = intval(Control::GetRequest("newspaper_page_id", 0));

        if($newspaperPageId>0){

            $newspaperArticleClientData = new NewspaperArticleClientData();
            $arrList = $newspaperArticleClientData->GetList(
                $newspaperPageId
            );
            if (count($arrList) > 0) {
                $resultCode = 1;
                $result = Format::FixJsonEncode($arrList);
            }
            else{
                $resultCode = -2;
            }
        }
        else{
            $resultCode = -1;
        }

        return '{"result_code":"'.$resultCode.'","newspaper_article":{"newspaper_article_list":' . $result . '}}';
    }

    /**
     * 返回列表数据集
     * @return string
     */
    public function GenListByNewspaper(){

        $result = "[{}]";

        $newspaperId = intval(Control::GetRequest("newspaper_id", 0));

        if($newspaperId>0){

            $newspaperArticleClientData = new NewspaperArticleClientData();
            $arrList = $newspaperArticleClientData->GetListByNewspaperId(
                $newspaperId,
                true
            );
            if (count($arrList) > 0) {
                $resultCode = 1;
                $result = Format::FixJsonEncode($arrList);
            }
            else{
                $resultCode = -2;
            }
        }
        else{
            $resultCode = -1;
        }

        return '{"result_code":"'.$resultCode.'","newspaper_article":{"newspaper_article_list":' . $result . '}}';
    }

    private function GetOne(){

        $result = "[{}]";

        $newspaperArticleId = intval(Control::PostOrGetRequest("newspaper_article_id",0));


        if(
            $newspaperArticleId > 0
        ){
            $newspaperArticleClientData = new NewspaperArticleClientData();
            $arrOne = $newspaperArticleClientData->GetOne($newspaperArticleId, TRUE);

            $result = Format::FixJsonEncode($arrOne);
            $resultCode = 1; //

        }else{
            $resultCode = -6; //参数错误;
        }


        return '{"result_code":"' . $resultCode . '","newspaper_article":' . $result . '}';


    }

    /**
     * 根据用户判断是否能看报纸
     * @return string
     */
    function CheckIsBought()
    {
        $result = "[{}]";

        $userId = parent::GetUserId();

        if ($userId <= 0) {
            $resultCode = $userId; //会员检验失败,参数错误
        } else {
            $siteId = Control::PostOrGetRequest("site_id", 0);
            $newspaperArticleId = intval(Control::PostOrGetRequest("NewspaperArticleId", 0));

            if ($siteId > 0
                && $newspaperArticleId > 0

            ) {
                $resultCode = 0;
                //是否购买了报纸
                $newspaperArticleClientData = new NewspaperArticleClientData();
                $newspaperPageClientData = new NewspaperPageClientData();
                $newspaperClientData = new NewspaperClientData();

                $newspaperPageId = $newspaperArticleClientData->GetNewspaperPageId($newspaperArticleId, true);
                $newspaperId = $newspaperPageClientData->GetNewspaperId($newspaperPageId, true);

                $channelId=$newspaperClientData->GetChannelId($newspaperId, true);
                $publishDate=$newspaperClientData->GetPublishDate($newspaperId, true);
                $userOrderNewspaperClientData = new UserOrderNewspaperClientData();
                $isBuy = $userOrderNewspaperClientData->CheckIsBoughtInTimeByChannelId(
                    $userId,
                    $channelId,
                    $publishDate);
                //可以直接免费看付费报纸
                $canExplore = parent::GetUserPopedomBoolValue(
                    $siteId,
                    $userId,
                    UserPopedomData::UserCanExploreMustPayNewspaper);
                //购买了报纸或有权限可以直接免费看报纸
                if ($isBuy>0 || $canExplore){
                    $resultCode = 1;
                    $arrOne = $newspaperArticleClientData->GetOne($newspaperArticleId,true);
                    $result = Format::FixJsonEncode($arrOne);
                }

            } else {
                $resultCode = -5;//参数不正确
            }

        }
        return '{"result_code":"' . $resultCode . '","result":' . $result . '}';

    }

    /**
     * 根据条件判断用户是否能看报纸
     * @return string
     */
    function GetOneByRightCheck()
    {
        $result = "[{}]";
        $resultCode = 0;
        $siteId = Control::PostOrGetRequest("site_id", 0);
        $newspaperArticleId = intval(Control::PostOrGetRequest("newspaper_article_id", 0));
        if ($newspaperArticleId > 0) {
            $newspaperArticleClientData = new NewspaperArticleClientData();
            $arrOne = $newspaperArticleClientData->GetOne($newspaperArticleId, true);
            $result = Format::FixJsonEncode($arrOne);
        }
        if (self::IsFreeReadByNewspaperArticleId($newspaperArticleId)) {
            $resultCode = 1;
        } else {
            $userId = parent::GetUserId();
            //结果标志判断
            if ($userId <= 0) {
                $resultCode = $userId; //会员检验失败,参数错误
            } else {
                if ($siteId > 0 && $newspaperArticleId > 0) {
                    $isAuthorizedUser = self::IsAuthorizedUser($siteId, $userId, $newspaperArticleId);
                    if ($isAuthorizedUser) {
                        $resultCode = 1;
                    }
                } else {
                    $resultCode = -5; //参数不正确
                }
            }
        }
        return '{"result_code":"' . $resultCode . '","result":' . $result . '}';
    }
} 