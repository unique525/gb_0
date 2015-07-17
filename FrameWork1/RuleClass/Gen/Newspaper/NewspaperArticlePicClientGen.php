<?php
/**
 * 客户端 电子报文章图片 生成类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_Gen_Newspaper
 * @author zhangchi
 */
class NewspaperArticlePicClientGen extends BaseClientGen implements IBaseClientGen {

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

        $newspaperArticleId = intval(Control::GetRequest("newspaper_article_id", 0));

        if($newspaperArticleId>0){

            $newspaperArticlePicClientData = new NewspaperArticlePicClientData();
            $arrList = $newspaperArticlePicClientData->GetList(
                $newspaperArticleId
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

        return '{"result_code":"'.$resultCode.'","newspaper_article_pic":{"newspaper_article_pic_list":' . $result . '}}';
    }

} 