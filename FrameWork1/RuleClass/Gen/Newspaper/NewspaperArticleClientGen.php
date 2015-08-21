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

} 