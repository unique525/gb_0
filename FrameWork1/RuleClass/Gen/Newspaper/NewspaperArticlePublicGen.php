<?php
/**
 * 公开访问 电子报文章 生成类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_Gen_Newspaper
 * @author zhangchi
 */
class NewspaperArticlePublicGen extends BasePublicGen {

    /**
     * 引导方法
     * @return string 返回执行结果
     */
    public function GenPublic() {
        $result = "";
        $action = Control::GetRequest("a", "");
        switch ($action) {
            case "detail":
                $result = self::GenDetail();
                break;
            case "list":
                $result = self::GenList();
                break;

        }
        return $result;
    }

    private function GenList() {
        $newspaperPageId = Control::GetRequest("newspaper_page_id", 0);
        $templateContent = "";

        if($newspaperPageId>0){
            $templateFileUrl = "newspaper/newspaper_article_list.html";
            $templateName = "default";
            $templatePath = "front_template";
            $templateContent = Template::Load($templateFileUrl, $templateName, $templatePath);
            $templateContent = str_ireplace("{NewspaperPageId}", $newspaperPageId, $templateContent);
            parent::ReplaceFirst($templateContent);


            $templateContent = parent::ReplaceTemplate($templateContent);


            parent::ReplaceEnd($templateContent);

        }
        return $templateContent;
    }

    private function GenDetail(){
        $newspaperArticleId = Control::GetRequest("newspaper_article_id", 0);
        $templateContent = "";

        if($newspaperArticleId>0){
            $templateFileUrl = "newspaper/newspaper_article_detail.html";
            $templateName = "default";
            $templatePath = "front_template";
            $templateContent = Template::Load($templateFileUrl, $templateName, $templatePath);
            $templateContent = str_ireplace("{NewspaperArticleId}", $newspaperArticleId, $templateContent);
            parent::ReplaceFirst($templateContent);

            $templateContent = parent::ReplaceTemplate($templateContent);


            $newspaperArticlePublicData = new NewspaperArticlePublicData();
            $arrOne = $newspaperArticlePublicData->GetOne($newspaperArticleId);

            $arrOne["NewspaperArticleContent"] =
                str_ireplace("\n","<br /><br />", $arrOne["NewspaperArticleContent"]);


            Template::ReplaceOne($templateContent, $arrOne);

            $templateContent = parent::ReplaceTemplate($templateContent);


            parent::ReplaceEnd($templateContent);

        }
        return $templateContent;
    }
} 