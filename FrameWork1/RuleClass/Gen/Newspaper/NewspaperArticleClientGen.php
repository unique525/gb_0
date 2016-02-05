<?php
/**
 * 客户端 电子报文章 生成类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_Gen_Newspaper
 * @author zhangchi
 */
class NewspaperArticleClientGen extends BaseClientGen implements IBaseClientGen {

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
     * @param $siteId
     * @param $userId
     * @param $newspaperArticleId
     * @return bool
     */
    function IsAuthorizedUser($siteId, $userId, $newspaperArticleId)
    {
        $result=false;
        if ($userId > 0){
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
                $result=true;
            }
        }
        return $result;
    }
} 