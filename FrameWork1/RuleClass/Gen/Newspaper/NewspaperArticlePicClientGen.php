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

            case "new":
                $result = self::GenNew();
                break;

        }
        $result = str_ireplace("{function}", $function, $result);
        return $result;
    }

} 